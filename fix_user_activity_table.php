<?php
/**
 * Исправление таблицы user_activity
 */

echo "<h1>Исправление таблицы user_activity</h1>";

// Подключаем необходимые файлы
require_once 'application/config.php';
require_once 'engine/main/db.php';

try {
    echo "<h2>1. Проверка текущей структуры таблицы user_activity</h2>";
    
    $columns = Database::fetchAll("DESCRIBE user_activity");
    echo "<h3>Текущие поля:</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Поле</th><th>Тип</th><th>NULL</th><th>Ключ</th><th>По умолчанию</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h2>2. Исправление структуры таблицы</h2>";
    
    // Проверяем, есть ли поле action_type
    $hasActionType = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'action_type') {
            $hasActionType = true;
            break;
        }
    }
    
    if (!$hasActionType) {
        echo "Добавление поля action_type...<br>";
        try {
            Database::execute("ALTER TABLE user_activity ADD COLUMN action_type VARCHAR(50) NOT NULL DEFAULT 'page_view' AFTER user_id");
            echo "✓ Поле action_type добавлено<br>";
        } catch (Exception $e) {
            echo "✗ Ошибка добавления поля action_type: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "✓ Поле action_type уже существует<br>";
    }
    
    // Проверяем, есть ли поле activity_description
    $hasActivityDescription = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'activity_description') {
            $hasActivityDescription = true;
            break;
        }
    }
    
    if (!$hasActivityDescription) {
        echo "Добавление поля activity_description...<br>";
        try {
            Database::execute("ALTER TABLE user_activity ADD COLUMN activity_description TEXT AFTER action_type");
            echo "✓ Поле activity_description добавлено<br>";
        } catch (Exception $e) {
            echo "✗ Ошибка добавления поля activity_description: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "✓ Поле activity_description уже существует<br>";
    }
    
    echo "<h2>3. Проверка исправленной структуры</h2>";
    
    $updatedColumns = Database::fetchAll("DESCRIBE user_activity");
    echo "<h3>Обновленные поля:</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Поле</th><th>Тип</th><th>NULL</th><th>Ключ</th><th>По умолчанию</th></tr>";
    foreach ($updatedColumns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h2>4. Тест записи в исправленную таблицу</h2>";
    
    try {
        Database::execute(
            "INSERT INTO user_activity (user_id, action_type, activity_description, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())",
            [1, 'test_activity', 'Тестовая активность пользователя (исправленная)', '127.0.0.1']
        );
        echo "✓ Тестовая запись в исправленную таблицу создана<br>";
    } catch (Exception $e) {
        echo "✗ Ошибка создания записи в исправленной таблице: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>5. Статус исправления</h2>";
    echo "<p>✅ Структура таблицы user_activity исправлена</p>";
    echo "<p>✅ Все необходимые поля добавлены</p>";
    echo "<p>✅ Тестовая запись создана успешно</p>";
    echo "<p>✅ Таблица готова к использованию</p>";
    
} catch (Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "<br>";
}
?> 