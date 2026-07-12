<?php
// Use pagination values provided by the controller when available
$limit = $limit ?? 10;
$currentPage = $currentPage ?? (isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1);
$total = $total ?? (isset($absences) ? count($absences) : 0);
$totalPages = ($limit > 0) ? (int)ceil($total / $limit) : 1;
$pagedAbsences = $absences ?? [];
?>

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
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 ml-0 lg:ml-64 p-4 sm:p-6 lg:p-8">
        <div class="bg-gradient-to-r from-[#1E4F86] to-[#8B5E3C] rounded-3xl p-8 shadow-xl text-white mb-8">


<h1 class="text-4xl font-bold flex items-center gap-3">

<i class="bi bi-person-x-fill text-5xl"></i>

Mes absences

</h1>


<p class="mt-3 text-blue-100">

Consultez et gérez les demandes d'absence des étudiants en temps réel.

</p>


</div>

<?php if (!empty($_SESSION['absence_success'])): ?>
    <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
        <?= htmlspecialchars($_SESSION['absence_success']) ?>
    </div>
    <?php unset($_SESSION['absence_success']); ?>
<?php endif; ?>

        <div class="grid md:grid-cols-4 gap-6 mb-6">

    <!-- Total congés -->
    <div class="bg-white rounded-3xl shadow-xl p-6 ring-1 ring-slate-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="bi bi-airplane-fill text-blue-900 text-2xl"></i>
        </div>
        <div>
            <p class="text-slate-500 text-sm uppercase tracking-wider font-semibold">Total Congés</p>
            <h3 class="text-3xl font-extrabold text-slate-900"><?php echo $totalAbsences ?></h3>
        </div>
    </div>

    <!-- Approuvés -->
    <div class="bg-white rounded-3xl shadow-xl p-6 ring-1 ring-slate-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
            <i class="bi bi-check-circle-fill text-green-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-slate-500 text-sm uppercase tracking-wider font-semibold">Approuvés</p>
            <h3 class="text-3xl font-extrabold text-slate-900"><?php echo $totalApprovedAbsences ?></h3>
        </div>
    </div>

    <!-- En attente -->
    <div class="bg-white rounded-3xl shadow-xl p-6 ring-1 ring-slate-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-amber-50 flex items-center justify-center">
            <i class="bi bi-hourglass-split text-amber-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-slate-500 text-sm uppercase tracking-wider font-semibold">En attente</p>
            <h3 class="text-3xl font-extrabold text-slate-900"><?php echo $totalPendingAbsences ?></h3>
        </div>
    </div>

    <!-- Refusés -->
    <div class="bg-white rounded-3xl shadow-xl p-6 ring-1 ring-slate-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center">
            <i class="bi bi-x-circle-fill text-red-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-slate-500 text-sm uppercase tracking-wider font-semibold">Refusés</p>
            <h3 class="text-3xl font-extrabold text-slate-900"><?php echo $totalRefusedAbsences ?></h3>
        </div>
    </div>

</div>
<div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                <i class="bi bi-person-x-fill text-red-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Mes Absences</h2>
                <p class="text-slate-500 text-sm">Suivez vos demandes et leur statut</p>
            </div>
        </div>

          <button onclick="document.getElementById('modalConge').classList.remove('hidden')"
              class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-3 rounded-2xl shadow-md flex items-center gap-2">
           <i class="bi bi-plus-circle"></i> Déclarer une absence
        </button>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gradient-to-r from-blue-900 to-amber-700 text-white">
                <tr>
                    <th class="text-left p-4">Justification</th>
                    <th class="text-left p-4">Date début</th>
                    <th class="text-left p-4">Date fin</th>
                    <th class="text-left p-4">Durée</th>
                    <th class="text-left p-4">Statut</th>
                </tr>
            </thead>

            <tbody>
              <?php foreach ($pagedAbsences as $absence): ?>
                <tr class="border-t hover:bg-slate-50">
                    <td class="p-4"><?= htmlspecialchars($absence["type"]) ?></td>
                    <td class="p-4"><?= htmlspecialchars($absence["start_date"]) ?></td>
                    <td class="p-4"><?= htmlspecialchars($absence["end_date"]) ?></td>
                    <td class="p-4"><?= htmlspecialchars($absence["duration"]) ?></td>
                    <td class="p-4">

<?php if ($absence['status'] == 'approuve'): ?>

    <span class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
        <i class="bi bi-check-circle-fill"></i>
        Approuvé
    </span>

