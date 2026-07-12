<?php

require_once __DIR__ . '/../Models/Settings.php';
require_once __DIR__ . '/../Models/CompanySettings.php';
require_once __DIR__ . '/../Models/Schedule.php';

class SettingsController
{
    private $pdo;
    private $settings;
    private $company;
    private $schedule;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->settings = new Settings($pdo);
        $this->company = new CompanySettings($pdo);
        $this->schedule = new Schedule($pdo);
    }

    public function index()
    {
        return [
            'settings' => $this->settings->get(),
            'company' => $this->company->get(),
            'schedule' => $this->schedule->get()
        ];
    }

    public function update()
    {
        $this->company->save($_POST['company_name'], $_POST['company_email']);

        $currentSettings = $this->settings->get();
        $radius = isset($_POST['radius']) && trim($_POST['radius']) !== ''
            ? (int) $_POST['radius']
            : (int) ($currentSettings['radius'] ?? 0);

        $schoolLat = isset($_POST['lat']) && trim($_POST['lat']) !== ''
            ? trim($_POST['lat'])
            : '14.721725593495935';

        $schoolLng = isset($_POST['lng']) && trim($_POST['lng']) !== ''
            ? trim($_POST['lng'])
            : '-17.463747100271004';

        $this->settings->save([
            'lat' => $schoolLat,
            'lng' => $schoolLng,
            'radius' => $radius,
            'gps' => isset($_POST['gps_enabled'])
        ]);

        $this->schedule->save($_POST);

        if (!empty($_POST['current_password']) || !empty($_POST['new_password']) || !empty($_POST['confirm_password'])) {
            if (empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
                $_SESSION['settings_error'] = 'Veuillez remplir tous les champs du mot de passe.';
            } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
                $_SESSION['settings_error'] = 'Les nouveaux mots de passe ne correspondent pas.';
            } else {
                $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['admin']['id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !password_verify($_POST['current_password'], $user['password_hash'])) {
                    $_SESSION['settings_error'] = 'Mot de passe actuel invalide.';
                } else {
                    $password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $update = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                    $update->execute([$password_hash, $_SESSION['admin']['id']]);
                    $_SESSION['settings_success'] = 'Les modifications et le mot de passe ont été enregistrés.';
                }
            }
        } else {
            $_SESSION['settings_success'] = 'Les modifications ont été enregistrées.';
        }

        header("Location: index.php?page=settings&success=1");
        exit;
    }
    
}