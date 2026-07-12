<?php

require_once '../../../config/database.php';
require_once '../../../config/mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $firstname  = $_POST['firstname'] ;
    $lastname   = $_POST['lastname'] ;
    $email      = $_POST['email'] ;
    $phone      = $_POST['phone'] ;
    $department_id = $_POST['department_id'];
    $cohort_id = $_POST['cohort_id'];
    $role       = $_POST['role'] ;

  
    if (empty($firstname) || empty($lastname) || empty($email)) {
        die("Veuillez remplir tous les champs obligatoires.");
    }

  
    $token = bin2hex(random_bytes(32));

   
    $photoPath = null;

    if (!empty($_FILES['photo']['name'])) {

        $uploadDir = __DIR__ . '/../../../public/uploads/';

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


        $sql = "INSERT INTO users
        (
            firstname,
            lastname,
            email,
            phone,
            department_id,
            cohort_id,
            role,
            photo,
            activation_token,
            is_active
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $firstname,
            $lastname,
            $email,
            $phone,
            $department_id,
            $cohort_id,
            $role,
            $photoPath,
            $token,
            0 
        ]);

        
        sendActivationMail(
            $email,
            $firstname . " " . $lastname,
            $token
        );

       
        header("Location: index.php?page=users");
        exit;

    
}