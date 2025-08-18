<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';
require_once 'application/models/SecureAnalyticsModel.php';

class EnhancedAnalyticsController extends BaseAdminController {
    
    private $secureModel;
    
    public function __construct() {
        parent::__construct();
        require_once 'engine/main/encryption.php';
        $this->secureModel = new SecureAnalyticsModel();
    }
    
    // Главная страница расширенной аналитики
    public function index() {
        $this->requireAccessLevel(10);
        
        try {
            require_once 'engine/main/db.php';
            
            // Получаем комплексную аналитику
            $analytics = $this->getEnhancedAnalytics();
            
            // Данные для интерактивных графиков
            $chartData = $this->getAdvancedChartData();
            
            // Геолокация и карта
            $geolocationData = $this->getGeolocationData();
            
            // Поведенческая аналитика
            $behaviorData = $this->getBehaviorAnalytics();
            
            // ML аномалии
            $mlAnomalies = $this->getMLAnomalies();
            
            // Реал-тайм активность
            $realtimeData = $this->getRealtimeData();
            
            // Добавляем дополнительные данные для отображения
            $analytics['new_users_today'] = $this->getNewUsersToday();
            $analytics['active_sessions'] = $this->getActiveSessions();
            $analytics['unread_alerts'] = $this->getUnreadAlertsCount();
            
            $this->render('admin/enhanced-analytics/index', [
                'title' => 'Расширенная аналитика',
                'analytics' => $analytics,
                'chartData' => $chartData,
                'geolocationData' => $geolocationData,
                'behaviorData' => $behaviorData,
                'mlAnomalies' => $mlAnomalies,
                'realtimeData' => $realtimeData
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке расширенной аналитики: ' . $e->getMessage()
            ]);
        }
    }
    
    // Геолокация и карта активности
    public function geolocation() {
        $this->requireAccessLevel(10);
        
        try {
            require_once 'engine/main/db.php';
            
            $geolocationStats = $this->getGeolocationStatistics();
            $suspiciousLocations = $this->getSuspiciousLocations();
            $countryStats = $this->getCountryStatistics();
            $mapData = $this->getMapData();
            
            $this->render('admin/enhanced-analytics/geolocation', [
                'title' => 'Геолокация активности',
                'geolocationStats' => $geolocationStats,
                'suspiciousLocations' => $suspiciousLocations,
                'countryStats' => $countryStats,
                'mapData' => $mapData
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке геолокации: ' . $e->getMessage()
            ]);
        }
    }
    
    // Поведенческая аналитика
    public function behavior() {
        $this->requireAccessLevel(10);
        
        try {
            require_once 'engine/main/db.php';
            
            $sessionMetrics = $this->getSessionMetrics();
            $engagementMetrics = $this->getEngagementMetrics();
            $userJourneys = $this->getUserJourneys();
            $conversionFunnel = $this->getConversionFunnel();
            $pageAnalytics = $this->getPageAnalytics();
            
            $behaviorData = [
                'session_metrics' => $sessionMetrics,
                'engagement_metrics' => $engagementMetrics,
                'user_journeys' => $userJourneys,
                'conversion_funnel' => $conversionFunnel,
                'page_analytics' => $pageAnalytics
            ];
            
            $this->render('admin/enhanced-analytics/behavior', [
                'title' => 'Поведенческая аналитика',
                'behaviorData' => $behaviorData
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке поведенческой аналитики: ' . $e->getMessage()
            ]);
        }
    }
    
    // ML Аномалии
    public function mlAnomalies() {
        $this->requireAccessLevel(10);
        
        try {
            require_once 'engine/main/db.php';
            
            $recentAnomalies = $this->getRecentAnomalies();
            $anomalyTypes = $this->getAnomalyTypes();
            $riskDistribution = $this->getRiskDistribution();
            $threatAnalysis = $this->getThreatAnalysis();
            
            $this->render('admin/enhanced-analytics/ml-anomalies', [
                'title' => 'ML Аномалии',
                'recentAnomalies' => $recentAnomalies,
                'anomalyTypes' => $anomalyTypes,
                'riskDistribution' => $riskDistribution,
                'threatAnalysis' => $threatAnalysis
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке ML аномалий: ' . $e->getMessage()
            ]);
        }
    }
    
