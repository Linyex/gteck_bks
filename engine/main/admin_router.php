<?php

class AdminRouter {
    
    private $routes = [
        'GET' => [
            '/admin/login' => ['AuthController', 'login'],
            '/admin/logout' => ['AuthController', 'logout'],
            '/admin' => ['DashboardController', 'index'],
            '/admin/dashboard' => ['DashboardController', 'index'],
            '/admin/index' => ['DashboardController', 'index'],
            // Оригинальные маршруты
            '/admin/users' => ['UsersController', 'index'],
            '/admin/users/create' => ['UsersController', 'create'],
            '/admin/users/edit/{id}' => ['UsersController', 'edit'],
            '/admin/users/view/{id}' => ['UsersController', 'view'],
            '/admin/news' => ['NewsController', 'index'],
            '/admin/news/create' => ['NewsController', 'create'],
            '/admin/news/edit/{id}' => ['NewsController', 'edit'],
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
            // Расширенная аналитика
            '/admin/enhanced-analytics' => ['EnhancedAnalyticsController', 'index'],
            '/admin/enhanced-analytics/geolocation' => ['EnhancedAnalyticsController', 'geolocation'],
            '/admin/enhanced-analytics/behavior' => ['EnhancedAnalyticsController', 'behavior'],
            '/admin/enhanced-analytics/ml-anomalies' => ['EnhancedAnalyticsController', 'mlAnomalies'],
            '/admin/enhanced-analytics/notifications' => ['EnhancedAnalyticsController', 'notifications'],
            '/admin/enhanced-analytics/reports' => ['EnhancedAnalyticsController', 'reports'],
            '/admin/enhanced-analytics/api' => ['EnhancedAnalyticsController', 'api'],
            '/admin/enhanced-analytics/export-data' => ['EnhancedAnalyticsController', 'exportData'],
            // Уведомления
            '/admin/notifications' => ['NotificationController', 'index'],
            '/admin/notifications/settings' => ['NotificationController', 'settings'],
            '/admin/notifications/api' => ['NotificationController', 'api']
        ],
        'POST' => [
            '/admin/login' => ['AuthController', 'login'],
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
            '/admin/notifications/resolve-incident' => ['NotificationController', 'resolveIncident']
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
        
        // Проверяем совпадение с параметрами
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = $this->buildPattern($route);
            if (preg_match($pattern, $uri, $matches)) {
                error_log("AdminRouter: Совпадение с параметрами найдено для $route");
                array_shift($matches); // Убираем полное совпадение
                return $this->executeRoute($handler, $matches);
            }
        }
        
        // Если маршрут не найден
        error_log("AdminRouter: Маршрут не найден для $uri ($method)");
        return $this->notFound();
    }
    
    private function buildPattern($route) {
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }
    
    private function executeRoute($handler, $params = []) {
        $controllerName = $handler[0];
        $methodName = $handler[1];
        
        // Подключаем базовые классы
        $this->loadBaseClasses();
        
        // Подключаем контроллер
        $controllerFile = APPLICATION_DIR . "controllers/admin/{$controllerName}.php";
        
        if (!file_exists($controllerFile)) {
            error_log("AdminRouter: Контроллер не найден: $controllerFile");
            return $this->notFound();
        }
        
        require_once $controllerFile;
        
        // Создаем экземпляр контроллера
        $controller = new $controllerName();
        
        // Проверяем, что метод существует
        if (!method_exists($controller, $methodName)) {
            error_log("AdminRouter: Метод не найден: $methodName в $controllerName");
            return $this->notFound();
        }
        
        // Вызываем метод с параметрами
        try {
            $result = call_user_func_array([$controller, $methodName], $params);
            error_log("AdminRouter: Метод выполнен успешно: $controllerName::$methodName");
            return $result;
        } catch (Exception $e) {
            error_log("AdminRouter: Ошибка выполнения метода: " . $e->getMessage());
            return $this->notFound();
        }
    }
    
    private function loadBaseClasses() {
        // Define constants if not already defined
        if (!defined('ENGINE_DIR')) {
            define('ENGINE_DIR', dirname(dirname(__FILE__)) . '/engine/');
        }
        if (!defined('APPLICATION_DIR')) {
            define('APPLICATION_DIR', dirname(dirname(__FILE__)) . '/application/');
        }
        
        // Подключаем базовый контроллер
        if (!class_exists('BaseController')) {
            $baseControllerFile = ENGINE_DIR . 'BaseController.php';
            if (file_exists($baseControllerFile)) {
                require_once $baseControllerFile;
            }
        }
        
        // Подключаем базовый админский контроллер
        if (!class_exists('BaseAdminController')) {
            $baseAdminControllerFile = APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';
            if (file_exists($baseAdminControllerFile)) {
                require_once $baseAdminControllerFile;
            }
        }
        
        // Подключаем базу данных
        if (!class_exists('Database')) {
            $dbFile = ENGINE_DIR . 'main/db.php';
            if (file_exists($dbFile)) {
                require_once $dbFile;
            }
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
        // Извлекаем переменные из массива данных
        extract($data);
        
        // Подключаем шаблон
        $viewFile = "application/views/{$view}.php";
        
        if (file_exists($viewFile)) {
            ob_start();
            include $viewFile;
            return ob_get_clean();
        } else {
            return "View {$view} not found";
        }
    }
} 