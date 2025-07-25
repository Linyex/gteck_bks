<?php

/**
 * CSRF защита
 * Обеспечивает защиту от Cross-Site Request Forgery атак
 */
class CSRFProtection {
    
    private static $tokenName = 'csrf_token';
    private static $sessionKey = 'csrf_tokens';
    
    /**
     * Генерирует CSRF токен
     */
    public static function generateToken() {
        if (!isset($_SESSION[self::$sessionKey])) {
            $_SESSION[self::$sessionKey] = [];
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION[self::$sessionKey][$token] = [
            'created_at' => time(),
            'used' => false
        ];
        
        return $token;
    }
    
    /**
     * Проверяет CSRF токен
     */
    public static function verifyToken($token) {
        if (empty($token)) {
            return false;
        }
        
        if (!isset($_SESSION[self::$sessionKey][$token])) {
            return false;
        }
        
        $tokenData = $_SESSION[self::$sessionKey][$token];
        
        // Проверяем срок действия токена (24 часа)
        if (time() - $tokenData['created_at'] > 86400) {
            unset($_SESSION[self::$sessionKey][$token]);
            return false;
        }
        
        // Проверяем, не был ли токен уже использован
        if ($tokenData['used']) {
            return false;
        }
        
        // Помечаем токен как использованный
        $_SESSION[self::$sessionKey][$token]['used'] = true;
        
        return true;
    }
    
    /**
     * Получает текущий токен или генерирует новый
     */
    public static function getToken() {
        if (!isset($_SESSION[self::$sessionKey])) {
            return self::generateToken();
        }
        
        // Ищем неиспользованный токен
        foreach ($_SESSION[self::$sessionKey] as $token => $data) {
            if (!$data['used'] && (time() - $data['created_at']) <= 86400) {
                return $token;
            }
        }
        
        // Если нет валидного токена, генерируем новый
        return self::generateToken();
    }
    
    /**
     * Создает скрытое поле с CSRF токеном
     */
    public static function getHiddenField() {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Проверяет CSRF токен в POST запросе
     */
    public static function verifyPostToken() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true; // CSRF защита нужна только для POST запросов
        }
        
        $token = $_POST[self::$tokenName] ?? '';
        return self::verifyToken($token);
    }
    
    /**
     * Проверяет CSRF токен в JSON запросе
     */
    public static function verifyJsonToken() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }
        
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        $token = $data[self::$tokenName] ?? '';
        return self::verifyToken($token);
    }
    
    /**
     * Очищает старые токены
     */
    public static function cleanupOldTokens() {
        if (!isset($_SESSION[self::$sessionKey])) {
            return;
        }
        
        $currentTime = time();
        
        foreach ($_SESSION[self::$sessionKey] as $token => $data) {
            // Удаляем токены старше 24 часов
            if ($currentTime - $data['created_at'] > 86400) {
                unset($_SESSION[self::$sessionKey][$token]);
            }
        }
    }
    
    /**
     * Получает JavaScript для автоматической вставки токена
     */
    public static function getJavaScript() {
        $token = self::getToken();
        
        return "
        <script>
        // Автоматически добавляем CSRF токен ко всем формам
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            const token = '" . $token . "';
            
            forms.forEach(function(form) {
                // Проверяем, есть ли уже поле с токеном
                if (!form.querySelector('input[name=\"" . self::$tokenName . "\"]')) {
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = '" . self::$tokenName . "';
                    hiddenField.value = token;
                    form.appendChild(hiddenField);
                }
            });
            
            // Добавляем токен к AJAX запросам
            const originalFetch = window.fetch;
            window.fetch = function(url, options = {}) {
                if (options.method && options.method.toUpperCase() === 'POST') {
                    if (!options.headers) {
                        options.headers = {};
                    }
                    
                    if (options.headers['Content-Type'] === 'application/json') {
                        let body = JSON.parse(options.body || '{}');
                        body['" . self::$tokenName . "'] = token;
                        options.body = JSON.stringify(body);
                    } else {
                        if (!options.body) {
                            options.body = new FormData();
                        }
                        options.body.append('" . self::$tokenName . "', token);
                    }
                }
                
                return originalFetch(url, options);
            };
        });
        </script>";
    }
    
    /**
     * Проверяет и выбрасывает исключение при неверном токене
     */
    public static function requireValidToken() {
        if (!self::verifyPostToken()) {
            throw new Exception('CSRF token validation failed');
        }
    }
    
    /**
     * Создает токен для API запросов
     */
    public static function generateApiToken() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['api_tokens'][$token] = [
            'created_at' => time(),
            'user_id' => $_SESSION['admin_user_id'] ?? null
        ];
        
        return $token;
    }
    
    /**
     * Проверяет API токен
     */
    public static function verifyApiToken($token) {
        if (empty($token)) {
            return false;
        }
        
        if (!isset($_SESSION['api_tokens'][$token])) {
            return false;
        }
        
        $tokenData = $_SESSION['api_tokens'][$token];
        
        // Проверяем срок действия токена (1 час для API)
        if (time() - $tokenData['created_at'] > 3600) {
            unset($_SESSION['api_tokens'][$token]);
            return false;
        }
        
        return true;
    }
} 