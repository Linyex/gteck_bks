<?php

class DetailedAnalyticsController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Детальная страница активности пользователей
     */
    public function activityDetails()
    {
        $period = $_GET['period'] ?? 7;
        $activityType = $_GET['activity_type'] ?? '';
        
        // Получаем статистику
        $stats = $this->getActivityStats($period, $activityType);
        
        // Получаем данные для графиков
        $chartData = $this->getActivityChartData($period, $activityType);
        
        // Получаем логи активности
        $activityLogs = $this->getActivityLogs($period, $activityType);

        include 'application/views/admin/enhanced-analytics/activity-details.php';
    }

    /**
     * Детальная страница безопасности
     */
    public function securityDetails()
    {
        $period = $_GET['period'] ?? 7;
        
        // Получаем статистику безопасности
        $stats = $this->getSecurityStats($period);
        
        // Получаем данные для графиков
        $chartData = $this->getSecurityChartData($period);
        
        // Получаем логи безопасности
        $securityLogs = $this->getSecurityLogs($period);

        $data = [
            'stats' => $stats,
            'chartData' => $chartData,
            'securityLogs' => $securityLogs,
            'filters' => ['period' => $period]
        ];

        include 'application/views/admin/enhanced-analytics/security-details.php';
    }

    /**
     * Детальная страница геолокации
     */
    public function geolocationDetails()
    {
        $period = $_GET['period'] ?? 7;
        
        // Получаем статистику геолокации
        $stats = $this->getGeolocationStats($period);
        
        // Получаем данные для карты
        $mapData = $this->getGeolocationMapData($period);
        
        // Получаем логи геолокации
        $geolocationLogs = $this->getGeolocationLogs($period);

        $data = [
            'stats' => $stats,
            'mapData' => $mapData,
            'geolocationLogs' => $geolocationLogs,
            'filters' => ['period' => $period]
        ];

        include 'application/views/admin/enhanced-analytics/geolocation-details.php';
    }

    /**
     * Экспорт логов активности
     */
    public function exportActivityLogs()
    {
        $period = $_GET['period'] ?? 7;
        $activityType = $_GET['activity_type'] ?? '';
        
        $logs = $this->getActivityLogs($period, $activityType);
        
        // Создаем CSV файл
        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Заголовки CSV
        fputcsv($output, ['Время', 'Пользователь', 'Тип действия', 'IP адрес', 'Детали']);
        
        // Данные
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['activity_time'],
                $log['user_fio'] ?? $log['user_login'] ?? 'Неизвестный',
                $log['action_type'],
                $log['ip_address'] ?? 'N/A',
                $log['details'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Получение статистики активности
     */
    private function getActivityStats($period, $activityType = '')
    {
        $whereConditions = ["DATE(activity_time) >= DATE_SUB(NOW(), INTERVAL ? DAY)"];
        $params = [$period];

        if ($activityType) {
            $whereConditions[] = "action_type = ?";
            $params[] = $activityType;
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Общее количество действий
        $totalActivities = Database::fetchOne("
            SELECT COUNT(*) FROM user_activity 
            WHERE $whereClause
        ", $params);

        // Уникальных пользователей
        $uniqueUsers = Database::fetchOne("
            SELECT COUNT(DISTINCT user_id) FROM user_activity 
            WHERE $whereClause
        ", $params);

        return [
            'total_activities' => $totalActivities,
            'unique_users' => $uniqueUsers
        ];
    }

    /**
     * Получение данных для графиков активности
     */
    private function getActivityChartData($period, $activityType = '')
    {
        $whereConditions = ["DATE(activity_time) >= DATE_SUB(NOW(), INTERVAL ? DAY)"];
        $params = [$period];

        if ($activityType) {
            $whereConditions[] = "action_type = ?";
            $params[] = $activityType;
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Активность по часам
        $hourlyActivity = Database::fetchAll("
            SELECT HOUR(activity_time) as hour, COUNT(*) as count 
            FROM user_activity 
            WHERE $whereClause 
            GROUP BY HOUR(activity_time) 
            ORDER BY hour
        ", $params);

        // Типы активности
        $activityTypes = Database::fetchAll("
            SELECT action_type as type, COUNT(*) as count 
            FROM user_activity 
            WHERE $whereClause 
            GROUP BY action_type 
            ORDER BY count DESC
        ", $params);

        return [
            'hourly_activity' => $hourlyActivity,
            'activity_types' => $activityTypes
        ];
    }

    /**
     * Получение логов активности
     */
    private function getActivityLogs($period, $activityType = '')
    {
        $whereConditions = ["DATE(ua.activity_time) >= DATE_SUB(NOW(), INTERVAL ? DAY)"];
        $params = [$period];

        if ($activityType) {
            $whereConditions[] = "ua.action_type = ?";
            $params[] = $activityType;
        }

        $whereClause = implode(' AND ', $whereConditions);

        return Database::fetchAll("
            SELECT ua.*, u.user_fio, u.user_login 
            FROM user_activity ua 
            LEFT JOIN users u ON ua.user_id = u.user_id 
            WHERE $whereClause 
            ORDER BY ua.activity_time DESC 
            LIMIT 100
        ", $params);
    }

    /**
     * Получение статистики безопасности
     */
    private function getSecurityStats($period)
    {
        // Неудачные попытки входа
        $failedLogins = Database::fetchOne("
            SELECT COUNT(*) FROM login_attempts 
            WHERE success = 0 AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ", [$period]);

        // Подозрительные действия
        $suspiciousActivities = Database::fetchOne("
            SELECT COUNT(*) FROM ml_anomalies 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ", [$period]);

        // Блокировки
        $blockedUsers = Database::fetchOne("
            SELECT COUNT(*) FROM users 
            WHERE is_active = 0 AND updated_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ", [$period]);

        return [
            'failed_logins' => $failedLogins,
            'suspicious_activities' => $suspiciousActivities,
            'blocked_users' => $blockedUsers
        ];
    }

    /**
     * Получение данных для графиков безопасности
     */
    private function getSecurityChartData($period)
    {
        // Попытки входа по дням
        $loginAttempts = Database::fetchAll("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM login_attempts 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
            GROUP BY DATE(created_at) 
            ORDER BY date
        ", [$period]);

        // Типы аномалий
        $anomalyTypes = Database::fetchAll("
            SELECT anomaly_type as type, COUNT(*) as count 
            FROM ml_anomalies 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
            GROUP BY anomaly_type 
            ORDER BY count DESC
        ", [$period]);

        return [
            'login_attempts' => $loginAttempts,
            'anomaly_types' => $anomalyTypes
        ];
    }

    /**
     * Получение логов безопасности
     */
    private function getSecurityLogs($period)
    {
        return Database::fetchAll("
            SELECT * FROM ml_anomalies 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
            ORDER BY created_at DESC 
            LIMIT 50
        ", [$period]);
    }

    /**
     * Получение статистики геолокации
     */
    private function getGeolocationStats($period)
    {
        // Уникальные страны
        $uniqueCountries = Database::fetchOne("
            SELECT COUNT(DISTINCT country) FROM ip_geolocation 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ", [$period]);

        // Уникальные города
        $uniqueCities = Database::fetchOne("
            SELECT COUNT(DISTINCT city) FROM ip_geolocation 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ", [$period]);

        return [
            'unique_countries' => $uniqueCountries,
            'unique_cities' => $uniqueCities
        ];
    }

    /**
     * Получение данных для карты геолокации
     */
    private function getGeolocationMapData($period)
    {
        return Database::fetchAll("
            SELECT latitude, longitude, country, city, COUNT(*) as count 
            FROM ip_geolocation 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
            GROUP BY latitude, longitude, country, city 
            ORDER BY count DESC 
            LIMIT 20
        ", [$period]);
    }

    /**
     * Получение логов геолокации
     */
    private function getGeolocationLogs($period)
    {
        return Database::fetchAll("
            SELECT * FROM ip_geolocation 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
            ORDER BY created_at DESC 
            LIMIT 50
        ", [$period]);
    }
} 