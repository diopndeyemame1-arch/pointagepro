<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-100">
<?php require_once '../layouts/sidebar.php'; ?>
<main class="ml-64 p-8">
 <!-- Scanner QR Code -->
       <div class="grid md:grid-cols-2 gap-6 mb-6">

    <!-- QR Code Manager -->
    <div class="bg-white rounded-2xl shadow p-6 text-center">

        <h3 class="text-xl font-bold mb-4">
            Mon QR Code Manager
        </h3>

        <img
            src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=MAN001"
            class="mx-auto border p-3 rounded-xl">

        <h4 class="font-semibold mt-4">
            Moussa Ba
        </h4>

        <p class="text-gray-500">
            ID : MAN001
        </p>

        <button
            class="mt-4 bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-xl">

            <i class="bi bi-download"></i>
            Télécharger

        </button>

    </div>

    <!-- Scanner QR Code -->
    <div class="bg-white rounded-2xl shadow p-6">

        <h3 class="text-xl font-bold text-center mb-4">
            Scanner un QR Code
        </h3>

        <p class="text-center text-gray-500 mb-4">
            Scanner le QR Code d'un étudiant.
        </p>

        <div class="border-4 border-dashed border-green-500 rounded-2xl h-64 flex items-center justify-center mb-4">

            <div class="text-center">

                <i class="bi bi-camera text-6xl text-gray-400"></i>

                <p class="mt-3 text-gray-500">
                    Caméra inactive
                </p>

            </div>

        </div>

        <div class="text-center">

            <button
                class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl">

                <i class="bi bi-camera-fill"></i>
                Activer la caméra

            </button>

        </div>

    </div>

</div>
</main>
</body>
</html>