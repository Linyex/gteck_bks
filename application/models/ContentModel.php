<?php

class ContentModel {
    public function __construct() {
        $this->ensureTable();
    }

    private function normalizePathInternal(string $path): string {
        $path = trim($path);
        // Отбрасываем протокол/домен и query/fragment
        $onlyPath = parse_url($path, PHP_URL_PATH);
        if ($onlyPath === null || $onlyPath === false) { $onlyPath = '/'; }
        if ($onlyPath === '') { $onlyPath = '/'; }
        // Убираем trailing slash (кроме корня)
        if ($onlyPath !== '/' && substr($onlyPath, -1) === '/') {
            $onlyPath = rtrim($onlyPath, '/');
        }
        // Гарантируем ведущий слэш
        if ($onlyPath[0] !== '/') { $onlyPath = '/' . $onlyPath; }
        return $onlyPath;
    }

    private function ensureTable() {
        // Создаем таблицу при первом обращении, если её нет
        Database::execute("CREATE TABLE IF NOT EXISTS content_overrides (
            id INT AUTO_INCREMENT PRIMARY KEY,
            path VARCHAR(255) NOT NULL,
            css_selector TEXT NOT NULL,
            content MEDIUMTEXT NOT NULL,
            is_html TINYINT(1) DEFAULT 0,
            updated_by INT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_path (path)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function listOverrides($limit = 200) {
        return Database::fetchAll(
            "SELECT * FROM content_overrides ORDER BY updated_at DESC LIMIT :limit",
            ['limit' => (int)$limit]
        );
    }

    public function getOverridesForPath(string $path) {
        $norm = $this->normalizePathInternal($path);
        return $this->getOverridesForPaths([$norm]);
    }

    public function getOverridesForPaths(array $paths) {
        if (empty($paths)) { return []; }
        $normalized = array_values(array_unique(array_map(function($p){ return $this->normalizePathInternal((string)$p); }, $paths)));
        // Также добавляем альтернативу с/без завершающего слеша на случай ранее сохранённых записей
        $alts = [];
        foreach ($normalized as $p) {
            if ($p === '/') { continue; }
            $alts[] = $p . '/';
        }
        $all = array_values(array_unique(array_merge($normalized, $alts)));
        // Готовим плейсхолдеры
        $in = [];
        $params = [];
        foreach ($all as $i => $p) {
            $key = ":p{$i}";
            $in[] = $key;
            $params[substr($key,1)] = $p;
        }
        $sql = "SELECT * FROM content_overrides WHERE path = '*'";
        if (!empty($in)) {
            $sql .= " OR path IN (" . implode(',', $in) . ")";
        }
        $sql .= " ORDER BY CASE WHEN path='*' THEN 0 ELSE 1 END, id ASC";
        return Database::fetchAll($sql, $params);
    }

    public function saveOverride(string $path, string $selector, string $content, int $isHtml = 0, ?int $userId = null) {
        $pathNorm = $this->normalizePathInternal($path);
        return Database::insert('content_overrides', [
            'path' => $pathNorm,
            'css_selector' => $selector,
            'content' => $content,
            'is_html' => $isHtml,
            'updated_by' => $userId
        ]);
    }

    public function deleteOverride(int $id) {
        return Database::delete('content_overrides', 'id = :id', ['id' => $id]);
    }
}


