<?php

require_once ENGINE_DIR . 'BaseController.php';
require_once APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';
require_once ENGINE_DIR . 'services/SecurityAuditService.php';

class SecurityApiController extends BaseAdminController {
    
    private $securityAudit;
    
    public function __construct() {
        parent::__construct();
        $this->securityAudit = new SecurityAuditService();
    }
    
    /**
     * Получение статистики безопасности
     */
    public function stats() {
        try {
            $days = $_GET['days'] ?? 30;
            $stats = $this->securityAudit->getSecurityStats($days);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Получение последних событий безопасности
     */
    public function events() {
        try {
            $limit = $_GET['limit'] ?? 20;
            $events = $this->securityAudit->getRecentSecurityEvents($limit);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $events
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Проверка подозрительной активности
     */
    public function checkSuspiciousActivity() {
        try {
            $userId = $_GET['user_id'] ?? null;
            $ipAddress = $_GET['ip_address'] ?? null;
            
            $suspicious = $this->securityAudit->checkSuspiciousActivity($userId, $ipAddress);
            
            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'suspicious' => $suspicious,
                    'has_suspicious' => !empty($suspicious)
                ]
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Добавление IP в черный список
     */
    public function addToBlacklist() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Method not allowed'
            ], 405);
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $ipAddress = $data['ip_address'] ?? '';
            $reason = $data['reason'] ?? '';
            $blockedUntil = $data['blocked_until'] ?? null;
            
            if (empty($ipAddress)) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'IP address is required'
                ], 400);
            }
            
            $this->db->execute(
                "INSERT INTO ip_blacklist (ip_address, reason, blocked_until, created_by) 
                 VALUES (?, ?, ?, ?)",
                [$ipAddress, $reason, $blockedUntil, $_SESSION['admin_user_id']]
            );
            
            $this->securityAudit->logAdminAction(
                $_SESSION['admin_user_id'],
                'add_ip_to_blacklist',
                $ipAddress,
                ['reason' => $reason, 'blocked_until' => $blockedUntil]
            );
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'IP address added to blacklist'
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Удаление IP из черного списка
     */
    public function removeFromBlacklist() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Method not allowed'
            ], 405);
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? 0;
            
            if (!$id) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'ID is required'
                ], 400);
            }
            
            $record = $this->db->fetchOne(
                "SELECT ip_address FROM ip_blacklist WHERE id = ?",
                [$id]
            );
            
            if (!$record) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Record not found'
                ], 404);
            }
            
            $this->db->execute(
                "DELETE FROM ip_blacklist WHERE id = ?",
                [$id]
            );
            
            $this->securityAudit->logAdminAction(
                $_SESSION['admin_user_id'],
                'remove_ip_from_blacklist',
                $record['ip_address']
            );
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'IP address removed from blacklist'
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Получение черного списка IP
     */
    public function blacklist() {
        try {
            $blacklist = $this->db->fetchAll(
                "SELECT ib.*, u.user_fio as created_by_name 
                 FROM ip_blacklist ib 
                 LEFT JOIN users u ON ib.created_by = u.user_id 
                 ORDER BY ib.created_at DESC"
            );
            
            $this->jsonResponse([
                'success' => true,
                'data' => $blacklist
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Получение активных сессий
     */
    public function sessions() {
        try {
            $sessions = $this->db->fetchAll(
                "SELECT us.*, u.user_fio, u.user_email 
                 FROM user_sessions us 
                 LEFT JOIN users u ON us.user_id = u.user_id 
                 WHERE us.is_active = 1 
                 ORDER BY us.last_activity DESC"
            );
            
            $this->jsonResponse([
                'success' => true,
                'data' => $sessions
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Завершение сессии
     */
    public function terminateSession() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Method not allowed'
            ], 405);
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $sessionId = $data['session_id'] ?? '';
            
            if (empty($sessionId)) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Session ID is required'
                ], 400);
            }
            
            $session = $this->db->fetchOne(
                "SELECT user_id FROM user_sessions WHERE session_token = ?",
                [$sessionId]
            );
            
            if (!$session) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Session not found'
                ], 404);
            }
            
            $this->db->execute(
                "UPDATE user_sessions SET is_active = 0 WHERE session_token = ?",
                [$sessionId]
            );
            
            $this->securityAudit->logAdminAction(
                $_SESSION['admin_user_id'],
                'terminate_session',
                $session['user_id'],
                ['session_id' => $sessionId]
            );
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Session terminated'
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Экспорт логов безопасности
     */
    public function exportLogs() {
        try {
            $startDate = $_GET['start_date'] ?? null;
            $endDate = $_GET['end_date'] ?? null;
            $format = $_GET['format'] ?? 'json';
            
            $logs = $this->securityAudit->exportSecurityLogs($startDate, $endDate, $format);
            
            if ($logs === false) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Failed to export logs'
                ], 500);
            }
            
            $filename = 'security_logs_' . date('Y-m-d_H-i-s');
            
            if ($format === 'csv') {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
            } else {
                header('Content-Type: application/json');
                header('Content-Disposition: attachment; filename="' . $filename . '.json"');
            }
            
            echo $logs;
            exit;
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Очистка старых логов
     */
    public function cleanupLogs() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Method not allowed'
            ], 405);
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $days = $data['days'] ?? 90;
            
            $deleted = $this->securityAudit->cleanupOldLogs($days);
            
            $this->securityAudit->logAdminAction(
                $_SESSION['admin_user_id'],
                'cleanup_logs',
                null,
                ['days' => $days, 'deleted_count' => $deleted]
            );
            
            $this->jsonResponse([
                'success' => true,
                'message' => "Deleted $deleted records",
                'data' => ['deleted_count' => $deleted]
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 