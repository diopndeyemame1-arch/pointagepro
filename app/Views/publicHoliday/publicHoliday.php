<?php
if(session_status()===PHP_SESSION_NONE){
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
}

$role = $_SESSION['role'] ?? '';
$selectedMonth = $selectedMonth ?? ($_GET['month'] ?? '');
$selectedMonth = preg_match('/^(0?[1-9]|1[0-2])$/', (string) $selectedMonth) ? (int) $selectedMonth : '';
$months = [
    1 => 'Janvier',
    2 => 'Février',
    3 => 'Mars',
    4 => 'Avril',
    5 => 'Mai',
    6 => 'Juin',
    7 => 'Juillet',
    8 => 'Août',
    9 => 'Septembre',
    10 => 'Octobre',
    11 => 'Novembre',
    12 => 'Décembre',
];
?>

<!DOCTYPE html>
<html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">

<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="ml-0 lg:ml-64 p-6 lg:p-10">


<!-- ================= HEADER ================= -->

<div class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C] 
rounded-3xl p-8 shadow-xl text-white mb-8">


<h1 class="text-4xl font-bold flex items-center gap-3">

<i class="bi bi-calendar-event-fill text-5xl"></i>

Jours fériés

</h1>


<p class="mt-3 text-blue-100">

Gestion du calendrier des jours fériés de l'application.

</p>


</div>





<!-- ================= KPI ================= -->


<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">



<!-- TOTAL -->

