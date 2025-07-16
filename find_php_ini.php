<?php
echo "<h1>PHP Configuration Helper</h1>";

// Find php.ini location
$ini_file = php_ini_loaded_file();
echo "<h2>Current php.ini location:</h2>";
if ($ini_file) {
    echo "✅ Found: $ini_file<br>";
    
    // Check if file is writable
    if (is_writable($ini_file)) {
        echo "✅ File is writable<br>";
    } else {
        echo "❌ File is NOT writable (run as administrator)<br>";
    }
    
    // Read current extensions
    echo "<h3>Current extension settings:</h3>";
    $ini_content = file_get_contents($ini_file);
    $lines = explode("\n", $ini_content);
    
    $pdo_mysql_found = false;
    $mysqli_found = false;
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, 'extension=pdo_mysql') !== false) {
            echo "Found: $line<br>";
            if (strpos($line, ';') === 0) {
                echo "⚠️ pdo_mysql is commented out (disabled)<br>";
            } else {
                echo "✅ pdo_mysql is enabled<br>";
                $pdo_mysql_found = true;
            }
        }
        if (strpos($line, 'extension=mysqli') !== false) {
            echo "Found: $line<br>";
            if (strpos($line, ';') === 0) {
                echo "⚠️ mysqli is commented out (disabled)<br>";
            } else {
                echo "✅ mysqli is enabled<br>";
                $mysqli_found = true;
            }
        }
    }
    
    if (!$pdo_mysql_found) {
        echo "<h3>Add this line to php.ini:</h3>";
        echo "<code>extension=pdo_mysql</code><br>";
    }
    
    if (!$mysqli_found) {
        echo "<h3>Add this line to php.ini:</h3>";
        echo "<code>extension=mysqli</code><br>";
    }
    
} else {
    echo "❌ No php.ini file found<br>";
}

// Show all possible php.ini locations
echo "<h2>Common php.ini locations:</h2>";
$possible_locations = [
    'C:\OSPanel\modules\php\PHP_8.2\php.ini',
    'C:\OSPanel\modules\php\PHP_8.1\php.ini',
    'C:\OSPanel\modules\php\PHP_8.0\php.ini',
    'C:\OSPanel\modules\php\PHP_7.4\php.ini',
    'C:\xampp\php\php.ini',
    'C:\wamp\bin\php\php8.2.4\php.ini',
    'C:\wamp64\bin\php\php8.2.4\php.ini'
];

foreach ($possible_locations as $location) {
    if (file_exists($location)) {
        echo "✅ Found: $location<br>";
    }
}

echo "<h2>Instructions:</h2>";
echo "1. Open the php.ini file in a text editor<br>";
echo "2. Find the extensions section<br>";
echo "3. Uncomment or add these lines:<br>";
echo "   <code>extension=pdo_mysql</code><br>";
echo "   <code>extension=mysqli</code><br>";
echo "4. Save the file<br>";
echo "5. Restart your web server<br>";
?> 