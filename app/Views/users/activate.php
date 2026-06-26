<?php
require_once '../../../config/database.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Token manquant");
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE activation_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$user) {
    die(" Lien invalide ou déjà utilisé");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">

    <h2 class="text-2xl font-bold mb-4 text-center"> Activation du compte</h2>

    <p class="text-gray-600 text-center mb-6">
        Créez votre mot de passe pour activer votre compte
    </p>

    <form action="save_password.php" method="POST" class="space-y-4">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <input type="password"
               name="password"
               required
               placeholder="Mot de passe"
               class="w-full border rounded-xl p-3">

        <input type="password"
               name="confirm_password"
               required
               placeholder="Confirmer le mot de passe"
               class="w-full border rounded-xl p-3">

        <button type="submit"
                class="w-full bg-green-700 text-white py-3 rounded-xl hover:bg-green-800">
            Activer mon compte
        </button>

    </form>

</div>

</body>
</html>