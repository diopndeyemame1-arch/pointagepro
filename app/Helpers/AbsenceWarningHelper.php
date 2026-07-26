<?php

/**
 * Helper pour gérer l'envoi des avertissements d'absence
 * Utilise la table audit_logs existante (pas de nouvelle colonne/migration)
 * 
 * audit_logs.entity = 'absence_warning'
 * audit_logs.action = 'SEND'
 * audit_logs.entity_id = UUID de l'utilisateur
 */

/**
 * Vérifie si un avertissement a déjà été envoyé à l'utilisateur pour le mois en cours
 * 
 * @param PDO $pdo
 * @param string $userId UUID de l'utilisateur
 * @return bool
 */
function hasAbsenceWarningBeenSent($pdo, $userId) {
    $monthStart = date('Y-m-01') . ' 00:00:00';
    
    $sql = "SELECT COUNT(*) FROM audit_logs 
            WHERE entity = 'absence_warning' 
            AND action = 'SEND'
            AND entity_id = :user_id
            AND created_at >= :month_start";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'month_start' => $monthStart
    ]);
    
    return $stmt->fetchColumn() > 0;
}

/**
 * Marque l'avertissement comme envoyé dans audit_logs
 * 
 * @param PDO $pdo
 * @param string $userId UUID de l'utilisateur
 * @param int $absenceCount Nombre d'absences au moment de l'envoi
 * @return void
 */
function markAbsenceWarningAsSent($pdo, $userId, $absenceCount = 0) {
    // On utilise addAudit si la session est disponible
    if (function_exists('addAudit')) {
        addAudit($pdo, 'SEND', 'absence_warning', $userId);
    } else {
        // Fallback : insertion directe
        try {
            $userIdSession = $_SESSION['user_id'] ?? null;
            $stmt = $pdo->prepare("
                INSERT INTO audit_logs
                (user_id, action, entity, entity_id, ip)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $userIdSession,
                'SEND',
                'absence_warning',
                $userId,
                $_SERVER['REMOTE_ADDR'] ?? null
            ]);
        } catch (Throwable $e) {
            error_log('AbsenceWarningHelper: ' . $e->getMessage());
        }
    }
}

/**
 * Récupère le nombre d'avertissements envoyés à un utilisateur
 * 
 * @param PDO $pdo
 * @param string $userId UUID de l'utilisateur
 * @return int
 */
function countAbsenceWarningsSent($pdo, $userId) {
    $sql = "SELECT COUNT(*) FROM audit_logs 
            WHERE entity = 'absence_warning' 
            AND action = 'SEND'
            AND entity_id = :user_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $userId]);
    
    return (int) $stmt->fetchColumn();
}

/**
 * Récupère la date du dernier avertissement envoyé
 * 
 * @param PDO $pdo
 * @param string $userId UUID de l'utilisateur
 * @return string|null Date au format Y-m-d H:i:s ou null si jamais envoyé
 */
function getLastAbsenceWarningDate($pdo, $userId) {
    $sql = "SELECT created_at FROM audit_logs 
            WHERE entity = 'absence_warning' 
            AND action = 'SEND'
            AND entity_id = :user_id
            ORDER BY created_at DESC
            LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result ? $result['created_at'] : null;
}