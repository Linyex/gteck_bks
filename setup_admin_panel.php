<?php
require_once 'application/config.php';
require_once 'engine/main/db.php';

echo "<h1>Настройка админ-панели</h1>";

try {
    // 1. Исправляем структуру таблицы users
    echo "<h3>1. Исправление структуры таблицы users</h3>";
    
    // Проверяем текущую структуру
    $columns = Database::fetchAll("DESCRIBE users");
    $passwordColumn = null;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'user_password') {
            $passwordColumn = $column;
            break;
        }
    }
    
    if ($passwordColumn && $passwordColumn['Type'] === 'varchar(32)') {
        echo "⚠️ Обнаружено поле user_password типа varchar(32). Изменяем на varchar(255)...<br>";
        
        Database::execute("ALTER TABLE users MODIFY user_password VARCHAR(255) NOT NULL");
        echo "✅ Поле user_password успешно изменено на VARCHAR(255)<br>";
    } else {
        echo "✅ Структура таблицы users корректна<br>";
    }
    
    // 2. Создаем или обновляем пользователя admin
    echo "<h3>2. Настройка пользователя admin</h3>";
    
    $adminUser = Database::fetchOne("SELECT * FROM users WHERE user_login = 'admin'");
    
    if ($adminUser) {
        echo "✅ Пользователь admin найден<br>";
        echo "Текущий пароль: " . substr($adminUser['user_password'], 0, 20) . "...<br>";
        
        // Обновляем пароль на правильный хеш
        $newPasswordHash = password_hash('admin', PASSWORD_DEFAULT);
        Database::execute("UPDATE users SET user_password = ?, user_fio = 'Администратор', user_status = 1, user_access_level = 10 WHERE user_login = 'admin'", 
            [$newPasswordHash]);
        
        echo "✅ Пароль пользователя admin обновлен<br>";
    } else {
        echo "❌ Пользователь admin не найден. Создаем...<br>";
        
        $passwordHash = password_hash('admin', PASSWORD_DEFAULT);
        Database::execute("INSERT INTO users (user_login, user_password, user_fio, user_status, user_access_level, user_date_reg) VALUES (?, ?, ?, ?, ?, NOW())", 
            ['admin', $passwordHash, 'Администратор', 1, 10]);
        
        echo "✅ Пользователь admin создан<br>";
    }
    
    // 3. Создаем таблицу auth_log если её нет
    echo "<h3>3. Создание таблицы auth_log</h3>";
    
    $authLogExists = Database::fetchOne("SHOW TABLES LIKE 'auth_log'");
    
    if (!$authLogExists) {
        $sql = "CREATE TABLE auth_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            ip_address VARCHAR(45),
            status VARCHAR(20) NOT NULL,
            login_time DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_login_time (login_time)
        )";
        
        Database::execute($sql);
        echo "✅ Таблица auth_log создана<br>";
    } else {
        echo "✅ Таблица auth_log уже существует<br>";
    }
    
    // 4. Проверяем тестовую аутентификацию
    echo "<h3>4. Тест аутентификации</h3>";
    
    $testUser = Database::fetchOne("SELECT * FROM users WHERE user_login = 'admin'");
    
    if ($testUser && password_verify('admin', $testUser['user_password'])) {
        echo "✅ Аутентификация работает корректно<br>";
        echo "Логин: admin<br>";
        echo "Пароль: admin<br>";
    } else {
        echo "❌ Ошибка аутентификации<br>";
    }
    
    // 5. Проверяем структуру других таблиц
    echo "<h3>5. Проверка структуры таблиц</h3>";
    
    $tables = ['news', 'files', 'photos', 'category'];
    
    foreach ($tables as $table) {
        $tableExists = Database::fetchOne("SHOW TABLES LIKE '$table'");
        if ($tableExists) {
            echo "✅ Таблица $table существует<br>";
        } else {
            echo "⚠️ Таблица $table не найдена<br>";
        }
    }
    
    echo "<h3>Настройка завершена!</h3>";
    echo "<p>Теперь вы можете войти в админ-панель:</p>";
    echo "<ul>";
    echo "<li>URL: <a href='/admin/login'>http://localhost/admin/login</a></li>";
    echo "<li>Логин: admin</li>";
    echo "<li>Пароль: admin</li>";
    echo "</ul>";
    
    echo "<h4>Доступные разделы админ-панели:</h4>";
    echo "<ul>";
    echo "<li><a href='/admin/dashboard'>Дашборд</a> - главная страница</li>";
    echo "<li><a href='/admin/users'>Пользователи</a> - управление пользователями</li>";
    echo "<li><a href='/admin/news'>Новости</a> - управление новостями</li>";
    echo "<li><a href='/admin/files'>Файлы</a> - управление файлами</li>";
    echo "<li><a href='/admin/photos'>Фотографии</a> - управление фотографиями</li>";
    echo "<li><a href='/admin/settings'>Настройки</a> - настройки системы</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><a href='index.php'>Вернуться на главную</a>";
?> 