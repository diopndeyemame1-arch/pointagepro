<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointage QR Code</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body class="bg-slate-100">

<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="lg:ml-64 p-4 md:p-6">

    <!-- Titre -->
    <div class="bg-gradient-to-r from-blue-900 to-amber-700 rounded-3xl p-8 shadow-xl text-white mb-8">
        <div class="flex flex-col lg:flex-row justify-between gap-6">
            <div>
                <h1 class="text-4xl font-bold flex items-center gap-3">
                    <i class="bi bi-qr-code-scan text-5xl"></i>
                    Pointage QR Code
                </h1>
                <p class="mt-3 text-blue-100">
                    Utilisez votre QR Code personnel pour enregistrer votre présence.
                </p>
            </div>
            <div class="bg-white/20 backdrop-blur rounded-2xl p-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-white/30 flex items-center justify-center">
                        <i class="bi bi-person-badge text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-white/80">Espace étudiant</p>
                        <h3 class="text-2xl font-bold">Mon QR Code</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-stretch max-w-7xl mx-auto">

        <!-- ================= QR CODE ================= -->

        <div id="printArea" class="bg-white rounded-3xl shadow-lg p-6 flex flex-col h-full border border-slate-200">

            <div class="text-center">

                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto">

                    <i class="bi bi-person-badge text-3xl text-blue-900"></i>

                </div>

                <h2 class="text-2xl font-bold mt-4 text-blue-900">
                    Mon QR Code
                </h2>

                <p class="text-gray-500 mt-2">
                    Présentez ce QR Code devant le scanner de votre salle.
                </p>

            </div>

            <div class="flex justify-center my-8">

                <img src="<?= $qrCode ?>" class="w-56 h-56 mx-auto rounded-3xl border border-slate-200 p-2 bg-white shadow-sm">

            </div>

            <div class="space-y-3 mt-4">

                <div class="bg-slate-50 rounded-xl px-4 py-3 flex justify-between">

                    <span class="text-gray-500">
                        Nom
                    </span>

                    <span class="font-semibold">
                       <?= htmlspecialchars($etudiant['firstname']) ?>
                       <?= htmlspecialchars($etudiant['lastname']) ?>
                    </span>

                </div>

                <div class="bg-slate-50 rounded-xl px-4 py-3 flex justify-between">

                   <span class="text-gray-500">
                              UUID
                     </span>

                   <span class="font-semibold text-xs break-all">
                          <?= htmlspecialchars($etudiant['id']) ?>
                   </span>
                </div>

                <div class="bg-slate-50 rounded-xl px-4 py-3 flex justify-between">

                    <span class="text-gray-500">
                        Department
                    </span>

                    <span class="font-semibold">
                       <?= htmlspecialchars($etudiant['department']) ?>
                    </span>

                </div>

            </div>

         <div class="mt-8 flex justify-center">

    <a
        href="<?= $qrCode ?>"
        download="<?= $etudiant['firstname'] ?>_<?= $etudiant['lastname'] ?>_QRCode.png"
       class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-2xl font-semibold transition inline-flex items-center shadow-lg">

        <i class="bi bi-download me-2"></i>

        Télécharger mon QR Code

    </a>

