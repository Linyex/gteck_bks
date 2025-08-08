<?php

class AdminRouter {
    
    private $routes = [
        'GET' => [
            '/admin/login' => ['AuthController', 'login'],
            '/admin/logout' => ['AuthController', 'logout'],
            '/admin' => ['DashboardController', 'index'],
            '/admin/dashboard' => ['DashboardController', 'index'],
            '/admin/index' => ['DashboardController', 'index'],
            '/admin/test' => ['TestController', 'index'],
            // 2FA маршруты
            '/admin/2fa' => ['TwoFactorController', 'index'],
            '/admin/2fa/verify' => ['TwoFactorController', 'showVerify'],
            // Оригинальные маршруты
            '/admin/users' => ['UsersController', 'index'],
            '/admin/users/create' => ['UsersController', 'create'],
            '/admin/users/edit/{id}' => ['UsersController', 'edit'],
            '/admin/users/view/{id}' => ['UsersController', 'view'],
            '/admin/news' => ['NewsController', 'index'],
            '/admin/news/create' => ['NewsController', 'create'],
            '/admin/news/edit/{id}' => ['NewsController', 'edit'],
            '/admin/news/confirm-delete/{id}' => ['NewsController', 'confirmDelete'],
            '/admin/files' => ['FilesController', 'index'],
            '/admin/files/upload' => ['FilesController', 'upload'],
            '/admin/photos' => ['PhotosController', 'index'],
            '/admin/photos/upload' => ['PhotosController', 'upload'],
            '/admin/settings' => ['SettingsController', 'index'],
            '/admin/profile' => ['ProfileController', 'index'],
            // Аналитика
            '/admin/analytics' => ['AnalyticsController', 'index'],
            '/admin/analytics/security' => ['AnalyticsController', 'security'],
            '/admin/analytics/user-activity' => ['AnalyticsController', 'userActivity'],
            '/admin/analytics/sessions' => ['AnalyticsController', 'sessions'],
            '/admin/analytics/api' => ['AnalyticsController', 'api'],
            '/admin/analytics/api/chart-data' => ['AnalyticsController', 'getChartData'],
            '/admin/analytics/api/realtime' => ['AnalyticsController', 'getRealtimeData'],
            // Расширенная аналитика
            '/admin/enhanced-analytics' => ['EnhancedAnalyticsController', 'index'],
            '/admin/enhanced-analytics/activity-details' => ['EnhancedAnalyticsController', 'activityDetails'],
            '/admin/enhanced-analytics/geolocation' => ['EnhancedAnalyticsController', 'geolocation'],
            '/admin/enhanced-analytics/behavior' => ['EnhancedAnalyticsController', 'behavior'],
            '/admin/enhanced-analytics/ml-anomalies' => ['EnhancedAnalyticsController', 'mlAnomalies'],
            '/admin/enhanced-analytics/notifications' => ['EnhancedAnalyticsController', 'notifications'],
            '/admin/enhanced-analytics/reports' => ['EnhancedAnalyticsController', 'reports'],
            '/admin/enhanced-analytics/api' => ['EnhancedAnalyticsController', 'api'],
            '/admin/enhanced-analytics/export-data' => ['EnhancedAnalyticsController', 'exportData'],
            // Безопасность
            '/admin/security' => ['SecurityController', 'index'],
            '/admin/security/audit' => ['SecurityController', 'audit'],
            '/admin/security/ip-blacklist' => ['SecurityController', 'ipBlacklist'],
            '/admin/security/sessions' => ['SecurityController', 'sessions'],
            '/admin/security/export-logs' => ['SecurityController', 'exportLogs'],
            '/admin/security/cleanup-logs' => ['SecurityController', 'cleanupLogs'],
            '/admin/security/api/stats' => ['SecurityController', 'apiStats'],
            '/admin/security/api/events' => ['SecurityController', 'apiEvents'],
            // Мониторинг
            '/admin/monitoring' => ['MonitoringController', 'index'],
            '/admin/monitoring/logs' => ['MonitoringController', 'logs'],
            '/admin/monitoring/threats' => ['MonitoringController', 'threats'],
            '/admin/monitoring/reports' => ['MonitoringController', 'reports'],
            '/admin/monitoring/settings' => ['MonitoringController', 'settings'],
            '/admin/monitoring/api/stats' => ['MonitoringController', 'apiStats'],
            '/admin/monitoring/api/threats' => ['MonitoringController', 'apiThreats'],
            '/admin/monitoring/api/logs' => ['MonitoringController', 'apiLogs'],
            '/admin/monitoring/api/run-monitoring' => ['MonitoringController', 'apiRunMonitoring'],
            // Уведомления
            '/admin/notifications' => ['NotificationController', 'index'],
            '/admin/notifications/settings' => ['NotificationController', 'settings'],
            '/admin/notifications/api' => ['NotificationController', 'api'],
            // Пароли групп
            '/admin/group-passwords' => ['GroupPasswordsController', 'index'],
            '/admin/group-passwords/create' => ['GroupPasswordsController', 'create'],
            '/admin/group-passwords/edit/{id}' => ['GroupPasswordsController', 'edit'],
            // Файлы контрольных работ
            '/admin/control-files' => ['ControlFilesController', 'index'],
            '/admin/control-files/upload' => ['ControlFilesController', 'upload'],
            '/admin/control-files/edit/{id}' => ['ControlFilesController', 'edit'],
            // Файлы УМК
            '/admin/umk-files' => ['UmkFilesController', 'index'],
            '/admin/umk-files/upload' => ['UmkFilesController', 'upload'],
            '/admin/umk-files/edit/{id}' => ['UmkFilesController', 'edit'],
            // API маршруты
            '/api/auth/me' => ['AuthApiController', 'me'],
            '/api/users' => ['UsersApiController', 'index'],
            '/api/users/{id}' => ['UsersApiController', 'show'],
            '/api/news' => ['NewsApiController', 'index'],
            '/api/news/{id}' => ['NewsApiController', 'show']
        ],
        'POST' => [
            '/admin/login' => ['AuthController', 'login'],
            // 2FA POST маршруты
            '/admin/2fa/enable' => ['TwoFactorController', 'enable'],
            '/admin/2fa/disable' => ['TwoFactorController', 'disable'],
            '/admin/2fa/verify' => ['TwoFactorController', 'verify'],
            '/admin/2fa/regenerate-backup' => ['TwoFactorController', 'regenerateBackupCodes'],
            // Оригинальные POST маршруты
            '/admin/users/create' => ['UsersController', 'store'],
            '/admin/users/edit/{id}' => ['UsersController', 'update'],
            '/admin/users/delete/{id}' => ['UsersController', 'delete'],
            '/admin/users/block/{id}' => ['UsersController', 'block'],
            '/admin/users/unblock/{id}' => ['UsersController', 'unblock'],
            '/admin/users/reset-password/{id}' => ['UsersController', 'resetPassword'],
            '/admin/users/force-password-change/{id}' => ['UsersController', 'forcePasswordChange'],
            '/admin/users/terminate-sessions/{id}' => ['UsersController', 'terminateSessions'],
            '/admin/users/mass-action' => ['UsersController', 'massAction'],
            '/admin/news/store' => ['NewsController', 'store'],
            '/admin/news/update/{id}' => ['NewsController', 'update'],
            '/admin/news/delete/{id}' => ['NewsController', 'delete'],
            '/admin/files/upload' => ['FilesController', 'store'],
            '/admin/files/delete/{id}' => ['FilesController', 'delete'],
            '/admin/photos/upload' => ['PhotosController', 'store'],
            '/admin/photos/delete/{id}' => ['PhotosController', 'delete'],
            '/admin/settings' => ['SettingsController', 'update'],
            '/admin/profile' => ['ProfileController', 'update'],
            // API аналитики
            '/admin/analytics/mark-notification-read' => ['AnalyticsController', 'markNotificationRead'],
            '/admin/analytics/mark-all-notifications-read' => ['AnalyticsController', 'markAllNotificationsRead'],
            '/admin/analytics/block-ip' => ['AnalyticsController', 'blockIP'],
            '/admin/analytics/block-all-suspicious-ips' => ['AnalyticsController', 'blockAllSuspiciousIPs'],
            // API расширенной аналитики
            '/admin/enhanced-analytics/mark-alert-read' => ['EnhancedAnalyticsController', 'markAlertRead'],
            '/admin/enhanced-analytics/update-notification-settings' => ['EnhancedAnalyticsController', 'updateNotificationSettings'],
            '/admin/enhanced-analytics/block-all-suspicious-ips' => ['EnhancedAnalyticsController', 'blockAllSuspiciousIPs'],
            '/admin/enhanced-analytics/generate-report' => ['EnhancedAnalyticsController', 'generateReport'],
            '/admin/enhanced-analytics/send-alert' => ['EnhancedAnalyticsController', 'sendAlert'],
            '/admin/enhanced-analytics/analyze-anomalies' => ['EnhancedAnalyticsController', 'analyzeAnomalies'],
            // API уведомлений
            '/admin/notifications/update-settings' => ['NotificationController', 'updateSettings'],
            '/admin/notifications/test-notification' => ['NotificationController', 'testNotification'],
            '/admin/notifications/send-notification' => ['NotificationController', 'sendNotification'],
            '/admin/notifications/mark-as-read' => ['NotificationController', 'markAsRead'],
            '/admin/notifications/mark-all-as-read' => ['NotificationController', 'markAllAsRead'],
            '/admin/notifications/resolve-incident' => ['NotificationController', 'resolveIncident'],
            // Пароли групп POST маршруты
            '/admin/group-passwords/create' => ['GroupPasswordsController', 'create'],
            '/admin/group-passwords/edit/{id}' => ['GroupPasswordsController', 'edit'],
            '/admin/group-passwords/delete' => ['GroupPasswordsController', 'delete'],
            '/admin/group-passwords/toggle' => ['GroupPasswordsController', 'toggle'],
            // Файлы контрольных работ POST маршруты
            '/admin/control-files/upload' => ['ControlFilesController', 'upload'],
            '/admin/control-files/edit/{id}' => ['ControlFilesController', 'edit'],
            '/admin/control-files/delete' => ['ControlFilesController', 'delete'],
            // Файлы УМК POST маршруты
            '/admin/umk-files/upload' => ['UmkFilesController', 'upload'],
            '/admin/umk-files/edit/{id}' => ['UmkFilesController', 'edit'],
            '/admin/umk-files/delete' => ['UmkFilesController', 'delete'],
            // Безопасность POST маршруты
            '/admin/security/ip-blacklist/add' => ['SecurityController', 'addToBlacklist'],
            '/admin/security/ip-blacklist/remove' => ['SecurityController', 'removeFromBlacklist'],
            '/admin/security/sessions/terminate' => ['SecurityController', 'terminateSession'],
            '/admin/security/cleanup-logs' => ['SecurityController', 'cleanupLogs'],
            // API POST маршруты
            '/api/auth/login' => ['AuthApiController', 'login'],
            '/api/auth/logout' => ['AuthApiController', 'logout'],
            '/api/auth/refresh' => ['AuthApiController', 'refresh'],
            '/api/auth/change-password' => ['AuthApiController', 'changePassword'],
            '/api/auth/2fa/verify' => ['AuthApiController', 'verify2fa'],
            '/api/users' => ['UsersApiController', 'store'],
            '/api/users/{id}/block' => ['UsersApiController', 'block'],
            '/api/users/{id}/unblock' => ['UsersApiController', 'unblock'],
            '/api/news' => ['NewsApiController', 'store'],
            '/api/news/upload-image' => ['NewsApiController', 'uploadImage'],
            // API аналитики
            '/api/analytics/stats' => ['AnalyticsApiController', 'getStats'],
            '/api/analytics/monitoring' => ['AnalyticsApiController', 'getMonitoring'],
            '/api/analytics/charts/{type}' => ['AnalyticsApiController', 'getChartData'],
            '/api/analytics/users/top' => ['AnalyticsApiController', 'getTopUsers'],
            '/api/analytics/actions/popular' => ['AnalyticsApiController', 'getPopularActions'],
            '/api/analytics/logs' => ['AnalyticsApiController', 'getLogs'],
            '/api/analytics/security/events' => ['AnalyticsApiController', 'getSecurityEvents'],
            '/api/analytics/performance/metrics' => ['AnalyticsApiController', 'getPerformanceMetrics'],
            '/api/analytics/performance/slow-queries' => ['AnalyticsApiController', 'getSlowQueries'],
            '/api/analytics/errors' => ['AnalyticsApiController', 'getErrors'],
            '/api/analytics/export/{type}' => ['AnalyticsApiController', 'exportData']
        ],
        'PUT' => [
            '/api/users/{id}' => ['UsersApiController', 'update'],
            '/api/news/{id}' => ['NewsApiController', 'update']
        ],
        'DELETE' => [
            '/api/users/{id}' => ['UsersApiController', 'destroy'],
            '/api/news/{id}' => ['NewsApiController', 'destroy']
        ]
    ];
    
