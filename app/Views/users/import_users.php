<?php

require_once __DIR__ . '/../../../config/database.php';

if (isset($_FILES['csv_file'])) {

    $file = fopen($_FILES['csv_file']['tmp_name'], 'r');

    // Ignorer l'entête
    fgetcsv($file, 1000, ";");

    while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {

        if (count($row) < 8) {
            continue;
        }

        $firstname  = $row[0] ;
        $lastname   = $row[1] ;
        $email      = $row[2] ;
        $phone      = $row[3] ;
        $department = $row[4] ;
        $cohort     = $row[5] ;
        $role       = trim($row[6] ?? 'etudiant');
        $password   = trim($row[7] ?? '12345678');
        $photo   = trim($row[8] ?? '');
        

        if (empty($firstname) || empty($lastname) || empty($email)) {
            continue;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users
            (firstname, lastname, email, phone,photo, department, cohort, role, password_hash)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)
        ");

        $stmt->execute([
            $firstname,
            $lastname,
            $email,
            $phone,
            $photo,
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