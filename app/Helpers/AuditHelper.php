<?php

function addAudit($pdo, $action, $entity, $entityId = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO audit_logs
            (user_id, action, entity, entity_id, ip)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION['user_id'] ?? null,
            strtoupper($action),
            $entity,
            $entityId,
            $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    } catch (Throwable $e) {
        error_log('Audit log failed: ' . $e->getMessage());
    }
}
