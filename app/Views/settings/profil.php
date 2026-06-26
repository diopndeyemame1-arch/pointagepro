<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">
     <?php require_once '../layouts/sidebar.php'; ?>

    <main class="flex-1 ml-64 p-8">

    <!-- Titre -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold">
            Mon Profil
        </h2>

        <p class="text-gray-500">
            Consultez et mettez à jour vos informations personnelles.
        </p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Carte profil -->
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex flex-col items-center">

                <img src="/COUR-TELLY-TECH/pointagepro/public/images/0r3.jpeg"
                     class="w-32 h-32 rounded-full border-4 border-green-500">

                <h3 class="mt-4 text-2xl font-bold">
                    Binta Ndiaye
                </h3>

                <p class="text-gray-500">
                    Étudiante
                </p>

                <button class="mt-4 bg-green-800 text-white px-4 py-2 rounded-xl">
                    <i class="bi bi-camera"></i>
                    Changer la photo
                </button>

            </div>

        </div>

        <!-- Informations -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">

            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <i class="bi bi-person-circle text-green-700"></i>
                Informations personnelles
            </h3>

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label class="font-medium">
                        <i class="bi bi-person"></i>
                        Prénom
                    </label>
                    <input type="text"
                           value="Binta"
                           class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="font-medium">
                        <i class="bi bi-person"></i>
                        Nom
                    </label>
                    <input type="text"
                           value="Ndiaye"
                           class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="font-medium">
                        <i class="bi bi-envelope"></i>
                        Email
                    </label>
                    <input type="email"
                           value="ndiaye@gmail.com"
                           class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="font-medium">
                        <i class="bi bi-telephone"></i>
                        Téléphone
                    </label>
                    <input type="text"
                           value="77 123 45 67"
                           class="w-full border rounded-xl px-4 py-3 mt-2">
                </div>

            </div>

        </div>

    </div>

    <!-- Informations académiques -->
    <div class="bg-white rounded-2xl shadow p-6 mt-6">

        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="bi bi-mortarboard-fill text-blue-600"></i>
            Informations académiques
        </h3>

        <div class="grid md:grid-cols-3 gap-4">

            <div>
                <label class="font-medium">
                    Departement
                </label>
                <input type="text"
                       value="Developpement Web"
                       class="w-full border rounded-xl px-4 py-3 mt-2 " readonly>
            </div>

            <div>
                <label class="font-medium">
                    Cohorte
                </label>
                <input type="text"
                       value="Cohorte 1"
                       class="w-full border rounded-xl px-4 py-3 mt-2" readonly>
            </div>

            <div>
                <label class="font-medium">
                    Matricule
                </label>
                <input type="text"
                       value="ETU001"
                       class="w-full border rounded-xl px-4 py-3 mt-2" readonly>
            </div>

        </div>

    </div>

    


        <button class="mt-6 bg-green-800 hover:bg-green-700 text-white px-6 py-3 rounded-xl">
            Enregistrer les modifications
        </button>

    </div>

</main>
<script>
function togglePassword(inputId, iconId) {

    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
    }
}
</script>
</body>
</html>