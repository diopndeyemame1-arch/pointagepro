<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Models/EtudiantModel.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: index.php?page=login');
    exit;
}

$studentStmt = $pdo->prepare("
    SELECT u.*, d.name AS department, c.name AS cohort
    FROM users u
    LEFT JOIN departments d ON d.id = u.department_id
    LEFT JOIN cohorts c ON c.id = u.cohort_id
    WHERE u.id = :id
    LIMIT 1
");
$studentStmt->execute(['id' => $userId]);
$student = $studentStmt->fetch(PDO::FETCH_ASSOC) ?: [];

$statsStmt = $pdo->prepare("
    SELECT
        COUNT(*) FILTER (WHERE status = 'present') AS present,
        COUNT(*) FILTER (WHERE status = 'retard') AS late,
        COUNT(*) FILTER (WHERE status = 'absent') AS absent
    FROM attendances
    WHERE user_id = :user_id
");
$statsStmt->execute(['user_id' => $userId]);
$attendanceStats = $statsStmt->fetch(PDO::FETCH_ASSOC) ?: ['present' => 0, 'late' => 0, 'absent' => 0];

$leaveStmt = $pdo->prepare("SELECT COUNT(*) FROM absences WHERE user_id = :user_id");
$leaveStmt->execute(['user_id' => $userId]);
$leaveCount = (int)$leaveStmt->fetchColumn();

$todayStmt = $pdo->prepare("
    SELECT *
    FROM attendances
    WHERE user_id = :user_id
    AND date = CURRENT_DATE
    LIMIT 1
");
$todayStmt->execute(['user_id' => $userId]);
$todayAttendance = $todayStmt->fetch(PDO::FETCH_ASSOC);

$scheduleStmt = $pdo->prepare("
    SELECT day, start_time, end_time
    FROM cohort_schedules
    WHERE cohort_id = :cohort_id
    ORDER BY start_time ASC
");
$scheduleStmt->execute(['cohort_id' => $student['cohort_id'] ?? 0]);
$schedules = $scheduleStmt->fetchAll(PDO::FETCH_ASSOC);

$days = [
    1 => 'Lundi',
    2 => 'Mardi',
    3 => 'Mercredi',
    4 => 'Jeudi',
    5 => 'Vendredi',
    6 => 'Samedi',
    7 => 'Dimanche',
];

$today = new DateTimeImmutable('today');
$todayLabel = $days[(int)$today->format('N')] . ' ' . $today->format('d/m/Y');
$nowTime = date('H:i:s');
$nextSchedule = null;

for ($offset = 0; $offset < 7 && $nextSchedule === null; $offset++) {
    $date = $today->modify('+' . $offset . ' day');
    $dayName = $days[(int)$date->format('N')];

    foreach ($schedules as $schedule) {
        if (($schedule['day'] ?? '') !== $dayName) {
            continue;
        }

        if ($offset === 0 && ($schedule['start_time'] ?? '00:00:00') < $nowTime) {
            continue;
        }

        $nextSchedule = [
            'day' => $dayName,
            'date' => $date,
            'start_time' => substr($schedule['start_time'], 0, 5),
            'end_time' => substr($schedule['end_time'], 0, 5),
        ];
        break;
    }
}

$studentName = trim(($student['firstname'] ?? 'Etudiant') . ' ' . ($student['lastname'] ?? ''));
$presentCount = (int)($attendanceStats['present'] ?? 0);
$lateCount = (int)($attendanceStats['late'] ?? 0);
$absentCount = (int)($attendanceStats['absent'] ?? 0);
$presentLabel = $presentCount >= 10 ? 'Excellent' : 'Historique';
$absenceLabel = $absentCount > 0 ? 'A surveiller' : 'Aucune absence';
$statusClass = 'bg-slate-100';
$statusTextClass = 'text-slate-700';
$todayStatus = 'Non pointe';
$todayDetail = 'Aucun pointage enregistre aujourd\'hui';

if ($todayAttendance) {
    if (($todayAttendance['status'] ?? '') === 'retard') {
        $statusClass = 'bg-yellow-100';
        $statusTextClass = 'text-yellow-700';
        $todayStatus = 'En retard';
    } else {
        $statusClass = 'bg-green-100';
        $statusTextClass = 'text-green-700';
        $todayStatus = 'Present';
    }

    $todayDetail = 'Pointage effectue a ' . substr($todayAttendance['check_in'] ?? '', 0, 5);

    if (!empty($todayAttendance['check_out'])) {
        $todayDetail .= ' - sortie a ' . substr($todayAttendance['check_out'], 0, 5);
    }
}

if (!$todayAttendance) {
    $activityTitle = 'Pointer l\'entree';
    $activitySubtitle = 'Votre pointage du jour est en attente';
} elseif (empty($todayAttendance['check_out'])) {
    $activityTitle = 'Pointer la sortie';
    $activitySubtitle = 'Entree deja enregistree aujourd\'hui';
} elseif ($nextSchedule) {
    $activityTitle = 'Prochain cours';
    $activitySubtitle = $nextSchedule['day'] . ' de ' . $nextSchedule['start_time'] . ' a ' . $nextSchedule['end_time'];
} else {
    $activityTitle = 'Planning indisponible';
    $activitySubtitle = 'Aucun horaire defini pour votre cohorte';
}

$qrModel = new EtudiantModel($pdo);
$qrCode = $qrModel->generateQrCode($userId);
?>
<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Étudiant - PointagePro</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="bg-slate-100">


<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="ml-0 lg:ml-64 p-6 lg:p-10">


<!-- ================= HEADER ================= -->

<div class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C] rounded-3xl p-8 shadow-xl text-white mb-8">


<div class="flex flex-col lg:flex-row justify-between gap-5">


<div>

<h1 class="text-4xl font-bold flex items-center gap-3">

<i class="bi bi-person-circle text-5xl"></i>

Bonjour, <?= htmlspecialchars($studentName) ?>

</h1>


<p class="mt-3 text-blue-100">

Voici votre résumé d'activité et votre présence.

</p>


</div>



<div class="bg-white/20 backdrop-blur px-5 py-3 rounded-xl">


<p class="text-sm">

Aujourd'hui

</p>


<p class="font-bold">

<?= htmlspecialchars($todayLabel) ?>

</p>


</div>


</div>


</div>




<!-- ================= KPI ================= -->


<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">



<!-- Présences -->

<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-green-600 hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Présences

</p>


<h2 class="text-4xl font-bold text-green-600 mt-2">

<?= $presentCount ?>

</h2>


<span class="text-green-600 text-sm">

<?= htmlspecialchars($presentLabel) ?>

</span>


</div>



<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">

<i class="bi bi-check-circle-fill text-green-600 text-3xl"></i>

</div>


</div>


</div>





<!-- Absences -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-red-600 hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Absences

</p>


<h2 class="text-4xl font-bold text-red-600 mt-2">

<?= $absentCount ?>

</h2>


<span class="text-red-600 text-sm">

<?= htmlspecialchars($absenceLabel) ?>

</span>


</div>



<div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">

<i class="bi bi-person-x-fill text-red-600 text-3xl"></i>

</div>


</div>


</div>





<!-- Congés -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-blue-700 hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Congés

</p>


<h2 class="text-4xl font-bold text-blue-700 mt-2">

<?= $leaveCount ?>

</h2>


<span class="text-blue-700 text-sm">

Demandes

</span>


</div>



<div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">

<i class="bi bi-airplane-fill text-blue-700 text-3xl"></i>

</div>


</div>


</div>





<!-- Retards -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-[#8B5E3C] hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Retards

</p>


<h2 class="text-4xl font-bold text-[#8B5E3C] mt-2">

<?= $lateCount ?>

</h2>


<span class="text-[#8B5E3C] text-sm">

Historique

</span>


</div>



<div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">

<i class="bi bi-clock-fill text-[#8B5E3C] text-3xl"></i>

</div>


</div>


</div>


</div>






<!-- ================= INFORMATIONS ================= -->


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">





<!-- Statut -->


<div class="bg-white rounded-3xl shadow-lg p-7 hover:shadow-xl transition">


<h2 class="text-xl font-bold text-[#1E4F86] flex items-center gap-2 mb-5">


<i class="bi bi-calendar-check"></i>

Statut du jour

</h2>



<div class="<?= $statusClass ?> rounded-xl p-5">


<p class="text-3xl font-bold <?= $statusTextClass ?>">

<?= htmlspecialchars($todayStatus) ?>

</p>


<p class="text-gray-500 mt-2">

<?= htmlspecialchars($todayDetail) ?>

</p>


</div>


</div>





<!-- Activité -->


<div class="bg-white rounded-3xl shadow-lg p-7 hover:shadow-xl transition">


<h2 class="text-xl font-bold text-[#1E4F86] flex items-center gap-2 mb-5">


<i class="bi bi-bell"></i>

Prochaine activité

</h2>



<div class="space-y-4">


<div class="bg-slate-50 rounded-xl p-4">


<p class="font-bold">

<?= htmlspecialchars($activityTitle) ?>

</p>


<p class="text-gray-500 text-sm">

<?= htmlspecialchars($activitySubtitle) ?>

</p>


</div>



</div>


</div>





<!-- QR -->


<div class="bg-white rounded-3xl shadow-lg p-7 text-center hover:shadow-xl transition">


<h2 class="text-xl font-bold text-[#1E4F86] flex justify-center items-center gap-2 mb-5">


<i class="bi bi-qr-code"></i>

Mon QR Code

</h2>



<div class="bg-slate-50 rounded-2xl p-5">


<img src="<?= htmlspecialchars($qrCode) ?>"

class="mx-auto rounded-xl shadow">


<p class="text-gray-500 text-sm mt-4">

Présentez ce code pour votre pointage

</p>


</div>


</div>



</div>


</main>


</body>

</html>
