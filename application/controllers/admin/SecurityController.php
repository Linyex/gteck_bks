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
        $this->requireAccessLevel(10);
        $stats = $this->securityAudit->getSecurityStats(30);
        $recentEvents = $this->securityAudit->getRecentSecurityEvents(20);
        
        $this->render('admin/security/index', [
            'title' => 'Безопасность системы',
            'navPage' => 'security',
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
        $this->requireAccessLevel(10);
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
            'navPage' => 'security',
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
        $this->requireAccessLevel(10);
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
            'navPage' => 'security',
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
        $this->requireAccessLevel(10);
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
        $this->requireAccessLevel(10);
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
        $this->requireAccessLevel(10);
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
            'navPage' => 'security',
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
        $this->requireAccessLevel(10);
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
        $this->requireAccessLevel(10);
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
        $this->requireAccessLevel(10);
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
        $this->requireAccessLevel(10);
        $days = (int)($_GET['days'] ?? 30);
        if ($days <= 0) { $days = 30; }
        $stats = $this->securityAudit->getSecurityStats($days);
        
        // Распределение по типам за период
        try {
            $byType = $this->db->fetchAll(
                "SELECT action_type, COUNT(*) AS cnt
                 FROM security_audit_log
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY)
                 GROUP BY action_type",
                [$days]
            );
        } catch (Exception $e) { $byType = []; }

        // Таймсерии по дням: неудачные и успешные входы
        try {
            $failedSeries = $this->db->fetchAll(
                "SELECT DATE(created_at) AS d, COUNT(*) AS c
                 FROM security_audit_log
                 WHERE action_type='login_failed' AND created_at > DATE_SUB(NOW(), INTERVAL ? DAY)
                 GROUP BY DATE(created_at)
                 ORDER BY d ASC",
                [$days]
            );
        } catch (Exception $e) { $failedSeries = []; }
        try {
            $successSeries = $this->db->fetchAll(
                "SELECT DATE(created_at) AS d, COUNT(*) AS c
                 FROM security_audit_log
                 WHERE action_type='login_success' AND created_at > DATE_SUB(NOW(), INTERVAL ? DAY)
                 GROUP BY DATE(created_at)
                 ORDER BY d ASC",
                [$days]
            );
        } catch (Exception $e) { $successSeries = []; }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'days' => $days,
            'stats' => $stats,
            'by_type' => $byType,
            'series' => [
                'failed_logins' => $failedSeries,
                'successful_logins' => $successSeries,
            ],
        ], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * API для получения последних событий
     */
    public function apiEvents() {
        $this->requireAccessLevel(10);
        $limit = $_GET['limit'] ?? 20;
        $events = $this->securityAudit->getRecentSecurityEvents($limit);
        
        header('Content-Type: application/json');
        echo json_encode($events);
    }

    /**
     * API: список аудита с фильтрами и пагинацией
     */
    public function apiAudit() {
        $this->requireAccessLevel(10);
        header('Content-Type: application/json');
        try {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? min(200, max(1, (int)$_GET['limit'])) : 50;
            $offset = ($page - 1) * $limit;

            $conditions = [];
            $params = [];

            $actionType = $_GET['action_type'] ?? '';
            if ($actionType !== '') {
                $conditions[] = "sal.action_type = ?";
                $params[] = $actionType;
            }

            $userId = $_GET['user_id'] ?? '';
            if ($userId !== '' && ctype_digit((string)$userId)) {
                $conditions[] = "sal.user_id = ?";
                $params[] = (int)$userId;
            }

            $ip = $_GET['ip_address'] ?? '';
            if ($ip !== '') {
                $conditions[] = "sal.ip_address LIKE ?";
                $params[] = "%{$ip}%";
            }

            // Дата: поддерживаем date, date_from, date_to
            $date = $_GET['date'] ?? '';
            $dateFrom = $_GET['date_from'] ?? '';
            $dateTo = $_GET['date_to'] ?? '';
            if ($date !== '') {
                $conditions[] = "DATE(sal.created_at) = ?";
                $params[] = $date;
            } else {
                if ($dateFrom !== '') { $conditions[] = "sal.created_at >= ?"; $params[] = $dateFrom; }
                if ($dateTo !== '') { $conditions[] = "sal.created_at <= ?"; $params[] = $dateTo; }
            }

            $where = !empty($conditions) ? ("WHERE " . implode(" AND ", $conditions)) : '';

            // Total
            $row = $this->db->fetchOne("SELECT COUNT(*) AS cnt FROM security_audit_log sal $where", $params);
            $total = (int)($row['cnt'] ?? 0);
            $totalPages = $limit > 0 ? (int)ceil($total / $limit) : 1;

            // Items
            $items = $this->db->fetchAll(
                "SELECT sal.*, u.user_fio, u.user_email
                 FROM security_audit_log sal
                 LEFT JOIN users u ON sal.user_id = u.user_id
                 $where
                 ORDER BY sal.created_at DESC
                 LIMIT ? OFFSET ?",
                array_merge($params, [$limit, $offset])
            );

            // Stats by type
            $statsByType = $this->db->fetchAll(
                "SELECT sal.action_type, COUNT(*) AS cnt
                 FROM security_audit_log sal
                 $where
                 GROUP BY sal.action_type",
                $params
            );

            echo json_encode([
                'success' => true,
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'total_pages' => $totalPages,
                'items' => $items,
                'stats' => $statsByType,
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * API: активные сессии с фильтрами
     */
    public function apiSessions() {
        $this->requireAccessLevel(10);
        header('Content-Type: application/json');
        try {
            $conditions = ["us.is_active = 1"];
            $params = [];

            $user = $_GET['user'] ?? '';
            if ($user !== '') {
                $conditions[] = "(u.user_fio LIKE ? OR u.user_login LIKE ?)";
                $params[] = "%{$user}%";
                $params[] = "%{$user}%";
            }

            $ip = $_GET['ip'] ?? '';
            if ($ip !== '') {
                $conditions[] = "us.ip_address LIKE ?";
                $params[] = "%{$ip}%";
            }

            $where = 'WHERE ' . implode(' AND ', $conditions);
            $items = $this->db->fetchAll(
                "SELECT us.*, u.user_fio, u.user_login
                 FROM user_sessions us
                 LEFT JOIN users u ON us.user_id = u.user_id
                 $where
                 ORDER BY us.last_activity DESC",
                $params
            );

            echo json_encode(['success' => true, 'items' => $items], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }
} 