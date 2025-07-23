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
        $this->requireAccessLevel(5);
        
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
            
            return $this->render('admin/enhanced-analytics/index', [
                'title' => 'Расширенная аналитика',
                'analytics' => $analytics,
                'chartData' => $chartData,
                'geolocationData' => $geolocationData,
                'behaviorData' => $behaviorData,
                'mlAnomalies' => $mlAnomalies,
                'realtimeData' => $realtimeData
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке расширенной аналитики: ' . $e->getMessage()
            ]);
        }
    }
    
    // Геолокация и карта активности
    public function geolocation() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $geolocationStats = $this->getGeolocationStatistics();
            $suspiciousLocations = $this->getSuspiciousLocations();
            $countryStats = $this->getCountryStatistics();
            
            return $this->render('admin/enhanced-analytics/geolocation', [
                'title' => 'Геолокация активности',
                'geolocationStats' => $geolocationStats,
                'suspiciousLocations' => $suspiciousLocations,
                'countryStats' => $countryStats
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке геолокации: ' . $e->getMessage()
            ]);
        }
    }
    
    // Поведенческая аналитика
    public function behavior() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $behaviorPatterns = $this->getBehaviorPatterns();
            $userJourneys = $this->getUserJourneys();
            $pageAnalytics = $this->getPageAnalytics();
            $conversionFunnel = $this->getConversionFunnel();
            
            return $this->render('admin/enhanced-analytics/behavior', [
                'title' => 'Поведенческая аналитика',
                'behaviorPatterns' => $behaviorPatterns,
                'userJourneys' => $userJourneys,
                'pageAnalytics' => $pageAnalytics,
                'conversionFunnel' => $conversionFunnel
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке поведенческой аналитики: ' . $e->getMessage()
            ]);
        }
    }
    
    // ML аномалии и детекция угроз
    public function mlAnomalies() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $anomalies = $this->getAllAnomalies();
            $threatAnalysis = $this->getThreatAnalysis();
            $falsePositives = $this->getFalsePositives();
            $mlMetrics = $this->getMLMetrics();
            
            return $this->render('admin/enhanced-analytics/ml-anomalies', [
                'title' => 'ML детекция аномалий',
                'anomalies' => $anomalies,
                'threatAnalysis' => $threatAnalysis,
                'falsePositives' => $falsePositives,
                'mlMetrics' => $mlMetrics
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке ML аномалий: ' . $e->getMessage()
            ]);
        }
    }
    
    // Уведомления и алерты
    public function notifications() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $securityAlerts = $this->getSecurityAlerts();
            $notificationSettings = $this->getNotificationSettings();
            $telegramNotifications = $this->getTelegramNotifications();
            $emailNotifications = $this->getEmailNotifications();
            
            return $this->render('admin/enhanced-analytics/notifications', [
                'title' => 'Уведомления и алерты',
                'securityAlerts' => $securityAlerts,
                'notificationSettings' => $notificationSettings,
                'telegramNotifications' => $telegramNotifications,
                'emailNotifications' => $emailNotifications
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке уведомлений: ' . $e->getMessage()
            ]);
        }
    }
    
    // Отчеты и экспорт
    public function reports() {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $automatedReports = $this->getAutomatedReports();
            $reportTemplates = $this->getReportTemplates();
            $exportHistory = $this->getExportHistory();
            
            return $this->render('admin/enhanced-analytics/reports', [
                'title' => 'Отчеты и экспорт',
                'automatedReports' => $automatedReports,
                'reportTemplates' => $reportTemplates,
                'exportHistory' => $exportHistory
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке отчетов: ' . $e->getMessage()
            ]);
        }
    }
    
    // API для AJAX запросов
    public function api() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $action = $_GET['action'] ?? '';
            
            switch ($action) {
                case 'metrics':
                    $response = [
                        'success' => true,
                        'metrics' => $this->getEnhancedAnalytics()
                    ];
                    break;
                    
                case 'charts':
                    $response = [
                        'success' => true,
                        'chartData' => $this->getAdvancedChartData()
                    ];
                    break;
                    
                case 'alerts':
                    $response = [
                        'success' => true,
                        'alerts' => $this->getRecentAlerts(),
                        'unread_count' => $this->getUnreadAlertsCount()
                    ];
                    break;
                    
                case 'realtime':
                    $response = [
                        'success' => true,
                        'data' => $this->getRealtimeData()
                    ];
                    break;
                    
                default:
                    $response = [
                        'success' => false,
                        'error' => 'Invalid action'
                    ];
            }
            
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    // Сохранение настроек
    public function settings() {
        $this->requireAccessLevel(5);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            try {
                $input = json_decode(file_get_contents('php://input'), true);
                
                // Сохраняем настройки в БД
                foreach ($input as $key => $value) {
                    Database::execute(
                        "INSERT INTO settings (name, value) VALUES (?, ?)
                         ON DUPLICATE KEY UPDATE value = VALUES(value)",
                        [$key, is_bool($value) ? ($value ? '1' : '0') : $value]
                    );
                }
                
                // Логируем изменение настроек
                $this->secureModel->createSecurityAlert(
                    'system_alert',
                    'info',
                    'Настройки изменены',
                    'Администратор изменил настройки аналитики',
                    $_SESSION['admin_user_id'],
                    $_SERVER['REMOTE_ADDR']
                );
                
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;
        }
    }
    
    // Действия с уведомлениями
    public function markAlertRead() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $alertId = $data['alert_id'] ?? null;
            
            if (!$alertId) {
                throw new Exception('Alert ID required');
            }
            
            require_once 'engine/main/db.php';
            
            Database::execute(
                "UPDATE security_alerts SET is_read = 1 WHERE id = ?",
                [$alertId]
            );
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Настройки уведомлений
    public function updateNotificationSettings() {
        $this->requireAccessLevel(5);
        
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            require_once 'engine/main/db.php';
            
            $userId = $_SESSION['admin_user_id'];
            $settings = $data['settings'] ?? [];
            
            Database::execute(
                "INSERT INTO notification_settings (user_id, email_enabled, telegram_enabled, telegram_chat_id, alert_types, daily_reports, weekly_reports) 
                 VALUES (?, ?, ?, ?, ?, ?, ?) 
                 ON DUPLICATE KEY UPDATE 
                 email_enabled = VALUES(email_enabled),
                 telegram_enabled = VALUES(telegram_enabled),
                 telegram_chat_id = VALUES(telegram_chat_id),
                 alert_types = VALUES(alert_types),
                 daily_reports = VALUES(daily_reports),
                 weekly_reports = VALUES(weekly_reports)",
                [
                    $userId,
                    $settings['email_enabled'] ?? true,
                    $settings['telegram_enabled'] ?? false,
                    $settings['telegram_chat_id'] ?? '',
                    json_encode($settings['alert_types'] ?? []),
                    $settings['daily_reports'] ?? false,
                    $settings['weekly_reports'] ?? false
                ]
            );
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Экспорт данных
    public function exportData() {
        $this->requireAccessLevel(5);
        
        try {
            $format = $_GET['format'] ?? 'json';
            $type = $_GET['type'] ?? 'general';
            
            require_once 'engine/main/db.php';
            
            $data = $this->getExportData($type);
            
            switch ($format) {
                case 'csv':
                    $this->exportCSV($data, $type);
                    break;
                case 'excel':
                    $this->exportExcel($data, $type);
                    break;
                case 'pdf':
                    $this->exportPDF($data, $type);
                    break;
                default:
                    $this->exportJSON($data, $type);
            }
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка экспорта',
                'message' => 'Ошибка при экспорте данных: ' . $e->getMessage()
            ]);
        }
    }
    
    // Приватные методы для получения данных
    
    private function getEnhancedAnalytics() {
        $analytics = [];
        
        // Основные метрики
        $analytics['users'] = $this->getUserMetrics();
        $analytics['security'] = $this->getSecurityMetrics();
        $analytics['behavior'] = $this->getBehaviorMetrics();
        $analytics['geolocation'] = $this->getGeolocationMetrics();
        $analytics['ml'] = $this->getMLMetrics();
        
        return $analytics;
    }
    
    private function getAdvancedChartData() {
        $chartData = [];
        
        // Активность по времени
        $chartData['activity_timeline'] = $this->getActivityTimeline();
        
        // Географическое распределение
        $chartData['geographic_distribution'] = $this->getGeographicDistribution();
        
        // Поведенческие паттерны
        $chartData['behavior_patterns'] = $this->getBehaviorPatterns();
        
        // Аномалии по времени
        $chartData['anomalies_timeline'] = $this->getAnomaliesTimeline();
        
        return $chartData;
    }
    
    private function getGeolocationData() {
        return [
            'countries' => $this->getTopCountries(),
            'cities' => $this->getTopCities(),
            'suspicious_locations' => $this->getSuspiciousLocations(),
            'map_data' => $this->getMapData()
        ];
    }
    
    private function getBehaviorAnalytics() {
        return [
            'page_views' => $this->getPageViews(),
            'user_journeys' => $this->getUserJourneys(),
            'conversion_funnel' => $this->getConversionFunnel(),
            'bounce_rate' => $this->getBounceRate()
        ];
    }
    
    private function getMLAnomalies() {
        return [
            'recent_anomalies' => $this->getRecentAnomalies(),
            'anomaly_types' => $this->getAnomalyTypes(),
            'risk_distribution' => $this->getRiskDistribution(),
            'false_positives' => $this->getFalsePositives()
        ];
    }
    
    private function getRealtimeData() {
        return [
            'active_users' => $this->getActiveUsers(),
            'current_sessions' => $this->getCurrentSessions(),
            'recent_activities' => $this->getRecentActivities(),
            'alerts' => $this->getRecentAlerts()
        ];
    }
    
    // Методы для получения конкретных метрик
    private function getUserMetrics() {
        $total = Database::fetchOne("SELECT COUNT(*) FROM users")['COUNT(*)'];
        $active = Database::fetchOne("SELECT COUNT(*) FROM users WHERE user_status = 1")['COUNT(*)'];
        $blocked = Database::fetchOne("SELECT COUNT(*) FROM users WHERE user_status = 0")['COUNT(*)'];
        
        return [
            'total' => $total,
            'active' => $active,
            'blocked' => $blocked,
            'new_today' => $this->getNewUsersToday(),
            'new_week' => $this->getNewUsersWeek()
        ];
    }
    
    private function getSecurityMetrics() {
        $suspicious_24h = Database::fetchOne(
            "SELECT COUNT(*) FROM ml_anomalies WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)"
        )['COUNT(*)'];
        
        $blocked_ips = Database::fetchOne("SELECT COUNT(*) FROM ip_blacklist")['COUNT(*)'];
        
        return [
            'suspicious_activities_24h' => $suspicious_24h,
            'blocked_ips' => $blocked_ips,
            'threat_level' => $this->calculateThreatLevel(),
            'alerts_today' => $this->getAlertsToday()
        ];
    }
    
    private function getBehaviorMetrics() {
        return [
            'avg_session_duration' => $this->getAverageSessionDuration(),
            'bounce_rate' => $this->getBounceRate(),
            'pages_per_session' => $this->getPagesPerSession(),
            'conversion_rate' => $this->getConversionRate()
        ];
    }
    
    private function getGeolocationMetrics() {
        $countries = Database::fetchAll("SELECT COUNT(DISTINCT country) FROM ip_geolocation WHERE country IS NOT NULL");
        $suspicious_countries = Database::fetchAll(
            "SELECT COUNT(DISTINCT country) FROM ip_geolocation ig 
             JOIN ml_anomalies ma ON ig.ip_address = ma.ip_address 
             WHERE ma.risk_level IN ('high', 'critical')"
        );
        
        return [
            'countries_count' => $countries[0]['COUNT(DISTINCT country)'] ?? 0,
            'suspicious_countries' => $suspicious_countries[0]['COUNT(DISTINCT country)'] ?? 0,
            'top_country' => $this->getTopCountry()
        ];
    }
    
    private function getMLMetrics() {
        $total_anomalies = Database::fetchOne("SELECT COUNT(*) FROM ml_anomalies")['COUNT(*)'];
        $critical_anomalies = Database::fetchOne(
            "SELECT COUNT(*) FROM ml_anomalies WHERE risk_level = 'critical'"
        )['COUNT(*)'];
        $false_positives = Database::fetchOne(
            "SELECT COUNT(*) FROM ml_anomalies WHERE is_false_positive = 1"
        )['COUNT(*)'];
        
        return [
            'total_anomalies' => $total_anomalies,
            'critical_anomalies' => $critical_anomalies,
            'false_positives' => $false_positives,
            'accuracy_rate' => $this->calculateAccuracyRate()
        ];
    }
    
    // Вспомогательные методы
    private function getNewUsersToday() {
        return Database::fetchOne(
            "SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()"
        )['COUNT(*)'] ?? 0;
    }
    
    private function getNewUsersWeek() {
        return Database::fetchOne(
            "SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
        )['COUNT(*)'] ?? 0;
    }
    
    private function calculateThreatLevel() {
        $critical = Database::fetchOne(
            "SELECT COUNT(*) FROM ml_anomalies WHERE risk_level = 'critical' AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)"
        )['COUNT(*)'] ?? 0;
        
        if ($critical > 10) return 'critical';
        if ($critical > 5) return 'high';
        if ($critical > 2) return 'medium';
        return 'low';
    }
    
    private function getAlertsToday() {
        return Database::fetchOne(
            "SELECT COUNT(*) FROM security_alerts WHERE DATE(created_at) = CURDATE()"
        )['COUNT(*)'] ?? 0;
    }
    
    private function getAverageSessionDuration() {
        $result = Database::fetchOne(
            "SELECT AVG(session_duration) FROM navigation_patterns WHERE session_duration > 0"
        );
        return round($result['AVG(session_duration)'] / 60, 1) ?? 0; // в минутах
    }
    
    private function getBounceRate() {
        $total_sessions = Database::fetchOne("SELECT COUNT(*) FROM navigation_patterns")['COUNT(*)'] ?? 0;
        $bounce_sessions = Database::fetchOne(
            "SELECT COUNT(*) FROM navigation_patterns WHERE total_pages = 1"
        )['COUNT(*)'] ?? 0;
        
        return $total_sessions > 0 ? round(($bounce_sessions / $total_sessions) * 100, 1) : 0;
    }
    
    private function getPagesPerSession() {
        $result = Database::fetchOne(
            "SELECT AVG(total_pages) FROM navigation_patterns WHERE total_pages > 0"
        );
        $avg = $result['AVG(total_pages)'] ?? 0;
        return round($avg, 1);
    }
    
    private function getConversionRate() {
        // Простая метрика конверсии - пользователи, которые выполнили определенные действия
        $total_users = Database::fetchOne("SELECT COUNT(*) FROM users")['COUNT(*)'] ?? 0;
        $active_users = Database::fetchOne(
            "SELECT COUNT(DISTINCT user_id) FROM user_activity WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
        )['COUNT(DISTINCT user_id)'] ?? 0;
        
        return $total_users > 0 ? round(($active_users / $total_users) * 100, 1) : 0;
    }
    
    private function getTopCountry() {
        $result = Database::fetchOne(
            "SELECT country FROM ip_geolocation WHERE country IS NOT NULL 
             GROUP BY country ORDER BY COUNT(*) DESC LIMIT 1"
        );
        return $result['country'] ?? 'Неизвестно';
    }
    
    private function calculateAccuracyRate() {
        $total = Database::fetchOne("SELECT COUNT(*) FROM ml_anomalies")['COUNT(*)'] ?? 0;
        $false_positives = Database::fetchOne(
            "SELECT COUNT(*) FROM ml_anomalies WHERE is_false_positive = 1"
        )['COUNT(*)'] ?? 0;
        
        return $total > 0 ? round((($total - $false_positives) / $total) * 100, 1) : 0;
    }
    
    // Добавляем недостающие методы
    private function getActivityTimeline() {
        return Database::fetchAll(
            "SELECT DATE(created_at) as date, COUNT(*) as count 
             FROM user_activity 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at) 
             ORDER BY date"
        );
    }
    
    private function getGeographicDistribution() {
        return Database::fetchAll(
            "SELECT country, COUNT(*) as count 
             FROM ip_geolocation 
             WHERE country IS NOT NULL 
             GROUP BY country 
             ORDER BY count DESC"
        );
    }
    
    private function getBehaviorPatterns() {
        return Database::fetchAll(
            "SELECT page_url, COUNT(*) as views, AVG(session_duration) as avg_duration
             FROM user_behavior 
             GROUP BY page_url 
             ORDER BY views DESC 
             LIMIT 20"
        );
    }
    
    private function getAnomaliesTimeline() {
        return Database::fetchAll(
            "SELECT DATE(created_at) as date, COUNT(*) as count, risk_level
             FROM ml_anomalies 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at), risk_level 
             ORDER BY date"
        );
    }
    
    private function getSuspiciousLocations() {
        return Database::fetchAll(
            "SELECT ig.ip_address, ig.country, ig.region, ig.city, 
                    ig.latitude, ig.longitude, ig.timezone, ig.isp,
                    COUNT(ma.id) as anomaly_count
             FROM ip_geolocation ig
             JOIN ml_anomalies ma ON ig.ip_address = ma.ip_address
             WHERE ma.risk_level IN ('high', 'critical')
             GROUP BY ig.ip_address, ig.country, ig.region, ig.city, 
                      ig.latitude, ig.longitude, ig.timezone, ig.isp
             ORDER BY anomaly_count DESC
             LIMIT 10"
        );
    }
    
    private function getUserJourneys() {
        return Database::fetchAll(
            "SELECT user_id, GROUP_CONCAT(page_url ORDER BY created_at) as journey
             FROM user_behavior 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY user_id
             ORDER BY COUNT(*) DESC
             LIMIT 10"
        );
    }
    
    private function getConversionFunnel() {
        return [
            'visitors' => Database::fetchOne("SELECT COUNT(DISTINCT user_id) FROM user_behavior")['COUNT(DISTINCT user_id)'] ?? 0,
            'engaged' => Database::fetchOne("SELECT COUNT(DISTINCT user_id) FROM user_behavior WHERE session_duration > 60")['COUNT(DISTINCT user_id)'] ?? 0,
            'converted' => Database::fetchOne("SELECT COUNT(DISTINCT user_id) FROM user_activity WHERE activity_type = 'conversion'")['COUNT(DISTINCT user_id)'] ?? 0
        ];
    }
    
    // Дополнительные недостающие методы
    private function getGeolocationStatistics() {
        return [
            'total_locations' => Database::fetchOne("SELECT COUNT(DISTINCT ip_address) FROM ip_geolocation")['COUNT(DISTINCT ip_address)'] ?? 0,
            'countries_count' => Database::fetchOne("SELECT COUNT(DISTINCT country) FROM ip_geolocation WHERE country IS NOT NULL")['COUNT(DISTINCT country)'] ?? 0,
            'cities_count' => Database::fetchOne("SELECT COUNT(DISTINCT city) FROM ip_geolocation WHERE city IS NOT NULL")['COUNT(DISTINCT city)'] ?? 0
        ];
    }
    
    private function getCountryStatistics() {
        return Database::fetchAll(
            "SELECT country, COUNT(*) as visits, COUNT(DISTINCT ip_address) as unique_ips
             FROM ip_geolocation 
             WHERE country IS NOT NULL 
             GROUP BY country 
             ORDER BY visits DESC 
             LIMIT 15"
        );
    }
    
    private function getPageAnalytics() {
        return [
            'most_visited' => Database::fetchAll("SELECT page_url, COUNT(*) as views FROM user_behavior GROUP BY page_url ORDER BY views DESC LIMIT 10"),
            'avg_time_on_page' => Database::fetchAll("SELECT page_url, AVG(session_duration) as avg_duration FROM user_behavior GROUP BY page_url ORDER BY avg_duration DESC LIMIT 10"),
            'bounce_pages' => Database::fetchAll("SELECT page_url, COUNT(*) as bounces FROM user_behavior WHERE session_duration < 30 GROUP BY page_url ORDER BY bounces DESC LIMIT 10")
        ];
    }
    
    private function getAllAnomalies() {
        return Database::fetchAll(
            "SELECT ma.*, u.user_fio, u.user_login, ig.country, ig.city
             FROM ml_anomalies ma 
             LEFT JOIN users u ON ma.user_id = u.user_id
             LEFT JOIN ip_geolocation ig ON ma.ip_address = ig.ip_address
             ORDER BY ma.created_at DESC"
        );
    }
    
    private function getThreatAnalysis() {
        return [
            'risk_levels' => Database::fetchAll("SELECT risk_level, COUNT(*) as count FROM ml_anomalies GROUP BY risk_level"),
            'anomaly_types' => Database::fetchAll("SELECT anomaly_type, COUNT(*) as count FROM ml_anomalies GROUP BY anomaly_type"),
            'trends' => Database::fetchAll("SELECT DATE(created_at) as date, COUNT(*) as count FROM ml_anomalies WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(created_at) ORDER BY date")
        ];
    }
    
    private function getFalsePositives() {
        return Database::fetchAll(
            "SELECT ma.*, u.user_fio, u.user_login
             FROM ml_anomalies ma 
             LEFT JOIN users u ON ma.user_id = u.user_id
             WHERE ma.is_false_positive = 1
             ORDER BY ma.created_at DESC"
        );
    }
    
    private function getNotificationSettings() {
        return [
            'email_enabled' => true,
            'telegram_enabled' => false,
            'sms_enabled' => false,
            'alert_levels' => ['critical', 'high', 'medium'],
            'frequency' => 'realtime'
        ];
    }
    
    private function getTelegramNotifications() {
        return [
            'bot_token' => 'your_bot_token_here',
            'chat_id' => 'your_chat_id_here',
            'enabled' => false,
            'notifications_sent' => 0
        ];
    }
    
    private function getEmailNotifications() {
        return [
            'smtp_host' => 'smtp.example.com',
            'smtp_port' => 587,
            'from_email' => 'alerts@example.com',
            'enabled' => true,
            'notifications_sent' => 0
        ];
    }
    
    private function getAutomatedReports() {
        return [
            'daily' => [
                'enabled' => true,
                'time' => '09:00',
                'recipients' => ['admin@example.com'],
                'format' => 'pdf'
            ],
            'weekly' => [
                'enabled' => true,
                'day' => 'monday',
                'time' => '10:00',
                'recipients' => ['admin@example.com', 'manager@example.com'],
                'format' => 'excel'
            ],
            'monthly' => [
                'enabled' => false,
                'day' => 1,
                'time' => '11:00',
                'recipients' => ['admin@example.com'],
                'format' => 'pdf'
            ]
        ];
    }
    
    // Методы для API
    private function getRealtimeAPI() {
        return [
            'active_users' => $this->getActiveUsers(),
            'current_sessions' => $this->getCurrentSessions(),
            'recent_activities' => $this->getRecentActivities(),
            'alerts' => $this->getRecentAlerts()
        ];
    }
    
    private function getGeolocationAPI() {
        return [
            'countries' => $this->getTopCountries(),
            'cities' => $this->getTopCities(),
            'map_data' => $this->getMapData()
        ];
    }
    
    private function getBehaviorAPI() {
        return [
            'page_views' => $this->getPageViews(),
            'user_journeys' => $this->getUserJourneys(),
            'conversion_funnel' => $this->getConversionFunnel()
        ];
    }
    
    private function getAnomaliesAPI() {
        return [
            'recent_anomalies' => $this->getRecentAnomalies(),
            'anomaly_types' => $this->getAnomalyTypes(),
            'risk_distribution' => $this->getRiskDistribution()
        ];
    }
    
    private function getAlertsAPI() {
        return [
            'security_alerts' => $this->getSecurityAlerts(),
            'unread_count' => $this->getUnreadAlertsCount()
        ];
    }
    
    private function getGeneralAPI() {
        return [
            'users' => $this->getUserMetrics(),
            'security' => $this->getSecurityMetrics(),
            'behavior' => $this->getBehaviorMetrics(),
            'geolocation' => $this->getGeolocationMetrics(),
            'ml' => $this->getMLMetrics()
        ];
    }
    
    // Методы для получения данных
    private function getActiveUsers() {
        return Database::fetchOne(
            "SELECT COUNT(DISTINCT user_id) FROM user_sessions WHERE last_activity >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)"
        )['COUNT(DISTINCT user_id)'] ?? 0;
    }
    
    private function getCurrentSessions() {
        return Database::fetchOne(
            "SELECT COUNT(*) FROM user_sessions WHERE last_activity >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)"
        )['COUNT(*)'] ?? 0;
    }
    
    private function getRecentActivities() {
        return Database::fetchAll(
            "SELECT ua.*, u.user_fio, u.user_login 
             FROM user_activity ua 
             JOIN users u ON ua.user_id = u.user_id 
             ORDER BY ua.created_at DESC LIMIT 10"
        );
    }
    
    private function getRecentAlerts() {
        return Database::fetchAll(
            "SELECT * FROM security_alerts WHERE is_read = 0 ORDER BY created_at DESC LIMIT 5"
        );
    }
    
    private function getTopCountries() {
        return Database::fetchAll(
            "SELECT country, COUNT(*) as count FROM ip_geolocation 
             WHERE country IS NOT NULL GROUP BY country 
             ORDER BY count DESC LIMIT 10"
        );
    }
    
    private function getTopCities() {
        return Database::fetchAll(
            "SELECT city, COUNT(*) as count FROM ip_geolocation 
             WHERE city IS NOT NULL GROUP BY city 
             ORDER BY count DESC LIMIT 10"
        );
    }
    
    private function getMapData() {
        return Database::fetchAll(
            "SELECT latitude, longitude, COUNT(*) as count 
             FROM ip_geolocation 
             WHERE latitude IS NOT NULL AND longitude IS NOT NULL 
             GROUP BY latitude, longitude"
        );
    }
    
    private function getPageViews() {
        return Database::fetchAll(
            "SELECT page_url, COUNT(*) as views 
             FROM user_behavior 
             GROUP BY page_url 
             ORDER BY views DESC LIMIT 10"
        );
    }
    
    private function getRecentAnomalies() {
        return Database::fetchAll(
            "SELECT ma.*, u.user_fio, u.user_login 
             FROM ml_anomalies ma 
             LEFT JOIN users u ON ma.user_id = u.user_id 
             ORDER BY ma.created_at DESC LIMIT 10"
        );
    }
    
    private function getAnomalyTypes() {
        return Database::fetchAll(
            "SELECT anomaly_type, COUNT(*) as count 
             FROM ml_anomalies 
             GROUP BY anomaly_type"
        );
    }
    
    private function getRiskDistribution() {
        return Database::fetchAll(
            "SELECT risk_level, COUNT(*) as count 
             FROM ml_anomalies 
             GROUP BY risk_level"
        );
    }
    
    private function getSecurityAlerts() {
        return Database::fetchAll(
            "SELECT * FROM security_alerts ORDER BY created_at DESC LIMIT 20"
        );
    }
    
    private function getUnreadAlertsCount() {
        return Database::fetchOne(
            "SELECT COUNT(*) FROM security_alerts WHERE is_read = 0"
        )['COUNT(*)'] ?? 0;
    }
    
    // Добавляем недостающие методы
    private function getReportTemplates() {
        return Database::fetchAll(
            "SELECT * FROM report_templates ORDER BY created_at DESC"
        );
    }
    
    private function getExportHistory() {
        return Database::fetchAll(
            "SELECT * FROM export_history ORDER BY created_at DESC LIMIT 20"
        );
    }
    
    // Методы для экспорта
    private function getExportData($type) {
        switch ($type) {
            case 'users':
                return Database::fetchAll("SELECT * FROM users");
            case 'activity':
                return Database::fetchAll("SELECT * FROM user_activity ORDER BY created_at DESC");
            case 'anomalies':
                return Database::fetchAll("SELECT * FROM ml_anomalies ORDER BY created_at DESC");
            case 'geolocation':
                return Database::fetchAll("SELECT * FROM ip_geolocation");
            default:
                return $this->getGeneralAPI();
        }
    }
    
    private function exportCSV($data, $type) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="analytics_' . $type . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
    }
    
    private function exportJSON($data, $type) {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="analytics_' . $type . '_' . date('Y-m-d') . '.json"');
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    private function exportExcel($data, $type) {
        // Простая реализация - можно расширить с библиотекой PHPSpreadsheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="analytics_' . $type . '_' . date('Y-m-d') . '.xls"');
        
        echo '<table border="1">';
        if (!empty($data)) {
            echo '<tr>';
            foreach (array_keys($data[0]) as $header) {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
            echo '</tr>';
            
            foreach ($data as $row) {
                echo '<tr>';
                foreach ($row as $value) {
                    echo '<td>' . htmlspecialchars($value) . '</td>';
                }
                echo '</tr>';
            }
        }
        echo '</table>';
    }
    
    private function exportPDF($data, $type) {
        // Простая реализация - можно расширить с библиотекой TCPDF или FPDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="analytics_' . $type . '_' . date('Y-m-d') . '.pdf"');
        
        // Здесь должна быть генерация PDF
        echo "PDF export for $type data";
    }
} 