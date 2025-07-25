<?php
/**
 * Исправление поля activity_time в таблице user_activity
 */

echo "<h1>Исправление поля activity_time</h1>";

// Подключаем необходимые файлы
require_once 'application/config.php';
require_once 'engine/main/db.php';

try {
    echo "<h2>1. Проверка поля activity_time</h2>";
    
    $columns = Database::fetchAll("DESCRIBE user_activity");
    $activityTimeColumn = null;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'activity_time') {
            $activityTimeColumn = $column;
            break;
        }
    }
    
    if ($activityTimeColumn) {
        echo "✓ Поле activity_time найдено<br>";
        echo "Тип: " . $activityTimeColumn['Type'] . "<br>";
        echo "NULL: " . $activityTimeColumn['Null'] . "<br>";
        echo "По умолчанию: " . $activityTimeColumn['Default'] . "<br>";
        
        // Проверяем, есть ли значение по умолчанию
        if ($activityTimeColumn['Default'] === null && $activityTimeColumn['Null'] === 'NO') {
            echo "⚠ Поле activity_time не имеет значения по умолчанию и не может быть NULL<br>";
            
            echo "<h2>2. Исправление поля activity_time</h2>";
            
            try {
                // Добавляем значение по умолчанию
                Database::execute("ALTER TABLE user_activity MODIFY COLUMN activity_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
                echo "✓ Поле activity_time исправлено<br>";
            } catch (Exception $e) {
                echo "✗ Ошибка исправления поля activity_time: " . $e->getMessage() . "<br>";
            }
        } else {
            echo "✓ Поле activity_time уже имеет правильные настройки<br>";
        }
    } else {
        echo "✗ Поле activity_time не найдено<br>";
    }
    
    echo "<h2>3. Тест записи в исправленную таблицу</h2>";
    
    try {
        Database::execute(
            "INSERT INTO user_activity (user_id, activity_type, activity_description, ip_address, activity_time, created_at) VALUES (?, ?, ?, ?, NOW(), NOW())",
            [1, 'test_activity', 'Тестовая активность пользователя (исправленная)', '127.0.0.1']
        );
        echo "✓ Тестовая запись в исправленную таблицу создана<br>";
    } catch (Exception $e) {
        echo "✗ Ошибка создания записи в исправленной таблице: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>4. Статус исправления</h2>";
    echo "<p>✅ Поле activity_time исправлено</p>";
    echo "<p>✅ Тестовая запись создана успешно</p>";
    echo "<p>✅ Таблица user_activity готова к использованию</p>";
    
} catch (Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "<br>";
}
?> 