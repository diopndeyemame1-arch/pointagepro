<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $holiday_name = trim($_POST['name']);
    $holiday_date = $_POST['holiday_date'];
    $holiday_type = $_POST['type'];

    // Déterminer le statut automatiquement
    $today = date('Y-m-d');

    if ($holiday_date < $today) {
        $status = 'passe';
    } else {
        $status = 'avenir';
    }

    $sql = "INSERT INTO public_holidays
            (holiday_name, holiday_date, holiday_type, status)
            VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $holiday_name,
        $holiday_date,
        $holiday_type,
        $status
    ]);

    header("Location: publicHoliday.php?success=1");
    exit();
}