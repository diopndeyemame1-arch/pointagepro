<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l'étudiant est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: index.php?page=login");
    exit();
}

$userId = $_SESSION['user_id'];

// Récupérer les infos de l'étudiant (cohorte, département)
$stmt = $pdo->prepare("
    SELECT u.id, u.firstname, u.lastname, u.cohort_id, u.department_id,
           c.name AS cohort_name, d.name AS department_name
    FROM users u
    LEFT JOIN cohorts c ON c.id = u.cohort_id
    LEFT JOIN departments d ON d.id = u.department_id
    WHERE u.id = :id
");
$stmt->execute(['id' => $userId]);
$etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etudiant || !$etudiant['cohort_id']) {
    echo "<p style='text-align:center;color:red;padding:2rem;'>Vous n'êtes affecté à aucune cohorte. Veuillez contacter l'administration.</p>";
    exit();
}

$cohortId = $etudiant['cohort_id'];

// Récupérer l'emploi du temps de la cohorte
$stmt = $pdo->prepare("
    SELECT cs.day, cs.start_time, cs.end_time
    FROM cohort_schedules cs
    WHERE cs.cohort_id = :cohort_id
    ORDER BY
        CASE cs.day
            WHEN 'Lundi' THEN 1
            WHEN 'Mardi' THEN 2
            WHEN 'Mercredi' THEN 3
            WHEN 'Jeudi' THEN 4
            WHEN 'Vendredi' THEN 5
            WHEN 'Samedi' THEN 6
            WHEN 'Dimanche' THEN 7
            ELSE 8
        END,
        cs.start_time
");
$stmt->execute(['cohort_id' => $cohortId]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grouper par jour
$days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
$scheduleByDay = [];
foreach ($days as $day) {
    $scheduleByDay[$day] = [];
}
foreach ($schedules as $s) {
    $scheduleByDay[$s['day']][] = $s;
}

// Calcul des statistiques
$totalDays = 0;
$totalHours = 0;
foreach ($scheduleByDay as $day => $slots) {
    if (!empty($slots)) {
        $totalDays++;
        foreach ($slots as $slot) {
            $start = new DateTime($slot['start_time']);
            $end = new DateTime($slot['end_time']);
            $diff = $start->diff($end);
            $totalHours += $diff->h + ($diff->i / 60);
        }
    }
}
$totalHours = round($totalHours, 1);

// Compter le nombre de créneaux (modules)
$totalModules = count($schedules);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon emploi du temps</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 lg:ml-64 p-4 md:p-6 lg:p-8 bg-slate-100 min-h-screen">

        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 md:mb-8">

                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
                        <i class="bi bi-calendar3-week text-blue-600"></i>
                        Mon emploi du temps
                    </h1>

                    <p class="text-gray-500 mt-2 text-sm md:text-base">
                        Consultez votre planning hebdomadaire.
                    </p>
                </div>

                <div class="bg-blue-50 px-4 md:px-6 py-3 md:py-4 rounded-xl w-full sm:w-auto">
                    <h3 class="font-bold text-blue-700 text-sm md:text-base">
                        <?= htmlspecialchars($etudiant['department_name'] ?? 'Département') ?>
                    </h3>
                    <p class="text-gray-500 text-xs md:text-sm">
                        <?= htmlspecialchars($etudiant['cohort_name'] ?? 'Cohorte') ?>
                    </p>
                </div>

            </div>

            <!-- Planning -->
            <div class="overflow-x-auto rounded-2xl border">

                <table class="w-full border-collapse min-w-[400px] sm:min-w-0">

                    <thead class="bg-gradient-to-r from-blue-900 to-amber-700 text-white">

                        <tr>
                            <th class="p-3 md:p-4 text-xs md:text-sm">Jour</th>
                            <th class="p-3 md:p-4 text-xs md:text-sm">Horaire</th>
                            <th class="p-3 md:p-4 text-xs md:text-sm">Créneaux</th>
                        </tr>

                    </thead>

                    <tbody class="text-center">

                        <?php if (empty($schedules)): ?>
                            <tr>
                                <td colspan="3" class="p-8 text-gray-500">
                                    Aucun cours programmé pour votre cohorte.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($days as $day): ?>
                                <?php $slots = $scheduleByDay[$day] ?? []; ?>
                                <?php if (!empty($slots)): ?>
                                    <?php foreach ($slots as $index => $slot): ?>
                                        <tr class="hover:bg-blue-50 border-b">
                                            <?php if ($index === 0): ?>
                                                <td class="p-3 md:p-4 font-semibold text-xs md:text-sm" rowspan="<?= count($slots) ?>">
                                                    <?= htmlspecialchars($day) ?>
                                                </td>
                                            <?php endif; ?>
                                            <td class="p-3 md:p-4 text-xs md:text-sm whitespace-nowrap">
                                                <?= date('H:i', strtotime($slot['start_time'])) ?> - <?= date('H:i', strtotime($slot['end_time'])) ?>
                                            </td>
                                            <td class="p-3 md:p-4">
                                                <span class="bg-green-100 text-green-700 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm">
                                                    Cours
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mt-6 md:mt-8">

                <div class="bg-blue-50 rounded-xl p-4 md:p-6">
                    <i class="bi bi-calendar-check text-2xl md:text-3xl text-blue-600"></i>
                    <h3 class="font-bold text-lg md:text-xl mt-3">
                        <?= $totalDays ?> jour<?= $totalDays > 1 ? 's' : '' ?>
                    </h3>
                    <p class="text-gray-500 text-sm md:text-base">
                        Cours cette semaine
                    </p>
                </div>

                <div class="bg-green-50 rounded-xl p-4 md:p-6">
                    <i class="bi bi-clock-history text-2xl md:text-3xl text-green-600"></i>
                    <h3 class="font-bold text-lg md:text-xl mt-3">
                        <?= $totalHours ?> h
                    </h3>
                    <p class="text-gray-500 text-sm md:text-base">
                        Volume horaire
                    </p>
                </div>

                <div class="bg-purple-50 rounded-xl p-4 md:p-6">
                    <i class="bi bi-book text-2xl md:text-3xl text-purple-600"></i>
                    <h3 class="font-bold text-lg md:text-xl mt-3">
                        <?= $totalModules ?> créneau<?= $totalModules > 1 ? 'x' : '' ?>
                    </h3>
                    <p class="text-gray-500 text-sm md:text-base">
                        Créneaux programmés
                    </p>
                </div>

            </div>

        </div>

    </main>

</body>
</html>