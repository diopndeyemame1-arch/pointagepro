<?php

class CohortController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

   public function update()
{
    try {

        $this->pdo->beginTransaction();

        $id = $_POST['id'];
        $name = $_POST['name'];
        $status = $_POST['status'];

        // Modifier la cohorte
        $stmt = $this->pdo->prepare("
            UPDATE cohorts
            SET name = ?, status = ?
            WHERE id = ?
        ");

        $stmt->execute([$name, $status, $id]);

        // Supprimer les anciens horaires
        $stmt = $this->pdo->prepare("
            DELETE FROM cohort_schedules
            WHERE cohort_id = ?
        ");

        $stmt->execute([$id]);

        // Réinsérer les nouveaux horaires
        if (!empty($_POST['schedules'])) {

            $stmt = $this->pdo->prepare("
                INSERT INTO cohort_schedules
                (cohort_id, day, start_time, end_time)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($_POST['schedules'] as $schedule) {

                if (!empty($schedule['start']) && !empty($schedule['end'])) {

                    $stmt->execute([
                        $id,
                        $schedule['day'],
                        $schedule['start'],
                        $schedule['end']
                    ]);
                }
            }
        }

        $this->pdo->commit();

        // Retour au département
        $stmt = $this->pdo->prepare("
            SELECT department_id
            FROM cohorts
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $cohort = $stmt->fetch(PDO::FETCH_ASSOC);

        header("Location: index.php?page=department_cohorts&id=".$cohort['department_id']);
        exit;

    } catch(Exception $e){

        $this->pdo->rollBack();

        die($e->getMessage());

    }
}
    public function delete()
{
    $id = $_GET['id'] ?? 0;

    $stmt = $this->pdo->prepare("SELECT department_id FROM cohorts WHERE id = ?");
    $stmt->execute([$id]);
    $cohort = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cohort) {
        die("Cohorte introuvable");
    }

    $department = $cohort['department_id'];

    $stmt = $this->pdo->prepare("DELETE FROM cohorts WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php?page=department_cohorts&id=" . $department);
    exit;
}
public function store()
{
    $this->pdo->beginTransaction();

    $stmt = $this->pdo->prepare("
        INSERT INTO cohorts (department_id, name, status)
        VALUES (?, ?, ?)
        RETURNING id
    ");

    $stmt->execute([
        $_POST['department_id'],
        $_POST['name'],
        $_POST['status']
    ]);

    $cohortId = $stmt->fetchColumn();

    if (!empty($_POST['schedule'])) {

        $stmtSchedule = $this->pdo->prepare("
            INSERT INTO cohort_schedules
            (cohort_id, day, start_time, end_time)
            VALUES (?, ?, ?, ?)
        ");

        foreach ($_POST['schedule'] as $day => $time) {

            if (!empty($time['start']) && !empty($time['end'])) {

                $stmtSchedule->execute([
                    $cohortId,
                    $day,
                    $time['start'],
                    $time['end']
                ]);
            }
        }
    }

    $this->pdo->commit();

    header("Location: index.php?page=departments");
    exit;
}
}