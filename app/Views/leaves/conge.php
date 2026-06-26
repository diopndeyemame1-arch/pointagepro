<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
   <link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
</head>

<body>

<div class="flex min-h-screen bg-slate-100">

  <!-- SIDEBAR -->
  <?php require_once '../layouts/sidebar.php'; ?>

  <!-- MAIN -->
      <main class="flex-1 ml-64 p-8 overflow-x-hidden ">


    <!-- HEADER (NON FIXE) -->
    <header class="bg-white border-b border-gray-100 px-6 py-4 p-6  items-center flex justify-between mb-8">

      <div>
        <h1 class="text-xl font-bold text-gray-900">Congés</h1>
        <p class="text-sm text-gray-400">Gestion des demandes de congés</p>
      </div>

      <div class="flex items-center gap-3">
        <button
onclick="openCalendar()"
class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">

<i class="bi bi-calendar3"></i>
Calendrier des congés

</button>

        <button class="border px-3 py-2 rounded-lg text-sm hover:bg-gray-50">
          🔽 Filtres
        </button>

        <button onclick="document.getElementById('modalConge').classList.remove('hidden')"
class="bg-green-800 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
    <i class="bi bi-plus-circle"></i>
    Nouvelle demande
</button>

<!-- Modal -->
<div id="modalConge"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl w-full max-w-2xl p-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h2 class="text-xl font-bold">
                <i class="bi bi-calendar-plus text-green-800"></i>
                Nouvelle demande de congé
            </h2>

            <button onclick="document.getElementById('modalConge').classList.add('hidden')">
                ✖
            </button>
        </div>

        <form class="mt-5 space-y-4">

            <div>
                <label class="font-medium">Etudiants</label>
                <select class="w-full border rounded-xl px-4 py-3">
                    <option>Fatou Sow</option>
                    <option>Moussa Ba</option>
                    <option>Awa Ndiaye</option>
                </select>
            </div>

            <div>
                <label class="font-medium">Type de congé</label>
                <select class="w-full border rounded-xl px-4 py-3">
                    <option>Congé annuel</option>
                    <option>Congé maladie</option>
                    <option>Congé exceptionnel</option>
                    <option>Congé sans solde</option>
                </select>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label>Date début</label>
                    <input type="date"
                    class="w-full border rounded-xl px-4 py-3">
                </div>

                <div>
                    <label>Date fin</label>
                    <input type="date"
                    class="w-full border rounded-xl px-4 py-3">
                </div>
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-people-fill"></i> Cohorte
                    </label>
                    <select class="w-full border rounded-xl px-4 py-3" name="cohort">
                        <option value="Cohorte 1">Cohorte 1</option>
                        <option value="Cohorte 2">Cohorte 2</option>
                        <option value="Cohorte 3">Cohorte 3</option>
                        <option value="Cohorte 4">Cohorte 4</option>
                    </select>
                </div>
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-building"></i> Département
                    </label>
                    <input type="text" name="department"
                           class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                           placeholder="Développement Web / Référent Digital">
                </div>
            </div>

            <div>
                <label>Motif</label>
                <textarea rows="4"
                class="w-full border rounded-xl px-4 py-3"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                onclick="document.getElementById('modalConge').classList.add('hidden')"
                class="border px-5 py-3 rounded-xl">
                    Annuler
                </button>

                <button type="submit"
                class="bg-green-800 text-white px-5 py-3 rounded-xl">
                    Envoyer
                </button>
            </div>

        </form>

    </div>

</div>
      </div>

    </header>

    <!-- CONTENT -->
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

      <!-- SECTION PRINCIPALE -->
      <div class="flex flex-col lg:flex-row gap-6">

        <!-- TABLE -->
        <div class="flex-1 min-w-0 bg-white rounded-xl border">

          <div class="p-4 border-b font-semibold text-2xl font-bold text-gray-800 flex items-center gap-3"><i class="bi bi-airplane text-green-700"></i>
            Liste des demandes de congés
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

    </div>

    

  </main>

</div>
<!-- MODAL CALENDRIER -->
<div id="modalCalendrier"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6 mx-4">

        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-4 mb-4">

            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i class="bi bi-calendar3 text-green-600"></i>
                Calendrier des congés
            </h2>

            <button
            onclick="document.getElementById('modalCalendrier').classList.add('hidden')"
            class="text-gray-500 hover:text-red-600 text-2xl">
                &times;
            </button>

        </div>

        <!-- Calendrier -->
        <div id="calendar"></div>

    </div>

</div>
<script>

document.addEventListener('DOMContentLoaded', function() {

    let calendarInitialized = false;

    window.openCalendar = function() {

        document
        .getElementById('modalCalendrier')
        .classList.remove('hidden');

        if (!calendarInitialized) {

            const calendarEl =
            document.getElementById('calendar');

            const calendar =
            new FullCalendar.Calendar(calendarEl, {

                initialView: 'dayGridMonth',

                locale: 'fr',

                height: 350,

                events: [

                    {
                        title: 'Fatou Sow',
                        start: '2026-05-05',
                        end: '2026-05-10',
                        color: '#22c55e'
                    },

                    {
                        title: 'Ibrahima Sarr',
                        start: '2026-05-15',
                        end: '2026-05-18',
                        color: '#f59e0b'
                    },

                    {
                        title: 'Cheikh Fall',
                        start: '2026-05-22',
                        end: '2026-05-24',
                        color: '#ef4444'
                    }

                ]

            });

            calendar.render();

            calendarInitialized = true;
        }

    }

});
</script>

</body>
</html>