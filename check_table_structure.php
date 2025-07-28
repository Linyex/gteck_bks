<?php
require_once 'engine/main/db.php';

echo "🔍 Проверка структуры таблицы group_passwords:\n\n";

try {
    $columns = Database::fetchAll('DESCRIBE group_passwords');
    
    echo "📊 Поля таблицы:\n";
    foreach ($columns as $col) {
        echo "   " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
    
    echo "\n📋 Содержимое таблицы:\n";
    $data = Database::fetchAll('SELECT * FROM group_passwords LIMIT 3');
    
    if (count($data) == 0) {
        echo "❌ Таблица пуста\n";
    } else {
        foreach ($data as $row) {
            echo "Строка: ";
            foreach ($row as $key => $value) {
                echo "$key='$value' ";
            }
            echo "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    
    // Возможно таблица не существует?
    echo "\n🔍 Проверяем существование таблицы:\n";
    try {
        $tables = Database::fetchAll("SHOW TABLES LIKE 'group_passwords'");
        if (count($tables) == 0) {
            echo "❌ Таблица group_passwords не существует!\n";
            echo "💡 Нужно запустить create_group_passwords_table.php\n";
        }
    } catch (Exception $e2) {
        echo "❌ Ошибка проверки таблиц: " . $e2->getMessage() . "\n";
    }
}
?> 