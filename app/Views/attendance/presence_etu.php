<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Models/Attendance.php';

$model = new Attendance($pdo);

$userId = $_SESSION['user_id'];

// Load school settings for client-side distance checks
require_once __DIR__ . '/../../Models/Settings.php';
$settingsModel = new Settings($pdo);
$schoolSettings = $settingsModel->get();

$schoolLatPHP = $schoolSettings['school_lat'] ?? '14.721725593495935';
$schoolLngPHP = $schoolSettings['school_lng'] ?? '-17.463747100271004';
$schoolRadiusPHP = (int)($schoolSettings['radius'] ?? 0);

$history = $model->getStudentHistory($userId);

$total = count($history);

$present = 0;
$retard = 0;

foreach ($history as $h) {
    if ($h['status'] == 'present') $present++;
    if ($h['status'] == 'retard') $retard++;
}

$taux = ($total > 0) ? round(($present / $total) * 100) : 0;
// Pagination for history display (keep KPIs calculated on full history)
$limit = 5;
$currentPage = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$total = count($history);
$totalPages = ($total > 0) ? (int)ceil($total / $limit) : 1;
$currentPage = min($currentPage, $totalPages);
$start = ($currentPage - 1) * $limit;
$pagedHistory = array_slice($history, $start, $limit);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mes Présences</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-slate-100">

<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="ml-0 lg:ml-64 p-6 lg:p-10">


<!-- ================= HEADER ================= -->

<div class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C] rounded-3xl p-8 shadow-xl text-white mb-8">


<h1 class="text-4xl font-bold flex items-center gap-3">

<i class="bi bi-calendar-check text-5xl"></i>

Mes Présences

</h1>


<p class="mt-3 text-blue-100">

Consultez votre historique de présence et effectuez votre pointage.

</p>


</div>





