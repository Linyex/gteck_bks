<?php

require_once __DIR__ . '/../../../engine/BaseController.php';
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../../engine/services/SecurityMonitoringService.php';
require_once __DIR__ . '/../../../engine/services/AdvancedLoggingService.php';

class MonitoringController extends BaseAdminController {
    
    private $securityMonitoring;
    private $advancedLogging;
    
    public function __construct() {
        parent::__construct();
        $this->securityMonitoring = new SecurityMonitoringService();
        $this->advancedLogging = new AdvancedLoggingService();
    }
    
    /**
     * Главная страница мониторинга
     */
    public function index() {
        try {
            $rawSecurityStats = $this->securityMonitoring->getSecurityStats(30);
            $currentThreats = $this->securityMonitoring->monitorRealTime();
            
            // Формируем правильную структуру securityStats
            $securityStats = [
                'security_score' => $this->calculateSecurityScore($rawSecurityStats),
                'system_status' => $this->determineSystemStatus($rawSecurityStats),
                'total_threats' => $rawSecurityStats['total_threats'] ?? 0,
                'blocked_ips' => $rawSecurityStats['blocked_ips'] ?? 0,
                'suspicious_activities' => $this->countSuspiciousActivities($rawSecurityStats)
            ];
            
            // Получаем системную статистику
            $systemStats = $this->getSystemStats();
            
            // Получаем последние угрозы
            $recentThreats = $this->getRecentThreats();
            
        } catch (Exception $e) {
            // Если произошла ошибка, устанавливаем значения по умолчанию
            $securityStats = [
                'security_score' => 85,
                'system_status' => 'secure',
                'total_threats' => 0,
                'blocked_ips' => 0,
                'suspicious_activities' => 0
            ];
            $currentThreats = [];
            $systemStats = [
                'cpu_usage' => 0,
                'memory_usage' => 0,
                'disk_usage' => 0,
                'network_usage' => 0
            ];
            $recentThreats = [];
        }
        
        $this->render('admin/monitoring/index', [
            'title' => 'Мониторинг безопасности',
            'currentPage' => 'monitoring',
            'securityStats' => $securityStats,
            'systemStats' => $systemStats,
            'recentThreats' => $recentThreats,
            'currentThreats' => $currentThreats,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/monitoring-dashboard.js'
            ]
        ]);
    }
    
    /**
     * Страница логов
     */
    public function logs() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $filters = [
            'level' => $_GET['level'] ?? null,
            'user_id' => $_GET['user_id'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        try {
            $logs = $this->advancedLogging->getLogs($filters, $limit, $offset);
            $total = count($this->advancedLogging->getLogs($filters, 10000, 0));
            $totalPages = ceil($total / $limit);
            
        } catch (Exception $e) {
            $logs = [];
            $totalPages = 0;
        }
        
        $this->render('admin/monitoring/logs', [
            'title' => 'Логи системы',
            'currentPage' => 'monitoring',
            'logs' => $logs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/monitoring-logs.js'
            ]
        ]);
    }
    
    /**
     * Страница угроз
     */
    public function threats() {
        try {
            require_once __DIR__ . '/../../../engine/main/db.php';
            $threats = Database::fetchAll(
                "SELECT sm.*, u.user_fio as resolved_by_name 
                 FROM security_monitoring sm 
                 LEFT JOIN users u ON sm.resolved_by = u.user_id 
                 ORDER BY sm.created_at DESC"
            );
        } catch (Exception $e) {
            $threats = [];
        }
        
        $this->render('admin/monitoring/threats', [
            'title' => 'Угрозы безопасности',
            'currentPage' => 'monitoring',
            'threats' => $threats,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/monitoring-threats.js'
            ]
        ]);
    }
    
    /**
     * Страница отчетов
     */
    public function reports() {
        try {
            $reports = $this->db->fetchAll(
                "SELECT sr.*, u.user_fio as generated_by_name 
                 FROM security_reports sr 
                 LEFT JOIN users u ON sr.generated_by = u.user_id 
                 ORDER BY sr.created_at DESC"
            );
        } catch (Exception $e) {
            $reports = [];
        }
        
        $this->render('admin/monitoring/reports', [
            'title' => 'Отчеты безопасности',
            'currentPage' => 'monitoring',
            'reports' => $reports,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/monitoring-reports.js'
            ]
        ]);
    }
    
    /**
     * Страница настроек мониторинга
     */
    public function settings() {
        try {
            $settings = $this->db->fetchAll(
                "SELECT * FROM monitoring_settings ORDER BY setting_key"
            );
        } catch (Exception $e) {
            $settings = [];
        }
        
        $this->render('admin/monitoring/settings', [
            'title' => 'Настройки мониторинга',
            'currentPage' => 'monitoring',
            'settings' => $settings,
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/admin-common.js',
                '/assets/js/monitoring-settings.js'
            ]
        ]);
    }
    
    /**
     * Обновление настроек мониторинга
     */
    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/monitoring/settings');
        }
        
        try {
            $settings = $_POST['settings'] ?? [];
            
            foreach ($settings as $key => $value) {
                $this->db->execute(
                    "UPDATE monitoring_settings SET setting_value = ? WHERE setting_key = ?",
                    [$value, $key]
                );
            }
            
            $this->advancedLogging->info(
                'Настройки мониторинга обновлены',
                ['settings' => $settings],
                $_SESSION['admin_user_id']
            );
            
            $this->setFlashMessage('success', 'Настройки обновлены');
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Ошибка обновления настроек');
        }
        
        $this->redirect('/admin/monitoring/settings');
    }
    
    /**
     * Разрешение угрозы
     */
    public function resolveThreat() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/monitoring/threats');
        }
        
        $threatId = $_POST['threat_id'] ?? 0;
        
        if (!$threatId) {
            $this->setFlashMessage('error', 'ID угрозы обязателен');
            $this->redirect('/admin/monitoring/threats');
        }
        
        try {
            $this->db->execute(
                "UPDATE security_monitoring SET is_resolved = 1, resolved_at = NOW(), resolved_by = ? WHERE id = ?",
                [$_SESSION['admin_user_id'], $threatId]
            );
            
            $this->advancedLogging->info(
                'Угроза разрешена',
                ['threat_id' => $threatId],
                $_SESSION['admin_user_id']
            );
            
            $this->setFlashMessage('success', 'Угроза разрешена');
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Ошибка разрешения угрозы');
        }
        
        $this->redirect('/admin/monitoring/threats');
    }
    
    /**
     * Создание отчета безопасности
     */
    public function generateReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/monitoring/reports');
        }
        
        $days = $_POST['days'] ?? 30;
        
        try {
            $report = $this->securityMonitoring->generateSecurityReport($days);
            
            $this->db->execute(
                "INSERT INTO security_reports (report_type, title, content, generated_by) 
                 VALUES (?, ?, ?, ?)",
                [
                    'security',
                    "Отчет о безопасности за $days дней",
                    json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                    $_SESSION['admin_user_id']
                ]
            );
            
            $this->advancedLogging->info(
                'Отчет безопасности создан',
                ['days' => $days],
                $_SESSION['admin_user_id']
            );
            
            $this->setFlashMessage('success', 'Отчет создан');
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Ошибка создания отчета');
        }
        
        $this->redirect('/admin/monitoring/reports');
    }
    
    /**
     * Экспорт логов
     */
    public function exportLogs() {
        $filters = [
            'level' => $_GET['level'] ?? null,
            'user_id' => $_GET['user_id'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        $format = $_GET['format'] ?? 'json';
        
        $logs = $this->advancedLogging->exportLogs($filters, $format);
        
        if ($logs === false) {
            $this->setFlashMessage('error', 'Ошибка экспорта логов');
            $this->redirect('/admin/monitoring/logs');
        }
        
        $filename = 'system_logs_' . date('Y-m-d_H-i-s');
        
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
            $this->redirect('/admin/monitoring/logs');
        }
        
        $days = $_POST['days'] ?? 90;
        
        try {
            $deleted = $this->advancedLogging->rotateLogs($days);
            
            $this->advancedLogging->info(
                'Логи очищены',
                ['days' => $days, 'deleted_count' => $deleted],
                $_SESSION['admin_user_id']
            );
            
            $this->setFlashMessage('success', "Удалено записей: $deleted");
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Ошибка очистки логов');
        }
        
        $this->redirect('/admin/monitoring/logs');
    }
    
    /**
     * API для получения статистики мониторинга
     */
    public function apiStats() {
        try {
            $stats = $this->securityMonitoring->getSecurityStats(30);
            
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
     * API для получения текущих угроз
     */
    public function apiThreats() {
        try {
            $threats = $this->securityMonitoring->monitorRealTime();
            
            $this->jsonResponse([
                'success' => true,
                'data' => $threats
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API для получения логов
     */
    public function apiLogs() {
        try {
            $filters = [
                'level' => $_GET['level'] ?? null,
                'user_id' => $_GET['user_id'] ?? null,
                'date_from' => $_GET['date_from'] ?? null,
                'date_to' => $_GET['date_to'] ?? null,
                'search' => $_GET['search'] ?? null
            ];
            
            $limit = $_GET['limit'] ?? 50;
            $offset = $_GET['offset'] ?? 0;
            
            $logs = $this->advancedLogging->getLogs($filters, $limit, $offset);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $logs
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Получение системной статистики
     */
    private function getSystemStats() {
        try {
            // Симуляция получения системной статистики
            return [
                'cpu_usage' => rand(10, 80),
                'memory_usage' => rand(20, 90),
                'disk_usage' => rand(30, 85),
                'network_usage' => rand(5, 50)
            ];
        } catch (Exception $e) {
            return [
                'cpu_usage' => 0,
                'memory_usage' => 0,
                'disk_usage' => 0,
                'network_usage' => 0
            ];
        }
    }
    
    /**
     * Расчет балла безопасности
     */
    private function calculateSecurityScore($stats) {
        $baseScore = 100;
        $threats = $stats['total_threats'] ?? 0;
        $blockedIPs = $stats['blocked_ips'] ?? 0;
        
        // Уменьшаем балл за угрозы
        $score = $baseScore - ($threats * 5);
        
        // Увеличиваем балл за заблокированные IP
        $score += ($blockedIPs * 2);
        
        return max(0, min(100, $score));
    }
    
    /**
     * Определение статуса системы
     */
    private function determineSystemStatus($stats) {
        $threats = $stats['total_threats'] ?? 0;
        
        if ($threats == 0) {
            return 'secure';
        } elseif ($threats <= 5) {
            return 'warning';
        } else {
            return 'critical';
        }
    }
    
    /**
     * Подсчет подозрительной активности
     */
    private function countSuspiciousActivities($stats) {
        $suspiciousCount = 0;
        
        if (isset($stats['threats_by_type'])) {
            foreach ($stats['threats_by_type'] as $threat) {
                if ($threat['threat_type'] === 'suspicious_activity') {
                    $suspiciousCount += $threat['count'];
                }
            }
        }
        
        return $suspiciousCount;
    }
    
    /**
     * Получение последних угроз
     */
    private function getRecentThreats() {
        try {
            require_once __DIR__ . '/../../../engine/main/db.php';
            return Database::fetchAll(
                "SELECT * FROM security_monitoring 
                 ORDER BY created_at DESC 
                 LIMIT 5"
            );
        } catch (Exception $e) {
            return [];
        }
    }
} 