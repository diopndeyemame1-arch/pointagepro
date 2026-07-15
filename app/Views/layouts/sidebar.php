<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['role'] ?? '';
$page = $_GET['page'] ?? '';
?>
<aside class="
fixed
top-0
left-0
w-64
h-screen
flex
flex-col
bg-gradient-to-b
from-slate-900
via-slate-800
to-amber-900
text-white
shadow-2xl
z-50
overflow-y-auto
">

<!-- ================= LOGO ================= -->
<div class="p-6 border-b border-white/10 flex-shrink-0">
    <h1 class="text-3xl font-bold flex items-center gap-2">
        <i class="bi bi-clock-history text-[#B8863A]"></i>
        Pointage
        <span class="text-[#B8863A]">Pro</span>
    </h1>
    <p class="text-xs text-slate-300 mt-2">Gestion intelligente des présences</p>
</div>

<!-- ================= MENU ADMIN ================= -->
<?php if($role == 'admin'): ?>
<nav class="flex-1 px-4 py-5 space-y-2">

<?php
$menus = [
    ['page'=>'admin',            'icon'=>'bi-speedometer2',         'name'=>'Tableau de bord'],
    ['page'=>'presence_admin',   'icon'=>'bi-calendar-check',      'name'=>'Présences'],
    ['page'=>'absence_admin',    'icon'=>'bi-person-x',            'name'=>'Absences'],
    ['page'=>'holiday',          'icon'=>'bi-calendar-event',      'name'=>'Jours fériés'],
    ['page'=>'reports',          'icon'=>'bi-file-earmark-bar-graph', 'name'=>'Rapports'],
    ['page'=>'audit_logs',       'icon'=>'bi-shield-lock',         'name'=>'Audit Logs'],
    ['page'=>'qr_code',          'icon'=>'bi-qr-code-scan',        'name'=>'QR Code'],
    ['page'=>'departments',      'icon'=>'bi-diagram-3-fill',      'name'=>'Départements'],
    ['page'=>'users',            'icon'=>'bi-people-fill',         'name'=>'Utilisateurs'],
    ['page'=>'settings',         'icon'=>'bi-gear-fill',           'name'=>'Paramètres']
];

foreach($menus as $menu):
    $active = ($page == $menu['page']);
?>
    <a href="index.php?page=<?= $menu['page'] ?>"
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 <?= $active ? 'bg-blue-600 shadow-lg' : 'hover:bg-gradient-to-r hover:from-blue-600 hover:to-[#8B5E34]' ?>">
        <i class="bi <?= $menu['icon'] ?> text-xl"></i>
        <span><?= $menu['name'] ?></span>
    </a>
<?php endforeach; ?>

</nav>
<?php endif; ?>

<!-- ================= MENU ETUDIANT ================= -->
<?php if($role == 'etudiant'): ?>
<nav class="flex-1 px-4 py-5 space-y-2">

<?php
$menus = [
    ['page'=>'etudiant',   'icon'=>'bi-speedometer2',    'name'=>'Tableau de bord'],
    ['page'=>'presence',   'icon'=>'bi-calendar-check',  'name'=>'Mes Présences'],
    ['page'=>'absence',    'icon'=>'bi-person-x',        'name'=>'Mes Absences'],
    ['page'=>'holiday',    'icon'=>'bi-calendar-event',  'name'=>'Jours fériés'],
    ['page'=>'qr_code_etu','icon'=>'bi-qr-code-scan',    'name'=>'Mon QR Code'],
    ['page'=>'profil',     'icon'=>'bi-person-circle',   'name'=>'Mon Profil']
];

foreach($menus as $menu):
    $active = ($page == $menu['page']);
?>
    <a href="index.php?page=<?= $menu['page'] ?>"
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 <?= $active ? 'bg-blue-600 shadow-lg' : 'hover:bg-gradient-to-r hover:from-blue-600 hover:to-[#8B5E34]' ?>">
        <i class="bi <?= $menu['icon'] ?> text-xl"></i>
        <span><?= $menu['name'] ?></span>
    </a>
<?php endforeach; ?>

</nav>
<?php endif; ?>

<!-- ================= PROFIL ADMIN ================= -->
<?php if($role == 'admin' && isset($_SESSION['admin'])):
    $admin = $_SESSION['admin'];
    $photoName = $admin['photo'] ?? '';
    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($admin['name']) . '&background=B8863A&color=fff&size=96';
    if (!empty($photoName)) {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/COUR-TELLY-TECH/pointagepro/public/' . $photoName;
        if (file_exists($fullPath)) {
            $avatarUrl = '/COUR-TELLY-TECH/pointagepro/public/' . $photoName;
        }
    }
?>
<div class="border-t border-white/10 px-4 py-3 flex-shrink-0">
    <div class="flex items-center gap-3">
        <img src="<?= $avatarUrl ?>"
             onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($admin['name']) ?>&background=B8863A&color=fff&size=96'"
             class="w-10 h-10 rounded-full object-cover border-2 border-[#B8863A] flex-shrink-0">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold truncate"><?= htmlspecialchars($admin['name']) ?></p>
            <p class="text-xs text-slate-400 truncate"><?= htmlspecialchars($admin['email']) ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ================= LOGOUT ================= -->
<div class="border-t border-white/20 px-4 py-3 flex-shrink-0">
    <a href="index.php?page=logout"
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-blue-600 hover:to-[#8B5E34]">
        <i class="bi bi-box-arrow-right text-xl"></i>
        Déconnexion
    </a>
</div>

</aside>