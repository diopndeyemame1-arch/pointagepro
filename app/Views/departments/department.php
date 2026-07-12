<?php
require_once __DIR__ . '/../../../config/database.php';

$sql = "
SELECT
    d.id AS department_id,
    d.name AS department_name,

    c.id AS cohort_id,
    c.name AS cohort_name,
    c.status

FROM departments d
LEFT JOIN cohorts c
ON d.id = c.department_id

ORDER BY d.name, c.name
";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$departments = [];

foreach ($rows as $row) {

    $id = $row['department_id'];

    if (!isset($departments[$id])) {
        $departments[$id] = [
    'id' => $row['department_id'], 
    'name' => $row['department_name'],
    'cohorts' => []
];
    }

    if ($row['cohort_id']) {
        $departments[$id]['cohorts'][] = $row;
    }
}
$deptCount = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();
$cohortCount = $pdo->query("SELECT COUNT(*) FROM cohorts")->fetchColumn();
$userCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role='etudiant'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-slate-100">
    <div class="flex min-h-screen">
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 ml-0 lg:ml-64 p-4 sm:p-6 lg:p-8">
<div class="bg-white/95 ring-1 ring-slate-200 rounded-2xl shadow-xl p-6">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold flex items-center gap-2">
            <i class="bi bi-diagram-3 text-indigo-700"></i>
            Departments & Cohortes
        </h2>
        <p class="text-gray-500">Gestion des formations et groupes</p>
    </div>

    <div class="flex gap-3">

        <button onclick="document.getElementById('deptModal').classList.remove('hidden')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-2xl flex items-center gap-2 shadow-md transition">
            <i class="bi bi-plus-circle"></i> Département
        </button>
        <button onclick="document.getElementById('cohortModal').classList.remove('hidden')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-2xl flex items-center gap-2 shadow-md transition">
            <i class="bi bi-plus-circle"></i> Cohorte
        </button>

    </div>
</div>

<!-- STATS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Départements -->
    <div class="group bg-white rounded-3xl p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-500 cursor-pointer">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-indigo-700 text-sm uppercase tracking-[0.2em] font-semibold">
                    Départements
                </p>
                <h3 class="text-4xl font-extrabold mt-2 text-slate-900">
                    <?= $deptCount ?>
                </h3>
            </div>
            <div class="bg-indigo-100 text-indigo-700 p-4 rounded-full group-hover:rotate-12 transition duration-500 shadow-inner">
                <i class="bi bi-building text-4xl"></i>
            </div>
        </div>
    </div>
    <!-- Cohortes -->
    <div class="group bg-white rounded-3xl p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-500 cursor-pointer">

        <div class="flex justify-between items-center">
            <div>
                <p class="text-emerald-700 text-sm uppercase tracking-[0.2em] font-semibold">
                    Cohortes
                </p>

                <h3 class="text-4xl font-extrabold mt-2 text-slate-900">
                    <?= $cohortCount ?>
                </h3>
            </div>

            <div class="bg-emerald-100 text-emerald-700 p-4 rounded-full group-hover:rotate-12 transition duration-500 shadow-inner">
                <i class="bi bi-people-fill text-4xl"></i>
            </div>
        </div>

    </div>

    <!-- Utilisateurs -->
    <div class="group bg-white rounded-3xl p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-500 cursor-pointer">

        <div class="flex justify-between items-center">
            <div>
                <p class="text-violet-700 text-sm uppercase tracking-[0.2em] font-semibold">
                    Utilisateurs
                </p>

                <h3 class="text-4xl font-extrabold mt-2 text-slate-900">
                    <?= $userCount ?>
                </h3>
            </div>

            <div class="bg-violet-100 text-violet-700 p-4 rounded-full group-hover:rotate-12 transition duration-500 shadow-inner">
                <i class="bi bi-person-fill text-4xl"></i>
            </div>
        </div>

    </div>

</div>

<!-- GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

<?php foreach ($departments as $dept): ?>

    <?php
        $colors = [
            "from-indigo-300 to-indigo-100",
            "from-emerald-300 to-emerald-100",
            "from-violet-300 to-violet-100"
        ];

        $color = $colors[array_rand($colors)];
    ?>

    <!-- CARD DEPARTMENT -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-lg transition">

        <!-- HEADER -->
        <div class="relative h-28 flex items-center justify-center bg-gradient-to-br <?= $color ?>">

            <div class="absolute inset-0 opacity-30 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>

            <div class="bg-white text-indigo-600 p-4 rounded-full w-16 h-16 flex items-center justify-center shadow-lg border-4 border-white">
                <i data-lucide="building" class="w-7 h-7"></i>
            </div>

        </div>

        <!-- BODY -->
        <div class="p-5 text-center flex flex-col flex-1">

            <h3 class="text-lg font-bold text-slate-900">
                <?= htmlspecialchars($dept['name']) ?>
            </h3>

            <p class="text-sm text-gray-500 mb-2">
                <?= count($dept['cohorts']) ?> cohorte(s)
            </p>

            <p class="text-xs text-gray-400 mb-4">
                Formation et gestion des étudiants
            </p>

            <!-- BUTTON -->
            <a href="index.php?page=department_cohorts&id=<?= $dept['id'] ?>"
               class="mt-auto border border-indigo-200 bg-indigo-50 text-indigo-700 py-2 rounded-2xl hover:bg-indigo-100 hover:border-indigo-300 transition text-center font-semibold">
                Voir les cohortes
            </a>

        </div>

    </div>

