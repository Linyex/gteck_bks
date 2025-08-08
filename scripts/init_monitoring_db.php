<?php
/**
 * Скрипт инициализации базы данных мониторинга
 * Запускается для создания таблиц и базовых настроек
 */

require_once __DIR__ . '/../engine/main/db.php';

try {
    echo "Инициализация базы данных мониторинга...\n";
    
    // Читаем SQL файл с таблицами
    $sqlFile = __DIR__ . '/../database/monitoring_tables.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("Файл $sqlFile не найден");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Разбиваем SQL на отдельные запросы
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $db = new Database();
    
    foreach ($queries as $query) {
        if (!empty($query)) {
            try {
                $db->execute($query);
                echo "✓ Выполнен запрос\n";
            } catch (Exception $e) {
                echo "⚠ Ошибка в запросе: " . $e->getMessage() . "\n";
            }
        }
    }
    
    // Добавляем тестовые данные для демонстрации
    echo "\nДобавление тестовых данных...\n";
    
    // Тестовые угрозы
    $testThreats = [
        [
            'threat_type' => 'sql_injection',
            'severity' => 'critical',
            'description' => 'Попытка SQL инъекции в параметре id',
            'details' => json_encode([
                'parameter' => 'id',
                'value' => '1 UNION SELECT * FROM users',
                'pattern' => 'UNION SELECT'
            ]),
            'ip_address' => '192.168.1.100',
            'request_uri' => '/admin/users?id=1 UNION SELECT * FROM users'
        ],
        [
            'threat_type' => 'xss_attack',
            'severity' => 'high',
            'description' => 'Попытка XSS атаки в поле комментария',
            'details' => json_encode([
                'parameter' => 'comment',
                'value' => '<script>alert("XSS")</script>',
                'pattern' => '<script'
            ]),
            'ip_address' => '192.168.1.101',
            'request_uri' => '/admin/news/create'
        ],
        [
            'threat_type' => 'failed_login',
            'severity' => 'medium',
            'description' => 'Множественные неудачные попытки входа',
            'details' => json_encode([
                'attempts' => 8,
                'timeframe' => '15 minutes'
            ]),
            'ip_address' => '192.168.1.102',
            'request_uri' => '/admin/login'
        ]
    ];
    
    foreach ($testThreats as $threat) {
        $db->execute(
            "INSERT INTO security_monitoring (threat_type, severity, description, details, ip_address, request_uri) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $threat['threat_type'],
                $threat['severity'],
                $threat['description'],
                $threat['details'],
                $threat['ip_address'],
                $threat['request_uri']
            ]
        );
    }
    
    // Тестовые заблокированные IP
    $testBlockedIPs = [
        ['ip_address' => '192.168.1.100', 'reason' => 'SQL инъекции'],
        ['ip_address' => '192.168.1.101', 'reason' => 'XSS атаки'],
        ['ip_address' => '192.168.1.102', 'reason' => 'Множественные неудачные входы']
    ];
    
    foreach ($testBlockedIPs as $ip) {
        $db->execute(
            "INSERT INTO ip_blacklist (ip_address, reason) VALUES (?, ?)",
            [$ip['ip_address'], $ip['reason']]
        );
    }
    
    // Тестовые логи аудита
    $testAuditLogs = [
        [
            'user_id' => 1,
            'ip_address' => '192.168.1.100',
            'action_type' => 'login_failed',
            'request_uri' => '/admin/login',
            'request_method' => 'POST'
        ],
        [
            'user_id' => null,
            'ip_address' => '192.168.1.101',
            'action_type' => 'page_access',
            'request_uri' => '/admin/users',
            'request_method' => 'GET'
        ],
        [
            'user_id' => 1,
            'ip_address' => '192.168.1.100',
            'action_type' => 'file_upload',
            'request_uri' => '/admin/files/upload',
            'request_method' => 'POST'
        ]
    ];
    
    foreach ($testAuditLogs as $log) {
        $db->execute(
            "INSERT INTO security_audit_log (user_id, ip_address, action_type, request_uri, request_method) 
             VALUES (?, ?, ?, ?, ?)",
            [
                $log['user_id'],
                $log['ip_address'],
                $log['action_type'],
                $log['request_uri'],
                $log['request_method']
            ]
        );
    }
    
    // Тестовые расширенные логи
    $testAdvancedLogs = [
        [
            'level' => 'alert',
            'message' => 'Обнаружена попытка SQL инъекции',
            'context' => json_encode(['ip' => '192.168.1.100', 'parameter' => 'id']),
            'ip_address' => '192.168.1.100'
        ],
        [
            'level' => 'warning',
            'message' => 'Множественные неудачные попытки входа',
            'context' => json_encode(['ip' => '192.168.1.102', 'attempts' => 8]),
            'ip_address' => '192.168.1.102'
        ],
        [
            'level' => 'info',
            'message' => 'Пользователь вошел в систему',
            'context' => json_encode(['user_id' => 1, 'ip' => '192.168.1.50']),
            'user_id' => 1,
            'ip_address' => '192.168.1.50'
        ]
    ];
    
    foreach ($testAdvancedLogs as $log) {
        $db->execute(
            "INSERT INTO advanced_logs (level, message, context, user_id, ip_address) 
             VALUES (?, ?, ?, ?, ?)",
            [
                $log['level'],
                $log['message'],
                $log['context'],
                $log['user_id'] ?? null,
                $log['ip_address']
            ]
        );
    }
    
    // Тестовая статистика по часам
    for ($hour = 0; $hour < 24; $hour++) {
        $activityCount = rand(10, 100);
        $threatCount = rand(0, 5);
        $uniqueIPs = rand(5, 20);
        $uniqueUsers = rand(1, 10);
        
        $db->execute(
            "INSERT INTO hourly_activity_stats (hour_of_day, activity_count, threat_count, unique_ips, unique_users, date) 
             VALUES (?, ?, ?, ?, ?, CURDATE())",
            [$hour, $activityCount, $threatCount, $uniqueIPs, $uniqueUsers]
        );
    }
    
    echo "✓ База данных мониторинга успешно инициализирована!\n";
    echo "✓ Добавлены тестовые данные для демонстрации\n";
    echo "\nТеперь вы можете:\n";
    echo "1. Открыть страницу мониторинга: /admin/monitoring\n";
    echo "2. Просмотреть угрозы: /admin/monitoring/threats\n";
    echo "3. Проверить логи: /admin/monitoring/logs\n";
    echo "4. Настроить параметры: /admin/monitoring/settings\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка инициализации: " . $e->getMessage() . "\n";
    exit(1);
}
