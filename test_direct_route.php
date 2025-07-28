<?php

// Симулируем HTTP запрос GET /admin/control-files

// Устанавливаем переменные сервера как при реальном запросе
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/admin/control-files';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';

// Включаем отображение ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "🌐 Симуляция HTTP запроса GET /admin/control-files\n";
echo "=================================================\n\n";

// Определяем константы как в index.php
define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

// Стартуем сессию и устанавливаем авторизацию
session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_user_id'] = 1;

echo "✅ Сессия и авторизация настроены\n";
echo "✅ Константы определены\n\n";

try {
    // Загружаем роутер как в index.php
    require_once ENGINE_DIR . 'main/admin_router.php';
    echo "✅ AdminRouter загружен\n";
    
    // Создаем роутер
    $router = new AdminRouter();
    echo "✅ AdminRouter создан\n";
    
    // Выполняем роутинг
    echo "🚀 Выполняем роутинг для '/admin/control-files'...\n";
    
    // Захватываем вывод
    ob_start();
    $result = $router->route('/admin/control-files', 'GET'); // Добавлены параметры!
    if ($result) {
        echo $result; // Выводим результат если он есть
    }
    $output = ob_get_clean();
    
    echo "✅ Роутинг выполнен успешно!\n";
    echo "📏 Размер вывода: " . strlen($output) . " символов\n\n";
    
    // Анализируем вывод на предмет ошибок
    $hasErrors = false;
    $errorTypes = [];
    
    if (strpos($output, 'Fatal error') !== false) {
        $errorTypes[] = 'Fatal error';
        $hasErrors = true;
    }
    if (strpos($output, 'Warning') !== false) {
        $errorTypes[] = 'Warning';
        $hasErrors = true;
    }
    if (strpos($output, 'Notice') !== false) {
        $errorTypes[] = 'Notice';
        $hasErrors = true;
    }
    if (strpos($output, 'Parse error') !== false) {
        $errorTypes[] = 'Parse error';
        $hasErrors = true;
    }
    if (strpos($output, 'Exception') !== false) {
        $errorTypes[] = 'Exception';
        $hasErrors = true;
    }
    
    if ($hasErrors) {
        echo "⚠️ ОБНАРУЖЕНЫ ОШИБКИ: " . implode(', ', $errorTypes) . "\n";
        echo "📄 Вывод с ошибками:\n";
        echo str_repeat("-", 50) . "\n";
        echo $output;
        echo "\n" . str_repeat("-", 50) . "\n";
    } else {
        echo "✅ Ошибок не обнаружено\n";
        echo "📝 Первые 200 символов вывода:\n";
        echo str_repeat("-", 50) . "\n";
        echo substr($output, 0, 200) . "...\n";
        echo str_repeat("-", 50) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ИСКЛЮЧЕНИЕ: " . $e->getMessage() . "\n";
    echo "📍 Файл: " . $e->getFile() . "\n";
    echo "📍 Строка: " . $e->getLine() . "\n";
    echo "📄 Трассировка:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "❌ ФАТАЛЬНАЯ ОШИБКА: " . $e->getMessage() . "\n";
    echo "📍 Файл: " . $e->getFile() . "\n";
    echo "📍 Строка: " . $e->getLine() . "\n";
    echo "📄 Трассировка:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🏁 ТЕСТ ЗАВЕРШЕН\n";
?> 