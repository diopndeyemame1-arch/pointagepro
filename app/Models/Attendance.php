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

    /**
     * Count the number of absences (scheduled days without attendance) for a student in the current month.
     */
    public function countAbsencesThisMonth($userId)
    {
        $dayName = date('l');
        $daysMap = [
            'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'
        ];

        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');

        $sql = "
            WITH scheduled_days AS (
                SELECT DISTINCT cs.day AS day_name
                FROM cohort_schedules cs
                JOIN users u ON u.cohort_id = cs.cohort_id
                WHERE u.id = ?
            ),
            all_dates_in_month AS (
                SELECT generate_series(
                    ?::date,
                    ?::date,
                    '1 day'::interval
                )::date AS date
            ),
            month_dates_with_dayname AS (
                SELECT 
                    d.date,
                    CASE 
                        WHEN EXTRACT(DOW FROM d.date) = 0 THEN 'Dimanche'
                        WHEN EXTRACT(DOW FROM d.date) = 1 THEN 'Lundi'
                        WHEN EXTRACT(DOW FROM d.date) = 2 THEN 'Mardi'
                        WHEN EXTRACT(DOW FROM d.date) = 3 THEN 'Mercredi'
                        WHEN EXTRACT(DOW FROM d.date) = 4 THEN 'Jeudi'
                        WHEN EXTRACT(DOW FROM d.date) = 5 THEN 'Vendredi'
                        WHEN EXTRACT(DOW FROM d.date) = 6 THEN 'Samedi'
                    END AS day_name
                FROM all_dates_in_month d
            )
            SELECT COUNT(*) AS absence_count
            FROM month_dates_with_dayname m
            JOIN scheduled_days s ON s.day_name = m.day_name
            WHERE m.date <= CURRENT_DATE
              AND NOT EXISTS (
                  SELECT 1 FROM attendances a
                  WHERE a.user_id = ?
                    AND a.date = m.date
              )
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $firstDay, $lastDay, $userId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Mark that the absence warning has been sent for a student.
     */
    public function markAbsenceWarningSent($userId)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET absence_warning_sent = true WHERE id = ?
        ");
        return $stmt->execute([$userId]);
    }

    /**
     * Check if the absence warning has already been sent for a student.
     */
    public function hasAbsenceWarningBeenSent($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT absence_warning_sent FROM users WHERE id = ?
        ");
        $stmt->execute([$userId]);
        return (bool) $stmt->fetchColumn();
    }
}
