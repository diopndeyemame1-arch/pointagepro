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
    <div class="p-6 space-y-6">



      <!-- KPI CARDS SIMPLIFIÉES -->

                  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">







            <!-- Total Étudiants -->



            <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-blue-500">



                <div class="flex items-center gap-4">



                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">



                        <i class="bi bi-calendar-check-fill text-blue-600 text-2xl"></i>



                    </div>



                    <div>



                        <p class="text-gray-500 text-sm font-medium">Solde Total</p>



                        <h3 class="text-3xl font-bold text-gray-800">250 </h3>



                    </div>



                </div>



            </div>







            <!-- Présences -->



            <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-green-500">



                <div class="flex items-center gap-4">



                    <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">



                        <i class="bi bi-check-circle-fill text-green-600 text-2xl"></i>



                    </div>



                    <div>



                        <p class="text-gray-500 text-sm font-medium">Approuvé</p>



                        <h3 class="text-3xl font-bold text-gray-800">220</h3>



                    </div>



                </div>



            </div>



        



            <!-- Retards -->



            <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-orange-500">



                <div class="flex items-center gap-4">



                    <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">



                        <i class="bi bi-hourglass-split text-orange-500 text-2xl"></i>



                    </div>



                    <div>



                        <p class="text-gray-500 text-sm font-medium">En attente</p>



                        <h3 class="text-3xl font-bold text-gray-800">18</h3>



                    </div>



                </div>     



            </div>



        



            <!-- Absences -->



            <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-red-500">



                <div class="flex items-center gap-4">



                    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">



                        <i class="bi bi-x-circle-fill text-red-600 text-2xl"></i>



                    </div>



                    <div>



                        <p class="text-gray-500 text-sm font-medium">Refusé</p>



                        <h3 class="text-3xl font-bold text-gray-800">12</h3>



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



            <table class="w-full ">



              <thead class="bg-green-800 text-white">

                <tr>

                  <th class="text-left p-3">Etudiants</th>

                  <th class="text-left p-3">departments</th>

                  <th class="text-left p-3">Cohortes</th>

                  <th class="text-left p-3">Type</th>

                  <th class="text-left p-3">Période</th>

                  <th class="text-left p-3">Durée</th>

                  <th class="text-left p-3">Statut</th>

                  <th class="text-left p-3">Actions</th>

                </tr>

              </thead>

              <tbody>

                <tr class="border-b hover:bg-gray-50">

                  <td class="p-3">Fatou Sow</td>

                  <td class="p-3">Bureautique</td>

                  <td class="p-3">Cohorte 3</td>

                  

                  <td class="p-3">Congé annuel</td>

                  <td class="p-3">05 - 09 Mai</td>

                  <td class="p-3">5 jours</td>

                  <td class="p-3 text-green-600">Approuvé</td>

                </tr>



                <tr class="border-b hover:bg-gray-50">

                  <td class="p-3">Ibrahima Sarr</td>

                  <td class="p-3">Marketing Digital</td>

                  <td class="p-3">Cohorte 2</td>

                  <td class="p-3">Maladie</td>

                  <td class="p-3">28 - 30 Avril</td>

                  <td class="p-3">3 jours</td>

                  <td class="p-3 text-orange-500">En attente</td>

                </tr>

                <tr class="border-b hover:bg-gray-50">

                  <td class="p-3">Awa Ndiaye</td>

                  <td class="p-3">Developpement Web</td>

                  <td class="p-3">Cohorte 1</td>

                  <td class="p-3">Fatigue</td>

                  <td class="p-3">19 - 30 Juin</td>

                  <td class="p-3">11 jours</td>

                  <td class="p-3 text-red-500">Refusé</td>

                </tr>



              </tbody>



            </table>



          </div>



        </div>



        




</div>
</main>
</body>
</html>