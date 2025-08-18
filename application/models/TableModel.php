<?php

class TableModel {
    private string $table = 'cms_tables';

    public function __construct() {
        $this->ensureTable();
    }

    private function ensureTable(): void {
        Database::execute("CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(190) NOT NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            description TEXT NULL,
            columns_json JSON NULL,
            rows_json LONGTEXT NULL,
            merges_json LONGTEXT NULL,
            is_public TINYINT(1) NOT NULL DEFAULT 1,
            updated_by INT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        // На случай существующей таблицы без merges_json — пытаемся добавить колонку
        try {
            Database::execute("ALTER TABLE {$this->table} ADD COLUMN merges_json LONGTEXT NULL");
        } catch (Throwable $e) { /* ignore if exists */ }
    }

    public function listAll(): array {
        return Database::fetchAll("SELECT id, slug, title, is_public, updated_at FROM {$this->table} ORDER BY updated_at DESC, id DESC");
    }

    public function getById(int $id): ?array {
        $row = Database::fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
        return $this->decode($row);
    }

    public function getBySlug(string $slug): ?array {
        $row = Database::fetchOne("SELECT * FROM {$this->table} WHERE slug = :slug", ['slug' => $slug]);
        return $this->decode($row);
    }

    public function create(string $title, string $slug, string $description = '', array $columns = [], array $rows = [], bool $isPublic = true, ?int $userId = null, array $merges = []): int {
        // Нормализуем и гарантируем уникальность slug
        $safeSlug = $this->generateUniqueSlug($slug, $title);
        return (int) Database::insert($this->table, [
            'title' => $title,
            'slug' => $safeSlug,
            'description' => $description,
            'columns_json' => json_encode($columns, JSON_UNESCAPED_UNICODE),
            'rows_json' => json_encode($rows, JSON_UNESCAPED_UNICODE),
            'merges_json' => json_encode($merges, JSON_UNESCAPED_UNICODE),
            'is_public' => $isPublic ? 1 : 0,
            'updated_by' => $userId
        ]);
    }

    public function updateMeta(int $id, string $title, string $slug, string $description, bool $isPublic, ?int $userId = null): bool {
        return Database::update($this->table, [
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'is_public' => $isPublic ? 1 : 0,
            'updated_by' => $userId
        ], 'id = :id', ['id' => $id]);
    }

    public function saveData(int $id, array $columns, array $rows, ?int $userId = null, array $merges = []): bool {
        return Database::update($this->table, [
            'columns_json' => json_encode($columns, JSON_UNESCAPED_UNICODE),
            'rows_json' => json_encode($rows, JSON_UNESCAPED_UNICODE),
            'merges_json' => json_encode($merges, JSON_UNESCAPED_UNICODE),
            'updated_by' => $userId
        ], 'id = :id', ['id' => $id]);
    }

    public function delete(int $id): bool {
        return Database::delete($this->table, 'id = :id', ['id' => $id]);
    }

    private function decode($row): ?array {
        if (!$row || !is_array($row)) return null;
        foreach (['columns_json','rows_json'] as $k) {
            if (isset($row[$k])) {
                $decoded = json_decode($row[$k], true);
                $row[$k] = is_array($decoded) ? $decoded : [];
            }
        }
        if (isset($row['merges_json'])) {
            $decoded = json_decode($row['merges_json'], true);
            $row['merges_json'] = is_array($decoded) ? $decoded : [];
        } else {
            $row['merges_json'] = [];
        }
        return $row;
    }

    // --- Helpers ---
    private function slugify(string $text): string {
        $text = trim($text);
        if ($text === '') return '';
        // Транслитерация, если доступно
        if (function_exists('iconv')) {
            $t = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
            if (is_string($t) && $t !== '') { $text = $t; }
        }
        $text = strtolower($text);
        $text = preg_replace('~[^a-z0-9]+~', '-', $text);
        $text = trim($text, '-');
        // Защита от пустоты
        return $text ?: '';
    }

    private function generateUniqueSlug(string $slug, string $title): string {
        $base = $this->slugify($slug);
        if ($base === '') {
            $fromTitle = $this->slugify($title);
            $base = $fromTitle !== '' ? $fromTitle : ('table-'.date('Ymd-His'));
        }
        $candidate = $base;
        $suffix = 2;
        while ($this->getBySlug($candidate) !== null) {
            $candidate = $base . '-' . $suffix;
            $suffix++;
        }
        return $candidate;
    }
}


