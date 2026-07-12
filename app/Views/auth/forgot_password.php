<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - PointagePro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-100 min-h-screen px-4 py-6 sm:py-8 flex items-center">
    <div class="w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="grid md:grid-cols-2">
            <div class="hidden md:flex items-center justify-center bg-blue-900 min-h-[600px]">
                <img src="/COUR-TELLY-TECH/pointagepro/public/images/dashboard.png" alt="PointagePro" class="w-full h-full min-h-[600px] object-cover">
            </div>
            <div class="bg-white p-6">
                <div class="flex justify-center">
                    <div class="w-16 h-16 rounded-full bg-blue-800 flex items-center justify-center text-2xl">
                        <i class="bi bi-key-fill text-white"></i>
                    </div>
                </div>
                <h2 class="text-center text-2xl font-bold text-gray-800 mt-4">Mot de passe oublié</h2>
                <p class="text-center text-gray-500 text-sm mt-2">Recevez un lien pour réinitialiser votre mot de passe.</p>
                <form class="mt-6" method="POST" action="index.php?page=send_reset_link">
                    <?php if(!empty($_SESSION['message'])): ?>
                        <div class="mb-4 text-center text-green-700 bg-green-100 rounded-xl px-4 py-3">
                            <?= htmlspecialchars($_SESSION['message']) ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
                    <?php if(!empty($_SESSION['error'])): ?>
                        <div class="mb-4 text-center text-red-700 bg-red-100 rounded-xl px-4 py-3">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <div class="relative">
                            <i class="bi bi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-blue-700"></i>
                            <input type="email" name="email" required placeholder="exemple@entreprise.com" class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-5 bg-gradient-to-r from-blue-900 to-sky-500 hover:from-blue-700 hover:to-sky-400 text-white py-2.5 rounded-xl font-semibold transition">Envoyer le lien</button>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        <a href="index.php?page=login" class="text-blue-900 hover:underline">Retour à la connexion</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
