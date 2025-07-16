<?php
echo "<h2>PHP Extensions Check</h2>";

// Check if PDO is available
echo "<h3>PDO Extension:</h3>";
if (extension_loaded('pdo')) {
    echo "✅ PDO is loaded<br>";
    echo "Available PDO drivers: " . implode(', ', PDO::getAvailableDrivers()) . "<br>";
} else {
    echo "❌ PDO is NOT loaded<br>";
}

// Check if PDO MySQL is available
echo "<h3>PDO MySQL Driver:</h3>";
if (extension_loaded('pdo_mysql')) {
    echo "✅ PDO MySQL driver is loaded<br>";
} else {
    echo "❌ PDO MySQL driver is NOT loaded<br>";
}

// Check if MySQL extension is available (alternative)
echo "<h3>MySQL Extension:</h3>";
if (extension_loaded('mysqli')) {
    echo "✅ MySQLi extension is loaded<br>";
} else {
    echo "❌ MySQLi extension is NOT loaded<br>";
}

// Check PHP version
echo "<h3>PHP Version:</h3>";
echo "PHP Version: " . phpversion() . "<br>";

// Check loaded extensions
echo "<h3>All Loaded Extensions:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<ul>";
foreach ($extensions as $ext) {
    if (strpos($ext, 'pdo') !== false || strpos($ext, 'mysql') !== false) {
        echo "<li><strong>$ext</strong></li>";
    } else {
        echo "<li>$ext</li>";
    }
}
echo "</ul>";

// Check php.ini location
echo "<h3>PHP Configuration:</h3>";
echo "php.ini location: " . php_ini_loaded_file() . "<br>";
echo "Additional .ini files: " . php_ini_scanned_files() . "<br>";
?> 