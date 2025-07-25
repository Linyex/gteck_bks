<?php

class Model {
    protected $db;
    
    public function __construct($db = null) {
        $this->db = $db;
    }
    
    // Метод для экранирования строк
    protected function escape($value) {
        if (is_string($value)) {
            return addslashes($value);
        }
        return $value;
    }
    
    // Метод для выполнения запроса
    protected function query($sql) {
        if ($this->db) {
            return $this->db->execute($sql);
        }
        return false;
    }
    
    // Метод для получения одной записи
    protected function fetchOne($sql, $params = []) {
        if ($this->db) {
            return $this->db->fetchOne($sql, $params);
        }
        return null;
    }
    
    // Метод для получения всех записей
    protected function fetchAll($sql, $params = []) {
        if ($this->db) {
            return $this->db->fetchAll($sql, $params);
        }
        return [];
    }
    
    // Метод для вставки данных
    protected function insert($table, $data) {
        if (!$this->db) return false;
        
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        return $this->db->execute($sql, $values);
    }
    
    // Метод для обновления данных
    protected function update($table, $data, $where, $whereParams = []) {
        if (!$this->db) return false;
        
        $setParts = [];
        $params = [];
        
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE {$where}";
        $params = array_merge($params, $whereParams);
        
        return $this->db->execute($sql, $params);
    }
    
    // Метод для удаления данных
    protected function delete($table, $where, $params = []) {
        if (!$this->db) return false;
        
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->db->execute($sql, $params);
    }
    
    // Метод для получения последнего вставленного ID
    protected function getLastId() {
        if ($this->db) {
            return $this->db->lastInsertId();
        }
        return null;
    }
} 