    // Уведомления
    public function notifications() {
        $this->requireAccessLevel(10);
        
        try {
            require_once 'engine/main/db.php';
            
            $securityAlerts = $this->getSecurityAlerts();
            $unreadCount = $this->getUnreadAlertsCount();
            
            $this->render('admin/enhanced-analytics/notifications', [
                'title' => 'Уведомления безопасности',
                'securityAlerts' => $securityAlerts,
                'unreadCount' => $unreadCount
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке уведомлений: ' . $e->getMessage()
            ]);
        }
    }
    
    // Отчеты
    public function reports() {
        $this->requireAccessLevel(10);
        
        try {
            require_once 'engine/main/db.php';
            
            $reportTemplates = $this->getReportTemplates();
            $exportHistory = $this->getExportHistory();
            
            $this->render('admin/enhanced-analytics/reports', [
                'title' => 'Отчеты и экспорт',
                'reportTemplates' => $reportTemplates,
                'exportHistory' => $exportHistory
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке отчетов: ' . $e->getMessage()
            ]);
        }
    }
    
    // API endpoints
    public function api() {
        $this->requireAccessLevel(10);
        
        $action = $_GET['action'] ?? '';
        
        try {
            require_once 'engine/main/db.php';
            
            switch ($action) {
                case 'realtime':
                    $data = $this->getRealtimeData();
                    break;
                case 'geolocation':
                    $data = $this->getGeolocationData();
                    break;
                case 'behavior':
                    $data = $this->getBehaviorAnalytics();
                    break;
                case 'anomalies':
                    $data = $this->getMLAnomalies();
                    break;
                default:
                    $data = ['error' => 'Неизвестное действие'];
            }
            
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Настройки
    public function settings() {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Обработка настроек
                $this->updateNotificationSettings();
                $this->setFlashMessage('success', 'Настройки обновлены');
                $this->redirect('/admin/enhanced-analytics/settings');
            } catch (Exception $e) {
                $this->setFlashMessage('error', 'Ошибка при обновлении настроек: ' . $e->getMessage());
            }
        }
        
        $this->render('admin/enhanced-analytics/settings', [
            'title' => 'Настройки аналитики'
        ]);
    }
    
    // Методы для получения данных
    private function getEnhancedAnalytics() {
        try {
            return [
                'total_users' => $this->getTotalUsers(),
                'active_users' => $this->getActiveUsers(),
                'total_sessions' => $this->getTotalSessions(),
                'active_sessions' => $this->getActiveSessions(),
                'failed_logins_24h' => $this->getFailedLogins24h(),
                'suspicious_activities' => $this->getSuspiciousActivitiesCount()
            ];
        } catch (Exception $e) {
            error_log("Error in getEnhancedAnalytics: " . $e->getMessage());
            return [];
        }
    }
    
    private function getAdvancedChartData() {
        try {
            return [
                'activity_timeline' => $this->getActivityTimeline(),
                'user_growth' => $this->getUserGrowth(),
                'session_analytics' => $this->getSessionAnalytics(),
                'geographic_distribution' => $this->getGeographicDistribution()
            ];
        } catch (Exception $e) {
            error_log("Error in getAdvancedChartData: " . $e->getMessage());
            return [];
        }
    }
    
    private function getGeolocationData() {
        try {
            return [
                'countries' => $this->getCountriesData(),
                'cities' => $this->getCitiesData(),
                'suspicious_locations' => $this->getSuspiciousLocations(),
                'map_data' => $this->getMapData()
            ];
        } catch (Exception $e) {
            error_log("Error in getGeolocationData: " . $e->getMessage());
            return [];
        }
    }
    
