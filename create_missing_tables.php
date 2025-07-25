<?php
/**
 * Создание недостающих таблиц авторизации
 */

echo "<h1>Создание недостающих таблиц</h1>";

// Подключаем необходимые файлы
require_once 'application/config.php';
require_once 'engine/main/db.php';

try {
    echo "<h2>1. Создание таблицы user_activity_log</h2>";
    
    $userActivityLogExists = Database::fetchAll("SHOW TABLES LIKE 'user_activity_log'");
    if (count($userActivityLogExists) == 0) {
        echo "Создание таблицы user_activity_log...<br>";
        try {
            Database::execute("
                CREATE TABLE user_activity_log (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    action_type VARCHAR(50) NOT NULL,
                    description TEXT,
                    ip_address VARCHAR(45),
                    activity_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_user_id (user_id),
                    INDEX idx_action_type (action_type),
                    INDEX idx_activity_time (activity_time)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ");
            echo "✓ Таблица user_activity_log создана<br>";
        } catch (Exception $e) {
            echo "✗ Ошибка создания таблицы user_activity_log: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "✓ Таблица user_activity_log уже существует<br>";
    }
    
    echo "<h2>2. Создание таблицы user_2fa</h2>";
    
    $user2faExists = Database::fetchAll("SHOW TABLES LIKE 'user_2fa'");
    if (count($user2faExists) == 0) {
        echo "Создание таблицы user_2fa...<br>";
        try {
            Database::execute("
                CREATE TABLE user_2fa (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    secret_key VARCHAR(255) NOT NULL,
                    is_enabled TINYINT(1) DEFAULT 0,
                    backup_codes TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_user_id (user_id),
                    INDEX idx_user_id (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ");
            echo "✓ Таблица user_2fa создана<br>";
        } catch (Exception $e) {
            echo "✗ Ошибка создания таблицы user_2fa: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "✓ Таблица user_2fa уже существует<br>";
    }
    
    echo "<h2>3. Обновление пароля пользователя admin</h2>";
    
    // Проверяем, есть ли пользователь admin
    $adminUser = Database::fetchOne("SELECT user_id, user_login FROM users WHERE user_login = 'admin'");
    if ($adminUser) {
        echo "Пользователь admin найден (ID: " . $adminUser['user_id'] . ")<br>";
        
        // Обновляем пароль
        $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
        try {
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$newPassword, $adminUser['user_id']]
            );
            echo "✓ Пароль пользователя admin обновлен на 'admin123'<br>";
        } catch (Exception $e) {
            echo "✗ Ошибка обновления пароля: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Пользователь admin не найден<br>";
    }
    
    echo "<h2>4. Проверка всех таблиц авторизации</h2>";
    $authTables = ['login_attempts', 'user_sessions', 'user_activity_log', 'user_2fa'];
    
    foreach ($authTables as $tableName) {
        $tables = Database::fetchAll("SHOW TABLES LIKE '$tableName'");
        if (count($tables) > 0) {
            echo "✓ Таблица $tableName существует<br>";
        } else {
            echo "✗ Таблица $tableName не существует<br>";
        }
    }
    
    echo "<h2>5. Тест авторизации после исправлений</h2>";
    
    $testUsername = 'admin';
    $testPassword = 'admin123';
    
    $user = Database::fetchOne("SELECT * FROM users WHERE user_login = ? AND user_status = 1", [$testUsername]);
    
    if ($user) {
        echo "✓ Пользователь admin найден<br>";
        
        if (password_verify($testPassword, $user['user_password'])) {
            echo "✓ Пароль admin123 работает<br>";
        } else {
            echo "✗ Пароль admin123 не работает<br>";
        }
    } else {
        echo "✗ Пользователь admin не найден<br>";
    }
    
    echo "<h2>6. Ссылки для тестирования</h2>";
    echo "<ul>";
    echo "<li><a href='http://localhost:8000/admin/login' target='_blank'>Страница входа</a></li>";
    echo "<li><a href='http://localhost:8000/admin/dashboard' target='_blank'>Дашборд</a></li>";
    echo "</ul>";
    
    echo "<h2>7. Данные для входа</h2>";
    echo "<p><strong>Логин:</strong> admin</p>";
    echo "<p><strong>Пароль:</strong> admin123</p>";
    
} catch (Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "<br>";
}
?> 