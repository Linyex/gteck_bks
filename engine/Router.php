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
        $this->routes['api'] = ['controller' => 'api', 'action' => 'index'];
        $this->routes['message'] = ['controller' => 'message', 'action' => 'index'];
        $this->routes['files'] = ['controller' => 'files', 'action' => 'index'];
        $this->routes['tables'] = ['controller' => 'tables', 'action' => 'index'];
        $this->routes['error'] = ['controller' => 'error', 'action' => 'index'];
    }
    
    public function dispatch($url) {
        // Очищаем URL от лишних символов
        $url = trim($url, '/');
        // Разрешаем дефисы в URL (для slug), не удаляем '-'
        $url = preg_replace("/[^\w\d\s\/\-]/", '', $url);
        
        // Разбиваем URL на части
        $parts = explode('/', $url);
        $parts = array_filter($parts);
        
        // Определяем контроллер и действие
        $controller = $this->defaultController;
        $action = $this->defaultAction;
        $params = [];
        
        $rawAction = null;
        if (!empty($parts)) {
            // Первая часть - контроллер
            $controller = array_shift($parts);
            
            if (!empty($parts)) {
                // Вторая часть - действие (кандидат)
                $rawAction = array_shift($parts);
                $action = $rawAction;
                
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
            // Если метода нет, воспринимаем сегмент действия как первый параметр для index
            if ($rawAction !== null) { array_unshift($params, $rawAction); }
            $action = 'index';
        }
        
        // Поддержка вложенных методов для API, например /api/content-overrides
        if ($controller === 'api') {
            if (!empty($params)) {
                $subaction = str_replace('-', '', implode('', $params));
                if (method_exists($controllerInstance, $subaction)) {
                    return $controllerInstance->$subaction();
                }
            }
            // POST /api/translate
            if ($action === 'translate' && method_exists($controllerInstance, 'translate')) {
                return $controllerInstance->translate();
            }
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