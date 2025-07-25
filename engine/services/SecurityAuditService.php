<?php

class SecurityAuditService {
    private $db;
    private $config;
    
    public function __construct() {
        require_once ENGINE_DIR . 'main/db.php';
        $this->db = new Database();
        $this->config = require(APPLICATION_DIR . 'config.php');
    }
    
    /**
     * Логирует действие пользователя
     */
    public function logAction($userId, $action, $details = [], $ipAddress = null, $userAgent = null) {
        try {
            $ipAddress = $ipAddress ?: $_SERVER['REMOTE_ADDR'] ?? '';
            $userAgent = $userAgent ?: $_SERVER['HTTP_USER_AGENT'] ?? '';
            $sessionId = session_id();
            
            $data = [
                'user_id' => $userId,
                'action_type' => $action,
                'action_details' => json_encode($details),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'session_id' => $sessionId,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->execute(
                "INSERT INTO security_audit_log (user_id, action_type, action_details, ip_address, user_agent, session_id, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                array_values($data)
            );
            
            return true;
        } catch (Exception $e) {
            error_log("SecurityAuditService: Ошибка логирования действия: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Логирует попытку входа
     */
    public function logLoginAttempt($username, $success, $ipAddress = null, $userAgent = null) {
        $details = [
            'username' => $username,
            'success' => $success,
            'timestamp' => time()
        ];
        
        return $this->logAction(
            null, 
            $success ? 'login_success' : 'login_failed', 
            $details, 
            $ipAddress, 
            $userAgent
        );
    }
    
    /**
     * Логирует выход пользователя
     */
    public function logLogout($userId, $ipAddress = null, $userAgent = null) {
        return $this->logAction($userId, 'logout', [], $ipAddress, $userAgent);
    }
    
    /**
     * Логирует изменение данных
     */
    public function logDataChange($userId, $table, $recordId, $action, $oldData = null, $newData = null) {
        $details = [
            'table' => $table,
            'record_id' => $recordId,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData
        ];
        
        return $this->logAction($userId, 'data_change', $details);
    }
    
    /**
     * Логирует доступ к файлам
     */
    public function logFileAccess($userId, $filePath, $action, $success = true) {
        $details = [
            'file_path' => $filePath,
            'action' => $action,
            'success' => $success
        ];
        
        return $this->logAction($userId, 'file_access', $details);
    }
    
    /**
     * Логирует административные действия
     */
    public function logAdminAction($userId, $action, $target = null, $details = []) {
        $details['target'] = $target;
        return $this->logAction($userId, 'admin_action', $details);
    }
    
    /**
     * Проверяет подозрительную активность
     */
    public function checkSuspiciousActivity($userId = null, $ipAddress = null) {
        try {
            $conditions = [];
            $params = [];
            
            if ($userId) {
                $conditions[] = "user_id = ?";
                $params[] = $userId;
            }
            
            if ($ipAddress) {
                $conditions[] = "ip_address = ?";
                $params[] = $ipAddress;
            }
            
            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
            
            // Проверяем количество неудачных попыток входа за последний час
            $failedLogins = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log 
                 $whereClause AND action_type = 'login_failed' 
                 AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)",
                $params
            );
            
            // Проверяем количество действий за последние 10 минут
            $recentActions = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log 
                 $whereClause AND created_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)",
                $params
            );
            
            $suspicious = [];
            
            if ($failedLogins['count'] > 5) {
                $suspicious[] = 'too_many_failed_logins';
            }
            
            if ($recentActions['count'] > 100) {
                $suspicious[] = 'too_many_actions';
            }
            
            return $suspicious;
            
        } catch (Exception $e) {
            error_log("SecurityAuditService: Ошибка проверки подозрительной активности: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Получает статистику безопасности
     */
    public function getSecurityStats($days = 30) {
        try {
            $stats = [];
            
            // Общая статистика
            $totalActions = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
            
            $failedLogins = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log 
                 WHERE action_type = 'login_failed' 
                 AND created_at > DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
            
            $successfulLogins = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log 
                 WHERE action_type = 'login_success' 
                 AND created_at > DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
            
            $adminActions = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log 
                 WHERE action_type = 'admin_action' 
                 AND created_at > DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
            
            $stats = [
                'total_actions' => $totalActions['count'] ?? 0,
                'failed_logins' => $failedLogins['count'] ?? 0,
                'successful_logins' => $successfulLogins['count'] ?? 0,
                'admin_actions' => $adminActions['count'] ?? 0,
                'login_success_rate' => $successfulLogins['count'] > 0 ? 
                    round(($successfulLogins['count'] / ($successfulLogins['count'] + $failedLogins['count'])) * 100, 2) : 0
            ];
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("SecurityAuditService: Ошибка получения статистики: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Получает последние события безопасности
     */
    public function getRecentSecurityEvents($limit = 50) {
        try {
            return $this->db->fetchAll(
                "SELECT sal.*, u.user_fio 
                 FROM security_audit_log sal 
                 LEFT JOIN users u ON sal.user_id = u.user_id 
                 ORDER BY sal.created_at DESC 
                 LIMIT ?",
                [$limit]
            );
        } catch (Exception $e) {
            error_log("SecurityAuditService: Ошибка получения событий: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Экспортирует логи безопасности
     */
    public function exportSecurityLogs($startDate = null, $endDate = null, $format = 'json') {
        try {
            $conditions = [];
            $params = [];
            
            if ($startDate) {
                $conditions[] = "created_at >= ?";
                $params[] = $startDate;
            }
            
            if ($endDate) {
                $conditions[] = "created_at <= ?";
                $params[] = $endDate;
            }
            
            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
            
            $logs = $this->db->fetchAll(
                "SELECT sal.*, u.user_fio, u.user_email 
                 FROM security_audit_log sal 
                 LEFT JOIN users u ON sal.user_id = u.user_id 
                 $whereClause 
                 ORDER BY sal.created_at DESC",
                $params
            );
            
            if ($format === 'csv') {
                return $this->exportToCSV($logs);
            } else {
                return json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            
        } catch (Exception $e) {
            error_log("SecurityAuditService: Ошибка экспорта логов: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Экспорт в CSV
     */
    private function exportToCSV($data) {
        if (empty($data)) {
            return '';
        }
        
        $output = fopen('php://temp', 'r+');
        
        // Заголовки
        fputcsv($output, array_keys($data[0]));
        
        // Данные
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
    
    /**
     * Очищает старые логи
     */
    public function cleanupOldLogs($days = 90) {
        try {
            $deleted = $this->db->execute(
                "DELETE FROM security_audit_log 
                 WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
            
            return $deleted;
        } catch (Exception $e) {
            error_log("SecurityAuditService: Ошибка очистки логов: " . $e->getMessage());
            return false;
        }
    }
} 