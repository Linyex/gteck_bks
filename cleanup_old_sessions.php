<?php
require_once 'engine/main/db.php';

echo "=== Очистка старых сессий ===\n";

try {
    // Проверяем количество активных сессий до очистки
    $activeSessionsBefore = Database::fetchAll("SELECT COUNT(*) as count FROM user_sessions WHERE is_active = 1");
    echo "1. Активных сессий до очистки: " . $activeSessionsBefore[0]['count'] . "\n";
    
    // Показываем все активные сессии
    $sessions = Database::fetchAll(
        "SELECT us.*, u.user_fio, u.user_login 
         FROM user_sessions us 
         LEFT JOIN users u ON us.user_id = u.user_id 
         WHERE us.is_active = 1 
         ORDER BY us.last_activity DESC"
    );
    
    echo "2. Детали активных сессий:\n";
    foreach ($sessions as $session) {
        echo "   - Пользователь: " . ($session['user_fio'] ?? 'Неизвестно') . "\n";
        echo "     IP: " . $session['ip_address'] . "\n";
        echo "     Последняя активность: " . $session['last_activity'] . "\n";
        echo "     ---\n";
    }
    
    // Деактивируем сессии старше 24 часов
    echo "3. Деактивируем сессии старше 24 часов...\n";
    $oldSessions = Database::fetchAll(
        "SELECT COUNT(*) as count FROM user_sessions 
         WHERE is_active = 1 
         AND last_activity < DATE_SUB(NOW(), INTERVAL 24 HOUR)"
    );
    
    echo "   - Найдено старых сессий: " . $oldSessions[0]['count'] . "\n";
    
    if ($oldSessions[0]['count'] > 0) {
        Database::execute(
            "UPDATE user_sessions 
             SET is_active = 0 
             WHERE is_active = 1 
             AND last_activity < DATE_SUB(NOW(), INTERVAL 24 HOUR)"
        );
        echo "   ✅ Старые сессии деактивированы\n";
    }
    
    // Деактивируем сессии старше 1 часа (для тестирования)
    echo "4. Деактивируем сессии старше 1 часа (для тестирования)...\n";
    $veryOldSessions = Database::fetchAll(
        "SELECT COUNT(*) as count FROM user_sessions 
         WHERE is_active = 1 
         AND last_activity < DATE_SUB(NOW(), INTERVAL 1 HOUR)"
    );
    
    echo "   - Найдено сессий старше 1 часа: " . $veryOldSessions[0]['count'] . "\n";
    
    if ($veryOldSessions[0]['count'] > 0) {
        Database::execute(
            "UPDATE user_sessions 
             SET is_active = 0 
             WHERE is_active = 1 
             AND last_activity < DATE_SUB(NOW(), INTERVAL 1 HOUR)"
        );
        echo "   ✅ Сессии старше 1 часа деактивированы\n";
    }
    
    // Проверяем результат
    $activeSessionsAfter = Database::fetchAll("SELECT COUNT(*) as count FROM user_sessions WHERE is_active = 1");
    echo "5. Активных сессий после очистки: " . $activeSessionsAfter[0]['count'] . "\n";
    
    // Показываем оставшиеся активные сессии
    $remainingSessions = Database::fetchAll(
        "SELECT us.*, u.user_fio, u.user_login 
         FROM user_sessions us 
         LEFT JOIN users u ON us.user_id = u.user_id 
         WHERE us.is_active = 1 
         ORDER BY us.last_activity DESC"
    );
    
    if (count($remainingSessions) > 0) {
        echo "6. Оставшиеся активные сессии:\n";
        foreach ($remainingSessions as $session) {
            echo "   - Пользователь: " . ($session['user_fio'] ?? 'Неизвестно') . "\n";
            echo "     IP: " . $session['ip_address'] . "\n";
            echo "     Последняя активность: " . $session['last_activity'] . "\n";
            echo "     ---\n";
        }
    } else {
        echo "6. Нет активных сессий\n";
    }
    
    echo "\n=== Очистка завершена ===\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
} 