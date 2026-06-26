<?php

require_once '../config/database.php';
require_once '../app/Controllers/UserController.php';

require_once __DIR__ . '/../app/routes/web.php';

// Controller
$userController = new UserController($pdo);

// récupérer les users
$users = $userController->index();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PointagePro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">

<h1 class="text-2xl font-bold p-6">Dashboard PointagePro</h1>

<div class="p-6 grid md:grid-cols-2 xl:grid-cols-3 gap-6">

<?php foreach ($users as $user): ?>

<div class="bg-white p-5 rounded-2xl shadow">

    <h3 class="font-bold">
        <?= $user['firstname'] . ' ' . $user['lastname'] ?>
    </h3>

    <p><?= $user['email'] ?></p>

    <p class="text-sm text-gray-500">
        <?= $user['role'] ?>
    </p>

</div>

<?php endforeach; ?>

</div>

</body>
</html>