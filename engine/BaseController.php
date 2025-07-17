<?php

class BaseController {
    protected $db;
    protected $config;
    protected $data = [];
    
    public function __construct() {
        // Инициализируем подключение к БД
        $this->db = new Database();
        
        // Загружаем конфигурацию
        $this->config = require(APPLICATION_DIR . 'config.php');
    }
    
    // Метод для загрузки модели
    protected function loadModel($modelName) {
        $modelFile = APPLICATION_DIR . 'models/' . $modelName . '.php';
        $modelClass = $modelName . 'Model';
        
        if (file_exists($modelFile)) {
            require_once($modelFile);
            return new $modelClass($this->db);
        }
        
        throw new Exception("Model {$modelName} not found");
    }
    
    // Метод для рендеринга view
    protected function render($view, $data = []) {
        // Объединяем данные
        $viewData = array_merge($this->data, $data);
        
        // Извлекаем переменные для view
        extract($viewData);
        
        // Начинаем буферизацию
        ob_start();
        
        // Проверяем, является ли это админским view
        $isAdminView = strpos($view, 'admin/') === 0;
        
        if (!$isAdminView) {
            // Подключаем header только для не-админских страниц
            if (file_exists(APPLICATION_DIR . 'views/common/header.php')) {
                include(APPLICATION_DIR . 'views/common/header.php');
            }
        }
        
        // Подключаем основной view
        $viewFile = APPLICATION_DIR . 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include($viewFile);
        } else {
            throw new Exception("View {$view} not found");
        }
        
        if (!$isAdminView) {
            // Подключаем footer только для не-админских страниц
            if (file_exists(APPLICATION_DIR . 'views/common/footer.php')) {
                include(APPLICATION_DIR . 'views/common/footer.php');
            }
        }
        
        // Возвращаем содержимое буфера
        return ob_get_clean();
    }
    
    // Метод для рендеринга без header/footer (для AJAX)
    protected function renderPartial($view, $data = []) {
        $viewData = array_merge($this->data, $data);
        extract($viewData);
        
        ob_start();
        $viewFile = APPLICATION_DIR . 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include($viewFile);
        } else {
            throw new Exception("View {$view} not found");
        }
        return ob_get_clean();
    }
    
    // Метод для JSON ответа
    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }
    
    // Метод для редиректа
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
    
    // Метод для получения POST данных
    protected function getPost($key = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }
    
    // Метод для получения GET данных
    protected function getGet($key = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
    
    // Метод для проверки AJAX запроса
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
} 