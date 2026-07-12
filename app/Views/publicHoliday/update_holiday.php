<?php
require_once '../../../config/database.php';
require_once '../../Models/PublicHoliday.php';

$model = new PublicHoliday($pdo);

$id = $_POST['id'];
$name = $_POST['name'];
$date = $_POST['holiday_date'];
$type = $_POST['type'];
$description = $_POST['description'];

$model->update($id, $name, $date, $type, $description);

header("Location: publicHoliday.php");
exit;