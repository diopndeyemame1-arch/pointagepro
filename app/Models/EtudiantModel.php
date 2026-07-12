<?php

class EtudiantModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // =========================
    // USER BY EMAIL
    // =========================
    public function getByEmail($email)
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, d.name AS department
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.id
            WHERE u.email = :email
            LIMIT 1
        ");

        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // GENERER QR CODE
    // =========================
    public function generateQrCode($userId)
    {
        require_once __DIR__ . '/QrCodeModel.php';

        $qrModel = new QrCodeModel($this->pdo);

        return $qrModel->generateStudentQrCode($userId);
    }
}
