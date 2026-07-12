<?php

class AuditLogController
{
    private $pdo;


    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function index($page = 1, $limit = 10)
    {
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;

        $total = (int)$this->pdo->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn();
        $totalPages = max(1, (int)ceil($total / $limit));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $limit;

        $sql = "
        SELECT 
            audit_logs.*,
            users.firstname,
            users.lastname,
            users.email

        FROM audit_logs

        LEFT JOIN users 
        ON users.id = audit_logs.user_id

        ORDER BY audit_logs.created_at DESC
        LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $statsStmt = $this->pdo->query("
            SELECT UPPER(action) AS action, COUNT(*) AS total
            FROM audit_logs
            GROUP BY UPPER(action)
        ");

        $stats = [
            'CREATE' => 0,
            'UPDATE' => 0,
            'DELETE' => 0,
        ];

        foreach ($statsStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $stats[$row['action']] = (int)$row['total'];
        }


        return [
            'logs' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'stats' => $stats,
        ];
    }
}
