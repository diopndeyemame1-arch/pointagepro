<?php
require_once '../../../config/database.php';

$id = $_POST['id'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$department = $_POST['department'];
$poste = $_POST['poste'];

$sql = "UPDATE users SET 
        fullname = ?, 
        email = ?, 
        phone = ?, 
        department = ?, 
        poste = ?
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$fullname, $email, $phone, $department, $poste, $id]);

header("Location: parametre.php?success=1");
exit;
?>