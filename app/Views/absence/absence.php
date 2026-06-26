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

    <!-- Cartes KPI -->
     <main class="flex-1 ml-64 p-8 ">
        <div class="mb-10">
            <h2 class="text-3xl font-bold">
                Absences 
            </h2>

            <p class="text-gray-500">
                Consultez et suivez les absences des etudiants en temps réel.
            </p>
        </div>
<div class="grid md:grid-cols-4 gap-6 mb-6">

    <!-- Total absences -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-red-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="bi bi-person-x-fill text-red-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-gray-500 text-sm uppercase">Total absences</p>
                <h3 class="text-3xl font-bold">28</h3>
            </div>
        </div>
    </div>

    <!-- Aujourd'hui -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-orange-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="bi bi-calendar-x text-orange-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-gray-500 text-sm uppercase">Aujourd'hui</p>
                <h3 class="text-3xl font-bold">5</h3>
            </div>
        </div>
    </div>

    <!-- Justifiées -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-green-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                <i class="bi bi-check-circle-fill text-green-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-gray-500 text-sm uppercase">Justifiées</p>
                <h3 class="text-3xl font-bold">18</h3>
            </div>
        </div>
    </div>

    <!-- Non justifiées -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-purple-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="bi bi-exclamation-circle-fill text-purple-600 text-2xl"></i>
            </div>

            <div>
                <p class="text-gray-500 text-sm uppercase">Non justifiées</p>
                <h3 class="text-3xl font-bold">10</h3>
            </div>
        </div>
    </div>

</div>
<div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold flex items-center gap-3">
            <i class="bi bi-person-x-fill text-red-600"></i>
            Gestion des absences
        </h2>

        <button class="bg-green-800 hover:bg-green-700 text-white px-5 py-3 rounded-xl">
            Déclarer une absence
        </button>
    </div>

    <!-- Recherche -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">

        <input
            type="text"
            placeholder="Rechercher un étudiant..."
            class="flex-1 border rounded-xl px-4 py-3">

        <select class="border rounded-xl px-4 py-3">
            <option>Tous les départements</option>
            <option>Developpement Web</option>
            <option>Bureautique</option>
            <option>Marketing Digital</option>
        </select>
        <select class="border rounded-xl px-4 py-3">
            <option>Toutes les cohortes</option>
            <option>Cohorte 1</option>
            <option>Cohorte 2</option>
            <option>Cohorte 3</option>
            <option>Cohorte 4</option>
        </select>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-green-800 text-white">
                <tr>
                    <th class="text-left p-4">Étudiant</th>
                    <th class="text-left p-4">Département</th>
                    <th class="text-left p-4">Cohorte</th>
                    <th class="text-left p-4">Date</th>
                    <th class="text-left p-4">Motif</th>
                    <th class="text-left p-4">Statut</th>
                    <th class="text-center p-4">Actions</th>
                </tr>
            </thead>

            <tbody>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/50?img=12"
                                 class="w-10 h-10 rounded-full">
                            <span>Fatou Sow</span>
                        </div>
                    </td>

                    <td class="p-4">Developpement Web</td>
                    <td class="p-4">Cohorte 1</td>
                    <td class="p-4">15/06/2026</td>

                    <td class="p-4">Maladie</td>

                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Justifiée
                        </span>
                    </td>

                    <td class="text-center p-4">
                        <button class="text-blue-600">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/50?img=25"
                                 class="w-10 h-10 rounded-full">
                            <span>Moussa Ba</span>
                        </div>
                    </td>

                    <td class="p-4">Marketing Digital</td>
                    <td class="p-4">Cohorte 1</td>


                    <td class="p-4">14/06/2026</td>

                    <td class="p-4">Aucun justificatif</td>

                    <td class="p-4">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                            Non justifiée
                        </span>
                    </td>

                    <td class="text-center p-4">
                        <button class="text-blue-600">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>
</main>
</body>
</html>