<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$stats = $data['stats'] ?? [
    'active_qr' => 0,
    'scans_today' => 0,
    'present_today' => 0,
    'late_today' => 0,
];
$scans = $data['scans'] ?? [];

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>QR Code Pointage - PointagePro</title>


<script src="https://cdn.tailwindcss.com"></script>


<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<script src="https://unpkg.com/html5-qrcode"></script>


</head>



<body class="bg-slate-100">


<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>



<main class="ml-0 lg:ml-64 p-6 lg:p-10">





<!-- ================= HEADER ================= -->


<div class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C]
rounded-3xl p-8 shadow-xl text-white mb-8">


<div class="flex flex-col lg:flex-row justify-between gap-6">


<div>


<h1 class="text-4xl font-bold flex items-center gap-3">


<i class="bi bi-qr-code-scan text-5xl"></i>


Gestion du QR Code de pointage


</h1>



<p class="mt-3 text-blue-100">


Générez et scannez les QR Codes pour enregistrer les présences.


</p>


</div>





<div class="bg-white/20 backdrop-blur rounded-2xl p-5">


<div class="flex items-center gap-4">


<div class="w-14 h-14 rounded-full bg-white/30 
flex items-center justify-center">


<i class="bi bi-camera-fill text-3xl"></i>


</div>


<div>


<p class="text-sm">

Scanner actif

</p>


<h3 class="text-2xl font-bold">

QR

</h3>


</div>


</div>


</div>


</div>


</div>









<!-- ================= KPI ================= -->



<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">





<!-- QR ACTIF -->

