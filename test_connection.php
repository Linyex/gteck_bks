<?php
require_once 'application/config.php';

echo "<h1>Database Connection Test</h1>";

try {
    echo "<h2>Testing PDO Connection</h2>";
    
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    echo "✅ PDO connection successful!<br>";
    echo "Server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "<br>";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "✅ Test query successful: " . $result['test'] . "<br>";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
    $databases = $stmt->fetchAll();
    
    if (count($databases) > 0) {
        echo "✅ Database '" . DB_NAME . "' exists<br>";
        
        // Check tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll();
        echo "Found " . count($tables) . " tables in database<br>";
        
        if (count($tables) > 0) {
            echo "<h3>Available tables:</h3>";
            foreach ($tables as $table) {
                $tableName = array_values($table)[0];
                echo "- $tableName<br>";
            }
        }
    } else {
        echo "⚠️ Database '" . DB_NAME . "' does not exist<br>";
        echo "<a href='setup_database.php'>Click here to create database and tables</a><br>";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ PDO Connection Failed</h2>";
    echo "Error: " . $e->getMessage() . "<br>";
    
    echo "<h3>Testing MySQLi as fallback:</h3>";
    try {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_error) {
            echo "❌ MySQLi connection failed: " . $mysqli->connect_error . "<br>";
        } else {
            echo "✅ MySQLi connection successful!<br>";
            $mysqli->close();
        }
    } catch (Exception $e) {
        echo "❌ MySQLi error: " . $e->getMessage() . "<br>";
    }
}

echo "<h2>Configuration Summary:</h2>";
echo "Host: " . DB_HOST . "<br>";
echo "Database: " . DB_NAME . "<br>";
echo "User: " . DB_USER . "<br>";
echo "Password: " . (DB_PASS ? '[SET]' : '[EMPTY]') . "<br>";

echo "<h2>Next Steps:</h2>";
echo "<a href='index.php'>Go to main site</a><br>";
echo "<a href='setup_database.php'>Setup database</a><br>";
?> 