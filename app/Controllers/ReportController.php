<?php

class ReportController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $departmentId = $_GET['department_id'] ?? '';
        $cohortId = $_GET['cohort_id'] ?? '';
        $today = date('Y-m-d');

        $departments = $this->pdo
            ->query("SELECT id, name FROM departments ORDER BY name")
            ->fetchAll(PDO::FETCH_ASSOC);

        $cohorts = $this->pdo
            ->query("SELECT id, name, department_id FROM cohorts ORDER BY name")
            ->fetchAll(PDO::FETCH_ASSOC);

        $where = "WHERE u.role = 'etudiant'";
        $params = [':today' => $today];

        if ($departmentId !== '') {
            $where .= " AND u.department_id = :department_id";
            $params[':department_id'] = $departmentId;
        }

        if ($cohortId !== '') {
            $where .= " AND u.cohort_id = :cohort_id";
            $params[':cohort_id'] = $cohortId;
        }

        $sql = "
            SELECT
                COALESCE(d.id, 0) AS department_id,
                COALESCE(d.name, 'Sans departement') AS department,
                COALESCE(c.id, 0) AS cohort_id,
                COALESCE(c.name, 'Sans cohorte') AS cohort,
                COUNT(u.id) AS students,
                COUNT(a.id) FILTER (WHERE a.status = 'present') AS present,
                COUNT(a.id) FILTER (WHERE a.status = 'retard') AS late,
                (COUNT(u.id) - COUNT(a.id)) AS absent
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.id
            LEFT JOIN cohorts c ON u.cohort_id = c.id
            LEFT JOIN attendances a ON a.user_id = u.id AND a.date = :today
            $where
            GROUP BY d.id, d.name, c.id, c.name
            ORDER BY d.name ASC, c.name ASC
        ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalStudents = array_sum(array_column($rows, 'students'));
        $totalPresent = array_sum(array_column($rows, 'present'));
        $totalLate = array_sum(array_column($rows, 'late'));
        $totalAbsent = array_sum(array_column($rows, 'absent'));
        $globalRate = $totalStudents > 0 ? round((($totalPresent + $totalLate) / $totalStudents) * 100) : 0;

        foreach ($rows as &$row) {
            $row['rate'] = ((int)$row['students'] > 0)
                ? round((((int)$row['present'] + (int)$row['late']) / (int)$row['students']) * 100)
                : 0;
        }
        unset($row);

        return [
            'today' => $today,
            'departments' => $departments,
            'cohorts' => $cohorts,
            'rows' => $rows,
            'filters' => [
                'department_id' => $departmentId,
                'cohort_id' => $cohortId,
            ],
            'kpi' => [
                'students' => $totalStudents,
                'present' => $totalPresent,
                'late' => $totalLate,
                'absent' => $totalAbsent,
                'global_rate' => $globalRate,
            ],
        ];
    }
}
