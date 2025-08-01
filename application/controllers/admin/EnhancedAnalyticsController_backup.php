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
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $geolocationStats = $this->getGeolocationStatistics();
            $suspiciousLocations = $this->getSuspiciousLocations();
            $countryStats = $this->getCountryStatistics();
            
            $this->render('admin/enhanced-analytics/geolocation', [
                'title' => 'Геолокация активности',
                'geolocationStats' => $geolocationStats,
                'suspiciousLocations' => $suspiciousLocations,
                'countryStats' => $countryStats
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
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $behaviorPatterns = $this->getBehaviorPatterns();
            $userJourneys = $this->getUserJourneys();
            $pageAnalytics = $this->getPageAnalytics();
            $conversionFunnel = $this->getConversionFunnel();
            
            $this->render('admin/enhanced-analytics/behavior', [
                'title' => 'Поведенческая аналитика',
                'behaviorPatterns' => $behaviorPatterns,
                'userJourneys' => $userJourneys,
                'pageAnalytics' => $pageAnalytics,
                'conversionFunnel' => $conversionFunnel
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
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
            
            $this->render('admin/enhanced-analytics/ml-anomalies', [
                'title' => 'ML детекция аномалий',
                'anomalies' => $anomalies,
                'threatAnalysis' => $threatAnalysis,
                'falsePositives' => $falsePositives,
                'mlMetrics' => $mlMetrics
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
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
            
            $this->render('admin/enhanced-analytics/notifications', [
                'title' => 'Уведомления и алерты',
                'securityAlerts' => $securityAlerts,
                'notificationSettings' => $notificationSettings,
                'telegramNotifications' => $telegramNotifications,
                'emailNotifications' => $emailNotifications
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
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
            
            $this->render('admin/enhanced-analytics/reports', [
                'title' => 'Отчеты и экспорт',
                'automatedReports' => $automatedReports,
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
            $this->render('admin/error/error', [
                'title' => 'Ошибка экспорта',
                'message' => 'Ошибка при экспорте данных: ' . $e->getMessage()
            ]);
        }
    }
    
    // Приватные методы для получения данных
    
    private function getEnhancedAnalytics() {
        try {
            require_once 'engine/main/db.php';
            
            $analytics = [
                'total_users' => $this->getTotalUsers(),
                'active_users' => $this->getActiveUsers(),
                'new_users_today' => $this->getNewUsersToday(),
                'new_users_week' => $this->getNewUsersWeek(),
                'total_sessions' => $this->getTotalSessions(),
                'active_sessions' => $this->getActiveSessions(),
                'failed_logins_24h' => $this->getFailedLogins24h(),
                'suspicious_activities' => $this->getSuspiciousActivitiesCount(),
                'threat_level' => $this->calculateThreatLevel(),
                'top_countries' => $this->getTopCountries(),
                'top_cities' => $this->getTopCities(),
                'recent_anomalies' => $this->getRecentAnomalies(),
                'security_alerts' => $this->getSecurityAlerts(),
                'unread_alerts' => $this->getUnreadAlertsCount()
            ];
            
            return $analytics;
        } catch (Exception $e) {
            error_log("Error in getEnhancedAnalytics: " . $e->getMessage());
            return [];
        }
    }
    
    private function getAdvancedChartData() {
        try {
            require_once 'engine/main/db.php';
            
            $chartData = [
                'activity_timeline' => $this->getActivityTimeline(),
                'geographic_distribution' => $this->getGeographicDistribution(),
                'behavior_patterns' => $this->getBehaviorPatterns(),
                'anomalies_timeline' => $this->getAnomaliesTimeline(),
                'security_metrics' => $this->getSecurityMetrics(),
                'user_growth' => $this->getUserGrowth(),
                'session_analytics' => $this->getSessionAnalytics(),
                'page_views' => $this->getPageViews()
            ];
            
            return $chartData;
        } catch (Exception $e) {
            error_log("Error in getAdvancedChartData: " . $e->getMessage());
            return [];
        }
    }
    
    private function getGeolocationData() {
        try {
            require_once 'engine/main/db.php';
            
            $geolocationData = [
                'countries' => $this->getCountriesData(),
                'cities' => $this->getCitiesData(),
                'suspicious_locations' => $this->getSuspiciousLocations(),
                'map_data' => $this->getMapData(),
                'top_countries' => $this->getTopCountries(),
                'top_cities' => $this->getTopCities()
            ];
            
            return $geolocationData;
        } catch (Exception $e) {
            error_log("Error in getGeolocationData: " . $e->getMessage());
            return [];
        }
    }
    
    private function getBehaviorAnalytics() {
        try {
            require_once 'engine/main/db.php';
            
            $behaviorData = [
                'user_journeys' => $this->getUserJourneys(),
                'conversion_funnel' => $this->getConversionFunnel(),
                'page_analytics' => $this->getPageAnalytics(),
                'behavior_patterns' => $this->getBehaviorPatterns(),
                'session_metrics' => $this->getSessionMetrics(),
                'engagement_metrics' => $this->getEngagementMetrics()
            ];
            
            return $behaviorData;
        } catch (Exception $e) {
            error_log("Error in getBehaviorAnalytics: " . $e->getMessage());
            return [];
        }
    }
    
    private function getMLAnomalies() {
        try {
            require_once 'engine/main/db.php';
            
            $mlData = [
                'recent_anomalies' => $this->getRecentAnomalies(),
                'anomaly_types' => $this->getAnomalyTypes(),
                'risk_distribution' => $this->getRiskDistribution(),
                'false_positives' => $this->getFalsePositives(),
                'accuracy_rate' => $this->calculateAccuracyRate(),
                'threat_analysis' => $this->getThreatAnalysis()
            ];
            
            return $mlData;
        } catch (Exception $e) {
            error_log("Error in getMLAnomalies: " . $e->getMessage());
            return [];
        }
    }
    
    private function getRealtimeData() {
        try {
            require_once 'engine/main/db.php';
            
            $realtimeData = [
                'active_users' => $this->getActiveUsers(),
                'current_sessions' => $this->getCurrentSessions(),
                'recent_activities' => $this->getRecentActivities(),
                'recent_alerts' => $this->getRecentAlerts(),
                'top_countries' => $this->getTopCountries(),
                'top_cities' => $this->getTopCities(),
                'map_data' => $this->getMapData(),
                'page_views' => $this->getPageViews()
            ];
            
            return $realtimeData;
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
    
    // Методы для географических данных
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
    
    // Методы для поведенческой аналитики
    private function getSessionMetrics() {
        try {
            $metrics = [];
            
            // Средняя продолжительность сессии
            $avgDuration = Database::fetchOne("
                SELECT AVG(session_duration) as avg_duration 
                FROM user_behavior 
                WHERE session_duration > 0
            ");
            $metrics['avg_session_duration'] = round($avgDuration['avg_duration'] ?? 0, 2);
            
            // Средняя глубина прокрутки
            $avgScrollDepth = Database::fetchOne("
                SELECT AVG(scroll_depth) as avg_scroll 
                FROM user_behavior 
                WHERE scroll_depth > 0
            ");
            $metrics['avg_scroll_depth'] = round($avgScrollDepth['avg_scroll'] ?? 0, 2);
            
            // Среднее количество кликов
            $avgClicks = Database::fetchOne("
                SELECT AVG(clicks_count) as avg_clicks 
                FROM user_behavior 
                WHERE clicks_count > 0
            ");
            $metrics['avg_clicks'] = round($avgClicks['avg_clicks'] ?? 0, 2);
            
            return $metrics;
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getEngagementMetrics() {
        try {
            $metrics = [];
            
            // Количество взаимодействий с формами
            $formInteractions = Database::fetchOne("
                SELECT SUM(form_interactions) as total_forms 
                FROM user_behavior
            ");
            $metrics['form_interactions'] = $formInteractions['total_forms'] ?? 0;
            
            // Количество загрузок файлов
            $fileDownloads = Database::fetchOne("
                SELECT SUM(file_downloads) as total_downloads 
                FROM user_behavior
            ");
            $metrics['file_downloads'] = $fileDownloads['total_downloads'] ?? 0;
            
            return $metrics;
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Методы для графиков
    private function getUserGrowth() {
        try {
            return Database::fetchAll("
                SELECT DATE(user_date_reg) as date, COUNT(*) as count 
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
                SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM user_sessions 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at) 
                ORDER BY date
            ");
        } catch (Exception $e) {
            return [];
        }
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
            
            // Активные страны (с активностью за последние 24 часа)
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
    
    // Методы для получения данных поведения
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
            
            // Процент отказов (симуляция)
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
    
    // Методы для получения данных активности
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
    
    private function getAnomaliesTimeline() {
        try {
            return Database::fetchAll("
                SELECT 
                    DATE(created_at) as date,
                    anomaly_type,
                    risk_level,
                    COUNT(*) as count
                FROM ml_anomalies 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at), anomaly_type, risk_level
                ORDER BY date
            ");
        } catch (Exception $e) {
            error_log("Error in getAnomaliesTimeline: " . $e->getMessage());
            return [];
        }
    }
    
    // Методы для получения данных безопасности

    
    // Методы для получения данных пользователей

    
    // Методы для получения уведомлений
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
    
    // Методы для получения данных карты
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
    
    // Методы для получения данных страниц
    private function getPageViews() {
        try {
            return Database::fetchAll("
                SELECT 
                    page_url,
                    COUNT(*) as views,
                    COUNT(DISTINCT user_id) as unique_users
                FROM user_behavior 
                GROUP BY page_url
                ORDER BY views DESC
                LIMIT 10
            ");
        } catch (Exception $e) {
            error_log("Error in getPageViews: " . $e->getMessage());
            return [];
        }
    }
    
    // Методы для получения данных аномалий
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
    
    private function getFalsePositives() {
        try {
            return Database::fetchAll("
                SELECT 
                    anomaly_type,
                    COUNT(*) as count
                FROM ml_anomalies 
                WHERE is_false_positive = 1
                GROUP BY anomaly_type
                ORDER BY count DESC
            ");
        } catch (Exception $e) {
            error_log("Error in getFalsePositives: " . $e->getMessage());
            return [];
        }
    }
    
    private function calculateAccuracyRate() {
        try {
            $totalAnomalies = Database::fetchOne("
                SELECT COUNT(*) as count FROM ml_anomalies
            ")['count'] ?? 0;
            
            $falsePositives = Database::fetchOne("
                SELECT COUNT(*) as count FROM ml_anomalies WHERE is_false_positive = 1
            ")['count'] ?? 0;
            
            if ($totalAnomalies > 0) {
                return round((($totalAnomalies - $falsePositives) / $totalAnomalies) * 100, 2);
            }
            
            return 0;
        } catch (Exception $e) {
            return 0;
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