<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

class AnalyticsController extends BaseAdminController {
    
    public function __construct() {
        parent::__construct();
        require_once 'engine/main/encryption.php';
    }
    
    public function index() {
        $this->requireAccessLevel(5); // Только модераторы и выше
        
        try {
            require_once 'engine/main/db.php';
            
            // Получаем расширенную статистику
            $analytics = $this->getComprehensiveAnalytics();
            
            // Получаем данные для графиков
            $chartData = $this->getChartData();
            
            // Получаем последние уведомления безопасности
            $securityNotifications = $this->getSecurityNotifications();
            
            // Получаем топ подозрительных IP
            $suspiciousIPs = $this->getSuspiciousIPs();
            
            // Получаем активность пользователей
            $userActivity = $this->getUserActivityStats();
            
            return $this->render('admin/analytics/index', [
                'title' => 'Аналитика безопасности',
                'analytics' => $analytics,
                'chartData' => $chartData,
                'securityNotifications' => $securityNotifications,
                'suspiciousIPs' => $suspiciousIPs,
                'userActivity' => $userActivity
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке аналитики: ' . $e->getMessage()
            ]);
        }
    }
    
    public function security() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            // Детальная статистика безопасности
            $securityStats = $this->getDetailedSecurityStats();
            
            // Анализ угроз
            $threatAnalysis = $this->getThreatAnalysis();
            
            // Статистика по IP адресам
            $ipStats = $this->getIPStatistics();
            
            // История инцидентов
            $incidentHistory = $this->getIncidentHistory();
            
