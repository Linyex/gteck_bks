<?php

class InfoWidgetModel {
	public function __construct() {
		$this->ensureTable();
	}

	private function ensureTable() {
		Database::execute("CREATE TABLE IF NOT EXISTS info_widget_items (
			id INT AUTO_INCREMENT PRIMARY KEY,
			title VARCHAR(255) NOT NULL,
			description TEXT NULL,
			url VARCHAR(1024) NULL,
			file_path VARCHAR(1024) NULL,
			icon VARCHAR(128) NULL,
			`group` VARCHAR(32) NOT NULL DEFAULT 'general',
			is_active TINYINT(1) NOT NULL DEFAULT 1,
			position INT NOT NULL DEFAULT 0,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			INDEX idx_group (`group`),
			INDEX idx_active (is_active),
			INDEX idx_position (position)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	}

	public function listAll(): array {
		return Database::fetchAll("SELECT * FROM info_widget_items ORDER BY `group` ASC, position ASC, id ASC");
	}

	public function listPublic(string $group = null): array {
		$params = [];
		$sql = "SELECT * FROM info_widget_items WHERE is_active = 1";
		if ($group !== null && $group !== '') {
			$sql .= " AND `group` = :grp";
			$params['grp'] = $group;
		}
		$sql .= " ORDER BY position ASC, id ASC";
		$rows = Database::fetchAll($sql, $params);
		return is_array($rows) ? $rows : [];
	}

	public function getById(int $id): ?array {
		$row = Database::fetchOne("SELECT * FROM info_widget_items WHERE id = :id", ['id' => $id]);
		return $row && is_array($row) ? $row : null;
	}

	public function create(array $data): int {
		return Database::insert('info_widget_items', [
			'title' => $data['title'] ?? '',
			'description' => $data['description'] ?? null,
			'url' => $data['url'] ?? null,
			'file_path' => $data['file_path'] ?? null,
			'icon' => $data['icon'] ?? null,
			'group' => $data['group'] ?? 'general',
			'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1,
			'position' => isset($data['position']) ? (int)$data['position'] : 0,
		]);
	}

	public function update(int $id, array $data): bool {
		$fields = [
			'title' => $data['title'] ?? null,
			'description' => array_key_exists('description', $data) ? $data['description'] : null,
			'url' => array_key_exists('url', $data) ? $data['url'] : null,
			'file_path' => array_key_exists('file_path', $data) ? $data['file_path'] : null,
			'icon' => array_key_exists('icon', $data) ? $data['icon'] : null,
			'group' => array_key_exists('group', $data) ? ($data['group'] ?? 'general') : null,
			'is_active' => array_key_exists('is_active', $data) ? (int)$data['is_active'] : null,
			'position' => array_key_exists('position', $data) ? (int)$data['position'] : null,
		];
		// Удаляем поля со значением null, чтобы не перезаписать NULL там, где нужно оставить прежнее
		$fields = array_filter($fields, function($v) { return $v !== null; });
		if (empty($fields)) return true;
		return Database::update('info_widget_items', $fields, 'id = :id', ['id' => $id]);
	}

	public function delete(int $id): bool {
		return Database::delete('info_widget_items', 'id = :id', ['id' => $id]);
	}

	public function reorder(array $idList): bool {
		$position = 0;
		foreach ($idList as $id) {
			Database::update('info_widget_items', ['position' => $position], 'id = :id', ['id' => (int)$id]);
			$position++;
		}
		return true;
	}
}


