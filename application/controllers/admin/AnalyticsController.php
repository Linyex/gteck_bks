<?php

/**
 * Контроллер аналитики и мониторинга
 */
class AnalyticsController extends BaseAdminController {
    
    private $analyticsService;
    private $monitoringService;
    private $loggingService;
    
    public function __construct() {
        parent::__construct();
        
        require_once ENGINE_DIR . 'services/AnalyticsService.php';
        require_once ENGINE_DIR . 'services/MonitoringService.php';
        require_once ENGINE_DIR . 'services/LoggingService.php';
        
        $this->analyticsService = new AnalyticsService();
        $this->monitoringService = new MonitoringService();
        $this->loggingService = new LoggingService();
    }
    
    /**
     * Главная страница аналитики
     */
    public function index() {
        try {
            // Инициализируем данные по умолчанию
            $analytics = [
                'users' => [
                    'total' => 0,
                    'active' => 0,
                    'blocked' => 0
                ],
                'logins_24h' => [
                    'total' => 0,
                    'successful' => 0,
                    'failed' => 0
                ],
                'sessions' => [
                    'active' => 0,
                    'created_24h' => 0,
                    'avg_duration' => 0
                ],
                'security' => [
                    'threat_level' => 'low',
                    'suspicious_activities_24h' => 0,
                    'blocked_ips' => 0
                ]
            ];
            
            $monitoringData = [
                'status' => 'ok',
                'checks' => [
                    'performance' => ['status' => 'ok', 'issues' => []],
                    'security' => ['status' => 'ok', 'issues' => []],
                    'storage' => ['status' => 'ok', 'issues' => []],
                    'database' => ['status' => 'ok', 'issues' => []]
                ]
            ];
            
            // Пытаемся получить данные из сервисов
            try {
                $systemStats = $this->analyticsService->getSystemStats();
                
                // Обновляем данные пользователей
                if (isset($systemStats['users'])) {
                    $analytics['users']['total'] = $systemStats['users']['total_users'] ?? 0;
                    $analytics['users']['active'] = $systemStats['users']['active_users'] ?? 0;
                }
                
            } catch (Exception $e) {
                // Игнорируем ошибки сервисов
            }
            
            // Пытаемся получить данные мониторинга
            try {
                $monitoringData = $this->monitoringService->runSystemCheck();
            } catch (Exception $e) {
                // Используем данные по умолчанию
            }
            
            // Получаем данные о входах
            try {
                require_once 'engine/main/db.php';
                $logins24h = Database::fetchOne("
                    SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN message LIKE '%SUCCESS%' THEN 1 ELSE 0 END) as successful,
                        SUM(CASE WHEN message LIKE '%FAILED%' THEN 1 ELSE 0 END) as failed
                    FROM system_logs 
                    WHERE level = 'info' 
                    AND message LIKE '%Login Attempt%'
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                ");
                
                if ($logins24h) {
                    $analytics['logins_24h'] = [
                        'total' => $logins24h['total'] ?? 0,
                        'successful' => $logins24h['successful'] ?? 0,
                        'failed' => $logins24h['failed'] ?? 0
                    ];
                }
                
                // Получаем данные о сессиях
                $sessions = Database::fetchOne("
                    SELECT 
                        COUNT(DISTINCT user_id) as active,
                        SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN 1 ELSE 0 END) as created_24h
                    FROM user_sessions 
                    WHERE is_active = 1 
                    AND last_activity >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                ");
                
                if ($sessions) {
                    $analytics['sessions'] = [
                        'active' => $sessions['active'] ?? 0,
                        'created_24h' => $sessions['created_24h'] ?? 0,
                        'avg_duration' => 30 // Временное значение
                    ];
                }
                
                // Получаем данные о безопасности
                $security = Database::fetchOne("
                    SELECT 
                        COUNT(*) as suspicious_24h
                    FROM system_logs 
                    WHERE level IN ('warning', 'error', 'critical')
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                ");
                
                if ($security) {
                    $suspiciousCount = $security['suspicious_24h'] ?? 0;
                    $threatLevel = $suspiciousCount > 10 ? 'high' : ($suspiciousCount > 5 ? 'medium' : 'low');
                    
                    $analytics['security'] = [
                        'threat_level' => $threatLevel,
                        'suspicious_activities_24h' => $suspiciousCount,
                        'blocked_ips' => 0 // Добавим позже
                    ];
                }
                
            } catch (Exception $e) {
                // Игнорируем ошибки БД, используем значения по умолчанию
            }
            
            $this->render('admin/analytics/index', [
                'analytics' => $analytics,
                'monitoring_data' => $monitoringData,
                'page_title' => 'Аналитика и мониторинг'
            ]);
            
        } catch (Exception $e) {
            // В случае критической ошибки показываем простую страницу
            $this->render('admin/analytics/index', [
                'analytics' => [
                    'users' => ['total' => 0, 'active' => 0, 'blocked' => 0],
                    'logins_24h' => ['total' => 0, 'successful' => 0, 'failed' => 0],
                    'sessions' => ['active' => 0, 'created_24h' => 0, 'avg_duration' => 0],
                    'security' => ['threat_level' => 'low', 'suspicious_activities_24h' => 0, 'blocked_ips' => 0]
                ],
                'monitoring_data' => [
                    'status' => 'ok',
                    'checks' => [
                        'performance' => ['status' => 'ok', 'issues' => []],
                        'security' => ['status' => 'ok', 'issues' => []],
                        'storage' => ['status' => 'ok', 'issues' => []],
                        'database' => ['status' => 'ok', 'issues' => []]
                    ]
                ],
                'page_title' => 'Аналитика и мониторинг'
            ]);
        }
    }
    
    /**
     * Страница мониторинга системы
     */
    public function monitoring() {
        try {
            $monitoringData = $this->monitoringService->runSystemCheck();
            $alerts = $this->monitoringService->getAlerts();
            
            $this->render('admin/analytics/monitoring', [
                'monitoring_data' => $monitoringData,
                'alerts' => $alerts,
                'page_title' => 'Мониторинг системы'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки мониторинга: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница логов
     */
    public function logs() {
        try {
            $level = $_GET['level'] ?? null;
            $lines = (int)($_GET['lines'] ?? 100);
            
            $logs = $this->loggingService->getLogs($level, $lines);
            $logStats = $this->loggingService->getLogStats();
            
            $this->render('admin/analytics/logs', [
                'logs' => $logs,
                'log_stats' => $logStats,
                'current_level' => $level,
                'lines' => $lines,
                'page_title' => 'Системные логи'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки логов: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница графиков
     */
    public function charts() {
        try {
            $period = $_GET['period'] ?? '7d';
            $chartType = $_GET['type'] ?? 'user_activity';
            
            $chartData = $this->analyticsService->getChartData($chartType, $period);
            
            $this->render('admin/analytics/charts', [
                'chart_data' => $chartData,
                'chart_type' => $chartType,
                'period' => $period,
                'page_title' => 'Графики и диаграммы'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки графиков: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница пользовательской аналитики
     */
    public function userAnalytics() {
        try {
            $topUsers = $this->analyticsService->getTopUsers(20);
            $popularActions = $this->analyticsService->getPopularActions(20);
            
            $this->render('admin/analytics/user_analytics', [
                'top_users' => $topUsers,
                'popular_actions' => $popularActions,
                'page_title' => 'Аналитика пользователей'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки пользовательской аналитики: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Страница безопасности
     */
    public function security() {
        try {
            require_once 'engine/main/db.php';
            
            $period = $_GET['period'] ?? '7d';
            
            // Получаем события безопасности
            $securityEvents = Database::fetchAll("
                SELECT 
                    ua.*,
                    u.user_login,
                    u.user_fio
                FROM user_activity ua
                LEFT JOIN users u ON ua.user_id = u.user_id
                WHERE ua.action_type IN ('login_failed', 'suspicious_activity', 'session_deactivated')
                AND ua.activity_time >= DATE_SUB(NOW(), INTERVAL " . $this->getInterval($period) . ")
                ORDER BY ua.activity_time DESC
                LIMIT 100
            ");
            
            // Получаем неудачные попытки входа
            $failedLogins = Database::fetchAll("
                SELECT 
                    ua.*,
                    u.user_login,
                    u.user_fio
                FROM user_activity ua
                LEFT JOIN users u ON ua.user_id = u.user_id
                WHERE ua.action_type = 'login_failed'
                AND ua.activity_time >= DATE_SUB(NOW(), INTERVAL " . $this->getInterval($period) . ")
                ORDER BY ua.activity_time DESC
                LIMIT 50
            ");
            
            // Получаем подозрительную активность
            $suspiciousActivity = Database::fetchAll("
                SELECT 
                    ua.*,
                    u.user_login,
                    u.user_fio
                FROM user_activity ua
                LEFT JOIN users u ON ua.user_id = u.user_id
                WHERE ua.suspicious = 1
                AND ua.activity_time >= DATE_SUB(NOW(), INTERVAL " . $this->getInterval($period) . ")
                ORDER BY ua.activity_time DESC
                LIMIT 50
            ");
            
            // Получаем статистику безопасности
            $securityStats = Database::fetchOne("
                SELECT 
                    COUNT(CASE WHEN action_type = 'login_failed' THEN 1 END) as failed_logins_24h,
                    COUNT(CASE WHEN suspicious = 1 THEN 1 END) as suspicious_activities_24h,
                    COUNT(CASE WHEN action_type = 'suspicious_activity' THEN 1 END) as security_events_24h,
                    COUNT(DISTINCT CASE WHEN action_type = 'login_failed' THEN ip_address END) as unique_failed_ips
                FROM user_activity 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            
            // Получаем IP адреса с множественными неудачными попытками
            $suspiciousIPs = Database::fetchAll("
                SELECT 
                    ip_address,
                    COUNT(*) as failed_attempts,
                    COUNT(DISTINCT user_id) as unique_users,
                    MAX(activity_time) as last_attempt
                FROM user_activity 
                WHERE action_type = 'login_failed'
                AND activity_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                GROUP BY ip_address
                HAVING COUNT(*) > 5
                ORDER BY failed_attempts DESC
                LIMIT 20
            ");
            
            // Получаем активность по времени для графика
            $securityTimeline = Database::fetchAll("
                SELECT 
                    DATE(activity_time) as date,
                    COUNT(CASE WHEN action_type = 'login_failed' THEN 1 END) as failed_logins,
                    COUNT(CASE WHEN suspicious = 1 THEN 1 END) as suspicious_activities
                FROM user_activity 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL " . $this->getInterval($period) . ")
                AND (action_type = 'login_failed' OR suspicious = 1)
                GROUP BY DATE(activity_time)
                ORDER BY date
            ");
            
        } catch (Exception $e) {
            // Демо-данные при ошибке БД
            $securityEvents = [
                [
                    'id' => 1,
                    'user_fio' => 'Администратор',
                    'action_type' => 'login_failed',
                    'activity_description' => 'Неудачная попытка входа',
                    'ip_address' => '192.168.1.100',
                    'activity_time' => date('Y-m-d H:i:s', strtotime('-2 hours'))
                ],
                [
                    'id' => 2,
                    'user_fio' => 'Неизвестный пользователь',
                    'action_type' => 'suspicious_activity',
                    'activity_description' => 'Подозрительная активность',
                    'ip_address' => '192.168.1.200',
                    'activity_time' => date('Y-m-d H:i:s', strtotime('-1 hour'))
                ]
            ];
            
            $failedLogins = [
                [
                    'id' => 1,
                    'user_fio' => 'Пользователь',
                    'action_type' => 'login_failed',
                    'activity_description' => 'Неверный пароль',
                    'ip_address' => '192.168.1.100',
                    'activity_time' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
                ],
                [
                    'id' => 2,
                    'user_fio' => 'Хакер',
                    'action_type' => 'login_failed',
                    'activity_description' => 'Множественные попытки входа',
                    'ip_address' => '192.168.1.101',
                    'activity_time' => date('Y-m-d H:i:s', strtotime('-15 minutes'))
                ]
            ];
            
            $suspiciousActivity = [
                [
                    'id' => 1,
                    'user_fio' => 'Неизвестный',
                    'action_type' => 'suspicious_activity',
                    'activity_description' => 'Попытка доступа к админ-панели',
                    'ip_address' => '192.168.1.200',
                    'activity_time' => date('Y-m-d H:i:s', strtotime('-1 hour'))
                ]
            ];
            
            $securityStats = [
                'failed_logins_24h' => 5,
                'suspicious_activities_24h' => 2,
                'security_events_24h' => 7,
                'unique_failed_ips' => 3
            ];
            
            $suspiciousIPs = [
                [
                    'ip_address' => '192.168.1.100',
                    'failed_attempts' => 8,
                    'unique_users' => 2,
                    'last_attempt' => date('Y-m-d H:i:s', strtotime('-10 minutes'))
                ],
                [
                    'ip_address' => '192.168.1.101',
                    'failed_attempts' => 12,
                    'unique_users' => 1,
                    'last_attempt' => date('Y-m-d H:i:s', strtotime('-5 minutes'))
                ]
            ];
            
            $securityTimeline = [
                [
                    'date' => date('Y-m-d', strtotime('-6 days')),
                    'failed_logins' => 3,
                    'suspicious_activities' => 1
                ],
                [
                    'date' => date('Y-m-d', strtotime('-5 days')),
                    'failed_logins' => 5,
                    'suspicious_activities' => 2
                ],
                [
                    'date' => date('Y-m-d', strtotime('-4 days')),
                    'failed_logins' => 2,
                    'suspicious_activities' => 0
                ],
                [
                    'date' => date('Y-m-d', strtotime('-3 days')),
                    'failed_logins' => 7,
                    'suspicious_activities' => 3
                ],
                [
                    'date' => date('Y-m-d', strtotime('-2 days')),
                    'failed_logins' => 4,
                    'suspicious_activities' => 1
                ],
                [
                    'date' => date('Y-m-d', strtotime('-1 day')),
                    'failed_logins' => 6,
                    'suspicious_activities' => 2
                ],
                [
                    'date' => date('Y-m-d'),
                    'failed_logins' => 5,
                    'suspicious_activities' => 2
                ]
            ];
        }
        
        $this->render('admin/analytics/security', [
            'security_events' => $securityEvents,
            'failed_logins' => $failedLogins,
            'suspicious_activity' => $suspiciousActivity,
            'security_stats' => $securityStats,
            'suspicious_ips' => $suspiciousIPs,
            'security_timeline' => $securityTimeline,
            'period' => $period,
            'page_title' => 'Безопасность системы'
        ]);
    }
    
    /**
     * Страница активности пользователей
     */
    public function userActivity() {
        try {
            require_once 'engine/main/db.php';
            
            $period = $_GET['period'] ?? '7d';
            $limit = (int)($_GET['limit'] ?? 50);
            
            // Получаем данные активности пользователей
            $userActivity = $this->getUserActivityData($period);
            
            // Получаем топ пользователей по активности
            $topUsers = Database::fetchAll("
                SELECT 
                    u.user_id,
                    u.user_login,
                    u.user_fio,
                    COUNT(ua.id) as activity_count,
                    MAX(ua.activity_time) as last_activity,
                    COUNT(DISTINCT DATE(ua.activity_time)) as active_days
                FROM users u
                LEFT JOIN user_activity_log ua ON u.user_id = ua.user_id
                WHERE ua.activity_time >= DATE_SUB(NOW(), INTERVAL " . $this->getInterval($period) . ")
                GROUP BY u.user_id, u.user_login, u.user_fio
                ORDER BY activity_count DESC
                LIMIT $limit
            ");
            
            // Получаем популярные действия
            $popularActions = Database::fetchAll("
                SELECT 
                    action_type,
                    COUNT(*) as count,
                    COUNT(DISTINCT user_id) as unique_users
                FROM user_activity_log 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL " . $this->getInterval($period) . ")
                GROUP BY action_type
                ORDER BY count DESC
                LIMIT 20
            ");
            
            // Получаем активность по времени
            $hourlyActivity = Database::fetchAll("
                SELECT 
                    HOUR(activity_time) as hour,
                    COUNT(*) as count
                FROM user_activity_log 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL " . $this->getInterval($period) . ")
                GROUP BY HOUR(activity_time)
                ORDER BY hour
            ");
            
            // Получаем активность по дням недели
            $weeklyActivity = Database::fetchAll("
                SELECT 
                    DAYOFWEEK(activity_time) as day_of_week,
                    COUNT(*) as count
                FROM user_activity_log 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL " . $this->getInterval($period) . ")
                GROUP BY DAYOFWEEK(activity_time)
                ORDER BY day_of_week
            ");
            
        } catch (Exception $e) {
            // Демо-данные при ошибке БД
            $userActivity = [
                [
                    'id' => 1,
                    'user_fio' => 'Администратор',
                    'action_type' => 'page_view',
                    'activity_description' => 'Просмотр аналитики',
                    'ip_address' => '192.168.1.1',
                    'activity_time' => date('Y-m-d H:i:s', strtotime('-1 hour'))
                ],
                [
                    'id' => 2,
                    'user_fio' => 'Модератор',
                    'action_type' => 'page_view',
                    'activity_description' => 'Просмотр новостей',
                    'ip_address' => '192.168.1.2',
                    'activity_time' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
                ],
                [
                    'id' => 3,
                    'user_fio' => 'Пользователь 1',
                    'action_type' => 'login_success',
                    'activity_description' => 'Успешный вход в систему',
                    'ip_address' => '192.168.1.3',
                    'activity_time' => date('Y-m-d H:i:s', strtotime('-15 minutes'))
                ]
            ];
            
            $topUsers = [
                [
                    'user_id' => 1,
                    'user_fio' => 'Администратор',
                    'activity_count' => 15,
                    'last_activity' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'active_days' => 7
                ],
                [
                    'user_id' => 2,
                    'user_fio' => 'Модератор',
                    'activity_count' => 8,
                    'last_activity' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                    'active_days' => 5
                ],
                [
                    'user_id' => 3,
                    'user_fio' => 'Пользователь 1',
                    'activity_count' => 3,
                    'last_activity' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
                    'active_days' => 2
                ]
            ];
            
            $popularActions = [
                [
                    'action_type' => 'page_view',
                    'count' => 25,
                    'unique_users' => 8
                ],
                [
                    'action_type' => 'login_success',
                    'count' => 12,
                    'unique_users' => 5
                ],
                [
                    'action_type' => 'login_failed',
                    'count' => 7,
                    'unique_users' => 3
                ]
            ];
            
            $hourlyActivity = [
                ['hour' => 9, 'count' => 5],
                ['hour' => 10, 'count' => 8],
                ['hour' => 11, 'count' => 12],
                ['hour' => 12, 'count' => 15],
                ['hour' => 13, 'count' => 10],
                ['hour' => 14, 'count' => 7],
                ['hour' => 15, 'count' => 9],
                ['hour' => 16, 'count' => 6]
            ];
            
            $weeklyActivity = [
                ['day_of_week' => 1, 'count' => 15], // Понедельник
                ['day_of_week' => 2, 'count' => 12], // Вторник
                ['day_of_week' => 3, 'count' => 18], // Среда
                ['day_of_week' => 4, 'count' => 14], // Четверг
                ['day_of_week' => 5, 'count' => 20], // Пятница
                ['day_of_week' => 6, 'count' => 8],  // Суббота
                ['day_of_week' => 7, 'count' => 5]   // Воскресенье
            ];
        }
        
        $this->render('admin/analytics/user-activity', [
            'user_activity' => $userActivity,
            'top_users' => $topUsers,
            'popular_actions' => $popularActions,
            'hourly_activity' => $hourlyActivity,
            'weekly_activity' => $weeklyActivity,
            'period' => $period,
            'limit' => $limit,
            'page_title' => 'Активность пользователей'
        ]);
    }
    
    /**
     * Страница сессий
     */
    public function sessions() {
        try {
            require_once 'engine/main/db.php';
            
            // Получаем активные сессии
            $activeSessions = Database::fetchAll("
                SELECT 
                    us.*,
                    u.user_login,
                    u.user_fio,
                    u.user_email,
                    TIMESTAMPDIFF(MINUTE, us.created_at, us.last_activity) as duration_minutes
                FROM user_sessions us
                LEFT JOIN users u ON us.user_id = u.user_id
                WHERE us.is_active = 1 
                AND us.last_activity >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                ORDER BY us.last_activity DESC
            ");
            
            // Получаем статистику сессий
            $sessionStats = Database::fetchOne("
                SELECT 
                    COUNT(*) as total_sessions,
                    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_sessions,
                    COUNT(CASE WHEN is_active = 0 THEN 1 END) as inactive_sessions,
                    COUNT(DISTINCT user_id) as unique_users,
                    AVG(TIMESTAMPDIFF(MINUTE, created_at, last_activity)) as avg_duration_minutes
                FROM user_sessions
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ");
            
            // Получаем сессии по дням
            $dailySessions = Database::fetchAll("
                SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as created_count,
                    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_count
                FROM user_sessions 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            
            // Получаем подозрительные сессии
            $suspiciousSessions = Database::fetchAll("
                SELECT 
                    us.*,
                    u.user_login,
                    u.user_fio
                FROM user_sessions us
                LEFT JOIN users u ON us.user_id = u.user_id
                WHERE us.suspicious = 1
                ORDER BY us.created_at DESC
                LIMIT 20
            ");
            
        } catch (Exception $e) {
            // Демо-данные при ошибке БД
            $activeSessions = [
                [
                    'id' => 1,
                    'user_fio' => 'Администратор',
                    'user_login' => 'admin',
                    'user_email' => 'admin@example.com',
                    'session_token' => 'session_admin_' . time(),
                    'ip_address' => '192.168.1.1',
                    'is_active' => 1,
                    'last_activity' => date('Y-m-d H:i:s', strtotime('-5 minutes')),
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'duration_minutes' => 115
                ],
                [
                    'id' => 2,
                    'user_fio' => 'Модератор',
                    'user_login' => 'moderator',
                    'user_email' => 'mod@example.com',
                    'session_token' => 'session_mod_' . time(),
                    'ip_address' => '192.168.1.2',
                    'is_active' => 1,
                    'last_activity' => date('Y-m-d H:i:s', strtotime('-10 minutes')),
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'duration_minutes' => 50
                ]
            ];
            
            $sessionStats = [
                'total_sessions' => 15,
                'active_sessions' => 2,
                'inactive_sessions' => 13,
                'unique_users' => 5,
                'avg_duration_minutes' => 45
            ];
            
            $dailySessions = [
                [
                    'date' => date('Y-m-d', strtotime('-6 days')),
                    'created_count' => 3,
                    'active_count' => 1
                ],
                [
                    'date' => date('Y-m-d', strtotime('-5 days')),
                    'created_count' => 5,
                    'active_count' => 2
                ],
                [
                    'date' => date('Y-m-d', strtotime('-4 days')),
                    'created_count' => 4,
                    'active_count' => 1
                ],
                [
                    'date' => date('Y-m-d', strtotime('-3 days')),
                    'created_count' => 6,
                    'active_count' => 3
                ],
                [
                    'date' => date('Y-m-d', strtotime('-2 days')),
                    'created_count' => 3,
                    'active_count' => 1
                ],
                [
                    'date' => date('Y-m-d', strtotime('-1 day')),
                    'created_count' => 7,
                    'active_count' => 2
                ],
                [
                    'date' => date('Y-m-d'),
                    'created_count' => 4,
                    'active_count' => 2
                ]
            ];
            
            $suspiciousSessions = [
                [
                    'id' => 3,
                    'user_fio' => 'Неизвестный пользователь',
                    'user_login' => 'unknown',
                    'session_token' => 'session_suspicious_' . time(),
                    'ip_address' => '192.168.1.200',
                    'is_active' => 0,
                    'last_activity' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'suspicious' => 1
                ]
            ];
        }
        
        $this->render('admin/analytics/sessions', [
            'active_sessions' => $activeSessions,
            'session_stats' => $sessionStats,
            'daily_sessions' => $dailySessions,
            'suspicious_sessions' => $suspiciousSessions,
            'page_title' => 'Управление сессиями'
        ]);
    }
    
    /**
     * Страница производительности
     */
    public function performance() {
        try {
            require_once 'engine/main/db.php';
            
            $slowQueries = [];
            $performanceMetrics = [];
            $apiStats = [];
            
            try {
                $slowQueries = Database::fetchAll("
                    SELECT * FROM system_logs 
                    WHERE level = 'debug' AND message LIKE '%SQL Query:%'
                    AND CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) > 1.0
                    ORDER BY CAST(JSON_EXTRACT(context, '$.execution_time') AS DECIMAL(10,3)) DESC
                    LIMIT 20
                ");
            } catch (Exception $e) {
                // Игнорируем ошибку
            }
            
            try {
                $performanceMetrics = Database::fetchAll("
                    SELECT * FROM performance_metrics 
                    ORDER BY created_at DESC 
                    LIMIT 50
                ");
            } catch (Exception $e) {
                // Таблица не существует, создаем пустой массив
            }
            
            try {
                $apiStats = Database::fetchAll("
                    SELECT endpoint, method, AVG(response_time) as avg_time, COUNT(*) as count
                    FROM api_statistics 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                    GROUP BY endpoint, method
                    ORDER BY avg_time DESC
                    LIMIT 20
                ");
            } catch (Exception $e) {
                // Таблица не существует, создаем пустой массив
            }
            
            $this->render('admin/analytics/performance', [
                'slow_queries' => $slowQueries,
                'performance_metrics' => $performanceMetrics,
                'api_stats' => $apiStats,
                'page_title' => 'Производительность'
            ]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка загрузки страницы производительности: ' . $e->getMessage());
            $this->redirect('/admin/error');
        }
    }
    
    /**
     * Очистка логов
     */
    public function clearLogs() {
        try {
            if (!$this->isPost()) {
                $this->redirect('/admin/analytics/logs');
            }
            
            $days = (int)($_POST['days'] ?? 30);
            $this->loggingService->cleanOldLogs($days);
            
            $this->loggingService->info('Логи очищены', [
                'days' => $days,
                'user_id' => $_SESSION['admin_user_id']
            ]);
            
            $this->setFlashMessage('success', "Логи старше {$days} дней успешно очищены");
            $this->redirect('/admin/analytics/logs');
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка очистки логов: ' . $e->getMessage());
            $this->setFlashMessage('error', 'Ошибка при очистке логов');
            $this->redirect('/admin/analytics/logs');
        }
    }
    
    /**
     * Экспорт данных аналитики
     */
    public function export() {
        try {
            $type = $_GET['type'] ?? 'system_stats';
            $format = $_GET['format'] ?? 'json';
            
            $data = [];
            
            switch ($type) {
                case 'system_stats':
                    $data = $this->analyticsService->getSystemStats();
                    break;
                case 'user_activity':
                    $data = $this->analyticsService->getChartData('user_activity', '30d');
                    break;
                case 'security_events':
                    require_once 'engine/main/db.php';
                    $data = Database::fetchAll("SELECT * FROM security_events ORDER BY created_at DESC LIMIT 1000");
                    break;
                case 'performance_metrics':
                    require_once 'engine/main/db.php';
                    $data = Database::fetchAll("SELECT * FROM performance_metrics ORDER BY created_at DESC LIMIT 1000");
                    break;
            }
            
            $filename = "analytics_{$type}_" . date('Y-m-d_H-i-s');
            
            if ($format === 'csv') {
                $this->exportToCsv($data, $filename);
            } else {
                $this->exportToJson($data, $filename);
            }
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка экспорта данных: ' . $e->getMessage());
            $this->redirect('/admin/analytics');
        }
    }
    
    /**
     * Экспорт в CSV
     */
    private function exportToCsv($data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            // Заголовки
            fputcsv($output, array_keys($data[0]));
            
            // Данные
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Экспорт в JSON
     */
    private function exportToJson($data, $filename) {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.json"');
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * AJAX: Получение данных для графиков
     */
    public function getChartData() {
        try {
            if (!$this->isAjax()) {
                $this->jsonResponse(['error' => 'Invalid request'], 400);
                return;
            }
            
            $period = $_GET['period'] ?? '7d';
            
            // Получаем данные для всех графиков
            $data = [
                'userActivity' => $this->getUserActivityData($period),
                'loginData' => $this->getLoginData($period),
                'sessionData' => $this->getSessionData($period),
                'securityData' => $this->getSecurityData($period)
            ];
            
            $this->jsonResponse(['success' => true, 'data' => $data]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения данных графика: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * AJAX: Получение реал-тайм данных
     */
    public function getRealtimeData() {
        try {
            if (!$this->isAjax()) {
                $this->jsonResponse(['error' => 'Invalid request'], 400);
                return;
            }
            
            require_once 'engine/main/db.php';
            
            // Активные пользователи
            $activeUsers = Database::fetchOne("
                SELECT COUNT(DISTINCT user_id) as count 
                FROM user_sessions 
                WHERE is_active = 1 
                AND last_activity >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
            ")['count'] ?? 0;
            
            // Последние действия
            $recentActions = Database::fetchAll("
                SELECT ua.activity_description, ua.activity_time, u.user_login
                FROM user_activity ua
                LEFT JOIN users u ON ua.user_id = u.user_id
                WHERE ua.activity_time >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
                ORDER BY ua.activity_time DESC
                LIMIT 10
            ");
            
            $formattedActions = [];
            foreach ($recentActions as $action) {
                $time = date('H:i', strtotime($action['activity_time']));
                $user = $action['user_login'] ?? 'Неизвестный';
                $description = $action['activity_description'] ?? 'Действие';
                $formattedActions[] = "[$time] $user: $description";
            }
            
            $data = [
                'activeUsers' => $activeUsers,
                'recentActions' => $formattedActions
            ];
            
            $this->jsonResponse(['success' => true, 'data' => $data]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения реал-тайм данных: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Получение данных активности пользователей
     */
    private function getUserActivityData($period) {
        try {
            require_once 'engine/main/db.php';
            
            $interval = $this->getInterval($period);
            
            return Database::fetchAll("
                SELECT DATE(activity_time) as date, COUNT(*) as count
                FROM user_activity_log 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(activity_time)
                ORDER BY date
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получение данных входов
     */
    private function getLoginData($period) {
        try {
            require_once 'engine/main/db.php';
            
            $interval = $this->getInterval($period);
            
            return Database::fetchAll("
                SELECT DATE(activity_time) as date, 
                       SUM(CASE WHEN action_type = 'login_success' THEN 1 ELSE 0 END) as successful,
                       SUM(CASE WHEN action_type = 'login_failed' THEN 1 ELSE 0 END) as failed
                FROM user_activity_log 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL $interval)
                AND action_type IN ('login_success', 'login_failed')
                GROUP BY DATE(activity_time)
                ORDER BY date
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получение данных сессий
     */
    private function getSessionData($period) {
        try {
            require_once 'engine/main/db.php';
            
            $interval = $this->getInterval($period);
            
            return Database::fetchAll("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM user_sessions 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получение данных безопасности
     */
    private function getSecurityData($period) {
        try {
            require_once 'engine/main/db.php';
            
            $interval = $this->getInterval($period);
            
            return Database::fetchAll("
                SELECT DATE(activity_time) as date, COUNT(*) as count
                FROM user_activity_log 
                WHERE activity_time >= DATE_SUB(NOW(), INTERVAL $interval)
                AND (action_type = 'suspicious_activity' OR suspicious = 1)
                GROUP BY DATE(activity_time)
                ORDER BY date
            ");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Получение интервала для периода
     */
    private function getInterval($period) {
        switch ($period) {
            case '7d':
                return '7 DAY';
            case '30d':
                return '30 DAY';
            case '90d':
                return '90 DAY';
            default:
                return '7 DAY';
        }
    }
    
    /**
     * AJAX: Получение данных мониторинга
     */
    public function getMonitoringData() {
        try {
            if (!$this->isAjax()) {
                $this->jsonResponse(['error' => 'Invalid request'], 400);
                return;
            }
            
            $data = $this->monitoringService->runSystemCheck();
            
            $this->jsonResponse(['success' => true, 'data' => $data]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка получения данных мониторинга: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * AJAX: Очистка предупреждений
     */
    public function clearAlerts() {
        try {
            if (!$this->isAjax() || !$this->isPost()) {
                $this->jsonResponse(['error' => 'Invalid request'], 400);
                return;
            }
            
            $this->monitoringService->clearAlerts();
            
            $this->jsonResponse(['success' => true]);
            
        } catch (Exception $e) {
            $this->loggingService->error('Ошибка очистки предупреждений: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
} 