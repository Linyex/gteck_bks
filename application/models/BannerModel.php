<?php

class BannerModel {
	public function __construct() {
		$this->ensureTable();
	}

	private function ensureTable() {
		Database::execute("CREATE TABLE IF NOT EXISTS footer_banners (
			id INT AUTO_INCREMENT PRIMARY KEY,
			title VARCHAR(255) NULL,
			image_path VARCHAR(1024) NOT NULL,
			link_url VARCHAR(1024) NULL,
			is_active TINYINT(1) NOT NULL DEFAULT 1,
			position INT NOT NULL DEFAULT 0,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			INDEX idx_active (is_active),
			INDEX idx_position (position)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	}

	public function listAll(): array {
		$rows = Database::fetchAll("SELECT * FROM footer_banners ORDER BY position ASC, id ASC");
		return is_array($rows) ? $rows : [];
	}

	public function listPublic(): array {
        $rows = Database::fetchAll("SELECT * FROM footer_banners WHERE is_active = 1 ORDER BY position ASC, id ASC");
        if (!is_array($rows) || count($rows) === 0) {
            $this->seedDefaultsIfEmpty();
            $rows = Database::fetchAll("SELECT * FROM footer_banners WHERE is_active = 1 ORDER BY position ASC, id ASC");
        }
        return is_array($rows) ? $rows : [];
	}

	public function create(array $data): int {
		return Database::insert('footer_banners', [
			'title' => $data['title'] ?? null,
			'image_path' => $data['image_path'] ?? '',
			'link_url' => $data['link_url'] ?? null,
			'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1,
			'position' => isset($data['position']) ? (int)$data['position'] : 0,
		]);
	}

	public function update(int $id, array $data): bool {
		$fields = [
			'title' => array_key_exists('title', $data) ? $data['title'] : null,
			'image_path' => array_key_exists('image_path', $data) ? $data['image_path'] : null,
			'link_url' => array_key_exists('link_url', $data) ? $data['link_url'] : null,
			'is_active' => array_key_exists('is_active', $data) ? (int)$data['is_active'] : null,
			'position' => array_key_exists('position', $data) ? (int)$data['position'] : null,
		];
		$fields = array_filter($fields, function($v){ return $v !== null; });
		if (empty($fields)) return true;
		return Database::update('footer_banners', $fields, 'id = :id', ['id' => $id]);
	}

	public function delete(int $id): bool {
		return Database::delete('footer_banners', 'id = :id', ['id' => $id]);
	}

	public function reorder(array $idList): bool {
		$pos = 0;
		foreach ($idList as $id) {
			Database::update('footer_banners', ['position' => $pos], 'id = :id', ['id' => (int)$id]);
			$pos++;
		}
		return true;
	}

    private function seedDefaultsIfEmpty(): void {
        $row = Database::fetchOne("SELECT COUNT(*) AS c FROM footer_banners");
        $cnt = ($row && is_array($row) && isset($row['c'])) ? (int)$row['c'] : 0;
        if ($cnt > 0) return;
        $defaults = [
            ['image' => '/assets/media/mini-slider/Имидж_рус_900х300.jpg', 'link' => 'https://fest-sbv.gck.by/'],
            ['image' => '/assets/media/mini-slider/god_blagoustroistvo.jpg', 'link' => 'https://gomel.gov.by/ru/content/god-blagoustroystva'],
            ['image' => '/assets/media/mini-slider/Oblisp.jpg', 'link' => '/dopage/oblisp'],
            ['image' => '/assets/media/mini-slider/11_direct.jpg', 'link' => 'https://president.gov.by/ru/documents/direktiva-no-11-ot-2-aprela-2025-g'],
            ['image' => '/assets/media/mini-slider/12_directiva.jpg', 'link' => 'https://president.gov.by/ru/documents/direktiva-no-12-ot-9-aprela-2025-g'],
            ['image' => '/assets/media/mini-slider/80_let_pobedy.jpg', 'link' => 'https://gomel-region.by/ru/80-ru'],
            ['image' => '/assets/media/mini-slider/yslygi.jpg', 'link' => 'http://xn----7sbgfh2alwzdhpc0c.xn--90ais/organization/15516/org-page'],
            ['image' => '/assets/media/mini-slider/pomow.jpg', 'link' => 'https://pomogut.by/'],
            ['image' => '/assets/media/mini-slider/der1.jpg', 'link' => 'https://president.gov.by/ru/documents/direktiva-1-ot-11-marta-2004-g-1397'],
        ];
        $pos = 0;
        foreach ($defaults as $d) {
            Database::insert('footer_banners', [
                'title' => null,
                'image_path' => $d['image'],
                'link_url' => $d['link'],
                'is_active' => 1,
                'position' => $pos,
            ]);
            $pos++;
        }
    }
}


