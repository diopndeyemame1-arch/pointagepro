<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendActivationMail($email, $fullname, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Disable verbose debug output in production
        $mail->SMTPDebug = 0;

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
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('diopndeyemame1@gmail.com', 'PointagePro');
        $mail->addAddress($email, $fullname);

        // Build a public activation link based on the current request environment.
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        // dirname of script name should point to /.../public when called through front controller
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '\/');
        // $link = $scheme . '://' . $host . $basePath . '/index.php?page=activate&token=' . urlencode($token);

        $mail->isHTML(true);
$mail->Subject = "Activation de votre compte PointagePro";

$link = "http://localhost/COUR-TELLY-TECH/pointagepro/public/index.php?page=activate&token=" . urlencode($token);

$mail->Body = '
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
</head>

<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:40px 0;">

<tr>
<td align="center">

<table width="650" cellpadding="0" cellspacing="0"
style="background:white;border-radius:20px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.08);">

<!-- HEADER -->

<tr>

<td style="background:#0f172a;padding:30px;">

<table width="100%">

<tr>

<td>

<h2 style="margin:0;color:white;font-size:30px;">
PointagePro
</h2>

<p style="margin:5px 0 0;color:#94a3b8;">
Gestion intelligente des présences
</p>

</td>

<td align="right">

<span style="
background:#b8863a;
padding:10px 18px;
border-radius:30px;
color:white;
font-weight:bold;
font-size:13px;
">
TELLYTECH
</span>

</td>

</tr>

</table>

</td>

</tr>

<!-- BODY -->

<tr>

<td style="padding:45px;">

<h1 style="
margin-top:0;
font-size:30px;
color:#111827;
">

Bonjour '.$fullname.' 👋

</h1>

<p style="
font-size:17px;
color:#555;
line-height:30px;
">

Votre compte <strong>PointagePro</strong> vient d\être créé.

Pour accéder à votre espace personnel,
vous devez d\abord activer votre compte.

</p>

<!-- Carte -->

<table
width="100%"
cellpadding="15"
style="
margin-top:30px;
background:#f8fafc;
border:1px solid #e5e7eb;
border-radius:15px;
">

<tr>

<td style="color:#666;">
Nom
</td>

<td align="right">
<strong>'.$fullname.'</strong>
</td>

</tr>

<tr>

<td style="border-top:1px dashed #ddd;color:#666;">
Email
</td>

<td align="right" style="border-top:1px dashed #ddd;">
'.$email.'
</td>

</tr>

</table>

<!-- Bouton -->

<table width="100%" style="margin-top:40px;">

<tr>

<td align="center">

<a href="'.$link.'"

style="
display:inline-block;
background:#15803d;
color:white;
padding:18px 45px;
border-radius:12px;
text-decoration:none;
font-weight:bold;
font-size:16px;
">

Activer mon compte

</a>

</td>

</tr>

</table>

<p style="
margin-top:35px;
font-size:14px;
color:#666;
">

Ce lien est valable pendant
<strong>48 heures</strong>.

</p>

<hr style="margin:40px 0;border:none;border-top:1px solid #e5e7eb;">

<p style="
font-size:13px;
color:#999;
">

Si le bouton ne fonctionne pas,
copiez ce lien dans votre navigateur :

</p>

<p>

<a href="'.$link.'"
style="
color:#15803d;
word-break:break-all;
">

'.$link.'

</a>

</p>

</td>

</tr>

<!-- FOOTER -->

<tr>

<td
align="center"
style="
background:#f8fafc;
padding:25px;
font-size:12px;
color:#999;
">

© '.date('Y').' PointagePro - TELLYTECH

</td>

</tr>

</table>

</td>

</tr>

</table>

</body>

</html>
';
$mail->AltBody =
"Bonjour $fullname,

Votre compte PointagePro a été créé.

