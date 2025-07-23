<?php

mb_internal_encoding("UTF-8");

define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

// Проверяем, является ли это запросом к статическому файлу
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Убираем query string из URI
$requestUri = strtok($requestUri, '?');

// Проверяем, является ли это запросом к статическому файлу
$staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'bmp', 'ico', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', 'ttf', 'woff', 'woff2', 'eot'];

$isStaticFile = false;
foreach ($staticExtensions as $ext) {
    if (preg_match('/\.' . $ext . '$/i', $requestUri)) {
        $isStaticFile = true;
        break;
    }
}

// Если это запрос к статическому файлу, отдаем его напрямую
if ($isStaticFile) {
    $filePath = $_SERVER['DOCUMENT_ROOT'] . $requestUri;
    
    // Проверяем, существует ли файл
    if (file_exists($filePath) && is_file($filePath)) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        // Устанавливаем правильные заголовки для разных типов файлов
        switch ($extension) {
            case 'css':
                header('Content-Type: text/css; charset=utf-8');
                // Отключаем кеширование для CSS файлов в режиме разработки
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');
                break;
            case 'js':
                header('Content-Type: application/javascript; charset=utf-8');
                // Отключаем кеширование для JS файлов в режиме разработки
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');
                break;
            case 'png':
                header('Content-Type: image/png');
                header('Cache-Control: public, max-age=31536000');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                header('Cache-Control: public, max-age=31536000');
                break;
            case 'gif':
                header('Content-Type: image/gif');
                header('Cache-Control: public, max-age=31536000');
                break;
            case 'webp':
                header('Content-Type: image/webp');
                header('Cache-Control: public, max-age=31536000');
                break;
            case 'bmp':
                header('Content-Type: image/bmp');
                header('Cache-Control: public, max-age=31536000');
                break;
            case 'ico':
                header('Content-Type: image/x-icon');
                header('Cache-Control: public, max-age=31536000');
                break;
            case 'ttf':
                header('Content-Type: font/ttf');
                header('Cache-Control: public, max-age=31536000');
                break;
            case 'woff':
                header('Content-Type: font/woff');
                header('Cache-Control: public, max-age=31536000');
                break;
            case 'woff2':
                header('Content-Type: font/woff2');
                header('Cache-Control: public, max-age=31536000');
                break;
            default:
                header('Content-Type: application/octet-stream');
        }
        
        // Отдаем файл
        readfile($filePath);
        exit;
    } else {
        // Файл не найден
        http_response_code(404);
        echo 'File not found: ' . $requestUri;
        exit;
    }
}

// Загружаем конфигурацию
require_once 'application/config.php';

// Загружаем основные классы
require_once(ENGINE_DIR . 'main/db.php');
require_once(ENGINE_DIR . 'BaseController.php');
require_once(ENGINE_DIR . 'Router.php');
require_once(ENGINE_DIR . 'main/admin_router.php');

// Запускаем сессию
session_start();

// Получаем URI
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Проверяем, является ли это админским запросом
if (strpos($uri, '/admin') === 0) {
    // Используем админский роутер
    $adminRouter = new AdminRouter();
    $response = $adminRouter->route($uri, $method);
} else {
    // Используем обычный роутер для основного сайта
    $router = new Router();
    
    // Получаем URL из параметра action
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    // Диспетчеризируем запрос
    $response = $router->dispatch($action);
}

// Выводим результат
echo $response;
?>
