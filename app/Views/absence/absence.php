<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>


<?php

$totalPages = ceil($total / $limit);

if($totalPages < 1){
    $totalPages = 1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">


<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="ml-0 lg:ml-64 p-6 lg:p-10">


<!-- ================= HEADER ================= -->

<div class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C] rounded-3xl p-8 shadow-xl text-white mb-8">


<h1 class="text-4xl font-bold flex items-center gap-3">

<i class="bi bi-person-x-fill text-5xl"></i>

Gestion des Absences

</h1>


<p class="mt-3 text-blue-100">

Consultez et gérez les demandes d'absence des étudiants en temps réel.

</p>


</div>





<!-- ================= KPI ================= -->


<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">



<!-- TOTAL -->

<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-[#1E4F86]
hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex items-center justify-between">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Total Absences

</p>


<h3 class="text-4xl font-bold text-[#1E4F86] mt-2">

<?= $totalAbsencesAdmin ?>

</h3>


</div>


<div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">

<i class="bi bi-calendar-x-fill text-[#1E4F86] text-3xl"></i>

</div>


</div>


</div>






<!-- APPROUVE -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-green-600
hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex items-center justify-between">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Approuvées

</p>


<h3 class="text-4xl font-bold text-green-600 mt-2">

<?= $totalApprovedAbsencesAdmin ?>

</h3>


</div>


<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">

<i class="bi bi-check-circle-fill text-green-600 text-3xl"></i>

</div>


</div>


</div>








<!-- ATTENTE -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-[#8B5E3C]
hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex items-center justify-between">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

En attente

</p>


<h3 class="text-4xl font-bold text-[#8B5E3C] mt-2">

<?= $totalPendingAbsencesAdmin ?>

</h3>


</div>


<div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">

<i class="bi bi-hourglass-split text-[#8B5E3C] text-3xl"></i>

</div>


</div>


</div>







<!-- REFUSE -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-red-600
hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex items-center justify-between">


<div>

<p class="text-gray-500 text-sm uppercase font-semibold">

Refusées

</p>


<h3 class="text-4xl font-bold text-red-600 mt-2">

<?= $totalRefusedAbsencesAdmin ?>

</h3>


</div>


<div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">

<i class="bi bi-x-circle-fill text-red-600 text-3xl"></i>

</div>


</div>


</div>


</div>






<!-- ================= TABLE ================= -->


<div class="bg-white rounded-3xl shadow-xl overflow-hidden">





<!-- HEADER TABLE -->


<div class="px-6 py-6 border-b flex flex-col lg:flex-row justify-between gap-5">


<div>


<h2 class="text-2xl font-bold text-[#1E4F86] flex items-center gap-3">

<i class="bi bi-person-x-fill"></i>

Gestion des absences

</h2>


<p class="text-gray-500 mt-1">

Liste des demandes d'absence des étudiants

</p>


</div>







<!-- FILTRE -->

<form method="GET" action="index.php"
class="flex flex-wrap gap-3">


<input type="hidden" name="page" value="absence_admin">



<input
type="text"
name="search"
value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
placeholder="Rechercher étudiant..."
class="border rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#1E4F86]">





<select name="department"
class="border rounded-xl px-4 py-3">


<option value="">
Tous départements
</option>


<?php foreach($departments as $department): ?>

<option value="<?= $department['id'] ?>">

<?= htmlspecialchars($department['name']) ?>

</option>

<?php endforeach; ?>


</select>







<select name="cohort"
class="border rounded-xl px-4 py-3">


<option value="">
Toutes cohortes
</option>


<?php foreach($cohorts as $cohort): ?>


<option value="<?= $cohort['id'] ?>">

<?= htmlspecialchars($cohort['name']) ?>

</option>


<?php endforeach; ?>


</select>






<button
class="bg-[#1E4F86] hover:bg-[#163C68]
text-white px-6 py-3 rounded-xl
transition hover:scale-105">


<i class="bi bi-funnel-fill mr-2"></i>

Filtrer


</button>



</form>



</div>









<!-- TABLE -->

<div class="overflow-x-auto">


<table class="w-full">


<thead class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C] text-white">


<tr>


<th class="p-4 text-left">Étudiant</th>

<th class="p-4 text-left">Département</th>

<th class="p-4 text-left">Cohorte</th>

<th class="p-4 text-left">Type</th>

<th class="p-4 text-left">Période</th>

<th class="p-4 text-center">Durée</th>

<th class="p-4 text-center">Statut</th>

<th class="p-4 text-center">Actions</th>


</tr>


</thead>





<tbody>


<?php foreach($absences as $absence): ?>


<tr class="border-b hover:bg-blue-50 transition duration-300">


<td class="p-4 font-semibold">

<?= htmlspecialchars($absence['firstname'].' '.$absence['lastname']) ?>

</td>



<td class="p-4">

<?= htmlspecialchars($absence['department']) ?>

</td>



<td class="p-4">

<?= htmlspecialchars($absence['cohort']) ?>

</td>



<td class="p-4">

<?= htmlspecialchars($absence['type']) ?>

</td>



<td class="p-4">

<?= date('d/m/Y',strtotime($absence['start_date'])) ?>

au

<?= date('d/m/Y',strtotime($absence['end_date'])) ?>

</td>



<td class="text-center">

<?= $absence['duration'] ?> jour(s)

</td>





<td class="text-center">


<?php if($absence['status']=="approuve"): ?>


<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

<i class="bi bi-check-circle"></i>

Approuvé

</span>



<?php elseif($absence['status']=="en_attente"): ?>


<span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full">

<i class="bi bi-hourglass"></i>

En attente

</span>


<?php else: ?>


<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

<i class="bi bi-x-circle"></i>

Refusé

</span>


<?php endif; ?>


</td>







<td class="text-center">


<?php if($absence['status']=="en_attente"): ?>


<div class="flex justify-center gap-2">


<a href="index.php?page=approve_absence&id=<?= $absence['id'] ?>"
class="w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded-xl flex items-center justify-center hover:scale-110 transition">


<i class="bi bi-check-lg"></i>


</a>





<a href="index.php?page=refuse_absence&id=<?= $absence['id'] ?>"
class="w-10 h-10 bg-red-600 hover:bg-red-700 text-white rounded-xl flex items-center justify-center hover:scale-110 transition">


<i class="bi bi-x-lg"></i>


</a>


</div>



<?php else: ?>


<span class="text-gray-500 font-semibold">

Traité

</span>


<?php endif; ?>


</td>


</tr>


<?php endforeach; ?>


</tbody>


</table>


</div>





<!-- PAGINATION -->


<div class="flex justify-center gap-2 p-6">


<?php for($i=1;$i<=$totalPages;$i++): ?>


<a href="index.php?page=absence_admin&p=<?=$i?>"

class="w-10 h-10 rounded-xl flex items-center justify-center border transition

<?=($currentPage==$i)

?'bg-[#1E4F86] text-white'

:'hover:bg-[#8B5E3C] hover:text-white'

?>">


<?=$i?>


</a>


<?php endfor; ?>


</div>



</div>




</main>

</body>
</html>