<?php elseif ($absence['status'] == 'en_attente'): ?>

    <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
        <i class="bi bi-hourglass-split"></i>
        En attente
    </span>

<?php elseif ($absence['status'] == 'refuse'): ?>

    <span class="inline-flex items-center gap-2 bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
        <i class="bi bi-x-circle-fill"></i>
        Refusé
    </span>

<?php endif; ?>

</td>
                </tr>

               <?php endforeach; ?>

            </tbody>

        </table>
        

    </div>
    <!-- pagination -->
<?php if ($totalPages > 1): ?>

<div class="flex justify-center items-center gap-3 mt-8">

    <!-- Précédent -->
    <a href="index.php?page=absence&p=<?= max(1, $currentPage - 1) ?>"
       class="<?= ($currentPage == 1) ? 'pointer-events-none opacity-50' : '' ?> inline-flex items-center gap-2 px-4 py-2 border rounded-2xl bg-white hover:bg-slate-50 transition">

        <i class="bi bi-chevron-left"></i>
        Précédent
    </a>

    <!-- Numéros -->
    <?php for($i = 1; $i <= $totalPages; $i++): ?>

          <a href="index.php?page=absence&p=<?= $i ?>"
              class="w-10 h-10 flex items-center justify-center border rounded-2xl <?= ($currentPage == $i) ? 'bg-blue-900 text-white' : 'bg-white hover:bg-slate-50' ?> transition">

            <?= $i ?>

        </a>

    <?php endfor; ?>

    <!-- Suivant -->
    <a href="index.php?page=absence&p=<?= min($totalPages, $currentPage + 1) ?>"
       class="<?= ($currentPage == $totalPages) ? 'pointer-events-none opacity-50' : '' ?> inline-flex items-center gap-2 px-4 py-2 border rounded-2xl bg-white hover:bg-slate-50 transition">

        Suivant
        <i class="bi bi-chevron-right"></i>

    </a>

</div>

<?php endif; ?>
<!-- fin pagination -->
</div>
    </main>
    

    <!-- Modal Demande Congé -->
    <div id="modalConge"
         class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-6 ring-1 ring-slate-200">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">

                <h2 class="text-2xl font-bold flex items-center gap-2">
                    <i class="bi bi-airplane-fill text-blue-900"></i>
                    Demande d'absence
                </h2>

                <button
                    onclick="document.getElementById('modalConge').classList.add('hidden')"
                    class="text-slate-500 hover:text-slate-900 text-2xl">
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

            <!-- Formulaire -->
            <form action="/COUR-TELLY-TECH/pointagepro/public/index.php?page=absence" method="POST" enctype="multipart/form-data">

            <div class="grid md:grid-cols-2 gap-4">

                <!-- Type -->
                <div>
                    <label class="block mb-2 font-medium">
                        <i class="bi bi-tag-fill text-blue-900"></i>
                        Type de congé
                    </label>

                    <select
                        class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-700" name="type">
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
                        <i class="bi bi-calendar-range-fill text-blue-900"></i>
                        Nombre de jours
                    </label>

                    <input
                        type="number"
                        min="1"
                        class="w-full border rounded-xl px-4 py-3" name="duration">
                </div>

            </div>
            <div class="grid md:grid-cols-2 gap-4">

                <!-- Date début -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">
                        <i class="bi bi-calendar-event-fill text-blue-900"></i>
                        Date de début
                    </label>
    
                    <input
                        type="date"
                        class="w-full border rounded-xl px-4 py-3" name="date_debut">
                </div>
    
                <!-- Date fin -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">
                        <i class="bi bi-calendar-check-fill text-blue-900"></i>
                        Date de fin
                    </label>
    
                    <input
                        type="date"
                        class="w-full border rounded-xl px-4 py-3" name="date_fin">
                </div>
            </div>
            <!-- Motif -->
            <div class="mt-4">
                <label class="block mb-2 font-medium">
                    <i class="bi bi-chat-left-text-fill text-blue-900"></i>
                    Motif
                </label>

                <textarea
                    rows="4"
                    class="w-full border rounded-xl px-4 py-3" name="motif"
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
                    class="px-5 py-3 border border-slate-200 rounded-2xl hover:bg-slate-50 transition">
                    Annuler
                </button>

                <button
                    type="submit"
                    class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-3 rounded-2xl shadow">
                    Envoyer la demande
                </button>

            </div>

        </form>

    </div>

</div>
</body>
</html>
