<?php

require_once __DIR__ . '/../Models/QrCodeModel.php';

class QrCodeController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new QrCodeModel($pdo);
    }

    public function index()
    {
        return [
            'qrCode' => $this->model->getAdminQrCode(),
            'stats' => $this->model->getStats(),
            'scans' => $this->model->getTodayScans(),
        ];
    }

    public function generate()
    {
        $this->model->generateAdminQrCode();

        header('Location: index.php?page=qr_code');
        exit;
    }

    public function scan()
    {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json; charset=UTF-8');

        if (session_status() === PHP_SESSION_NONE) {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
        }

        if (($_SESSION['role'] ?? '') !== 'admin') {
            echo json_encode([
                'success' => false,
                'message' => 'Seul un administrateur peut scanner un QR Code.',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Methode non autorisee.',
            ]);
            exit;
        }

        $decodedText = $_POST['uuid'] ?? $_POST['qr_code'] ?? '';

        if (trim((string)$decodedText) === '') {
            echo json_encode([
                'success' => false,
                'message' => 'QR Code vide.',
            ]);
            exit;
        }

        try {
            echo json_encode($this->model->markPresentFromQr($decodedText), JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Impossible d enregistrer le pointage.',
            ], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }

    public function listPresence()
    {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode([
            'success' => true,
            'scans' => $this->model->getTodayScans(),
            'stats' => $this->model->getStats(),
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }
}
