<?php

require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: utilisateur.php");
    exit;
}

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($token) || empty($password) || empty($confirm_password)) {
    die("Tous les champs sont obligatoires.");
}

if ($password !== $confirm_password) {
    die("Les mots de passe ne correspondent pas.");
}

if (strlen($password) < 8) {
    die("Le mot de passe doit contenir au moins 8 caractères.");
}


$stmt = $pdo->prepare("
    SELECT id
    FROM users
    WHERE activation_token = ?
    LIMIT 1
");

$stmt->execute([$token]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Lien d'activation invalide ou déjà utilisé.");
}


$password_hash = password_hash($password, PASSWORD_DEFAULT);


$stmt = $pdo->prepare("
    UPDATE users
    SET
        password_hash = ?,
        is_active = TRUE,
        activation_token = NULL
    WHERE id = ?
");

$stmt->execute([
    $password_hash,
    $user['id']
]);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Document</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md text-center">

<div class="text-green-600 text-6xl mb-4">
    ✔
</div>

<h2 class="text-2xl font-bold mb-3">Compte activé</h2>

<p class="text-gray-600 mb-6">Votre compte a été activé avec succès.Vous pouvez maintenant vous connecter.</p>

<a href="../auth/login.php"
   class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl inline-block">
Se connecter
</a>

</div>

</body>
</html>