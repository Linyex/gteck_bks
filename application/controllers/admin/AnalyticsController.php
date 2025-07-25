<?php

/**
 * Контроллер аналитики и мониторинга
 */
class AnalyticsController extends BaseAdminController {
    
    private $analyticsService;
    private $monitoringService;
    private $loggingService;
    
    public function __construct() {
        parent::__construct();
        
        require_once ENGINE_DIR . 'services/AnalyticsService.php';
        require_once ENGINE_DIR . 'services/MonitoringService.php';
        require_once ENGINE_DIR . 'services/LoggingService.php';
        
        $this->analyticsService = new AnalyticsService();
        $this->monitoringService = new MonitoringService();
        $this->loggingService = new LoggingService();
    }
    
    /**
     * Главная страница аналитики
     */
    public function index() {
        try {
            $systemStats = $this->analyticsService->getSystemStats();
            $monitoringData = $this->monitoringService->runSystemCheck();
            
            $this->view->render('admin/analytics/index', [
                'system_stats' => $systemStats,
                'monitoring_data' => $monitoringData,
                'page_title' => 'Аналитика и мониторинг'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки аналитики: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница мониторинга системы
     */
    public function monitoring() {
        try {
            $monitoringData = $this->monitoringService->runSystemCheck();
            $alerts = $this->monitoringService->getAlerts();
            
            $this->view->render('admin/analytics/monitoring', [
                'monitoring_data' => $monitoringData,
                'alerts' => $alerts,
                'page_title' => 'Мониторинг системы'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки мониторинга: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница логов
     */
    public function logs() {
        try {
            $level = $_GET['level'] ?? null;
            $lines = (int)($_GET['lines'] ?? 100);
            
            $logs = $this->loggingService->getLogs($level, $lines);
            $logStats = $this->loggingService->getLogStats();
            
            $this->view->render('admin/analytics/logs', [
                'logs' => $logs,
                'log_stats' => $logStats,
                'current_level' => $level,
                'lines' => $lines,
                'page_title' => 'Системные логи'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки логов: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница графиков
     */
    public function charts() {
        try {
            $period = $_GET['period'] ?? '7d';
            $chartType = $_GET['type'] ?? 'user_activity';
            
            $chartData = $this->analyticsService->getChartData($chartType, $period);
            
            $this->view->render('admin/analytics/charts', [
                'chart_data' => $chartData,
                'chart_type' => $chartType,
                'period' => $period,
                'page_title' => 'Графики и диаграммы'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки графиков: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница пользовательской аналитики
     */
    public function userAnalytics() {
        try {
            $topUsers = $this->analyticsService->getTopUsers(20);
            $popularActions = $this->analyticsService->getPopularActions(20);
            
            $this->view->render('admin/analytics/user_analytics', [
                'top_users' => $topUsers,
                'popular_actions' => $popularActions,
                'page_title' => 'Аналитика пользователей'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки пользовательской аналитики: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница безопасности
     */
    public function security() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $securityEvents = Database::fetchAll("
                SELECT * FROM security_events 
                ORDER BY created_at DESC 
                LIMIT 50
            ");
            
            $failedLogins = Database::fetchAll("
                SELECT * FROM system_logs 
                WHERE level = 'info' AND message LIKE '%Login Attempt: FAILED%'
                ORDER BY created_at DESC 
                LIMIT 20
            ");
            
            $suspiciousActivity = Database::fetchAll("
                SELECT * FROM system_logs 
                WHERE level IN ('warning', 'error', 'critical')
                ORDER BY created_at DESC 
                LIMIT 20
            ");
            
            $this->view->render('admin/analytics/security', [
                'security_events' => $securityEvents,
                'failed_logins' => $failedLogins,
                'suspicious_activity' => $suspiciousActivity,
                'page_title' => 'Безопасность'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки страницы безопасности: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница производительности
     */
    public function performance() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $slowQueries = Database::fetchAll("
                SELECT * FROM system_logs 
                WHERE level = 'debug' AND message LIKE '%SQL Query:%'
                AND CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) > 1.0
                ORDER BY CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) DESC
                LIMIT 20
            ");
            
            $performanceMetrics = Database::fetchAll("
                SELECT * FROM performance_metrics 
                ORDER BY created_at DESC 
                LIMIT 50
            ");
            
            $apiStats = Database::fetchAll("
                SELECT endpoint, method, AVG(response_time) as avg_time, COUNT(*) as count
                FROM api_statistics 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                GROUP BY endpoint, method
                ORDER BY avg_time DESC
                LIMIT 20
            ");
            
            $this->view->render('admin/analytics/performance', [
                'slow_queries' => $slowQueries,
                'performance_metrics' => $performanceMetrics,
                'api_stats' => $apiStats,
                'page_title' => 'Производительность'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки страницы производительности: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Очистка логов
     */
    public function clearLogs() {
        try {
            if (!$this->isPost()) {
                $this->redirect('/admin/analytics/logs');
            }
            
            $days = (int)($_POST['days'] ?? 30);
            $this->loggingService->cleanOldLogs($days);
            
            $this->loggingService->info('Логи очищены', [
                'days' => $days,
                'user_id' => $_SESSION['admin_user_id']
            ]);
            
            $this->setFlashMessage('success', "Логи старше {$days} дней успешно очищены");
            $this->redirect('/admin/analytics/logs');
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка очистки логов: ' . $e->getMessage());
            $this->setFlashMessage('error', 'Ошибка при очистке логов');
            $this->redirect('/admin/analytics/logs');
        }
    }
    
    /**
     * Экспорт данных аналитики
     */
    public function export() {
        try {
            $type = $_GET['type'] ?? 'system_stats';
            $format = $_GET['format'] ?? 'json';
            
            $data = [];
            
            switch ($type) {
                case 'system_stats':
                    $data = $this->analyticsService->getSystemStats();
                    break;
                case 'user_activity':
                    $data = $this->analyticsService->getChartData('user_activity', '30d');
                    break;
                case 'security_events':
                    require_once ENGINE_DIR . 'main/db.php';
                    $data = Database::fetchAll("SELECT * FROM security_events ORDER BY created_at DESC LIMIT 1000");
                    break;
                case 'performance_metrics':
                    require_once ENGINE_DIR . 'main/db.php';
                    $data = Database::fetchAll("SELECT * FROM performance_metrics ORDER BY created_at DESC LIMIT 1000");
                    break;
            }
            
            $filename = "analytics_{$type}_" . date('Y-m-d_H-i-s');
            
            if ($format === 'csv') {
                $this->exportToCsv($data, $filename);
            } else {
                $this->exportToJson($data, $filename);
            }
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка экспорта данных: ' . $e->getMessage());
            $this->redirect('/admin/analytics');
        }
    }
    
    /**
     * Экспорт в CSV
     */
    private function exportToCsv($data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            // Заголовки
            fputcsv($output, array_keys($data[0]));
            
            // Данные
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Экспорт в JSON
     */
    private function exportToJson($data, $filename) {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.json"');
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * AJAX: Получение данных для графиков
     */
    public function getChartData() {
        try {
            if (!$this->isAjax()) {
                $this->jsonResponse(['error' => 'Invalid request'], 400);
                return;
            }
            
            $chartType = $_GET['type'] ?? 'user_activity';
            $period = $_GET['period'] ?? '7d';
            
            $data = $this->analyticsService->getChartData($chartType, $period);
            
            $this->jsonResponse(['success' => true, 'data' => $data]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения данных графика: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * AJAX: Получение данных мониторинга
     */
    public function getMonitoringData() {
        try {
            if (!$this->isAjax()) {
                $this->jsonResponse(['error' => 'Invalid request'], 400);
                return;
            }
            
            $data = $this->monitoringService->runSystemCheck();
            
            $this->jsonResponse(['success' => true, 'data' => $data]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения данных мониторинга: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * AJAX: Очистка предупреждений
     */
    public function clearAlerts() {
        try {
            if (!$this->isAjax() || !$this->isPost()) {
                $this->jsonResponse(['error' => 'Invalid request'], 400);
                return;
            }
            
            $this->monitoringService->clearAlerts();
            
            $this->jsonResponse(['success' => true]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка очистки предупреждений: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
} 