<?php
require_once 'engine/main/db.php';

echo "🔍 Проверка паролей в таблице group_passwords:\n\n";

try {
    $passwords = Database::fetchAll('SELECT group_name, password_hash, is_active, description FROM group_passwords ORDER BY group_name');
    
    echo "📊 Найдено паролей: " . count($passwords) . "\n\n";
    
    if (count($passwords) == 0) {
        echo "❌ Таблица group_passwords пуста!\n";
        echo "💡 Нужно запустить create_group_passwords_table.php\n";
    } else {
        foreach ($passwords as $p) {
            echo "🔑 Группа: " . $p['group_name'] . "\n";
            echo "   Хеш: " . substr($p['password_hash'], 0, 30) . "...\n";
            echo "   Активен: " . ($p['is_active'] ? 'Да' : 'Нет') . "\n";
            echo "   Описание: " . $p['description'] . "\n\n";
        }
        
        // Тестируем проверку пароля
        echo "🧪 ТЕСТ ПРОВЕРКИ ПАРОЛЯ:\n";
        $testGroup = $passwords[0]['group_name'];
        echo "Тестируем группу: $testGroup\n";
        
        // Пробуем неправильный пароль
        $testPassword = "wrong_password";
        $result = password_verify($testPassword, $passwords[0]['password_hash']);
        echo "Пароль '$testPassword': " . ($result ? "✅ Верный" : "❌ Неверный") . "\n";
        
        // Проверяем с GroupPasswordChecker
        require_once 'engine/libs/GroupPasswordChecker.php';
        $checkerResult = GroupPasswordChecker::checkPassword($testGroup, $testPassword);
        echo "GroupPasswordChecker результат: " . ($checkerResult ? "✅ Успех" : "❌ Ошибка") . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}
?> 