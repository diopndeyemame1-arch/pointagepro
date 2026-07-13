<?php

if(session_status() === PHP_SESSION_NONE){
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
}

$logs = $logs ?? [];
$totalLogs = $totalLogs ?? count($logs);
$auditStats = $auditStats ?? [
    'CREATE' => count(array_filter($logs, function($l){ return ($l['action'] ?? '') == 'CREATE'; })),
    'UPDATE' => count(array_filter($logs, function($l){ return ($l['action'] ?? '') == 'UPDATE'; })),
    'DELETE' => count(array_filter($logs, function($l){ return ($l['action'] ?? '') == 'DELETE'; })),
];

$currentPage = (int)($currentPage ?? ($_GET['p'] ?? 1));
$totalPages = (int)($totalPages ?? 1);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Journal d'audit - PointagePro</title>


<script src="https://cdn.tailwindcss.com"></script>


<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


</head>


<body class="bg-slate-100">


<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>



<main class="ml-0 lg:ml-64 p-6 lg:p-10">



<!-- HEADER -->

<div class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C]
rounded-3xl p-8 shadow-xl text-white mb-8">


<div class="flex flex-col lg:flex-row justify-between gap-6">


<div>


<h1 class="text-4xl font-bold flex items-center gap-3">


<i class="bi bi-shield-lock-fill text-5xl"></i>


Journal d'audit


</h1>



<p class="mt-3 text-blue-100">


Traçabilité complète des actions utilisateurs


</p>


</div>





<div class="bg-white/20 backdrop-blur rounded-2xl p-5">


<div class="flex items-center gap-4">


<div class="w-14 h-14 bg-white/30 rounded-full 
flex items-center justify-center">


<i class="bi bi-clock-history text-3xl"></i>


</div>


<div>


<p class="text-sm">

Événements

</p>


<h2 class="text-3xl font-bold">


<?= $totalLogs ?>


</h2>


</div>


</div>


</div>




</div>


</div>









<!-- KPI -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">





<!-- CREATION -->


<div class="bg-white rounded-2xl shadow-lg p-6
border-l-8 border-[#1E4F86]
hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex justify-between items-center">


<div>


<p class="text-gray-500 uppercase text-sm font-semibold">

Créations

</p>


<h3 class="text-4xl font-bold text-[#1E4F86]">


<?= $auditStats['CREATE'] ?? 0 ?>


</h3>


</div>



<div class="w-16 h-16 bg-blue-100 rounded-full 
flex items-center justify-center">


<i class="bi bi-plus-circle-fill 
text-[#1E4F86] text-3xl"></i>


</div>


</div>


</div>










<!-- MODIFICATION -->


<div class="bg-white rounded-2xl shadow-lg p-6
border-l-8 border-[#8B5E3C]
hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex justify-between items-center">


<div>


<p class="text-gray-500 uppercase text-sm font-semibold">

Modifications

</p>


<h3 class="text-4xl font-bold text-[#8B5E3C]">


<?= $auditStats['UPDATE'] ?? 0 ?>


</h3>


</div>



<div class="w-16 h-16 bg-orange-100 rounded-full 
flex items-center justify-center">


<i class="bi bi-pencil-square 
text-[#8B5E3C] text-3xl"></i>


</div>


</div>


</div>









<!-- SUPPRESSION -->


<div class="bg-white rounded-2xl shadow-lg p-6
border-l-8 border-red-600
hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex justify-between items-center">


<div>


<p class="text-gray-500 uppercase text-sm font-semibold">

Suppressions

</p>


<h3 class="text-4xl font-bold text-red-600">


<?= $auditStats['DELETE'] ?? 0 ?>


</h3>


</div>



<div class="w-16 h-16 bg-red-100 rounded-full 
flex items-center justify-center">


<i class="bi bi-trash-fill 
text-red-600 text-3xl"></i>


</div>


</div>


</div>




</div>









<!-- TABLE -->


<div class="bg-white rounded-3xl shadow-xl overflow-hidden">



<div class="px-6 py-6 border-b">


<h2 class="text-2xl font-bold text-[#1E4F86]
flex items-center gap-3">


<i class="bi bi-database-fill"></i>


Historique des activités


</h2>


</div>






<div class="overflow-x-auto">


<table class="w-full">


<thead 
class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C]
text-white">


<tr>


<th class="p-4 text-left">
Utilisateur
</th>


<th class="p-4 text-left">
Action
</th>


<th class="p-4 text-left">
Module
</th>


<th class="p-4 text-left">
Objet
</th>


<th class="p-4 text-left">
IP
</th>


<th class="p-4 text-left">
Date
</th>


</tr>


</thead>






<tbody>


<?php if(empty($logs)): ?>


<tr>


<td colspan="6" class="p-6 text-center text-gray-500">


Aucune activite enregistre.


</td>


</tr>


<?php else: ?>


<?php foreach($logs as $log): ?>


<tr class="border-b hover:bg-blue-50 transition">


<td class="p-4">


<p class="font-bold">


<?= htmlspecialchars(
($log['firstname'] ?? 'Inconnu')
." ".
($log['lastname'] ?? '')
) ?>


</p>


<p class="text-xs text-gray-500">


<?= htmlspecialchars($log['email'] ?? '') ?>


</p>


</td>






<td class="p-4">


<?php

switch($log['action']){

case "CREATE":

$color="bg-blue-100 text-[#1E4F86]";
$icon="bi-plus-circle";

break;


case "UPDATE":

$color="bg-orange-100 text-[#8B5E3C]";
$icon="bi-pencil-square";

break;


case "DELETE":

$color="bg-red-100 text-red-700";
$icon="bi-trash";

break;


default:

$color="bg-gray-100";
$icon="bi-info-circle";

}

?>


<span class="<?= $color ?> px-3 py-2 rounded-full 
flex gap-2 items-center w-fit font-semibold">


<i class="bi <?= $icon ?>"></i>


<?= $log['action'] ?>


</span>


</td>







<td class="p-4">


<span class="bg-slate-100 px-3 py-1 rounded-lg">


<?= htmlspecialchars($log['entity'] ?? '--') ?>


</span>


</td>







<td class="p-4 font-mono text-gray-500">


<?= isset($log['entity_id']) 
? substr((string)$log['entity_id'],0,8).'...'
:'--'
?>


</td>







<td class="p-4 font-mono">


<?= htmlspecialchars($log['ip'] ?? '--') ?>


</td>






<td class="p-4 text-gray-500">


<?= isset($log['created_at'])
? date("d/m/Y H:i",strtotime($log['created_at']))
:'--'
?>


</td>




</tr>


<?php endforeach; ?>


<?php endif; ?>


</tbody>


</table>


</div>







<!-- PAGINATION -->


<div class="flex justify-center gap-3 p-6">


<?php

$totalPages = $totalPages ?? 1;


for($i=1;$i<=$totalPages;$i++):

?>


<a href="index.php?page=audit_logs&p=<?=$i?>"

class="w-10 h-10 rounded-xl border flex items-center justify-center
transition duration-300

<?= $currentPage==$i

?'bg-[#1E4F86] text-white scale-110'

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
