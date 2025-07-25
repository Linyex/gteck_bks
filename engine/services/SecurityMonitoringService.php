<?php

class SecurityMonitoringService {
    private $db;
    private $config;
    private $advancedLogging;
    
    public function __construct() {
        require_once __DIR__ . '/../main/db.php';
        require_once __DIR__ . '/AdvancedLoggingService.php';
        
        $this->db = new Database();
        $this->config = require(__DIR__ . '/../../application/config.php');
        $this->advancedLogging = new AdvancedLoggingService();
    }
    
    /**
     * Мониторинг в реальном времени
     */
    public function monitorRealTime() {
        try {
            $threats = [];
            
            // Проверяем подозрительную активность
            $suspiciousActivity = $this->checkSuspiciousActivity();
            if (!empty($suspiciousActivity)) {
                $threats[] = [
                    'type' => 'suspicious_activity',
                    'severity' => 'high',
                    'description' => 'Обнаружена подозрительная активность',
                    'details' => $suspiciousActivity
                ];
            }
            
            // Проверяем неудачные попытки входа
            $failedLogins = $this->checkFailedLogins();
            if (!empty($failedLogins)) {
                $threats[] = [
                    'type' => 'failed_logins',
                    'severity' => 'medium',
                    'description' => 'Множественные неудачные попытки входа',
                    'details' => $failedLogins
                ];
            }
            
            // Проверяем необычную активность
            $unusualActivity = $this->checkUnusualActivity();
            if (!empty($unusualActivity)) {
                $threats[] = [
                    'type' => 'unusual_activity',
                    'severity' => 'medium',
                    'description' => 'Обнаружена необычная активность',
                    'details' => $unusualActivity
                ];
            }
            
            // Проверяем попытки SQL инъекций
            $sqlInjectionAttempts = $this->checkSqlInjectionAttempts();
            if (!empty($sqlInjectionAttempts)) {
                $threats[] = [
                    'type' => 'sql_injection',
                    'severity' => 'critical',
                    'description' => 'Обнаружены попытки SQL инъекций',
                    'details' => $sqlInjectionAttempts
                ];
            }
            
            // Проверяем попытки XSS атак
            $xssAttempts = $this->checkXssAttempts();
            if (!empty($xssAttempts)) {
                $threats[] = [
                    'type' => 'xss_attack',
                    'severity' => 'critical',
                    'description' => 'Обнаружены попытки XSS атак',
                    'details' => $xssAttempts
                ];
            }
            
            // Логируем угрозы
            foreach ($threats as $threat) {
                $this->advancedLogging->alert(
                    "Security threat detected: {$threat['description']}",
                    $threat,
                    null
                );
            }
            
            return $threats;
            
        } catch (Exception $e) {
            $this->advancedLogging->error("SecurityMonitoringService: Ошибка мониторинга", ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Проверка подозрительной активности
     */
    private function checkSuspiciousActivity() {
        try {
            $suspicious = [];
            
            // Проверяем количество действий за последние 5 минут
            $recentActions = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)"
            );
            
            if (($recentActions['count'] ?? 0) > 100) {
                $suspicious[] = 'too_many_actions';
            }
            
            // Проверяем активность с одного IP
            $ipActivity = $this->db->fetchAll(
                "SELECT ip_address, COUNT(*) as count 
                 FROM security_audit_log 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE) 
                 GROUP BY ip_address 
                 HAVING count > 50"
            );
            
            foreach ($ipActivity as $activity) {
                $suspicious[] = "high_activity_from_ip: {$activity['ip_address']}";
            }
            
            return $suspicious;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Проверка неудачных попыток входа
     */
    private function checkFailedLogins() {
        try {
            $failedLogins = $this->db->fetchAll(
                "SELECT ip_address, COUNT(*) as count 
                 FROM security_audit_log 
                 WHERE action_type = 'login_failed' 
                 AND created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE) 
                 GROUP BY ip_address 
                 HAVING count > 5"
            );
            
            return $failedLogins;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Проверка необычной активности
     */
    private function checkUnusualActivity() {
        try {
            $unusual = [];
            
            // Проверяем активность в нерабочее время
            $hour = (int)date('H');
            if ($hour < 6 || $hour > 23) {
                $nightActivity = $this->db->fetchOne(
                    "SELECT COUNT(*) as count FROM security_audit_log 
                     WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)"
                );
                
                if (($nightActivity['count'] ?? 0) > 20) {
                    $unusual[] = 'high_night_activity';
                }
            }
            
            // Проверяем активность с новых IP адресов
            $newIPs = $this->db->fetchAll(
                "SELECT DISTINCT ip_address 
                 FROM security_audit_log 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) 
                 AND ip_address NOT IN (
                     SELECT DISTINCT ip_address 
                     FROM security_audit_log 
                     WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)
                 )"
            );
            
            if (count($newIPs) > 5) {
                $unusual[] = 'many_new_ips';
            }
            
            return $unusual;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Проверка попыток SQL инъекций
     */
    private function checkSqlInjectionAttempts() {
        $suspiciousPatterns = [
            'UNION SELECT',
            'DROP TABLE',
            'DELETE FROM',
            'INSERT INTO',
            'UPDATE SET',
            'OR 1=1',
            'OR 1=0',
            '--',
            '/*',
            '*/',
            'xp_cmdshell',
            'exec(',
            'eval(',
            'system('
        ];
        
        $attempts = [];
        
        // Проверяем GET и POST параметры
        foreach ($_GET as $key => $value) {
            foreach ($suspiciousPatterns as $pattern) {
                if (stripos($value, $pattern) !== false) {
                    $attempts[] = [
                        'type' => 'sql_injection',
                        'parameter' => $key,
                        'value' => $value,
                        'pattern' => $pattern
                    ];
                }
            }
        }
        
        foreach ($_POST as $key => $value) {
            foreach ($suspiciousPatterns as $pattern) {
                if (stripos($value, $pattern) !== false) {
                    $attempts[] = [
                        'type' => 'sql_injection',
                        'parameter' => $key,
                        'value' => $value,
                        'pattern' => $pattern
                    ];
                }
            }
        }
        
        return $attempts;
    }
    
    /**
     * Проверка попыток XSS атак
     */
    private function checkXssAttempts() {
        $xssPatterns = [
            '<script',
            'javascript:',
            'onload=',
            'onerror=',
            'onclick=',
            'onmouseover=',
            'alert(',
            'confirm(',
            'prompt(',
            'document.cookie',
            'window.location',
            'eval(',
            'innerHTML',
            'outerHTML'
        ];
        
        $attempts = [];
        
        // Проверяем GET и POST параметры
        foreach ($_GET as $key => $value) {
            foreach ($xssPatterns as $pattern) {
                if (stripos($value, $pattern) !== false) {
                    $attempts[] = [
                        'type' => 'xss_attack',
                        'parameter' => $key,
                        'value' => $value,
                        'pattern' => $pattern
                    ];
                }
            }
        }
        
        foreach ($_POST as $key => $value) {
            foreach ($xssPatterns as $pattern) {
                if (stripos($value, $pattern) !== false) {
                    $attempts[] = [
                        'type' => 'xss_attack',
                        'parameter' => $key,
                        'value' => $value,
                        'pattern' => $pattern
                    ];
                }
            }
        }
        
        return $attempts;
    }
    
    /**
     * Блокировка подозрительных IP
     */
    public function blockSuspiciousIPs($threats) {
        try {
            $blockedIPs = [];
            
            foreach ($threats as $threat) {
                if ($threat['type'] === 'failed_logins' || $threat['type'] === 'suspicious_activity') {
                    foreach ($threat['details'] as $detail) {
                        $ipAddress = $detail['ip_address'] ?? null;
                        
                        if ($ipAddress && !$this->isIPBlocked($ipAddress)) {
                            $this->db->execute(
                                "INSERT INTO ip_blacklist (ip_address, reason, created_by) 
                                 VALUES (?, ?, ?)",
                                [$ipAddress, "Auto-blocked due to {$threat['type']}", 1]
                            );
                            
                            $blockedIPs[] = $ipAddress;
                            
                            $this->advancedLogging->alert(
                                "IP address auto-blocked: $ipAddress",
                                [
                                    'ip_address' => $ipAddress,
                                    'reason' => $threat['type'],
                                    'threat_details' => $threat
                                ]
                            );
                        }
                    }
                }
            }
            
            return $blockedIPs;
            
        } catch (Exception $e) {
            $this->advancedLogging->error("SecurityMonitoringService: Ошибка блокировки IP", ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Проверка, заблокирован ли IP
     */
    private function isIPBlocked($ipAddress) {
        try {
            $result = $this->db->fetchOne(
                "SELECT id FROM ip_blacklist WHERE ip_address = ?",
                [$ipAddress]
            );
            
            return !empty($result);
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Получение статистики безопасности
     */
    public function getSecurityStats($days = 30) {
        try {
            $stats = [];
            
            // Общее количество угроз
            $threatsCount = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM advanced_logs 
                 WHERE level IN ('alert', 'critical') 
                 AND created_at > DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
            
            $stats['total_threats'] = $threatsCount['count'] ?? 0;
            
            // Угрозы по типам
            $threatsByType = $this->db->fetchAll(
                "SELECT 
                    CASE 
                        WHEN message LIKE '%SQL injection%' THEN 'sql_injection'
                        WHEN message LIKE '%XSS%' THEN 'xss_attack'
                        WHEN message LIKE '%failed login%' THEN 'failed_login'
                        WHEN message LIKE '%suspicious%' THEN 'suspicious_activity'
                        ELSE 'other'
                    END as threat_type,
                    COUNT(*) as count
                 FROM advanced_logs 
                 WHERE level IN ('alert', 'critical') 
                 AND created_at > DATE_SUB(NOW(), INTERVAL ? DAY)
                 GROUP BY threat_type",
                [$days]
            );
            
            $stats['threats_by_type'] = $threatsByType;
            
            // Заблокированные IP
            $blockedIPs = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM ip_blacklist 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY)",
                [$days]
            );
            
            $stats['blocked_ips'] = $blockedIPs['count'] ?? 0;
            
            // Активность по часам
            $hourlyActivity = $this->db->fetchAll(
                "SELECT HOUR(created_at) as hour, COUNT(*) as count 
                 FROM security_audit_log 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY) 
                 GROUP BY HOUR(created_at) 
                 ORDER BY hour",
                [$days]
            );
            
            $stats['hourly_activity'] = $hourlyActivity;
            
            return $stats;
            
        } catch (Exception $e) {
            $this->advancedLogging->error("SecurityMonitoringService: Ошибка получения статистики", ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Создание отчета о безопасности
     */
    public function generateSecurityReport($days = 30) {
        try {
            $stats = $this->getSecurityStats($days);
            $threats = $this->monitorRealTime();
            
            $report = [
                'generated_at' => date('Y-m-d H:i:s'),
                'period_days' => $days,
                'summary' => [
                    'total_threats' => $stats['total_threats'] ?? 0,
                    'blocked_ips' => $stats['blocked_ips'] ?? 0,
                    'current_threats' => count($threats)
                ],
                'threats_by_type' => $stats['threats_by_type'] ?? [],
                'hourly_activity' => $stats['hourly_activity'] ?? [],
                'current_threats' => $threats,
                'recommendations' => $this->generateRecommendations($stats, $threats)
            ];
            
            return $report;
            
        } catch (Exception $e) {
            $this->advancedLogging->error("SecurityMonitoringService: Ошибка создания отчета", ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Генерация рекомендаций
     */
    private function generateRecommendations($stats, $threats) {
        $recommendations = [];
        
        if (($stats['total_threats'] ?? 0) > 10) {
            $recommendations[] = 'Высокий уровень угроз. Рекомендуется усилить мониторинг безопасности.';
        }
        
        if (($stats['blocked_ips'] ?? 0) > 5) {
            $recommendations[] = 'Много заблокированных IP. Возможно, требуется дополнительная защита.';
        }
        
        if (!empty($threats)) {
            $recommendations[] = 'Обнаружены активные угрозы. Требуется немедленное вмешательство.';
        }
        
        if (empty($recommendations)) {
            $recommendations[] = 'Система безопасности работает стабильно.';
        }
        
        return $recommendations;
    }
} 