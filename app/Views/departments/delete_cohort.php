<?php
require_once __DIR__.'/../../../config/database.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT department_id FROM cohorts WHERE id=?");
$stmt->execute([$id]);

$cohort = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$cohort){
    die("Cohorte introuvable");
}

$department = $cohort['department_id'];

$stmt = $pdo->prepare("DELETE FROM cohorts WHERE id=?");
$stmt->execute([$id]);

header("Location: department_cohorts.php?id=".$department);
exit;