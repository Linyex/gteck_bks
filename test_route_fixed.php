<?php

// Исправленный тест роутинга
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/admin/control-files';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "🌐 Исправленный тест роутинга /admin/control-files\n";
echo "================================================\n\n";

define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_user_id'] = 1;

try {
    require_once ENGINE_DIR . 'main/admin_router.php';
    
    $router = new AdminRouter();
    echo "✅ AdminRouter создан\n";
    
    echo "🚀 Выполняем route('/admin/control-files', 'GET')...\n";
    
    ob_start();
    $result = $router->route('/admin/control-files', 'GET');
    $output = ob_get_clean();
    
    echo "✅ Роутинг завершен!\n";
    echo "📏 Размер вывода: " . strlen($output) . " символов\n";
    
    if ($result) {
        echo "📄 Результат роутера: " . strlen($result) . " символов\n";
    }
    
    // Проверяем на ошибки
    if (strpos($output, 'Fatal') !== false || strpos($output, 'Error') !== false) {
        echo "❌ ОШИБКИ В ВЫВОДЕ:\n";
        echo $output . "\n";
    } else {
        echo "✅ Ошибок не обнаружено\n";
        echo "📝 Первые 300 символов:\n";
        echo substr($output, 0, 300) . "...\n";
    }
    
} catch (Exception $e) {
    echo "❌ Исключение: " . $e->getMessage() . "\n";
    echo "📍 " . $e->getFile() . ":" . $e->getLine() . "\n";
} catch (Error $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    echo "📍 " . $e->getFile() . ":" . $e->getLine() . "\n";
}
?> 