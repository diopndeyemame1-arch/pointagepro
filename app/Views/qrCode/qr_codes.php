<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


</head>
<body class="bg-slate-100">
     <?php require_once '../layouts/sidebar.php'; ?>
     <main class="flex-1 ml-64 p-8">
     <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

    <!-- QR Actif -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-blue-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="bi bi-qr-code-scan text-blue-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm uppercase">QR Actif</p>
                <h3 class="text-3xl font-bold">1</h3>
            </div>
        </div>
    </div>

    <!-- Scans aujourd'hui -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-green-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                <i class="bi bi-phone text-green-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm uppercase">Scans du jour</p>
                <h3 class="text-3xl font-bold">125</h3>
            </div>
        </div>
    </div>

    <!-- Présences -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-purple-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="bi bi-person-check-fill text-purple-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm uppercase">Présences</p>
                <h3 class="text-3xl font-bold">118</h3>
            </div>
        </div>
    </div>

    <!-- Retards -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-orange-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="bi bi-clock-history text-orange-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm uppercase">Retards</p>
                <h3 class="text-3xl font-bold">7</h3>
            </div>
        </div>
    </div>

</div>
<div class="grid md:grid-cols-2 gap-6 mb-6">

    <!-- QR Code -->
    <div class="bg-white rounded-2xl shadow p-8">

        <div class="flex justify-between items-center mb-6">

            <h2 class="text-2xl font-bold flex items-center gap-3">
                <i class="bi bi-qr-code-scan text-green-700"></i>
                QR Code de Pointage
            </h2>

            <button class="bg-green-800 hover:bg-green-700 text-white px-4 py-2 rounded-xl">
                Générer
            </button>

        </div>

        <div class="text-center">

            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=POINTAGEPRO"
                class="mx-auto border p-3 rounded-xl">

            <h3 class="text-lg font-semibold mt-4">
                QR Actif
            </h3>

            <p class="text-gray-500 mt-2">
                Les étudiants peuvent scanner ce QR Code.
            </p>

            <button class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl">
                Télécharger
            </button>

        </div>

    </div>

    <!-- Scanner QR -->
    <div class="bg-white rounded-2xl shadow p-8">

        <h2 class="text-2xl font-bold flex items-center gap-3 mb-6">
            <i class="bi bi-camera-fill text-green-700"></i>
            Scanner QR Code
        </h2>

        <div class="border-4 border-green-500 rounded-2xl h-72 flex items-center justify-center bg-slate-50">

            <div class="w-52 h-52 border-4 border-green-600 rounded-xl relative">

                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-green-700"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-green-700"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-green-700"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-green-700"></div>

            </div>

        </div>

        <div class="flex justify-center gap-3 mt-6">

            <button class="bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-xl flex items-center gap-2">
                <i class="bi bi-camera-fill"></i>
                Activer
            </button>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl flex items-center gap-2">
                <i class="bi bi-qr-code-scan"></i>
                Scanner
            </button>

        </div>

    </div>

</div>
<div class="bg-white rounded-2xl shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Historique des scans
    </h2>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-green-800 text-white">
                <tr>
                    <th class="p-4 text-left">Étudiant</th>
                    <th class="p-4 text-left">Date</th>
                    <th class="p-4 text-left">Heure</th>
                    <th class="p-4 text-left">Statut</th>
                </tr>
            </thead>

            <tbody>

                <tr class="border-t">
                    <td class="p-4">Fatou Sow</td>
                    <td class="p-4">15/06/2026</td>
                    <td class="p-4">08:01</td>
                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                            Présente
                        </span>
                    </td>
                </tr>

                <tr class="border-t">
                    <td class="p-4">Moussa Ba</td>
                    <td class="p-4">15/06/2026</td>
                    <td class="p-4">08:18</td>
                    <td class="p-4">
                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                            Retard
                        </span>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>
</main>
</body>
</html>
