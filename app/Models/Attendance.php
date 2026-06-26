<?php

class Attendance
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🔹 Liste des présences avec infos user
    public function getAll()
    {
        $sql = "
            SELECT a.*, u.firstname, u.lastname, u.email, u.photo, u.department, u.cohort
            FROM attendances a
            JOIN users u ON u.id = a.user_id
            ORDER BY a.date DESC
        ";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Présence d’un utilisateur
    public function getByUser($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM attendances
            WHERE user_id = ?
            ORDER BY date DESC
        ");

        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Check-in (arrivée)
    public function checkIn($user_id, $status = 'present')
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO attendances (user_id, date, check_in, status)
            VALUES (?, CURRENT_DATE, CURRENT_TIME, ?)
            ON CONFLICT (user_id, date)
            DO UPDATE SET
                check_in = EXCLUDED.check_in,
                status = EXCLUDED.status
        ");

        return $stmt->execute([$user_id, $status]);
    }

    // 🔹 Check-out (départ)
    public function checkOut($user_id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE attendances
            SET check_out = CURRENT_TIME
            WHERE user_id = ?
            AND date = CURRENT_DATE
        ");

        return $stmt->execute([$user_id]);
    }

    // 🔹 Statistiques
    public function stats()
    {
        $present = $this->pdo->query("SELECT COUNT(*) FROM attendances WHERE status='present'")->fetchColumn();
        $retard  = $this->pdo->query("SELECT COUNT(*) FROM attendances WHERE status='retard'")->fetchColumn();
        $absent  = $this->pdo->query("SELECT COUNT(*) FROM attendances WHERE status='absent'")->fetchColumn();

        return compact('present', 'retard', 'absent');
    }
}