<?php endforeach; ?>


</div>
</div>
</main>
</div>
<!-- ================= MODAL DEPARTMENT ================= -->
<div id="deptModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">

<div class="bg-white p-6 rounded-3xl w-full max-w-md shadow-2xl ring-1 ring-slate-200">

<h2 class="text-2xl font-bold mb-4 text-slate-900">Créer un département</h2>

<form action="/COUR-TELLY-TECH/pointagepro/public/index.php?page=store_department" method="POST">

<input type="text" name="name" placeholder="Nom département"
class="w-full border border-slate-200 p-3 rounded-2xl mb-3 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300" required>

<textarea name="description" placeholder="Description"
class="w-full border border-slate-200 p-3 rounded-2xl mb-3 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300"></textarea>

<button name="create_department"
class="bg-indigo-600 hover:bg-indigo-700 text-white w-full py-3 rounded-2xl font-semibold transition">
    Créer
</button>

</form>

<button onclick="closeDeptModal()" class="mt-3 text-slate-600 hover:text-slate-900 transition">
Fermer
</button>

</div>
</div>

<!-- ================= MODAL COHORT ================= -->
<div id="cohortModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 pt-6 pb-4">
      <h2 class="text-xl font-bold text-gray-800">Nouvelle Cohorte</h2>

      <button onclick="document.getElementById('cohortModal').classList.add('hidden')"
              class="text-gray-400 hover:text-gray-600 text-2xl">
        &times;
      </button>
    </div>

    <div class="border-t mx-6"></div>

    <!-- FORM -->
   <form method="POST"
      action="/COUR-TELLY-TECH/pointagepro/public/index.php?page=store_cohort">


      <!-- Département -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Département <span class="text-red-500">*</span>
        </label>

        <select name="department_id"
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">

          <option value="">Sélectionner un département...</option>

          <?php
          $deps = $pdo->query("SELECT * FROM departments ORDER BY name");
          while ($d = $deps->fetch(PDO::FETCH_ASSOC)):
          ?>
            <option value="<?= $d['id'] ?>">
              <?= htmlspecialchars($d['name']) ?>
            </option>
          <?php endwhile; ?>

        </select>
      </div>

      <!-- Nom cohorte -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Nom cohorte <span class="text-red-500">*</span>
        </label>

        <input type="text" name="name"
          placeholder="Ex: Cohorte 2025A"
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
      </div>

      <!-- Jours de cours -->
      <?php
      $jours = [
          "Lundi",
          "Mardi",
          "Mercredi",
          "Jeudi",
          "Vendredi",
          "Samedi",
          "Dimanche"
      ];
      
      foreach($jours as $jour):
      ?>
      
      <div class="border rounded-xl p-4 mb-3">
      
          <label class="flex items-center gap-2 font-semibold">
      
              <input
                  type="checkbox"
                  onchange="toggleDay(this,'<?= $jour ?>')">
      
              <?= $jour ?>
      
          </label>
      
          <div
              id="<?= $jour ?>"
              class="hidden mt-3 grid grid-cols-2 gap-3">
      
              <div>
      
                  <label>Entrée</label>
      
                  <input
                      type="time"
                      name="schedule[<?= $jour ?>][start]"
                      class="w-full border rounded-lg p-2">
      
              </div>
      
              <div>
      
                  <label>Sortie</label>
      
                  <input
                      type="time"
                      name="schedule[<?= $jour ?>][end]"
                      class="w-full border rounded-lg p-2">
      
              </div>
      
          </div>
      
      </div>
      
      <?php endforeach; ?>


      <!-- Statut -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>

        <select name="status"
          class="w-full border rounded-lg px-3 py-2.5 text-sm">

          <option value="active">Actif</option>
          <option value="inactive">Inactif</option>
          <option value="archived">Archivé</option>

        </select>
      </div>

      <!-- FOOTER -->
      <div class="flex justify-end gap-3 pt-3 border-t">

        <button type="button"
          onclick="document.getElementById('cohortModal').classList.add('hidden')"
          class="px-5 py-2 border rounded-lg">
          Annuler
        </button>

        <button type="submit"
          class="bg-indigo-700 text-white px-5 py-2 rounded-lg">
          Créer
        </button>

      </div>

    </form>

  </div>

</div>

<script>
lucide.createIcons();
</script>
<script>
function closeDeptModal(){
    document.getElementById('deptModal').classList.add('hidden');
}

document.addEventListener("DOMContentLoaded", function () {
    lucide.createIcons();
});
</script>
<script>
function toggleDay(check, id){

    let bloc = document.getElementById(id);

    if(check.checked){
        bloc.classList.remove("hidden");
    }else{
        bloc.classList.add("hidden");
    }

}
</script>
</body>
</html>
