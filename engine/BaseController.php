<?php

class BaseController {
    protected $db;
    protected $config;
    protected $data = [];
    protected $settings = [];
    
    public function __construct() {
        // Инициализируем подключение к БД
        require_once ENGINE_DIR . 'main/db.php';
        
        // Инициализируем объект базы данных
        $this->db = new class {
            public function fetchOne($sql, $params = []) {
                return Database::fetchOne($sql, $params);
            }
            
            public function fetchAll($sql, $params = []) {
                return Database::fetchAll($sql, $params);
            }
            
            public function execute($sql, $params = []) {
                return Database::execute($sql, $params);
            }
            
            public function lastInsertId() {
                return Database::lastInsertId();
            }
        };
        
        // Загружаем конфигурацию
        $this->config = require(APPLICATION_DIR . 'config.php');

        // Глобальные настройки из БД
        $this->loadAppSettings();

        // Принудительное перенаправление на HTTPS при необходимости
        $this->enforceHttpsIfRequired();
    }
    
    // Метод для загрузки модели
    protected function loadModel($modelName) {
        // Загружаем базовый класс Model
        require_once ENGINE_DIR . 'libs/Model.php';
        
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
        
        // Безопасностные и кэш-заголовки при необходимости
        $this->applySecurityHeaders();
        $this->applyCacheHeaders();

        // Выводим содержимое буфера
        echo ob_get_clean();
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

    // ====== Дополнительно: настройки приложения ======
    protected function loadAppSettings() {
        try {
            $rows = Database::fetchAll("SELECT setting_key, setting_value FROM settings");
            $settings = [];
            foreach ($rows as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }

            // Значения по умолчанию
            $defaults = [
                'site_name' => 'NoContrGtec',
                'site_description' => 'Административная панель',
                'admin_email' => 'admin@example.com',
                'max_file_size' => 10,
                'allowed_file_types' => 'jpg,jpeg,png,gif,pdf,doc,docx',
                'enable_registration' => 1,
                'enable_notifications' => 1,
                'session_timeout' => 3600,
                'maintenance_mode' => 0,
                'enable_lazy_loading' => 1,
                'enable_service_worker' => 1,
                'cache_max_age' => 86400,
                'security_enforce_https' => 0,
                'security_content_security_policy' => "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'"
            ];

            $this->settings = array_merge($defaults, $settings);
        } catch (Exception $e) {
            // В случае ошибки используем дефолты
            $this->settings = [
                'enable_lazy_loading' => 1,
                'enable_service_worker' => 1,
                'cache_max_age' => 86400,
                'security_enforce_https' => 0,
                'security_content_security_policy' => "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'"
            ];
        }

        // Прокидываем в представления
        $this->data['settings'] = $this->settings;
    }

    protected function enforceHttpsIfRequired() {
        if (!empty($this->settings['security_enforce_https'])) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                       (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
                       (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
            if (!$isHttps) {
                $host = $_SERVER['HTTP_HOST'] ?? '';
                $uri = $_SERVER['REQUEST_URI'] ?? '/';
                header('Location: https://' . $host . $uri, true, 301);
                exit;
            }
        }
    }

    protected function applyCacheHeaders() {
        if (!headers_sent() && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $maxAge = (int)($this->settings['cache_max_age'] ?? 0);
            if ($maxAge > 0) {
                header('Cache-Control: public, max-age=' . $maxAge);
            }
        }
    }

    protected function applySecurityHeaders() {
        if (!headers_sent()) {
            $csp = (string)($this->settings['security_content_security_policy'] ?? "");
            if ($csp !== '') {
                header('Content-Security-Policy: ' . $csp);
            }
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('Referrer-Policy: strict-origin-when-cross-origin');
        }
    }
} 