<?php if (!empty($_SESSION['attendance_error'])): ?>
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
        <?= htmlspecialchars($_SESSION['attendance_error']) ?>
    </div>
    <?php unset($_SESSION['attendance_error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['attendance_success'])): ?>
    <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
        <?= htmlspecialchars($_SESSION['attendance_success']) ?>
    </div>
    <?php unset($_SESSION['attendance_success']); ?>
<?php endif; ?>

<!-- ================= KPI ================= -->


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">



<!-- Présences -->

<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-green-600 hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 uppercase text-sm font-semibold">

Présences

</p>


<h2 class="text-4xl font-bold text-green-600 mt-2">

<?= $present ?>

</h2>


<p class="text-green-600 text-sm">

Jours présents

</p>


</div>


<div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">

<i class="bi bi-check-circle-fill text-green-600 text-3xl"></i>

</div>


</div>


</div>





<!-- Retards -->

<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-[#8B5E3C] hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 uppercase text-sm font-semibold">

Retards

</p>


<h2 class="text-4xl font-bold text-[#8B5E3C] mt-2">

<?= $retard ?>

</h2>


<p class="text-[#8B5E3C] text-sm">

Retards enregistrés

</p>


</div>



<div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">

<i class="bi bi-clock-history text-[#8B5E3C] text-3xl"></i>

</div>


</div>


</div>





<!-- Taux -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-[#1E4F86] hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 uppercase text-sm font-semibold">

Taux présence

</p>


<h2 class="text-4xl font-bold text-[#1E4F86] mt-2">

<?= $taux ?>%

</h2>


<p class="text-blue-600 text-sm">

Performance globale

</p>


</div>



<div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">

<i class="bi bi-graph-up-arrow text-[#1E4F86] text-3xl"></i>

</div>


</div>


</div>


</div>






<!-- ================= HISTORIQUE ================= -->


<div class="bg-white rounded-3xl shadow-xl overflow-hidden">



<!-- HEADER TABLE -->

<div class="px-6 py-5 border-b flex flex-col lg:flex-row justify-between gap-5">


<div>


<h2 class="text-2xl font-bold text-[#1E4F86] flex items-center gap-2">

<i class="bi bi-list-check"></i>

Historique des présences

</h2>


<p class="text-gray-500 text-sm mt-1">

Suivi détaillé de vos pointages

</p>


</div>





<!-- BUTTONS -->


<div class="flex flex-wrap gap-3">


<form id="entryForm" method="POST" action="index.php?page=pointage_entree" onsubmit="return submitWithGeo(this,event)">
    <input type="hidden" name="lat" />
    <input type="hidden" name="lng" />

    <button

    class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl font-semibold flex items-center gap-2 transition hover:scale-105 shadow">


    <i class="bi bi-box-arrow-in-right"></i>

    Entrée


    </button>


</form>





<form id="exitForm" method="POST" action="index.php?page=pointage_sortie" onsubmit="return submitWithGeo(this,event)">
    <input type="hidden" name="lat" />
    <input type="hidden" name="lng" />

    <button

    class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-semibold flex items-center gap-2 transition hover:scale-105 shadow">


    <i class="bi bi-box-arrow-right"></i>

    Sortie


    </button>


</form>



</div>


</div>






<div class="table-responsive">
<table class="w-full">
<thead class="bg-gradient-to-r from-blue-900 to-amber-700 text-white">
<tr>
<th class="px-4 py-3 font-bold whitespace-nowrap">Date</th>
<th class="px-4 py-3 font-bold whitespace-nowrap">Entree</th>
<th class="px-4 py-3 font-bold whitespace-nowrap">Sortie</th>
<th class="px-4 py-3 font-bold whitespace-nowrap">Statut</th>
</tr>
</thead>

<tbody >

<?php if (!empty($pagedHistory)): ?>
<?php foreach ($pagedHistory as $h): ?>


<tr class="border-t hover:bg-slate-50">


<td class="p-4 text-center whitespace-nowrap">

<?= $h['date'] ?>

</td>



<td class="p-4 text-center whitespace-nowrap">

<?= $h['check_in'] ?? '--:--' ?>

</td>




<td class="p-4 text-center whitespace-nowrap">

<?= $h['check_out'] ?? '--:--' ?>

</td>




<td class="p-4 text-center whitespace-nowrap">


<?php if($h['status']=="present"): ?>


<span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">

<i class="bi bi-check-circle"></i>

Présent

</span>


<?php elseif($h['status']=="retard"): ?>


<span class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full font-semibold">

<i class="bi bi-clock"></i>

Retard

</span>


<?php else: ?>


<span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">

<i class="bi bi-x-circle"></i>

Absent

</span>


<?php endif; ?>


</td>



</tr>



<?php endforeach; ?>
<?php else: ?>
<tr>
<td colspan="4" class="p-6 text-center text-slate-500">
Aucun pointage trouvÃ©.
</td>
</tr>
<?php endif; ?>


</tbody>


</table>


</div>

<?php if ($total > 0): ?>
<div class="px-6 py-4 border-t flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
<p class="text-sm text-slate-500">
Affichage de <?= $start + 1 ?> a <?= min($start + $limit, $total) ?> sur <?= $total ?> pointages
</p>

<div class="flex flex-wrap items-center gap-2">
<a href="index.php?page=presence&p=<?= max(1, $currentPage - 1) ?>"
   class="px-3 py-2 rounded-xl border text-sm <?= ($currentPage <= 1) ? 'pointer-events-none opacity-50 bg-slate-100 text-slate-400' : 'bg-white text-slate-600 hover:bg-slate-100' ?>">
Precedent
</a>

<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<a href="index.php?page=presence&p=<?= $i ?>"
   class="min-w-10 px-3 py-2 rounded-xl border text-center text-sm <?= ($i == $currentPage) ? 'bg-[#1E4F86] text-white border-[#1E4F86]' : 'bg-white text-slate-600 hover:bg-slate-100' ?>">
<?= $i ?>
</a>
<?php endfor; ?>

<a href="index.php?page=presence&p=<?= min($totalPages, $currentPage + 1) ?>"
   class="px-3 py-2 rounded-xl border text-sm <?= ($currentPage >= $totalPages) ? 'pointer-events-none opacity-50 bg-slate-100 text-slate-400' : 'bg-white text-slate-600 hover:bg-slate-100' ?>">
Suivant
</a>
</div>
</div>
<?php endif; ?>



</div>



</main>

</body>
</html>

<script>
// Injected school settings from PHP
const SCHOOL_LAT = parseFloat("<?= $schoolLatPHP ?>");
const SCHOOL_LNG = parseFloat("<?= $schoolLngPHP ?>");
const SCHOOL_RADIUS = parseInt("<?= $schoolRadiusPHP ?>", 10);

function toRad(v){return v*Math.PI/180;}
function haversineDistance(lat1, lon1, lat2, lon2){
    const R = 6371000;
    const dLat = toRad(lat2-lat1);
    const dLon = toRad(lon2-lon1);
    const a = Math.sin(dLat/2)*Math.sin(dLat/2) + Math.cos(toRad(lat1))*Math.cos(toRad(lat2))*Math.sin(dLon/2)*Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function showDistanceModal(distance, allowed, form){
    const modal = document.getElementById('distanceModal');
    document.getElementById('distanceValue').textContent = Math.round(distance) + ' m';
    document.getElementById('radiusValue').textContent = SCHOOL_RADIUS + ' m';
    document.getElementById('distanceMessage').textContent = allowed ? 'Vous êtes à l\'école, vous pouvez pointer.' : 'Vous êtes trop loin de l\'école.';
    document.getElementById('distanceConfirm').disabled = !allowed;
    modal.dataset.formId = form.id;
    modal.classList.remove('hidden');
}

function closeDistanceModal(){
    document.getElementById('distanceModal').classList.add('hidden');
}

function submitWithGeo(form, event){
    event.preventDefault();
    if (!navigator.geolocation){
        alert('Votre navigateur ne supporte pas la géolocalisation.');
        return false;
    }

    navigator.geolocation.getCurrentPosition(function(pos){
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        const distance = haversineDistance(SCHOOL_LAT, SCHOOL_LNG, lat, lng);

        // set hidden inputs
        let latInput = form.querySelector('input[name="lat"]');
        let lngInput = form.querySelector('input[name="lng"]');
        if (!latInput){ latInput = document.createElement('input'); latInput.type='hidden'; latInput.name='lat'; form.appendChild(latInput); }
        if (!lngInput){ lngInput = document.createElement('input'); lngInput.type='hidden'; lngInput.name='lng'; form.appendChild(lngInput); }
        latInput.value = lat; lngInput.value = lng;

        const allowed = (SCHOOL_RADIUS === 0) ? true : (distance <= SCHOOL_RADIUS);
        showDistanceModal(distance, allowed, form);

    }, function(err){
        alert('Impossible d\'obtenir votre position: ' + err.message);
    }, {enableHighAccuracy:true, timeout:10000});

    return false;
}

function confirmDistanceAndSubmit(){
    const modal = document.getElementById('distanceModal');
    const formId = modal.dataset.formId;
    const form = document.getElementById(formId);
    closeDistanceModal();
    if (form) form.submit();
}
</script>

<!-- Distance modal -->
<div id="distanceModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <h3 class="text-lg font-bold mb-2">Vérification de la position</h3>
        <p id="distanceMessage" class="mb-2 text-sm text-slate-700"></p>
        <p class="text-sm mb-1">Distance: <strong id="distanceValue"></strong></p>
        <p class="text-sm mb-4">Rayon autorisé: <strong id="radiusValue"></strong></p>

        <div class="flex justify-end gap-3">
            <button onclick="closeDistanceModal()" class="px-4 py-2 border rounded-lg">Fermer</button>
            <button id="distanceConfirm" onclick="confirmDistanceAndSubmit()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg" disabled>Confirmer</button>
        </div>
    </div>
</div>
