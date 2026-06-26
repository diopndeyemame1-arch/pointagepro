<?php
require_once __DIR__ . '/../../../config/database.php';

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
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
     <?php require_once '../layouts/sidebar.php'; ?>

    
    <!-- Contenu -->
    <main class="flex-1 ml-64 p-8">

        <div class="bg-white rounded-2xl shadow-lg p-6">

            <!-- En-tête -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fa-solid fa-user-group text-green-700 text-3xl"></i>
                    Gestion des utilisateurs
                    </h2>
                    <p class="text-gray-500">Gérez les employés et administrateurs du système.</p>
                </div>
                <div class="flex gap-3">
                    <!-- Bouton Importer utilisateurs -->
                    <button
                        onclick="document.getElementById('importModal').classList.remove('hidden')"
                        class="bg-green-800 hover:bg-green-700 text-white px-5 py-3 rounded-xl flex items-center gap-2">
                        <i class="bi bi-upload"></i>
                        Importer des utilisateurs
                    </button>
                    <!-- Bouton Ajouter utilisateur -->
                    <button
                        onclick="document.getElementById('modalUser').classList.remove('hidden')"
                        class="bg-green-800 hover:bg-green-700 text-white px-5 py-3 rounded-xl flex items-center gap-2">
                        <i class="bi bi-plus-circle"></i>
                        Ajouter un utilisateur
                    </button>
                </div>
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
        <form class="mt-6" method="POST" action="store_user.php" enctype="multipart/form-data">

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
                           placeholder="Fatou Sow">
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
                    <select class="w-full border rounded-xl px-4 py-3"  name="role">
                        <option value="etudiant">Étudiant</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Département -->
                <div>
                    <label class="font-medium flex items-center gap-2 mb-2">
                        <i class="bi bi-building"></i> Département
                    </label>
                    <input type="text" name="department"
                           class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none"
                           placeholder="Développement Web / Référent Digital">
                </div>

                <!-- Cohorte -->
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
                        class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl flex items-center gap-2">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer
                </button>

            </div>

        </form>

    </div>
</div>
            <!-- Recherche -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="relative flex-1">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="text" placeholder="Rechercher un utilisateur..."
                        class="w-full border rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <select class="border rounded-xl px-4 py-3">
                    <option>Toutes les formations</option>
                    <option>Développement Web</option>
                    <option>Référent Digital</option>
                    <option>Bureautique</option>

                </select>
                
                <select class="border rounded-xl px-4 py-3">
                    <option>Toutes les cohortes</option>
                    <option>Cohorte 1</option>
                    <option>Cohorte 2</option>
                    <option>Cohorte 3</option>
                    <option>Cohorte 4</option>
                </select>
            </div>

           <!-- Liste des utilisateurs en Cards -->
            <?php if (!empty($users)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($users as $user): ?>

<div class="bg-white border rounded-2xl p-5 shadow hover:shadow-lg transition">

    <!-- Header -->
    <div class="flex justify-between items-start">

        <div class="flex items-center gap-4 ">
           <img src="../../../public/<?= $user['photo'] ?>"
     class="w-16 h-16 rounded-full object-cover flex-shrink-0"
     alt="Photo utilisateur">

            <div>
                <h3 class="font-bold text-lg">
                    <?= $user['firstname'] . ' ' . $user['lastname'] ?>
                </h3>
                <p class="text-sm text-gray-500">ID: <?= $user['id'] ?></p>
            </div>
        </div>

        <!-- ACTIF / INACTIF -->
        <span class="<?= $user['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> px-3 py-1 rounded-full text-sm">
            <?= $user['is_active'] ? 'Actif' : 'Inactif' ?>
        </span>

    </div>

    <!-- INFOS -->
    <div class="mt-4 space-y-3 text-sm">

        <div class="flex items-center gap-2">
            <i class="bi bi-envelope text-blue-600"></i>
            <span><?= $user['email'] ?></span>
        </div>

        <div class="flex items-center gap-2">
            <i class="bi bi-telephone text-green-600"></i>
            <span><?= $user['phone'] ?></span>
        </div>
        <div class="flex items-center gap-2">
            <i class="bi bi-person-badge text-indigo-600"></i>
            <span><?= $user['role'] ?></span>
        </div>

        <div class="flex items-center gap-2">
            <i class="bi bi-code-slash text-purple-600"></i>
            <span><?= $user['department'] ?></span>
        </div>

        <div class="flex items-center gap-2">
            <i class="bi bi-people-fill text-orange-600"></i>
            <span><?= $user['cohort'] ?></span>
        </div>

    </div>

    <!-- ACTIONS -->
    <div class="flex justify-end gap-3 mt-5">

        <!-- Modifier -->
        <button
    onclick="
        document.getElementById('editModal').classList.remove('hidden');

        document.getElementById('edit_id').value='<?= $user['id'] ?>';
        document.getElementById('edit_firstname').value='<?= $user['firstname'] ?>';
        document.getElementById('edit_lastname').value='<?= $user['lastname'] ?>';
        document.getElementById('edit_email').value='<?= $user['email'] ?>';
        document.getElementById('edit_phone').value='<?= $user['phone'] ?>';
        document.getElementById('edit_department').value='<?= $user['department'] ?>';
        document.getElementById('edit_cohort').value='<?= $user['cohort'] ?>';
        document.getElementById('edit_role').value='<?= $user['role'] ?>';
    "
    class="bg-blue-100 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-200">
    <i class="bi bi-pencil-square"></i>
</button>

        <!-- Supprimer -->
        <a href="delete_user.php?id=<?= $user['id'] ?>"
   onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')"
   class="bg-red-100 text-red-600 px-3 py-2 rounded-lg hover:bg-red-200">
    <i class="bi bi-trash-fill"></i>
</a>

    </div>

</div>

<?php endforeach; ?>
</div>
       <?php else: ?>
    <p>Aucun utilisateur trouvé</p>
<?php endif; ?>



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

        <form action="update_user.php" method="POST" class="mt-6">

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

                <div>
                    <label>Département</label>
                    <input
                        type="text"
                        id="edit_department"
                        name="department"
                        class="w-full border rounded-xl p-3">
                </div>

                <div>
                    <label>Cohorte</label>
                    <input
                        type="text"
                        id="edit_cohort"
                        name="cohort"
                        class="w-full border rounded-xl p-3">
                </div>

                <div>
                    <label>Rôle</label>

                    <select
                        id="edit_role"
                        name="role"
                        class="w-full border rounded-xl p-3">

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
                    class="bg-green-700 text-white px-5 py-3 rounded-xl">
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

        <form action="import_users.php"
              method="POST"
              enctype="multipart/form-data"
              class="mt-6">

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
</body>
</html>