<div class="bg-white rounded-2xl shadow-lg p-6
border-l-8 border-[#1E4F86]

hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex items-center justify-between">


<div>


<p class="text-gray-500 text-sm uppercase font-semibold">

QR Actif

</p>


<h3 class="text-4xl font-bold text-[#1E4F86] mt-2">

<?= (int)$stats['active_qr'] ?>

</h3>


</div>




<div class="w-16 h-16 rounded-full bg-blue-100
flex items-center justify-center">


<i class="bi bi-qr-code text-[#1E4F86] text-3xl"></i>


</div>



</div>


</div>









<!-- SCANS -->



<div class="bg-white rounded-2xl shadow-lg p-6
border-l-8 border-green-600

hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex items-center justify-between">


<div>


<p class="text-gray-500 text-sm uppercase font-semibold">

Scans aujourd'hui

</p>


<h3 class="text-4xl font-bold text-green-600 mt-2">

<?= (int)$stats['scans_today'] ?>

</h3>


</div>




<div class="w-16 h-16 rounded-full bg-green-100
flex items-center justify-center">


<i class="bi bi-phone-fill text-green-600 text-3xl"></i>


</div>



</div>


</div>









<!-- PRESENTS -->


<div class="bg-white rounded-2xl shadow-lg p-6
border-l-8 border-purple-600

hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex items-center justify-between">


<div>


<p class="text-gray-500 text-sm uppercase font-semibold">

Présences

</p>


<h3 class="text-4xl font-bold text-purple-600 mt-2">

<?= (int)$stats['present_today'] ?>

</h3>


</div>




<div class="w-16 h-16 rounded-full bg-purple-100
flex items-center justify-center">


<i class="bi bi-person-check-fill text-purple-600 text-3xl"></i>


</div>



</div>


</div>









<!-- RETARDS -->



<div class="bg-white rounded-2xl shadow-lg p-6
border-l-8 border-[#8B5E3C]

hover:-translate-y-2 hover:shadow-2xl
transition duration-300">


<div class="flex items-center justify-between">


<div>


<p class="text-gray-500 text-sm uppercase font-semibold">

Retards

</p>


<h3 class="text-4xl font-bold text-[#8B5E3C] mt-2">

<?= (int)$stats['late_today'] ?>

</h3>


</div>




<div class="w-16 h-16 rounded-full bg-orange-100
flex">

<div class="m-auto">

<i class="bi bi-clock-history text-[#8B5E3C] text-3xl"></i>

</div>

</div>



</div>


</div>





</div>









<!-- ================= QR + SCANNER ================= -->


<div class="grid lg:grid-cols-2 gap-8 mb-8">





<!-- QR CODE -->

<div class="bg-white rounded-3xl shadow-xl p-8">



<div class="flex justify-between items-center mb-6">


<h2 class="text-2xl font-bold text-[#1E4F86]
flex items-center gap-3">


<i class="bi bi-qr-code-scan"></i>


QR Code actif


</h2>






<form action="index.php?page=qr_code&action=generate"
method="POST">


<button

class="bg-[#1E4F86]
hover:bg-[#163C68]
text-white px-5 py-3 rounded-xl
flex items-center gap-2
transition">


<i class="bi bi-arrow-repeat"></i>


Générer


</button>


</form>



</div>







<div class="text-center">


<?php if(file_exists(__DIR__.'/../../../public/uploads/qrcodes/qr_actif.png')): ?>


<img

src="/uploads/qrcodes/qr_actif.png?<?=time()?>"

class="mx-auto border-4 border-[#1E4F86]
rounded-2xl p-4 shadow-lg"

width="260"

>


<?php else: ?>


<div class="p-10 text-gray-500">

<i class="bi bi-qr-code text-5xl"></i>


<p class="mt-3">

Aucun QR généré

</p>


</div>


<?php endif; ?>







<h3 class="text-xl font-bold mt-5">

QR PointagePro

</h3>



<p class="text-gray-500 mt-2">


Les étudiants utilisent ce QR pour enregistrer leur présence.


</p>





<a

href="/uploads/qrcodes/qr_actif.png"

download="QR_PointagePro.png"


class="inline-flex mt-5 bg-[#8B5E3C]
hover:bg-[#6F482D]
text-white px-6 py-3 rounded-xl
items-center gap-2 transition">

<i class="bi bi-printer-fill"></i>
Imprimer
</a>
</div>
</div>
<!-- ================= SCANNER QR ================= -->


<div class="bg-white rounded-3xl shadow-xl p-8">


<h2 class="text-2xl font-bold text-[#1E4F86]
flex items-center gap-3 mb-6">


<i class="bi bi-camera-fill"></i>


Scanner un QR Code


</h2>





<div id="reader"

class="w-full h-[450px]
border-4 border-[#1E4F86]
rounded-2xl overflow-hidden">


</div>





<p id="result"

class="mt-5 text-center font-semibold text-lg">


</p>






<div class="flex justify-center gap-4 mt-6">





<button

id="btnStart"

class="bg-[#1E4F86]
hover:bg-[#163C68]
text-white px-6 py-3 rounded-xl

flex items-center gap-2
transition">


<i class="bi bi-camera-fill"></i>


Activer


</button>







<button

id="btnStop"

class="bg-red-600
hover:bg-red-700

text-white px-6 py-3 rounded-xl

flex items-center gap-2
transition">


<i class="bi bi-x-circle-fill"></i>


Arrêter


</button>





</div>



</div>



</div>









<!-- ================= HISTORIQUE ================= -->



<div class="bg-white rounded-3xl shadow-xl overflow-hidden">


<div class="p-6 border-b">


<h2 class="text-2xl font-bold text-[#1E4F86]
flex items-center gap-3">


<i class="bi bi-clock-history"></i>


Historique des scans


</h2>



</div>






<div class="overflow-x-auto">


<table class="w-full">


<thead

class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C]
text-white">


<tr>


<th class="p-4 text-left">

Étudiant

</th>


<th class="p-4 text-left">

DÃ©partement

</th>


<th class="p-4 text-left">

Cohorte

</th>


<th class="p-4 text-left">

Date

</th>


<th class="p-4 text-left">

Heure

</th>


<th class="p-4 text-left">

Statut

</th>


</tr>


</thead>





<tbody id="historiqueScans">


<?php if (!empty($scans)): ?>

<?php foreach ($scans as $scan): ?>

<tr class="border-b hover:bg-blue-50 transition">

<td class="p-4 font-semibold">
<?= htmlspecialchars(($scan['firstname'] ?? '') . ' ' . ($scan['lastname'] ?? '')) ?>
</td>

<td class="p-4">
<?= htmlspecialchars($scan['department'] ?? 'Non defini') ?>
</td>

<td class="p-4">
<?= htmlspecialchars($scan['cohort'] ?? 'Non defini') ?>
</td>

<td class="p-4">
<?= htmlspecialchars($scan['date'] ?? date('Y-m-d')) ?>
</td>

<td class="p-4">
<?= htmlspecialchars($scan['check_in'] ?? '') ?>
</td>

<td class="p-4">
<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
<i class="bi bi-check-circle"></i>
<?= htmlspecialchars(($scan['status'] ?? 'present') === 'retard' ? 'Retard' : 'PrÃ©sent') ?>
</span>
</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>


<td colspan="6"

class="text-center p-8 text-gray-500">


Aucun scan enregistré


</td>


</tr>


<?php endif; ?>

</tbody>



</table>


</div>



</div>






</main>









<script>


let html5QrCode = new Html5Qrcode("reader");


let dernierQr = "";





// ====================
// DEMARRER CAMERA
// ====================


document
.getElementById("btnStart")
.addEventListener("click",function(){



Html5Qrcode.getCameras()

.then(cameras=>{


if(cameras.length===0){

alert("Aucune caméra détectée");

return;

}





html5QrCode.start(


cameras[0].id,


{


fps:10,

qrbox:250


},





async function(decodedText){



// éviter double scan

if(decodedText === dernierQr){

return;

}



dernierQr = decodedText;






try{



let response = await fetch(


"/index.php?page=scan_qr",


{


method:"POST",


headers:{


"Content-Type":
"application/x-www-form-urlencoded"


},



body:

"uuid="+encodeURIComponent(decodedText)



}



);






let text = await response.text();




let data;




try{


data = JSON.parse(text);


}

catch(e){


console.log(text);


document.getElementById("result").innerHTML=


`<span class="text-red-600">

Erreur serveur

</span>`;


return;


}








if(!data.success){


document.getElementById("result").innerHTML=


`<span class="text-red-600">

${data.message}

</span>`;


return;


}







document.getElementById("result").innerHTML=


`

<span class="text-green-700">

<i class="bi bi-check-circle-fill"></i>

${data.etudiant.firstname}

${data.etudiant.lastname}

Présent

</span>

`;





ajouterLigne(data.etudiant);





}



catch(error){



console.error(error);



}




},



(error)=>{



}

);




});



});









// ====================
// STOP CAMERA
// ====================


document

.getElementById("btnStop")

.addEventListener("click",function(){



html5QrCode.stop()

.then(()=>{


document.getElementById("result").innerHTML=


`

<span class="text-gray-500">

Scanner arrêté

</span>

`;



})

.catch(err=>{


console.log(err);


});



});









// ====================
// AJOUT HISTORIQUE
// ====================


function ajouterLigne(etudiant){



const tbody =
document.getElementById("historiqueScans");



let vide = tbody.querySelector("td[colspan]");



if(vide){

tbody.innerHTML="";

}





let maintenant = new Date();



let date =
etudiant.date || maintenant.toLocaleDateString("fr-FR");



let heure =
etudiant.check_in || maintenant.toLocaleTimeString("fr-FR");








tbody.insertAdjacentHTML(

"afterbegin",


`

<tr class="border-b hover:bg-blue-50 transition">


<td class="p-4 font-semibold">


${etudiant.firstname}

${etudiant.lastname}


</td>


<td class="p-4">

${etudiant.department || "Non defini"}

</td>


<td class="p-4">

${etudiant.cohort || "Non defini"}

</td>



<td class="p-4">


${date}


</td>




<td class="p-4">


${heure}


</td>





<td class="p-4">


<span

class="bg-green-100

text-green-700

px-3 py-1

rounded-full">


<i class="bi bi-check-circle"></i>


Présent


</span>


</td>



</tr>


`

);
}
</script>
</body>
</html>
