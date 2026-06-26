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
   <main class="flex-1 ml-64 p-8">

    <div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold flex items-center gap-3">
            <i class="bi bi-person-x-fill text-red-600"></i>
            Mes Absences
        </h2>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-green-800 text-white">
                <tr>
                    <th class="text-left p-4">Date</th>
                    <th class="text-left p-4">Motif</th>
                    <th class="text-left p-4">Justificatif</th>
                    <th class="text-left p-4">Statut</th>
                </tr>
            </thead>

            <tbody>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">15/06/2026</td>
                    <td class="p-4">Maladie</td>

                    <td class="p-4">
                        <button class="text-blue-600">
                            <i class="bi bi-file-earmark-pdf"></i>
                            Voir
                        </button>
                    </td>

                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Justifiée
                        </span>
                    </td>
                </tr>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">10/06/2026</td>
                    <td class="p-4">Absence non signalée</td>

                    <td class="p-4 text-gray-400">
                        Aucun
                    </td>

                    <td class="p-4">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                            Non justifiée
                        </span>
                    </td>
                </tr>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">05/06/2026</td>
                    <td class="p-4">Rendez-vous médical</td>

                    <td class="p-4">
                        <button class="text-blue-600">
                            <i class="bi bi-file-earmark-pdf"></i>
                            Voir
                        </button>
                    </td>

                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Justifiée
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