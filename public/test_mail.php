<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail.php';

echo "<h2>Test d'envoi d'e-mail (SMTP)</h2>";

$email_test = 'diopndeyemame1@gmail.com'; // Vous pouvez changer cette adresse pour tester

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
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'diopndeyemame1@gmail.com';
    $mail->Password = 'oojo gbdu juup dfsq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Utiliser SSL au lieu de TLS
    $mail->Port = 465; // Port 465
    $mail->Timeout = 10; // Timeout de 10 secondes maximum

    $mail->setFrom('diopndeyemame1@gmail.com', 'PointagePro');
    $mail->addAddress($email_test, 'Test User');

    $mail->isHTML(true);
    $mail->Subject = "Test SMTP PointagePro";
    $mail->Body    = "Ceci est un e-mail de test de diagnostic.";

    $mail->send();
    echo "<h3 style='color:green;'>✅ Succès ! L'e-mail a été envoyé à $email_test.</h3>";

} catch (Exception $e) {
    echo "<h3 style='color:red;'>❌ Échec de l'envoi de l'e-mail.</h3>";
    echo "<b>Erreur :</b> " . $e->getMessage() . "<br>";
    echo "<b>Infos PHPMailer :</b> " . $mail->ErrorInfo;
}
