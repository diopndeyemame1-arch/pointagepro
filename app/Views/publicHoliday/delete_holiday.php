<?php
require_once '../../../config/database.php';
require_once '../../Models/PublicHoliday.php';

$model = new PublicHoliday($pdo);

$id = $_GET['id'] ?? null;

if ($id) {
    $model->delete($id);
}

header("Location: publicHoliday.php");
exit;