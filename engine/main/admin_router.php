<?php

class AdminRouter {
    
    private $routes = [
        'GET' => [
            '/admin/login' => ['AuthController', 'login'],
            '/admin/logout' => ['AuthController', 'logout'],
            '/admin/dashboard' => ['DashboardController', 'index'],
            '/admin/users' => ['UsersController', 'index'],
            '/admin/users/create' => ['UsersController', 'create'],
            '/admin/users/edit/{id}' => ['UsersController', 'edit'],
            '/admin/news' => ['NewsController', 'index'],
            '/admin/news/create' => ['NewsController', 'create'],
            '/admin/news/edit/{id}' => ['NewsController', 'edit'],
            '/admin/files' => ['FilesController', 'index'],
            '/admin/files/upload' => ['FilesController', 'upload'],
            '/admin/photos' => ['PhotosController', 'index'],
            '/admin/photos/upload' => ['PhotosController', 'upload'],
            '/admin/settings' => ['SettingsController', 'index'],
            '/admin/profile' => ['ProfileController', 'index']
        ],
        'POST' => [
            '/admin/login' => ['AuthController', 'login'],
            '/admin/users/create' => ['UsersController', 'store'],
            '/admin/users/edit/{id}' => ['UsersController', 'update'],
            '/admin/users/delete/{id}' => ['UsersController', 'delete'],
            '/admin/news/create' => ['NewsController', 'store'],
            '/admin/news/edit/{id}' => ['NewsController', 'update'],
            '/admin/news/delete/{id}' => ['NewsController', 'delete'],
            '/admin/files/upload' => ['FilesController', 'store'],
            '/admin/files/delete/{id}' => ['FilesController', 'delete'],
            '/admin/photos/upload' => ['PhotosController', 'store'],
            '/admin/photos/delete/{id}' => ['PhotosController', 'delete'],
            '/admin/settings' => ['SettingsController', 'update'],
            '/admin/profile' => ['ProfileController', 'update']
        ]
    ];
    
    public function route($uri, $method = 'GET') {
        // Убираем trailing slash
        $uri = rtrim($uri, '/');
        
        // Проверяем точное совпадение
        if (isset($this->routes[$method][$uri])) {
            return $this->executeRoute($this->routes[$method][$uri]);
        }
        
        // Проверяем совпадение с параметрами
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = $this->buildPattern($route);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Убираем полное совпадение
                return $this->executeRoute($handler, $matches);
            }
        }
        
        // Если маршрут не найден
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
        $controllerFile = "application/controllers/admin/{$controllerName}.php";
        
        if (!file_exists($controllerFile)) {
            return $this->notFound();
        }
        
        require_once $controllerFile;
        
        // Создаем экземпляр контроллера
        $controller = new $controllerName();
        
        // Проверяем, что метод существует
        if (!method_exists($controller, $methodName)) {
            return $this->notFound();
        }
        
        // Вызываем метод с параметрами
        return call_user_func_array([$controller, $methodName], $params);
    }
    
    private function loadBaseClasses() {
        // Подключаем базовый контроллер (уже подключен в index.php)
        if (!class_exists('BaseController') && file_exists('engine/BaseController.php')) {
            require_once 'engine/BaseController.php';
        }
        
        // Подключаем базовый админский контроллер
        if (!class_exists('BaseAdminController') && file_exists('application/controllers/admin/BaseAdminController.php')) {
            require_once 'application/controllers/admin/BaseAdminController.php';
        }
        
        // Подключаем базу данных (уже подключена в index.php)
        if (!class_exists('Database') && file_exists('engine/main/db.php')) {
            require_once 'engine/main/db.php';
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