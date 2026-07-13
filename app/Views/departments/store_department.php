<?php
require_once "../../../config/database.php";

$name = trim($_POST['name']);
$description = trim($_POST['description']);

$sql = "INSERT INTO departments(name, description)
        VALUES(:name, :description)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':name' => $name,
    ':description' => $description
]);
    header("Location: /index.php?page=departments");

// header("Location: department.php");
exit;