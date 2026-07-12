<?php
// require_once '../../../config/database.php';

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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Activation du compte</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-[#EEEBE2] min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden">

    <!-- Header -->

    <div class="bg-slate-900 px-8 py-8">

        <div class="flex justify-between items-center">

            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-xl bg-amber-600 flex items-center justify-center">

                    <i class="bi bi-qr-code-scan text-white text-2xl"></i>

                </div>

                <div>

                    <h2 class="text-white text-2xl font-bold">
                        PointagePro
                    </h2>

                    <p class="text-gray-300 text-sm">
                        Plateforme de pointage
                    </p>

                </div>

            </div>

            <span class="text-amber-500 font-bold">
                TELLYTECH
            </span>

        </div>

    </div>

    <!-- Body -->

    <div class="p-8">

        <span class="uppercase tracking-widest text-amber-700 font-semibold text-xs">
            Activation
        </span>

        <h1 class="text-3xl font-bold mt-3 text-slate-900">

            Bienvenue

            <span class="text-green-700">
                <?= htmlspecialchars($user['firstname']) ?>
            </span>

        </h1>

        <p class="mt-5 text-gray-600 leading-7">

            Votre compte PointagePro a été créé avec succès.

            Pour commencer à utiliser la plateforme,

            veuillez définir votre mot de passe.

        </p>

        <!-- Carte utilisateur -->

        <div class="mt-8 rounded-2xl border bg-slate-50 p-5 space-y-4">

            <div class="flex justify-between">

                <span class="text-gray-500">
                    Nom
                </span>

                <strong>
                    <?= htmlspecialchars($user['firstname'].' '.$user['lastname']) ?>
                </strong>

            </div>

            <div class="border-t border-dashed"></div>

            <div class="flex justify-between">

                <span class="text-gray-500">
                    Email
                </span>

                <strong class="text-sm">
                    <?= htmlspecialchars($user['email']) ?>
                </strong>

            </div>

            <div class="border-t border-dashed"></div>

            <div class="flex justify-between">

                <span class="text-gray-500">
                    Rôle
                </span>

                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-semibold">

                    <?= ucfirst($user['role']) ?>

                </span>

            </div>

        </div>

        <!-- Formulaire -->

        <form action="index.php?page=save_password" method="POST"
              class="mt-8 space-y-5">

            <input
                type="hidden"
                name="token"
                value="<?= htmlspecialchars($token) ?>">

            <div>

                <label class="font-semibold mb-2 block">

                    Nouveau mot de passe

                </label>

                <input
                    type="password"
                    name="password"
                    required
                    minlength="8"
                    class="w-full border rounded-xl px-4 py-4 focus:ring-2 focus:ring-green-700 outline-none"
                    placeholder="********">

            </div>

            <div>

                <label class="font-semibold mb-2 block">

                    Confirmation

                </label>

                <input
                    type="password"
                    name="confirm_password"
                    required
                    minlength="8"
                    class="w-full border rounded-xl px-4 py-4 focus:ring-2 focus:ring-green-700 outline-none"
                    placeholder="********">

            </div>

            <button
                type="submit"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl transition">

                <i class="bi bi-check-circle-fill mr-2"></i>

                Activer mon compte

            </button>

        </form>

        <p class="text-center text-gray-500 text-sm mt-8">

            Ce lien est valable pendant 48 heures.

        </p>

    </div>

</div>

</body>
</html>