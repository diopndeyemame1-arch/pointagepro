<?php
if(session_status() === PHP_SESSION_NONE){
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PointagePro - Connexion</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-slate-100 min-h-screen px-4 py-6 sm:py-8 flex items-center">

    <div class="w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden">

        <div class="grid md:grid-cols-2">

<!-- Partie gauche -->
<div class="hidden md:flex items-center justify-center bg-blue-900 min-h-[600px]">

    <img 
        src="/images/dashboard.png"
        src="/COUR-TELLY-TECH/pointagepro/public/images/dashboards.png"
        alt="PointagePro"
        class="w-full h-full min-h-[600px] object-cover"
    >

</div>

            <!-- Partie droite -->
            <div class="bg-white p-6">

                <div class="flex justify-center">
                    <div class="w-16 h-16 rounded-full bg-blue-800 flex items-center justify-center text-2xl">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                </div>

                <h2 class="text-center text-2xl font-bold text-gray-800 mt-4">
                    Se connecter
                </h2>

                <p class="text-center text-gray-500 text-sm mt-2">
                    Accédez à votre espace sécurisé
                </p>

                <form class="mt-6" method="POST" action="index.php?page=trait-login">
                    <p style="color:red; text-align:center;">
         <?php
            if(isset($_SESSION['error'])){
               echo $_SESSION['error'];
               unset($_SESSION['error']);
            }
         ?>
     </p> <br>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <div class="relative">
                            <i class="bi bi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-blue-700"></i>
                        
                            <input
                                type="email" name="email"
                                placeholder="exemple@entreprise.com"
                                class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Mot de passe
                        </label>

                       <div class="relative">
                             <i class="bi bi-lock absolute left-3 top-1/2 -translate-y-1/2 text-blue-700"></i>
                         
                             <input
                                 type="password" name="password" id="passwordInput"
                                 placeholder="********"
                                 class="w-full border border-gray-300 rounded-xl pl-10 pr-12 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"
                             >
                             <button type="button" onclick="togglePassword()"
                                 class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-700 transition">
                                 <i id="togglePasswordIcon" class="bi bi-eye"></i>
                             </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-4 text-sm">

                        <a href="index.php?page=mot-de-passe-oublie" class="text-blue-900 hover:underline">
                            Mot de passe oublié ?
                        </a>

                    </div>

                    <button
                        type="submit"
                        class="w-full mt-5 bg-gradient-to-r from-blue-900 to-sky-500 hover:from-amber-700 hover:to-sky-400 text-white py-2.5 rounded-xl font-semibold transition" >
                        Se connecter
                    </button>

                </form>

            </div>

        </div>

    </div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('togglePasswordIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

</body>
</html>
