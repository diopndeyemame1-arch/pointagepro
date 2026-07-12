<?php

class Department
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getDepartments()
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM departments
            ORDER BY name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCohorts()
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM cohorts
            ORDER BY name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDepartmentWithCohorts($id)
{
    $sql = "
        SELECT
            d.id AS department_id,
            d.name AS department_name,
            c.*
        FROM departments d
        LEFT JOIN cohorts c
            ON d.id = c.department_id
        WHERE d.id = ?
        ORDER BY c.name
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function create($name, $description)
{
    $stmt = $this->pdo->prepare("
        INSERT INTO departments(name, description)
        VALUES (?, ?)
    ");

    return $stmt->execute([$name, $description]);
}
}