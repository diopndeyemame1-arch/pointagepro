<?php

require_once __DIR__ . '/../../../config/database.php';

if (isset($_FILES['csv_file'])) {

    $file = fopen($_FILES['csv_file']['tmp_name'], 'r');

    // Ignorer l'entête
    fgetcsv($file);

    while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {

        $firstname = $row[0];
        $lastname  = $row[1];
        $email     = $row[2];
        $phone     = $row[3];
        $department= $row[4];
        $cohort    = $row[5];
        $role      = $row[6];
        $password  = $row[7];

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users
            (
                firstname,
                lastname,
                email,
                phone,
                department,
                cohort,
                role,
                password_hash
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $firstname,
            $lastname,
            $email,
            $phone,
            $department,
            $cohort,
            $role,
            $password_hash
        ]);
    }

    fclose($file);
}

header("Location: utilisateur.php");
exit;