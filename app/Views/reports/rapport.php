<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Rapports - PointagePro</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
     <?php require_once '../layouts/sidebar.php'; ?>
   

    <!-- CONTENU -->
    <main class="flex-1 ml-64 p-8">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8">

            <div>
                <h2 class="text-3xl font-bold"><i class="bi bi-file-earmark-text text-purple-800 text-4xl"></i>
                    Rapports
                </h2>
            </div>

            <div class="flex gap-3">

                <button class="bg-white border px-4 py-3 rounded-xl">
                    <i class="bi bi-calendar3"></i>
                    01 avril 2026 - 30 avril 2026
                </button>

                <button class="bg-white border px-4 py-3 rounded-xl">
                    <i class="bi bi-funnel"></i>
                    Filtres
                </button>

                <button class="bg-white border px-4 py-3 rounded-xl">
                    <i class="bi bi-download"></i>
                    Exporter
                </button>

            </div>

        </div>

        <!-- KPI -->
<div class="grid lg:grid-cols-5 md:grid-cols-2 gap-5 mb-6">

    <!-- Heures travaillées -->
    <div  class="bg-white p-5 rounded-2xl shadow border-l-8 border-blue-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="bi bi-mortarboard-fill text-blue-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Total Etudiants
                </p>
                <h3 class="text-3xl font-bold mt-1 text-blue-600">
                    3
                </h3>
            </div>
        </div>
    </div>

    <!-- Heures supplémentaires -->
    <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-orange-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="bi bi-graph-up text-orange-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Taux
                </p>
                <h3 class="text-3xl font-bold mt-1 text-orange-600">
                    50%
                </h3>
            </div>
        </div>
    </div>

    <!-- Absences -->
    <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-red-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="bi bi-person-x-fill text-red-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Absences
                </p>
                <h3 class="text-3xl font-bold mt-1 text-red-600">
                    28 
                </h3>
            </div>
        </div>
    </div>

    <!-- Retards -->
    <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-purple-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="bi bi-clock text-purple-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Retards
                </p>
                <h3 class="text-3xl font-bold mt-1 text-purple-600">
                    42 
                </h3>
            </div>
        </div>
    </div>

    <!-- Présence -->
    <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-green-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                <i class="bi bi-check-circle-fill text-green-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">
                    Présences
                </p>
                <h3 class="text-3xl font-bold mt-1 text-green-600">
                    94
                </h3>
            </div>
        </div>
    </div>

</div>
       

        <!-- TABLEAU -->
        <div class="bg-white rounded-2xl shadow p-6 mb-6">

            <h3 class="font-bold text-xl mb-5">
                Résumé des pointages
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full ">

                    <thead class="bg-green-800 text-white">

                        <tr>
                            <th class="p-3 text-left">Département</th>
                            <th class="p-3 text-left">Etudiants</th>
                            <th class="p-3 text-left">Cohortes</th>
                            <th class="p-3 text-left">A l'heures</th>
                            <th class="p-3 text-left">En retard</th>
                            <th class="p-3 text-left">Absences</th>
                            <th class="p-3 text-left">Taux</th>
                        </tr>

                    </thead>

                    <tbody>

    <!-- Informatique -->
    <tr class="border-b hover:bg-slate-50">

        <td class="p-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-laptop text-blue-600"></i>
                </div>

                <span class="font-medium">
                    Developpement Web
                </span>
            </div>
        </td>

        <td class="p-3">Sweet Angel</td>
        <td class="p-3">Cohorte 1</td>
        <td class="p-3">1</td>
        <td class="p-3">0</td>
        <td class="p-3">2 jours</td>

        <td class="p-3">
            <span class="text-green-600 font-semibold">
                96%
            </span>
        </td>
    </tr>

    <!-- Ressources Humaines -->
    <tr class="border-b hover:bg-slate-50">

        <td class="p-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-people-fill text-purple-600"></i>
                </div>

                <span class="font-medium">
                    Bureautique
                </span>
            </div>
        </td>

        <td class="p-3">Ndeye Diop</td>
        <td class="p-3">Cohorte 2</td>
        <td class="p-3">0</td>
        <td class="p-3">2</td>
        <td class="p-3">1 jour</td>

        <td class="p-3">
            <span class="text-purple-600 font-semibold">
                98%
            </span>
        </td>
    </tr>

    <!-- Comptabilité -->
    <tr class="hover:bg-slate-50">

        <td class="p-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-cash-stack text-orange-600"></i>
                </div>

                <span class="font-medium">
                    Marketing Digital
                </span>
            </div>
        </td>

        <td class="p-3">Rokhaya</td>
        <td class="p-3">Cohorte 3</td>
        <td class="p-3">0</td>
        <td class="p-3">0</td>
        <td class="p-3">3 jours</td>

        <td class="p-3">
            <span class="text-yellow-600 font-semibold">
                92%
            </span>
        </td>
    </tr>

</tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>
</html>