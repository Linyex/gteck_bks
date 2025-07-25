<?php

/**
 * Сервис мониторинга
 * Обеспечивает мониторинг состояния системы и уведомления о проблемах
 */
class MonitoringService {
    
    private $alerts = [];
    private $thresholds = [];
    private $notificationService;
    
    public function __construct() {
        $this->loadThresholds();
        
        // Инициализируем NotificationService только если он доступен
        try {
            require_once ENGINE_DIR . 'services/NotificationService.php';
            $this->notificationService = new NotificationService();
        } catch (Exception $e) {
            $this->notificationService = null;
        }
    }
    
    /**
     * Загружает пороговые значения
     */
    private function loadThresholds() {
        $this->thresholds = [
            'cpu_usage' => 80,
            'memory_usage' => 85,
            'disk_usage' => 90,
            'error_rate' => 5,
            'response_time' => 2.0,
            'failed_logins' => 10,
            'concurrent_users' => 100
        ];
    }
    
    /**
     * Проводит полную проверку системы
     */
    public function runSystemCheck() {
        $checks = [
            'performance' => $this->checkPerformance(),
            'security' => $this->checkSecurity(),
            'storage' => $this->checkStorage(),
            'database' => $this->checkDatabase(),
            'logs' => $this->checkLogs(),
            'backups' => $this->checkBackups()
        ];
        
        $overallStatus = $this->calculateOverallStatus($checks);
        
        return [
            'status' => $overallStatus,
            'checks' => $checks,
            'alerts' => $this->alerts,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Проверка производительности
     */
    private function checkPerformance() {
        $status = 'ok';
        $issues = [];
        
        // Проверка времени отклика
        $avgResponseTime = $this->getAverageResponseTime();
        if ($avgResponseTime > $this->thresholds['response_time']) {
            $status = 'warning';
            $issues[] = "Среднее время отклика: {$avgResponseTime}с (порог: {$this->thresholds['response_time']}с)";
            $this->addAlert('performance', 'high_response_time', "Высокое время отклика: {$avgResponseTime}с");
        }
        
        // Проверка медленных запросов
        $slowQueries = $this->getSlowQueries();
        if (count($slowQueries) > 0) {
            $status = 'warning';
            $issues[] = "Обнаружено " . count($slowQueries) . " медленных запросов";
            $this->addAlert('performance', 'slow_queries', "Обнаружены медленные запросы");
        }
        
        // Проверка использования памяти
        $memoryUsage = $this->getMemoryUsage();
        if ($memoryUsage > $this->thresholds['memory_usage']) {
            $status = 'critical';
            $issues[] = "Использование памяти: {$memoryUsage}% (порог: {$this->thresholds['memory_usage']}%)";
            $this->addAlert('performance', 'high_memory_usage', "Высокое использование памяти: {$memoryUsage}%");
        }
        
        return [
            'status' => $status,
            'issues' => $issues,
            'metrics' => [
                'avg_response_time' => $avgResponseTime,
                'memory_usage' => $memoryUsage,
                'slow_queries_count' => count($slowQueries)
            ]
        ];
    }
    
    /**
     * Проверка безопасности
     */
    private function checkSecurity() {
        $status = 'ok';
        $issues = [];
        
        // Проверка неудачных попыток входа
        $failedLogins = $this->getFailedLoginsCount();
        if ($failedLogins > $this->thresholds['failed_logins']) {
            $status = 'warning';
            $issues[] = "Неудачных попыток входа за 24ч: {$failedLogins} (порог: {$this->thresholds['failed_logins']})";
            $this->addAlert('security', 'failed_logins', "Много неудачных попыток входа: {$failedLogins}");
        }
        
        // Проверка подозрительной активности
        $suspiciousActivity = $this->getSuspiciousActivity();
        if (count($suspiciousActivity) > 0) {
            $status = 'critical';
            $issues[] = "Обнаружена подозрительная активность";
            $this->addAlert('security', 'suspicious_activity', "Обнаружена подозрительная активность");
        }
        
        // Проверка ошибок безопасности
        $securityErrors = $this->getSecurityErrors();
        if (count($securityErrors) > 0) {
            $status = 'critical';
            $issues[] = "Обнаружены ошибки безопасности";
            $this->addAlert('security', 'security_errors', "Обнаружены ошибки безопасности");
        }
        
        return [
            'status' => $status,
            'issues' => $issues,
            'metrics' => [
                'failed_logins_24h' => $failedLogins,
                'suspicious_activity_count' => count($suspiciousActivity),
                'security_errors_count' => count($securityErrors)
            ]
        ];
    }
    
    /**
     * Проверка хранилища
     */
    private function checkStorage() {
        $status = 'ok';
        $issues = [];
        
        // Проверка использования диска
        $diskUsage = $this->getDiskUsage();
        if ($diskUsage > $this->thresholds['disk_usage']) {
            $status = 'critical';
            $issues[] = "Использование диска: {$diskUsage}% (порог: {$this->thresholds['disk_usage']}%)";
            $this->addAlert('storage', 'high_disk_usage', "Высокое использование диска: {$diskUsage}%");
        }
        
        // Проверка размера логов
        $logSize = $this->getLogSize();
        if ($logSize > 100 * 1024 * 1024) { // 100MB
            $status = 'warning';
            $issues[] = "Размер логов: " . $this->formatBytes($logSize);
            $this->addAlert('storage', 'large_logs', "Большой размер логов");
        }
        
        // Проверка размера загрузок
        $uploadSize = $this->getUploadSize();
        if ($uploadSize > 500 * 1024 * 1024) { // 500MB
            $status = 'warning';
            $issues[] = "Размер загрузок: " . $this->formatBytes($uploadSize);
            $this->addAlert('storage', 'large_uploads', "Большой размер загрузок");
        }
        
        return [
            'status' => $status,
            'issues' => $issues,
            'metrics' => [
                'disk_usage' => $diskUsage,
                'log_size' => $logSize,
                'upload_size' => $uploadSize
            ]
        ];
    }
    
    /**
     * Проверка базы данных
     */
    private function checkDatabase() {
        $status = 'ok';
        $issues = [];
        
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверка подключения
            $connection = Database::getConnection();
            if (!$connection) {
                $status = 'critical';
                $issues[] = "Нет подключения к базе данных";
                $this->addAlert('database', 'connection_failed', "Ошибка подключения к БД");
                return ['status' => $status, 'issues' => $issues];
            }
            
            // Проверка размера БД
            $dbSize = $this->getDatabaseSize();
            if ($dbSize > 100 * 1024 * 1024) { // 100MB
                $status = 'warning';
                $issues[] = "Размер БД: " . $this->formatBytes($dbSize);
                $this->addAlert('database', 'large_database', "Большой размер БД");
            }
            
            // Проверка медленных запросов
            $slowQueries = $this->getDatabaseSlowQueries();
            if (count($slowQueries) > 0) {
                $status = 'warning';
                $issues[] = "Медленные запросы в БД";
                $this->addAlert('database', 'slow_queries', "Медленные запросы в БД");
            }
            
            return [
                'status' => $status,
                'issues' => $issues,
                'metrics' => [
                    'database_size' => $dbSize,
                    'slow_queries_count' => count($slowQueries)
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'critical',
                'issues' => ["Ошибка проверки БД: " . $e->getMessage()]
            ];
        }
    }
    
    /**
     * Проверка логов
     */
    private function checkLogs() {
        $status = 'ok';
        $issues = [];
        
        // Проверка ошибок в логах
        $errorCount = $this->getErrorCount();
        if ($errorCount > 50) {
            $status = 'warning';
            $issues[] = "Много ошибок в логах: {$errorCount}";
            $this->addAlert('logs', 'many_errors', "Много ошибок в логах: {$errorCount}");
        }
        
        // Проверка критических ошибок
        $criticalCount = $this->getCriticalErrorCount();
        if ($criticalCount > 0) {
            $status = 'critical';
            $issues[] = "Критические ошибки: {$criticalCount}";
            $this->addAlert('logs', 'critical_errors', "Критические ошибки: {$criticalCount}");
        }
        
        return [
            'status' => $status,
            'issues' => $issues,
            'metrics' => [
                'error_count_24h' => $errorCount,
                'critical_error_count_24h' => $criticalCount
            ]
        ];
    }
    
    /**
     * Проверка резервных копий
     */
    private function checkBackups() {
        $status = 'ok';
        $issues = [];
        
        try {
            require_once ENGINE_DIR . 'services/BackupService.php';
            $backupService = new BackupService();
            
            $backups = $backupService->getBackupsList();
            $recentBackups = array_filter($backups, function($backup) {
                return strtotime($backup['created_at']) > strtotime('-7 days');
            });
            
            if (count($recentBackups) === 0) {
                $status = 'critical';
                $issues[] = "Нет резервных копий за последние 7 дней";
                $this->addAlert('backups', 'no_recent_backups', "Нет резервных копий за неделю");
            } elseif (count($recentBackups) < 3) {
                $status = 'warning';
                $issues[] = "Мало резервных копий за неделю: " . count($recentBackups);
                $this->addAlert('backups', 'few_backups', "Мало резервных копий");
            }
            
            return [
                'status' => $status,
                'issues' => $issues,
                'metrics' => [
                    'total_backups' => count($backups),
                    'recent_backups' => count($recentBackups),
                    'last_backup' => count($backups) > 0 ? $backups[0]['created_at'] : null
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'critical',
                'issues' => ["Ошибка проверки бэкапов: " . $e->getMessage()]
            ];
        }
    }
    
    /**
     * Вычисляет общий статус системы
     */
    private function calculateOverallStatus($checks) {
        $statuses = array_column($checks, 'status');
        
        if (in_array('critical', $statuses)) {
            return 'critical';
        } elseif (in_array('warning', $statuses)) {
            return 'warning';
        } else {
            return 'ok';
        }
    }
    
    /**
     * Добавляет предупреждение
     */
    private function addAlert($category, $type, $message) {
        $this->alerts[] = [
            'category' => $category,
            'type' => $type,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Отправляем уведомление
        if ($this->notificationService) {
            $this->notificationService->send('system_alert', ['admin'], [
                'category' => $category,
                'type' => $type,
                'message' => $message
            ]);
        }
    }
    
    /**
     * Получает среднее время отклика
     */
    private function getAverageResponseTime() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $result = Database::fetchOne("
                SELECT AVG(CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3))) as avg_time 
                FROM system_logs 
                WHERE level = 'debug' AND message LIKE '%SQL Query:%' 
                AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ");
            
            return round($result['avg_time'] ?? 0, 3);
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Получает медленные запросы
     */
    private function getSlowQueries() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            return Database::fetchAll("
                SELECT message, JSON_EXTRACT(context, '$.execution_time') as execution_time
                FROM system_logs 
                WHERE level = 'debug' AND message LIKE '%SQL Query:%' 
                AND CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) > 1.0
                AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
                ORDER BY CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получает использование памяти
     */
    private function getMemoryUsage() {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        
        if ($memoryLimit !== '-1') {
            $memoryLimitBytes = $this->parseMemoryLimit($memoryLimit);
            return round(($memoryUsage / $memoryLimitBytes) * 100, 2);
        }
        
        return 0;
    }
    
    /**
     * Парсит лимит памяти
     */
    private function parseMemoryLimit($limit) {
        $unit = strtolower(substr($limit, -1));
        $value = (int)substr($limit, 0, -1);
        
        switch ($unit) {
            case 'k': return $value * 1024;
            case 'm': return $value * 1024 * 1024;
            case 'g': return $value * 1024 * 1024 * 1024;
            default: return $value;
        }
    }
    
    /**
     * Получает количество неудачных попыток входа
     */
    private function getFailedLoginsCount() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM system_logs 
                WHERE level = 'info' AND message LIKE '%Login Attempt: FAILED%' 
                AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Получает подозрительную активность
     */
    private function getSuspiciousActivity() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            return Database::fetchAll("
                SELECT * FROM system_logs 
                WHERE level IN ('warning', 'error', 'critical') 
                AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
                ORDER BY created_at DESC
                LIMIT 20
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получает ошибки безопасности
     */
    private function getSecurityErrors() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            return Database::fetchAll("
                SELECT * FROM system_logs 
                WHERE level = 'error' 
                AND (message LIKE '%security%' OR message LIKE '%auth%' OR message LIKE '%login%')
                AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
                ORDER BY created_at DESC
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получает использование диска
     */
    private function getDiskUsage() {
        $totalSpace = disk_total_space('.');
        $freeSpace = disk_free_space('.');
        $usedSpace = $totalSpace - $freeSpace;
        
        return round(($usedSpace / $totalSpace) * 100, 2);
    }
    
    /**
     * Получает размер логов
     */
    private function getLogSize() {
        $size = 0;
        $logDir = 'logs/';
        
        if (is_dir($logDir)) {
            $files = glob($logDir . '*.log');
            foreach ($files as $file) {
                $size += filesize($file);
            }
        }
        
        return $size;
    }
    
    /**
     * Получает размер загрузок
     */
    private function getUploadSize() {
        $size = 0;
        $uploadDir = 'uploads/';
        
        if (is_dir($uploadDir)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploadDir));
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        }
        
        return $size;
    }
    
    /**
     * Получает размер базы данных
     */
    private function getDatabaseSize() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $result = Database::fetchOne("
                SELECT SUM(data_length + index_length) as size
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
            ");
            
            return $result['size'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Получает медленные запросы БД
     */
    private function getDatabaseSlowQueries() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            return Database::fetchAll("
                SELECT * FROM system_logs 
                WHERE level = 'debug' AND message LIKE '%SQL Query:%' 
                AND CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) > 2.0
                AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
                ORDER BY CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получает количество ошибок
     */
    private function getErrorCount() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM system_logs 
                WHERE level = 'error' 
                AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Получает количество критических ошибок
     */
    private function getCriticalErrorCount() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM system_logs 
                WHERE level = 'critical' 
                AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Форматирует размер в байтах
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Получает все предупреждения
     */
    public function getAlerts() {
        return $this->alerts;
    }
    
    /**
     * Очищает предупреждения
     */
    public function clearAlerts() {
        $this->alerts = [];
    }
} 