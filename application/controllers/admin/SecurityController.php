<?php

require_once ENGINE_DIR . 'BaseController.php';
require_once APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';
require_once ENGINE_DIR . 'services/SecurityAuditService.php';

class SecurityController extends BaseAdminController {
    
    private $securityAudit;
    
    public function __construct() {
        parent::__construct();
        $this->securityAudit = new SecurityAuditService();
    }
    
    /**
     * Главная страница безопасности
     */
    public function index() {
        $stats = $this->securityAudit->getSecurityStats(30);
        $recentEvents = $this->securityAudit->getRecentSecurityEvents(20);
        
        $this->render('admin/security/index', [
            'title' => 'Безопасность системы',
            'currentPage' => 'security',
            'stats' => $stats,
            'recentEvents' => $recentEvents,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/security-dashboard.js'
            ]
        ]);
    }
    
    /**
     * Страница аудита безопасности
     */
    public function audit() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        try {
            $total = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log"
            );
            
            $logs = $this->db->fetchAll(
                "SELECT sal.*, u.user_fio, u.user_email 
                 FROM security_audit_log sal 
                 LEFT JOIN users u ON sal.user_id = u.user_id 
                 ORDER BY sal.created_at DESC 
                 LIMIT ? OFFSET ?",
                [$limit, $offset]
            );
            
            $totalPages = ceil($total['count'] / $limit);
            
        } catch (Exception $e) {
            $logs = [];
            $totalPages = 0;
        }
        
        $this->render('admin/security/audit', [
            'title' => 'Аудит безопасности',
            'currentPage' => 'security',
            'logs' => $logs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/security-audit.js'
            ]
        ]);
    }
    
    /**
     * Страница блокировки IP
     */
    public function ipBlacklist() {
        try {
            $blacklist = $this->db->fetchAll(
                "SELECT ib.*, u.user_fio as created_by_name 
                 FROM ip_blacklist ib 
                 LEFT JOIN users u ON ib.created_by = u.user_id 
                 ORDER BY ib.created_at DESC"
            );
        } catch (Exception $e) {
            $blacklist = [];
        }
        
        $this->render('admin/security/ip-blacklist', [
            'title' => 'Блокировка IP адресов',
            'currentPage' => 'security',
            'blacklist' => $blacklist,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/ip-blacklist.js'
            ]
        ]);
    }
    
    /**
     * Добавление IP в черный список
     */
    public function addToBlacklist() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/security/ip-blacklist');
        }
        
        $ipAddress = $_POST['ip_address'] ?? '';
        $reason = $_POST['reason'] ?? '';
        $blockedUntil = $_POST['blocked_until'] ?? null;
        
        if (empty($ipAddress)) {
            $this->setFlashMessage('error', 'IP адрес обязателен');
            $this->redirect('/admin/security/ip-blacklist');
        }
        
        try {
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
            
            $this->setFlashMessage('success', 'IP адрес добавлен в черный список');
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Ошибка при добавлении IP адреса');
        }
        
        $this->redirect('/admin/security/ip-blacklist');
    }
    
    /**
     * Удаление IP из черного списка
     */
    public function removeFromBlacklist() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/security/ip-blacklist');
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $this->setFlashMessage('error', 'ID записи обязателен');
            $this->redirect('/admin/security/ip-blacklist');
        }
        
        try {
            $record = $this->db->fetchOne(
                "SELECT ip_address FROM ip_blacklist WHERE id = ?",
                [$id]
            );
            
            if ($record) {
                $this->db->execute(
                    "DELETE FROM ip_blacklist WHERE id = ?",
                    [$id]
                );
                
                $this->securityAudit->logAdminAction(
                    $_SESSION['admin_user_id'],
                    'remove_ip_from_blacklist',
                    $record['ip_address']
                );
                
                $this->setFlashMessage('success', 'IP адрес удален из черного списка');
            }
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Ошибка при удалении IP адреса');
        }
        
        $this->redirect('/admin/security/ip-blacklist');
    }
    
    /**
     * Страница сессий пользователей
     */
    public function sessions() {
        try {
            $sessions = $this->db->fetchAll(
                "SELECT us.*, u.user_fio, u.user_login 
                 FROM user_sessions us 
                 LEFT JOIN users u ON us.user_id = u.user_id 
                 WHERE us.is_active = 1 
                 ORDER BY us.last_activity DESC"
            );
        } catch (Exception $e) {
            $sessions = [];
        }
        
        $this->render('admin/security/sessions', [
            'title' => 'Активные сессии',
            'currentPage' => 'security',
            'sessions' => $sessions,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/security-sessions.js'
            ]
        ]);
    }
    
    /**
     * Завершение сессии пользователя
     */
    public function terminateSession() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/security/sessions');
        }
        
        $sessionId = $_POST['session_id'] ?? '';
        
        if (empty($sessionId)) {
            $this->setFlashMessage('error', 'ID сессии обязателен');
            $this->redirect('/admin/security/sessions');
        }
        
        try {
            $session = $this->db->fetchOne(
                "SELECT user_id FROM user_sessions WHERE session_token = ?",
                [$sessionId]
            );
            
            if ($session) {
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
                
                $this->setFlashMessage('success', 'Сессия завершена');
            }
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Ошибка при завершении сессии');
        }
        
        $this->redirect('/admin/security/sessions');
    }
    
    /**
     * Экспорт логов безопасности
     */
    public function exportLogs() {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $format = $_GET['format'] ?? 'json';
        
        $logs = $this->securityAudit->exportSecurityLogs($startDate, $endDate, $format);
        
        if ($logs === false) {
            $this->setFlashMessage('error', 'Ошибка при экспорте логов');
            $this->redirect('/admin/security/audit');
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
    }
    
    /**
     * Очистка старых логов
     */
    public function cleanupLogs() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/security/audit');
        }
        
        $days = $_POST['days'] ?? 90;
        
        try {
            $deleted = $this->securityAudit->cleanupOldLogs($days);
            
            $this->securityAudit->logAdminAction(
                $_SESSION['admin_user_id'],
                'cleanup_logs',
                null,
                ['days' => $days, 'deleted_count' => $deleted]
            );
            
            $this->setFlashMessage('success', "Удалено записей: $deleted");
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Ошибка при очистке логов');
        }
        
        $this->redirect('/admin/security/audit');
    }
    
    /**
     * API для получения статистики безопасности
     */
    public function apiStats() {
        $days = $_GET['days'] ?? 30;
        $stats = $this->securityAudit->getSecurityStats($days);
        
        header('Content-Type: application/json');
        echo json_encode($stats);
    }
    
    /**
     * API для получения последних событий
     */
    public function apiEvents() {
        $limit = $_GET['limit'] ?? 20;
        $events = $this->securityAudit->getRecentSecurityEvents($limit);
        
        header('Content-Type: application/json');
        echo json_encode($events);
    }
} 