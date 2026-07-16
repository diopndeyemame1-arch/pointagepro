<?php
echo "<h2>Test d'envoi d'e-mail via l'API Brevo</h2>";

$email_test = $_GET['email'] ?? 'diopndeyemame1@gmail.com';
$apiKey = $_GET['key'] ?? getenv('BREVO_API_KEY'); // Lit depuis l'URL ou la variable d'environnement

if (!$apiKey) {
    die("<h3 style='color:red;'>❌ Erreur : Clé API Brevo manquante. Ajoutez la clé dans l'URL : ?email=$email_test&key=xkeysib-...</h3>");
}

echo "<p>Tentative d'envoi vers : <strong>" . htmlspecialchars($email_test) . "</strong></p>";

$data = [
    'sender' => [
        'name' => 'PointagePro',
        'email' => 'modou.expert.it@gmail.com'
    ],
    'to' => [
        [
            'email' => $email_test,
            'name' => 'Destinataire Test'
        ]
    ],
    'subject' => 'Diagnostic API Brevo - PointagePro',
    'htmlContent' => '<h3>Test réussi !</h3><p>Ceci est un e-mail envoyé via l\'API HTTP Brevo depuis Render.</p>'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'api-key: ' . $apiKey,
    'content-type: application/json',
    'accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h3>Retour de l'API Brevo :</h3>";
echo "<b>Code HTTP :</b> " . $httpCode . "<br>";
echo "<b>Réponse :</b> <pre>" . htmlspecialchars($response) . "</pre>";

if ($httpCode >= 200 && $httpCode < 300) {
    echo "<h3 style='color:green;'>✅ Succès ! L'e-mail a bien été envoyé via l'API Brevo.</h3>";
} else {
    echo "<h3 style='color:red;'>❌ Échec de l'envoi de l'e-mail.</h3>";
}
