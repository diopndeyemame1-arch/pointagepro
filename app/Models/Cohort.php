<?php

class Cohort
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer une cohorte par son ID
    public function find($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM cohorts
            WHERE id = ?
        ");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Modifier une cohorte
    public function update($id, $name, $status)
    {
        $stmt = $this->pdo->prepare("
            UPDATE cohorts
            SET name = ?, status = ?
            WHERE id = ?
        ");

        return $stmt->execute([$name, $status, $id]);
    }

    // Supprimer les horaires d'une cohorte
    public function deleteSchedules($cohortId)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM cohort_schedules
            WHERE cohort_id = ?
        ");

        return $stmt->execute([$cohortId]);
    }

    // Ajouter un horaire
    public function addSchedule($cohortId, $day, $start, $end)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO cohort_schedules
            (cohort_id, day, start_time, end_time)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $cohortId,
            $day,
            $start,
            $end
        ]);
    }

    // Supprimer une cohorte
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM cohorts
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    // Récupérer toutes les cohortes d'un département
    public function getByDepartment($departmentId)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM cohorts
            WHERE department_id = ?
            ORDER BY name
        ");

        $stmt->execute([$departmentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}