<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendActivationMail($email, $fullname, $token)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tonadresse@gmail.com';
        $mail->Password = 'TON_APP_PASSWORD';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->SMTPDebug = 0; // mets 2 pour debug

        $mail->setFrom('tonadresse@gmail.com', 'PointagePro');
        $mail->addAddress($email, $fullname);

        $link = "http://127.0.0.1/pointagepro/app/Views/users/activate.php?token=$token";

        $mail->isHTML(true);
        $mail->Subject = "Activation de votre compte";

        $mail->Body = "
            Bonjour <b>$fullname</b><br><br>

            Cliquez ici pour activer votre compte :<br><br>

            <a href='$link'>Activer mon compte</a>
        ";

        $mail->send();

    } catch (Exception $e) {

        echo "Erreur envoi mail : " . $mail->ErrorInfo;
    }
}