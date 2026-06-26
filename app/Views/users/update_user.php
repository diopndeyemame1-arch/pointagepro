<?php

require_once __DIR__ . '/../../../config/database.php';

$id         = $_POST['id'];
$firstname  = $_POST['firstname'];
$lastname   = $_POST['lastname'];
$email      = $_POST['email'];
$phone      = $_POST['phone'];
$department = $_POST['department'];
$cohort     = $_POST['cohort'];
$role       = $_POST['role'];

$sql = "UPDATE users
        SET firstname=?,
            lastname=?,
            email=?,
            phone=?,
            department=?,
            cohort=?,
            role=?
        WHERE id=?";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $firstname,
    $lastname,
    $email,
    $phone,
    $department,
    $cohort,
    $role,
    $id
]);

header("Location: utilisateur.php");
exit;