    public function route($uri, $method = 'GET') {
        // Убираем trailing slash
        $uri = rtrim($uri, '/');
        
        // Отладочная информация
        error_log("AdminRouter: URI = $uri, Method = $method");
        
        // Проверяем точное совпадение
        if (isset($this->routes[$method][$uri])) {
            error_log("AdminRouter: Точное совпадение найдено для $uri");
            return $this->executeRoute($this->routes[$method][$uri]);
        }
        
        // Проверяем маршруты с параметрами
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = $this->buildPattern($route);
            if (preg_match($pattern, $uri, $matches)) {
                error_log("AdminRouter: Совпадение с параметрами найдено для $uri");
                array_shift($matches); // Убираем полное совпадение
                return $this->executeRoute($handler, $matches);
            }
        }
        
        error_log("AdminRouter: Маршрут не найден для $uri");
        return $this->notFound();
    }
    
    private function buildPattern($route) {
        return '#^' . preg_replace('#\{([a-zA-Z0-9_]+)\}#', '([^/]+)', $route) . '$#';
    }
    
    private function executeRoute($handler, $params = []) {
        try {
            $this->loadBaseClasses();
            
            $controllerName = $handler[0];
            $methodName = $handler[1];
            
            error_log("AdminRouter: Выполняем $controllerName::$methodName");
            
            // Загружаем файл контроллера
            $controllerFile = APPLICATION_DIR . 'controllers/admin/' . $controllerName . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
            } else {
                error_log("AdminRouter: Файл контроллера не найден: $controllerFile");
                throw new Exception("Controller file $controllerName.php not found");
            }
            
            if (!class_exists($controllerName)) {
                error_log("AdminRouter: Класс $controllerName не найден");
                throw new Exception("Controller $controllerName not found");
            }
            
            $controller = new $controllerName();
            
            if (!method_exists($controller, $methodName)) {
                error_log("AdminRouter: Метод $methodName не найден в $controllerName");
                throw new Exception("Method $methodName not found in $controllerName");
            }
            
            // Вызываем метод с параметрами
            call_user_func_array([$controller, $methodName], $params);
            
        } catch (Exception $e) {
            error_log("AdminRouter: Ошибка при выполнении маршрута: " . $e->getMessage());
            echo $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при выполнении запроса: ' . $e->getMessage()
            ]);
        }
    }
    
    private function loadBaseClasses() {
        // Определяем константы, если они не определены
        if (!defined('ENGINE_DIR')) {
            define('ENGINE_DIR', dirname(__FILE__) . '/../../');
        }
        if (!defined('APPLICATION_DIR')) {
            define('APPLICATION_DIR', dirname(__FILE__) . '/../../../application/');
        }
        
        // Подключаем базовые классы
        require_once ENGINE_DIR . 'BaseController.php';
        require_once APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';
        
        // Подключаем Database если не подключен
        if (!class_exists('Database')) {
            require_once ENGINE_DIR . 'main/db.php';
        }
    }
    
    private function notFound() {
        http_response_code(404);
        return $this->render('admin/error/404', [
            'title' => 'Страница не найдена',
            'message' => 'Запрашиваемая страница не существует'
        ]);
    }
    
    private function render($view, $data = []) {
        // Извлекаем переменные для view
        extract($data);
        
        // Начинаем буферизацию
        ob_start();
        
        // Подключаем view
        $viewFile = APPLICATION_DIR . 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include($viewFile);
        } else {
            echo "View {$view} not found";
        }
        
        return ob_get_clean();
    }
} 