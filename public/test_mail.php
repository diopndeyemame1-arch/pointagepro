<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail.php';

echo "<h2>Test d'envoi d'e-mail de diagnostic</h2>";

// On récupère l'email passé dans l'URL (?email=...) ou on utilise une adresse par défaut
$email_test = $_GET['email'] ?? 'diopndeyemame1@gmail.com';

echo "<p>Tentative d'envoi d'un e-mail de test vers : <strong>" . htmlspecialchars($email_test) . "</strong></p>";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 3; // Debugging détaillé
    $mail->Debugoutput = 'html';

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->isSMTP();
    $mail->Host = gethostbyname('smtp.gmail.com');
    $mail->SMTPAuth = true;
    $mail->Username = 'diopndeyemame1@gmail.com';
    $mail->Password = 'oojo gbdu juup dfsq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Timeout = 10;

    $mail->setFrom('diopndeyemame1@gmail.com', 'PointagePro');
    $mail->addAddress($email_test, 'Utilisateur Test');

    $mail->isHTML(true);
    $mail->Subject = "Diagnostic SMTP PointagePro";
    $mail->Body    = "Ceci est un e-mail de diagnostic envoyé par le script de test.";

    $mail->send();
    echo "<h3 style='color:green;'>✅ Succès ! L'e-mail a bien été envoyé et accepté par le serveur pour $email_test.</h3>";

} catch (Exception $e) {
    echo "<h3 style='color:red;'>❌ Échec de l'envoi de l'e-mail.</h3>";
    echo "<b>Erreur :</b> " . $e->getMessage() . "<br>";
    echo "<b>Infos PHPMailer :</b> " . $mail->ErrorInfo;
}
