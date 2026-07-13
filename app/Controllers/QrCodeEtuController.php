<?php

require_once __DIR__ . '/../Models/QrCodeModel.php';

class QrCodeEtuController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new QrCodeModel($pdo);
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
        }

        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: index.php?page=login');
            exit;
        }

        $student = $this->model->getStudentById($userId);

        if (!$student) {
            header('Location: index.php?page=etudiant');
            exit;
        }

        return [
            'etudiant' => $student,
            'qrCode' => $this->model->generateStudentQrCode($student['id']),
        ];
    }

    public function scanAdminQr()
    {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json; charset=UTF-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Methode non autorisee.']);
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $student = $userId ? $this->model->getStudentById($userId) : null;

        if (!$student) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Session etudiant invalide.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $qrCode = trim((string) ($_POST['qr_code'] ?? ''));
        if ($qrCode !== 'POINTAGE_ADMIN_SCAN') {
            echo json_encode(['success' => false, 'message' => 'Ce QR Code n est pas le QR Code de pointage.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        try {
            echo json_encode($this->model->markPresentForStudent($student['id']), JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Impossible d enregistrer le pointage.'], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }
}
