<?php

if (session_status() === PHP_SESSION_NONE) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
}

$role = $_SESSION['role'] ?? '';
$page = $_GET['page'] ?? '';

?>

<style>
@media (max-width: 1023px) {
    main {
        padding-bottom: 7rem !important;
    }

    main h1 {
        font-size: clamp(1.75rem, 8vw, 2.5rem) !important;
        line-height: 1.15 !important;
    }

    main h2 {
        overflow-wrap: anywhere;
    }

    table {
        min-width: 640px;
    }
}
</style>

<aside class="
w-full
lg:w-64
bg-gradient-to-b
from-slate-900
via-slate-800
to-amber-900
text-white
fixed
bottom-0
left-0
right-0
lg:top-0
lg:bottom-auto
h-20
lg:h-screen
shadow-2xl
z-50
overflow-x-auto
lg:overflow-x-hidden
">


<!-- ================= LOGO ================= -->

<div class="
p-6
border-b
border-white/10
hidden
lg:block
">


<h1 class="
text-3xl
font-bold
flex
items-center
gap-2
">


<i class="
bi bi-clock-history
text-[#B8863A]
">
</i>


Pointage

<span class="text-[#B8863A]">
Pro
</span>


</h1>


<p class="
text-xs
text-slate-300
mt-2
">

Gestion intelligente des présences

</p>


</div>





<!-- ================= MENU ADMIN ================= -->

<?php if($role == 'admin'): ?>


<nav class="
px-3
py-2
lg:px-4
lg:py-5
flex
lg:block
items-center
gap-2
lg:space-y-2
">



<?php


$menus = [

[
'page'=>'admin',
'icon'=>'bi-speedometer2',
'name'=>'Tableau de bord'
],

[
'page'=>'presence_admin',
'icon'=>'bi-calendar-check',
'name'=>'Présences'
],

[
'page'=>'absence_admin',
'icon'=>'bi-person-x',
'name'=>'Absences'
],

[
'page'=>'holiday',
'icon'=>'bi-calendar-event',
'name'=>'Jours fériés'
],

[
'page'=>'reports',
'icon'=>'bi-file-earmark-bar-graph',
'name'=>'Rapports'
],

[
'page'=>'audit_logs',
'icon'=>'bi-shield-lock',
'name'=>'Audit Logs'
],

[
'page'=>'qr_code',
'icon'=>'bi-qr-code-scan',
'name'=>'QR Code'
],

[
'page'=>'departments',
'icon'=>'bi-diagram-3-fill',
'name'=>'Départements'
],

[
'page'=>'users',
'icon'=>'bi-people-fill',
'name'=>'Utilisateurs'
],

[
'page'=>'settings',
'icon'=>'bi-gear-fill',
'name'=>'Paramètres'
]


];



foreach($menus as $menu):

$active = ($page == $menu['page']);

?>


<a href="index.php?page=<?= $menu['page'] ?>"

class="

group

relative

flex

items-center

gap-3

px-3
lg:px-4

py-2
lg:py-3

rounded-2xl
lg:rounded-xl

transition-all

duration-300


<?= $active

?

'bg-blue-600 shadow-lg shadow-blue-500/30'

:

'hover:bg-gradient-to-r hover:from-blue-600 hover:to-[#8B5E34]'

?>


lg:hover:translate-x-2

hover:shadow-xl

">


<!-- Barre gauche -->

<span class="

absolute

left-0

top-0

h-full

w-1

bg-[#B8863A]

rounded-r

opacity-0

group-hover:opacity-100

transition

duration-300

">

</span>



<i class="

bi <?= $menu['icon'] ?>

text-xl

transition

duration-300


<?= $active

?

'text-white'

:

'text-slate-300 group-hover:text-white'

?>

">

</i>



<span class="text-xs lg:text-base whitespace-nowrap">

<?= $menu['name'] ?>

</span>



</a>


<?php endforeach; ?>


</nav>


<?php endif; ?>






<!-- ================= MENU ETUDIANT ================= -->


<?php if($role == 'etudiant'): ?>


<nav class="
px-3
py-2
lg:px-4
lg:py-5
flex
lg:block
items-center
gap-2
lg:space-y-2
">


<?php


$menus = [

[
'page'=>'etudiant',
'icon'=>'bi-speedometer2',
'name'=>'Tableau de bord'
],

[
'page'=>'presence',
'icon'=>'bi-calendar-check',
'name'=>'Mes Présences'
],

[
'page'=>'absence',
'icon'=>'bi-person-x',
'name'=>'Mes Absences'
],

[
'page'=>'holiday',
'icon'=>'bi-calendar-event',
'name'=>'Jours fériés'
],

[
'page'=>'qr_code_etu',
'icon'=>'bi-qr-code-scan',
'name'=>'Mon QR Code'
],

[
'page'=>'profil',
'icon'=>'bi-person-circle',
'name'=>'Mon Profil'
]

];


foreach($menus as $menu):

$active = ($page==$menu['page']);

?>


<a href="index.php?page=<?= $menu['page'] ?>"

class="

group

relative

flex

items-center

gap-3

px-3
lg:px-4

py-2
lg:py-3

rounded-2xl
lg:rounded-xl

transition-all

duration-300


<?= $active

?

'bg-blue-600 shadow-lg'

:

'hover:bg-gradient-to-r hover:from-blue-600 hover:to-[#8B5E34]'

?>


lg:hover:translate-x-2

">


<i class="

bi <?= $menu['icon'] ?>

text-xl

">

</i>


<span class="text-xs lg:text-base whitespace-nowrap">
<?= $menu['name'] ?>
</span>


</a>


<?php endforeach; ?>


</nav>


<?php endif; ?>






<!-- ================= LOGOUT ================= -->


<div class="
absolute
bottom-6
left-4
right-4
hidden
lg:block
">


<hr class="
border-white/20
mb-0.03
">


<a href="index.php?page=logout"

class="

group

flex

items-center

gap-3

px-4

py-3

rounded-xl

transition-all

duration-300

hover:bg-gradient-to-r

hover:from-blue-600

hover:to-[#8B5E34]-600

hover:translate-x-2

">


<i class="
bi bi-box-arrow-right
text-xl
group-hover:rotate-12
transition
">

</i>


Déconnexion


</a>


</div>



</aside>
