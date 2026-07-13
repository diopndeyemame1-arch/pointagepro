<?php
if(session_status() === PHP_SESSION_NONE){
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=mot-de-passe-oublie');
    exit;
}

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    $_SESSION['error'] = 'Veuillez saisir votre adresse e-mail.';
    header('Location: index.php?page=mot-de-passe-oublie');
    exit;
}

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/mail.php';

$stmt = $pdo->prepare('SELECT id, firstname, lastname FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Ne pas révéler si l'email existe ou non
    $_SESSION['message'] = 'Si ce compte existe, un lien de réinitialisation a été envoyé à votre adresse e-mail.';
    header('Location: index.php?page=mot-de-passe-oublie');
    exit;
}

$token = bin2hex(random_bytes(32));

$stmt = $pdo->prepare('UPDATE users SET activation_token = ? WHERE id = ?');
$stmt->execute([$token, $user['id']]);

$fullname = $user['firstname'] . ' ' . $user['lastname'];

sendPasswordResetMail($email, $fullname, $token);

$_SESSION['message'] = 'Si ce compte existe, un lien de réinitialisation a été envoyé à votre adresse e-mail.';
header('Location: index.php?page=mot-de-passe-oublie');
exit;
