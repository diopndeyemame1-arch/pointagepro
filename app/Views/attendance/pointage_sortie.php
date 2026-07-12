<?php
session_start();
require_once '../../../config/database.php';

$user_id = $_SESSION['user_id'];
$date = date('Y-m-d');
$time = date('H:i:s');

$stmt = $pdo->prepare("
    UPDATE attendances
    SET check_out = ?
    WHERE user_id = ? AND date = ?
");

$stmt->execute([$time, $user_id, $date]);

header("Location: presence_etu.php");
exit;