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
        $sql = "SELECT news.*, nc.name AS category_name, nc.type AS category_slug FROM `news` news";
        
        // Добавляем JOIN'ы
        foreach ($joins as $join) {
            switch ($join) {
                case "category":
                    $sql .= " LEFT JOIN news_categories nc ON news.category_id = nc.id";
                    break;
            }
        }
        
        // Добавляем WHERE условия
        if (!empty($data)) {
            $sql .= " WHERE nc.type = :category_slug";
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
            $params['category_slug'] = $data;
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
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['count'];
    }
    
    public function getTotalNewsCat($data, $joins = []) {
        $sql = "SELECT COUNT(*) AS count FROM `news` news";
        
        foreach ($joins as $join) {
            switch ($join) {
                case "category":
                    $sql .= " LEFT JOIN news_categories nc ON news.category_id = nc.id";
                    break;
            }
        }
        
        if (!empty($data)) {
            $sql .= " WHERE nc.type = :category_slug";
        }
        
        $params = [];
        if (!empty($data)) {
            $params['category_slug'] = $data;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['count'];
    }
    
    public function getNewsById($newsId, $joins = []) {
        $sql = "SELECT news.*, nc.name AS category_name, nc.type AS category_slug FROM `news` news";
        
        foreach ($joins as $join) {
            switch ($join) {
                case "category":
                    $sql .= " LEFT JOIN news_categories nc ON news.category_id = nc.id";
                    break;
            }
        }
        
        $sql .= " WHERE news.news_id = :news_id LIMIT 1";
        
        return $this->db->fetchOne($sql, ['news_id' => (int)$newsId]);
    }

    // Соседние новости (предыдущая/следующая) относительно даты публикации
    public function getPrevNext($newsId) {
        // Получаем текущую дату новости
        $current = $this->db->fetchOne("SELECT news_date_add FROM news WHERE news_id = :id", ['id' => (int)$newsId]);
        if (!$current) return ['prev' => null, 'next' => null];
        $date = $current['news_date_add'];
        
        $prev = $this->db->fetchOne(
            "SELECT news_id, news_title FROM news WHERE news_date_add > :date ORDER BY news_date_add ASC LIMIT 1",
            ['date' => $date]
        );
        $next = $this->db->fetchOne(
            "SELECT news_id, news_title FROM news WHERE news_date_add < :date ORDER BY news_date_add DESC LIMIT 1",
            ['date' => $date]
        );
        return ['prev' => $prev, 'next' => $next];
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
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['count'];
    }
    
    public function getCategoryByName($categoryName) {
        // Имя категории для публичной части — slug (type) из news_categories
        $sql = "SELECT id AS category_id, name AS category_name, type AS category_slug FROM `news_categories` WHERE type = :category_slug LIMIT 1";
        return $this->db->fetchOne($sql, ['category_slug' => $categoryName]);
    }
    
    /**
     * Возвращает список всех категорий новостей
     */
    public function getAllCategories() {
        try {
            $sql = "SELECT id AS category_id, name, type FROM news_categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            error_log('Error in getAllCategories: ' . $e->getMessage());
            return [];
        }
    }
    
    public function getLastNews() {
        $sql = "SELECT news_id AS count FROM news ORDER BY news_id DESC LIMIT 1";
        $result = $this->db->fetchOne($sql);
        return $result['count'];
    }
    
    /**
     * Получает важные новости с оптимизацией
     */
    public function getImportantNews($limit = 6) {
        try {
            $sql = "SELECT n.news_id, n.news_title, n.news_text, n.news_image, n.news_date_add, nc.name AS category_name, nc.type AS category_slug 
                    FROM news n 
                    LEFT JOIN news_categories nc ON n.category_id = nc.id 
                    WHERE n.category_id IN (1, 5, 6) 
                    ORDER BY n.news_date_add DESC 
                    LIMIT :limit";
            
            return $this->db->fetchAll($sql, ['limit' => (int)$limit]);
        } catch (Exception $e) {
            error_log('Error in getImportantNews: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Получает обычные новости с оптимизацией
     */
    public function getRegularNews($limit = 4) {
        try {
            $sql = "SELECT n.news_id, n.news_title, n.news_text, n.news_image, n.news_date_add, nc.name AS category_name, nc.type AS category_slug 
                    FROM news n 
                    LEFT JOIN news_categories nc ON n.category_id = nc.id 
                    WHERE n.category_id NOT IN (1, 5, 6) 
                    ORDER BY n.news_date_add DESC 
                    LIMIT :limit";
            
            return $this->db->fetchAll($sql, ['limit' => (int)$limit]);
        } catch (Exception $e) {
            error_log('Error in getRegularNews: ' . $e->getMessage());
            return [];
        }
    }
    
    // Получение всех новостей с пагинацией
    public function getAllNewsWithPagination($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT n.*, nc.name AS category_name, nc.type AS category_slug 
                FROM news n 
                LEFT JOIN news_categories nc ON n.category_id = nc.id 
                ORDER BY n.news_date_add DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            'limit' => (int)$limit,
            'offset' => (int)$offset
        ]);
    }
    
    // Получение новостей по категории
    public function getNewsByCategory($categoryId, $limit = 10) {
        $sql = "SELECT n.*, nc.name AS category_name, nc.type AS category_slug 
                FROM news n 
                LEFT JOIN news_categories nc ON n.category_id = nc.id 
                WHERE n.category_id = :category_id 
                ORDER BY n.news_date_add DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [
            'category_id' => (int)$categoryId,
            'limit' => (int)$limit
        ]);
    }
    
    // Получение новостей по категории с пагинацией
    public function getNewsByCategoryPaginated($categoryId, $start = 0, $limit = 10) {
        $sql = "SELECT n.*, nc.name AS category_name, nc.type AS category_slug 
                FROM news n 
                LEFT JOIN news_categories nc ON n.category_id = nc.id 
                WHERE n.category_id = :category_id 
                ORDER BY n.news_date_add DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            'category_id' => (int)$categoryId,
            'limit' => (int)$limit,
            'offset' => (int)$start
        ]);
    }
    
    // Подсчёт новостей по slug категории (поддержка старой таблицы category)
    public function countNewsBySlug(string $slug): int {
        $sql = "SELECT COUNT(*) AS count
                FROM news n
                LEFT JOIN news_categories nc ON n.category_id = nc.id
                LEFT JOIN category c ON n.category_id = c.category_id
                WHERE (nc.type = :slug OR c.category_name = :slug)";
        $row = $this->db->fetchOne($sql, ['slug' => $slug]);
        return (int)($row['count'] ?? 0);
    }
    
    // Выборка новостей по slug категории с пагинацией (поддержка старой таблицы category)
    public function getNewsBySlugPaginated(string $slug, int $start = 0, int $limit = 10): array {
        $sql = "SELECT n.*, COALESCE(nc.name, c.category_text, c.category_name) AS category_name, 
                       COALESCE(nc.type, :slug) AS category_slug
                FROM news n
                LEFT JOIN news_categories nc ON n.category_id = nc.id
                LEFT JOIN category c ON n.category_id = c.category_id
                WHERE (nc.type = :slug OR c.category_name = :slug)
                ORDER BY n.news_date_add DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, [
            'slug' => $slug,
            'limit' => (int)$limit,
            'offset' => (int)$start
        ]);
    }
    
    // Получение отображаемого имени категории по slug
    public function getCategoryDisplayNameBySlug(string $slug): string {
        // Пробуем новую таблицу
        $nc = $this->db->fetchOne("SELECT name FROM news_categories WHERE type = :slug LIMIT 1", ['slug' => $slug]);
        if (!empty($nc['name'])) {
            return $nc['name'];
        }
        // Фолбэк на старую
        $old = $this->db->fetchOne("SELECT COALESCE(category_text, category_name) AS name FROM category WHERE category_name = :slug OR category_text = :slug LIMIT 1", ['slug' => $slug]);
        if (!empty($old['name'])) {
            return $old['name'];
        }
        return 'Категория';
    }
    
    // Поиск новостей
    public function searchNews($searchText, $limit = 10) {
        $sql = "SELECT n.*, nc.name AS category_name, nc.type AS category_slug 
                FROM news n 
                LEFT JOIN news_categories nc ON n.category_id = nc.id 
                WHERE n.news_title LIKE :search OR n.news_text LIKE :search 
                ORDER BY n.news_date_add DESC 
                LIMIT :limit";
        
        $searchTerm = '%' . $searchText . '%';
        
        return $this->db->fetchAll($sql, [
            'search' => $searchTerm,
            'limit' => (int)$limit
        ]);
    }
    
    // Получение дополнительных изображений новости
    public function getNewsAdditionalImages($newsId) {
        try {
            $sql = "SELECT * FROM news_img WHERE news_id = :news_id ORDER BY img_order ASC";
            return $this->db->fetchAll($sql, ['news_id' => (int)$newsId]);
        } catch (Exception $e) {
            error_log('Error in getNewsAdditionalImages: ' . $e->getMessage());
            return [];
        }
    }
    
    // Получение новости с дополнительными изображениями
    public function getNewsWithImages($newsId, $joins = []) {
        try {
            $news = $this->getNewsById($newsId, $joins);
            if ($news) {
                try {
                    $news['additional_images'] = $this->getNewsAdditionalImages($newsId);
                } catch (Exception $e) {
                    // Если таблица дополнительных изображений не существует, используем пустой массив
                    error_log('Error getting additional images: ' . $e->getMessage());
                    $news['additional_images'] = [];
                }
            }
            return $news;
        } catch (Exception $e) {
            error_log('Error in getNewsWithImages: ' . $e->getMessage());
            return null;
        }
    }
}
