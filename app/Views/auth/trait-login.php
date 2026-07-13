<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
        header("Location: index.php?page=login");
        exit();

    }
    if (!$user['is_active']) {
    $_SESSION['error'] = "Votre compte n'est pas encore activé. Vérifiez votre email.";
    header("Location: index.php?page=login");
    exit;
}

    // Vérification du mot de passe
    if (!password_verify($password, $user['password_hash'])) {

        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        header("Location: index.php?page=login");
        exit();

    }

    // Vérification du compte
    if (!$user['is_verified']) {

        $_SESSION['error'] =
        "Votre compte n'est pas encore activé. Consultez votre e-mail.";

        header("Location: index.php?page=login");
        exit();

    }

    // Connexion
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['name']    = $user['firstname'];
    
    
    $_SESSION['admin'] = [
    'id'    => $user['id'],
    'name'  => $user['firstname'] . ' ' . $user['lastname'],
    'email' => $user['email'],
    'phone' => $user['phone'],
    'photo' => $user['photo']
];

    // Redirection suivant le rôle
   switch ($user['role']) {

    case 'admin':
        header("Location: index.php?page=admin");
        break;

    case 'etudiant':
        header("Location: index.php?page=etudiant");
        break;

    default:
        header("Location: index.php?page=login");
        break;
}
exit();



}
