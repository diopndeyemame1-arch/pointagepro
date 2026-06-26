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
     <main class="flex-1 ml-64 p-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold">
                Jours feriés
            </h2>

            <p class="text-gray-500">
                Consultez et suivez les jours feriés des etudiants en temps réel.
            </p>
        </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

    <!-- Total -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-blue-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="bi bi-calendar-event text-blue-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-gray-500 text-sm uppercase">Total jours fériés</p>
                <h3 class="text-3xl font-bold">15</h3>
            </div>
        </div>
    </div>

    <!-- Ce mois -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-green-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                <i class="bi bi-calendar2-week text-green-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-gray-500 text-sm uppercase">Ce mois-ci</p>
                <h3 class="text-3xl font-bold">2</h3>
            </div>
        </div>
    </div>

    <!-- Prochain -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-orange-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="bi bi-alarm text-orange-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-gray-500 text-sm uppercase">Prochain férié</p>
                <h3 class="text-xl font-bold">Tabaski</h3>
            </div>
        </div>
    </div>

    <!-- Restants -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-purple-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="bi bi-calendar-check text-purple-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-gray-500 text-sm uppercase">Restants</p>
                <h3 class="text-3xl font-bold">8</h3>
            </div>
        </div>
    </div>

</div>
<div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold flex items-center gap-3">
            <i class="bi bi-calendar-event text-blue-600"></i>
            Liste des jours fériés
        </h2>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-green-800 text-white">
                <tr>
                    <th class="text-left p-4">Jour férié</th>
                    <th class="text-left p-4">Date</th>
                    <th class="text-left p-4">Type</th>
                    <th class="text-left p-4">Statut</th>
                </tr>
            </thead>

            <tbody>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">Nouvel An</td>
                    <td class="p-4">01/01/2026</td>
                    <td class="p-4">National</td>
                    <td class="p-4">
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                            Passé
                        </span>
                    </td>
                </tr>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">Fête du Travail</td>
                    <td class="p-4">01/05/2026</td>
                    <td class="p-4">National</td>
                    <td class="p-4">
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                            Passé
                        </span>
                    </td>
                </tr>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">Tabaski</td>
                    <td class="p-4">27/06/2026</td>
                    <td class="p-4">Religieux</td>
                    <td class="p-4">
                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                            À venir
                        </span>
                    </td>
                </tr>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">Tamkharit</td>
                    <td class="p-4">16/07/2026</td>
                    <td class="p-4">Religieux</td>
                    <td class="p-4">
                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                            À venir
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