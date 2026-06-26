<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-100">

<?php require_once '../layouts/sidebar.php'; ?>

<main class="flex-1 ml-64 p-8">

    <!-- Titre -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold">
            Mes Présences
        </h2>

        <p class="text-gray-500">
            Consultez votre historique de présence.
        </p>
    </div>

    <!-- Cartes statistiques -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">

        <!-- Présences -->
        <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-green-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-calendar-check-fill text-green-600 text-2xl"></i>
                </div>

                <div>
                    <p class="text-gray-500 text-sm uppercase">
                        Présences
                    </p>
                    <h3 class="text-3xl font-bold">120</h3>
                </div>
            </div>
        </div>

        <!-- Retards -->
        <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-orange-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-clock-fill text-orange-600 text-2xl"></i>
                </div>

                <div>
                    <p class="text-gray-500 text-sm uppercase">
                        Retards
                    </p>
                    <h3 class="text-3xl font-bold">5</h3>
                </div>
            </div>
        </div>

        <!-- Taux -->
        <div class="bg-white rounded-2xl shadow p-6 border-l-8 border-blue-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-graph-up-arrow text-blue-600 text-2xl"></i>
                </div>

                <div>
                    <p class="text-gray-500 text-sm uppercase">
                        Taux de présence
                    </p>
                    <h3 class="text-3xl font-bold">96%</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-2xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold flex items-center gap-2">
                <i class="bi bi-list-check text-green-700"></i>
                Historique des présences
            </h3>

            <input
                type="date"
                class="border rounded-xl px-4 py-2">
        </div>
        <div class="bg-white rounded-2xl shadow p-6 mb-8">

    <h3 class="text-xl font-bold mb-4">
        Pointage du jour
    </h3>

    <div class="flex gap-4">

        <form method="POST" action="pointage_entree.php">
           <button
                type="button"
                onclick="pointerEntree()"
                class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl flex items-center gap-2">
                <i class="bi bi-box-arrow-in-right"></i>
                Pointer l'entrée
            </button>
            <p id="gps_status" class="mt-4"></p>
        </form>

        <form method="POST" action="pointage_sortie.php">
            <button
                type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl flex items-center gap-2">

                <i class="bi bi-box-arrow-right"></i>
                Pointer la sortie

            </button>
        </form>

    </div>

</div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-green-800 text-white">
                    <tr>
                        <th class="text-left p-4">Date</th>
                        <th class="text-left p-4">Entrée</th>
                        <th class="text-left p-4">Sortir</th>
                        <th class="text-left p-4">Statut</th>
                    </tr>
                </thead>

                <tbody>

                    <tr class="border-t hover:bg-slate-50">
                        <td class="p-4">16/06/2026</td>
                        <td class="p-4">08:00</td>
                        <td class="p-4">17:00</td>
                        <td class="p-4">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Présent
                            </span>
                        </td>
                    </tr>

                    <tr class="border-t hover:bg-slate-50">
                        <td class="p-4">15/06/2026</td>
                        <td class="p-4">08:20</td>
                        <td class="p-4">17:00</td>
                        <td class="p-4">
                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                                Retard
                            </span>
                        </td>
                    </tr>

                    <tr class="border-t hover:bg-slate-50">
                        <td class="p-4">14/06/2026</td>
                        <td class="p-4">08:05</td>
                        <td class="p-4">17:00</td>
                        <td class="p-4">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Présent
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</main>
<script>
// 📍 Coordonnées école (Liberté 5 - approximatif Dakar)
const ecoleLat = 14.7128;
const ecoleLng = -17.4677;

// distance max autorisée (en mètres)
const MAX_DISTANCE = 5000; // 5 km

function distance(lat1, lon1, lat2, lon2) {

    const R = 6371e3; // rayon terre en mètres

    const φ1 = lat1 * Math.PI/180;
    const φ2 = lat2 * Math.PI/180;

    const Δφ = (lat2-lat1) * Math.PI/180;
    const Δλ = (lon2-lon1) * Math.PI/180;

    const a =
        Math.sin(Δφ/2) * Math.sin(Δφ/2) +
        Math.cos(φ1) * Math.cos(φ2) *
        Math.sin(Δλ/2) * Math.sin(Δλ/2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c;
}

function pointerEntree() {

    const status = document.getElementById("gps_status");

    if (!navigator.geolocation) {
        status.innerHTML = "❌ GPS non supporté";
        status.className = "text-red-600 font-semibold";
        return;
    }

    navigator.geolocation.getCurrentPosition(

        function(position) {

            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            const dist = distance(userLat, userLng, ecoleLat, ecoleLng);

            if (dist <= MAX_DISTANCE) {

                status.innerHTML =
                    "✅ Présence validée<br>Distance école : "
                    + Math.round(dist)
                    + " mètres";

                status.className = "text-green-600 font-semibold";

            } else {

                status.innerHTML =
                    "❌ Vous êtes trop loin de l'école<br>Distance : "
                    + Math.round(dist)
                    + " mètres";

                status.className = "text-red-600 font-semibold";
            }

        },

        function() {

            status.innerHTML = "❌ Impossible d'obtenir votre position";
            status.className = "text-red-600 font-semibold";
        }

    );
}
</script>
<!--bbbbbbbbbbbbbbbbbbbbb-->
</body>
</html>