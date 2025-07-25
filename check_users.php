<?php
/**
 * Проверка пользователей в базе данных
 */

echo "<h1>Проверка пользователей в базе данных</h1>";

// Подключаем необходимые файлы
require_once 'application/config.php';
require_once 'engine/main/db.php';

try {
    echo "<h2>1. Проверка таблицы users</h2>";
    
    // Проверяем существование таблицы
    $tables = Database::fetchAll("SHOW TABLES LIKE 'users'");
    if (count($tables) == 0) {
        echo "✗ Таблица users не существует<br>";
        exit;
    }
    
    echo "✓ Таблица users существует<br>";
    
    // Проверяем структуру таблицы
    $columns = Database::fetchAll("DESCRIBE users");
    echo "<h3>Структура таблицы users:</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Поле</th><th>Тип</th><th>NULL</th><th>Ключ</th><th>По умолчанию</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Проверяем количество пользователей
    $count = Database::fetchOne("SELECT COUNT(*) as count FROM users");
    echo "<h3>Количество пользователей: " . $count['count'] . "</h3>";
    
    if ($count['count'] > 0) {
        // Показываем список пользователей (без паролей)
        $users = Database::fetchAll("SELECT user_id, user_login, user_fio, user_email, user_status, user_access_level, created_at FROM users ORDER BY user_id");
        
        echo "<h3>Список пользователей:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Логин</th><th>ФИО</th><th>Email</th><th>Статус</th><th>Уровень доступа</th><th>Дата создания</th></tr>";
        
        foreach ($users as $user) {
            $status = $user['user_status'] ? 'Активен' : 'Неактивен';
            echo "<tr>";
            echo "<td>" . $user['user_id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['user_login']) . "</td>";
            echo "<td>" . htmlspecialchars($user['user_fio']) . "</td>";
            echo "<td>" . htmlspecialchars($user['user_email']) . "</td>";
            echo "<td>" . $status . "</td>";
            echo "<td>" . $user['user_access_level'] . "</td>";
            echo "<td>" . $user['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Проверяем активных пользователей
        $activeUsers = Database::fetchAll("SELECT user_id, user_login, user_fio FROM users WHERE user_status = 1");
        echo "<h3>Активные пользователи (" . count($activeUsers) . "):</h3>";
        foreach ($activeUsers as $user) {
            echo "- " . htmlspecialchars($user['user_login']) . " (" . htmlspecialchars($user['user_fio']) . ")<br>";
        }
        
    } else {
        echo "<h3>Пользователи не найдены</h3>";
        echo "<p>Необходимо создать пользователей в базе данных.</p>";
    }
    
    // Проверяем таблицу 2FA
    echo "<h2>2. Проверка таблицы user_2fa</h2>";
    $tables2fa = Database::fetchAll("SHOW TABLES LIKE 'user_2fa'");
    if (count($tables2fa) > 0) {
        echo "✓ Таблица user_2fa существует<br>";
        
        $users2fa = Database::fetchAll("SELECT user_id, is_enabled FROM user_2fa");
        echo "Пользователей с 2FA: " . count($users2fa) . "<br>";
        
        foreach ($users2fa as $user2fa) {
            $status = $user2fa['is_enabled'] ? 'Включена' : 'Отключена';
            echo "- Пользователь ID " . $user2fa['user_id'] . ": 2FA " . $status . "<br>";
        }
    } else {
        echo "✗ Таблица user_2fa не существует<br>";
    }
    
    // Создаем тестового пользователя если их нет
    if ($count['count'] == 0) {
        echo "<h2>3. Создание тестового пользователя</h2>";
        
        $testUser = [
            'user_login' => 'admin',
            'user_password' => password_hash('admin123', PASSWORD_DEFAULT),
            'user_fio' => 'Администратор системы',
            'user_email' => 'admin@nocontrgtec.com',
            'user_status' => 1,
            'user_access_level' => 10,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            Database::execute(
                "INSERT INTO users (user_login, user_password, user_fio, user_email, user_status, user_access_level, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    $testUser['user_login'],
                    $testUser['user_password'],
                    $testUser['user_fio'],
                    $testUser['user_email'],
                    $testUser['user_status'],
                    $testUser['user_access_level'],
                    $testUser['created_at']
                ]
            );
            
            echo "✓ Тестовый пользователь создан:<br>";
            echo "- Логин: admin<br>";
            echo "- Пароль: admin123<br>";
            echo "- ФИО: Администратор системы<br>";
            
        } catch (Exception $e) {
            echo "✗ Ошибка создания тестового пользователя: " . $e->getMessage() . "<br>";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "<br>";
}

echo "<h2>4. Инструкции для входа</h2>";
echo "<p>Если пользователи существуют, используйте их логин и пароль для входа.</p>";
echo "<p>Если пользователей нет, используйте созданного тестового пользователя:</p>";
echo "<ul>";
echo "<li><strong>Логин:</strong> admin</li>";
echo "<li><strong>Пароль:</strong> admin123</li>";
echo "</ul>";

echo "<h2>5. Ссылки для тестирования</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/admin/login' target='_blank'>Страница входа</a></li>";
echo "<li><a href='http://localhost:8000/admin/dashboard' target='_blank'>Дашборд (после входа)</a></li>";
echo "</ul>";
?> 