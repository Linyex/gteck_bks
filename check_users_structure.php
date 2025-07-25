<?php
require_once 'engine/main/db.php';

echo "=== Проверка структуры таблицы users ===\n";

try {
    $fields = Database::fetchAll("DESCRIBE users");
    echo "Поля таблицы users:\n";
    foreach ($fields as $field) {
        echo "- " . $field['Field'] . " (" . $field['Type'] . ")\n";
    }
    
    echo "\n=== Проверка данных ===\n";
    $users = Database::fetchAll("SELECT * FROM users LIMIT 3");
    echo "Первые 3 пользователя:\n";
    foreach ($users as $user) {
        echo "- ID: " . $user['user_id'] . ", ФИО: " . $user['user_fio'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
} 