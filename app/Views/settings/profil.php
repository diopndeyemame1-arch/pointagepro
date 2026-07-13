<?php

// session_start();

require_once __DIR__.'/../../../config/database.php';
require_once __DIR__.'/../../Models/ProfilModel.php';

$model = new ProfilModel($pdo);

$user = $model->getUserById($_SESSION['user_id']);

?>

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
         <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>


    <main class="flex-1 ml-0 lg:ml-64 p-4 sm:p-6 lg:p-8">

    <!-- Titre -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold">
            Mon Profil
        </h2>

        <p class="text-gray-500">
            Consultez et mettez à jour vos informations personnelles.
        </p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Carte profil -->
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex flex-col items-center">

<?php
$photoPath = "/uploads/" . ($user['photo'] ?? '');

if (empty($user['photo']) || !file_exists($_SERVER['DOCUMENT_ROOT'] . $photoPath)) {
    $photoPath = "/images/swe.jpeg";
}
?>
<img src="<?= $photoPath ?>"
     class="w-32 h-32 rounded-full border-4 border-green-500 object-cover">

                <h3 class="mt-4 text-2xl font-bold">
                <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></h3>

                <p class="text-gray-500"><?= ucfirst($_SESSION['role']) ?></p>

                <button class="mt-4 bg-green-800 text-white px-4 py-2 rounded-xl">
                    <i class="bi bi-camera"></i>
                    Changer la photo
                </button>

            </div>

        </div>

        <!-- Informations -->
         <form method="POST" action="../../Controllers/ProfilController.php">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">

            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="bi bi-person-circle text-green-700"></i>
                Informations personnelles
            </h3>


            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label class="font-medium">
                        <i class="bi bi-person"></i>
                        Prénom
                    </label>
                   <input
type="text"
name="firstname"
value="<?= htmlspecialchars($user['firstname']) ?>"
class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="font-medium">
                        <i class="bi bi-person"></i>
                        Nom
                    </label>
                    <input
type="text"
name="lastname"
value="<?= htmlspecialchars($user['lastname']) ?>"
class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="font-medium">
                        <i class="bi bi-envelope"></i>
                        Email
                    </label>
                    <input
type="email"
name="email"
value="<?= htmlspecialchars($user['email']) ?>"
class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="font-medium">
                        <i class="bi bi-telephone"></i>
                        Téléphone
                    </label>
                    <input
type="text"
name="phone"
value="<?= htmlspecialchars($user['phone']) ?>"
class="w-full border rounded-xl px-4 py-3 mt-2">
                           
                </div>

            </div>

        </div>

    </div>

    <!-- Informations académiques -->
    <div class="bg-white rounded-2xl shadow p-6 mt-6">

        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="bi bi-mortarboard-fill text-blue-600"></i>
            Informations académiques
        </h3>

        <div class="grid md:grid-cols-3 gap-4">

            <div>
                <label class="font-medium">
                    Departement
                </label>
                <input type="text"
                        value="<?= $user['department_id'] ?? '' ?>"
                       class="w-full border rounded-xl px-4 py-3 mt-2 " readonly>
            </div>

            <div>
                <label class="font-medium">
                    Cohorte
                </label>
                <input type="text"
                      value="<?= $user['cohort_id'] ?? '' ?>"
                       class="w-full border rounded-xl px-4 py-3 mt-2" readonly>
            </div>

            <div>
                <label class="font-medium">
                    Matricule
                </label>
                <input type="text"
                      value="<?= $user['id'] ?? '' ?>"
                       class="w-full border rounded-xl px-4 py-3 mt-2" readonly>
            </div>

        </div>

    </div>

    


        <button
type="submit"
class="mt-6 bg-green-800 hover:bg-green-700 text-white px-6 py-3 rounded-xl">

Enregistrer les modifications

</button>
        </form>

    </div>

</main>
<script>
function togglePassword(inputId, iconId) {

    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
    }
}
</script>
</body>
</html>
