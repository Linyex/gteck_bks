<?php

require_once __DIR__ . '/BaseAdminController.php';
require_once ENGINE_DIR . 'main/db.php';
require_once APPLICATION_DIR . 'models/InfoWidgetModel.php';

class InfoWidgetController extends BaseAdminController {
	public function index() {
		// Доступ: 4 (Зам. по воспитательной) и выше
		$this->requireAccessLevel(4);
		$model = new InfoWidgetModel();
		$items = $model->listAll();
		echo $this->render('admin/info_widget/index', [
			'title' => 'Инфо-виджет: управление',
			'currentPage' => 'info-widget',
			'items' => $items,
		]);
	}

	public function store() {
		$this->requireAccessLevel(4);
		$model = new InfoWidgetModel();
		$data = [
			'title' => trim($_POST['title'] ?? ''),
			'description' => $_POST['description'] ?? null,
			'url' => $_POST['url'] ?? null,
			'icon' => $_POST['icon'] ?? null,
			'group' => $_POST['group'] ?? 'general',
			'is_active' => isset($_POST['is_active']) ? 1 : 0,
			'position' => (int)($_POST['position'] ?? 0),
		];
		$filePath = $this->handleUpload('file');
		if ($filePath) { $data['file_path'] = $filePath; }
		$id = $model->create($data);
		return $this->json(['success' => true, 'id' => $id]);
	}

	public function update() {
		$this->requireAccessLevel(4);
		$model = new InfoWidgetModel();
		$id = (int)($_POST['id'] ?? 0);
		if (!$id) return $this->json(['success' => false, 'message' => 'id required']);
        $data = [
            'title' => $_POST['title'] ?? null,
            'description' => $_POST['description'] ?? null,
            'url' => $_POST['url'] ?? null,
            'icon' => $_POST['icon'] ?? null,
            'group' => $_POST['group'] ?? null,
            // Явно трактуем как 1 только значение '1', иначе 0
            'is_active' => array_key_exists('is_active', $_POST) ? (($_POST['is_active'] === '1') ? 1 : 0) : null,
            'position' => isset($_POST['position']) ? (int)$_POST['position'] : null,
        ];
		$filePath = $this->handleUpload('file');
		if ($filePath) { $data['file_path'] = $filePath; }
		$ok = $model->update($id, $data);
		return $this->json(['success' => $ok]);
	}

	public function delete() {
		$this->requireAccessLevel(4);
		$model = new InfoWidgetModel();
		$id = (int)($_POST['id'] ?? 0);
		if (!$id) return $this->json(['success' => false]);
		$ok = $model->delete($id);
		return $this->json(['success' => $ok]);
	}

	public function reorder() {
		$this->requireAccessLevel(4);
		$model = new InfoWidgetModel();
		$order = $_POST['order'] ?? [];
		if (!is_array($order)) { $order = []; }
		$ok = $model->reorder(array_map('intval', $order));
		return $this->json(['success' => $ok]);
	}

	private function handleUpload(string $field): ?string {
		if (!isset($_FILES[$field]) || empty($_FILES[$field]['name'])) return null;
		$dir = 'assets/files';
		if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
		$ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
		$name = 'info_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . ($ext ? ('.' . $ext) : '');
		$dest = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $name;
		if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) return null;
		return '/' . str_replace(['\\','\\\\'], '/', $dest);
	}

	private function json($data) {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}
}


