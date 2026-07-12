<?php
require_once __DIR__ . '/../../../config/database.php';

try {

    $pdo->beginTransaction();

    $id = $_POST['id'];
    $name = $_POST['name'];
    $status = $_POST['status'];

    /* 1. UPDATE COHORT (SANS HEURES) */
    $stmt = $pdo->prepare("
        UPDATE cohorts
        SET name = ?,
            status = ?
        WHERE id = ?
    ");

    $stmt->execute([$name, $status, $id]);

    $pdo->prepare("DELETE FROM cohort_schedules WHERE cohort_id = ?")
        ->execute([$id]);

    if (!empty($_POST['schedules'])) {

        $stmtSchedule = $pdo->prepare("
            INSERT INTO cohort_schedules (cohort_id, day, start_time, end_time)
            VALUES (?, ?, ?, ?)
        ");

        foreach ($_POST['schedules'] as $schedule) {

            if (!empty($schedule['start']) && !empty($schedule['end'])) {

                $stmtSchedule->execute([
                    $id,
                    $schedule['day'],
                    $schedule['start'],
                    $schedule['end']
                ]);
            }
        }
    }

    $pdo->commit();

    $stmt = $pdo->prepare("SELECT department_id FROM cohorts WHERE id = ?");
    $stmt->execute([$id]);
    $dept = $stmt->fetch(PDO::FETCH_ASSOC);

    header("Location: department_cohorts.php?id=" . $dept['department_id']);
    exit;

} catch (Exception $e) {

    $pdo->rollBack();
    die("Erreur : " . $e->getMessage());
}
?>