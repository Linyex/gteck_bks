<?php

// Определяем константы
define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

// Инициализация сессии для тестирования
session_start();

// Добавляем пользователя с правами для тестирования
$_SESSION['user_id'] = 1;
$_SESSION['access_level'] = 10;

try {
    // Подключаем контроллер
    require_once 'application/controllers/admin/ControlFilesController.php';
    
    echo "✅ Контроллер подключен успешно\n";
    
    // Создаем экземпляр контроллера
    $controller = new ControlFilesController();
    
    echo "✅ Контроллер создан успешно\n";
    
    // Тестируем метод index
    ob_start();
    $controller->index();
    $output = ob_get_clean();
    
    echo "✅ Метод index выполнен успешно\n";
    echo "📄 Длина вывода: " . strlen($output) . " символов\n";
    
    if (strpos($output, 'error') !== false || strpos($output, 'Error') !== false) {
        echo "⚠️  Обнаружены ошибки в выводе:\n";
        echo substr($output, 0, 500) . "...\n";
    } else {
        echo "✅ Вывод выглядит корректно\n";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    echo "📍 Файл: " . $e->getFile() . "\n";
    echo "📍 Строка: " . $e->getLine() . "\n";
    echo "📄 Трассировка:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "❌ Фатальная ошибка: " . $e->getMessage() . "\n";
    echo "📍 Файл: " . $e->getFile() . "\n";
    echo "📍 Строка: " . $e->getLine() . "\n";
    echo "📄 Трассировка:\n" . $e->getTraceAsString() . "\n";
}
?> 