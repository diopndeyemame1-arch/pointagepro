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
<body class="bg-slate-100">
    <?php require_once '../layouts/sidebar.php'; ?>

<main class="ml-64 p-8">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Mon QR Code -->
        <div class="bg-white rounded-2xl shadow p-6 text-center">

            <h3 class="text-xl font-bold mb-4">
                Mon QR Code
            </h3>

            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=ETU001"
                class="mx-auto">

            <h4 class="font-semibold mt-4">
                Ndeye Mame Diop
            </h4>

            <p class="text-gray-500">
                ETU001
            </p>

            <button
                class="mt-4 bg-green-700 hover:bg-green-800 text-white px-5 py-3 rounded-xl">
                <i class="bi bi-download"></i>
                Télécharger
            </button>

        </div>

        <!-- Scanner QR Code -->
        <div class="bg-white rounded-2xl shadow p-6">

            <h2 class="text-2xl font-bold text-center mb-6">
                <i class="bi bi-qr-code-scan text-green-700"></i>
                Scanner un QR Code
            </h2>

            <p class="text-center text-gray-500 mb-6">
                Scannez le QR Code affiché dans votre salle.
            </p>

            <div class="border-4 border-dashed border-green-500 rounded-2xl h-64 flex items-center justify-center mb-6">

                <div class="text-center">
                    <i class="bi bi-camera text-6xl text-gray-400"></i>

                    <p class="mt-3 text-gray-500">
                        Caméra inactive
                    </p>
                </div>

            </div>

            <div class="text-center">

                <button
                    class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl">

                    <i class="bi bi-camera-fill"></i>
                    Activer la caméra

                </button>

            </div>

        </div>

    </div>

</main>
</body>
</html>