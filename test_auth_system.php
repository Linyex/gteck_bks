<?php
require_once 'application/config.php';
require_once 'engine/main/db.php';

echo "<h1>Тест системы аутентификации</h1>";

// Проверяем подключение к базе данных
try {
    $db = Database::getConnection();
    echo "✅ Подключение к базе данных успешно<br>";
} catch (Exception $e) {
    echo "❌ Ошибка подключения к БД: " . $e->getMessage() . "<br>";
    exit;
}

// Проверяем таблицу пользователей
try {
    $users = Database::fetchAll("SELECT * FROM users LIMIT 5");
    echo "✅ Таблица users найдена. Количество записей: " . count($users) . "<br>";
    
    if (count($users) > 0) {
        echo "<h3>Первые 5 пользователей:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Логин</th><th>Пароль</th><th>ФИО</th><th>Статус</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['user_id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['user_login']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($user['user_password'], 0, 20)) . "...</td>";
            echo "<td>" . htmlspecialchars($user['user_fio']) . "</td>";
            echo "<td>" . htmlspecialchars($user['user_status']) . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
} catch (Exception $e) {
    echo "❌ Ошибка при получении пользователей: " . $e->getMessage() . "<br>";
}

// Тестируем хеширование паролей
echo "<h3>Тест хеширования паролей:</h3>";

$testPassword = 'admin';
$salt = defined('PASSWORD_SALT') ? PASSWORD_SALT : 'nocontrgtec_salt_2024';

// MD5 хеш (старый способ)
$md5Hash = md5($testPassword);
echo "MD5 хеш для 'admin': " . $md5Hash . "<br>";

// MD5 с солью
$md5SaltHash = md5($testPassword . $salt);
echo "MD5 с солью для 'admin': " . $md5SaltHash . "<br>";

// SHA1 хеш
$sha1Hash = sha1($testPassword);
echo "SHA1 хеш для 'admin': " . $sha1Hash . "<br>";

// SHA1 с солью
$sha1SaltHash = sha1($testPassword . $salt);
echo "SHA1 с солью для 'admin': " . $sha1SaltHash . "<br>";

// Проверяем, есть ли пользователь admin
try {
    $adminUser = Database::fetchOne("SELECT * FROM users WHERE user_login = 'admin'");
    
    if ($adminUser) {
        echo "<h3>Пользователь admin найден:</h3>";
        echo "ID: " . $adminUser['user_id'] . "<br>";
        echo "Логин: " . $adminUser['user_login'] . "<br>";
        echo "Пароль в БД: " . $adminUser['user_password'] . "<br>";
        echo "ФИО: " . $adminUser['user_fio'] . "<br>";
        echo "Статус: " . $adminUser['user_status'] . "<br>";
        
        // Проверяем различные варианты хеширования
        echo "<h4>Проверка пароля:</h4>";
        echo "Пароль 'admin' MD5: " . md5('admin') . " - " . (md5('admin') === $adminUser['user_password'] ? "✅ Совпадает" : "❌ Не совпадает") . "<br>";
        echo "Пароль 'admin' MD5 с солью: " . md5('admin' . $salt) . " - " . (md5('admin' . $salt) === $adminUser['user_password'] ? "✅ Совпадает" : "❌ Не совпадает") . "<br>";
        echo "Пароль 'admin' SHA1: " . sha1('admin') . " - " . (sha1('admin') === $adminUser['user_password'] ? "✅ Совпадает" : "❌ Не совпадает") . "<br>";
        echo "Пароль 'admin' SHA1 с солью: " . sha1('admin' . $salt) . " - " . (sha1('admin' . $salt) === $adminUser['user_password'] ? "✅ Совпадает" : "❌ Не совпадает") . "<br>";
        
    } else {
        echo "❌ Пользователь admin не найден в базе данных<br>";
        
        // Создаем пользователя admin
        echo "<h3>Создание пользователя admin:</h3>";
        $adminPassword = md5('admin' . $salt);
        
        try {
            Database::execute("INSERT INTO users (user_login, user_password, user_fio, user_status, user_access_level, user_date_reg) VALUES (?, ?, ?, ?, ?, NOW())", 
                ['admin', $adminPassword, 'Администратор', 1, 10]);
            echo "✅ Пользователь admin создан с паролем 'admin'<br>";
        } catch (Exception $e) {
            echo "❌ Ошибка создания пользователя: " . $e->getMessage() . "<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Ошибка при поиске пользователя admin: " . $e->getMessage() . "<br>";
}

// Проверяем структуру таблицы users
echo "<h3>Структура таблицы users:</h3>";
try {
    $columns = Database::fetchAll("DESCRIBE users");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Поле</th><th>Тип</th><th>NULL</th><th>Ключ</th><th>По умолчанию</th><th>Дополнительно</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
} catch (Exception $e) {
    echo "❌ Ошибка при получении структуры таблицы: " . $e->getMessage() . "<br>";
}

echo "<h3>Рекомендации:</h3>";
echo "1. Проверьте, что в контроллере аутентификации используется правильный алгоритм хеширования<br>";
echo "2. Убедитесь, что соль (PASSWORD_SALT) совпадает в конфигурации и при создании хеша<br>";
echo "3. Проверьте, что в форме входа передаются правильные имена полей<br>";
echo "4. Убедитесь, что сессии работают корректно<br>";

echo "<br><a href='index.php'>Вернуться на главную</a>";
?> 