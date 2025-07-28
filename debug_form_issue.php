<?php
// Симулируем отправку формы с паролем
$_POST = [
    'group_name' => 'Т111',
    'password' => '123'
];

echo "🧪 СИМУЛЯЦИЯ ОТПРАВКИ ФОРМЫ:\n";
echo "POST данные: " . print_r($_POST, true) . "\n\n";

// Подключаем необходимые файлы
require_once 'engine/main/db.php';
require_once 'engine/libs/GroupPasswordChecker.php';

// Симулируем логику из kontrolnui.php
$group_name = trim($_POST['group_name']);
$password = trim($_POST['password']);

echo "📝 Обработанные данные:\n";
echo "Группа: '$group_name'\n";
echo "Пароль: '$password'\n\n";

// Проверяем пароль
$result = GroupPasswordChecker::checkPassword($group_name, $password);
echo "🔍 Результат проверки: " . ($result ? "✅ УСПЕХ" : "❌ ОШИБКА") . "\n";

// Проверяем сессию
session_start();
if (isset($_SESSION['group_access'])) {
    echo "✅ Сессия создана:\n";
    print_r($_SESSION['group_access']);
} else {
    echo "❌ Сессия не создана\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎯 ВЫВОД:\n";

if ($result) {
    echo "✅ Логика работает правильно!\n";
    echo "💡 Проблема может быть в:\n";
    echo "   1. Кодировке символов на странице\n";
    echo "   2. JavaScript блокировке формы\n";
    echo "   3. Проблемах с сессией на сайте\n";
} else {
    echo "❌ Проблема в логике проверки\n";
}
?> 