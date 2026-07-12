<?php

require_once __DIR__ . '/../../../config/database.php';

$id         = $_POST['id'];
$firstname  = $_POST['firstname'];
$lastname   = $_POST['lastname'];
$email      = $_POST['email'];
$phone      = $_POST['phone'];
$department_id = $_POST['department_id'];
$cohort_id = $_POST['cohort_id'];
$role       = $_POST['role'];

$sql = "UPDATE users
        SET firstname=?,
            lastname=?,
            email=?,
            phone=?,
            department_id = ?,
            cohort_id = ?,
            role=?
        WHERE id=?";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $firstname,
    $lastname,
    $email,
    $phone,
    $department_id,
    $cohort_id,
    $role,
    $id
]);

header("Location: index.php?page=users");
exit;