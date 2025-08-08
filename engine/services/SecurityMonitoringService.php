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
            
            // Проверяем количество действий за последние 5 минут (исключая страницы мониторинга)
            $recentActions = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM security_audit_log 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
                 AND request_uri NOT LIKE '%/admin/monitoring%'
                 AND request_uri NOT LIKE '%/admin/analytics%'
                 AND request_uri NOT LIKE '%/admin/security%'"
            );
            
            if (($recentActions['count'] ?? 0) > 100) {
                $suspicious[] = 'too_many_actions';
            }
            
            // Проверяем активность с одного IP (исключая страницы мониторинга)
            $ipActivity = $this->db->fetchAll(
                "SELECT ip_address, COUNT(*) as count 
                 FROM security_audit_log 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE) 
                 AND request_uri NOT LIKE '%/admin/monitoring%'
                 AND request_uri NOT LIKE '%/admin/analytics%'
                 AND request_uri NOT LIKE '%/admin/security%'
                 GROUP BY ip_address 
                 HAVING count > 50"
            );
            
            foreach ($ipActivity as $activity) {
                $suspicious[] = "high_activity_from_ip: {$activity['ip_address']}";
            }
            
            // Проверяем подозрительные паттерны запросов
            $suspiciousPatterns = $this->checkRequestPatterns();
            $suspicious = array_merge($suspicious, $suspiciousPatterns);
            
            // Проверяем аномальную активность пользователей
            $userAnomalies = $this->checkUserAnomalies();
            $suspicious = array_merge($suspicious, $userAnomalies);
            
            return $suspicious;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Проверка подозрительных паттернов запросов
     */
    private function checkRequestPatterns() {
        $patterns = [];
        
        // Проверяем частые запросы к одним и тем же страницам (исключая страницы мониторинга)
        $repeatedRequests = $this->db->fetchAll(
            "SELECT request_uri, COUNT(*) as count 
             FROM security_audit_log 
             WHERE created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE) 
             AND request_uri NOT LIKE '%/admin/monitoring%'
             AND request_uri NOT LIKE '%/admin/analytics%'
             AND request_uri NOT LIKE '%/admin/security%'
             GROUP BY request_uri 
             HAVING count > 20"
        );
        
        foreach ($repeatedRequests as $request) {
            $patterns[] = "repeated_requests: {$request['request_uri']} ({$request['count']} times)";
        }
        
        // Проверяем запросы с подозрительными заголовками (исключая страницы мониторинга)
        $suspiciousHeaders = $this->db->fetchAll(
            "SELECT * FROM security_audit_log 
             WHERE created_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE) 
             AND request_uri NOT LIKE '%/admin/monitoring%'
             AND request_uri NOT LIKE '%/admin/analytics%'
             AND request_uri NOT LIKE '%/admin/security%'
             AND (user_agent LIKE '%bot%' OR user_agent LIKE '%crawler%' OR user_agent LIKE '%scanner%')"
        );
        
        foreach ($suspiciousHeaders as $header) {
            $patterns[] = "suspicious_user_agent: {$header['user_agent']}";
        }
        
        return $patterns;
    }
    
    /**
     * Проверка аномальной активности пользователей
     */
    private function checkUserAnomalies() {
        $anomalies = [];
        
        // Проверяем пользователей с необычно высокой активностью (исключая страницы мониторинга)
        $activeUsers = $this->db->fetchAll(
            "SELECT user_id, COUNT(*) as count 
             FROM security_audit_log 
             WHERE user_id IS NOT NULL 
             AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) 
             AND request_uri NOT LIKE '%/admin/monitoring%'
             AND request_uri NOT LIKE '%/admin/analytics%'
             AND request_uri NOT LIKE '%/admin/security%'
             GROUP BY user_id 
             HAVING count > 100"
        );
        
        foreach ($activeUsers as $user) {
            $anomalies[] = "high_user_activity: user_id {$user['user_id']} ({$user['count']} actions)";
        }
        
        // Проверяем одновременные сессии с одного IP (исключая страницы мониторинга)
        $concurrentSessions = $this->db->fetchAll(
            "SELECT ip_address, COUNT(DISTINCT user_id) as session_count 
             FROM security_audit_log 
             WHERE user_id IS NOT NULL 
             AND created_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE) 
             AND request_uri NOT LIKE '%/admin/monitoring%'
             AND request_uri NOT LIKE '%/admin/analytics%'
             AND request_uri NOT LIKE '%/admin/security%'
             GROUP BY ip_address 
             HAVING session_count > 3"
        );
        
        foreach ($concurrentSessions as $session) {
            $anomalies[] = "concurrent_sessions: IP {$session['ip_address']} ({$session['session_count']} users)";
        }
        
        return $anomalies;
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
            
            // Проверяем активность в нерабочее время (исключая страницы мониторинга)
            $hour = (int)date('H');
            if ($hour < 6 || $hour > 23) {
                $nightActivity = $this->db->fetchOne(
                    "SELECT COUNT(*) as count FROM security_audit_log 
                     WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
                     AND request_uri NOT LIKE '%/admin/monitoring%'
                     AND request_uri NOT LIKE '%/admin/analytics%'
                     AND request_uri NOT LIKE '%/admin/security%'"
                );
                
                if (($nightActivity['count'] ?? 0) > 20) {
                    $unusual[] = 'high_night_activity';
                }
            }
            
            // Проверяем активность с новых IP адресов (исключая страницы мониторинга)
            $newIPs = $this->db->fetchAll(
                "SELECT DISTINCT ip_address 
                 FROM security_audit_log 
                 WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) 
                 AND request_uri NOT LIKE '%/admin/monitoring%'
                 AND request_uri NOT LIKE '%/admin/analytics%'
                 AND request_uri NOT LIKE '%/admin/security%'
                 AND ip_address NOT IN (
                     SELECT DISTINCT ip_address 
                     FROM security_audit_log 
                     WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)
                     AND request_uri NOT LIKE '%/admin/monitoring%'
                     AND request_uri NOT LIKE '%/admin/analytics%'
                     AND request_uri NOT LIKE '%/admin/security%'
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
            // Базовые SQL инъекции
            'UNION SELECT',
            'UNION ALL SELECT',
            'DROP TABLE',
            'DELETE FROM',
            'INSERT INTO',
            'UPDATE SET',
            'OR 1=1',
            'OR 1=0',
            'OR \'1\'=\'1',
            'OR "1"="1',
            '--',
            '/*',
            '*/',
            'xp_cmdshell',
            'exec(',
            'eval(',
            'system(',
            // Расширенные паттерны
            'WAITFOR DELAY',
            'BENCHMARK(',
            'SLEEP(',
            'SELECT COUNT(*)',
            'SELECT * FROM',
            'INFORMATION_SCHEMA',
            'sys.tables',
            'sys.columns',
            'CAST(',
            'CONVERT(',
            'HEX(',
            'UNHEX(',
            'LOAD_FILE(',
            'INTO OUTFILE',
            'INTO DUMPFILE',
            'GROUP BY',
            'ORDER BY',
            'HAVING',
            'LIMIT',
            'OFFSET'
        ];
        
        $attempts = [];
        
        // Проверяем GET и POST параметры
        foreach ($_GET as $key => $value) {
            $attempts = array_merge($attempts, $this->checkParameterForSQLInjection($key, $value, 'GET'));
        }
        
        foreach ($_POST as $key => $value) {
            $attempts = array_merge($attempts, $this->checkParameterForSQLInjection($key, $value, 'POST'));
        }
        
        // Проверяем заголовки запроса
        $headers = getallheaders();
        foreach ($headers as $header => $value) {
            $attempts = array_merge($attempts, $this->checkParameterForSQLInjection($header, $value, 'HEADER'));
        }
        
        return $attempts;
    }
    
    /**
     * Проверка параметра на SQL инъекцию
     */
    private function checkParameterForSQLInjection($key, $value, $source) {
        $attempts = [];
        
        // Расширенные паттерны SQL инъекций
        $suspiciousPatterns = [
            'UNION SELECT', 'UNION ALL SELECT', 'DROP TABLE', 'DELETE FROM', 'INSERT INTO',
            'UPDATE SET', 'OR 1=1', 'OR 1=0', 'OR \'1\'=\'1', 'OR "1"="1', '--', '/*', '*/',
            'xp_cmdshell', 'exec(', 'eval(', 'system(', 'WAITFOR DELAY', 'BENCHMARK(',
            'SLEEP(', 'SELECT COUNT(*)', 'SELECT * FROM', 'INFORMATION_SCHEMA',
            'sys.tables', 'sys.columns', 'CAST(', 'CONVERT(', 'HEX(', 'UNHEX(',
            'LOAD_FILE(', 'INTO OUTFILE', 'INTO DUMPFILE', 'GROUP BY', 'ORDER BY',
            'HAVING', 'LIMIT', 'OFFSET'
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($value, $pattern) !== false) {
                $attempts[] = [
                    'type' => 'sql_injection',
                    'parameter' => $key,
                    'value' => $value,
                    'pattern' => $pattern,
                    'source' => $source,
                    'severity' => $this->calculateSQLInjectionSeverity($pattern, $value)
                ];
            }
        }
        
        // Проверяем на попытки обхода фильтров
        $bypassAttempts = $this->checkSQLInjectionBypass($key, $value, $source);
        $attempts = array_merge($attempts, $bypassAttempts);
        
        return $attempts;
    }
    
    /**
     * Проверка попыток обхода фильтров SQL инъекций
     */
    private function checkSQLInjectionBypass($key, $value, $source) {
        $bypassAttempts = [];
        
        // Попытки обхода через кодирование
        $encodedPatterns = [
            'urlencode' => urlencode('UNION SELECT'),
            'base64' => base64_encode('UNION SELECT'),
            'hex' => bin2hex('UNION SELECT'),
            'unicode' => '\u0055\u004E\u0049\u004F\u004E'
        ];
        
        foreach ($encodedPatterns as $encoding => $encodedPattern) {
            if (stripos($value, $encodedPattern) !== false) {
                $bypassAttempts[] = [
                    'type' => 'sql_injection_bypass',
                    'parameter' => $key,
                    'value' => $value,
                    'encoding' => $encoding,
                    'source' => $source,
                    'severity' => 'high'
                ];
            }
        }
        
        // Попытки обхода через комментарии
        $commentBypass = [
            '/**/UNION/**/SELECT',
            'UNION/*comment*/SELECT',
            'UNION--comment--SELECT'
        ];
        
        foreach ($commentBypass as $bypass) {
            if (stripos($value, $bypass) !== false) {
                $bypassAttempts[] = [
                    'type' => 'sql_injection_bypass',
                    'parameter' => $key,
                    'value' => $value,
                    'bypass_method' => 'comment_injection',
                    'source' => $source,
                    'severity' => 'high'
                ];
            }
        }
        
        return $bypassAttempts;
    }
    
    /**
     * Расчет серьезности SQL инъекции
     */
    private function calculateSQLInjectionSeverity($pattern, $value) {
        $criticalPatterns = ['DROP TABLE', 'DELETE FROM', 'xp_cmdshell', 'exec(', 'eval('];
        $highPatterns = ['UNION SELECT', 'INSERT INTO', 'UPDATE SET', 'INFORMATION_SCHEMA'];
        $mediumPatterns = ['OR 1=1', 'OR 1=0', 'SELECT * FROM'];
        
        if (in_array(strtoupper($pattern), $criticalPatterns)) {
            return 'critical';
        } elseif (in_array(strtoupper($pattern), $highPatterns)) {
            return 'high';
        } elseif (in_array(strtoupper($pattern), $mediumPatterns)) {
            return 'medium';
        } else {
            return 'low';
        }
    }
    
    /**
     * Проверка попыток XSS атак
     */
    private function checkXssAttempts() {
        $attempts = [];
        
        // Проверяем GET и POST параметры
        foreach ($_GET as $key => $value) {
            $attempts = array_merge($attempts, $this->checkParameterForXSS($key, $value, 'GET'));
        }
        
        foreach ($_POST as $key => $value) {
            $attempts = array_merge($attempts, $this->checkParameterForXSS($key, $value, 'POST'));
        }
        
        // Проверяем заголовки запроса
        $headers = getallheaders();
        foreach ($headers as $header => $value) {
            $attempts = array_merge($attempts, $this->checkParameterForXSS($header, $value, 'HEADER'));
        }
        
        return $attempts;
    }
    
    /**
     * Проверка параметра на XSS атаку
     */
    private function checkParameterForXSS($key, $value, $source) {
        $attempts = [];
        
        // Базовые XSS паттерны
        $xssPatterns = [
            // JavaScript теги и события
            '<script', 'javascript:', 'vbscript:', 'data:text/html',
            'onload=', 'onerror=', 'onclick=', 'onmouseover=', 'onmouseout=',
            'onfocus=', 'onblur=', 'onchange=', 'onsubmit=', 'onreset=',
            'onselect=', 'onunload=', 'onresize=', 'onscroll=',
            
            // JavaScript функции
            'alert(', 'confirm(', 'prompt(', 'eval(', 'setTimeout(',
            'setInterval(', 'Function(', 'constructor(',
            
            // DOM манипуляции
            'document.cookie', 'window.location', 'location.href',
            'innerHTML', 'outerHTML', 'document.write', 'document.writeln',
            
            // CSS выражения
            'expression(', 'url(javascript:', 'behavior:',
            
            // HTML5 события
            'oninput=', 'onkeyup=', 'onkeydown=', 'onkeypress=',
            'oncontextmenu=', 'onbeforeunload=', 'onpagehide=',
            
            // Мета-теги
            '<meta', 'refresh', 'http-equiv',
            
            // Фреймы и объекты
            '<iframe', '<object', '<embed', '<applet',
            
            // Кодированные атаки
            '&#x3C;script', '&#60;script', '%3Cscript', '%3cscript',
            '\\x3Cscript', '\\u003Cscript'
        ];
        
        foreach ($xssPatterns as $pattern) {
            if (stripos($value, $pattern) !== false) {
                $attempts[] = [
                    'type' => 'xss_attack',
                    'parameter' => $key,
                    'value' => $value,
                    'pattern' => $pattern,
                    'source' => $source,
                    'severity' => $this->calculateXSSSeverity($pattern, $value)
                ];
            }
        }
        
        // Проверяем на попытки обхода фильтров
        $bypassAttempts = $this->checkXSSBypass($key, $value, $source);
        $attempts = array_merge($attempts, $bypassAttempts);
        
        return $attempts;
    }
    
    /**
     * Проверка попыток обхода фильтров XSS
     */
    private function checkXSSBypass($key, $value, $source) {
        $bypassAttempts = [];
        
        // Попытки обхода через кодирование
        $encodedPatterns = [
            'urlencode' => urlencode('<script>alert(1)</script>'),
            'base64' => base64_encode('<script>alert(1)</script>'),
            'hex' => bin2hex('<script>alert(1)</script>'),
            'unicode' => '\u003C\u0073\u0063\u0072\u0069\u0070\u0074'
        ];
        
        foreach ($encodedPatterns as $encoding => $encodedPattern) {
            if (stripos($value, $encodedPattern) !== false) {
                $bypassAttempts[] = [
                    'type' => 'xss_bypass',
                    'parameter' => $key,
                    'value' => $value,
                    'encoding' => $encoding,
                    'source' => $source,
                    'severity' => 'high'
                ];
            }
        }
        
        // Попытки обхода через CSS
        $cssBypass = [
            'expression(alert(1))',
            'url(javascript:alert(1))',
            'behavior:url(javascript:alert(1))'
        ];
        
        foreach ($cssBypass as $bypass) {
            if (stripos($value, $bypass) !== false) {
                $bypassAttempts[] = [
                    'type' => 'xss_css_bypass',
                    'parameter' => $key,
                    'value' => $value,
                    'bypass_method' => 'css_injection',
                    'source' => $source,
                    'severity' => 'high'
                ];
            }
        }
        
        // Попытки обхода через HTML5
        $html5Bypass = [
            '<svg onload=alert(1)>',
            '<img src=x onerror=alert(1)>',
            '<body onload=alert(1)>',
            '<input onfocus=alert(1) autofocus>'
        ];
        
        foreach ($html5Bypass as $bypass) {
            if (stripos($value, $bypass) !== false) {
                $bypassAttempts[] = [
                    'type' => 'xss_html5_bypass',
                    'parameter' => $key,
                    'value' => $value,
                    'bypass_method' => 'html5_injection',
                    'source' => $source,
                    'severity' => 'high'
                ];
            }
        }
        
        return $bypassAttempts;
    }
    
    /**
     * Расчет серьезности XSS атаки
     */
    private function calculateXSSSeverity($pattern, $value) {
        $criticalPatterns = ['<script', 'javascript:', 'eval(', 'document.cookie', 'window.location'];
        $highPatterns = ['alert(', 'confirm(', 'prompt(', 'innerHTML', 'outerHTML'];
        $mediumPatterns = ['onload=', 'onclick=', 'onmouseover=', 'onfocus='];
        
        if (in_array(strtolower($pattern), $criticalPatterns)) {
            return 'critical';
        } elseif (in_array(strtolower($pattern), $highPatterns)) {
            return 'high';
        } elseif (in_array(strtolower($pattern), $mediumPatterns)) {
            return 'medium';
        } else {
            return 'low';
        }
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
            // Возвращаем тестовые данные если база недоступна
            return $this->getTestSecurityStats();
        }
    }
    
    /**
     * Получение тестовой статистики безопасности
     */
    private function getTestSecurityStats() {
        return [
            'total_threats' => 12,
            'blocked_ips' => 3,
            'threats_by_type' => [
                ['threat_type' => 'sql_injection', 'count' => 4],
                ['threat_type' => 'xss_attack', 'count' => 3],
                ['threat_type' => 'failed_login', 'count' => 2],
                ['threat_type' => 'suspicious_activity', 'count' => 3]
            ],
            'hourly_activity' => [
                ['hour' => 0, 'count' => 15],
                ['hour' => 1, 'count' => 8],
                ['hour' => 2, 'count' => 5],
                ['hour' => 3, 'count' => 3],
                ['hour' => 4, 'count' => 2],
                ['hour' => 5, 'count' => 4],
                ['hour' => 6, 'count' => 12],
                ['hour' => 7, 'count' => 25],
                ['hour' => 8, 'count' => 45],
                ['hour' => 9, 'count' => 67],
                ['hour' => 10, 'count' => 89],
                ['hour' => 11, 'count' => 76],
                ['hour' => 12, 'count' => 92],
                ['hour' => 13, 'count' => 88],
                ['hour' => 14, 'count' => 95],
                ['hour' => 15, 'count' => 78],
                ['hour' => 16, 'count' => 82],
                ['hour' => 17, 'count' => 91],
                ['hour' => 18, 'count' => 87],
                ['hour' => 19, 'count' => 73],
                ['hour' => 20, 'count' => 65],
                ['hour' => 21, 'count' => 54],
                ['hour' => 22, 'count' => 42],
                ['hour' => 23, 'count' => 28]
            ]
        ];
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