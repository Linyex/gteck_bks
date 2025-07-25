<?php

class AdvancedLoggingService {
    private $db;
    private $config;
    private $logLevels = [
        'emergency' => 0,
        'alert' => 1,
        'critical' => 2,
        'error' => 3,
        'warning' => 4,
        'notice' => 5,
        'info' => 6,
        'debug' => 7
    ];
    
    public function __construct() {
        require_once __DIR__ . '/../main/db.php';
        $this->db = new Database();
        $this->config = require(__DIR__ . '/../../application/config.php');
    }
    
    /**
     * Логирование с различными уровнями
     */
    public function log($level, $message, $context = [], $userId = null) {
        try {
            $data = [
                'level' => $level,
                'message' => $message,
                'context' => json_encode($context),
                'user_id' => $userId,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'session_id' => session_id(),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->execute(
                "INSERT INTO advanced_logs (level, message, context, user_id, ip_address, user_agent, session_id, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                array_values($data)
            );
            
            // Также записываем в файл для критических ошибок
            if (in_array($level, ['emergency', 'alert', 'critical', 'error'])) {
                $this->writeToFile($level, $message, $context);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("AdvancedLoggingService: Ошибка логирования: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Запись в файл
     */
    private function writeToFile($level, $message, $context = []) {
        $logDir = __DIR__ . '/../../logs/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . date('Y-m-d') . '_' . $level . '.log';
        $logEntry = sprintf(
            "[%s] %s: %s %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            !empty($context) ? json_encode($context) : ''
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Логирование экстренных ситуаций
     */
    public function emergency($message, $context = [], $userId = null) {
        return $this->log('emergency', $message, $context, $userId);
    }
    
    /**
     * Логирование предупреждений
     */
    public function alert($message, $context = [], $userId = null) {
        return $this->log('alert', $message, $context, $userId);
    }
    
    /**
     * Логирование критических ошибок
     */
    public function critical($message, $context = [], $userId = null) {
        return $this->log('critical', $message, $context, $userId);
    }
    
    /**
     * Логирование ошибок
     */
    public function error($message, $context = [], $userId = null) {
        return $this->log('error', $message, $context, $userId);
    }
    
    /**
     * Логирование предупреждений
     */
    public function warning($message, $context = [], $userId = null) {
        return $this->log('warning', $message, $context, $userId);
    }
    
    /**
     * Логирование уведомлений
     */
    public function notice($message, $context = [], $userId = null) {
        return $this->log('notice', $message, $context, $userId);
    }
    
    /**
     * Логирование информации
     */
    public function info($message, $context = [], $userId = null) {
        return $this->log('info', $message, $context, $userId);
    }
    
    /**
     * Логирование отладки
     */
    public function debug($message, $context = [], $userId = null) {
        return $this->log('debug', $message, $context, $userId);
    }
    
    /**
     * Логирование SQL запросов
     */
    public function logSqlQuery($query, $params = [], $executionTime = null, $userId = null) {
        $context = [
            'query' => $query,
            'params' => $params,
            'execution_time' => $executionTime,
            'type' => 'sql_query'
        ];
        
        return $this->log('debug', 'SQL Query executed', $context, $userId);
    }
    
    /**
     * Логирование производительности
     */
    public function logPerformance($operation, $executionTime, $memoryUsage = null, $userId = null) {
        $context = [
            'operation' => $operation,
            'execution_time' => $executionTime,
            'memory_usage' => $memoryUsage,
            'type' => 'performance'
        ];
        
        $level = $executionTime > 1.0 ? 'warning' : 'info';
        return $this->log($level, "Performance: $operation", $context, $userId);
    }
    
    /**
     * Логирование безопасности
     */
    public function logSecurity($event, $details = [], $userId = null) {
        $context = array_merge($details, ['type' => 'security']);
        return $this->log('warning', "Security event: $event", $context, $userId);
    }
    
    /**
     * Получение логов с фильтрацией
     */
    public function getLogs($filters = [], $limit = 100, $offset = 0) {
        try {
            $whereConditions = [];
            $params = [];
            
            if (!empty($filters['level'])) {
                $whereConditions[] = "level = ?";
                $params[] = $filters['level'];
            }
            
            if (!empty($filters['user_id'])) {
                $whereConditions[] = "user_id = ?";
                $params[] = $filters['user_id'];
            }
            
            if (!empty($filters['date_from'])) {
                $whereConditions[] = "created_at >= ?";
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $whereConditions[] = "created_at <= ?";
                $params[] = $filters['date_to'];
            }
            
            if (!empty($filters['search'])) {
                $whereConditions[] = "message LIKE ?";
                $params[] = '%' . $filters['search'] . '%';
            }
            
            $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";
            
            $params[] = $limit;
            $params[] = $offset;
            
            return $this->db->fetchAll(
                "SELECT al.*, u.user_fio 
                 FROM advanced_logs al 
                 LEFT JOIN users u ON al.user_id = u.user_id 
                 $whereClause 
                 ORDER BY al.created_at DESC 
                 LIMIT ? OFFSET ?",
                $params
            );
            
        } catch (Exception $e) {
            error_log("AdvancedLoggingService: Ошибка получения логов: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Получение статистики логов
     */
    public function getLogStats($days = 30) {
        try {
            $stats = [];
            
            // Статистика по уровням
            $levelStats = $this->db->fetchAll(
                "SELECT level, COUNT(*) as count 
                 FROM advanced_logs 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY) 
                 GROUP BY level",
                [$days]
            );
            
            foreach ($levelStats as $stat) {
                $stats['levels'][$stat['level']] = $stat['count'];
            }
            
            // Общее количество логов
            $totalLogs = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM advanced_logs WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
            
            $stats['total'] = $totalLogs['count'] ?? 0;
            
            // Статистика по пользователям
            $userStats = $this->db->fetchAll(
                "SELECT user_id, COUNT(*) as count 
                 FROM advanced_logs 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY) AND user_id IS NOT NULL 
                 GROUP BY user_id 
                 ORDER BY count DESC 
                 LIMIT 10",
                [$days]
            );
            
            $stats['top_users'] = $userStats;
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("AdvancedLoggingService: Ошибка получения статистики: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Ротация логов
     */
    public function rotateLogs($daysToKeep = 90) {
        try {
            // Удаляем старые логи из БД
            $deleted = $this->db->execute(
                "DELETE FROM advanced_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$daysToKeep]
            );
            
            // Удаляем старые файлы логов
            $logDir = __DIR__ . '/../../logs/';
            if (is_dir($logDir)) {
                $files = glob($logDir . '*.log');
                $cutoffTime = time() - ($daysToKeep * 24 * 60 * 60);
                
                foreach ($files as $file) {
                    if (filemtime($file) < $cutoffTime) {
                        unlink($file);
                    }
                }
            }
            
            return $deleted;
            
        } catch (Exception $e) {
            error_log("AdvancedLoggingService: Ошибка ротации логов: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Экспорт логов
     */
    public function exportLogs($filters = [], $format = 'json') {
        try {
            $logs = $this->getLogs($filters, 10000, 0);
            
            if ($format === 'csv') {
                return $this->exportToCSV($logs);
            } else {
                return json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            
        } catch (Exception $e) {
            error_log("AdvancedLoggingService: Ошибка экспорта логов: " . $e->getMessage());
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
} 