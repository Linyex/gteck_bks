<?php
require_once 'application/config.php';
require_once 'engine/main/db.php';

echo "<h1>Исправление пароля пользователя admin</h1>";

try {
    // Проверяем текущего пользователя admin
    $adminUser = Database::fetchOne("SELECT * FROM users WHERE user_login = 'admin'");
    
    if ($adminUser) {
        echo "✅ Пользователь admin найден<br>";
        echo "Текущий пароль в БД: " . $adminUser['user_password'] . "<br>";
        
        // Создаем новый хеш для пароля 'admin'
        $newPasswordHash = password_hash('admin', PASSWORD_DEFAULT);
        
        // Обновляем пароль в базе данных
        Database::execute("UPDATE users SET user_password = ? WHERE user_login = 'admin'", [$newPasswordHash]);
        
        echo "✅ Пароль пользователя admin обновлен<br>";
        echo "Новый хеш: " . $newPasswordHash . "<br>";
        
        // Проверяем, что хеш работает
        if (password_verify('admin', $newPasswordHash)) {
            echo "✅ Проверка пароля 'admin' прошла успешно<br>";
        } else {
            echo "❌ Ошибка проверки пароля<br>";
        }
        
    } else {
        echo "❌ Пользователь admin не найден<br>";
        
        // Создаем пользователя admin
        $passwordHash = password_hash('admin', PASSWORD_DEFAULT);
        
        Database::execute("INSERT INTO users (user_login, user_password, user_fio, user_status, user_access_level, user_date_reg) VALUES (?, ?, ?, ?, ?, NOW())", 
            ['admin', $passwordHash, 'Администратор', 1, 10]);
        
        echo "✅ Пользователь admin создан с паролем 'admin'<br>";
        echo "Хеш: " . $passwordHash . "<br>";
    }
    
    echo "<h3>Тест аутентификации:</h3>";
    
    // Тестируем аутентификацию
    $testUser = Database::fetchOne("SELECT * FROM users WHERE user_login = 'admin'");
    
    if ($testUser) {
        echo "Пользователь найден: " . $testUser['user_login'] . "<br>";
        
        // Тестируем правильный пароль
        if (password_verify('admin', $testUser['user_password'])) {
            echo "✅ Пароль 'admin' работает корректно<br>";
        } else {
            echo "❌ Пароль 'admin' не работает<br>";
        }
        
        // Тестируем неправильный пароль
        if (password_verify('wrong_password', $testUser['user_password'])) {
            echo "❌ Неправильный пароль прошел проверку (это ошибка)<br>";
        } else {
            echo "✅ Неправильный пароль правильно отклонен<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "<br>";
}

echo "<h3>Инструкции для входа:</h3>";
echo "1. Перейдите на страницу входа в админ-панель<br>";
echo "2. Используйте логин: admin<br>";
echo "3. Используйте пароль: admin<br>";
echo "4. Нажмите кнопку входа<br>";

echo "<br><a href='index.php'>Вернуться на главную</a>";
echo "<br><a href='test_auth_system.php'>Запустить тест аутентификации</a>";
?> 