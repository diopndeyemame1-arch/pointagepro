<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['firstname'];

        if ($user['role'] == 'admin') {
            header("Location: ../dashboard/admin.php");
        }
        elseif ($user['role'] == 'manager') {
            header("Location: ../dashboard/manager.php");
        }
        else {
            header("Location: ../dashboard/etudiant.php");
        }

        exit;

    } else {
        $_SESSION['error'] = "Email ou Mot de passe incorrect";
        header("Location: login.php");
        exit();
    }
}