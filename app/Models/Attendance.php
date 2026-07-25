<?php

class Attendance
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAttendanceToday($date, $limit = 10, $offset = 0)
{
    // Récupérer le nom du jour actuel (Lundi, Mardi, etc.)
    $dayName = date('l', strtotime($date));
    $daysMap = [
        'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'
    ];
    $todayDayName = $daysMap[$dayName] ?? '';

    $sql = "
    SELECT 
        u.id,
        u.firstname,
        u.lastname,
        u.email,
        u.phone,
        u.photo,

        d.name AS department_name,
        c.name AS cohort_name,

        a.check_in,
        a.check_out,
        a.status

    FROM users u

    LEFT JOIN departments d
        ON u.department_id = d.id

    LEFT JOIN cohorts c
        ON u.cohort_id = c.id

    LEFT JOIN attendances a
        ON u.id = a.user_id AND a.date = ?

    INNER JOIN cohort_schedules cs
        ON cs.cohort_id = u.cohort_id
        AND LOWER(cs.day) = LOWER(?)

    WHERE u.role = 'etudiant'

    ORDER BY u.firstname ASC
    LIMIT ? OFFSET ?
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$date, $todayDayName, $limit, $offset]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

  

    public function countPresentToday()
    {
        return $this->pdo->query("
            SELECT COUNT(*) 
            FROM attendances 
            WHERE date = CURRENT_DATE 
            AND status = 'present'
        ")->fetchColumn();
    }

    public function countLateToday()
    {
        return $this->pdo->query("
            SELECT COUNT(*) 
            FROM attendances 
            WHERE date = CURRENT_DATE 
            AND status = 'retard'
        ")->fetchColumn();
    }

    public function countStudents()
    {
        // Compter uniquement les étudiants qui ont cours aujourd'hui
        $dayName = date('l');
        $daysMap = [
            'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'
        ];
        $todayDayName = $daysMap[$dayName] ?? '';

        $stmt = $this->pdo->prepare("
            SELECT COUNT(DISTINCT u.id)
            FROM users u
            INNER JOIN cohort_schedules cs
                ON cs.cohort_id = u.cohort_id
                AND LOWER(cs.day) = LOWER(?)
            WHERE u.role = 'etudiant'
        ");
        $stmt->execute([$todayDayName]);
        return $stmt->fetchColumn();
    }

  
    public function countAllStudents()
    {
        return $this->pdo->query("
            SELECT COUNT(*) 
            FROM users 
            WHERE role = 'etudiant'
        ")->fetchColumn();
    }

  
    public function getStudentHistory($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM attendances
            WHERE user_id = ?
            ORDER BY date DESC
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function checkToday($userId, $date)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM attendances 
            WHERE user_id = ? AND date = ?
        ");

        $stmt->execute([$userId, $date]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createEntry($userId, $date, $time, $status)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO attendances (user_id, date, check_in, status)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([$userId, $date, $time, $status]);
    }

    public function updateExit($userId, $date, $time)
    {
        $stmt = $this->pdo->prepare("
            UPDATE attendances 
            SET check_out = ?
            WHERE user_id = ? AND date = ?
        ");

        return $stmt->execute([$time, $userId, $date]);
    }
}
