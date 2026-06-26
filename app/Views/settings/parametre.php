<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Paramètres - PointagePro</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <?php require_once '../layouts/sidebar.php'; ?>

    <!-- Contenu principal -->
    <main class="flex-1 ml-64 p-8">

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
            <!-- Profil Administrateur -->
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
            
                    <!-- Photo -->
                    <div class="relative">
            
                        <img
                            src="/COUR-TELLY-TECH/pointagepro/public/images/swe.jpeg"
                            alt="Administrateur"
                            class="w-28 h-28 rounded-full border-4 border-green-600 object-cover"
                        >
            
                        <button
                            class="absolute bottom-0 right-0 bg-green-600 text-white w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="bi bi-camera"></i>
                        </button>
            
                    </div>
            
                    <!-- Informations -->
                    <div class="flex-1 grid md:grid-cols-2 gap-4 w-full">
            
                        <div>
                            <label class="block text-sm font-medium mb-2"><i class="bi bi-person-fill"></i>
                                Nom complet
                            </label>
            
                            <input
                                type="text"
                                value="Ndeye Mame Diop"
                                class="w-full border rounded-xl px-4 py-3"
                            >
                        </div>
            
                        <div>
                            <label class="block text-sm font-medium mb-2"><i class="bi bi-geo-alt-fill"></i>
                                Email
                            </label>
            
                            <input
                                type="email"
                                value="admin@pointagepro.com"
                                class="w-full border rounded-xl px-4 py-3">
                            
                        </div>
            
                        <div>
                            <label class="block text-sm font-medium mb-2"><i class="bi bi-telephone-fill"></i>
                                Téléphone
                            </label>
            
                            <input
                                type="text"
                                value="+221 77 123 45 67"
                                class="w-full border rounded-xl px-4 py-3"
                            >
                        </div>
            
                        <div>
                            <label class="block text-sm font-medium mb-2"><i class="bi bi-briefcase-fill"></i>
                                Rôle
                            </label>
            
                            <input
                                type="text"
                                value="Administrateur"
                                class="w-full border rounded-xl px-4 py-3 bg-gray-100"
                                readonly>
                            
                        </div>
                        <div>
                            <label class="flex items-center gap-2 mb-2 text-sm font-medium">
                                <i class="bi bi-diagram-3-fill text-purple-600"></i>
                                Département
                            </label>
                        
                            <input
                                type="text"
                                value="Informatique"
                                class="w-full border rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="flex items-center gap-2 mb-2 text-sm font-medium">
                                <i class="bi bi-briefcase-fill text-orange-600"></i>
                                Poste
                            </label>
                        
                            <input
                                type="text"
                                value="Administrateur système"
                                class="w-full border rounded-xl px-4 py-3" >
                        </div>
            
                    </div>
            
                </div>
            
            </div>

            <!-- Informations entreprise -->
            <div class="border rounded-xl p-5 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="bi bi-person-circle text-green-600"></i>
                    </div>
                    <h3 class="font-semibold text-lg ">
                       Informations de l'entreprise
                    </h3>
                </div>

                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-2 text-sm font-medium"><i class="bi bi-person-fill"></i>
                            Nom de l'entreprise
                        </label>

                        <input type="text"
                               value="PointagePro SARL"
                               class="w-full border rounded-xl px-4 py-3">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium"><i class="bi bi-geo-alt-fill"></i>
                            Email
                        </label>
                         
                        <input type="email"
                               value="contact@pointagepro.com"
                               class="w-full border rounded-xl px-4 py-3">
                    </div>

                </div>

            </div>

        
            <!-- Horaires de travail -->
        <div class="border rounded-xl p-5 mb-6">
        
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="bi bi-clock-history text-blue-600"></i>
                </div>
        
                <h3 class="font-semibold text-lg">
                    Horaires de travail
                </h3>
            </div>
        
            
            <!-- Référentiel -->
            <div class="mb-6">
            
                <label class="block mb-2 text-sm font-medium"><i class="bi bi-diagram-3-fill text-purple-600"></i>
                    Référentiel
                </label>
            
                <select id="referentiel"
                        onchange="updatePlanning()"
                        class="w-full border rounded-xl px-4 py-3">
            
                    <option value="">-- Choisir un référentiel --</option>
                    <option value="devweb">Développement Web</option>
                    <option value="marketing">Marketing Digital</option>
                    <option value="bureautique">Bureautique</option>
            
                </select>
            
            </div>
            
            <!-- Planning semaine -->
            <div class="border rounded-xl p-5 mb-6">
            
                <h3 class="font-semibold text-lg mb-4"><i class="bi bi-calendar-week-fill text-blue-600"></i>
                    Planning hebdomadaire
                </h3>
            
                <!-- Lundi -->
                <div class="grid md:grid-cols-3 gap-3 mb-3 items-center">
                    <label class="font-medium">Lundi</label>
                    <input type="time" id="mon_start" class="border rounded-xl px-3 py-2">
                    <input type="time" id="mon_end" class="border rounded-xl px-3 py-2">
                </div>
            
                <!-- Mardi -->
                <div class="grid md:grid-cols-3 gap-3 mb-3 items-center">
                    <label class="font-medium">Mardi</label>
                    <input type="time" id="tue_start" class="border rounded-xl px-3 py-2">
                    <input type="time" id="tue_end" class="border rounded-xl px-3 py-2">
                </div>
            
                <!-- Mercredi -->
                <div class="grid md:grid-cols-3 gap-3 mb-3 items-center">
                    <label class="font-medium">Mercredi</label>
                    <input type="time" id="wed_start" class="border rounded-xl px-3 py-2">
                    <input type="time" id="wed_end" class="border rounded-xl px-3 py-2">
                </div>
            
                <!-- Jeudi -->
                <div class="grid md:grid-cols-3 gap-3 mb-3 items-center">
                    <label class="font-medium">Jeudi</label>
                    <input type="time" id="thu_start" class="border rounded-xl px-3 py-2">
                    <input type="time" id="thu_end" class="border rounded-xl px-3 py-2">
                </div>
            
                <!-- Vendredi -->
                <div class="grid md:grid-cols-3 gap-3 mb-3 items-center">
                    <label class="font-medium">Vendredi</label>
                    <input type="time" id="fri_start" class="border rounded-xl px-3 py-2">
                    <input type="time" id="fri_end" class="border rounded-xl px-3 py-2">
                </div>
            
            </div>
        
        </div>
        <!-- Géolocalisation -->
