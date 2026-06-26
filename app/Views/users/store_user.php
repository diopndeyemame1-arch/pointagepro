<?php
require_once __DIR__ . '/../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $cohort    = trim($_POST['cohort'] ?? '');
    $role      = trim($_POST['role'] ?? 'etudiant');
    $password  = $_POST['password'] ?? '';

    // Vérification des champs obligatoires
    if (
        empty($firstname) ||
        empty($lastname) ||
        empty($email) ||
        empty($password)
    ) {
        die("Veuillez remplir tous les champs obligatoires.");
    }

    // Hash du mot de passe
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Gestion de la photo
    $photoPath = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

        $uploadDir = __DIR__ . '/../../../public/uploads/';

        // Créer le dossier uploads s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

        $fileName = uniqid() . '.' . $extension;

        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            $photoPath = 'uploads/' . $fileName;
        }
    }

    try {

        $sql = "INSERT INTO users
        (
            firstname,
            lastname,
            email,
            phone,
            department,
            cohort,
            role,
            password_hash,
            photo
        )
        VALUES
        (
            ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $firstname,
            $lastname,
            $email,
            $phone,
            $department,
            $cohort,
            $role,
            $password_hash,
            $photoPath
        ]);

        header("Location: utilisateur.php");
        exit;

    } catch (PDOException $e) {

        die("Erreur : " . $e->getMessage());

    }
}
?>