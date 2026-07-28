<?php

class AttendanceEligibility
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function check($userId, $date = null, $userLat = null, $userLng = null)
    {
        $date = $date ?: date('Y-m-d');
        $dayNumber = (int) date('N', strtotime($date));

        if ($dayNumber === 7) {
            return ['allowed' => false, 'message' => 'Le pointage n\'est pas autorisé le dimanche.'];
        }

        $holiday = $this->pdo->prepare('SELECT 1 FROM public_holidays WHERE holiday_date = :date LIMIT 1');
        $holiday->execute([':date' => $date]);

        if ($holiday->fetchColumn()) {
            return ['allowed' => false, 'message' => 'Le pointage n\'est pas autorisé un jour férié.'];
        }

        $student = $this->pdo->prepare("SELECT cohort_id FROM users WHERE id = :user_id AND role = 'etudiant' LIMIT 1");
        $student->execute([':user_id' => $userId]);
        $cohortId = $student->fetchColumn();

        if (!$cohortId) {
            return ['allowed' => false, 'message' => 'Aucune cohorte n\'est associée à cet étudiant.'];
        }

        $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
        $schedule = $this->pdo->prepare(
            'SELECT 1 FROM cohort_schedules WHERE cohort_id = :cohort_id AND LOWER(day) = LOWER(:day) LIMIT 1'
        );
        $schedule->execute([':cohort_id' => $cohortId, ':day' => $days[$dayNumber]]);

        if (!$schedule->fetchColumn()) {
            return [
                'allowed' => false,
                'message' => 'Le pointage n\'est pas autorisé : aucun cours n\'est prévu aujourd\'hui dans votre emploi du temps.',
            ];
        }

        // Vérification de la position géographique
        if ($userLat !== null && $userLng !== null) {
            $geoCheck = $this->checkGeoLocation((float)$userLat, (float)$userLng);
            if (!$geoCheck['allowed']) {
                return $geoCheck;
            }
        }

        return ['allowed' => true, 'message' => 'Pointage autorisé.'];
    }

    /**
     * Vérifie si l'utilisateur se trouve dans le périmètre de l'école.
     */
    private function checkGeoLocation($userLat, $userLng)
    {
        $stmt = $this->pdo->query("SELECT school_lat, school_lng, radius, gps_enabled FROM settings LIMIT 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$settings || empty($settings['gps_enabled'])) {
            return ['allowed' => true, 'message' => 'Pointage autorisé.'];
        }

        $schoolLat = (float) $settings['school_lat'];
        $schoolLng = (float) $settings['school_lng'];
        $radius = (int) $settings['radius']; // en mètres

        if ($schoolLat == 0 && $schoolLng == 0) {
            return ['allowed' => true, 'message' => 'Pointage autorisé.'];
        }

        // Formule de Haversine pour calculer la distance entre deux points GPS
        $earthRadius = 6371000; // Rayon de la Terre en mètres

        $dLat = deg2rad($userLat - $schoolLat);
        $dLng = deg2rad($userLng - $schoolLng);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($schoolLat)) * cos(deg2rad($userLat)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        if ($distance > $radius) {
            return [
                'allowed' => false,
                'message' => 'Vous êtes trop loin de l\'école, vous ne pouvez pas pointer. Distance : ' . round($distance) . ' m (limite : ' . $radius . ' m).',
            ];
        }

        return ['allowed' => true, 'message' => 'Pointage autorisé.'];
    }
}
