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

        <div class="grid md:grid-cols-4 gap-6 mb-6">

    <!-- Total congés -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-blue-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="bi bi-airplane-fill text-blue-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm uppercase">Total Congés</p>
                <h3 class="text-3xl font-bold">12</h3>
            </div>
        </div>
    </div>

    <!-- Approuvés -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-green-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                <i class="bi bi-check-circle-fill text-green-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm uppercase">Approuvés</p>
                <h3 class="text-3xl font-bold">8</h3>
            </div>
        </div>
    </div>

    <!-- En attente -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-orange-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="bi bi-hourglass-split text-orange-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm uppercase">En attente</p>
                <h3 class="text-3xl font-bold">3</h3>
            </div>
        </div>
    </div>

    <!-- Refusés -->
    <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-red-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="bi bi-x-circle-fill text-red-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm uppercase">Refusés</p>
                <h3 class="text-3xl font-bold">1</h3>
            </div>
        </div>
    </div>

</div>
<div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold flex items-center gap-3">
            <i class="bi bi-person-x-fill text-red-600"></i>
            Mes Absences
        </h2>

        <button onclick="document.getElementById('modalConge').classList.remove('hidden')"
           class="bg-green-800 hover:bg-green-700 text-white px-5 py-3 rounded-xl">
           <i class="bi bi-plus-circle"></i> Déclarer une absence
        </button>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-green-800 text-white">
                <tr>
                    <th class="text-left p-4">Justification</th>
                    <th class="text-left p-4">Date début</th>
                    <th class="text-left p-4">Date fin</th>
                    <th class="text-left p-4">Durée</th>
                    <th class="text-left p-4">Statut</th>
                </tr>
            </thead>

            <tbody>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">Congé maladie</td>
                    <td class="p-4">01/07/2026</td>
                    <td class="p-4">05/07/2026</td>
                    <td class="p-4">5 jours</td>
                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Approuvé
                        </span>
                    </td>
                </tr>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">Congé personnel</td>
                    <td class="p-4">15/07/2026</td>
                    <td class="p-4">17/07/2026</td>
                    <td class="p-4">3 jours</td>
                    <td class="p-4">
                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                            En attente
                        </span>
                    </td>
                </tr>

                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4">Congé exceptionnel</td>
                    <td class="p-4">20/06/2026</td>
                    <td class="p-4">22/06/2026</td>
                    <td class="p-4">3 jours</td>
                    <td class="p-4">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                            Refusé
                        </span>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>
    </main>

    <!-- Modal Demande Congé -->
<div id="modalConge"
     class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">

            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i class="bi bi-airplane-fill text-blue-600"></i>
                Demande un absence
            </h2>

            <button
                onclick="document.getElementById('modalConge').classList.add('hidden')"
                class="text-gray-500 hover:text-red-600 text-2xl">
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

        <!-- Formulaire -->
        <form action="" method="POST" enctype="multipart/form-data">

            <div class="grid md:grid-cols-2 gap-4">

                <!-- Type -->
                <div>
                    <label class="block mb-2 font-medium">
                        <i class="bi bi-tag-fill text-green-700"></i>
                        Type de congé
                    </label>

                    <select
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500">
                        <option>Choisir...</option>
                        <option>Congé maladie</option>
                        <option>Congé personnel</option>
                        <option>Congé exceptionnel</option>
                        <option>Congé maternité</option>
                    </select>
                </div>

                <!-- Durée -->
                <div>
                    <label class="block mb-2 font-medium">
                        <i class="bi bi-calendar-range-fill text-green-700"></i>
                        Nombre de jours
                    </label>

                    <input
                        type="number"
                        min="1"
                        class="w-full border rounded-xl px-4 py-3">
                </div>

            </div>
            <div class="grid md:grid-cols-2 gap-4">

                <!-- Date début -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">
                        <i class="bi bi-calendar-event-fill text-green-700"></i>
                        Date de début
                    </label>
    
                    <input
                        type="date"
                        class="w-full border rounded-xl px-4 py-3">
                </div>
    
                <!-- Date fin -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">
                        <i class="bi bi-calendar-check-fill text-green-700"></i>
                        Date de fin
                    </label>
    
                    <input
                        type="date"
                        class="w-full border rounded-xl px-4 py-3">
                </div>
            </div>
            <!-- Motif -->
            <div class="mt-4">
                <label class="block mb-2 font-medium">
                    <i class="bi bi-chat-left-text-fill text-green-700"></i>
                    Motif
                </label>

                <textarea
                    rows="4"
                    class="w-full border rounded-xl px-4 py-3"
                    placeholder="Expliquez la raison de votre demande..."></textarea>
            </div>

            <!-- Justificatif
            <div class="mt-4">
                <label class="block mb-2 font-medium">
                    <i class="bi bi-file-earmark-arrow-up-fill text-green-700"></i>
                    Justificatif (optionnel)
                </label>

                <input
                    type="file"
                    class="w-full border rounded-xl px-4 py-3">
            </div> -->

            <!-- Boutons -->
            <div class="flex justify-end gap-3 mt-6">

                <button
                    type="button"
                    onclick="document.getElementById('modalConge').classList.add('hidden')"
                    class="px-5 py-3 border rounded-xl">
                    Annuler
                </button>

                <button
                    type="submit"
                    class="bg-green-800 hover:bg-green-700 text-white px-5 py-3 rounded-xl">
                    Envoyer la demande
                </button>

            </div>

        </form>

    </div>

</div>
</body>
</html>