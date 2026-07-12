<?php
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $lat = $_POST['schoolLat'];
    $lng = $_POST['schoolLng'];
    $radius = $_POST['radius'];

    $stmt = $pdo->prepare("
        INSERT INTO school_settings (id, school_lat, school_lng, radius, gps_enabled)
        VALUES (gen_random_uuid(), ?, ?, ?, true)
        ON CONFLICT (id)
        DO UPDATE SET
            school_lat = EXCLUDED.school_lat,
            school_lng = EXCLUDED.school_lng,
            radius = EXCLUDED.radius
    ");

    $stmt->execute([$lat, $lng, $radius]);

    header("Location: parametre.php?success=1");
    exit;
}