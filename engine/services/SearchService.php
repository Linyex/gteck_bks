<?php

/**
 * Сервис поиска
 * Обеспечивает полнотекстовый поиск и автодополнение
 */
class SearchService {
    
    private $searchIndex = [];
    private $indexFile = 'search_index.json';
    private $maxResults = 50;
    private $minScore = 0.1;
    
    public function __construct() {
        $this->loadSearchIndex();
    }
    
    /**
     * Выполняет поиск по запросу
     */
    public function search($query, $filters = [], $limit = null) {
        try {
            if (empty($query)) {
                return ['results' => [], 'total' => 0];
            }
            
            $limit = $limit ?: $this->maxResults;
            $query = $this->normalizeQuery($query);
            $tokens = $this->tokenize($query);
            
            $results = [];
            $scores = [];
            
            // Поиск по индексу
            foreach ($this->searchIndex as $type => $items) {
                if (!empty($filters['type']) && $filters['type'] !== $type) {
                    continue;
                }
                
                foreach ($items as $id => $item) {
                    $score = $this->calculateScore($item, $tokens, $query);
                    
                    if ($score >= $this->minScore) {
                        $results[] = [
                            'id' => $id,
                            'type' => $type,
                            'title' => $item['title'],
                            'description' => $item['description'],
                            'url' => $item['url'],
                            'score' => $score,
                            'highlighted' => $this->highlightMatches($item['content'], $tokens)
                        ];
                        $scores[] = $score;
                    }
                }
            }
            
            // Сортируем по релевантности
            array_multisort($scores, SORT_DESC, $results);
            
            // Ограничиваем результаты
            $results = array_slice($results, 0, $limit);
            
            return [
                'results' => $results,
                'total' => count($results),
                'query' => $query
            ];
            
        } catch (Exception $e) {
            return [
                'results' => [],
                'total' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Автодополнение
     */
    public function autocomplete($query, $limit = 10) {
        try {
            if (empty($query)) {
                return [];
            }
            
            $query = $this->normalizeQuery($query);
            $suggestions = [];
            
            foreach ($this->searchIndex as $type => $items) {
                foreach ($items as $id => $item) {
                    $title = strtolower($item['title']);
                    $description = strtolower($item['description']);
                    
                    if (strpos($title, $query) === 0 || strpos($description, $query) === 0) {
                        $suggestions[] = [
                            'text' => $item['title'],
                            'type' => $type,
                            'url' => $item['url']
                        ];
                        
                        if (count($suggestions) >= $limit) {
                            break 2;
                        }
                    }
                }
            }
            
            return array_slice($suggestions, 0, $limit);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Индексирует контент
     */
    public function indexContent($type, $id, $title, $content, $url, $description = '') {
        try {
            $normalizedContent = $this->normalizeContent($content);
            $tokens = $this->tokenize($normalizedContent);
            
            $this->searchIndex[$type][$id] = [
                'title' => $title,
                'content' => $normalizedContent,
                'description' => $description,
                'url' => $url,
                'tokens' => $tokens,
                'indexed_at' => date('Y-m-d H:i:s')
            ];
            
            $this->saveSearchIndex();
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Удаляет элемент из индекса
     */
    public function removeFromIndex($type, $id) {
        try {
            if (isset($this->searchIndex[$type][$id])) {
                unset($this->searchIndex[$type][$id]);
                $this->saveSearchIndex();
            }
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Обновляет индекс
     */
    public function updateIndex($type, $id, $title, $content, $url, $description = '') {
        return $this->indexContent($type, $id, $title, $content, $url, $description);
    }
    
    /**
     * Переиндексирует всю базу данных
     */
    public function reindexAll() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $this->searchIndex = [];
            
            // Индексируем новости
            $news = Database::fetchAll("SELECT news_id, news_title, news_text FROM news");
            foreach ($news as $item) {
                $this->indexContent(
                    'news',
                    $item['news_id'],
                    $item['news_title'],
                    $item['news_text'],
                    "/news/view/{$item['news_id']}"
                );
            }
            
            // Индексируем пользователей
            $users = Database::fetchAll("SELECT user_id, user_login, user_fio FROM users");
            foreach ($users as $item) {
                $this->indexContent(
                    'users',
                    $item['user_id'],
                    $item['user_fio'] ?: $item['user_login'],
                    $item['user_fio'] . ' ' . $item['user_login'],
                    "/admin/users/view/{$item['user_id']}"
                );
            }
            
            // Индексируем файлы
            $files = Database::fetchAll("SELECT file_id, file_name, file_description FROM files");
            foreach ($files as $item) {
                $this->indexContent(
                    'files',
                    $item['file_id'],
                    $item['file_name'],
                    $item['file_description'] . ' ' . $item['file_name'],
                    "/admin/files/view/{$item['file_id']}"
                );
            }
            
            $this->saveSearchIndex();
            
            return [
                'success' => true,
                'indexed_items' => count($this->searchIndex)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Нормализует запрос
     */
    private function normalizeQuery($query) {
        $query = mb_strtolower(trim($query));
        $query = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $query);
        $query = preg_replace('/\s+/', ' ', $query);
        
        return trim($query);
    }
    
    /**
     * Нормализует контент
     */
    private function normalizeContent($content) {
        $content = strip_tags($content);
        $content = mb_strtolower($content);
        $content = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $content);
        $content = preg_replace('/\s+/', ' ', $content);
        
        return trim($content);
    }
    
    /**
     * Токенизирует текст
     */
    private function tokenize($text) {
        $tokens = explode(' ', $text);
        $tokens = array_filter($tokens, function($token) {
            return mb_strlen($token) >= 2;
        });
        
        return array_unique($tokens);
    }
    
    /**
     * Вычисляет релевантность
     */
    private function calculateScore($item, $queryTokens, $fullQuery) {
        $score = 0;
        $content = $item['content'];
        $title = mb_strtolower($item['title']);
        $description = mb_strtolower($item['description']);
        
        // Точное совпадение в заголовке
        if (strpos($title, $fullQuery) !== false) {
            $score += 10;
        }
        
        // Точное совпадение в описании
        if (strpos($description, $fullQuery) !== false) {
            $score += 5;
        }
        
        // Совпадения токенов в заголовке
        foreach ($queryTokens as $token) {
            if (strpos($title, $token) !== false) {
                $score += 3;
            }
        }
        
        // Совпадения токенов в описании
        foreach ($queryTokens as $token) {
            if (strpos($description, $token) !== false) {
                $score += 2;
            }
        }
        
        // Совпадения токенов в контенте
        foreach ($queryTokens as $token) {
            $count = substr_count($content, $token);
            $score += $count * 0.5;
        }
        
        // Бонус за длину совпадения
        $score += mb_strlen($fullQuery) * 0.1;
        
        return $score;
    }
    
    /**
     * Подсвечивает совпадения
     */
    private function highlightMatches($content, $tokens) {
        $highlighted = $content;
        
        foreach ($tokens as $token) {
            $highlighted = str_ireplace(
                $token,
                "<mark>$token</mark>",
                $highlighted
            );
        }
        
        return $highlighted;
    }
    
    /**
     * Загружает индекс поиска
     */
    private function loadSearchIndex() {
        $indexPath = $this->indexFile;
        
        if (file_exists($indexPath)) {
            $indexData = file_get_contents($indexPath);
            $this->searchIndex = json_decode($indexData, true) ?: [];
        } else {
            $this->searchIndex = [];
        }
    }
    
    /**
     * Сохраняет индекс поиска
     */
    private function saveSearchIndex() {
        $indexData = json_encode($this->searchIndex, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->indexFile, $indexData);
    }
    
    /**
     * Получает статистику индекса
     */
    public function getIndexStats() {
        $stats = [
            'total_items' => 0,
            'types' => []
        ];
        
        foreach ($this->searchIndex as $type => $items) {
            $count = count($items);
            $stats['total_items'] += $count;
            $stats['types'][$type] = $count;
        }
        
        return $stats;
    }
    
    /**
     * Очищает индекс
     */
    public function clearIndex() {
        $this->searchIndex = [];
        $this->saveSearchIndex();
        
        return true;
    }
    
    /**
     * Поиск по типу контента
     */
    public function searchByType($type, $query, $limit = 20) {
        return $this->search($query, ['type' => $type], $limit);
    }
    
    /**
     * Получает популярные запросы
     */
    public function getPopularQueries($limit = 10) {
        // В реальной системе здесь была бы таблица с логами поиска
        return [
            'новости',
            'пользователи',
            'файлы',
            'админ',
            'настройки'
        ];
    }
    
    /**
     * Получает похожие элементы
     */
    public function getSimilarItems($type, $id, $limit = 5) {
        try {
            if (!isset($this->searchIndex[$type][$id])) {
                return [];
            }
            
            $currentItem = $this->searchIndex[$type][$id];
            $currentTokens = $currentItem['tokens'];
            
            $similar = [];
            
            foreach ($this->searchIndex[$type] as $itemId => $item) {
                if ($itemId == $id) {
                    continue;
                }
                
                $similarity = $this->calculateSimilarity($currentTokens, $item['tokens']);
                
                if ($similarity > 0.3) {
                    $similar[] = [
                        'id' => $itemId,
                        'title' => $item['title'],
                        'url' => $item['url'],
                        'similarity' => $similarity
                    ];
                }
            }
            
            // Сортируем по схожести
            usort($similar, function($a, $b) {
                return $b['similarity'] <=> $a['similarity'];
            });
            
            return array_slice($similar, 0, $limit);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Вычисляет схожесть между наборами токенов
     */
    private function calculateSimilarity($tokens1, $tokens2) {
        $intersection = array_intersect($tokens1, $tokens2);
        $union = array_unique(array_merge($tokens1, $tokens2));
        
        if (empty($union)) {
            return 0;
        }
        
        return count($intersection) / count($union);
    }
} 