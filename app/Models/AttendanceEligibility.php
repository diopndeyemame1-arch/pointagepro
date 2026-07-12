<?php

class AttendanceEligibility
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function check($userId, $date = null)
    {
        $date = $date ?: date('Y-m-d');
        $dayNumber = (int) date('N', strtotime($date));

        if ($dayNumber === 7) {
            return ['allowed' => false, 'message' => 'Le pointage n’est pas autorisé le dimanche.'];
        }

        $holiday = $this->pdo->prepare('SELECT 1 FROM public_holidays WHERE holiday_date = :date LIMIT 1');
        $holiday->execute([':date' => $date]);

        if ($holiday->fetchColumn()) {
            return ['allowed' => false, 'message' => 'Le pointage n’est pas autorisé un jour férié.'];
        }

        $student = $this->pdo->prepare("SELECT cohort_id FROM users WHERE id = :user_id AND role = 'etudiant' LIMIT 1");
        $student->execute([':user_id' => $userId]);
        $cohortId = $student->fetchColumn();

        if (!$cohortId) {
            return ['allowed' => false, 'message' => 'Aucune cohorte n’est associée à cet étudiant.'];
        }

        $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
        $schedule = $this->pdo->prepare(
            'SELECT 1 FROM cohort_schedules WHERE cohort_id = :cohort_id AND LOWER(day) = LOWER(:day) LIMIT 1'
        );
        $schedule->execute([':cohort_id' => $cohortId, ':day' => $days[$dayNumber]]);

        if (!$schedule->fetchColumn()) {
            return [
                'allowed' => false,
                'message' => 'Le pointage n’est pas autorisé : aucun cours n’est prévu aujourd’hui dans votre emploi du temps.',
            ];
        }

        return ['allowed' => true, 'message' => 'Pointage autorisé.'];
    }
}
