<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<?php require_once '../layouts/sidebar.php'; ?>
    <main class="flex-1 ml-64 p-8">
    
<div class="bg-slate-50 border rounded-xl p-5 mb-6">

    <!-- Titre -->
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="bi bi-person-workspace text-blue-600"></i>
        </div>

        <h3 class="font-semibold text-lg">
            Profil Manager
        </h3>
    </div>

    <div class="flex flex-col md:flex-row items-center gap-6">

        <!-- Photo -->
        <div class="relative">

            <img
                src="https://i.pravatar.cc/150?img=12"
                alt="Manager"
                class="w-28 h-28 rounded-full border-4 border-blue-600 object-cover">

            <button
                class="absolute bottom-0 right-0 bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center">

                <i class="bi bi-camera-fill"></i>

            </button>

        </div>

        <!-- Informations -->
        <div class="flex-1 grid md:grid-cols-2 gap-4 w-full">

            <div>
                <label class="flex items-center gap-2 mb-2 font-medium">
                    <i class="bi bi-person-fill text-blue-600"></i>
                    Nom complet
                </label>

                <input
                    type="text"
                    value="Moussa Ba"
                    class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="flex items-center gap-2 mb-2 font-medium">
                    <i class="bi bi-envelope-fill text-green-600"></i>
                    Email
                </label>

                <input
                    type="email"
                    value="moussa.ba@pointagepro.com"
                    class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="flex items-center gap-2 mb-2 font-medium">
                    <i class="bi bi-telephone-fill text-orange-600"></i>
                    Téléphone
                </label>

                <input
                    type="text"
                    value="+221 77 456 78 90"
                    class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="flex items-center gap-2 mb-2 font-medium">
                    <i class="bi bi-person-badge-fill text-purple-600"></i>
                    Rôle
                </label>

                <input
                    type="text"
                    value="Manager"
                    readonly
                    class="w-full border rounded-xl px-4 py-3 bg-gray-100">
            </div>

            <div>
                <label class="flex items-center gap-2 mb-2 font-medium">
                    <i class="bi bi-diagram-3-fill text-indigo-600"></i>
                    Département
                </label>

                <input
                    type="text"
                    value="Développement Web"
                    class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="flex items-center gap-2 mb-2 font-medium">
                    <i class="bi bi-briefcase-fill text-red-600"></i>
                    Poste
                </label>

                <input
                    type="text"
                    value="Responsable pédagogique"
                    class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="flex items-center gap-2 mb-2 font-medium">
                    <i class="bi bi-people-fill text-cyan-600"></i>
                    Étudiants supervisés
                </label>

                <input
                    type="number"
                    value="45"
                    class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="flex items-center gap-2 mb-2 font-medium">
                    <i class="bi bi-book-fill text-emerald-600"></i>
                    Référentiel géré
                </label>

                <input
                    type="text"
                    value="Génie Logiciel"
                    class="w-full border rounded-xl px-4 py-3">
            </div>

        </div>

    </div>

</div>

<!-- Sécurité -->
<div class="bg-white border rounded-xl p-5">

    <div class="flex items-center gap-3 mb-5">

        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
            <i class="bi bi-shield-lock-fill text-red-600"></i>
        </div>

        <h3 class="font-semibold text-lg">
            Sécurité du compte
        </h3>

    </div>

    <div class="grid md:grid-cols-2 gap-4">

        <div>
            <label class="flex items-center gap-2 mb-2 font-medium">
                <i class="bi bi-lock-fill text-red-600"></i>
                Mot de passe actuel
            </label>

            <input
                type="password"
                class="w-full border rounded-xl px-4 py-3">
        </div>

        <div>
            <label class="flex items-center gap-2 mb-2 font-medium">
                <i class="bi bi-key-fill text-green-600"></i>
                Nouveau mot de passe
            </label>

            <input
                type="password"
                class="w-full border rounded-xl px-4 py-3">
        </div>

    </div>

    <div class="mt-4">
        <label class="flex items-center gap-2 mb-2 font-medium">
            <i class="bi bi-shield-check text-blue-600"></i>
            Confirmer le mot de passe
        </label>

        <input
            type="password"
            class="w-full border rounded-xl px-4 py-3">
    </div>

    <div class="flex justify-end mt-6">
        <button
            class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-xl flex items-center gap-2">

            <i class="bi bi-save-fill"></i>
            Enregistrer les modifications

        </button>
    </div>

</div>
</main>

</body>
</html>