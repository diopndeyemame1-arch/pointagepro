<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-100">
     <?php require_once '../layouts/sidebar.php'; ?>

    <main class="flex-1 ml-64 p-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">

        <div>
            <h2 class="text-3xl font-bold">
                Présences / Attendance
            </h2>

            <p class="text-gray-500">
                Consultez et suivez les présences des etudiants en temps réel.
            </p>
        </div>

        <div class="flex gap-3">

            <button class="bg-white border px-4 py-3 rounded-xl">
                <i class="bi bi-calendar3"></i>
                27 Avril 2026
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

    <!-- Cartes KPI -->
    <div class="grid lg:grid-cols-4 gap-5 mb-6">

        <!-- Présents -->
        <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-green-500">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="bi bi-people-fill text-green-600 text-2xl"></i>
                </div>

                <div>
                    <p class="text-gray-500 text-sm uppercase">
                        Présents
                    </p>

                    <h3 class="text-3xl font-bold">
                        123
                    </h3>
                </div>

            </div>

        </div>

        <!-- Retards -->
        <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-orange-500">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="bi bi-clock-history text-orange-600 text-2xl"></i>
                </div>

                <div>
                    <p class="text-gray-500 text-sm uppercase">
                        En retard
                    </p>

                    <h3 class="text-3xl font-bold">
                        15
                    </h3>
                </div>

            </div>

        </div>

        <!-- Absents -->
        <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-red-500">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="bi bi-person-x-fill text-red-600 text-2xl"></i>
                </div>

                <div>
                    <p class="text-gray-500 text-sm uppercase">
                        Absents
                    </p>

                    <h3 class="text-3xl font-bold">
                        8
                    </h3>
                </div>

            </div>

        </div>

        <!-- Total -->
        <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-purple-500">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="bi bi-person-badge-fill text-purple-600 text-2xl"></i>
                </div>

                <div>
                    <p class="text-gray-500 text-sm uppercase">
                        Total etudiants
                    </p>

                    <h3 class="text-3xl font-bold">
                        160
                    </h3>
                </div>

            </div>

        </div>

    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-2xl shadow p-6">

        <h3 class="text-xl font-bold mb-5"><i class="bi bi-calendar-check"></i>
            Liste des présences
        </h3>

        <!-- Recherche -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">

            <div class="relative flex-1">

                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <input
                    type="text"
                    placeholder="Rechercher un employé..."
                    class="w-full border rounded-xl pl-10 pr-4 py-3">

            </div>

            <select class="border rounded-xl px-4 py-3">
                <option>Tous les départements</option>
                <option value="">Developpement Web</option>
                <option value="">Marketing Digital</option>
                <option value="">Bureautique</option>
            </select>

            <select class="border rounded-xl px-4 py-3">
                <option>Tous les statuts</option>
                <option value="present">Presences</option>
                <option value="absent">Absences</option>
                <option value="retard">En retard</option>
            </select>
            <select class="border rounded-xl px-4 py-3">
                <option>Toutes les cohortes</option>
                <option>Cohorte 1</option>
                <option>Cohorte 2</option>
                <option>Cohorte 3</option>
                <option>Cohorte 4</option>
            </select>

        </div>

       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Card Présence -->
    <div class="bg-white border rounded-2xl p-5 shadow hover:shadow-lg transition">

        <div class="flex justify-between items-center mb-4">

            <div class="flex items-center gap-3">

                <img src="https://i.pravatar.cc/100?img=1"
                     class="w-14 h-14 rounded-full object-cover">

                <div>
                    <h3 class="font-bold text-lg">Fatou Sow</h3>
                    <p class="text-sm text-gray-500">
                        Développement Web
                    </p>
                </div>

            </div>

            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                Présent
            </span>

        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">
                    <i class="bi bi-people-fill"></i> Cohorte
                </span>
                <span class="font-semibold">Cohorte 1</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">
                    <i class="bi bi-clock"></i> Arrivée
                </span>
                <span class="font-semibold">08:05</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">
                    <i class="bi bi-box-arrow-right"></i> Départ
                </span>
                <span class="font-semibold">17:00</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">
                    <i class="bi bi-hourglass"></i> Durée
                </span>
                <span class="font-semibold">08h 55m</span>
            </div>

        </div>

        <div class="mt-5 flex justify-end gap-2">

            <button class="bg-blue-100 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-200">
                <i class="bi bi-eye"></i>
            </button>

            <button class="bg-green-100 text-green-600 px-3 py-2 rounded-lg hover:bg-green-200">
                <i class="bi bi-pencil-square"></i>
            </button>

        </div>

    </div>

</div>

    </div>

</main>
</body>
</html>
