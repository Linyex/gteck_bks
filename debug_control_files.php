<?php

// Включаем отображение всех ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "🔍 Отладка ControlFilesController...\n\n";

// Определяем константы
define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

echo "✅ Константы определены\n";
echo "   ENGINE_DIR: " . ENGINE_DIR . "\n";
echo "   APPLICATION_DIR: " . APPLICATION_DIR . "\n\n";

// Инициализация сессии
session_start();

// Устанавливаем сессию админа
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_user_id'] = 1;

echo "✅ Сессия инициализирована\n\n";

try {
    // Тестируем подключение к базе данных
    echo "🔌 Тестируем подключение к БД...\n";
    require_once ENGINE_DIR . 'main/db.php';
    echo "✅ db.php подключен\n";
    
    // Тестируем простой запрос
    $test = Database::fetchAll("SELECT 1 as test");
    echo "✅ База данных отвечает: " . json_encode($test) . "\n\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка БД: " . $e->getMessage() . "\n\n";
}

try {
    // Тестируем SQL запрос из контроллера
    echo "📊 Тестируем SQL запрос контроллера...\n";
    
    $sql = "
        SELECT f.*, GROUP_CONCAT(g.groupname) as groups 
        FROM dkrfiles f 
        LEFT JOIN dkrjointable j ON f.id = j.fileid 
        LEFT JOIN dkrgroups g ON j.groupid = g.id_group 
        GROUP BY f.id 
        ORDER BY f.upload_date DESC
    ";
    
    echo "SQL: " . preg_replace('/\s+/', ' ', trim($sql)) . "\n";
    
    $files = Database::fetchAll($sql);
    echo "✅ SQL запрос выполнен успешно\n";
    echo "📄 Найдено файлов: " . count($files) . "\n\n";
    
    if (!empty($files)) {
        echo "📋 Первый файл:\n";
        print_r($files[0]);
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка SQL: " . $e->getMessage() . "\n\n";
}

try {
    // Тестируем BaseController
    echo "🏗️ Тестируем BaseController...\n";
    require_once ENGINE_DIR . 'BaseController.php';
    echo "✅ BaseController подключен\n";
    
    // Тестируем BaseAdminController
    echo "🔑 Тестируем BaseAdminController...\n";
    require_once APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';
    echo "✅ BaseAdminController подключен\n";
    
    // Создаем экземпляр BaseAdminController для проверки
    $baseAdmin = new BaseAdminController();
    echo "✅ BaseAdminController создан\n\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка контроллера: " . $e->getMessage() . "\n";
    echo "📍 Файл: " . $e->getFile() . "\n";
    echo "📍 Строка: " . $e->getLine() . "\n\n";
}

try {
    // Тестируем представление
    echo "🎨 Тестируем представление...\n";
    $viewFile = APPLICATION_DIR . 'views/admin/control-files/index.php';
    
    if (file_exists($viewFile)) {
        echo "✅ Файл представления существует: $viewFile\n";
        
        // Проверяем синтаксис PHP в представлении
        $output = shell_exec("php -l \"$viewFile\" 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "✅ Синтаксис представления корректен\n";
        } else {
            echo "❌ Ошибка синтаксиса в представлении:\n$output\n";
        }
    } else {
        echo "❌ Файл представления не найден: $viewFile\n";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка представления: " . $e->getMessage() . "\n\n";
}

try {
    // Финальный тест - создание контроллера
    echo "🎯 Финальный тест - создание ControlFilesController...\n";
    require_once APPLICATION_DIR . 'controllers/admin/ControlFilesController.php';
    echo "✅ ControlFilesController подключен\n";
    
    $controller = new ControlFilesController();
    echo "✅ ControlFilesController создан\n";
    
    // Пробуем вызвать метод index
    echo "🏃 Пробуем выполнить метод index()...\n";
    ob_start();
    $controller->index();
    $output = ob_get_clean();
    
    echo "✅ Метод index() выполнен успешно!\n";
    echo "📏 Длина вывода: " . strlen($output) . " символов\n";
    
    // Ищем признаки ошибок в выводе
    $errors = [];
    if (strpos($output, 'Fatal error') !== false) $errors[] = 'Fatal error';
    if (strpos($output, 'Warning') !== false) $errors[] = 'Warning';
    if (strpos($output, 'Notice') !== false) $errors[] = 'Notice';
    if (strpos($output, 'Parse error') !== false) $errors[] = 'Parse error';
    
    if (!empty($errors)) {
        echo "⚠️ Обнаружены ошибки в выводе: " . implode(', ', $errors) . "\n";
        echo "📄 Первые 1000 символов вывода:\n";
        echo substr($output, 0, 1000) . "\n";
    } else {
        echo "✅ Вывод выглядит нормально\n";
    }
    
} catch (Error $e) {
    echo "❌ Фатальная ошибка: " . $e->getMessage() . "\n";
    echo "📍 Файл: " . $e->getFile() . "\n";
    echo "📍 Строка: " . $e->getLine() . "\n";
    echo "📄 Трассировка:\n" . $e->getTraceAsString() . "\n";
} catch (Exception $e) {
    echo "❌ Исключение: " . $e->getMessage() . "\n";
    echo "📍 Файл: " . $e->getFile() . "\n";
    echo "📍 Строка: " . $e->getLine() . "\n";
    echo "📄 Трассировка:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🏁 ОТЛАДКА ЗАВЕРШЕНА\n";
?> 