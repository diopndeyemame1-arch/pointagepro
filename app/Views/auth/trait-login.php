<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT * FROM users
        WHERE email = :email
    ");

    $stmt->execute([
        'email' => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Utilisateur inexistant
    if (!$user) {

        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        header("Location: login.php");
        exit();

    }
    if (!$user['is_active']) {
    $_SESSION['error'] = "Votre compte n'est pas encore activé. Vérifiez votre email.";
    header("Location: login.php");
    exit;
}

    // Vérification du mot de passe
    if (!password_verify($password, $user['password_hash'])) {

        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        header("Location: login.php");
        exit();

    }

    // Vérification du compte
    if (!$user['is_verified']) {

        $_SESSION['error'] =
        "Votre compte n'est pas encore activé. Consultez votre e-mail.";

        header("Location: login.php");
        exit();

    }

    // Connexion
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['name']    = $user['firstname'];

    // Redirection suivant le rôle
    switch ($user['role']) {

        case 'admin':
            header("Location: ../dashboard/admin.php");
            break;

        case 'manager':
            header("Location: ../dashboard/manager.php");
            break;

        default:
            header("Location: ../dashboard/etudiant.php");
            break;
    }

    exit();
}