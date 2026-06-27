<?php
session_start();

$role = $_SESSION['role'] ?? '';
$page = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-64 bg-gradient-to-b from-green-900 to-emerald-800 text-white fixed h-screen">

    <!-- Logo -->
    <div class="p-6 border-b border-white/10">
        <h1 class="text-3xl font-bold flex items-center gap-2">
            <i class="bi bi-clock-history text-yellow-400"></i>
            Pointage<span class="text-yellow-400">Pro</span>
        </h1>
    </div>

    <!-- ADMIN & MANAGER -->
    <?php if($role == 'admin'): ?>

    <nav class="px-4 py-4 space-y-1">

        <a href="../dashboard/admin.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'admin.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-speedometer2"></i>
            Tableau de bord
        </a>

        <a href="../attendance/presence.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'presence.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-calendar-check"></i>
            Présences
        </a>

        <a href="../absence/absence.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'absence.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-person-x"></i>
            Absences
        </a>

        <a href="../publicHoliday/publicHoliday.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'publicHoliday.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-calendar-event"></i>
            Jours fériés
        </a>

        <a href="../reports/rapport.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'rapport.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-file-earmark-bar-graph"></i>
            Rapports
        </a>

        <a href="../qrcode/qr_codes.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'qr_codes.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-qr-code-scan"></i>
            QR Code
        </a>
        <a href="../users/utilisateur.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'utilisateur.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-people"></i>
            Utilisateurs
        </a>

        <a href="../settings/parametre.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'parametre.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-gear"></i>
            Paramètres
        </a>
    </nav>

    <?php endif; ?>

    <!-- ETUDIANT -->
    <?php if($role == 'etudiant'): ?>

    <nav class="px-4 py-4 space-y-1">

        <a href="../dashboard/etudiant.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'etudiant.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-speedometer2"></i>
            Tableau de bord
        </a>

        <a href="../attendance/presence_etu.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'presence_etu.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-calendar-check"></i>
            Mes Présences
        </a>

        <a href="../absence/absence_etu.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'absence_etu.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-person-x"></i>
            Mes Absences
        </a>

        

        <a href="../publicHoliday/publicHoliday.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'publicHoliday.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-calendar-event"></i>
            Jours fériés
        </a>

        <a href="../qrcode/qrcode_etu.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'qrcode_etu.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-qr-code-scan"></i>
            Mon QR Code
        </a>

        <a href="../settings/profil.php"
           class="flex items-center gap-3 px-4 py-3 rounded-xl <?= ($page == 'profil.php') ? 'bg-green-600' : 'hover:bg-white/10' ?>">
            <i class="bi bi-person-circle"></i>
            Mon Profil
        </a>

    </nav>

    <?php endif; ?>


    <!-- Déconnexion -->
    <div class="absolute bottom-6 left-4 right-4">

        <hr class="border-white/20 mb-4">

        <a href="../auth/login.php"
           class="flex items-center gap-3 px-4 py-3 hover:bg-red-500/20 rounded-xl">
            <i class="bi bi-box-arrow-right"></i>
            Déconnexion
        </a>

    </div>

</aside>