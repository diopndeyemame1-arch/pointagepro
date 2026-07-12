<?php

class Schedule
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get()
    {
        $stmt = $this->pdo->query("SELECT * FROM admin_schedules LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        $sql = "UPDATE admin_schedules SET
            mon_start=?, mon_end=?,
            tue_start=?, tue_end=?,
            wed_start=?, wed_end=?,
            thu_start=?, thu_end=?,
            fri_start=?, fri_end=?
            WHERE id = 1";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $data['mon_start'], $data['mon_end'],
            $data['tue_start'], $data['tue_end'],
            $data['wed_start'], $data['wed_end'],
            $data['thu_start'], $data['thu_end'],
            $data['fri_start'], $data['fri_end']
        ]);
    }
}