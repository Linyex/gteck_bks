<?php
// Database configuration
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'gtecbks_db');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

// Check if PDO MySQL is available, otherwise use MySQLi
if (!function_exists('getDbConnection')) {
function getDbConnection() {
    if (extension_loaded('pdo_mysql')) {
        try {
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
            return ['type' => 'pdo', 'connection' => $pdo];
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    } elseif (extension_loaded('mysqli')) {
        try {
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($mysqli->connect_error) {
                throw new Exception("Database connection failed: " . $mysqli->connect_error);
            }
            $mysqli->set_charset("utf8");
            return ['type' => 'mysqli', 'connection' => $mysqli];
        } catch (Exception $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    } else {
        throw new Exception("No MySQL database driver available. Please enable pdo_mysql or mysqli extension in php.ini");
    }
}
}

// Site configuration
if (!defined('SITE_NAME')) define('SITE_NAME', 'NoContrGtec');
if (!defined('SITE_URL')) define('SITE_URL', 'http://localhost:3000');
if (!defined('ADMIN_EMAIL')) define('ADMIN_EMAIL', 'admin@nocontrgtec.com');

// Session configuration
if (!defined('SESSION_NAME')) define('SESSION_NAME', 'nocontrgtec_session');
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 3600); // 1 hour

// File upload configuration
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', __DIR__ . '/../uploads/');
if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
if (!defined('ALLOWED_EXTENSIONS')) define('ALLOWED_EXTENSIONS', serialize(['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']));

// Security configuration
if (!defined('CSRF_TOKEN_NAME')) define('CSRF_TOKEN_NAME', 'csrf_token');
if (!defined('PASSWORD_SALT')) define('PASSWORD_SALT', 'nocontrgtec_salt_2024');

// Error reporting (set to false in production)
if (!defined('DEBUG_MODE')) define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?> 