<?php
require_once 'engine/main/db.php';

echo "=== Создание простой таблицы сессий ===\n";

try {
    // Удаляем старую таблицу если она существует
    Database::execute("DROP TABLE IF EXISTS user_sessions");
    echo "1. Старая таблица удалена\n";
    
    // Создаем новую простую таблицу
    $sql = "CREATE TABLE user_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        session_token VARCHAR(255) NOT NULL,
        ip_address VARCHAR(45),
        user_agent TEXT,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_session_token (session_token),
        INDEX idx_is_active (is_active),
        INDEX idx_last_activity (last_activity)
    )";
    
    Database::execute($sql);
    echo "2. Новая таблица user_sessions создана\n";
    
    // Проверяем структуру
    $fields = Database::fetchAll("DESCRIBE user_sessions");
    echo "3. Структура новой таблицы:\n";
    foreach ($fields as $field) {
        echo "   - " . $field['Field'] . " (" . $field['Type'] . ")\n";
    }
    
    // Тестируем создание записи
    echo "4. Тестируем создание записи...\n";
    
    $userId = 1;
    $sessionToken = 'test_session_' . time();
    $ip = '127.0.0.1';
    $userAgent = 'Test User Agent';
    
    Database::execute(
        "INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent) VALUES (?, ?, ?, ?)",
        [$userId, $sessionToken, $ip, $userAgent]
    );
    echo "   ✅ Тестовая запись создана!\n";
    
    // Проверяем запись
    $testSession = Database::fetchOne(
        "SELECT us.*, u.user_fio, u.user_login 
         FROM user_sessions us 
         LEFT JOIN users u ON us.user_id = u.user_id 
         WHERE us.session_token = ?",
        [$sessionToken]
    );
    
    if ($testSession) {
        echo "   ✅ Запись найдена в базе!\n";
        echo "   - Пользователь: " . ($testSession['user_fio'] ?? 'Неизвестно') . "\n";
        echo "   - IP: " . $testSession['ip_address'] . "\n";
        echo "   - Активна: " . ($testSession['is_active'] ? 'Да' : 'Нет') . "\n";
    }
    
    // Удаляем тестовую запись
    Database::execute("DELETE FROM user_sessions WHERE session_token = ?", [$sessionToken]);
    echo "   Тестовая запись удалена.\n";
    
    echo "\n5. Таблица готова к использованию!\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}

echo "\n=== Создание завершено ===\n"; 