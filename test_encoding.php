<?php
echo "🔤 ТЕСТ КОДИРОВКИ СИМВОЛОВ:\n\n";

// Тестируем разные варианты группы Т111
$variants = [
    'Т111',  // Кириллица
    'T111',  // Латиница
    'т111',  // Маленькие буквы
    'T111'   // Большие буквы
];

foreach ($variants as $variant) {
    echo "Группа: '$variant' (длина: " . strlen($variant) . ")\n";
    echo "Байты: " . bin2hex($variant) . "\n";
    echo "UTF-8: " . mb_detect_encoding($variant, 'UTF-8') . "\n\n";
}

// Тестируем пароль
$password = '123';
echo "Пароль: '$password' (длина: " . strlen($password) . ")\n";
echo "Байты: " . bin2hex($password) . "\n\n";

// Проверяем что происходит при trim()
echo "🔍 ТЕСТ TRIM():\n";
$testInput = "  123  ";
echo "До trim: '$testInput'\n";
echo "После trim: '" . trim($testInput) . "'\n";
?> 