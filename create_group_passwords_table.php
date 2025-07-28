<?php
require_once __DIR__ . '/engine/main/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS group_passwords (
        id INT AUTO_INCREMENT PRIMARY KEY,
        group_name VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        description TEXT,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    Database::execute($sql);
    echo "✅ Таблица group_passwords создана успешно\n";
    
    // Добавляем начальные пароли для групп
    $initial_passwords = [
        ['T111', 't111', 'Торговая деятельность (на основе ПТО) - 1 курс'],
        ['T101', 't101', 'Торговая деятельность (на основе ОСО) - 1 курс'],
        ['Э101', 'э101', 'Экономическая деятельность - 1 курс'],
        ['Ю101', 'ю101', 'Правовая деятельность - 1 курс'],
        ['T211', 't211', 'Торговая деятельность (на основе ПТО) - 2 курс'],
        ['T201', 't201', 'Торговая деятельность (на основе ОСО) - 2 курс'],
        ['Э201', 'э201', 'Экономическая деятельность - 2 курс'],
        ['Ю201', 'ю201', 'Правовая деятельность - 2 курс'],
        ['T301', 't301', 'Торговая деятельность - 3 курс'],
        ['Э301', 'э301', 'Экономическая деятельность - 3 курс'],
        ['Б301', 'б301', 'Бухгалтерская деятельность - 3 курс']
    ];
    
    foreach ($initial_passwords as $group) {
        $hashed_password = password_hash($group[1], PASSWORD_DEFAULT);
        try {
            Database::execute(
                "INSERT IGNORE INTO group_passwords (group_name, password, description) VALUES (?, ?, ?)",
                [$group[0], $hashed_password, $group[2]]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки дублирования
        }
    }
    
    echo "✅ Начальные пароли групп добавлены\n";
    echo "📊 Всего групп в системе: " . count($initial_passwords) . "\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}
?> 