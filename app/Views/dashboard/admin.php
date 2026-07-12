<?php
require_once __DIR__ . '/../../../config/database.php';

$today = new DateTimeImmutable('today');
$todayLabel = $today->format('d/m/Y');

$totalStudents = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$usersToday = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE created_at::date = CURRENT_DATE")->fetchColumn();

$presentToday = (int)$pdo->query("
    SELECT COUNT(*)
    FROM attendances
    WHERE date = CURRENT_DATE
    AND status = 'present'
")->fetchColumn();

$lateToday = (int)$pdo->query("
    SELECT COUNT(*)
    FROM attendances
    WHERE date = CURRENT_DATE
    AND status = 'retard'
")->fetchColumn();

$studentsCheckedToday = (int)$pdo->query("
    SELECT COUNT(DISTINCT user_id)
    FROM attendances
    WHERE date = CURRENT_DATE
")->fetchColumn();

$absentToday = max(0, $totalStudents - $studentsCheckedToday);
$attendanceRate = $totalStudents > 0 ? round(($presentToday / $totalStudents) * 100) : 0;
$lateRate = $totalStudents > 0 ? round(($lateToday / $totalStudents) * 100) : 0;
$absenceRate = $totalStudents > 0 ? round(($absentToday / $totalStudents) * 100) : 0;

$approvedLeavesToday = (int)$pdo->query("
    SELECT COUNT(*)
    FROM absences
    WHERE status = 'approuve'
    AND updated_at::date = CURRENT_DATE
")->fetchColumn();

$pendingLeaves = (int)$pdo->query("SELECT COUNT(*) FROM absences WHERE status = 'en_attente'")->fetchColumn();
$qrCodes = (int)$pdo->query("SELECT COUNT(*) FROM qr_codes")->fetchColumn();

$recentRows = $pdo->query("
    SELECT action, entity, created_at
    FROM audit_logs
    ORDER BY created_at DESC
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

$activityItems = [
    $studentsCheckedToday . " etudiant(s) ont pointe aujourd'hui",
    $usersToday . " utilisateur(s) ajoute(s) aujourd'hui",
    $approvedLeavesToday . " conge(s) valide(s) aujourd'hui",
];

$notifications = [
    'Systeme actif',
    $pendingLeaves . ' demande(s) d\'absence en attente',
    $qrCodes . ' QR code(s) genere(s)',
];

if (!empty($recentRows)) {
    $activityItems = array_map(function ($row) {
        return strtoupper($row['action']) . ' sur ' . $row['entity'] . ' le ' . date('d/m/Y H:i', strtotime($row['created_at']));
    }, $recentRows);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin - PointagePro</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="bg-slate-100">


<div class="flex min-h-screen">


<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>



<main class="flex-1 ml-0 lg:ml-64 p-4 sm:p-6 lg:p-8 overflow-x-hidden">



<!-- ================= HEADER ================= -->


<div class="bg-gradient-to-r from-blue-900 to-amber-700 rounded-3xl p-8 shadow-xl text-white mb-8">


<div class="flex justify-between items-center">


<div>


<h1 class="text-4xl font-bold flex items-center gap-3">

<i class="bi bi-speedometer2 text-5xl"></i>

Tableau de bord Admin

</h1>


<p class="mt-3 text-blue-100">

Gestion globale du système de pointage.

</p>


</div>



<div class="bg-white/20 backdrop-blur px-5 py-3 rounded-xl">


<i class="bi bi-calendar3"></i>

<?= htmlspecialchars($todayLabel) ?>


</div>



</div>


</div>






<!-- ================= KPI ================= -->



<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">





<!-- ETUDIANTS -->


<div class="bg-white rounded-3xl shadow-lg p-6 border-l-8 border-blue-900
hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>


<p class="text-gray-500 text-sm uppercase font-semibold">

Étudiants

</p>


<h2 class="text-4xl font-bold text-blue-900 mt-2">

<?= $totalStudents ?>

</h2>


<span class="text-green-600 text-sm">

<?= $totalUsers ?> utilisateur(s)

</span>


</div>



<div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">

<i class="bi bi-mortarboard-fill text-3xl text-blue-900"></i>

</div>


</div>


</div>





<!-- PRESENTS -->


<div class="bg-white rounded-3xl shadow-lg p-6 border-l-8 border-green-600
hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>


<p class="text-gray-500 text-sm uppercase font-semibold">

Présences

</p>


<h2 class="text-4xl font-bold text-green-600 mt-2">

<?= $presentToday ?>

</h2>


<span class="text-green-600 text-sm">

<?= $attendanceRate ?>%

</span>


</div>



<div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">


<i class="bi bi-person-check-fill text-3xl text-green-600"></i>


</div>


</div>


</div>







<!-- RETARDS -->


<div class="bg-white rounded-3xl shadow-lg p-6 border-l-8 border-amber-700
hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>


<p class="text-gray-500 text-sm uppercase font-semibold">

Retards

</p>


<h2 class="text-4xl font-bold text-amber-700 mt-2">

<?= $lateToday ?>

</h2>


<span class="text-amber-700 text-sm">

<?= $lateRate ?>%

</span>


</div>



<div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center">

<i class="bi bi-clock-history text-3xl text-amber-700"></i>

</div>



</div>


</div>







<!-- ABSENCES -->


<div class="bg-white rounded-3xl shadow-lg p-6 border-l-8 border-red-600
hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>


<p class="text-gray-500 text-sm uppercase font-semibold">

Absences

</p>


<h2 class="text-4xl font-bold text-red-600 mt-2">

<?= $absentToday ?>

</h2>


<span class="text-red-600 text-sm">

<?= $absenceRate ?>%

</span>


</div>



<div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">


<i class="bi bi-person-x-fill text-3xl text-red-600"></i>


</div>



</div>


</div>



</div>








<!-- ================= SECTION BAS ================= -->



<div class="grid lg:grid-cols-3 gap-6 mt-8">





<!-- ACTIVITE -->


<div class="bg-white rounded-3xl shadow-lg p-6 hover:shadow-xl transition">


<div class="flex items-center gap-3 mb-5">


<div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">

<i class="bi bi-activity text-2xl text-blue-900"></i>

</div>


<h2 class="text-xl font-bold text-blue-900">

Activité récente

</h2>


</div>




<ul class="space-y-4 text-gray-600">


<?php foreach ($activityItems as $item): ?>
<li class="flex gap-3">

<i class="bi bi-check-circle-fill text-green-600"></i>

<?= htmlspecialchars($item) ?>

</li>
<?php endforeach; ?>


</ul>


</div>








<!-- NOTIFICATIONS -->


<div class="bg-white rounded-3xl shadow-lg p-6 hover:shadow-xl transition">


<div class="flex items-center gap-3 mb-5">


<div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">


<i class="bi bi-bell text-2xl text-amber-700"></i>


</div>



<h2 class="text-xl font-bold text-blue-900">

Notifications

</h2>


</div>



<ul class="space-y-4 text-gray-600">


<?php foreach ($notifications as $notification): ?>
<li class="flex gap-3">

<i class="bi bi-bell-fill text-amber-700"></i>

<?= htmlspecialchars($notification) ?>

</li>
<?php endforeach; ?>


</ul>


</div>







<!-- SYSTEME -->


<div class="bg-gradient-to-br from-blue-900 to-amber-700 rounded-3xl shadow-xl p-6 text-white">


<h2 class="text-xl font-bold mb-5">

État système

</h2>


<div class="space-y-4">


<div class="flex justify-between">

<span>

Serveur

</span>


<span class="bg-green-500 px-3 py-1 rounded-full">

Actif

</span>


</div>



<div class="flex justify-between">

<span>

Base de données

</span>


<span class="bg-green-500 px-3 py-1 rounded-full">

OK

</span>


</div>




<div class="flex justify-between">

<span>

Sécurité

</span>


<span class="bg-green-500 px-3 py-1 rounded-full">

Protégée

</span>


</div>



</div>


</div>




</div>




</main>


</div>



</body>

</html>
