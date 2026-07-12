<?php

class QrCodeModel
{
    private $pdo;
    private $publicBase = '/COUR-TELLY-TECH/pointagepro/public';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getStudentById($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                u.id,
                u.firstname,
                u.lastname,
                u.email,
                u.phone,
                u.role,
                u.is_active,
                u.cohort_id,
                d.name AS department,
                c.name AS cohort
            FROM users u
            LEFT JOIN departments d ON d.id = u.department_id
            LEFT JOIN cohorts c ON c.id = u.cohort_id
            WHERE u.id = :id
            AND u.role = 'etudiant'
            LIMIT 1
        ");
        $stmt->execute([':id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateStudentQrCode($userId)
    {
        require_once __DIR__ . '/../../vendor/autoload.php';

        $student = $this->getStudentById($userId);
        if (!$student) {
            return null;
        }

        $folder = __DIR__ . '/../../public/uploads/qrcodes';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $safeId = preg_replace('/[^a-zA-Z0-9_-]/', '_', (string)$student['id']);
        $fileName = 'student_' . $safeId . '.png';
        $filePath = $folder . '/' . $fileName;
        $payload = $this->buildStudentPayload($student['id']);

        $qrCode = \Endroid\QrCode\QrCode::create($payload)
            ->setSize(320)
            ->setMargin(16)
            ->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High);

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);
        $result->saveToFile($filePath);

        return $this->publicBase . '/uploads/qrcodes/' . $fileName;
    }

    public function generateAdminQrCode()
    {
        require_once __DIR__ . '/../../vendor/autoload.php';

        $folder = __DIR__ . '/../../public/uploads/qrcodes';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $filePath = $folder . '/qr_actif.png';
        $qrCode = \Endroid\QrCode\QrCode::create('POINTAGE_ADMIN_SCAN')
            ->setSize(320)
            ->setMargin(16)
            ->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High);

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $writer->write($qrCode)->saveToFile($filePath);

        return $this->publicBase . '/uploads/qrcodes/qr_actif.png';
    }

    public function getAdminQrCode()
    {
        $filePath = __DIR__ . '/../../public/uploads/qrcodes/qr_actif.png';

        if (!file_exists($filePath)) {
            return $this->generateAdminQrCode();
        }

        return $this->publicBase . '/uploads/qrcodes/qr_actif.png';
    }

    public function markPresentFromQr($decodedText)
    {
        $userId = $this->extractUserId($decodedText);
        if (!$userId) {
            return [
                'success' => false,
                'message' => 'QR Code invalide.',
            ];
        }

        $student = $this->getStudentById($userId);
        if (!$student) {
            return [
                'success' => false,
                'message' => 'Etudiant introuvable pour ce QR Code.',
            ];
        }

        if (isset($student['is_active']) && !in_array($student['is_active'], [1, '1', true, 't', 'true', 'TRUE'], true)) {
            return [
                'success' => false,
                'message' => 'Ce compte etudiant est inactif.',
            ];
        }

        $date = date('Y-m-d');
        $time = date('H:i:s');

        require_once __DIR__ . '/AttendanceEligibility.php';
        $eligibility = (new AttendanceEligibility($this->pdo))->check($student['id'], $date);

        if (!$eligibility['allowed']) {
            return [
                'success' => false,
                'message' => $eligibility['message'],
            ];
        }

        $existing = $this->findAttendance($student['id'], $date);

        if ($existing) {
            $stmt = $this->pdo->prepare("
                UPDATE attendances
                SET status = 'present',
                    check_in = COALESCE(check_in, :time)
                WHERE id = :id
            ");
            $stmt->execute([
                ':time' => $time,
                ':id' => $existing['id'],
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO attendances (user_id, date, check_in, status)
                VALUES (:user_id, :date, :check_in, 'present')
            ");
            $stmt->execute([
                ':user_id' => $student['id'],
                ':date' => $date,
                ':check_in' => $time,
            ]);
        }

        return [
            'success' => true,
            'message' => $existing ? 'Presence deja enregistree aujourd hui.' : 'Presence enregistree.',
            'etudiant' => $this->formatStudent($student, $date, $existing['check_in'] ?? $time, 'present'),
        ];
    }

    public function markPresentForStudent($userId)
    {
        return $this->markPresentFromQr($this->buildStudentPayload($userId));
    }

    public function getTodayScans($limit = 20)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                u.id,
                u.firstname,
                u.lastname,
                d.name AS department,
                c.name AS cohort,
                a.date,
                a.check_in,
                a.status
            FROM attendances a
            INNER JOIN users u ON u.id = a.user_id
            LEFT JOIN departments d ON d.id = u.department_id
            LEFT JOIN cohorts c ON c.id = u.cohort_id
            WHERE a.date = CURRENT_DATE
            AND u.role = 'etudiant'
            ORDER BY a.check_in DESC NULLS LAST, u.firstname ASC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return array_map(function ($row) {
            return $this->formatStudent($row, $row['date'], $row['check_in'], $row['status']);
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getStats()
    {
        return [
            'active_qr' => (int)$this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn(),
            'scans_today' => (int)$this->pdo->query("SELECT COUNT(*) FROM attendances WHERE date = CURRENT_DATE")->fetchColumn(),
            'present_today' => (int)$this->pdo->query("SELECT COUNT(*) FROM attendances WHERE date = CURRENT_DATE AND status = 'present'")->fetchColumn(),
            'late_today' => (int)$this->pdo->query("SELECT COUNT(*) FROM attendances WHERE date = CURRENT_DATE AND status = 'retard'")->fetchColumn(),
        ];
    }

    private function buildStudentPayload($userId)
    {
        return 'POINTAGE_USER_' . trim((string)$userId);
    }

    private function extractUserId($decodedText)
    {
        $decodedText = trim((string)$decodedText);

        if (preg_match('/^POINTAGE_USER_([a-zA-Z0-9_-]+)$/', $decodedText, $matches)) {
            return trim($matches[1]);
        }

        if (preg_match('/^[0-9]+$/', $decodedText)) {
            return $decodedText;
        }

        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $decodedText)) {
            return $decodedText;
        }

        if (preg_match('/(?:user_id|student_id|id)=([a-zA-Z0-9_-]+)/i', $decodedText, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function findAttendance($userId, $date)
    {
        $stmt = $this->pdo->prepare("
            SELECT id, check_in, status
            FROM attendances
            WHERE user_id = :user_id
            AND date = :date
            LIMIT 1
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':date' => $date,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function formatStudent(array $student, $date, $checkIn, $status)
    {
        return [
            'id' => $student['id'] ?? null,
            'firstname' => $student['firstname'] ?? '',
            'lastname' => $student['lastname'] ?? '',
            'department' => $student['department'] ?? 'Non defini',
            'cohort' => $student['cohort'] ?? 'Non defini',
            'date' => $date,
            'check_in' => $checkIn,
            'status' => $status ?: 'present',
        ];
    }
}