    private function getBehaviorAnalytics() {
        try {
            return [
                'session_metrics' => $this->getSessionMetrics(),
                'engagement_metrics' => $this->getEngagementMetrics(),
                'user_journeys' => $this->getUserJourneys(),
                'conversion_funnel' => $this->getConversionFunnel()
            ];
        } catch (Exception $e) {
            error_log("Error in getBehaviorAnalytics: " . $e->getMessage());
            return [];
        }
    }
    
    private function getMLAnomalies() {
        try {
            return [
                'recent_anomalies' => $this->getRecentAnomalies(),
                'anomaly_types' => $this->getAnomalyTypes(),
                'risk_distribution' => $this->getRiskDistribution(),
                'threat_analysis' => $this->getThreatAnalysis()
            ];
        } catch (Exception $e) {
            error_log("Error in getMLAnomalies: " . $e->getMessage());
            return [];
        }
    }
    
    private function getRealtimeData() {
        try {
            return [
                'current_sessions' => $this->getCurrentSessions(),
                'recent_activities' => $this->getRecentActivities(),
                'security_alerts' => $this->getSecurityAlerts()
            ];
        } catch (Exception $e) {
            error_log("Error in getRealtimeData: " . $e->getMessage());
            return [];
        }
    }
    
    // Методы для получения базовых метрик
    private function getTotalUsers() {
        try {
            $result = Database::fetchOne("SELECT COUNT(*) as count FROM users");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getActiveUsers() {
        try {
            $result = Database::fetchOne("
                SELECT COUNT(DISTINCT user_id) as count 
                FROM user_sessions 
                WHERE is_active = 1 
                AND last_activity >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
            ");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getTotalSessions() {
        try {
            $result = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getActiveSessions() {
        try {
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM user_sessions 
                WHERE is_active = 1
            ");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getFailedLogins24h() {
        try {
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM login_attempts 
                WHERE success = 0 
                AND attempt_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getSuspiciousActivitiesCount() {
        try {
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM user_activity 
                WHERE suspicious = 1 
                AND activity_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getCurrentSessions() {
        try {
            return Database::fetchAll("
                SELECT us.*, u.user_fio, u.user_login
                FROM user_sessions us
                LEFT JOIN users u ON us.user_id = u.user_id
                WHERE us.is_active = 1
                ORDER BY us.last_activity DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Методы для геолокации
    private function getCountriesData() {
        try {
            return Database::fetchAll("
                SELECT country, COUNT(*) as count 
                FROM ip_geolocation 
                WHERE country IS NOT NULL 
                GROUP BY country 
                ORDER BY count DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getCitiesData() {
        try {
            return Database::fetchAll("
                SELECT city, COUNT(*) as count 
                FROM ip_geolocation 
                WHERE city IS NOT NULL 
                GROUP BY city 
                ORDER BY count DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Методы для сессий и вовлеченности
    private function getSessionMetrics() {
        try {
            $avgDuration = Database::fetchOne("
                SELECT AVG(session_duration) as avg_duration 
                FROM user_behavior 
                WHERE session_duration > 0
            ")['avg_duration'] ?? 0;
            
            $avgClicks = Database::fetchOne("
                SELECT AVG(click_count) as avg_clicks 
                FROM user_behavior 
                WHERE click_count > 0
            ")['avg_clicks'] ?? 0;
            
            $avgScrollDepth = Database::fetchOne("
                SELECT AVG(scroll_depth) as avg_scroll 
                FROM user_behavior 
                WHERE scroll_depth > 0
            ")['avg_scroll'] ?? 0;
            
            return [
                'avg_session_duration' => round($avgDuration, 2),
                'avg_clicks' => round($avgClicks, 2),
                'avg_scroll_depth' => round($avgScrollDepth, 2)
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getEngagementMetrics() {
        try {
            $formInteractions = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM user_behavior 
                WHERE form_interactions > 0
            ")['count'] ?? 0;
            
            $fileDownloads = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM user_behavior 
                WHERE file_downloads > 0
            ")['count'] ?? 0;
            
            return [
                'form_interactions' => $formInteractions,
                'file_downloads' => $fileDownloads
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getUserGrowth() {
        try {
            return Database::fetchAll("
                SELECT 
                    DATE(user_date_reg) as date,
                    COUNT(*) as new_users
                FROM users 
                WHERE user_date_reg >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(user_date_reg)
                ORDER BY date
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getSessionAnalytics() {
        try {
            return Database::fetchAll("
                SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as sessions,
                    AVG(session_duration) as avg_duration
                FROM user_behavior 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Методы для геолокации
    private function getGeolocationStatistics() {
        try {
            $stats = [];
            
            // Получаем статистику по странам
            $countries = Database::fetchAll("
                SELECT country, COUNT(*) as count 
                FROM ip_geolocation 
                WHERE country IS NOT NULL 
                GROUP BY country 
                ORDER BY count DESC
            ");
            $stats['countries'] = $countries;
            
            // Получаем статистику по городам
            $cities = Database::fetchAll("
                SELECT city, COUNT(*) as count 
                FROM ip_geolocation 
                WHERE city IS NOT NULL 
                GROUP BY city 
                ORDER BY count DESC
            ");
            $stats['cities'] = $cities;
            
            // Топ город
            $topCity = Database::fetchOne("
                SELECT city, COUNT(*) as count 
                FROM ip_geolocation 
                WHERE city IS NOT NULL 
                GROUP BY city 
                ORDER BY count DESC 
                LIMIT 1
            ");
            $stats['top_city'] = $topCity['city'] ?? 'Нет данных';
            
            // Активные страны
            $activeCountries = Database::fetchAll("
                SELECT DISTINCT ig.country 
                FROM ip_geolocation ig
                JOIN user_activity ua ON ig.ip_address = ua.ip_address
                WHERE ua.activity_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                AND ig.country IS NOT NULL
            ");
            $stats['active_countries'] = $activeCountries;
            
            // Подозрительные страны
            $suspiciousCountries = Database::fetchAll("
                SELECT DISTINCT ig.country 
                FROM ip_geolocation ig
                JOIN user_activity ua ON ig.ip_address = ua.ip_address
                WHERE ua.suspicious = 1
                AND ig.country IS NOT NULL
            ");
            $stats['suspicious_countries'] = $suspiciousCountries;
            
            // Заблокированные страны (симуляция)
            $stats['blocked_countries'] = [];
            
            return $stats;
        } catch (Exception $e) {
            error_log("Error in getGeolocationStatistics: " . $e->getMessage());
            return [];
        }
    }
    
    private function getCountryStatistics() {
        try {
            return Database::fetchAll("
                SELECT 
                    ig.country,
                    COUNT(DISTINCT ua.user_id) as user_count,
                    COUNT(DISTINCT ig.city) as cities_count,
                    CASE 
                        WHEN COUNT(ua.activity_id) > 100 THEN 'high'
                        WHEN COUNT(ua.activity_id) > 50 THEN 'medium'
                        ELSE 'low'
                    END as risk_level
                FROM ip_geolocation ig
                LEFT JOIN user_activity ua ON ig.ip_address = ua.ip_address
                WHERE ig.country IS NOT NULL
                GROUP BY ig.country
                ORDER BY user_count DESC
                LIMIT 20
            ");
        } catch (Exception $e) {
            error_log("Error in getCountryStatistics: " . $e->getMessage());
            return [];
        }
    }
    
    private function getSuspiciousLocations() {
        try {
            return Database::fetchAll("
                SELECT 
                    ig.city,
                    ig.country,
                    ig.latitude,
                    ig.longitude,
                    ua.ip_address,
                    COUNT(ua.activity_id) as activity_count,
                    CASE 
                        WHEN COUNT(ua.activity_id) > 50 THEN 'high'
                        WHEN COUNT(ua.activity_id) > 20 THEN 'medium'
                        ELSE 'low'
                    END as risk_level
                FROM ip_geolocation ig
                JOIN user_activity ua ON ig.ip_address = ua.ip_address
                WHERE ua.suspicious = 1
                AND ig.city IS NOT NULL
                GROUP BY ig.city, ig.country, ig.latitude, ig.longitude, ua.ip_address
                ORDER BY activity_count DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            error_log("Error in getSuspiciousLocations: " . $e->getMessage());
            return [];
        }
    }
    
    // Методы для поведения
    private function getUserJourneys() {
        try {
            return Database::fetchAll("
                SELECT 
                    user_id,
                    GROUP_CONCAT(page_url ORDER BY created_at SEPARATOR ' → ') as path_sequence,
                    COUNT(DISTINCT page_url) as total_pages,
                    SUM(session_duration) as session_duration
                FROM user_behavior 
                WHERE session_duration > 0
                GROUP BY user_id
                ORDER BY session_duration DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            error_log("Error in getUserJourneys: " . $e->getMessage());
            return [];
        }
    }
    
    private function getConversionFunnel() {
        try {
            $funnel = [];
            
            // Общее количество пользователей
            $totalUsers = Database::fetchOne("SELECT COUNT(*) as count FROM users")['count'] ?? 0;
            $funnel['total_users'] = $totalUsers;
            
            // Пользователи с активностью
            $activeUsers = Database::fetchOne("
                SELECT COUNT(DISTINCT user_id) as count 
                FROM user_activity 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ")['count'] ?? 0;
            $funnel['active_users'] = $activeUsers;
            
            // Пользователи с взаимодействиями
            $interactingUsers = Database::fetchOne("
                SELECT COUNT(DISTINCT user_id) as count 
                FROM user_behavior 
                WHERE form_interactions > 0 OR file_downloads > 0
            ")['count'] ?? 0;
            $funnel['interacting_users'] = $interactingUsers;
            
            // Процент отказов
            $funnel['bounce_rate'] = round((($totalUsers - $activeUsers) / $totalUsers) * 100, 1);
            
            return $funnel;
        } catch (Exception $e) {
            error_log("Error in getConversionFunnel: " . $e->getMessage());
            return [];
        }
    }
    
    private function getPageAnalytics() {
        try {
            return Database::fetchAll("
                SELECT 
                    page_url,
                    COUNT(*) as views,
                    AVG(time_spent) as avg_time,
                    COUNT(DISTINCT user_id) as unique_users
                FROM user_behavior 
                GROUP BY page_url
                ORDER BY views DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            error_log("Error in getPageAnalytics: " . $e->getMessage());
            return [];
        }
    }
    
    // Методы для активности
    private function getActivityTimeline() {
        try {
            return Database::fetchAll("
                SELECT 
                    DATE(activity_time) as date,
                    COUNT(*) as count,
                    COUNT(DISTINCT user_id) as unique_users
                FROM user_activity 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(activity_time)
                ORDER BY date
            ");
        } catch (Exception $e) {
            error_log("Error in getActivityTimeline: " . $e->getMessage());
            return [];
        }
    }
    
    private function getGeographicDistribution() {
        try {
            return Database::fetchAll("
                SELECT 
                    ig.latitude,
                    ig.longitude,
                    ig.city,
                    ig.country,
                    COUNT(ua.activity_id) as count
                FROM ip_geolocation ig
                JOIN user_activity ua ON ig.ip_address = ua.ip_address
                WHERE ig.latitude IS NOT NULL 
                AND ig.longitude IS NOT NULL
                AND ua.activity_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY ig.latitude, ig.longitude, ig.city, ig.country
                ORDER BY count DESC
                LIMIT 50
            ");
        } catch (Exception $e) {
            error_log("Error in getGeographicDistribution: " . $e->getMessage());
            return [];
        }
    }
    
    // Методы для ML аномалий
    private function getRecentAnomalies() {
        try {
            return Database::fetchAll("
                SELECT 
                    ma.*,
                    u.user_fio,
                    u.user_login
                FROM ml_anomalies ma
                LEFT JOIN users u ON ma.user_id = u.user_id
                ORDER BY ma.created_at DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            error_log("Error in getRecentAnomalies: " . $e->getMessage());
            return [];
        }
    }
    
    private function getAnomalyTypes() {
        try {
            return Database::fetchAll("
                SELECT 
                    anomaly_type,
                    risk_level,
                    COUNT(*) as count
                FROM ml_anomalies 
                GROUP BY anomaly_type, risk_level
                ORDER BY count DESC
            ");
        } catch (Exception $e) {
            error_log("Error in getAnomalyTypes: " . $e->getMessage());
            return [];
        }
    }
    
    private function getRiskDistribution() {
        try {
            return Database::fetchAll("
                SELECT 
                    risk_level,
                    COUNT(*) as count
                FROM ml_anomalies 
                GROUP BY risk_level
                ORDER BY count DESC
            ");
        } catch (Exception $e) {
            error_log("Error in getRiskDistribution: " . $e->getMessage());
            return [];
        }
    }
    
    private function getThreatAnalysis() {
        try {
            return [
                'total_threats' => Database::fetchOne("SELECT COUNT(*) as count FROM ml_anomalies")['count'] ?? 0,
                'high_risk_threats' => Database::fetchOne("SELECT COUNT(*) as count FROM ml_anomalies WHERE risk_level IN ('high', 'critical')")['count'] ?? 0,
                'accuracy_rate' => $this->calculateAccuracyRate(),
                'false_positive_rate' => Database::fetchOne("SELECT COUNT(*) as count FROM ml_anomalies WHERE is_false_positive = 1")['count'] ?? 0
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Методы для уведомлений
    private function getSecurityAlerts() {
        try {
            // Симулируем уведомления безопасности
            return [
                [
                    'id' => 1,
                    'title' => 'Подозрительная активность',
                    'description' => 'Обнаружена необычная активность с IP адреса',
                    'severity' => 'high',
                    'type' => 'suspicious_activity',
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'title' => 'Множественные неудачные попытки входа',
                    'description' => 'Зафиксировано 5 неудачных попыток входа подряд',
                    'severity' => 'medium',
                    'type' => 'failed_login',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
                ]
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getUnreadAlertsCount() {
        try {
            // Симулируем количество непрочитанных уведомлений
            return 2;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    // Методы для карты
    private function getMapData() {
        try {
            return Database::fetchAll("
                SELECT 
                    ig.latitude,
                    ig.longitude,
                    COUNT(ua.activity_id) as count
                FROM ip_geolocation ig
                JOIN user_activity ua ON ig.ip_address = ua.ip_address
                WHERE ig.latitude IS NOT NULL 
                AND ig.longitude IS NOT NULL
                AND ua.activity_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY ig.latitude, ig.longitude
                ORDER BY count DESC
                LIMIT 100
            ");
        } catch (Exception $e) {
            error_log("Error in getMapData: " . $e->getMessage());
            return [];
        }
    }
    
    // Методы для отчетов
    private function getReportTemplates() {
        return [
            'daily_summary' => 'Ежедневный отчет',
            'weekly_analytics' => 'Недельная аналитика',
            'security_report' => 'Отчет по безопасности',
            'user_behavior' => 'Поведение пользователей'
        ];
    }
    
    private function getExportHistory() {
        return [
            [
                'id' => 1,
                'type' => 'daily_summary',
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'completed'
            ]
        ];
    }
    
    // Вспомогательные методы
    private function calculateAccuracyRate() {
        try {
            $totalAnomalies = Database::fetchOne("SELECT COUNT(*) as count FROM ml_anomalies")['count'] ?? 0;
            $falsePositives = Database::fetchOne("SELECT COUNT(*) as count FROM ml_anomalies WHERE is_false_positive = 1")['count'] ?? 0;
            
            if ($totalAnomalies > 0) {
                return round((($totalAnomalies - $falsePositives) / $totalAnomalies) * 100, 2);
            }
            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getRecentActivities() {
        try {
            return Database::fetchAll("
                SELECT ua.*, u.user_fio, u.user_login
                FROM user_activity ua
                LEFT JOIN users u ON ua.user_id = u.user_id
                ORDER BY ua.activity_time DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function updateNotificationSettings() {
        // Логика обновления настроек уведомлений
    }
    
    private function markAlertRead() {
        // Логика отметки уведомления как прочитанного
    }
    
    private function exportData() {
        // Логика экспорта данных
    }

    // Методы для получения данных пользователей
    private function getNewUsersToday() {
        try {
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM users 
                WHERE user_date_reg >= CURDATE()
            ");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getNewUsersWeek() {
        try {
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM users 
                WHERE user_date_reg >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function calculateThreatLevel() {
        try {
            // Подсчитываем уровень угрозы на основе различных факторов
            $failedLogins = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM login_attempts 
                WHERE success = 0 
                AND attempt_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ")['count'] ?? 0;
            
            $suspiciousActivities = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM user_activity 
                WHERE suspicious = 1 
                AND activity_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ")['count'] ?? 0;
            
            $anomalies = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM ml_anomalies 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                AND risk_level IN ('high', 'critical')
            ")['count'] ?? 0;
            
            $totalThreats = $failedLogins + $suspiciousActivities + $anomalies;
            
            if ($totalThreats > 50) return 'critical';
            if ($totalThreats > 20) return 'high';
            if ($totalThreats > 10) return 'medium';
            return 'low';
        } catch (Exception $e) {
            return 'low';
        }
    }
    
    private function getAlertsToday() {
        try {
            $result = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM security_alerts 
                WHERE created_at >= CURDATE()
            ");
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getAverageSessionDuration() {
        try {
            $result = Database::fetchOne("
                SELECT AVG(session_duration) as avg_duration 
                FROM user_behavior 
                WHERE session_duration > 0
            ");
            return round($result['avg_duration'] ?? 0, 2);
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getBounceRate() {
        try {
            $totalSessions = Database::fetchOne("SELECT COUNT(*) as count FROM user_sessions")['count'] ?? 0;
            $singlePageSessions = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM user_behavior 
                WHERE page_views = 1
            ")['count'] ?? 0;
            
            if ($totalSessions > 0) {
                return round(($singlePageSessions / $totalSessions) * 100, 2);
            }
            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getPagesPerSession() {
        try {
            $result = Database::fetchOne("
                SELECT AVG(page_views) as avg_pages 
                FROM user_behavior 
                WHERE page_views > 0
            ");
            return round($result['avg_pages'] ?? 0, 2);
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getConversionRate() {
        try {
            $totalUsers = Database::fetchOne("SELECT COUNT(*) as count FROM users")['count'] ?? 0;
            $convertedUsers = Database::fetchOne("
                SELECT COUNT(DISTINCT user_id) as count 
                FROM user_activity 
                WHERE action_type = 'conversion'
            ")['count'] ?? 0;
            
            if ($totalUsers > 0) {
                return round(($convertedUsers / $totalUsers) * 100, 2);
            }
            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getTopCountry() {
        try {
            $result = Database::fetchOne("
                SELECT country, COUNT(*) as count 
                FROM ip_geolocation 
                WHERE country IS NOT NULL 
                GROUP BY country 
                ORDER BY count DESC 
                LIMIT 1
            ");
            return $result['country'] ?? 'Нет данных';
        } catch (Exception $e) {
            return 'Нет данных';
        }
    }

    /**
     * Детальная страница активности
     */
    public function activityDetails()
    {
        try {
            $period = $_GET['period'] ?? 7;
            $activityType = $_GET['activity_type'] ?? '';
            $search = $_GET['search'] ?? '';
            $sortBy = $_GET['sort_by'] ?? 'activity_time';
            $sortOrder = $_GET['sort_order'] ?? 'DESC';
            
            // Валидация параметров сортировки
            $allowedSortFields = ['activity_time', 'user_fio', 'action_type', 'ip_address'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'activity_time';
            }
            
            $allowedSortOrders = ['ASC', 'DESC'];
            if (!in_array($sortOrder, $allowedSortOrders)) {
                $sortOrder = 'DESC';
            }
            
            // Получаем статистику
            $stats = $this->getActivityStats($period, $activityType, $search);
            
            // Получаем данные для графиков
            $chartData = $this->getActivityChartData($period, $activityType, $search);
            
            // Получаем логи активности с поиском и сортировкой
            $activityLogs = $this->getActivityLogs($period, $activityType, $search, $sortBy, $sortOrder);

            include 'application/views/admin/enhanced-analytics/activity-details.php';
        } catch (Exception $e) {
            error_log("Error in activityDetails: " . $e->getMessage());
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке детальной аналитики: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Получение статистики активности
     */
    private function getActivityStats($period, $activityType = '', $search = '')
    {
        $whereConditions = ["DATE(activity_time) >= DATE_SUB(NOW(), INTERVAL ? DAY)"];
        $params = [$period];

        if ($activityType) {
            $whereConditions[] = "action_type = ?";
            $params[] = $activityType;
        }

        if ($search) {
            $whereConditions[] = "(u.user_fio LIKE ? OR u.user_login LIKE ? OR ua.action_type LIKE ? OR ua.ip_address LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Общее количество действий
        $totalActivities = Database::fetchOne("
            SELECT COUNT(*) FROM user_activity ua 
            LEFT JOIN users u ON ua.user_id = u.user_id
            WHERE $whereClause
        ", $params);

        // Уникальных пользователей
        $uniqueUsers = Database::fetchOne("
            SELECT COUNT(DISTINCT ua.user_id) FROM user_activity ua 
            LEFT JOIN users u ON ua.user_id = u.user_id
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
    private function getActivityChartData($period, $activityType = '', $search = '')
    {
        $whereConditions = ["DATE(activity_time) >= DATE_SUB(NOW(), INTERVAL ? DAY)"];
        $params = [$period];

        if ($activityType) {
            $whereConditions[] = "action_type = ?";
            $params[] = $activityType;
        }

        if ($search) {
            $whereConditions[] = "(u.user_fio LIKE ? OR u.user_login LIKE ? OR ua.action_type LIKE ? OR ua.ip_address LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Активность по часам
        $hourlyActivity = Database::fetchAll("
            SELECT HOUR(ua.activity_time) as hour, COUNT(*) as count 
            FROM user_activity ua 
            LEFT JOIN users u ON ua.user_id = u.user_id
            WHERE $whereClause 
            GROUP BY HOUR(ua.activity_time) 
            ORDER BY hour
        ", $params);

        // Типы активности
        $activityTypes = Database::fetchAll("
            SELECT ua.action_type as type, COUNT(*) as count 
            FROM user_activity ua 
            LEFT JOIN users u ON ua.user_id = u.user_id
            WHERE $whereClause 
            GROUP BY ua.action_type 
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
    private function getActivityLogs($period, $activityType = '', $search = '', $sortBy = 'activity_time', $sortOrder = 'DESC')
    {
        $whereConditions = ["DATE(ua.activity_time) >= DATE_SUB(NOW(), INTERVAL ? DAY)"];
        $params = [$period];

        if ($activityType) {
            $whereConditions[] = "ua.action_type = ?";
            $params[] = $activityType;
        }

        if ($search) {
            $whereConditions[] = "(u.user_fio LIKE ? OR u.user_login LIKE ? OR ua.action_type LIKE ? OR ua.ip_address LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Обрабатываем сортировку по полям пользователя
        $orderBy = $sortBy;
        if ($sortBy === 'user_fio' || $sortBy === 'user_login') {
            $orderBy = "u.$sortBy";
        } else {
            $orderBy = "ua.$sortBy";
        }
        
        return Database::fetchAll("
            SELECT ua.*, u.user_fio, u.user_login 
            FROM user_activity ua 
            LEFT JOIN users u ON ua.user_id = u.user_id 
            WHERE $whereClause 
            ORDER BY $orderBy $sortOrder 
            LIMIT 100
        ", $params);
    }
}
?> 