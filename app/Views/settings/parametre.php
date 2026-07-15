<?php
// if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$admin = $_SESSION['admin'];
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Models/CompanySettings.php';
require_once __DIR__ . '/../../Models/Schedule.php';
require_once __DIR__ . '/../../Models/Settings.php';

$settingsModel = new Settings($pdo);
$settings = $settingsModel->get();
$companyModel = new CompanySettings($pdo);
$company = $companyModel->get();

$scheduleModel = new Schedule($pdo);
$schedule = $scheduleModel->get();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Document</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
             <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>


    <!-- Contenu principal -->
    <main class="flex-1 ml-0 lg:ml-64 p-4 sm:p-6 lg:p-8">

        <div class="bg-white rounded-2xl shadow-lg p-6 max-w-6xl mx-auto">

            <!-- Titre -->
            <div class="flex items-center gap-3 mb-6">

                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="bi bi-gear-fill text-gray-700 text-xl"></i>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Paramètres
                    </h2>

                    <p class="text-gray-500">
                        Configurez votre application PointagePro
                    </p>
                </div>

            </div>

            <!-- Messages d'erreur / succès -->
            <?php if (isset($_SESSION['settings_error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?= htmlspecialchars($_SESSION['settings_error']) ?>
                </div>
                <?php unset($_SESSION['settings_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['settings_success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= htmlspecialchars($_SESSION['settings_success']) ?>
                </div>
                <?php unset($_SESSION['settings_success']); ?>
            <?php endif; ?>

            <!-- FORM -->
           <form method="POST" action="index.php?page=update_settings">

            <!-- ================= ADMIN ================= -->
            <div class="bg-slate-50 border rounded-xl p-5 mb-6">
            
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="bi bi-person-circle text-green-600"></i>
                    </div>
                
                    <h3 class="font-semibold text-lg">
                        Informations personnelles
                    </h3>
                </div>
            
                <div class="flex flex-col md:flex-row items-center gap-6">
            
                    <div class="relative">
                        <?php
$photo = "/uploads/6a3d2dfddd032.webp";

if (!empty($admin['photo'])) {

    $path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $admin['photo'];

    if (file_exists($path)) {
        $photo = "/uploads/" . $admin['photo'];
    }
}
?>

<img src="<?= $photo ?>"
     class="w-28 h-28 rounded-full border-4 border-green-600 object-cover">
     

                        <button type="button"
                            class="absolute bottom-0 right-0 bg-green-600 text-white w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="bi bi-camera"></i>
                        </button>
                    </div>
            
                    <div class="flex-1 grid md:grid-cols-2 gap-4 w-full">
            
                        <div>
                            <label class="block text-sm font-medium mb-2"><i class="bi bi-person-fill"></i> Nom complet </label>
                            <input type="text"
                                name="admin_name"
                                value="<?= htmlspecialchars($admin['name']) ?>"
                                class="w-full border rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2"><i class="bi bi-geo-alt-fill"></i> Email </label>
                            <input type="email"
                                name="admin_email"
                                value="<?= htmlspecialchars($admin['email']) ?>"
                                class="w-full border rounded-xl px-4 py-3">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2"><i class="bi bi-telephone-fill"></i> Téléphone </label>
                            <input type="text"
                                name="admin_phone"
                                value="<?= htmlspecialchars($admin['phone']) ?>"
                                class="w-full border rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2"><i class="bi bi-briefcase-fill"></i> Rôle </label>
                            <input type="text" value="Administrateur"
                               class="w-full border rounded-xl px-4 py-3 bg-gray-100"
                               readonly>
                        </div>
                    </div>
            
                </div>
            
            </div>

            <!-- ================= ECOLE ================= -->
            <div class="border rounded-xl p-5 mb-6">

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="bi bi-building text-green-600"></i>
                    </div>
                    <h3 class="font-semibold text-lg">
                        Informations de l'école
                    </h3>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium"><i class="bi bi-person-fill"></i> Nom de l'ecole </label>
                        <input
                        type="text"
                        name="company_name"
                        value="<?= htmlspecialchars($company['company_name'] ?? '') ?>"
                        class="w-full border rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium"><i class="bi bi-geo-alt-fill"></i> Email </label>
                       <input
                       type="email"
                       name="company_email"
                       value="<?= htmlspecialchars($company['company_email'] ?? '') ?>"
                       class="w-full border rounded-xl px-4 py-3">
                    </div>
                </div>

            </div>

            <!-- ================= HORAIRES ================= -->
            <div class="border rounded-xl p-5 mb-6">

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="bi bi-clock-history text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-lg">Horaires de travail</h3>
                </div>

                <?php
                $days = [
                    "mon" => "Lundi",
                    "tue" => "Mardi",
                    "wed" => "Mercredi",
                    "thu" => "Jeudi",
                    "fri" => "Vendredi"
                ];

                foreach ($days as $key => $label):
                ?>

                <div class="grid md:grid-cols-3 gap-3 mb-3 items-center">

                    <label class="font-medium"><?= $label ?></label>

                    <input
                        type="time"
                        name="<?= $key ?>_start"
                        value="<?= $schedule[$key.'_start'] ?? '' ?>"
                        class="border rounded-xl px-3 py-2">

                    <input
                        type="time"
                        name="<?= $key ?>_end"
                        value="<?= $schedule[$key.'_end'] ?? '' ?>"
                        class="border rounded-xl px-3 py-2">

                </div>

                <?php endforeach; ?>

            </div>

            <!-- ================= GPS ================= -->
            <div class="border rounded-xl p-5 mb-6">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="bi bi-geo-alt-fill text-green-600"></i>
                    </div>
                    <h3 class="font-semibold text-lg">Géolocalisation</h3>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label>Latitude</label>
                        <input
                        type="text"
                        name="lat"
                        id="schoolLat"
                        value="<?= htmlspecialchars($settings['school_lat'] ?? '14.721725593495935') ?>"
                        readonly
                        class="w-full border rounded-xl px-4 py-3 bg-slate-100 text-slate-700">
                    </div>
                    <div>
                        <label>Longitude</label>
                        <input
                        type="text"
                        name="lng"
                        id="schoolLng"
                        value="<?= htmlspecialchars($settings['school_lng'] ?? '-17.463747100271004') ?>"
                        readonly
                        class="w-full border rounded-xl px-4 py-3 bg-slate-100 text-slate-700">
                    </div>
                    <div>
                        <label>Rayon autorisé (mètres)</label>
                        <input
    type="number"
    name="radius"
    value="<?= htmlspecialchars($settings['radius'] ?? '') ?>"
    class="w-full border rounded-xl px-4 py-3">
                    </div>     
                </div>

                <div class="mt-3 flex items-center gap-2">
                    <input
    type="checkbox"
    name="gps_enabled"
    value="1"
    <?= !empty($settings['gps_enabled']) ? 'checked' : '' ?>>
                    <label>Activer GPS</label>
                </div>

                <button type="button"
                        onclick="getLocation()"
                        class="mt-4 bg-[#1E4F86] text-white px-4 py-2 rounded-lg">
                    Obtenir ma position
                </button>

            </div>

            <!-- ================= SECURITY ================= -->
            <!-- Sécurité -->
<div class="border rounded-xl p-5 mb-6">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
            <i class="bi bi-shield-lock-fill text-red-600"></i>
        </div>
        <h3 class="font-semibold text-lg">
            Sécurité
        </h3>
    </div>

    <!-- Mot de passe actuel -->
    <div class="mb-5">
        <label class="flex items-center gap-2 mb-2 text-sm font-medium">
            <i class="bi bi-lock-fill text-red-600"></i>
            Mot de passe actuel
        </label>

        <div class="relative">
            <input
                type="password"
                id="currentPassword"
                name="current_password"
                placeholder="Saisissez votre mot de passe actuel"
                class="w-full border rounded-xl px-4 pr-12 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">

            <button
                type="button"
                onclick="togglePassword('currentPassword','eye1')"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">

                <i id="eye1" class="bi bi-eye-fill"></i>
            </button>
        </div>
    </div>

    <!-- Nouveau mot de passe + Confirmation -->
    <div class="grid md:grid-cols-2 gap-4">

        <!-- Nouveau mot de passe -->
        <div>
            <label class="flex items-center gap-2 mb-2 text-sm font-medium">
                <i class="bi bi-key-fill text-green-600"></i>
                Nouveau mot de passe
            </label>

            <div class="relative">
                <input
                    type="password"
                    id="newPassword"
                    name="new_password"
                    placeholder="Nouveau mot de passe"
                    class="w-full border rounded-xl px-4 pr-12 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">

                <button
                    type="button"
                    onclick="togglePassword('newPassword','eye2')"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">

                    <i id="eye2" class="bi bi-eye-fill"></i>
                </button>
            </div>
        </div>

        <!-- Confirmation -->
        <div>
            <label class="flex items-center gap-2 mb-2 text-sm font-medium">
                <i class="bi bi-shield-check text-blue-600"></i>
                Confirmer le mot de passe
            </label>

            <div class="relative">
                <input
                    type="password"
                    id="confirmPassword"
                    name="confirm_password"
                    placeholder="Confirmer le mot de passe"
                    class="w-full border rounded-xl px-4 pr-12 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">

                <button
                    type="button"
                    onclick="togglePassword('confirmPassword','eye3')"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">

                    <i id="eye3" class="bi bi-eye-fill"></i>
                </button>
            </div>
        </div>

    </div>
</div>

            <!-- ================= BUTTON ================= -->
            <div class="flex justify-end">

                <button type="submit"
                        class="bg-[#1E4F86] hover:bg-[#1A406D] text-white px-6 py-3 rounded-xl flex items-center gap-2">

                    <i class="bi bi-check-circle"></i>
                    Enregistrer

                </button>

            </div>

            </form>

        </div>
    </main>

</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye-fill');
        icon.classList.add('bi-eye-slash-fill');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash-fill');
        icon.classList.add('bi-eye-fill');
    }
}

function getLocation() {

    if (!navigator.geolocation) {
        alert("Votre navigateur ne supporte pas la géolocalisation.");
        return;
    }

    navigator.geolocation.getCurrentPosition(

        function(position){

            document.getElementById("schoolLat").value = position.coords.latitude;
            document.getElementById("schoolLng").value = position.coords.longitude;

        },

        function(error){
            alert("Impossible d'obtenir votre position.");
        }

    );

}
</script>

</body>
</html>
