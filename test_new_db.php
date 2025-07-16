<?php
require_once 'application/config.php';
require_once 'engine/main/db.php';

echo "<h1>Testing New Database Connection</h1>";

try {
    echo "<h2>1. Testing Database Connection</h2>";
    $connection = Database::getConnection();
    $type = Database::getConnectionType();
    
    echo "✅ Database connection successful!<br>";
    echo "Connection type: $type<br>";
    
    echo "<h2>2. Testing Simple Query</h2>";
    $result = Database::query("SELECT 1 as test");
    echo "✅ Query executed successfully<br>";
    
    echo "<h2>3. Testing Database Setup</h2>";
    
    // Check if tables exist
    $tables = Database::fetchAll("SHOW TABLES");
    echo "Found " . count($tables) . " tables in database<br>";
    
    if (count($tables) > 0) {
        echo "<h3>Available tables:</h3>";
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            echo "- $tableName<br>";
        }
    } else {
        echo "<h3>No tables found. Running database setup...</h3>";
        
        // Read and execute setup SQL
        $setupSql = file_get_contents('database_setup.sql');
        $queries = explode(';', $setupSql);
        
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                try {
                    Database::query($query);
                    echo "✅ Executed: " . substr($query, 0, 50) . "...<br>";
                } catch (Exception $e) {
                    echo "⚠️ Query failed: " . $e->getMessage() . "<br>";
                }
            }
        }
        
        echo "<h3>Database setup completed!</h3>";
    }
    
    echo "<h2>4. Testing User Authentication</h2>";
    $users = Database::fetchAll("SELECT * FROM users LIMIT 5");
    echo "Found " . count($users) . " users in database<br>";
    
    if (count($users) > 0) {
        echo "<h3>Sample users:</h3>";
        foreach ($users as $user) {
            echo "- " . $user['login'] . " (ID: " . $user['id'] . ")<br>";
        }
    }
    
    echo "<h2>✅ All tests passed! Your database is working correctly.</h2>";
    echo "<p><a href='index.php'>Go to main site</a></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
    echo "<h3>Please fix the php.ini file:</h3>";
    echo "<ol>";
    echo "<li>Open: C:\\php\\php.ini</li>";
    echo "<li>Find these lines and remove the semicolon (;):</li>";
    echo "<ul>";
    echo "<li>;extension=pdo_mysql</li>";
    echo "<li>;extension=mysqli</li>";
    echo "</ul>";
    echo "<li>Change to:</li>";
    echo "<ul>";
    echo "<li>extension=pdo_mysql</li>";
    echo "<li>extension=mysqli</li>";
    echo "</ul>";
    echo "<li>Save the file</li>";
    echo "<li>Restart your web server</li>";
    echo "</ol>";
}
?> 