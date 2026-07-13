<?php

class PointageController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earth = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earth * $c;
    }

    private function canAttend($userId)
    {
        require_once __DIR__ . '/../Models/AttendanceEligibility.php';

        return (new AttendanceEligibility($this->pdo))->check($userId);
    }

    public function entree()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            header('Location: index.php?page=login');
            exit;
        }

        $eligibility = $this->canAttend($user_id);
        if (!$eligibility['allowed']) {
            $_SESSION['attendance_error'] = $eligibility['message'];
            header('Location: index.php?page=presence');
            exit;
        }

        $lat = isset($_POST['lat']) ? trim($_POST['lat']) : '';
        $lng = isset($_POST['lng']) ? trim($_POST['lng']) : '';

        require_once __DIR__ . '/../Models/Settings.php';
        require_once __DIR__ . '/AttendanceController.php';

        $settingsModel = new Settings($this->pdo);
        $settings = $settingsModel->get();

        $gps_enabled = !empty($settings['gps_enabled']);
        $schoolLat = $settings['school_lat'] ?? '14.721725593495935';
        $schoolLng = $settings['school_lng'] ?? '-17.463747100271004';
        $radius = (int)($settings['radius'] ?? 0);

        if ($gps_enabled) {
            if ($lat === '' || $lng === '') {
                $_SESSION['attendance_error'] = "Impossible d'obtenir votre position. Activez la géolocalisation.";
                header('Location: index.php?page=presence');
                exit;
            }

            $distance = $this->haversine((float)$schoolLat, (float)$schoolLng, (float)$lat, (float)$lng);

            if ($distance > $radius) {
                $_SESSION['attendance_error'] = 'Vous êtes trop loin de l\'école (' . round($distance) . ' m). Rayon autorisé: ' . $radius . ' m.';
                header('Location: index.php?page=presence');
                exit;
            }
        }

        $attCtrl = new AttendanceController($this->pdo);
        $result = $attCtrl->pointerEntree($user_id);

        if ($result === 'déjà_pointé') {
            $_SESSION['attendance_error'] = 'Vous avez déjà pointé l\'entrée aujourd\'hui.';
        } else {
            $_SESSION['attendance_success'] = 'Entrée enregistrée.';
        }

        header('Location: index.php?page=presence');
        exit;
    }

    public function sortie()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            header('Location: index.php?page=login');
            exit;
        }

        $eligibility = $this->canAttend($user_id);
        if (!$eligibility['allowed']) {
            $_SESSION['attendance_error'] = $eligibility['message'];
            header('Location: index.php?page=presence');
            exit;
        }

        $lat = isset($_POST['lat']) ? trim($_POST['lat']) : '';
        $lng = isset($_POST['lng']) ? trim($_POST['lng']) : '';

        require_once __DIR__ . '/../Models/Settings.php';
        require_once __DIR__ . '/AttendanceController.php';

        $settingsModel = new Settings($this->pdo);
        $settings = $settingsModel->get();

        $gps_enabled = !empty($settings['gps_enabled']);
        $schoolLat = $settings['school_lat'] ?? '14.721725593495935';
        $schoolLng = $settings['school_lng'] ?? '-17.463747100271004';
        $radius = (int)($settings['radius'] ?? 0);

        if ($gps_enabled) {
            if ($lat === '' || $lng === '') {
                $_SESSION['attendance_error'] = "Impossible d'obtenir votre position. Activez la géolocalisation.";
                header('Location: index.php?page=presence');
                exit;
            }

            $distance = $this->haversine((float)$schoolLat, (float)$schoolLng, (float)$lat, (float)$lng);

            if ($distance > $radius) {
                $_SESSION['attendance_error'] = 'Vous êtes trop loin de l\'école (' . round($distance) . ' m). Rayon autorisé: ' . $radius . ' m.';
                header('Location: index.php?page=presence');
                exit;
            }
        }

        $attCtrl = new AttendanceController($this->pdo);
        $attCtrl->pointerSortie($user_id);
        $_SESSION['attendance_success'] = 'Sortie enregistrée.';

        header('Location: index.php?page=presence');
        exit;
    }
}