</div>

        </div>

        <!-- ================= POINTAGE ================= -->

        <div id="pointageArea" class="bg-white rounded-3xl shadow-lg p-6 flex flex-col h-full border border-slate-200">

            <div class="text-center">

                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto">

                    <i class="bi bi-qr-code-scan text-3xl text-blue-900"></i>

                </div>

                <h2 class="text-2xl font-bold mt-4">
                    Pointage
                </h2>

                <p class="text-gray-500 mt-2">
                    Sélectionnez le type de pointage à effectuer.
                </p>

            </div>


            <!-- Résumé -->

            <div class="mt-6 bg-slate-50 rounded-3xl p-5 border border-slate-200">

                <div class="mt-10 space-y-6">
                    <div id="notification" class="hidden mb-6 rounded-xl p-4 text-center font-semibold"></div>

                    <div id="reader" class="w-full h-[450px] border-4 border-[#1E4F86] rounded-2xl overflow-hidden"></div>

                    <p id="result" class="mt-5 text-center font-semibold text-lg"></p>

                    <div class="flex justify-center gap-4 mt-6">
                        <button id="btnStart" class="bg-[#1E4F86] hover:bg-[#163C68] text-white px-6 py-3 rounded-xl flex items-center gap-2 transition">
                            <i class="bi bi-camera-fill"></i>
                            Activer
                        </button>

                        <button id="btnStop" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition">
                            <i class="bi bi-x-circle-fill"></i>
                            Arrêter
                        </button>
                    </div>

                </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Règles de pointage
    <section class="max-w-3xl mx-auto mt-8 bg-white rounded-3xl shadow-lg border border-slate-200 p-6 md:p-8 text-slate-800">
        <h2 class="flex items-center gap-3 text-2xl font-bold text-slate-900">
            <span class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
            </span>
            Règles de pointage à prendre en compte
        </h2>

        <p class="mt-5 text-lg">Un étudiant <strong>ne peut pas effectuer un pointage</strong> dans les cas suivants :</p>

        <ol class="mt-4 list-decimal list-outside space-y-2 pl-6 leading-relaxed">
            <li><strong>Le jour est un jour férié.</strong></li>
            <li><strong>Le jour est un dimanche.</strong></li>
            <li><strong>Le jour ne figure pas dans son emploi du temps.</strong></li>
            <li>
                <strong>Le pointage est effectué plus de 30 minutes avant le début de son cours.</strong>
                <ul class="mt-1 list-disc pl-6">
                    <li><strong>Exemple :</strong> si Sidy a cours de <strong>12h00 à 18h00</strong>, il ne peut pas pointer avant <strong>11h30</strong>. Le pointage est autorisé <strong>à partir de 11h30</strong>.</li>
                </ul>
            </li>
            <li><strong>L'étudiant se trouve en dehors du périmètre géographique autorisé</strong> (ex. : le site Telly Tech).</li>
        </ol>

        <h3 class="mt-8 text-xl font-bold text-slate-900">Condition d'autorisation</h3>
        <p class="mt-4 text-lg leading-relaxed">Le pointage est <strong>autorisé uniquement si toutes les conditions suivantes sont réunies :</strong></p>

        <ul class="mt-4 list-disc list-outside space-y-2 pl-6 leading-relaxed">
            <li>Le jour n'est <strong>ni un dimanche ni un jour férié.</strong></li>
            <li>Le jour correspond à <strong>un jour prévu dans l'emploi du temps de l'étudiant.</strong></li>
            <li>L'heure actuelle est <strong>supérieure ou égale à 30 minutes avant le début du cours.</strong></li>
            <li>L'étudiant est <strong>à l'intérieur du périmètre géographique autorisé (Telly Tech).</strong></li>
        </ul>
    </section> -->