Activez votre compte ici :
$link";
        $mail->send();

        return true;

    } catch (Exception $e) {
        // In production, don't echo. Return false so caller can log/handle it.
        return false;
    }
}

function sendPasswordResetMail($email, $fullname, $token)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
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
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('diopndeyemame1@gmail.com', 'PointagePro');
        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = "Réinitialisation de votre mot de passe PointagePro";

        $link = "http://localhost/COUR-TELLY-TECH/pointagepro/public/index.php?page=reset-password&token=" . urlencode($token);

        $mail->Body = '
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:40px 0;">
<tr>
<td align="center">
<table width="650" cellpadding="0" cellspacing="0" style="background:white;border-radius:20px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.08);">
<tr>
<td style="background:#0f172a;padding:30px;">
<table width="100%">
<tr>
<td>
<h2 style="margin:0;color:white;font-size:30px;">PointagePro</h2>
<p style="margin:5px 0 0;color:#94a3b8;">Réinitialisation de mot de passe</p>
</td>
<td align="right">
<span style="background:#1d4ed8;padding:10px 18px;border-radius:30px;color:white;font-weight:bold;font-size:13px;">TELLYTECH</span>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding:45px;">
<h1 style="margin-top:0;font-size:30px;color:#111827;">Bonjour '.$fullname.' 👋</h1>
<p style="font-size:17px;color:#555;line-height:30px;">Vous avez demandé à réinitialiser votre mot de passe PointagePro. Cliquez sur le bouton ci-dessous pour définir un nouveau mot de passe.</p>
<table width="100%" style="margin-top:40px;"><tr><td align="center"><a href="'.$link.'" style="display:inline-block;background:#2563eb;color:white;padding:18px 45px;border-radius:12px;text-decoration:none;font-weight:bold;font-size:16px;">Réinitialiser mon mot de passe</a></td></tr></table>
<p style="margin-top:35px;font-size:14px;color:#666;">Ce lien est valable pendant 48 heures.</p>
<hr style="margin:40px 0;border:none;border-top:1px solid #e5e7eb;">
<p style="font-size:13px;color:#999;">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
<p><a href="'.$link.'" style="color:#2563eb;word-break:break-all;">'.$link.'</a></p>
</td>
</tr>
<tr>
<td align="center" style="background:#f8fafc;padding:25px;font-size:12px;color:#999;">© '.date('Y').' PointagePro - TELLYTECH</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
';

        $mail->AltBody = "Bonjour $fullname,\n\nVous avez demandé une réinitialisation de mot de passe PointagePro.\n\nUtilisez le lien suivant : $link";
        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}

function sendAbsenceNotificationMail($email, $fullname, $subject, $title, $message, array $absence = [])
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'diopndeyemame1@gmail.com';
        $mail->Password = 'oojo gbdu juup dfsq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('diopndeyemame1@gmail.com', 'PointagePro');
        $mail->addAddress($email, $fullname);
        $mail->isHTML(true);
        $mail->Subject = $subject;

        $safeName = htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8');
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        $details = '';

        if ($absence) {
            $type = htmlspecialchars($absence['type'] ?? '', ENT_QUOTES, 'UTF-8');
            $start = htmlspecialchars($absence['start_date'] ?? '', ENT_QUOTES, 'UTF-8');
            $end = htmlspecialchars($absence['end_date'] ?? '', ENT_QUOTES, 'UTF-8');
            $details = "<p><strong>Type :</strong> {$type}<br><strong>Du :</strong> {$start}<br><strong>Au :</strong> {$end}</p>";
        }

        $mail->Body = "<div style=\"font-family:Arial,sans-serif;color:#1e293b;max-width:600px;margin:auto\"><h2 style=\"color:#1e4f86\">{$safeTitle}</h2><p>Bonjour {$safeName},</p><p>{$safeMessage}</p>{$details}<p>Cordialement,<br>PointagePro</p></div>";
        $mail->AltBody = "Bonjour {$fullname},\n\n{$message}";
        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}
