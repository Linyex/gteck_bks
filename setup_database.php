<?php
require_once 'application/config.php';

echo "<h1>Database Setup</h1>";

try {
    echo "<h2>1. Connecting to MySQL server</h2>";
    
    // First connect without database to create it
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "✅ Connected to MySQL server<br>";
    
    echo "<h2>2. Creating database</h2>";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8 COLLATE utf8_general_ci");
    echo "✅ Database '" . DB_NAME . "' created/verified<br>";
    
    // Connect to the specific database
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "<h2>3. Creating tables</h2>";
    
    // Read and execute setup SQL
    $setupSql = file_get_contents('database_setup.sql');
    $queries = explode(';', $setupSql);
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                $pdo->exec($query);
                $successCount++;
                echo "✅ Executed: " . substr($query, 0, 50) . "...<br>";
            } catch (PDOException $e) {
                $errorCount++;
                echo "⚠️ Query failed: " . $e->getMessage() . "<br>";
                echo "Query: " . substr($query, 0, 100) . "...<br>";
            }
        }
    }
    
    echo "<h2>4. Setup Summary</h2>";
    echo "Successful queries: $successCount<br>";
    echo "Failed queries: $errorCount<br>";
    
    if ($errorCount == 0) {
        echo "<h3>✅ Database setup completed successfully!</h3>";
        
        // Check tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll();
        echo "Created " . count($tables) . " tables:<br>";
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            echo "- $tableName<br>";
        }
        
        echo "<h3>Next Steps:</h3>";
        echo "<a href='index.php'>Go to main site</a><br>";
        echo "<a href='test_connection.php'>Test connection</a><br>";
    } else {
        echo "<h3>⚠️ Some queries failed. Please check the errors above.</h3>";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ Database Setup Failed</h2>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "<h3>Please check:</h3>";
    echo "1. MySQL server is running<br>";
    echo "2. Username and password are correct<br>";
    echo "3. User has CREATE DATABASE privileges<br>";
}
?> 