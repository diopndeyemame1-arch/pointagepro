<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$token = $_GET['token'] ?? '';
if (empty($token)) {
    $_SESSION['error'] = 'Lien invalide ou manquant.';
    header('Location: index.php?page=mot-de-passe-oublie');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - PointagePro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-100 min-h-screen px-4 py-6 sm:py-8 flex items-center">
    <div class="w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="grid md:grid-cols-2">
            <div class="hidden md:flex items-center justify-center bg-blue-900 min-h-[600px]">
                <img src="/images/dashboard.png" alt="PointagePro" class="w-full h-full min-h-[600px] object-cover">
            </div>
            <div class="bg-white p-6">
                <div class="flex justify-center">
                    <div class="w-16 h-16 rounded-full bg-blue-800 flex items-center justify-center text-2xl">
                        <i class="bi bi-shield-lock-fill text-white"></i>
                    </div>
                </div>
                <h2 class="text-center text-2xl font-bold text-gray-800 mt-4">Réinitialiser le mot de passe</h2>
                <p class="text-center text-gray-500 text-sm mt-2">Entrez un nouveau mot de passe pour votre compte.</p>
                <form class="mt-6" method="POST" action="index.php?page=save_password">
                    <?php if(!empty($_SESSION['error'])): ?>
                        <div class="mb-4 text-center text-red-700 bg-red-100 rounded-xl px-4 py-3">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Nouveau mot de passe</label>
                        <input type="password" name="password" required minlength="8" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="********">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Confirmation</label>
                        <input type="password" name="confirm_password" required minlength="8" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="********">
                    </div>
                    <button type="submit" class="w-full mt-5 bg-gradient-to-r from-blue-900 to-sky-500 hover:from-blue-700 hover:to-sky-400 text-white py-2.5 rounded-xl font-semibold transition">Réinitialiser le mot de passe</button>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        <a href="index.php?page=login" class="text-blue-900 hover:underline">Retour à la connexion</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
