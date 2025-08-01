<?php
require_once __DIR__ . '/../../application/config.php';

class Database {
    private static $connection = null;
    private static $connectionType = null;
    
    public static function getConnection() {
        if (self::$connection === null) {
            try {
                $dbConfig = getDbConnection();
                self::$connection = $dbConfig['connection'];
                self::$connectionType = $dbConfig['type'];
            } catch (Exception $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
    
    public static function getConnectionType() {
        if (self::$connectionType === null) {
            self::getConnection(); // This will set the connection type
        }
        return self::$connectionType;
    }
    
    public static function query($sql, $params = []) {
        $connection = self::getConnection();
        $type = self::getConnectionType();
        
        if ($type === 'pdo') {
            return self::executePDOQuery($connection, $sql, $params);
        } else {
            return self::executeMySQLiQuery($connection, $sql, $params);
        }
    }
    
    private static function executePDOQuery($pdo, $sql, $params = []) {
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    private static function executeMySQLiQuery($mysqli, $sql, $params = []) {
        // Simple parameter replacement for MySQLi
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $value = $mysqli->real_escape_string($value);
                $sql = str_replace(":$key", "'$value'", $sql);
            }
        }
        
        $result = $mysqli->query($sql);
        if ($result === false) {
            throw new Exception("Query failed: " . $mysqli->error);
        }
        return $result;
    }
    
    public static function fetchAll($sql, $params = []) {
        $result = self::query($sql, $params);
        $type = self::getConnectionType();
        
        if ($type === 'pdo') {
            return $result->fetchAll();
        } else {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
    public static function fetchOne($sql, $params = []) {
        $result = self::query($sql, $params);
        $type = self::getConnectionType();
        
        if ($type === 'pdo') {
            return $result->fetch();
        } else {
            return $result->fetch_assoc();
        }
    }
    
    public static function fetch($sql, $params = []) {
        return self::fetchOne($sql, $params);
    }
    
    public static function execute($sql, $params = []) {
        $result = self::query($sql, $params);
        $type = self::getConnectionType();
        
        if ($type === 'pdo') {
            return $result->rowCount();
        } else {
            return $result->affected_rows;
        }
    }
    
    public static function lastInsertId() {
        $connection = self::getConnection();
        $type = self::getConnectionType();
        
        if ($type === 'pdo') {
            return $connection->lastInsertId();
        } else {
            return $connection->insert_id;
        }
    }
    
    public static function insert($table, $data) {
        $connection = self::getConnection();
        $type = self::getConnectionType();
        
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        
        $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES ($placeholders)";
        
        if ($type === 'pdo') {
            $stmt = $connection->prepare($sql);
            $stmt->execute($values);
            return $connection->lastInsertId();
        } else {
            // Для MySQLi нужно экранировать значения
            $escapedValues = [];
            foreach ($values as $value) {
                $escapedValues[] = $connection->real_escape_string($value);
            }
            $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES ('" . implode("', '", $escapedValues) . "')";
            $connection->query($sql);
            return $connection->insert_id;
        }
    }
}
?> 