<?php
require_once __DIR__ . '/../../../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    die("Département introuvable");
}

$stmt = $pdo->prepare("SELECT * FROM departments WHERE id = ?");
$stmt->execute([$id]);
$department = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$department) {
    die("Département introuvable");
}

$stmt = $pdo->prepare("SELECT * FROM cohorts WHERE department_id = ?");
$stmt->execute([$id]);
$cohorts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($cohorts as &$cohort) {

    $stmtSchedule = $pdo->prepare("
        SELECT *
        FROM cohort_schedules
        WHERE cohort_id = ?
        ORDER BY id
    ");

    $stmtSchedule->execute([$cohort['id']]);

    $cohort['schedules'] = $stmtSchedule->fetchAll(PDO::FETCH_ASSOC);
}

unset($cohort);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Cohortes</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">
      <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

 <main class="flex-1 ml-0 lg:ml-64 p-4 sm:p-6 lg:p-8">
<div class="bg-white/95 ring-1 ring-slate-200 rounded-3xl shadow-xl p-6">

<!-- HEADER -->
<div class="mb-10">
    <div class="flex items-center gap-3">

        <div class="bg-indigo-600 text-white p-3 rounded-2xl shadow-lg">
            <i class="bi bi-building text-xl"></i>
        </div>

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                <?= htmlspecialchars($department['name']) ?>
            </h1>
            <p class="text-slate-500">Gestion des cohortes du département</p>
        </div>

    </div>
</div>

<!-- GRID -->
<div class="grid md:grid-cols-3 gap-6">

<?php foreach ($cohorts as $c): ?>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 hover:shadow-lg transition duration-300 overflow-hidden">

        <!-- HEADER COLOR -->
        <div class="h-24 bg-gradient-to-r from-indigo-200 to-indigo-100 flex items-center justify-center relative">

            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:14px_14px]"></div>

            <div class="bg-white text-indigo-600 p-4 rounded-full shadow-lg border-4 border-white">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>

        </div>

        <!-- BODY -->
        <div class="p-5 text-center">

            <h2 class="text-xl font-bold text-slate-900 flex items-center justify-center gap-2">
                <i class="bi bi-diagram-3 text-indigo-600"></i>
                <?= htmlspecialchars($c['name']) ?>
            </h2>

            <div class="mt-3 text-left">

<?php foreach(($c['schedules'] ?? []) as $horaire): ?>

<div class="flex justify-between border-b py-1 text-sm">

    <span class="font-medium">
        <i class="bi bi-calendar-event text-green-600"></i>
        <?= htmlspecialchars($horaire['day']) ?>
    </span>

    <span>
        <i class="bi bi-clock text-blue-600"></i>

        <?= substr($horaire['start_time'],0,5) ?>

        -

        <?= substr($horaire['end_time'],0,5) ?>
    </span>

</div>

<?php endforeach; ?>

</div>

            <!-- STATUS -->
                    <div class="mt-4">

                <?php if ($c['status'] == 'active'): ?>
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm flex items-center justify-center gap-1">
                        <i class="bi bi-check-circle"></i> Actif
                    </span>

                <?php elseif ($c['status'] == 'inactive'): ?>
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm flex items-center justify-center gap-1">
                        <i class="bi bi-x-circle"></i> Inactif
                    </span>

                <?php else: ?>
                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm flex items-center justify-center gap-1">
                        <i class="bi bi-archive"></i> Archivé
                    </span>
                <?php endif; ?>

            </div>

            <!-- BUTTONS -->
            <div class="flex gap-2 mt-5 justify-center">
                <?php $first = $c['schedules'][0] ?? null; ?>
                <button onclick='openEditModal(
                    <?= $c["id"] ?>,
                    "<?= htmlspecialchars($c["name"], ENT_QUOTES) ?>",
                    <?= json_encode($c["status"]) ?>,
                    <?= json_encode($c["schedules"]) ?>
                )' class="bg-indigo-100 text-indigo-700 px-3 py-2 rounded-2xl hover:bg-indigo-200 transition shadow-sm">
                    <i class="bi bi-pencil"></i>
                </button>

               <a href="/COUR-TELLY-TECH/pointagepro/public/index.php?page=delete_cohort&id=<?= $c['id'] ?>"
   onclick="return confirm('Voulez-vous vraiment supprimer cette cohorte ?')"
   class="bg-red-100 text-red-700 px-3 py-2 rounded-2xl hover:bg-red-200 transition shadow-sm">
    <i class="bi bi-trash"></i>
</a>
            </div>
        </div>
    </div>

<?php endforeach; ?>

</div>

<!-- EMPTY STATE -->
<?php if (count($cohorts) == 0): ?>
<div class="text-center mt-10">
    <i class="bi bi-inbox text-5xl text-gray-400"></i>
    <p class="text-gray-500 mt-2">Aucune cohorte trouvée</p>
</div>
<?php endif; ?>
</div>
</main>
</div>
<div id="editModal"
class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">

<div class="bg-white rounded-3xl w-full max-w-2xl p-6 shadow-2xl ring-1 ring-slate-200">

<h2 class="text-2xl font-bold mb-6 text-slate-900">Modifier la cohorte</h2>

<form action="/COUR-TELLY-TECH/pointagepro/public/index.php?page=update_cohort" method="POST">

<input type="hidden" name="id" id="edit_id">

<!-- NOM -->
<div class="mb-4">
<label>Nom</label>
<input id="edit_name" name="name" type="text"
class="w-full border rounded-lg p-3">
</div>

<!-- STATUS -->
<div class="mb-4">
<label>Statut</label>
<select id="edit_status" name="status"
class="w-full border rounded-lg p-3">
    <option value="active">Actif</option>
    <option value="inactive">Inactif</option>
    <option value="archived">Archivé</option>
</select>
</div>

<!-- SCHEDULES -->
<div class="mb-4">
<label class="font-bold">Horaires</label>

<div id="edit_schedules" class="space-y-2 mt-2"></div>

</div>

<div class="flex justify-end gap-3">

<button type="button"
onclick="closeEditModal()"
class="px-5 py-2 border border-slate-300 rounded-2xl hover:bg-slate-100 transition">
Annuler
</button>

<button type="submit"
class="bg-indigo-700 text-white px-5 py-2 rounded-2xl hover:bg-indigo-800 transition">
Enregistrer
</button>

</div>

</form>

</div>
</div>

</div>
<script>

function openEditModal(id, name, status, schedules) {

    document.getElementById('editModal').classList.remove('hidden');

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_status').value = status;

    const container = document.getElementById('edit_schedules');
    container.innerHTML = "";

    if (!schedules || schedules.length === 0) {
        container.innerHTML = "<p class='text-gray-400'>Aucun horaire</p>";
        return;
    }

    schedules.forEach((s, index) => {

        container.innerHTML += `
        <div class="grid grid-cols-3 gap-2">

            <input type="text"
                name="schedules[${index}][day]"
                value="${s.day}"
                class="border p-2 rounded">

            <input type="time"
                name="schedules[${index}][start]"
                value="${s.start_time}"
                class="border p-2 rounded">

            <input type="time"
                name="schedules[${index}][end]"
                value="${s.end_time}"
                class="border p-2 rounded">

        </div>`;
    });
}

function closeEditModal(){

    document.getElementById('editModal').classList.add('hidden');

}

</script>
</body>
</html>
