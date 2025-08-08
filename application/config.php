<?php
// Database configuration
if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', 'gtecbks_db');
if (!defined('DB_USER')) define('DB_USER', 'gtecbks_user');
if (!defined('DB_PASS')) define('DB_PASS', 'H6e7V3u5');

// Directory constants
if (!defined('ENGINE_DIR')) define('ENGINE_DIR', __DIR__ . '/../engine/');
if (!defined('APPLICATION_DIR')) define('APPLICATION_DIR', __DIR__ . '/');

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
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_TIMEOUT => 5, // Добавляем таймаут
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
            $pdo->exec("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
            return ['type' => 'pdo', 'connection' => $pdo];
        } catch (PDOException $e) {
            // Пробуем альтернативные настройки
            try {
                $pdo = new PDO(
                    "mysql:host=localhost;dbname=" . DB_NAME . ";charset=utf8",
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_TIMEOUT => 5,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    ]
                );
                $pdo->exec("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
                return ['type' => 'pdo', 'connection' => $pdo];
            } catch (PDOException $e2) {
                throw new Exception("Database connection failed: " . $e2->getMessage());
            }
        }
    } elseif (extension_loaded('mysqli')) {
        try {
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($mysqli->connect_error) {
                // Пробуем localhost
                $mysqli = new mysqli('localhost', DB_USER, DB_PASS, DB_NAME);
                if ($mysqli->connect_error) {
                    throw new Exception("Database connection failed: " . $mysqli->connect_error);
                }
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

if (!defined('SITE_NAME')) define('SITE_NAME', 'NoContrGtec');
if (!defined('SITE_URL')) define('SITE_URL', 'http://localhost:3000');
if (!defined('ADMIN_EMAIL')) define('ADMIN_EMAIL', 'gtecsp1@gmail.com');

if (!defined('SESSION_NAME')) define('SESSION_NAME', 'nocontrgtec_session');
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 3600);

if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', __DIR__ . '/../uploads/');
if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
if (!defined('ALLOWED_EXTENSIONS')) define('ALLOWED_EXTENSIONS', serialize(['jpg', 'rtf', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']));

if (!defined('CSRF_TOKEN_NAME')) define('CSRF_TOKEN_NAME', 'csrf_token');
if (!defined('PASSWORD_SALT')) define('PASSWORD_SALT', 'nocontrgtec_salt_2024');

if (!defined('DEBUG_MODE')) define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?> 