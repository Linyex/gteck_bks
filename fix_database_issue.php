<?php
echo "<h1>Database Connection Issue Diagnosis</h1>";

// Check PDO and drivers
echo "<h2>1. PDO Status</h2>";
if (extension_loaded('pdo')) {
    echo "✅ PDO is loaded<br>";
    $drivers = PDO::getAvailableDrivers();
    if (empty($drivers)) {
        echo "❌ No PDO drivers available<br>";
        echo "This is the main issue!<br>";
    } else {
        echo "✅ Available drivers: " . implode(', ', $drivers) . "<br>";
    }
} else {
    echo "❌ PDO is not loaded<br>";
}

// Check specific MySQL extensions
echo "<h2>2. MySQL Extensions</h2>";
$extensions = ['pdo_mysql', 'mysqli', 'mysql'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext is loaded<br>";
    } else {
        echo "❌ $ext is NOT loaded<br>";
    }
}

// Check PHP version and configuration
echo "<h2>3. PHP Configuration</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "php.ini location: " . (php_ini_loaded_file() ?: 'Not found') . "<br>";

// Solutions
echo "<h2>4. Solutions</h2>";
echo "<h3>Option 1: Enable PDO MySQL in php.ini</h3>";
echo "1. Find your php.ini file<br>";
echo "2. Uncomment or add: extension=pdo_mysql<br>";
echo "3. Restart your web server<br>";

echo "<h3>Option 2: Use MySQLi instead of PDO</h3>";
echo "If PDO MySQL is not available, we can modify the code to use MySQLi.<br>";

echo "<h3>Option 3: Check OSPanel Configuration</h3>";
echo "Since you're using OSPanel, check:<br>";
echo "- OSPanel → Modules → PHP → Extensions<br>";
echo "- Make sure pdo_mysql is enabled<br>";

// Test alternative connection methods
echo "<h2>5. Testing Alternative Connection</h2>";
if (extension_loaded('mysqli')) {
    echo "Testing MySQLi connection...<br>";
    try {
        $mysqli = new mysqli('localhost', 'root', '', 'nocontrgtec');
        if ($mysqli->connect_error) {
            echo "❌ MySQLi connection failed: " . $mysqli->connect_error . "<br>";
        } else {
            echo "✅ MySQLi connection successful!<br>";
            $mysqli->close();
        }
    } catch (Exception $e) {
        echo "❌ MySQLi error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "MySQLi not available for testing<br>";
}

echo "<h2>6. Next Steps</h2>";
echo "1. Check OSPanel PHP extensions<br>";
echo "2. Enable pdo_mysql extension<br>";
echo "3. Restart OSPanel<br>";
echo "4. Test again<br>";
?> 