</main>
<script type="text/plain" id="old-qr-script">
window.addEventListener("load", function(){
    let html5QrCode = new Html5Qrcode("reader");
    let dernierQr = "";

    document.getElementById("btnStart").addEventListener("click", function(){
        Html5Qrcode.getCameras().then(cameras => {
            if(cameras.length === 0){
                alert("Aucune caméra détectée");
                return;
            }

            html5QrCode.start(
                cameras[0].id,
                { fps:10, qrbox:250 },
                async function(decodedText){
                    if(decodedText === dernierQr){
                        return;
                    }
                    dernierQr = decodedText;
                    try{
                        let response = await fetch(
                            "/COUR-TELLY-TECH/pointagepro/public/index.php?page=scan_admin_qr",
                            {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: "qr_code=" + encodeURIComponent(decodedText)
                            }
                        );

                        let text = await response.text();
                        let data;
                        try{
                            data = JSON.parse(text);
                        } catch(e){
                            console.log(text);
                            document.getElementById("result").innerHTML = `<span class="text-red-600">Erreur serveur</span>`;
                            return;
                        }

                        if(!data.success){
                            document.getElementById("result").innerHTML = `<span class="text-red-600">${data.message}</span>`;
                            return;
                        }

                        document.getElementById("result").innerHTML = `
                            <span class="text-green-700">
                                <i class="bi bi-check-circle-fill"></i>
                                ${data.etudiant.firstname} ${data.etudiant.lastname} Présent
                            </span>
                        `;

                    } catch(error){
                        console.error(error);
                    }
                },
                function(error){
                    // ignore scan errors
                }
            ).catch(err => {
                console.error(err);
            });
        });
    });

    document.getElementById("btnStop").addEventListener("click", function(){
        html5QrCode.stop().then(()=>{
            document.getElementById("result").innerHTML = `<span class="text-gray-500">Scanner arrêté</span>`;
        }).catch(err=>{
            console.log(err);
        });
    });
});
</script>
<script>
window.addEventListener("load", function(){
    const result = document.getElementById("result");
    const btnStart = document.getElementById("btnStart");
    const btnStop = document.getElementById("btnStop");
    let html5QrCode = null;
    let dernierQr = "";
    let scannerActif = false;

    function afficherMessage(message, classe = "text-red-600") {
        result.innerHTML = `<span class="${classe}">${message}</span>`;
    }

    function cameraSupportee() {
        return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
    }

    btnStart.addEventListener("click", async function(){
        if (scannerActif) {
            afficherMessage("Scanner deja actif", "text-blue-700");
            return;
        }

        if (typeof Html5Qrcode === "undefined") {
            afficherMessage("La librairie QR Code n'a pas ete chargee. Verifiez votre connexion internet.");
            return;
        }

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        if (!cameraSupportee()) {
            afficherMessage("Camera indisponible. Ouvrez la page avec localhost ou HTTPS, puis autorisez la camera.");
            return;
        }

        try {
            btnStart.disabled = true;
            afficherMessage("Ouverture de la camera...", "text-blue-700");

            const cameras = await Html5Qrcode.getCameras();
            if (!cameras || cameras.length === 0) {
                afficherMessage("Aucune camera detectee. Verifiez les autorisations du navigateur.");
                btnStart.disabled = false;
                return;
            }

            const backCamera = cameras.find(camera =>
                /back|rear|environment|arriere/i.test(camera.label)
            );
            const cameraId = (backCamera || cameras[0]).id;

            await html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                },
                async function(decodedText){
                    if (decodedText === dernierQr) {
                        return;
                    }

                    dernierQr = decodedText;

                    try {
                        const response = await fetch(
                            "/COUR-TELLY-TECH/pointagepro/public/index.php?page=scan_admin_qr",
                            {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: "qr_code=" + encodeURIComponent(decodedText)
                            }
                        );

                        const text = await response.text();
                        let data;

                        try {
                            data = JSON.parse(text);
                        } catch(e) {
                            console.log(text);
                            afficherMessage("Erreur serveur");
                            return;
                        }

                        if (!data.success) {
                            afficherMessage(data.message);
                            return;
                        }

                        result.innerHTML = `
                            <span class="text-green-700">
                                <i class="bi bi-check-circle-fill"></i>
                                ${data.etudiant.firstname} ${data.etudiant.lastname} Present
                            </span>
                        `;
                    } catch(error) {
                        console.error(error);
                        afficherMessage("Impossible d'enregistrer le pointage");
                    }
                },
                function(){
                    // Les erreurs de lecture sont normales tant qu'aucun QR code n'est trouve.
                }
            );

            scannerActif = true;
            btnStart.disabled = false;
            afficherMessage("Camera active. Placez le QR Code dans le cadre.", "text-green-700");
        } catch (err) {
            console.error(err);
            btnStart.disabled = false;

            if (err && (err.name === "NotAllowedError" || String(err).includes("Permission"))) {
                afficherMessage("Permission camera refusee. Autorisez la camera dans votre navigateur.");
                return;
            }

            if (err && (err.name === "NotFoundError" || String(err).includes("Requested device not found"))) {
                afficherMessage("Aucune camera disponible sur cet appareil.");
                return;
            }

            afficherMessage("Impossible de demarrer la camera. Essayez avec localhost ou HTTPS.");
        }
    });

    btnStop.addEventListener("click", async function(){
        if (!scannerActif) {
            afficherMessage("Le scanner n'est pas actif", "text-gray-500");
            return;
        }

        try {
            await html5QrCode.stop();
            html5QrCode.clear();
            scannerActif = false;
            dernierQr = "";
            afficherMessage("Scanner arrete", "text-gray-500");
        } catch(err) {
            console.log(err);
            afficherMessage("Impossible d'arreter le scanner");
        }
    });
});
</script>
</body>
</html>
