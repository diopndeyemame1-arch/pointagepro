<?php

require_once __DIR__ . '/../../../config/database.php';

$id = $_GET['id'] ?? null;

if ($id) {

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

}

header("Location: utilisateur.php");
exit;