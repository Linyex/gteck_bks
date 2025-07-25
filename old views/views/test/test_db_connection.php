<?php
// Тест подключения к базе данных
require_once 'engine/database/mysql.php';

// Параметры подключения из конфигурации
$hostname = 'localhost';
$username = 'gtecbks_user';
$password = 'H6e7V3u5';
$database = 'gtecbks_db';

echo "<h2>Тест подключения к базе данных</h2>";

try {
    echo "<p>Попытка подключения к базе данных...</p>";
    $db = new mysqlDriver($hostname, $username, $password, $database);
    echo "<p style='color: green;'>✓ Подключение к базе данных успешно!</p>";
    
    // Проверяем таблицы
    echo "<h3>Проверка таблиц:</h3>";
    
    $tables = ['dkrfiles', 'dkrgroups', 'dkrjointable'];
    
    foreach ($tables as $table) {
        try {
            $result = $db->query("SELECT COUNT(*) as count FROM $table");
            echo "<p>✓ Таблица '$table' существует, записей: " . $result->row['count'] . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Ошибка с таблицей '$table': " . $e->getMessage() . "</p>";
        }
    }
    
    // Проверяем структуру таблицы dkrfiles
    echo "<h3>Структура таблицы dkrfiles:</h3>";
    try {
        $result = $db->query("DESCRIBE dkrfiles");
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Поле</th><th>Тип</th><th>NULL</th><th>Ключ</th><th>По умолчанию</th><th>Дополнительно</th></tr>";
        foreach ($result->rows as $row) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Ошибка при получении структуры таблицы: " . $e->getMessage() . "</p>";
    }
    
    // Проверяем несколько записей из таблицы dkrfiles
    echo "<h3>Последние записи из dkrfiles:</h3>";
    try {
        $result = $db->query("SELECT * FROM dkrfiles ORDER BY upload_date DESC LIMIT 5");
        if ($result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Имя файла</th><th>Путь</th><th>Дата загрузки</th></tr>";
            foreach ($result->rows as $row) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['filename']) . "</td>";
                echo "<td>" . htmlspecialchars($row['path']) . "</td>";
                echo "<td>" . $row['upload_date'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Таблица dkrfiles пуста</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Ошибка при получении записей: " . $e->getMessage() . "</p>";
    }
    
    // Проверяем группы
    echo "<h3>Группы:</h3>";
    try {
        $result = $db->query("SELECT * FROM dkrgroups ORDER BY groupname");
        if ($result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Название группы</th></tr>";
            foreach ($result->rows as $row) {
                echo "<tr>";
                echo "<td>" . $row['id_group'] . "</td>";
                echo "<td>" . htmlspecialchars($row['groupname']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Таблица dkrgroups пуста</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Ошибка при получении групп: " . $e->getMessage() . "</p>";
    }
    
    // Тест вставки записи
    echo "<h3>Тест вставки записи:</h3>";
    try {
        $test_filename = $db->escape('test_file_' . date('YmdHis') . '.txt');
        $test_path = $db->escape('/assets/files/konrolnui/test_file.txt');
        
        $sql = "INSERT INTO dkrfiles (filename, path, upload_date) VALUES ('$test_filename', '$test_path', NOW())";
        $db->query($sql);
        $insert_id = $db->getLastId();
        
        echo "<p style='color: green;'>✓ Тестовая запись добавлена с ID: $insert_id</p>";
        
        // Удаляем тестовую запись
        $db->query("DELETE FROM dkrfiles WHERE id = '$insert_id'");
        echo "<p>✓ Тестовая запись удалена</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Ошибка при тестировании вставки: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Ошибка подключения к базе данных: " . $e->getMessage() . "</p>";
    
    // Дополнительная диагностика
    echo "<h3>Диагностика:</h3>";
    echo "<p>Hostname: $hostname</p>";
    echo "<p>Username: $username</p>";
    echo "<p>Database: $database</p>";
    
    // Проверяем доступность MySQL
    if (function_exists('mysqli_connect')) {
        echo "<p>✓ Расширение mysqli доступно</p>";
    } else {
        echo "<p style='color: red;'>✗ Расширение mysqli недоступно</p>";
    }
    
    // Проверяем подключение без выбора базы данных
    try {
        $test_link = new mysqli($hostname, $username, $password);
        if (!$test_link->connect_error) {
            echo "<p style='color: green;'>✓ Подключение к серверу MySQL успешно</p>";
            
            // Проверяем существование базы данных
            $result = $test_link->query("SHOW DATABASES LIKE '$database'");
            if ($result->num_rows > 0) {
                echo "<p style='color: green;'>✓ База данных '$database' существует</p>";
            } else {
                echo "<p style='color: red;'>✗ База данных '$database' не существует</p>";
            }
            
            $test_link->close();
        } else {
            echo "<p style='color: red;'>✗ Ошибка подключения к серверу MySQL: " . $test_link->connect_error . "</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Ошибка при тестировании подключения к серверу: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";
echo "<p><a href='/stud/admin_upload'>Вернуться к загрузке файлов</a></p>";
?> 