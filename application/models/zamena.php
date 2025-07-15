<?php

class zamenaModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getLastZamenas() {
        $sql = "SELECT * FROM `zamena` ORDER BY zamena_date_add DESC LIMIT 1";
        return $this->db->fetch($sql);
    }
    
    public function createZamena($data) {
        return $this->db->insert('zamena', [
            'zamena_text' => $data['zamena_text'],
            'zamena_file' => $data['zamena_file'],
            'zamena_date_add' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function updateZamena($zamenaId, $data = []) {
        return $this->db->update('zamena', $data, 'zamena_id = :zamena_id', ['zamena_id' => (int)$zamenaId]);
    }
    
    public function deleteZamena($zamenaId) {
        return $this->db->delete('zamena', 'zamena_id = :zamena_id', ['zamena_id' => (int)$zamenaId]);
    }
    
    public function getZamenaById($zamenaId) {
        $sql = "SELECT * FROM `zamena` WHERE zamena_id = :zamena_id LIMIT 1";
        return $this->db->fetch($sql, ['zamena_id' => (int)$zamenaId]);
    }
    
    public function getAllZamenas($limit = null) {
        $sql = "SELECT * FROM `zamena` ORDER BY zamena_date_add DESC";
        if ($limit) {
            $sql .= " LIMIT :limit";
            return $this->db->fetchAll($sql, ['limit' => (int)$limit]);
        }
        return $this->db->fetchAll($sql);
    }
} 