<?php

class Settings
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get()
    {
        $stmt = $this->pdo->query("SELECT * FROM settings LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        $sql = "INSERT INTO settings (id, school_lat, school_lng, radius, gps_enabled) 
                VALUES (1, ?, ?, ?, ?)
                ON CONFLICT (id) DO UPDATE SET
                    school_lat = EXCLUDED.school_lat,
                    school_lng = EXCLUDED.school_lng,
                    radius = EXCLUDED.radius,
                    gps_enabled = EXCLUDED.gps_enabled";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $data['lat']);
        $stmt->bindValue(2, $data['lng']);
        $stmt->bindValue(3, $data['radius'], PDO::PARAM_INT);
        $stmt->bindValue(4, !empty($data['gps']), PDO::PARAM_BOOL);

        return $stmt->execute();
    }
}