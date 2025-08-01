<?php

/**
 * Сервис аналитики
 * Обеспечивает сбор, анализ и визуализацию данных
 */
class AnalyticsService {
    
    private $db;
    private $cache = [];
    private $cacheExpiry = 300; // 5 минут
    
    public function __construct() {
        require_once ENGINE_DIR . 'main/db.php';
        $this->db = new Database();
    }
    
    /**
     * Получает общую статистику системы
     */
    public function getSystemStats() {
        $cacheKey = 'system_stats';
        
        if ($this->isCacheValid($cacheKey)) {
            return $this->cache[$cacheKey]['data'];
        }
        
        $stats = [
            'users' => $this->getUserStats(),
            'content' => $this->getContentStats(),
            'activity' => $this->getActivityStats(),
            'performance' => $this->getPerformanceStats(),
            'security' => $this->getSecurityStats()
        ];
        
        $this->cacheData($cacheKey, $stats);
        
        return $stats;
    }
    
    /**
     * Статистика пользователей
     */
    private function getUserStats() {
        try {
            $totalUsers = 0;
            $activeUsers = 0;
            $newUsers = 0;
            $userRoles = [];
            
            // Проверяем существование таблицы users
            try {
                $totalUsers = $this->db->fetchOne("SELECT COUNT(*) as count FROM users")['count'] ?? 0;
                $activeUsers = $this->db->fetchOne("SELECT COUNT(*) as count FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)")['count'] ?? 0;
                $newUsers = $this->db->fetchOne("SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")['count'] ?? 0;
                
                $userRoles = $this->db->fetchAll("SELECT user_role, COUNT(*) as count FROM users GROUP BY user_role");
            } catch (Exception $e) {
                // Таблица users не существует
            }
            
            return [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'new_users_7d' => $newUsers,
                'user_roles' => $userRoles,
                'activity_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0
            ];
        } catch (Exception $e) {
            return [
                'total_users' => 0,
                'active_users' => 0,
                'new_users_7d' => 0,
                'user_roles' => [],
                'activity_rate' => 0
            ];
        }
    }
    
    /**
     * Статистика контента
     */
    private function getContentStats() {
        try {
            $totalNews = 0;
            $totalFiles = 0;
            $totalPhotos = 0;
            $recentNews = 0;
            $recentFiles = 0;
            
            // Проверяем существование таблицы news
            try {
                $totalNews = $this->db->fetchOne("SELECT COUNT(*) as count FROM news")['count'] ?? 0;
                $recentNews = $this->db->fetchOne("SELECT COUNT(*) as count FROM news WHERE news_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)")['count'] ?? 0;
            } catch (Exception $e) {
                // Таблица news не существует
            }
            
            // Проверяем существование таблицы files
            try {
                $totalFiles = $this->db->fetchOne("SELECT COUNT(*) as count FROM files")['count'] ?? 0;
                $recentFiles = $this->db->fetchOne("SELECT COUNT(*) as count FROM files WHERE upload_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)")['count'] ?? 0;
            } catch (Exception $e) {
                // Таблица files не существует
            }
            
            // Проверяем существование таблицы photos
            try {
                $totalPhotos = $this->db->fetchOne("SELECT COUNT(*) as count FROM photos")['count'] ?? 0;
            } catch (Exception $e) {
                // Таблица photos не существует
            }
            
            return [
                'total_news' => $totalNews,
                'total_files' => $totalFiles,
                'total_photos' => $totalPhotos,
                'recent_news_7d' => $recentNews,
                'recent_files_7d' => $recentFiles,
                'total_content' => $totalNews + $totalFiles + $totalPhotos
            ];
        } catch (Exception $e) {
            return [
                'total_news' => 0,
                'total_files' => 0,
                'total_photos' => 0,
                'recent_news_7d' => 0,
                'recent_files_7d' => 0,
                'total_content' => 0
            ];
        }
    }
    
