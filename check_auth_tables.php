<?php
/**
 * Проверка таблиц авторизации
 */

echo "<h1>Проверка таблиц авторизации</h1>";

// Подключаем необходимые файлы
require_once 'application/config.php';
require_once 'engine/main/db.php';

try {
    echo "<h2>1. Проверка таблиц авторизации</h2>";
    
    $authTables = [
        'login_attempts' => 'Попытки входа',
        'user_sessions' => 'Сессии пользователей',
        'user_activity_log' => 'Лог активности пользователей',
        'user_2fa' => 'Двухфакторная аутентификация'
    ];
    
    foreach ($authTables as $tableName => $description) {
        $tables = Database::fetchAll("SHOW TABLES LIKE ?", [$tableName]);
        if (count($tables) > 0) {
            echo "✓ Таблица <strong>$tableName</strong> ($description) существует<br>";
            
            // Проверяем количество записей
            $count = Database::fetchOne("SELECT COUNT(*) as count FROM $tableName");
            echo "&nbsp;&nbsp;&nbsp;&nbsp;Записей: " . $count['count'] . "<br>";
            
            // Показываем структуру таблицы
            $columns = Database::fetchAll("DESCRIBE $tableName");
            echo "&nbsp;&nbsp;&nbsp;&nbsp;Поля: ";
            $fieldNames = [];
            foreach ($columns as $column) {
                $fieldNames[] = $column['Field'];
            }
            echo implode(', ', $fieldNames) . "<br>";
            
        } else {
            echo "✗ Таблица <strong>$tableName</strong> ($description) не существует<br>";
        }
    }
    
    echo "<h2>2. Создание недостающих таблиц</h2>";
    
    // Создаем таблицу login_attempts если её нет
    $loginAttemptsExists = Database::fetchAll("SHOW TABLES LIKE 'login_attempts'");
    if (count($loginAttemptsExists) == 0) {
        echo "Создание таблицы login_attempts...<br>";
        try {
            Database::execute("
                CREATE TABLE login_attempts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    username VARCHAR(96),
                    ip_address VARCHAR(45),
                    success TINYINT(1) DEFAULT 0,
                    attempt_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                    failure_reason TEXT,
                    INDEX idx_user_id (user_id),
                    INDEX idx_username (username),
                    INDEX idx_ip_address (ip_address),
                    INDEX idx_attempt_time (attempt_time)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ");
            echo "✓ Таблица login_attempts создана<br>";
        } catch (Exception $e) {
            echo "✗ Ошибка создания таблицы login_attempts: " . $e->getMessage() . "<br>";
        }
    }
    
    // Создаем таблицу user_sessions если её нет
    $userSessionsExists = Database::fetchAll("SHOW TABLES LIKE 'user_sessions'");
    if (count($userSessionsExists) == 0) {
        echo "Создание таблицы user_sessions...<br>";
        try {
            Database::execute("
                CREATE TABLE user_sessions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    session_token VARCHAR(255) NOT NULL,
                    ip_address VARCHAR(45),
                    user_agent TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    last_activity DATETIME DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_user_id (user_id),
                    INDEX idx_session_token (session_token),
                    INDEX idx_last_activity (last_activity)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ");
            echo "✓ Таблица user_sessions создана<br>";
        } catch (Exception $e) {
            echo "✗ Ошибка создания таблицы user_sessions: " . $e->getMessage() . "<br>";
        }
    }
    
    // Создаем таблицу user_activity_log если её нет
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
    }
    
    // Создаем таблицу user_2fa если её нет
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
    }
    
    echo "<h2>3. Тест авторизации</h2>";
    echo "<p>Теперь можно попробовать войти в систему:</p>";
    echo "<ul>";
    echo "<li><a href='http://localhost:8000/admin/login' target='_blank'>Страница входа</a></li>";
    echo "</ul>";
    
    echo "<h2>4. Доступные пользователи</h2>";
    $users = Database::fetchAll("SELECT user_id, user_login, user_fio, user_status FROM users WHERE user_status = 1");
    if (count($users) > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Логин</th><th>ФИО</th><th>Статус</th></tr>";
        foreach ($users as $user) {
            $status = $user['user_status'] ? 'Активен' : 'Неактивен';
            echo "<tr>";
            echo "<td>" . $user['user_id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['user_login']) . "</td>";
            echo "<td>" . htmlspecialchars($user['user_fio']) . "</td>";
            echo "<td>" . $status . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Активных пользователей не найдено.</p>";
    }
    
} catch (Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "<br>";
}
?> 