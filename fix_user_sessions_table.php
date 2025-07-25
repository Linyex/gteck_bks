<?php
require_once 'engine/main/db.php';

echo "=== Исправление таблицы user_sessions ===\n";

try {
    // Проверяем текущую структуру
    $fields = Database::fetchAll("DESCRIBE user_sessions");
    echo "Текущие поля таблицы user_sessions:\n";
    foreach ($fields as $field) {
        echo "- " . $field['Field'] . " (" . $field['Type'] . ")\n";
    }
    
    // Проверяем, есть ли поле session_id
    $hasSessionId = false;
    foreach ($fields as $field) {
        if ($field['Field'] === 'session_id') {
            $hasSessionId = true;
            break;
        }
    }
    
    if ($hasSessionId) {
        echo "\nУдаляем лишнее поле session_id...\n";
        Database::execute("ALTER TABLE user_sessions DROP COLUMN session_id");
        echo "Поле session_id удалено!\n";
    }
    
    // Проверяем, есть ли поле session_token
    $hasSessionToken = false;
    foreach ($fields as $field) {
        if ($field['Field'] === 'session_token') {
            $hasSessionToken = true;
            break;
        }
    }
    
    if (!$hasSessionToken) {
        echo "\nДобавляем поле session_token...\n";
        Database::execute("ALTER TABLE user_sessions ADD COLUMN session_token VARCHAR(255) NOT NULL AFTER user_id");
        echo "Поле session_token добавлено!\n";
    }
    
    // Проверяем поле is_active
    $hasIsActive = false;
    foreach ($fields as $field) {
        if ($field['Field'] === 'is_active') {
            $hasIsActive = true;
            break;
        }
    }
    
    if (!$hasIsActive) {
        echo "\nДобавляем поле is_active...\n";
        Database::execute("ALTER TABLE user_sessions ADD COLUMN is_active BOOLEAN DEFAULT TRUE AFTER user_agent");
        echo "Поле is_active добавлено!\n";
    }
    
    // Проверяем поле last_activity
    $hasLastActivity = false;
    foreach ($fields as $field) {
        if ($field['Field'] === 'last_activity') {
            $hasLastActivity = true;
            break;
        }
    }
    
    if (!$hasLastActivity) {
        echo "\nДобавляем поле last_activity...\n";
        Database::execute("ALTER TABLE user_sessions ADD COLUMN last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at");
        echo "Поле last_activity добавлено!\n";
    }
    
    // Проверяем обновленную структуру
    $updatedFields = Database::fetchAll("DESCRIBE user_sessions");
    echo "\nОбновленная структура таблицы user_sessions:\n";
    foreach ($updatedFields as $field) {
        echo "- " . $field['Field'] . " (" . $field['Type'] . ")\n";
    }
    
    // Тестируем создание записи
    echo "\nТестируем создание записи...\n";
    
    $userId = 1;
    $sessionToken = 'test_session_' . time();
    $ip = '127.0.0.1';
    $userAgent = 'Test User Agent';
    
    try {
        Database::execute(
            "INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, login_time, created_at, last_activity) VALUES (?, ?, ?, ?, NOW(), NOW(), NOW())",
            [$userId, $sessionToken, $ip, $userAgent]
        );
        echo "✅ Тестовая запись создана успешно!\n";
        
        // Проверяем созданную запись
        $testSession = Database::fetchOne(
            "SELECT * FROM user_sessions WHERE session_token = ?",
            [$sessionToken]
        );
        
        if ($testSession) {
            echo "✅ Запись найдена в базе!\n";
            echo "- ID: " . $testSession['id'] . "\n";
            echo "- Пользователь: " . $testSession['user_id'] . "\n";
            echo "- Активна: " . ($testSession['is_active'] ? 'Да' : 'Нет') . "\n";
        }
        
        // Удаляем тестовую запись
        Database::execute("DELETE FROM user_sessions WHERE session_token = ?", [$sessionToken]);
        echo "Тестовая запись удалена.\n";
        
    } catch (Exception $e) {
        echo "❌ Ошибка создания тестовой записи: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}

echo "\n=== Исправление завершено ===\n"; 