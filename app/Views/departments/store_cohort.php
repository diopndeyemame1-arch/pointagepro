<?php
require_once '../../../config/database.php';



    $pdo->beginTransaction();

    $sql = "INSERT INTO cohorts (department_id, name, status)
            VALUES (:department_id, :name, :status)
            RETURNING id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':department_id' => $_POST['department_id'],
        ':name'          => $_POST['name'],
        ':status'        => $_POST['status']
    ]);

    $cohortId = $stmt->fetchColumn();

    if (!empty($_POST['schedule'])) {

        $sqlSchedule = "INSERT INTO cohort_schedules
                        (cohort_id, day, start_time, end_time)
                        VALUES
                        (:cohort_id, :day, :start_time, :end_time)";

        $stmtSchedule = $pdo->prepare($sqlSchedule);

        foreach ($_POST['schedule'] as $day => $time) {

            if (!empty($time['start']) && !empty($time['end'])) {
        
                $stmtSchedule->execute([
                    ':cohort_id'  => $cohortId,
                    ':day'        => $day,
                    ':start_time' => $time['start'],
                    ':end_time'   => $time['end']
                ]);
            }
        }
    }

    $pdo->commit();

    header("Location: /COUR-TELLY-TECH/pointagepro/public/index.php?page=departments");
exit;
    exit;

