<?php

/**
 * Сервис логирования
 * Обеспечивает централизованное логирование с различными уровнями и ротацией
 */
class LoggingService {
    
    private $logDir = 'logs/';
    private $maxLogSize = 10 * 1024 * 1024; // 10MB
    private $maxLogFiles = 5;
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
    private $currentLevel = 'info';
    
    public function __construct($level = 'info') {
        $this->currentLevel = $level;
        
        // Создаем директорию для логов если не существует
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }
    
    /**
     * Логирует сообщение
     */
    public function log($level, $message, $context = []) {
        if (!$this->shouldLog($level)) {
            return;
        }
        
        $logEntry = $this->formatLogEntry($level, $message, $context);
        $this->writeLog($level, $logEntry);
        
        // Также сохраняем в базу данных для аналитики
        $this->saveToDatabase($level, $message, $context);
    }
    
    /**
     * Проверяет нужно ли логировать
     */
    private function shouldLog($level) {
        return $this->logLevels[$level] <= $this->logLevels[$this->currentLevel];
    }
    
    /**
     * Форматирует запись лога
     */
    private function formatLogEntry($level, $message, $context) {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $userId = $_SESSION['admin_user_id'] ?? 'guest';
        $requestUri = $_SERVER['REQUEST_URI'] ?? 'unknown';
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
        
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        
        return "[$timestamp] [$level] [$ip] [$userId] [$requestMethod $requestUri] $message$contextStr\n";
    }
    
    /**
     * Записывает лог в файл
     */
    private function writeLog($level, $logEntry) {
        $logFile = $this->logDir . $level . '.log';
        
        // Проверяем размер файла
        if (file_exists($logFile) && filesize($logFile) > $this->maxLogSize) {
            $this->rotateLog($level);
        }
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Ротирует лог файлы
     */
    private function rotateLog($level) {
        $logFile = $this->logDir . $level . '.log';
        
        // Переименовываем существующие файлы
        for ($i = $this->maxLogFiles - 1; $i >= 1; $i--) {
            $oldFile = $this->logDir . $level . '.' . $i . '.log';
            $newFile = $this->logDir . $level . '.' . ($i + 1) . '.log';
            
            if (file_exists($oldFile)) {
                rename($oldFile, $newFile);
            }
        }
        
        // Переименовываем текущий файл
        if (file_exists($logFile)) {
            rename($logFile, $this->logDir . $level . '.1.log');
        }
    }
    
    /**
     * Сохраняет лог в базу данных
     */
    private function saveToDatabase($level, $message, $context) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute(
                "INSERT INTO system_logs (level, message, context, ip_address, user_agent, user_id, request_uri, request_method, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    $level,
                    $message,
                    json_encode($context),
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null,
                    $_SESSION['admin_user_id'] ?? null,
                    $_SERVER['REQUEST_URI'] ?? null,
                    $_SERVER['REQUEST_METHOD'] ?? null
                ]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки записи в БД
        }
    }
    
    /**
     * Удобные методы для разных уровней логирования
     */
    public function emergency($message, $context = []) {
        $this->log('emergency', $message, $context);
    }
    
    public function alert($message, $context = []) {
        $this->log('alert', $message, $context);
    }
    
    public function critical($message, $context = []) {
        $this->log('critical', $message, $context);
    }
    
    public function error($message, $context = []) {
        $this->log('error', $message, $context);
    }
    
    public function warning($message, $context = []) {
        $this->log('warning', $message, $context);
    }
    
    public function notice($message, $context = []) {
        $this->log('notice', $message, $context);
    }
    
    public function info($message, $context = []) {
        $this->log('info', $message, $context);
    }
    
    public function debug($message, $context = []) {
        $this->log('debug', $message, $context);
    }
    
    /**
     * Логирует исключение
     */
    public function logException($exception, $context = []) {
        $message = $exception->getMessage();
        $trace = $exception->getTraceAsString();
        
        $context['file'] = $exception->getFile();
        $context['line'] = $exception->getLine();
        $context['trace'] = $trace;
        
        $this->error($message, $context);
    }
    
    /**
     * Логирует SQL запросы
     */
    public function logQuery($query, $params = [], $executionTime = null) {
        $context = [
            'params' => $params,
            'execution_time' => $executionTime
        ];
        
        $this->debug("SQL Query: $query", $context);
    }
    
    /**
     * Логирует действия пользователя
     */
    public function logUserAction($action, $details = []) {
        $context = [
            'action' => $action,
            'details' => $details,
            'session_id' => session_id()
        ];
        
        $this->info("User Action: $action", $context);
    }
    
    /**
     * Логирует попытки входа
     */
    public function logLoginAttempt($username, $success, $ip = null) {
        $status = $success ? 'SUCCESS' : 'FAILED';
        $context = [
            'username' => $username,
            'ip' => $ip ?: ($_SERVER['REMOTE_ADDR'] ?? 'unknown'),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $this->info("Login Attempt: $status for user '$username'", $context);
    }
    
    /**
     * Получает логи из файла
     */
    public function getLogs($level = null, $lines = 100) {
        $logs = [];
        
        if ($level) {
            $logFile = $this->logDir . $level . '.log';
            if (file_exists($logFile)) {
                $logs[$level] = $this->readLogFile($logFile, $lines);
            }
        } else {
            // Читаем все уровни
            foreach (array_keys($this->logLevels) as $logLevel) {
                $logFile = $this->logDir . $logLevel . '.log';
                if (file_exists($logFile)) {
                    $logs[$logLevel] = $this->readLogFile($logFile, $lines);
                }
            }
        }
        
        return $logs;
    }
    
    /**
     * Читает файл лога
     */
    private function readLogFile($file, $lines) {
        if (!file_exists($file)) {
            return [];
        }
        
        $content = file($file);
        return array_slice($content, -$lines);
    }
    
    /**
     * Очищает старые логи
     */
    public function cleanOldLogs($days = 30) {
        $cutoff = time() - ($days * 24 * 60 * 60);
        
        foreach (array_keys($this->logLevels) as $level) {
            $logFile = $this->logDir . $level . '.log';
            
            if (file_exists($logFile) && filemtime($logFile) < $cutoff) {
                unlink($logFile);
            }
            
            // Очищаем ротированные файлы
            for ($i = 1; $i <= $this->maxLogFiles; $i++) {
                $rotatedFile = $this->logDir . $level . '.' . $i . '.log';
                if (file_exists($rotatedFile) && filemtime($rotatedFile) < $cutoff) {
                    unlink($rotatedFile);
                }
            }
        }
        
        // Очищаем логи из базы данных
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute(
                "DELETE FROM system_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки
        }
    }
    
    /**
     * Получает статистику логов
     */
    public function getLogStats() {
        $stats = [];
        
        foreach (array_keys($this->logLevels) as $level) {
            $logFile = $this->logDir . $level . '.log';
            
            if (file_exists($logFile)) {
                $stats[$level] = [
                    'size' => filesize($logFile),
                    'lines' => count(file($logFile)),
                    'last_modified' => filemtime($logFile)
                ];
            } else {
                $stats[$level] = [
                    'size' => 0,
                    'lines' => 0,
                    'last_modified' => null
                ];
            }
        }
        
        return $stats;
    }
    
    /**
     * Устанавливает уровень логирования
     */
    public function setLevel($level) {
        if (isset($this->logLevels[$level])) {
            $this->currentLevel = $level;
        }
    }
    
    /**
     * Получает текущий уровень логирования
     */
    public function getLevel() {
        return $this->currentLevel;
    }
} 