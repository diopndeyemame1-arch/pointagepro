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
  <?php require_once '../layouts/sidebar.php'; ?>
  <main class="flex-1 ml-64 p-8 ">

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold">
            Bonjour, Étudiant 👋
        </h2>

        <p class="text-gray-500">
            Voici un résumé de votre activité.
        </p>
    </div>

    <!-- KPI Cards -->
    <div class="grid md:grid-cols-4 gap-6 mb-6">

        <!-- Présences -->
        <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-green-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="bi bi-check-circle-fill text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm uppercase">Présences</p>
                    <h3 class="text-3xl font-bold">18</h3>
                </div>
            </div>
        </div>

        <!-- Absences -->
        <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-red-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="bi bi-person-x-fill text-red-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm uppercase">Absences</p>
                    <h3 class="text-3xl font-bold">4</h3>
                </div>
            </div>
        </div>

        <!-- Congés -->
        <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-blue-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="bi bi-airplane-fill text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm uppercase">Congés</p>
                    <h3 class="text-3xl font-bold">2</h3>
                </div>
            </div>
        </div>

        <!-- Retard -->
        <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-orange-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="bi bi-clock-fill text-orange-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm uppercase">Retards</p>
                    <h3 class="text-3xl font-bold">3</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Infos rapides -->
    <div class="grid md:grid-cols-3 gap-6">

        <!-- Statut aujourd'hui -->
        <div class="bg-white rounded-2xl shadow p-9">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="bi bi-calendar-check text-green-600"></i>
                Statut du jour
            </h3>

            <p class="text-2xl font-bold text-green-600">
                Présent
            </p>

            <p class="text-gray-500 text-sm mt-2">
                Pointage effectué à 08:05
            </p>
        </div>

        <!-- Prochaine activité -->
        <div class="bg-white rounded-2xl shadow p-9">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="bi bi-bell text-blue-600"></i>
                Prochaine activité
            </h3>

            <p class="font-semibold">
                Cours PHP & MySQL
            </p>

            <p class="text-gray-500 text-sm">
                Aujourd'hui à 10h00
            </p>
        </div>

        <!-- QR Code -->
        <div class="bg-white rounded-2xl shadow p-6 text-center">

            <h3 class="font-bold mb-4 flex items-center justify-center gap-2">
                <i class="bi bi-qr-code text-purple-600"></i>
                Mon QR Code
            </h3>

            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=ETU001"
                 class="mx-auto">

            <p class="text-gray-500 text-sm mt-2">
                Utilisez ce code pour le pointage
            </p>

        </div>

    </div>

</main>

</body>
</html>