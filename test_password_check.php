<?php
require_once 'engine/main/db.php';
require_once 'engine/libs/GroupPasswordChecker.php';

echo "🧪 ТЕСТИРОВАНИЕ ПРОВЕРКИ ПАРОЛЕЙ:\n\n";

// Получаем данные групп
$groups = Database::fetchAll('SELECT group_name, password FROM group_passwords WHERE is_active = 1 LIMIT 3');

foreach ($groups as $group) {
    $groupName = $group['group_name'];
    echo "🔑 Группа: $groupName\n";
    
    // Тест 1: Неправильный пароль
    $wrongPassword = "wrong123";
    $result1 = GroupPasswordChecker::checkPassword($groupName, $wrongPassword);
    echo "   Неправильный пароль '$wrongPassword': " . ($result1 ? "❌ ОШИБКА! Пропустил!" : "✅ Заблокировал") . "\n";
    
    // Тест 2: Попытка понять правильный пароль (для демонстрации - не безопасно!)
    // В реальности пароли должны знать только администраторы
    echo "   (Для тестирования нужно знать правильный пароль группы)\n";
    
    echo "\n";
}

// Дополнительный тест с пустыми значениями
echo "🔍 ДОПОЛНИТЕЛЬНЫЕ ТЕСТЫ:\n";
echo "Пустой пароль: " . (GroupPasswordChecker::checkPassword("Т111", "") ? "❌ Пропустил" : "✅ Заблокировал") . "\n";
echo "Несуществующая группа: " . (GroupPasswordChecker::checkPassword("НетГруппы", "123") ? "❌ Пропустил" : "✅ Заблокировал") . "\n";

echo "\n✅ Тестирование завершено!\n";
echo "💡 Теперь нужно протестировать на реальном сайте с правильными паролями групп.\n";
?> 