<?php

/**
 * Базовый API контроллер
 * Обеспечивает общую функциональность для всех API контроллеров
 */
class BaseApiController extends BaseController {
    
    protected $response = [];
    protected $statusCode = 200;
    protected $headers = [];
    
    public function __construct() {
        parent::__construct();
        
        // Устанавливаем заголовки для API
        $this->setHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With'
        ]);
        
        // Обрабатываем preflight запросы
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->sendResponse([], 200);
            exit;
        }
    }
    
    /**
     * Устанавливает заголовки ответа
     */
    protected function setHeaders($headers) {
        $this->headers = array_merge($this->headers, $headers);
    }
    
    /**
     * Устанавливает код статуса
     */
    protected function setStatusCode($code) {
        $this->statusCode = $code;
    }
    
    /**
     * Добавляет данные в ответ
     */
    protected function addData($key, $value) {
        $this->response[$key] = $value;
    }
    
    /**
     * Устанавливает сообщение об ошибке
     */
    protected function setError($message, $code = 400) {
        $this->setStatusCode($code);
        $this->response = [
            'success' => false,
            'error' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Устанавливает успешный ответ
     */
    protected function setSuccess($data = null, $message = 'Success') {
        $this->setStatusCode(200);
        $this->response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Отправляет JSON ответ
     */
    protected function sendResponse($data = null, $statusCode = null) {
        if ($data !== null) {
            $this->response = $data;
        }
        
        if ($statusCode !== null) {
            $this->setStatusCode($statusCode);
        }
        
        // Устанавливаем HTTP код статуса
        http_response_code($this->statusCode);
        
        // Отправляем заголовки
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        
        // Отправляем JSON
        echo json_encode($this->response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Отправляет ошибку
     */
    protected function sendError($message, $code = 400) {
        $this->setError($message, $code);
        $this->sendResponse();
    }
    
    /**
     * Отправляет успешный ответ
     */
    protected function sendSuccess($data = null, $message = 'Success') {
        $this->setSuccess($data, $message);
        $this->sendResponse();
    }
    
    /**
     * Проверяет авторизацию
     */
    protected function requireAuth() {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            $this->sendError('Unauthorized', 401);
        }
    }
    
    /**
     * Проверяет уровень доступа
     */
    protected function requireAccessLevel($minLevel = 1) {
        $this->requireAuth();
        
        if (!isset($_SESSION['admin_access_level']) || $_SESSION['admin_access_level'] < $minLevel) {
            $this->sendError('Insufficient permissions', 403);
        }
    }
    
    /**
     * Получает данные из POST запроса
     */
    protected function getPostData() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError('Invalid JSON data');
        }
        
        return $data;
    }
    
    /**
     * Валидирует данные
     */
    protected function validateData($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            if (!isset($data[$field]) || empty($data[$field])) {
                if (strpos($rule, 'required') !== false) {
                    $errors[$field] = "Field '$field' is required";
                }
                continue;
            }
            
            $value = $data[$field];
            
            // Проверяем тип данных
            if (strpos($rule, 'string') !== false && !is_string($value)) {
                $errors[$field] = "Field '$field' must be a string";
            }
            
            if (strpos($rule, 'int') !== false && !is_numeric($value)) {
                $errors[$field] = "Field '$field' must be a number";
            }
            
            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "Field '$field' must be a valid email";
            }
            
            // Проверяем длину
            if (preg_match('/min:(\d+)/', $rule, $matches)) {
                $min = (int)$matches[1];
                if (strlen($value) < $min) {
                    $errors[$field] = "Field '$field' must be at least $min characters";
                }
            }
            
            if (preg_match('/max:(\d+)/', $rule, $matches)) {
                $max = (int)$matches[1];
                if (strlen($value) > $max) {
                    $errors[$field] = "Field '$field' must be no more than $max characters";
                }
            }
        }
        
        if (!empty($errors)) {
            $this->sendError('Validation failed', 422);
            $this->addData('errors', $errors);
            $this->sendResponse();
        }
        
        return true;
    }
    
    /**
     * Пагинация
     */
    protected function paginate($query, $params = [], $page = 1, $perPage = 20) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем общее количество записей
            $countQuery = preg_replace('/SELECT .* FROM/', 'SELECT COUNT(*) as count FROM', $query);
            $countQuery = preg_replace('/ORDER BY .*/', '', $countQuery);
            
            $total = Database::fetchOne($countQuery, $params);
            $total = $total['count'] ?? 0;
            
            // Вычисляем смещение
            $offset = ($page - 1) * $perPage;
            
            // Добавляем LIMIT и OFFSET к запросу
            $query .= " LIMIT $perPage OFFSET $offset";
            
            // Получаем данные
            $data = Database::fetchAll($query, $params);
            
            // Вычисляем метаданные пагинации
            $totalPages = ceil($total / $perPage);
            
            return [
                'data' => $data,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                    'has_next' => $page < $totalPages,
                    'has_prev' => $page > 1
                ]
            ];
        } catch (Exception $e) {
            $this->sendError('Database error: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Логирование API запросов
     */
    protected function logApiRequest($action, $data = null) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $userId = $_SESSION['admin_user_id'] ?? null;
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $method = $_SERVER['REQUEST_METHOD'] ?? '';
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            
            Database::execute(
                "INSERT INTO api_logs (user_id, action, ip_address, user_agent, method, uri, request_data, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [$userId, $action, $ip, $userAgent, $method, $uri, $data ? json_encode($data) : null]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки логирования
        }
    }
    
    /**
     * Обработка ошибок
     */
    protected function handleException($e) {
        error_log("API Error: " . $e->getMessage());
        
        if ($e instanceof Exception) {
            $this->sendError($e->getMessage(), 500);
        } else {
            $this->sendError('Internal server error', 500);
        }
    }
} 