            return $this->render('admin/analytics/security', [
                'title' => 'Детальная безопасность',
                'securityStats' => $securityStats,
                'threatAnalysis' => $threatAnalysis,
                'ipStats' => $ipStats,
                'incidentHistory' => $incidentHistory
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке статистики безопасности: ' . $e->getMessage()
            ]);
        }
    }
    
    public function userActivity() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
            
            if ($userId) {
                // Детальная активность конкретного пользователя
                $userActivity = $this->getUserDetailedActivity($userId);
                $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$userId]);
                
                return $this->render('admin/analytics/user-activity', [
                    'title' => 'Активность пользователя',
                    'user' => $user,
                    'userActivity' => $userActivity
                ]);
            } else {
                // Общая статистика активности пользователей
                $activityStats = $this->getActivityStatistics();
                
                return $this->render('admin/analytics/activity-overview', [
                    'title' => 'Обзор активности',
                    'activityStats' => $activityStats
                ]);
            }
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке статистики активности: ' . $e->getMessage()
            ]);
        }
    }
    
    public function sessions() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            // Статистика сессий
            $sessionStats = $this->getSessionStatistics();
            
            // Активные сессии
            $activeSessions = $this->getActiveSessions();
            
            // Подозрительные сессии
            $suspiciousSessions = $this->getSuspiciousSessions();
            
            return $this->render('admin/analytics/sessions', [
                'title' => 'Управление сессиями',
                'sessionStats' => $sessionStats,
                'activeSessions' => $activeSessions,
                'suspiciousSessions' => $suspiciousSessions
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке статистики сессий: ' . $e->getMessage()
            ]);
        }
    }
    
    public function api() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            require_once 'engine/main/db.php';
            
            $type = $_GET['type'] ?? 'general';
            
            switch ($type) {
                case 'security_stats':
                    $data = $this->getSecurityStatsAPI();
                    break;
                case 'activity_chart':
                    $data = $this->getActivityChartData();
                    break;
                case 'login_attempts':
                    $data = $this->getLoginAttemptsData();
                    break;
                case 'sessions_data':
                    $data = $this->getSessionsData();
                    break;
                default:
                    $data = $this->getGeneralStatsAPI();
            }
            
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function markNotificationRead() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $notificationId = $data['notification_id'] ?? null;
            
            if (!$notificationId) {
                throw new Exception('Notification ID required');
            }
            
            require_once 'engine/main/db.php';
            
            Database::execute(
                "UPDATE security_notifications SET is_read = 1 WHERE id = ?",
                [$notificationId]
            );
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function markAllNotificationsRead() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            require_once 'engine/main/db.php';
            
            Database::execute("UPDATE security_notifications SET is_read = 1");
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function blockIP() {
        $this->requireAccessLevel(10);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ipAddress = $data['ip_address'] ?? null;
            $reason = $data['reason'] ?? 'Подозрительная активность';
            $duration = $data['duration'] ?? 24; // часы
            
            if (!$ipAddress) {
                throw new Exception('IP address required');
            }
            
            require_once 'engine/main/db.php';
            
            $blockedUntil = date('Y-m-d H:i:s', strtotime("+{$duration} hours"));
            
            Database::execute(
                "INSERT INTO ip_blacklist (ip_address, reason, blocked_by, blocked_until) 
                 VALUES (?, ?, ?, ?) 
                 ON DUPLICATE KEY UPDATE reason = ?, blocked_until = ?",
                [$ipAddress, $reason, $_SESSION['admin_user_id'], $blockedUntil, $reason, $blockedUntil]
            );
            
            // Логируем действие
            $this->logAdminAction('block_ip', null, [
                'admin_id' => $_SESSION['admin_user_id'],
                'ip_address' => $ipAddress,
                'reason' => $reason,
                'duration' => $duration
            ]);
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function blockAllSuspiciousIPs() {
        $this->requireAccessLevel(10);
        
        header('Content-Type: application/json');
        
        try {
            require_once 'engine/main/db.php';
            
            // Получаем подозрительные IP
            $suspiciousIPs = Database::fetchAll("
                SELECT ip_address, COUNT(*) as attempts
                FROM login_attempts 
                WHERE success = 0 
                AND attempt_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
                GROUP BY ip_address 
                HAVING attempts > 5
            ");
            
            $blockedCount = 0;
            foreach ($suspiciousIPs as $ip) {
                try {
                    Database::execute(
                        "INSERT INTO ip_blacklist (ip_address, reason, blocked_by, blocked_until) 
                         VALUES (?, ?, ?, ?) 
                         ON DUPLICATE KEY UPDATE reason = ?, blocked_until = ?",
                        [
                            $ip['ip_address'], 
                            'Автоматическая блокировка', 
                            $_SESSION['admin_user_id'], 
                            date('Y-m-d H:i:s', strtotime('+24 hours')),
                            'Автоматическая блокировка',
                            date('Y-m-d H:i:s', strtotime('+24 hours'))
                        ]
                    );
                    $blockedCount++;
                } catch (Exception $e) {
                    // Продолжаем с другими IP
                }
            }
            
            // Логируем действие
            $this->logAdminAction('block_all_suspicious_ips', null, [
                'admin_id' => $_SESSION['admin_user_id'],
                'blocked_count' => $blockedCount
            ]);
            
            echo json_encode(['success' => true, 'blocked_count' => $blockedCount]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function getRealTimeStats() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            require_once 'engine/main/db.php';
            
            $stats = [];
            
            // Активные сессии за последние 5 минут
            $activeSessions5min = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM user_sessions 
                WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            ")['count'];
            
            // Неудачные попытки входа за последние 5 минут
            $failedLogins5min = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM login_attempts 
                WHERE success = 0 
                AND attempt_time > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            ")['count'];
            
            // Подозрительная активность за последние 5 минут
            $suspiciousActivity5min = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM user_activity 
                WHERE action_type IN ('suspicious_login', 'failed_login', 'blocked_ip')
                AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            ")['count'];
            
            $stats = [
                'active_sessions_5min' => $activeSessions5min,
                'failed_logins_5min' => $failedLogins5min,
                'suspicious_activity_5min' => $suspiciousActivity5min,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            echo json_encode($stats);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function getSecurityAlerts() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            require_once 'engine/main/db.php';
            
            $alerts = [];
            
            // Критические уведомления
            $criticalNotifications = Database::fetchAll("
                SELECT * FROM security_notifications 
                WHERE severity = 'critical' AND is_read = 0 
                ORDER BY created_at DESC 
                LIMIT 5
            ");
            
            // Подозрительные IP за последний час
            $suspiciousIPs = Database::fetchAll("
                SELECT ip_address, COUNT(*) as attempts
                FROM login_attempts 
                WHERE success = 0 
                AND attempt_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
                GROUP BY ip_address 
                HAVING attempts > 10
                ORDER BY attempts DESC 
                LIMIT 5
            ");
            
            // Пользователи с множественными неудачными попытками
            $usersWithFailures = Database::fetchAll("
                SELECT u.user_id, u.user_login, u.user_fio, COUNT(la.attempt_id) as failed_attempts
                FROM users u
                JOIN login_attempts la ON u.user_id = la.user_id
                WHERE la.success = 0 
                AND la.attempt_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
                GROUP BY u.user_id
                HAVING failed_attempts > 5
                ORDER BY failed_attempts DESC 
                LIMIT 5
            ");
            
            $alerts = [
                'critical_notifications' => $criticalNotifications,
                'suspicious_ips' => $suspiciousIPs,
                'users_with_failures' => $usersWithFailures,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            echo json_encode($alerts);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Приватные методы для получения данных
    
    private function getComprehensiveAnalytics() {
        $analytics = [];
        
        // Общая статистика пользователей
        $totalUsers = Database::fetchOne("SELECT COUNT(*) as count FROM users")['count'];
        $activeUsers = Database::fetchOne("SELECT COUNT(*) as count FROM users WHERE user_status = 1")['count'];
        $blockedUsers = Database::fetchOne("SELECT COUNT(*) as count FROM users WHERE user_status = 0")['count'];
        
        $analytics['users'] = [
            'total' => $totalUsers,
            'active' => $activeUsers,
            'blocked' => $blockedUsers,
            'active_percentage' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0
        ];
        
        // Статистика входов за последние 24 часа
        $successfulLogins24h = Database::fetchOne("SELECT COUNT(*) as count FROM login_attempts WHERE success = 1 AND attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)")['count'];
        $failedLogins24h = Database::fetchOne("SELECT COUNT(*) as count FROM login_attempts WHERE success = 0 AND attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)")['count'];
        
        $analytics['logins_24h'] = [
            'successful' => $successfulLogins24h,
            'failed' => $failedLogins24h,
            'total' => $successfulLogins24h + $failedLogins24h,
            'success_rate' => ($successfulLogins24h + $failedLogins24h) > 0 ? round(($successfulLogins24h / ($successfulLogins24h + $failedLogins24h)) * 100, 2) : 0
        ];
        
        // Статистика сессий
        $activeSessions = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions WHERE is_active = 1")['count'];
        $totalSessions24h = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)")['count'];
        
        $analytics['sessions'] = [
            'active' => $activeSessions,
            'created_24h' => $totalSessions24h,
            'avg_duration' => $this->getAverageSessionDuration()
        ];
        
        // Статистика безопасности
        $suspiciousActivities24h = Database::fetchOne("SELECT COUNT(*) as count FROM user_activity WHERE action_type IN ('suspicious_login', 'failed_login', 'blocked_ip') AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)")['count'];
        $blockedIPs = Database::fetchOne("SELECT COUNT(*) as count FROM ip_blacklist")['count'];
        
        $analytics['security'] = [
            'suspicious_activities_24h' => $suspiciousActivities24h,
            'blocked_ips' => $blockedIPs,
            'threat_level' => $this->calculateThreatLevel()
        ];
        
        return $analytics;
    }
    
    private function getChartData() {
        $chartData = [];
        
        // Данные для графика активности за последние 7 дней
        $activityData = Database::fetchAll("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM user_activity 
            WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date
        ");
        
        $chartData['activity_7_days'] = $activityData;
        
        // Данные для графика входов за последние 24 часа
        $loginData = Database::fetchAll("
            SELECT HOUR(attempt_time) as hour, 
                   SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful,
                   SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed
            FROM login_attempts 
            WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY HOUR(attempt_time)
            ORDER BY hour
        ");
        
        $chartData['logins_24h'] = $loginData;
        
        // Данные для графика подозрительной активности
        $suspiciousData = Database::fetchAll("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM user_activity 
            WHERE action_type IN ('suspicious_login', 'failed_login', 'blocked_ip')
            AND created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date
        ");
        
        $chartData['suspicious_7_days'] = $suspiciousData;
        
        return $chartData;
    }
    
    private function getSecurityNotifications() {
        return Database::fetchAll("
            SELECT * FROM security_notifications 
            WHERE is_read = 0 
            ORDER BY created_at DESC 
            LIMIT 10
        ");
    }
    
    private function getSuspiciousIPs() {
        return Database::fetchAll("
            SELECT ip_address, COUNT(*) as attempts, 
                   SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful,
                   SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed
            FROM login_attempts 
            WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY ip_address 
            HAVING failed > 5 OR attempts > 20
            ORDER BY failed DESC, attempts DESC
            LIMIT 10
        ");
    }
    
    private function getUserActivityStats() {
        return Database::fetchAll("
            SELECT u.user_id, u.user_login, u.user_fio,
                   COUNT(ua.activity_id) as activity_count,
                   MAX(ua.created_at) as last_activity
            FROM users u
            LEFT JOIN user_activity ua ON u.user_id = ua.user_id
            WHERE ua.created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY u.user_id
            ORDER BY activity_count DESC
            LIMIT 10
        ");
    }
    
    private function getDetailedSecurityStats() {
        $stats = [];
        
        // Статистика по типам угроз
        $threatTypes = Database::fetchAll("
            SELECT action_type, COUNT(*) as count
            FROM user_activity 
            WHERE action_type IN ('suspicious_login', 'failed_login', 'blocked_ip', 'multiple_failures')
            AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY action_type
        ");
        
        $stats['threat_types'] = $threatTypes;
        
        // Статистика по времени
        $hourlyStats = Database::fetchAll("
            SELECT HOUR(created_at) as hour, COUNT(*) as count
            FROM user_activity 
            WHERE action_type IN ('suspicious_login', 'failed_login', 'blocked_ip')
            AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY HOUR(created_at)
            ORDER BY hour
        ");
        
        $stats['hourly_stats'] = $hourlyStats;
        
        // Статистика по IP адресам
        $ipStats = Database::fetchAll("
            SELECT ip_address, COUNT(*) as attempts,
                   SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful,
                   SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed
            FROM login_attempts 
            WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY ip_address
            ORDER BY failed DESC
            LIMIT 20
        ");
        
        $stats['ip_stats'] = $ipStats;
        
        return $stats;
    }
    
    private function getThreatAnalysis() {
        $analysis = [];
        
        // Анализ рисков
        $highRiskUsers = Database::fetchAll("
            SELECT u.user_id, u.user_login, u.user_fio,
                   COUNT(la.attempt_id) as failed_attempts,
                   COUNT(DISTINCT la.ip_address) as unique_ips
            FROM users u
            JOIN login_attempts la ON u.user_id = la.user_id
            WHERE la.success = 0 
            AND la.attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY u.user_id
            HAVING failed_attempts > 5 OR unique_ips > 3
        ");
        
        $analysis['high_risk_users'] = $highRiskUsers;
        
        // Анализ подозрительных паттернов
        $suspiciousPatterns = Database::fetchAll("
            SELECT ip_address, COUNT(*) as attempts,
                   COUNT(DISTINCT user_id) as unique_users
            FROM login_attempts 
            WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY ip_address
            HAVING attempts > 10 OR unique_users > 5
            ORDER BY attempts DESC
        ");
        
        $analysis['suspicious_patterns'] = $suspiciousPatterns;
        
        return $analysis;
    }
    
    private function getIPStatistics() {
        return Database::fetchAll("
            SELECT ip_address,
                   COUNT(*) as total_attempts,
                   SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful_attempts,
                   SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed_attempts,
                   COUNT(DISTINCT user_id) as unique_users,
                   MIN(attempt_time) as first_attempt,
                   MAX(attempt_time) as last_attempt
            FROM login_attempts 
            WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY ip_address
            ORDER BY failed_attempts DESC, total_attempts DESC
            LIMIT 50
        ");
    }
    
    private function getIncidentHistory() {
        return Database::fetchAll("
            SELECT sn.*, 
                   CASE 
                       WHEN sn.notification_type = 'suspicious_login' THEN 'Подозрительный вход'
                       WHEN sn.notification_type = 'failed_login' THEN 'Неудачная попытка входа'
                       WHEN sn.notification_type = 'blocked_ip' THEN 'IP заблокирован'
                       WHEN sn.notification_type = 'multiple_failures' THEN 'Множественные неудачи'
                       ELSE sn.notification_type
                   END as type_name
            FROM security_notifications sn
            ORDER BY created_at DESC
            LIMIT 50
        ");
    }
    
    private function getUserDetailedActivity($userId) {
        $activity = [];
        
        // История входов
        $loginHistory = Database::fetchAll("
            SELECT * FROM login_attempts 
            WHERE user_id = ? 
            ORDER BY attempt_time DESC 
            LIMIT 20
        ", [$userId]);
        
        $activity['login_history'] = $loginHistory;
        
        // История сессий
        $sessionHistory = Database::fetchAll("
            SELECT * FROM user_sessions 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 20
        ", [$userId]);
        
        $activity['session_history'] = $sessionHistory;
        
        // История активности
        $userActivity = Database::fetchAll("
            SELECT * FROM user_activity 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 50
        ", [$userId]);
        
        $activity['user_activity'] = $userActivity;
        
        // Статистика по IP адресам
        $ipStats = Database::fetchAll("
            SELECT ip_address, COUNT(*) as attempts,
                   SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful,
                   SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed
            FROM login_attempts 
            WHERE user_id = ?
            GROUP BY ip_address
            ORDER BY attempts DESC
        ", [$userId]);
        
        $activity['ip_stats'] = $ipStats;
        
        return $activity;
    }
    
    private function getActivityStatistics() {
        return Database::fetchAll("
            SELECT 
                u.user_id,
                u.user_login,
                u.user_fio,
                u.is_active,
                COUNT(la.attempt_id) as login_attempts,
                SUM(CASE WHEN la.success = 1 THEN 1 ELSE 0 END) as successful_logins,
                SUM(CASE WHEN la.success = 0 THEN 1 ELSE 0 END) as failed_logins,
                COUNT(DISTINCT la.ip_address) as unique_ips,
                MAX(la.attempt_time) as last_login_attempt,
                COUNT(ua.activity_id) as activity_count,
                MAX(ua.created_at) as last_activity
            FROM users u
            LEFT JOIN login_attempts la ON u.user_id = la.user_id
            LEFT JOIN user_activity ua ON u.user_id = ua.user_id
            WHERE la.attempt_time > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY u.user_id
            ORDER BY activity_count DESC, login_attempts DESC
        ");
    }
    
    private function getSessionStatistics() {
        $stats = [];
        
        // Общая статистика сессий
        $totalSessions = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions")['count'];
        $activeSessions = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions WHERE is_active = 1")['count'];
        $sessions24h = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)")['count'];
        
        $stats['general'] = [
            'total' => $totalSessions,
            'active' => $activeSessions,
            'created_24h' => $sessions24h,
            'avg_duration' => $this->getAverageSessionDuration()
        ];
        
        // Статистика по пользователям
        $userSessionStats = Database::fetchAll("
            SELECT u.user_id, u.user_login, u.user_fio,
                   COUNT(us.session_id) as total_sessions,
                   SUM(CASE WHEN us.is_active = 1 THEN 1 ELSE 0 END) as active_sessions,
                   MAX(us.created_at) as last_session
            FROM users u
            LEFT JOIN user_sessions us ON u.user_id = us.user_id
            GROUP BY u.user_id
            HAVING total_sessions > 0
            ORDER BY active_sessions DESC, total_sessions DESC
        ");
        
        $stats['by_user'] = $userSessionStats;
        
        return $stats;
    }
    
    private function getActiveSessions() {
        return Database::fetchAll("
            SELECT us.*, u.user_login, u.user_fio
            FROM user_sessions us
            JOIN users u ON us.user_id = u.user_id
            WHERE us.is_active = 1
            ORDER BY us.last_activity DESC
        ");
    }
    
    private function getSuspiciousSessions() {
        return Database::fetchAll("
            SELECT us.*, u.user_login, u.user_fio,
                   COUNT(la.attempt_id) as failed_attempts
            FROM user_sessions us
            JOIN users u ON us.user_id = u.user_id
            LEFT JOIN login_attempts la ON us.ip_address = la.ip_address 
                AND la.success = 0 
                AND la.attempt_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            WHERE us.is_active = 1
            GROUP BY us.session_id
            HAVING failed_attempts > 3
            ORDER BY failed_attempts DESC
        ");
    }
    
    // API методы для графиков
    
    private function getSecurityStatsAPI() {
        return $this->getComprehensiveAnalytics();
    }
    
    private function getActivityChartData() {
        return $this->getChartData();
    }
    
    private function getLoginAttemptsData() {
        return Database::fetchAll("
            SELECT DATE(attempt_time) as date, HOUR(attempt_time) as hour,
                   SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful,
                   SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed
            FROM login_attempts 
            WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(attempt_time), HOUR(attempt_time)
            ORDER BY date, hour
        ");
    }
    
    private function getSessionsData() {
        return Database::fetchAll("
            SELECT DATE(created_at) as date, COUNT(*) as sessions
            FROM user_sessions 
            WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date
        ");
    }
    
    private function getGeneralStatsAPI() {
        return [
            'users' => $this->getComprehensiveAnalytics()['users'],
            'security' => $this->getComprehensiveAnalytics()['security'],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    // Вспомогательные методы
    
    private function getAverageSessionDuration() {
        $result = Database::fetchOne("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, last_activity)) as avg_duration
            FROM user_sessions 
            WHERE is_active = 0 
            AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ");
        
        return round($result['avg_duration'] ?? 0, 2);
    }
    
    private function calculateThreatLevel() {
        $suspiciousActivities = Database::fetchOne("
            SELECT COUNT(*) as count 
            FROM user_activity 
            WHERE action_type IN ('suspicious_login', 'failed_login', 'blocked_ip')
            AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ")['count'];
        
        if ($suspiciousActivities > 50) return 'critical';
        if ($suspiciousActivities > 20) return 'high';
        if ($suspiciousActivities > 10) return 'medium';
        return 'low';
    }
}
?> 