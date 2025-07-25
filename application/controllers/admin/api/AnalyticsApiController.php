<?php

/**
 * API контроллер для аналитики
 */
class AnalyticsApiController extends BaseApiController {
    
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
     * GET /api/analytics/stats
     * Получение общей статистики системы
     */
    public function getStats() {
        try {
            $stats = $this->analyticsService->getSystemStats();
            
            $this->jsonResponse([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения статистики: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/monitoring
     * Получение данных мониторинга
     */
    public function getMonitoring() {
        try {
            $data = $this->monitoringService->runSystemCheck();
            
            $this->jsonResponse([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения данных мониторинга: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/charts/{type}
     * Получение данных для графиков
     */
    public function getChartData($type) {
        try {
            $period = $_GET['period'] ?? '7d';
            
            $data = $this->analyticsService->getChartData($type, $period);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $data,
                'type' => $type,
                'period' => $period
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения данных графика: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/users/top
     * Получение топ пользователей
     */
    public function getTopUsers() {
        try {
            $limit = (int)($_GET['limit'] ?? 10);
            
            $users = $this->analyticsService->getTopUsers($limit);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $users
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения топ пользователей: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/actions/popular
     * Получение популярных действий
     */
    public function getPopularActions() {
        try {
            $limit = (int)($_GET['limit'] ?? 10);
            
            $actions = $this->analyticsService->getPopularActions($limit);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $actions
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения популярных действий: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/logs
     * Получение логов
     */
    public function getLogs() {
        try {
            $level = $_GET['level'] ?? null;
            $lines = (int)($_GET['lines'] ?? 100);
            $page = (int)($_GET['page'] ?? 1);
            $perPage = (int)($_GET['per_page'] ?? 50);
            
            $logs = $this->loggingService->getLogs($level, $lines);
            $logStats = $this->loggingService->getLogStats();
            
            // Пагинация
            $total = count($logs);
            $offset = ($page - 1) * $perPage;
            $paginatedLogs = array_slice($logs, $offset, $perPage);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $paginatedLogs,
                'stats' => $logStats,
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения логов: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * DELETE /api/analytics/logs
     * Очистка логов
     */
    public function clearLogs() {
        try {
            $days = (int)($_POST['days'] ?? 30);
            
            $this->loggingService->cleanOldLogs($days);
            
            $this->loggingService->info('Логи очищены через API', [
                'days' => $days,
                'user_id' => $_SESSION['admin_user_id'] ?? null
            ]);
            
            $this->jsonResponse([
                'success' => true,
                'message' => "Логи старше {$days} дней успешно очищены"
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка очистки логов: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/security/events
     * Получение событий безопасности
     */
    public function getSecurityEvents() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $page = (int)($_GET['page'] ?? 1);
            $perPage = (int)($_GET['per_page'] ?? 50);
            $severity = $_GET['severity'] ?? null;
            
            $where = "WHERE 1=1";
            $params = [];
            
            if ($severity) {
                $where .= " AND severity = ?";
                $params[] = $severity;
            }
            
            $offset = ($page - 1) * $perPage;
            
            $events = Database::fetchAll("
                SELECT * FROM security_events 
                $where
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?
            ", array_merge($params, [$perPage, $offset]));
            
            $total = Database::fetchOne("
                SELECT COUNT(*) as count FROM security_events $where
            ", $params)['count'] ?? 0;
            
            $this->jsonResponse([
                'success' => true,
                'data' => $events,
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения событий безопасности: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/performance/metrics
     * Получение метрик производительности
     */
    public function getPerformanceMetrics() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $page = (int)($_GET['page'] ?? 1);
            $perPage = (int)($_GET['per_page'] ?? 50);
            $metricName = $_GET['metric'] ?? null;
            
            $where = "WHERE 1=1";
            $params = [];
            
            if ($metricName) {
                $where .= " AND metric_name = ?";
                $params[] = $metricName;
            }
            
            $offset = ($page - 1) * $perPage;
            
            $metrics = Database::fetchAll("
                SELECT * FROM performance_metrics 
                $where
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?
            ", array_merge($params, [$perPage, $offset]));
            
            $total = Database::fetchOne("
                SELECT COUNT(*) as count FROM performance_metrics $where
            ", $params)['count'] ?? 0;
            
            $this->jsonResponse([
                'success' => true,
                'data' => $metrics,
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения метрик производительности: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/performance/slow-queries
     * Получение медленных запросов
     */
    public function getSlowQueries() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $limit = (int)($_GET['limit'] ?? 20);
            
            $queries = Database::fetchAll("
                SELECT * FROM system_logs 
                WHERE level = 'debug' AND message LIKE '%SQL Query:%'
                AND CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) > 1.0
                ORDER BY CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) DESC
                LIMIT ?
            ", [$limit]);
            
            $this->jsonResponse([
                'success' => true,
                'data' => $queries
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения медленных запросов: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/errors
     * Получение ошибок приложений
     */
    public function getErrors() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $page = (int)($_GET['page'] ?? 1);
            $perPage = (int)($_GET['per_page'] ?? 50);
            $errorType = $_GET['type'] ?? null;
            
            $where = "WHERE 1=1";
            $params = [];
            
            if ($errorType) {
                $where .= " AND error_type = ?";
                $params[] = $errorType;
            }
            
            $offset = ($page - 1) * $perPage;
            
            $errors = Database::fetchAll("
                SELECT * FROM application_errors 
                $where
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?
            ", array_merge($params, [$perPage, $offset]));
            
            $total = Database::fetchOne("
                SELECT COUNT(*) as count FROM application_errors $where
            ", $params)['count'] ?? 0;
            
            $this->jsonResponse([
                'success' => true,
                'data' => $errors,
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения ошибок приложений: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * POST /api/analytics/log
     * Логирование события
     */
    public function logEvent() {
        try {
            $data = $this->getRequestData();
            
            $level = $data['level'] ?? 'info';
            $message = $data['message'] ?? '';
            $context = $data['context'] ?? [];
            
            if (empty($message)) {
                $this->jsonResponse(['error' => 'Message is required'], 400);
                return;
            }
            
            $this->loggingService->log($level, $message, $context);
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Event logged successfully'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка логирования события: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * GET /api/analytics/export/{type}
     * Экспорт данных
     */
    public function exportData($type) {
        try {
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
                default:
                    $this->jsonResponse(['error' => 'Unknown export type'], 400);
                    return;
            }
            
            $filename = "analytics_{$type}_" . date('Y-m-d_H-i-s');
            
            if ($format === 'csv') {
                $this->exportToCsv($data, $filename);
            } else {
                $this->exportToJson($data, $filename);
            }
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка экспорта данных: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
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
} 