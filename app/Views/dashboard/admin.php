<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="flex min-h-screen bg-slate-100">

  

    <!-- Sidebar -->
    <?php require_once '../layouts/sidebar.php'; ?>

    <!-- Contenu -->
    <main class="flex-1 ml-64 p-8 overflow-x-hidden">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">

            <div>
                <h2 class="text-3xl font-bold">
                    Bonjour, Admin 👋
                </h2>
                <p class="text-gray-500">
                    Voici un aperçu global du système de pointage.
                </p>
            </div>

            <div class="flex items-center gap-4">

                <button class="border px-4 py-2 rounded-xl bg-white">
                    Aujourd'hui, 27 avril 2026
                </button>

                <img src="https://i.pravatar.cc/50"
                     class="w-12 h-12 rounded-full">
            </div>

        </div>

        <!-- KPI CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- Étudiants -->
            <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-blue-500">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="bi bi-mortarboard-fill text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Étudiants</p>
                        <h3 class="text-3xl font-bold">250</h3>
                    </div>
                </div>
            </div>

            <!-- Présences -->
            <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-green-500">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="bi bi-person-check-fill text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Présences Aujourd'hui</p>
                        <h3 class="text-3xl font-bold">220</h3>
                    </div>
                </div>
            </div>

            <!-- Retards -->
            <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-orange-500">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="bi bi-clock-history text-orange-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Retards</p>
                        <h3 class="text-3xl font-bold">18</h3>
                    </div>
                </div>
            </div>

            <!-- Absences -->
            <div class="bg-white p-5 rounded-2xl shadow border-l-8 border-red-500">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="bi bi-person-x-fill text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Absences</p>
                        <h3 class="text-3xl font-bold">12</h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Section résumé -->
        <div class="grid md:grid-cols-2 gap-6 mt-8">

            <!-- Activité -->
            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="bi bi-activity text-green-600"></i>
                    Activité récente
                </h3>

                <ul class="space-y-3 text-gray-600">
                    <li>✔ 5 étudiants ont pointé aujourd'hui</li>
                    <li>✔ 2 nouveaux utilisateurs ajoutés</li>
                    <li>✔ 1 congé validé</li>
                    <li>✔ 3 absences enregistrées</li>
                </ul>
            </div>

            <!-- Notifications -->
            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="bi bi-bell text-blue-600"></i>
                    Notifications
                </h3>

                <ul class="space-y-3 text-gray-600">
                    <li>🔔 Système de pointage actif</li>
                    <li>🔔 QR Code généré avec succès</li>
                    <li>🔔 Rapport mensuel disponible</li>
                </ul>
            </div>

        </div>

    </main>

</div>


</body>
</html>