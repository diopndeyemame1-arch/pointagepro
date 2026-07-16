<?php

// Fonction d'envoi d'e-mail via l'API HTTP Brevo (port 443) pour contourner les restrictions SMTP de Render
function sendBrevoEmail($toEmail, $toName, $subject, $htmlContent, $altText = '') {
    $apiKey = getenv('BREVO_API_KEY');
    
    if (!$apiKey) {
        error_log("Brevo API Key manquante dans l'environnement. Veuillez définir BREVO_API_KEY sur Render.");
        return false;
    }
    
    $data = [
        'sender' => [
            'name' => 'PointagePro',
            'email' => 'modou.expert.it@gmail.com' // Expéditeur vérifié
        ],
        'to' => [
            [
                'email' => $toEmail,
                'name' => $toName
            ]
        ],
        'subject' => $subject,
        'htmlContent' => $htmlContent
    ];
    
    if (!empty($altText)) {
        $data['textContent'] = $altText;
    }
    
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
    
    if ($httpCode >= 200 && $httpCode < 300) {
        return true;
    } else {
        error_log("Brevo API Mail Error: Code $httpCode | Response: $response");
        return false;
    }
}

function sendActivationMail($email, $fullname, $token)
{
    $baseUrl = rtrim(getenv('APP_URL') ?: ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'), '/');
    $link = $baseUrl . '/index.php?page=activate&token=' . urlencode($token);

    $subject = "Activation de votre compte PointagePro";
    
    $body = '
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
<p style="margin:5px 0 0;color:#94a3b8;">Gestion intelligente des présences</p>
</td>
<td align="right">
<span style="background:#b8863a;padding:10px 18px;border-radius:30px;color:white;font-weight:bold;font-size:13px;">TELLYTECH</span>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding:45px;">
<h1 style="margin-top:0;font-size:30px;color:#111827;">Bonjour '.$fullname.' 👋</h1>
<p style="font-size:17px;color:#555;line-height:30px;">Votre compte <strong>PointagePro</strong> vient d\'être créé. Pour accéder à votre espace personnel, vous devez d\'abord activer votre compte.</p>
<table width="100%" cellpadding="15" style="margin-top:30px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:15px;">
<tr>
<td style="color:#666;">Nom</td>
<td align="right"><strong>'.$fullname.'</strong></td>
</tr>
<tr>
<td style="border-top:1px dashed #ddd;color:#666;">Email</td>
<td align="right" style="border-top:1px dashed #ddd;">'.$email.'</td>
</tr>
</table>
<table width="100%" style="margin-top:40px;">
<tr>
<td align="center">
<a href="'.$link.'" style="display:inline-block;background:#15803d;color:white;padding:18px 45px;border-radius:12px;text-decoration:none;font-weight:bold;font-size:16px;">Activer mon compte</a>
</td>
</tr>
</table>
<p style="margin-top:35px;font-size:14px;color:#666;">Ce lien est valable pendant <strong>48 heures</strong>.</p>
<hr style="margin:40px 0;border:none;border-top:1px solid #e5e7eb;">
<p style="font-size:13px;color:#999;">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
<p><a href="'.$link.'" style="color:#15803d;word-break:break-all;">'.$link.'</a></p>
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

    $altBody = "Bonjour $fullname,\n\nVotre compte PointagePro a été créé.\n\nActivez votre compte ici :\n$link";
    
    return sendBrevoEmail($email, $fullname, $subject, $body, $altBody);
}

function sendPasswordResetMail($email, $fullname, $token)
{
    $baseUrl = rtrim(getenv('APP_URL') ?: ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'), '/');
    $link = $baseUrl . '/index.php?page=reset-password&token=' . urlencode($token);

    $subject = "Réinitialisation de votre mot de passe PointagePro";
    
    $body = '
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

    $altBody = "Bonjour $fullname,\n\nVous avez demandé une réinitialisation de mot de passe PointagePro.\n\nUtilisez le lien suivant : $link";
    
    return sendBrevoEmail($email, $fullname, $subject, $body, $altBody);
}

function sendAbsenceNotificationMail($email, $fullname, $subject, $title, $message, array $absence = [])
{
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

    $body = "<div style=\"font-family:Arial,sans-serif;color:#1e293b;max-width:600px;margin:auto\"><h2 style=\"color:#1e4f86\">{$safeTitle}</h2><p>Bonjour {$safeName},</p><p>{$safeMessage}</p>{$details}<p>Cordialement,<br>PointagePro</p></div>";
    $altBody = "Bonjour {$fullname},\n\n{$message}";
    
    return sendBrevoEmail($email, $fullname, $subject, $body, $altBody);
}