<div class="border rounded-xl p-5 mb-6">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
            <i class="bi bi-geo-alt-fill text-green-600"></i>
        </div>

        <h3 class="font-semibold text-lg">
            Géolocalisation
        </h3>
    </div>

    <div class="border rounded-xl p-5 mb-6">

    <h3 class="font-semibold text-lg mb-4">
        <i class="bi bi-geo-alt-fill text-green-600"></i>
        Zone de pointage
    </h3>

    <div class="grid md:grid-cols-3 gap-4">

        <div>
            <label>Latitude</label>
            <input type="text"
                   id="schoolLat"
                   value="14.6937"
                   class="w-full border rounded-xl px-4 py-3">
        </div>

        <div>
            <label>Longitude</label>
            <input type="text"
                   id="schoolLng"
                   value="-17.4441"
                   class="w-full border rounded-xl px-4 py-3">
        </div>

        <div>
            <label>Rayon autorisé (mètres)</label>
            <input type="number"
                   id="radius"
                   value="100"
                   class="w-full border rounded-xl px-4 py-3">
        </div>

    </div>

</div>

    <!-- Bouton -->
    <div class="mt-5">
        <button
            onclick="getLocation()"
            class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl flex items-center gap-2">

            <i class="bi bi-crosshair"></i>
            Obtenir ma position

        </button>
    </div>

</div>


           <!-- Sécurité -->
            <div class="border rounded-xl p-5 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="bi bi-shield-lock-fill text-red-600"></i>
                    </div>
                    <h3 class="font-semibold text-lg">Sécurité</h3>
                </div>
            
                <!-- Mot de passe actuel -->
                <div class="mb-5">
                    <label class="flex items-center gap-2 mb-2 text-sm font-medium">
                        <i class="bi bi-lock-fill text-red-600"></i>
                        Mot de passe actuel
                    </label>
                    <div class="relative">
                        <input
                            type="password" id="currentPassword" placeholder="Saisissez votre mot de passe actuel"
                            class="w-full border rounded-xl px-4 pr-12 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <button
                            type="button"
                            onclick="togglePassword('currentPassword','eye1')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500" >
                            <i id="eye1" class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>
                <!-- Nouveau mot de passe + Confirmation -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center gap-2 mb-2 text-sm font-medium">
                            <i class="bi bi-key-fill text-green-600"></i>
                            Nouveau mot de passe
                        </label>
                        <div class="relative">
                            <input
                                type="password" id="newPassword" placeholder="Nouveau mot de passe"
                                class="w-full border rounded-xl px-4 pr-12 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <button
                                type="button"
                                onclick="togglePassword('newPassword','eye2')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500" >
                                <i id="eye2" class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </div>
            
                    <div>
                        <label class="flex items-center gap-2 mb-2 text-sm font-medium">
                            <i class="bi bi-shield-check text-blue-600"></i>
                            Confirmer le mot de passe
                        </label>
                        <div class="relative">
                            <input
                                type="password" id="confirmPassword" placeholder="Confirmer le mot de passe"
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

            <!-- Bouton -->
            <div class="flex justify-end">

                <button class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl flex items-center gap-2">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer les modifications
                </button>

            </div>

        </div>

    </main>

</div>
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
<script>
function updatePlanning() {

    const ref = document.getElementById("referentiel").value;

    // ===== Développement Web =====
    if (ref === "devweb") {

        setDay("08:00","13:00", "08:00","13:00", "08:00","13:00", "08:00","13:00", "08:00","12:00");

    }

    // ===== Marketing Digital =====
    else if (ref === "marketing") {

        setDay("09:00","15:00", "09:00","15:00", "09:00","15:00", "09:00","15:00", "09:00","14:00");

    }

    // ===== Bureautique =====
    else if (ref === "bureautique") {

        setDay("08:30","14:30", "08:30","14:30", "08:30","14:30", "08:30","14:30", "08:30","12:30");

    }

    else {
        clearAll();
    }
}

// Fonction pour remplir les jours
function setDay(mon_s, mon_e,
                tue_s, tue_e,
                wed_s, wed_e,
                thu_s, thu_e,
                fri_s, fri_e) {

    document.getElementById("mon_start").value = mon_s;
    document.getElementById("mon_end").value = mon_e;

    document.getElementById("tue_start").value = tue_s;
    document.getElementById("tue_end").value = tue_e;

    document.getElementById("wed_start").value = wed_s;
    document.getElementById("wed_end").value = wed_e;

    document.getElementById("thu_start").value = thu_s;
    document.getElementById("thu_end").value = thu_e;

    document.getElementById("fri_start").value = fri_s;
    document.getElementById("fri_end").value = fri_e;
}

// Reset
function clearAll() {

    const fields = [
        "mon_start","mon_end",
        "tue_start","tue_end",
        "wed_start","wed_end",
        "thu_start","thu_end",
        "fri_start","fri_end"
    ];

    fields.forEach(id => document.getElementById(id).value = "");
}
</script>

</body>
</html>