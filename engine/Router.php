<?php

class Router {
    private $routes = [];
    private $defaultController = 'main';
    private $defaultAction = 'index';
    
    public function __construct() {
        // Регистрируем стандартные маршруты
        $this->registerDefaultRoutes();
    }
    
    private function registerDefaultRoutes() {
        // Основные маршруты
        $this->routes[''] = ['controller' => 'main', 'action' => 'index'];
        $this->routes['main'] = ['controller' => 'main', 'action' => 'index'];
        $this->routes['news'] = ['controller' => 'news', 'action' => 'index'];
        $this->routes['admin'] = ['controller' => 'admin', 'action' => 'index'];
        $this->routes['stud'] = ['controller' => 'stud', 'action' => 'index'];
        $this->routes['prepod'] = ['controller' => 'prepod', 'action' => 'index'];
        $this->routes['kol'] = ['controller' => 'kol', 'action' => 'index'];
        $this->routes['abut'] = ['controller' => 'abut', 'action' => 'index'];
        $this->routes['okno'] = ['controller' => 'okno', 'action' => 'index'];
        $this->routes['dopage'] = ['controller' => 'dopage', 'action' => 'index'];
        $this->routes['search'] = ['controller' => 'search', 'action' => 'index'];
        $this->routes['message'] = ['controller' => 'message', 'action' => 'index'];
        $this->routes['files'] = ['controller' => 'files', 'action' => 'index'];
        $this->routes['error'] = ['controller' => 'error', 'action' => 'index'];
    }
    
    public function dispatch($url) {
        // Очищаем URL от лишних символов
        $url = trim($url, '/');
        $url = preg_replace("/[^\w\d\s\/]/", '', $url);
        
        // Разбиваем URL на части
        $parts = explode('/', $url);
        $parts = array_filter($parts);
        
        // Определяем контроллер и действие
        $controller = $this->defaultController;
        $action = $this->defaultAction;
        $params = [];
        
        if (!empty($parts)) {
            // Первая часть - контроллер
            $controller = array_shift($parts);
            
            if (!empty($parts)) {
                // Вторая часть - действие
                $action = array_shift($parts);
                
                // Остальные части - параметры
                $params = $parts;
            }
        }
        
        // Проверяем существование контроллера
        $controllerFile = APPLICATION_DIR . 'controllers/' . $controller . '.php';
        $controllerClass = $controller . 'Controller';
        
        if (!file_exists($controllerFile)) {
            // Если контроллер не найден, пробуем найти в подпапках
            $controllerFile = APPLICATION_DIR . 'controllers/' . $controller . '/' . $action . '.php';
            $controllerClass = $action . 'Controller';
            
            if (!file_exists($controllerFile)) {
                // Возвращаем 404
                return $this->handle404();
            }
        }
        
        // Загружаем контроллер
        require_once($controllerFile);
        
        if (!class_exists($controllerClass)) {
            return $this->handle404();
        }
        
        // Создаем экземпляр контроллера
        $controllerInstance = new $controllerClass();
        
        // Проверяем существование метода
        if (!method_exists($controllerInstance, $action)) {
            $action = 'index';
        }
        
        // Вызываем метод контроллера
        if (empty($params)) {
            return $controllerInstance->$action();
        } else {
            return call_user_func_array([$controllerInstance, $action], $params);
        }
    }
    
    private function handle404() {
        header("HTTP/1.0 404 Not Found");
        require_once(APPLICATION_DIR . 'controllers/error.php');
        $errorController = new errorController();
        return $errorController->index();
    }
} 