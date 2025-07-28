<?php
require_once 'engine/main/db.php';
require_once 'engine/libs/GroupPasswordChecker.php';

echo "🔍 ДИАГНОСТИКА ПРОБЛЕМЫ С ПАРОЛЕМ ГРУППЫ Т111:\n\n";

// 1. Проверяем что есть в БД для группы Т111
echo "📊 1. Данные группы Т111 в БД:\n";
try {
    $group = Database::fetchOne("SELECT * FROM group_passwords WHERE group_name = 'Т111'");
    if ($group) {
        echo "✅ Группа найдена:\n";
        echo "   ID: " . $group['id'] . "\n";
        echo "   Название: " . $group['group_name'] . "\n";
        echo "   Активна: " . ($group['is_active'] ? 'Да' : 'Нет') . "\n";
        echo "   Хеш пароля: " . substr($group['password'], 0, 30) . "...\n";
        echo "   Описание: " . $group['description'] . "\n\n";
    } else {
        echo "❌ Группа Т111 не найдена в БД!\n\n";
        return;
    }
} catch (Exception $e) {
    echo "❌ Ошибка БД: " . $e->getMessage() . "\n\n";
    return;
}

// 2. Тестируем пароль "123"
echo "🧪 2. Тестирование пароля '123':\n";
$testPassword = "123";
$result = GroupPasswordChecker::checkPassword("Т111", $testPassword);
echo "Результат проверки: " . ($result ? "✅ УСПЕХ" : "❌ ОШИБКА") . "\n";

// 3. Проверяем хеш пароля "123"
echo "\n🔐 3. Проверка хеша пароля '123':\n";
$correctHash = password_hash("123", PASSWORD_DEFAULT);
echo "Правильный хеш для '123': " . substr($correctHash, 0, 30) . "...\n";
echo "Хеш в БД: " . substr($group['password'], 0, 30) . "...\n";

// 4. Проверяем напрямую password_verify
echo "\n🔍 4. Прямая проверка password_verify:\n";
$directCheck = password_verify("123", $group['password']);
echo "password_verify('123', хеш_из_БД): " . ($directCheck ? "✅ Верный" : "❌ Неверный") . "\n";

// 5. Проверяем другие возможные пароли
echo "\n🔍 5. Тестирование других вариантов:\n";
$testPasswords = ["123", "t111", "T111", "т111", "Т111", "password", ""];
foreach ($testPasswords as $pwd) {
    $check = password_verify($pwd, $group['password']);
    echo "Пароль '$pwd': " . ($check ? "✅ Верный" : "❌ Неверный") . "\n";
}

// 6. Проверяем сессию
echo "\n📋 6. Проверка сессии:\n";
session_start();
if (isset($_SESSION['group_access'])) {
    echo "✅ Сессия содержит данные доступа:\n";
    print_r($_SESSION['group_access']);
} else {
    echo "❌ Сессия не содержит данных доступа\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎯 ЗАКЛЮЧЕНИЕ:\n";

if ($result) {
    echo "✅ Система работает правильно!\n";
    echo "💡 Возможно проблема в интерфейсе или кодировке\n";
} else {
    echo "❌ Проблема найдена!\n";
    if (!$directCheck) {
        echo "   - Пароль '123' не соответствует хешу в БД\n";
        echo "   - Возможно пароль был изменен не через админку\n";
    }
}

echo "\n💡 РЕКОМЕНДАЦИИ:\n";
echo "1. Проверьте что пароль точно '123' (без пробелов)\n";
echo "2. Попробуйте сбросить пароль через админку\n";
echo "3. Проверьте кодировку символов\n";
?> 