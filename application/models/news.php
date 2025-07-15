<?php

class newsModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function createNews($data) {
        return $this->db->insert('news', [
            'user_id' => (int)$data['user_id'],
            'news_title' => $data['news_title'],
            'news_text' => $data['news_text'],
            'news_image' => $data['news_image'],
            'category_id' => $data['category_id'],
            'news_date_add' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function deleteNews($newsId) {
        return $this->db->delete('news', 'news_id = :news_id', ['news_id' => (int)$newsId]);
    }
    
    public function deleteAllNews($newsId) {
        return $this->db->delete('news_img', 'news_id = :news_id', ['news_id' => (int)$newsId]);
    }
    
    public function updateNews($newsId, $data = []) {
        return $this->db->update('news', $data, 'news_id = :news_id', ['news_id' => (int)$newsId]);
    }
    
    public function getnews($data = [], $joins = [], $sort = [], $options = []) {
        $sql = "SELECT * FROM `news`";
        
        // Добавляем JOIN'ы
        foreach ($joins as $join) {
            switch ($join) {
                case "category":
                    $sql .= " LEFT JOIN category ON news.category_id = category.category_id";
                    break;
            }
        }
        
        // Добавляем WHERE условия
        if (!empty($data)) {
            $sql .= " WHERE category.category_name = :category_name";
        }
        
        // Добавляем сортировку
        if (!empty($sort)) {
            $orderParts = [];
            foreach ($sort as $key => $value) {
                $orderParts[] = "{$key} {$value}";
            }
            $sql .= " ORDER BY " . implode(', ', $orderParts);
        }
        
        // Добавляем лимиты
        if (!empty($options)) {
            if ($options['start'] < 0) {
                $options['start'] = 0;
            }
            if ($options['limit'] < 1) {
                $options['limit'] = 20;
            }
            $sql .= " LIMIT :start, :limit";
        }
        
        $params = [];
        if (!empty($data)) {
            $params['category_name'] = $data;
        }
        if (!empty($options)) {
            $params['start'] = (int)$options['start'];
            $params['limit'] = (int)$options['limit'];
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getTotalnews($data = []) {
        $sql = "SELECT COUNT(*) AS count FROM `news`";
        $params = [];
        
        if (!empty($data)) {
            $whereParts = [];
            foreach ($data as $key => $value) {
                $whereParts[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'];
    }
    
    public function getTotalNewsCat($data, $joins = []) {
        $sql = "SELECT COUNT(*) AS count FROM `news`";
        
        foreach ($joins as $join) {
            switch ($join) {
                case "category":
                    $sql .= " LEFT JOIN category ON news.category_id = category.category_id";
                    break;
            }
        }
        
        if (!empty($data)) {
            $sql .= " WHERE category.category_name = :category_name";
        }
        
        $params = [];
        if (!empty($data)) {
            $params['category_name'] = $data;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'];
    }
    
    public function getNewsById($newsId, $joins = []) {
        $sql = "SELECT * FROM `news`";
        
        foreach ($joins as $join) {
            switch ($join) {
                case "category":
                    $sql .= " LEFT JOIN category ON news.category_id = category.category_id";
                    break;
            }
        }
        
        $sql .= " WHERE news_id = :news_id LIMIT 1";
        
        return $this->db->fetch($sql, ['news_id' => (int)$newsId]);
    }
    
    public function getTotalCategory($data = []) {
        $sql = "SELECT COUNT(*) AS count FROM `category`";
        $params = [];
        
        if (!empty($data)) {
            $whereParts = [];
            foreach ($data as $key => $value) {
                $whereParts[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'];
    }
    
    public function getCategoryByName($categoryName) {
        $sql = "SELECT * FROM `category` WHERE category_name = :category_name LIMIT 1";
        return $this->db->fetch($sql, ['category_name' => $categoryName]);
    }
    
    public function getLastNews() {
        $sql = "SELECT news_id AS count FROM news ORDER BY news_id DESC LIMIT 1";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
}