    /**
     * Статистика активности
     */
    private function getActivityStats() {
        try {
            $todayLogins = 0;
            $todayActions = 0;
            $activeSessions = 0;
            $weeklyActivity = [];
            $hourlyActivity = [];
            
            // Проверяем существование таблицы system_logs
            try {
                $todayLogins = $this->db->fetchOne("SELECT COUNT(*) as count FROM system_logs WHERE level = 'info' AND message LIKE '%Login Attempt: SUCCESS%' AND created_at >= CURDATE()")['count'] ?? 0;
                $todayActions = $this->db->fetchOne("SELECT COUNT(*) as count FROM system_logs WHERE level = 'info' AND message LIKE '%User Action:%' AND created_at >= CURDATE()")['count'] ?? 0;
                
                $weeklyActivity = $this->db->fetchAll("
                    SELECT DATE(created_at) as date, COUNT(*) as count 
                    FROM system_logs 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    GROUP BY DATE(created_at)
                    ORDER BY date
                ");
                
                $hourlyActivity = $this->db->fetchAll("
                    SELECT HOUR(created_at) as hour, COUNT(*) as count 
                    FROM system_logs 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                    GROUP BY HOUR(created_at)
                    ORDER BY hour
                ");
            } catch (Exception $e) {
                // Таблица system_logs не существует
            }
            
            // Проверяем активные сессии
            try {
                $activeSessions = $this->db->fetchOne("
                    SELECT COUNT(DISTINCT user_id) as count 
                    FROM user_sessions 
                    WHERE is_active = 1 
                    AND last_activity >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                ")['count'] ?? 0;
            } catch (Exception $e) {
                // Таблица user_sessions не существует
            }
            
            return [
                'today_logins' => $todayLogins,
                'today_actions' => $todayActions,
                'active_sessions' => $activeSessions,
                'weekly_activity' => $weeklyActivity,
                'hourly_activity' => $hourlyActivity
            ];
        } catch (Exception $e) {
            return [
                'today_logins' => 0,
                'today_actions' => 0,
                'active_sessions' => 0,
                'weekly_activity' => [],
                'hourly_activity' => []
            ];
        }
    }
    
    /**
     * Статистика производительности
     */
    private function getPerformanceStats() {
        try {
            $avgResponseTime = 0;
            $slowQueries = [];
            
            // Проверяем существование таблицы system_logs
            try {
                $avgResponseTime = $this->db->fetchOne("
                    SELECT AVG(CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3))) as avg_time 
                    FROM system_logs 
                    WHERE level = 'debug' AND message LIKE '%SQL Query:%' 
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                ")['avg_time'] ?? 0;
                
                $slowQueries = $this->db->fetchAll("
                    SELECT message, JSON_EXTRACT(context, '$.execution_time') as execution_time
                    FROM system_logs 
                    WHERE level = 'debug' AND message LIKE '%SQL Query:%' 
                    AND CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) > 1.0
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                    ORDER BY CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) DESC
                    LIMIT 10
                ");
            } catch (Exception $e) {
                // Таблица system_logs не существует
            }
            
            return [
                'avg_response_time' => round($avgResponseTime, 3),
                'slow_queries' => $slowQueries
            ];
        } catch (Exception $e) {
            return [
                'avg_response_time' => 0,
                'slow_queries' => []
            ];
        }
    }
    
    /**
     * Статистика безопасности
     */
    private function getSecurityStats() {
        try {
            $failedLogins = 0;
            $suspiciousActivity = 0;
            $uniqueIPs = 0;
            
            // Проверяем существование таблицы system_logs
            try {
                $failedLogins = $this->db->fetchOne("
                    SELECT COUNT(*) as count 
                    FROM system_logs 
                    WHERE level = 'info' AND message LIKE '%Login Attempt: FAILED%' 
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                ")['count'] ?? 0;
                
                $suspiciousActivity = $this->db->fetchOne("
                    SELECT COUNT(*) as count 
                    FROM system_logs 
                    WHERE level IN ('warning', 'error', 'critical') 
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                ")['count'] ?? 0;
                
                $uniqueIPs = $this->db->fetchOne("
                    SELECT COUNT(DISTINCT ip_address) as count 
                    FROM system_logs 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                ")['count'] ?? 0;
            } catch (Exception $e) {
                // Таблица system_logs не существует
            }
            
            return [
                'failed_logins_24h' => $failedLogins,
                'suspicious_activity_24h' => $suspiciousActivity,
                'unique_ips_24h' => $uniqueIPs
            ];
        } catch (Exception $e) {
            return [
                'failed_logins_24h' => 0,
                'suspicious_activity_24h' => 0,
                'unique_ips_24h' => 0
            ];
        }
    }
    
    /**
     * Получает данные для графиков
     */
    public function getChartData($type, $period = '7d') {
        $cacheKey = "chart_{$type}_{$period}";
        
        if ($this->isCacheValid($cacheKey)) {
            return $this->cache[$cacheKey]['data'];
        }
        
        $data = [];
        
        switch ($type) {
            case 'user_activity':
                $data = $this->getUserActivityChart($period);
                break;
            case 'content_creation':
                $data = $this->getContentCreationChart($period);
                break;
            case 'login_attempts':
                $data = $this->getLoginAttemptsChart($period);
                break;
            case 'error_rates':
                $data = $this->getErrorRatesChart($period);
                break;
            case 'performance':
                $data = $this->getPerformanceChart($period);
                break;
        }
        
        $this->cacheData($cacheKey, $data);
        
        return $data;
    }
    
    /**
     * График активности пользователей
     */
    private function getUserActivityChart($period) {
        try {
            $interval = $this->getInterval($period);
            
            $data = $this->db->fetchAll("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM system_logs 
                WHERE level = 'info' AND message LIKE '%User Action:%'
                AND created_at >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            
            return $this->formatChartData($data, $period);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * График создания контента
     */
    private function getContentCreationChart($period) {
        try {
            $interval = $this->getInterval($period);
            
            $newsData = $this->db->fetchAll("
                SELECT DATE(news_date) as date, COUNT(*) as count
                FROM news 
                WHERE news_date >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(news_date)
                ORDER BY date
            ");
            
            $filesData = $this->db->fetchAll("
                SELECT DATE(upload_date) as date, COUNT(*) as count
                FROM files 
                WHERE upload_date >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(upload_date)
                ORDER BY date
            ");
            
            return [
                'news' => $this->formatChartData($newsData, $period),
                'files' => $this->formatChartData($filesData, $period)
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * График попыток входа
     */
    private function getLoginAttemptsChart($period) {
        try {
            $interval = $this->getInterval($period);
            
            $successData = $this->db->fetchAll("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM system_logs 
                WHERE level = 'info' AND message LIKE '%Login Attempt: SUCCESS%'
                AND created_at >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            
            $failedData = $this->db->fetchAll("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM system_logs 
                WHERE level = 'info' AND message LIKE '%Login Attempt: FAILED%'
                AND created_at >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            
            return [
                'success' => $this->formatChartData($successData, $period),
                'failed' => $this->formatChartData($failedData, $period)
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * График ошибок
     */
    private function getErrorRatesChart($period) {
        try {
            $interval = $this->getInterval($period);
            
            $data = $this->db->fetchAll("
                SELECT DATE(created_at) as date, level, COUNT(*) as count
                FROM system_logs 
                WHERE level IN ('error', 'warning', 'critical')
                AND created_at >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(created_at), level
                ORDER BY date, level
            ");
            
            return $this->formatErrorChartData($data, $period);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * График производительности
     */
    private function getPerformanceChart($period) {
        try {
            $interval = $this->getInterval($period);
            
            $data = $this->db->fetchAll("
                SELECT DATE(created_at) as date, AVG(CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3))) as avg_time
                FROM system_logs 
                WHERE level = 'debug' AND message LIKE '%SQL Query:%'
                AND created_at >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            
            return $this->formatChartData($data, $period);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Форматирует данные для графиков
     */
    private function formatChartData($data, $period) {
        $formatted = [];
        
        foreach ($data as $row) {
            $formatted[] = [
                'date' => $row['date'],
                'value' => (int)$row['count']
            ];
        }
        
        return $formatted;
    }
    
    /**
     * Форматирует данные ошибок для графиков
     */
    private function formatErrorChartData($data, $period) {
        $formatted = [];
        $errors = [];
        
        foreach ($data as $row) {
            $date = $row['date'];
            $level = $row['level'];
            $count = (int)$row['count'];
            
            if (!isset($errors[$date])) {
                $errors[$date] = [];
            }
            
            $errors[$date][$level] = $count;
        }
        
        foreach ($errors as $date => $levels) {
            $formatted[] = [
                'date' => $date,
                'error' => $levels['error'] ?? 0,
                'warning' => $levels['warning'] ?? 0,
                'critical' => $levels['critical'] ?? 0
            ];
        }
        
        return $formatted;
    }
    
    /**
     * Получает интервал для периода
     */
    private function getInterval($period) {
        $intervals = [
            '1d' => '1 DAY',
            '7d' => '7 DAY',
            '30d' => '30 DAY',
            '90d' => '90 DAY'
        ];
        
        return $intervals[$period] ?? '7 DAY';
    }
    
    /**
     * Проверяет валидность кэша
     */
    private function isCacheValid($key) {
        return isset($this->cache[$key]) && 
               (time() - $this->cache[$key]['timestamp']) < $this->cacheExpiry;
    }
    
    /**
     * Кэширует данные
     */
    private function cacheData($key, $data) {
        $this->cache[$key] = [
            'data' => $data,
            'timestamp' => time()
        ];
    }
    
    /**
     * Очищает кэш
     */
    public function clearCache() {
        $this->cache = [];
    }
    
    /**
     * Получает топ пользователей по активности
     */
    public function getTopUsers($limit = 10) {
        try {
            return $this->db->fetchAll("
                SELECT user_id, COUNT(*) as action_count
                FROM system_logs 
                WHERE level = 'info' AND message LIKE '%User Action:%'
                AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY user_id
                ORDER BY action_count DESC
                LIMIT ?
            ", [$limit]);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получает популярные действия
     */
    public function getPopularActions($limit = 10) {
        try {
            return $this->db->fetchAll("
                SELECT 
                    JSON_EXTRACT(context, '$.action') as action,
                    COUNT(*) as count
                FROM system_logs 
                WHERE level = 'info' AND message LIKE '%User Action:%'
                AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY JSON_EXTRACT(context, '$.action')
                ORDER BY count DESC
                LIMIT ?
            ", [$limit]);
        } catch (Exception $e) {
            return [];
        }
    }
} 