<?php
require_once __DIR__ . '/../../../config/database.php';

$limit = 6;
$department_id = $_GET['department_id'] ?? '';
$cohort_id = $_GET['cohort_id'] ?? '';
$search = $_GET['search'] ?? '';

$departments = $pdo->query("SELECT * FROM departments ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$cohorts = $pdo->query("SELECT * FROM cohorts ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// numéro de la page de pagination
$currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;

if ($currentPage < 1) {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $limit;

// total utilisateurs
$totalStmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $totalStmt->fetchColumn();

$totalPages = ceil($totalUsers / $limit);
$sql = "
SELECT
    users.*,
    departments.name AS department,
    cohorts.name AS cohort
FROM users
LEFT JOIN departments
    ON users.department_id = departments.id
LEFT JOIN cohorts
    ON users.cohort_id = cohorts.id
WHERE 1=1
";
$params = [];

if (!empty($department_id)) {
    $sql .= " AND users.department_id = :department_id";
    $params[':department_id'] = $department_id;
}

if (!empty($cohort_id)) {
    $sql .= " AND users.cohort_id = :cohort_id";
    $params[':cohort_id'] = $cohort_id;
}

if (!empty($search)) {
    $sql .= " AND (
        users.firstname LIKE :search OR
        users.lastname LIKE :search OR
        users.email LIKE :search OR
        departments.name LIKE :search OR
        cohorts.name LIKE :search
    )";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY users.created_at DESC
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
     <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>


    
    <!-- Contenu -->
    <main class="flex-1 ml-64 p-4 sm:p-6 lg:p-8">

        <div class="bg-gradient-to-r from-blue-900 to-amber-700 rounded-3xl p-8 shadow-xl text-white mb-8">

            <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                <div>
                    <h2 class="text-3xl font-bold flex items-center gap-3">
                        <i class="fa-solid fa-user-group text-4xl"></i>
                        Gestion des utilisateurs
                    </h2>
                    <p class="mt-3 text-blue-100 max-w-2xl">Gérez les employés et administrateurs du système avec un tableau moderne et cohérent.</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-3xl p-5 min-w-[220px]">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-white/30 flex items-center justify-center">
                            <i class="bi bi-people-fill text-3xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-white/80">Administration</p>
                            <h3 class="text-2xl font-bold">Utilisateurs</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-wrap gap-3">
                <button
                    onclick="document.getElementById('importModal').classList.remove('hidden')"
                    class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-3 rounded-2xl flex items-center gap-2 shadow-lg transition">
                    <i class="bi bi-upload"></i>
                    Importer des utilisateurs
                </button>
                <button
                    onclick="document.getElementById('modalUser').classList.remove('hidden')"
                    class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-3 rounded-2xl flex items-center gap-2 shadow-lg transition">
                    <i class="bi bi-plus-circle"></i>
                    Ajouter un utilisateur
                </button>
            </div>
            <div class="text-sm text-slate-500">Total utilisateurs : <span class="font-semibold"><?= $totalUsers ?></span></div>
        </div>

        <!-- Modal Ajouter Utilisateur -->
           
<div id="modalUser"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">

        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="bi bi-person-plus-fill text-green-700"></i>
                Ajouter un utilisateur
            </h2>

            <button
                onclick="document.getElementById('modalUser').classList.add('hidden')"
                class="text-gray-500 hover:text-red-600 text-2xl">
                &times;
            </button>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="index.php?page=store_user" enctype="multipart/form-data" onsubmit="const btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.classList.add('opacity-50', 'cursor-not-allowed'); btn.innerHTML='<i class=\'bi bi-arrow-repeat animate-spin mr-2\'></i> Enregistrement...';">

            <div class="grid md:grid-cols-2 gap-4">

                <!-- Nom -->
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-person"></i> Prénom 
                    </label>
                    <input type="text" name="firstname"
                           class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                           placeholder="Fatou Sow">
                </div>
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-person"></i> Nom 
                    </label>
                    <input type="text" name="lastname"
                           class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                           placeholder="Sow">
                </div>

                <!-- Email -->
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-envelope"></i> Email
                    </label>
                    <input type="email"  name="email"
                           class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                           placeholder="fatou@gmail.com">
                </div>

                <!-- Téléphone -->
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-telephone"></i> Téléphone
                    </label>
                    <input type="text" name="phone"
                           class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                           placeholder="+221 77 000 00 00">
                </div>

                <!-- Rôle -->
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-person-badge"></i> Rôle
                    </label>
                    <select class="w-full border rounded-xl px-4 py-3" name="role" id="create_role" onchange="toggleCohortDeptFields(this.value, 'create')">
                        <option value="etudiant">Étudiant</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

               <!-- Département -->
                <div id="create_dept_group">
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-building"></i> Département
                    </label>
                
                    <select name="department_id"
                            class="w-full border rounded-xl px-4 py-3">
                        <?php foreach ($departments as $dep): ?>
                            <option value="<?= $dep['id'] ?>"
                                <?= ($department_id == $dep['id']) ? 'selected' : '' ?> >
                                <?= htmlspecialchars($dep['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Cohorte -->
                <div id="create_cohort_group">
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-people-fill"></i> Cohorte
                    </label>
                
                    <select name="cohort_id"
                            class="w-full border rounded-xl px-4 py-3">
                        <?php foreach ($cohorts as $coh): ?>
                            <option value="<?= $coh['id'] ?>"
                                <?= ($cohort_id == $coh['id']) ? 'selected' : '' ?> >
                                <?= htmlspecialchars($coh['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                 <!-- Mot de passe -->
                 <div class="mt-4">
                     <label class="font-medium flex items-center gap-2 mb-2">
                         <i class="bi bi-lock"></i> Mot de passe
                     </label>
                     <input type="password" name="password"
                            class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                            placeholder="********">
                 </div>

                <!-- Photo -->
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-image"></i> Photo
                    </label>
                    <input type="file" name="photo" class="w-full border rounded-xl px-4 py-3">
                </div>

            </div>

           

            <!-- Boutons -->
            <div class="flex justify-end gap-3 mt-6">

                <button type="button"
                        onclick="document.getElementById('modalUser').classList.add('hidden')"
                        class="px-5 py-3 border rounded-xl hover:bg-gray-100">
                    Annuler
                </button>

                <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-xl flex items-center gap-2">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer
                </button>

            </div>

        </form>

    </div>
</div>
            <!-- Recherche -->
            <div>
                <form method="GET" action="index.php" class="flex flex-col lg:flex-row gap-4 mb-6 items-center">

    <input type="hidden" name="page" value="users">

    <div class="relative flex-1">
        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

        <input
            type="text"
            placeholder="Rechercher un utilisateur..."
            name="search"
            value="<?= htmlspecialchars($search ?? '') ?>"
            class="w-full border border-slate-200 rounded-2xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm">
    </div>


    <select name="department_id"
            class="border border-slate-200 rounded-2xl px-4 py-3 bg-white shadow-sm"
            onchange="this.form.submit()">

        <option value="">Tous les départements</option>

        <?php foreach ($departments as $dep): ?>
            <option value="<?= $dep['id'] ?>"
                <?= ($department_id == $dep['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($dep['name']) ?>
            </option>
        <?php endforeach; ?>

    </select>


    <select name="cohort_id"
            class="border border-slate-200 rounded-2xl px-4 py-3 bg-white shadow-sm"
            onchange="this.form.submit()">

        <option value="">Toutes les cohortes</option>

        <?php foreach ($cohorts as $coh): ?>
            <option value="<?= $coh['id'] ?>"
                <?= ($cohort_id == $coh['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($coh['name']) ?>
            </option>
        <?php endforeach; ?>

    </select>

</form>
            </div>

           <!-- Liste des utilisateurs en Cards -->
            <?php if (!empty($users)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($users as $user): ?>
                        <?php $isActive = in_array($user['is_active'] ?? '', [1, '1', true, 't', 'true', 'TRUE'], true); ?>
                        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-lg hover:-translate-y-1 hover:shadow-2xl transition">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-4">
                                    <?php
                                    $photoUrl = null;
                                    if (!empty($user['photo'])) {
                                        // La BDD stocke déjà "uploads/nom_fichier"
                                        $photoPath = $_SERVER['DOCUMENT_ROOT'] . "/" . $user['photo'];
                                        if (file_exists($photoPath)) {
                                            $photoUrl = "/" . $user['photo'];
                                        }
                                    }
                                    ?>
                                    <?php if ($photoUrl): ?>
                                        <img src="<?= $photoUrl ?>"
                                             class="w-16 h-16 rounded-full object-cover flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                            <?= strtoupper(substr($user['firstname'] ?? '', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h3 class="font-bold text-lg">
                                            <?= htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?>
                                        </h3>
                                        <p class="text-sm text-slate-500">ID: <?= htmlspecialchars($user['id'] ?? '') ?></p>
                                    </div>
                                </div>
                                <span class="<?= $isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> px-3 py-1 rounded-full text-sm">
                                    <?= $isActive ? 'Actif' : 'Inactif' ?>
                                </span>
                            </div>

                            <div class="mt-4 space-y-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-envelope text-blue-600"></i>
                                    <span><?= htmlspecialchars($user['email'] ?? '') ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-telephone text-green-600"></i>
                                    <span><?= htmlspecialchars($user['phone'] ?? '') ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-person-badge text-indigo-600"></i>
                                    <span><?= htmlspecialchars($user['role'] ?? '') ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-code-slash text-purple-600"></i>
                                    <span><?= htmlspecialchars($user['department'] ?? '') ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-people-fill text-orange-600"></i>
                                    <span><?= htmlspecialchars($user['cohort'] ?? '') ?></span>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-5">
                                <button
                                    onclick="
                                        document.getElementById('editModal').classList.remove('hidden');
                                        document.getElementById('edit_id').value='<?= $user['id'] ?? '' ?>';
                                        document.getElementById('edit_firstname').value='<?= htmlspecialchars($user['firstname'] ?? '') ?>';
                                        document.getElementById('edit_lastname').value='<?= htmlspecialchars($user['lastname'] ?? '') ?>';
                                        document.getElementById('edit_email').value='<?= htmlspecialchars($user['email'] ?? '') ?>';
                                        document.getElementById('edit_phone').value='<?= htmlspecialchars($user['phone'] ?? '') ?>';
                                        document.getElementById('edit_department').value = '<?= $user['department_id'] ?? '' ?>';
                                        document.getElementById('edit_cohort').value = '<?= $user['cohort_id'] ?? '' ?>';
                                        document.getElementById('edit_role').value='<?= htmlspecialchars($user['role'] ?? '') ?>';
                                        toggleCohortDeptFields('<?= htmlspecialchars($user['role'] ?? '') ?>', 'edit');
                                    "
                                    class="bg-blue-100 text-blue-600 px-3 py-2 rounded-2xl hover:bg-blue-200 transition">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <a href="index.php?page=toggle_user_status&id=<?= $user['id'] ?? '' ?>"
                                   onclick="return confirm('Voulez-vous vraiment <?= $isActive ? 'désactiver' : 'activer' ?> ce compte ?')"
                                   title="<?= $isActive ? 'Désactiver le compte' : 'Activer le compte' ?>"
                                   class="<?= $isActive ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' ?> px-3 py-2 rounded-2xl transition">
                                    <i class="bi <?= $isActive ? 'bi-person-x-fill' : 'bi-person-check-fill' ?>"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-slate-500">Aucun utilisateur trouvé.</p>
            <?php endif; ?>

            <div class="flex flex-wrap justify-center gap-2 mt-8">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="index.php?page=users&p=<?= $i ?>&department_id=<?= urlencode($department_id) ?>&cohort_id=<?= urlencode($cohort_id) ?>&search=<?= urlencode($search) ?>"
                       class="px-4 py-2 rounded-2xl border <?= ($i == $currentPage) ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' ?> transition">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </main>
    </div>

<div id="editModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h2 class="text-xl font-bold">
                Modifier l'utilisateur
            </h2>

            <button
                onclick="document.getElementById('editModal').classList.add('hidden')"
                class="text-2xl">
                &times;
            </button>
        </div>

      <form action="index.php?page=update_user" method="POST" onsubmit="const btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.classList.add('opacity-50', 'cursor-not-allowed'); btn.innerHTML='<i class=\'bi bi-arrow-repeat animate-spin mr-2\'></i> Enregistrement...';">

            <input type="hidden" name="id" id="edit_id">

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label>Prénom</label>
                    <input
                        type="text"
                        id="edit_firstname"
                        name="firstname"
                        class="w-full border rounded-xl p-3">
                </div>

                <div>
                    <label>Nom</label>
                    <input
                        type="text"
                        id="edit_lastname"
                        name="lastname"
                        class="w-full border rounded-xl p-3">
                </div>

                <div>
                    <label>Email</label>
                    <input
                        type="email"
                        id="edit_email"
                        name="email"
                        class="w-full border rounded-xl p-3">
                </div>

                <div>
                    <label>Téléphone</label>
                    <input
                        type="text"
                        id="edit_phone"
                        name="phone"
                        class="w-full border rounded-xl p-3">
                </div>

                <div id="edit_dept_group">
                    <label>Département</label>
                    <select id="edit_department" name="department_id" class="w-full border rounded-xl p-3">
                        <?php foreach ($departments as $dep): ?>
                            <option value="<?= $dep['id'] ?>">
                                <?= htmlspecialchars($dep['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="edit_cohort_group">
                    <label>Cohorte</label>
                    <select id="edit_cohort" name="cohort_id" class="w-full border rounded-xl p-3">
                        <?php foreach ($cohorts as $coh): ?>
                            <option value="<?= $coh['id'] ?>">
                                <?= htmlspecialchars($coh['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label>Rôle</label>

                    <select
                        id="edit_role"
                        name="role"
                        class="w-full border rounded-xl p-3"
                        onchange="toggleCohortDeptFields(this.value, 'edit')">

                        <option value="etudiant">Étudiant</option>
                        <option value="admin">Admin</option>

                    </select>
                </div>

            </div>

            <div class="flex justify-end mt-6 gap-3">

                <button
                    type="button"
                    onclick="document.getElementById('editModal').classList.add('hidden')"
                    class="px-5 py-3 border rounded-xl">
                    Annuler
                </button>

                <button
                    type="submit"
                    class="bg-blue-700 text-white px-5 py-3 rounded-xl">
                    Enregistrer
                </button>

            </div>

        </form>

    </div>

</div>

       <!--importe utilisateur-->
       <div id="importModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">

        <div class="flex justify-between items-center border-b pb-4">
            <h2 class="text-xl font-bold">
                Importer des utilisateurs
            </h2>

            <button
                onclick="document.getElementById('importModal').classList.add('hidden')"
                class="text-2xl">
                &times;
            </button>
        </div>

        <form action="index.php?page=import_users"
              method="POST"
              enctype="multipart/form-data"
              class="mt-6"
              onsubmit="const btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.classList.add('opacity-50', 'cursor-not-allowed'); btn.innerHTML='<i class=\'bi bi-arrow-repeat animate-spin mr-2\'></i> Importation...';">

            <label class="block mb-2 font-medium">
                Fichier CSV
            </label>

            <input
                type="file"
                name="csv_file"
                accept=".csv"
                required
                class="w-full border rounded-xl p-3">

            <div class="flex justify-end mt-6 gap-3">

                <button
                    type="button"
                    onclick="document.getElementById('importModal').classList.add('hidden')"
                    class="border px-5 py-3 rounded-xl">
                    Annuler
                </button>

                <button
                    type="submit"
                    class="bg-green-700 text-white px-5 py-3 rounded-xl">
                    Importer
                </button>

            </div>

        </form>

    </div>

</div>

<script>
function toggleCohortDeptFields(role, prefix) {
    const deptGroup = document.getElementById(prefix + '_dept_group');
    const cohortGroup = document.getElementById(prefix + '_cohort_group');
    if (deptGroup && cohortGroup) {
        if (role === 'admin') {
            deptGroup.classList.add('hidden');
            cohortGroup.classList.add('hidden');
        } else {
            deptGroup.classList.remove('hidden');
            cohortGroup.classList.remove('hidden');
        }
    }
}

// Initialiser à l'ouverture de la page pour le formulaire d'ajout
document.addEventListener('DOMContentLoaded', function() {
    const createRoleSelect = document.getElementById('create_role');
    if (createRoleSelect) {
        toggleCohortDeptFields(createRoleSelect.value, 'create');
    }
});
</script>
</body>
</html>

