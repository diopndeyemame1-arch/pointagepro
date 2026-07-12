<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/mail.php';

if (isset($_FILES['csv_file']) && is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
    $tmp = $_FILES['csv_file']['tmp_name'];
    $file = fopen($tmp, 'r');

    if ($file) {
        $first = fgetcsv($file, 1000, ';');
        $delimiter = ';';

        if ($first === false) {
            fclose($file);
            header("Location: utilisateur.php?error=empty_file");
            exit;
        }

        if (count($first) <= 1) {
            rewind($file);
            $delimiter = ',';
            $first = fgetcsv($file, 1000, $delimiter);
        }

        $first0 = strtolower(trim($first[0] ?? ''));
        $hasHeader = in_array($first0, ['firstname', 'prenom', 'prénom', 'first name', 'nom', 'name']);
        if (!$hasHeader) {
            rewind($file);
        }

        while (($row = fgetcsv($file, 1000, $delimiter)) !== FALSE) {
            if (count($row) < 3) {
                continue;
            }

            $firstname = trim($row[0] ?? '');
            $lastname  = trim($row[1] ?? '');
            $email     = trim($row[2] ?? '');

            if ($firstname === '' || $lastname === '' || $email === '') {
                continue;
            }

            $check = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $check->execute([$email]);
            if ($check->fetch()) {
                continue;
            }

            $phone = trim($row[3] ?? null);
            $department = trim($row[4] ?? '');
            $cohort = trim($row[5] ?? '');
            $role = strtolower(trim($row[6] ?? 'etudiant'));
            $password = trim($row[7] ?? '');
            $photo = trim($row[8] ?? null);

            $allowedRoles = ['admin', 'etudiant'];
            if (!in_array($role, $allowedRoles)) {
                $role = 'etudiant';
            }

            $department_id = null;
            if ($department !== '') {
                if (is_numeric($department)) {
                    $department_id = (int)$department;
                } else {
                    $stmt = $pdo->prepare("SELECT id FROM departments WHERE name = ? LIMIT 1");
                    $stmt->execute([$department]);
                    $dep = $stmt->fetch(PDO::FETCH_ASSOC);
                    $department_id = $dep['id'] ?? null;
                }
            }

            $cohort_id = null;
            if ($cohort !== '') {
                if (is_numeric($cohort)) {
                    $cohort_id = (int)$cohort;
                } else {
                    $stmt = $pdo->prepare("SELECT id FROM cohorts WHERE name = ? LIMIT 1");
                    $stmt->execute([$cohort]);
                    $co = $stmt->fetch(PDO::FETCH_ASSOC);
                    $cohort_id = $co['id'] ?? null;
                }
            }

            if ($role === 'etudiant') {
                $activation_token = bin2hex(random_bytes(32));
                $is_active = 0;
                $password_hash = null;
            } else {
                $activation_token = null;
                $is_active = 1;
                $password_hash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
            }

            $insert = $pdo->prepare(
                "INSERT INTO users
                 (firstname, lastname, email, phone, photo, department_id, cohort_id, role, password_hash, activation_token, is_active)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $insert->execute([
                $firstname,
                $lastname,
                $email,
                $phone,
                $photo,
                $department_id,
                $cohort_id,
                $role,
                $password_hash,
                $activation_token,
                (int)$is_active
            ]);

            if ($role === 'etudiant' && $activation_token) {
                sendActivationMail($email, $firstname . ' ' . $lastname, $activation_token);
            }
        }

        fclose($file);
    }
}

header("Location: utilisateur.php");
exit;