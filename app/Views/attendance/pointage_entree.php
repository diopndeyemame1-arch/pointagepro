<?php
session_start();

require_once __DIR__ . '/../../../config/database.php';

$user_id = $_SESSION['user_id'];
$date = date('Y-m-d');
$time = date('H:i:s');

$stmt = $pdo->prepare("
    INSERT INTO attendances (user_id, date, check_in, status)
    VALUES (:user_id, :date, :check_in, 'present')
    ON CONFLICT (user_id, date)
    DO UPDATE SET
        check_in = EXCLUDED.check_in,
        status = 'present'
");

$stmt->execute([
    ':user_id' => $user_id,
    ':date' => $date,
    ':check_in' => $time
]);