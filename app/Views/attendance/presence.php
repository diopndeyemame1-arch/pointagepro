<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Models/Attendance.php';
require_once __DIR__ . '/../../Controllers/AttendanceController.php';

$controller = new AttendanceController($pdo);

$currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;

if ($currentPage < 1) {
    $currentPage = 1;
}
$limit = 6;

$data = $controller->adminData($currentPage, $limit);

$users = $data['users'];
$totalStudents = $data['total_students'];
$present = $data['present'];
$late = $data['late'];

$totalPages = ceil($totalStudents / $limit);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Présences Admin</title>

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

<i class="bi bi-calendar-check-fill text-5xl"></i>

Présences Étudiants

</h1>


<p class="mt-3 text-blue-100">

Suivi des présences et des pointages en temps réel.

</p>


</div>






<!-- ================= KPI ================= -->


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">



<!-- PRESENT -->

<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-green-600 hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 uppercase text-sm font-semibold">

Présents

</p>


<h2 class="text-4xl font-bold text-green-600 mt-2">

<?= $present ?>

</h2>


</div>


<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">

<i class="bi bi-check-circle-fill text-green-600 text-3xl"></i>

</div>


</div>


</div>






<!-- RETARD -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-[#8B5E3C] hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 uppercase text-sm font-semibold">

Retards

</p>


<h2 class="text-4xl font-bold text-[#8B5E3C] mt-2">

<?= $late ?>

</h2>


</div>


<div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">

<i class="bi bi-clock-history text-[#8B5E3C] text-3xl"></i>

</div>


</div>


</div>







<!-- TOTAL -->


<div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-[#1E4F86] hover:-translate-y-2 hover:shadow-2xl transition duration-300">


<div class="flex justify-between items-center">


<div>

<p class="text-gray-500 uppercase text-sm font-semibold">

Étudiants

</p>


<h2 class="text-4xl font-bold text-[#1E4F86] mt-2">

<?= $totalStudents ?>

</h2>


</div>


<div class="w-16 h-16 bg-blue-100 rounded-full flex justify-center items-center">

<i class="bi bi-people-fill text-[#1E4F86] text-3xl"></i>

</div>


</div>


</div>



</div>








<!-- ================= LISTE ================= -->



<div class="bg-white rounded-3xl shadow-xl p-6">



<div class="flex justify-between items-center mb-6">


<div>


<h2 class="text-2xl font-bold text-[#1E4F86] flex items-center gap-2">


<i class="bi bi-list-check"></i>

Liste des présences


</h2>


<p class="text-gray-500 text-sm">

État actuel des étudiants

</p>


</div>


<i class="bi bi-person-lines-fill text-3xl text-gray-400"></i>


</div>








<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">



<?php foreach($users as $user): ?>


<?php $status=$user['status'] ?? 'absent'; ?>



<div class="bg-white  rounded-2xl p-5 shadow-xl hover:shadow-xl hover:-translate-y-1 transition duration-300">



<!-- USER -->


<div class="flex justify-between items-start mb-5">



<div class="flex gap-3 items-center">


<img src="/<?= $user['photo'] ?>"
class="w-16 h-16 rounded-full object-cover border-2 border-blue-100">


<div>

<h3 class="font-bold text-slate-800">

<?= $user['firstname'].' '.$user['lastname'] ?>

</h3>


<p class="text-sm text-gray-500">

<?= htmlspecialchars($user['department_name']) ?>

</p>


</div>


</div>





<!-- STATUS -->


<?php if($status=="present"): ?>


<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">

Présent

</span>


<?php elseif($status=="retard"): ?>


<span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm font-semibold">

Retard

</span>


<?php else: ?>


<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">

Absent

</span>


<?php endif; ?>


</div>







<!-- DETAILS -->


<div class="space-y-3 text-sm">



<div class="flex justify-between">

<span class="text-gray-500">

<i class="bi bi-people text-[#8B5E3C]"></i>

Cohorte

</span>


<strong>

<?= htmlspecialchars($user['cohort_name']) ?>

</strong>


</div>





<div class="flex justify-between">

<span class="text-gray-500">

<i class="bi bi-envelope text-blue-600"></i>

Email

</span>


<strong class="text-xs">

<?= $user['email'] ?>

</strong>


</div>





<div class="flex justify-between">

<span class="text-gray-500">

<i class="bi bi-box-arrow-in-right text-green-600"></i>

Entrée

</span>


<strong>

<?= $user['check_in'] ?? '--:--' ?>

</strong>


</div>






<div class="flex justify-between">

<span class="text-gray-500">

<i class="bi bi-box-arrow-right text-red-600"></i>

Sortie

</span>


<strong>

<?= $user['check_out'] ?? '--:--' ?>

</strong>


</div>



</div>



</div>



<?php endforeach; ?>


</div>









<!-- PAGINATION -->


<div class="flex justify-center mt-8 gap-2">


<?php for($i=1;$i<=$totalPages;$i++): ?>


<a href="index.php?page=presence_admin&p=<?=$i?>"

class="px-4 py-2 rounded-xl font-semibold transition

<?=($i==$currentPage)

?'bg-[#1E4F86] text-white'

:'bg-slate-100 hover:bg-[#8B5E3C] hover:text-white'

?>">


<?=$i?>


</a>



<?php endfor; ?>


</div>



</div>




</main>


</body>
</html>