<div class="
bg-white rounded-2xl shadow-lg p-6 
border-l-8 border-[#1E4F86]
hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Total jours fériés

</p>


<h3 class="text-4xl font-bold text-[#1E4F86] mt-2">

<?= $total ?? 0 ?>

</h3>


</div>


<div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">

<i class="bi bi-calendar-event-fill text-[#1E4F86] text-3xl"></i>

</div>


</div>

</div>





<!-- CE MOIS -->

<div class="
bg-white rounded-2xl shadow-lg p-6 
border-l-8 border-green-600
hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex justify-between">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Ce mois

</p>


<h3 class="text-4xl font-bold text-green-600 mt-2">

<?= $thisMonth ?? 0 ?>

</h3>


</div>


<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">

<i class="bi bi-calendar2-week-fill text-green-600 text-3xl"></i>

</div>


</div>

</div>







<!-- PROCHAIN -->


<div class="
bg-white rounded-2xl shadow-lg p-6 
border-l-8 border-[#8B5E3C]
hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex justify-between">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Prochain

</p>


<h3 class="text-xl font-bold text-[#8B5E3C] mt-3">

<?= $next['holiday_name'] ?? 'Aucun' ?>

</h3>


</div>


<div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">

<i class="bi bi-alarm-fill text-[#8B5E3C] text-3xl"></i>

</div>


</div>

</div>







<!-- RESTANTS -->


<div class="
bg-white rounded-2xl shadow-lg p-6 
border-l-8 border-purple-600
hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex justify-between">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Restants

</p>


<h3 class="text-4xl font-bold text-purple-600 mt-2">

<?= $remaining ?? 0 ?>

</h3>


</div>


<div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">

<i class="bi bi-calendar-check-fill text-purple-600 text-3xl"></i>

</div>


</div>

</div>



</div>







<!-- ================= TABLE ================= -->


<div class="bg-white rounded-3xl shadow-xl overflow-hidden">



<div class="
px-6 py-6 border-b 
flex flex-col lg:flex-row 
justify-between gap-5">


<div>

<h2 class="
text-2xl font-bold text-[#1E4F86]
flex items-center gap-3">

<i class="bi bi-calendar-event"></i>

Liste des jours fériés

</h2>


<p class="text-gray-500 mt-2">

Calendrier des jours fériés enregistrés

</p>


</div>





<form method="GET" action="index.php" class="flex flex-col sm:flex-row gap-3 sm:items-end">
<input type="hidden" name="page" value="holiday">

<div>
<label for="monthFilter" class="block text-sm font-semibold text-gray-600 mb-1">
Filtrer par mois
</label>
<select
id="monthFilter"
name="month"
class="border rounded-xl px-4 py-3">
<option value="">Tous les mois</option>
<?php foreach($months as $monthNumber => $monthName): ?>
<option value="<?= $monthNumber ?>" <?= ((int) $selectedMonth === $monthNumber) ? 'selected' : '' ?>>
<?= htmlspecialchars($monthName) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<button
type="submit"
class="bg-[#1E4F86] hover:bg-[#8B5E3C] text-white px-5 py-3 rounded-xl transition duration-300">
Filtrer
</button>

<?php if($selectedMonth): ?>
<a href="index.php?page=holiday"
class="border border-gray-300 text-gray-700 hover:bg-gray-100 px-5 py-3 rounded-xl transition duration-300">
Réinitialiser
</a>
<?php endif; ?>
</form>

<?php if($role === 'admin'): ?>

<button 
    type="button"
    id="openHolidayModal"
    title="Définir un jour férié"
    class="
bg-white
border-2 border-[#1E4F86]
text-[#1E4F86]
hover:bg-[#1E4F86]
hover:text-white
px-5 py-3 rounded-xl

flex items-center gap-3

transition duration-300
hover:scale-105">

    <i class="bi bi-calendar-plus-fill text-2xl"></i>
    Définir un jour férié

</button>

<?php endif; ?>



</div>







<div class="overflow-x-auto">


<table class="w-full">



<thead class="
bg-gradient-to-r 
from-[#1E4F86] 
to-[#8B5E3C]
text-white">


<tr>


<th class="p-4 text-left">
Nom
</th>


<th class="p-4 text-left">
Date
</th>


<th class="p-4 text-left">
Type
</th>


<th class="p-4 text-left">
Statut
</th>


<?php if($role=='admin'): ?>

<th class="p-4 text-center">
Actions
</th>

<?php endif; ?>


</tr>


</thead>





<tbody>



<?php foreach(($holidays ?? []) as $h): ?>


<tr class="
border-b
hover:bg-blue-50
transition duration-300">


<td class="p-4 font-semibold">

<?= htmlspecialchars($h['holiday_name']) ?>

</td>



<td class="p-4">

<?= date('d/m/Y',
strtotime($h['holiday_date'])) ?>

</td>




<td class="p-4">

<span class="
bg-blue-100 
text-[#1E4F86]
px-3 py-1 rounded-full">

<?= htmlspecialchars($h['holiday_type']) ?>

</span>

</td>





<td class="p-4">


<?php if($h['status']=='passe'): ?>


<span class="
bg-gray-100 
text-gray-700
px-3 py-1 rounded-full">

Passé

</span>


<?php else: ?>


<span class="
bg-orange-100
text-orange-700
px-3 py-1 rounded-full">

À venir

</span>


<?php endif; ?>


</td>





<?php if($role=='admin'): ?>


<td class="p-4 text-center">


<div class="flex justify-center gap-3">


<button type="button"
class="
editHolidayBtn
text-[#1E4F86]
hover:text-[#8B5E3C]
text-2xl
transition"
data-id="<?= htmlspecialchars($h['id'], ENT_QUOTES) ?>"
data-name="<?= htmlspecialchars($h['holiday_name'], ENT_QUOTES) ?>"
data-date="<?= htmlspecialchars($h['holiday_date'], ENT_QUOTES) ?>"
data-type="<?= htmlspecialchars($h['holiday_type'], ENT_QUOTES) ?>"
data-description="<?= htmlspecialchars($h['description'] ?? '', ENT_QUOTES) ?>">

<i class="bi bi-pencil-square"></i>

</button>



<a href="
index.php?page=delete_holiday&id=<?= urlencode($h['id']) ?><?= $selectedMonth ? '&month=' . urlencode($selectedMonth) : '' ?>"
onclick="return confirm('Supprimer ce jour férié ?')"

class="
text-red-600
hover:text-red-800
text-2xl">

<i class="bi bi-trash"></i>

</a>


</div>


</td>


<?php endif; ?>



</tr>



<?php endforeach; ?>



</tbody>



</table>



</div>






<!-- PAGINATION -->


<div class="flex justify-center gap-2 p-6">


<?php 

$totalPages = $totalPages ?? 1;


for($i=1;$i<=$totalPages;$i++): 

?>


<a href="index.php?page=holiday&p=<?=$i?><?= $selectedMonth ? '&month=' . urlencode($selectedMonth) : '' ?>"

class="
w-10 h-10 rounded-xl
flex items-center justify-center

border

transition duration-300

<?= ($page==$i)

?'bg-[#1E4F86] text-white'

:'bg-white hover:bg-[#8B5E3C] hover:text-white'

?>

">


<?=$i?>


</a>


<?php endfor; ?>


</div>



</div>



</main>
<div id="holidayModal"
class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50">


<div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6">


<h2 class="text-2xl font-bold mb-5">
Définir un jour férié
</h2>


<form method="POST" action="index.php?page=store_holiday">


<input 
type="text"
name="name"
placeholder="Nom du jour férié"
class="w-full border rounded-xl px-4 py-3 mb-4"
required>


<input 
type="date"
name="holiday_date"
class="w-full border rounded-xl px-4 py-3 mb-4"
required>


<select 
name="type"
class="w-full border rounded-xl px-4 py-3 mb-4">

<option value="National">National</option>
<option value="Religieux">Religieux</option>
<option value="International">International</option>

</select>


<textarea
name="description"
placeholder="Description"
class="w-full border rounded-xl px-4 py-3 mb-4">
</textarea>


<div class="flex justify-end gap-3">


<button 
type="button"
onclick="closeModal()"
class="px-5 py-3 border rounded-xl">

Annuler

</button>


<button
type="submit"
class="
bg-[#1E4F86]
hover:bg-[#8B5E3C]
text-white px-6 py-3 rounded-xl

flex items-center gap-2

transition duration-300
hover:scale-105">

Enregistrer

</button>


</div>


</form>

</div>

</div>

<div id="editHolidayModal"
class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50">

<div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6">

<h2 class="text-2xl font-bold mb-5">
Modifier un jour férié
</h2>

<form
method="POST"
id="editHolidayForm"
data-base-action="index.php?page=update_holiday<?= $selectedMonth ? '&month=' . urlencode($selectedMonth) : '' ?>"
action="index.php?page=update_holiday<?= $selectedMonth ? '&month=' . urlencode($selectedMonth) : '' ?>">

<input type="hidden" name="id" id="editHolidayId">

<input
type="text"
name="name"
id="editHolidayName"
placeholder="Nom du jour férié"
class="w-full border rounded-xl px-4 py-3 mb-4"
required>

<input
type="date"
name="holiday_date"
id="editHolidayDate"
class="w-full border rounded-xl px-4 py-3 mb-4"
required>

<select
name="type"
id="editHolidayType"
class="w-full border rounded-xl px-4 py-3 mb-4">

<option value="National">National</option>
<option value="Religieux">Religieux</option>
<option value="International">International</option>

</select>

<textarea
name="description"
id="editHolidayDescription"
placeholder="Description"
class="w-full border rounded-xl px-4 py-3 mb-4">
</textarea>

<div class="flex justify-end gap-3">

<button
type="button"
onclick="closeEditModal()"
class="px-5 py-3 border rounded-xl">

Annuler

</button>

<button
type="submit"
class="bg-[#1E4F86] hover:bg-[#8B5E3C] text-white px-6 py-3 rounded-xl transition duration-300">

Modifier

</button>

</div>

</form>

</div>

</div>
<script>

const modal = document.getElementById("holidayModal");

const btn = document.getElementById("openHolidayModal");
const editModal = document.getElementById("editHolidayModal");
const editHolidayForm = document.getElementById("editHolidayForm");


if(btn){

    btn.addEventListener("click", function(){

        modal.classList.remove("hidden");
        modal.classList.add("flex");

    });

}



function closeModal(){

    modal.classList.remove("flex");
    modal.classList.add("hidden");

}

document.querySelectorAll(".editHolidayBtn").forEach(function(button){

    button.addEventListener("click", function(){

        document.getElementById("editHolidayId").value = button.dataset.id;
        document.getElementById("editHolidayName").value = button.dataset.name;
        document.getElementById("editHolidayDate").value = button.dataset.date;
        document.getElementById("editHolidayType").value = button.dataset.type;
        document.getElementById("editHolidayDescription").value = button.dataset.description;
        editHolidayForm.action = editHolidayForm.dataset.baseAction + "&id=" + encodeURIComponent(button.dataset.id);

        editModal.classList.remove("hidden");
        editModal.classList.add("flex");

    });

});

function closeEditModal(){

    editModal.classList.remove("flex");
    editModal.classList.add("hidden");

}


</script>

</body>
</html>
