<?php

require_once __DIR__ . '/BaseAdminController.php';
require_once ENGINE_DIR . 'main/db.php';
require_once APPLICATION_DIR . 'models/BannerModel.php';

class BannersController extends BaseAdminController {
	public function index() {
		// Доступ: 4 (Зам. по воспитательной) и выше
		$this->requireAccessLevel(4);
		$model = new BannerModel();
		$items = $model->listAll();
		echo $this->render('admin/banners/index', [
			'title' => 'Баннеры футера',
			'currentPage' => 'banners',
			'items' => $items,
		]);
	}

	public function store() {
		$this->requireAccessLevel(4);
		$model = new BannerModel();
		$img = $this->handleUpload('image');
		if (!$img) return $this->json(['success' => false, 'message' => 'Изображение обязательно']);
		$id = $model->create([
			'title' => $_POST['title'] ?? null,
			'image_path' => $img,
			'link_url' => $_POST['link_url'] ?? null,
			'is_active' => isset($_POST['is_active']) ? 1 : 0,
			'position' => (int)($_POST['position'] ?? 0),
		]);
		return $this->json(['success' => true, 'id' => $id]);
	}

	public function update() {
		$this->requireAccessLevel(4);
		$model = new BannerModel();
		$id = (int)($_POST['id'] ?? 0);
		if (!$id) return $this->json(['success' => false]);
		$data = [
			'title' => $_POST['title'] ?? null,
			'link_url' => $_POST['link_url'] ?? null,
			'is_active' => array_key_exists('is_active', $_POST) ? (($_POST['is_active'] === '1') ? 1 : 0) : null,
			'position' => isset($_POST['position']) ? (int)$_POST['position'] : null,
		];
		$img = $this->handleUpload('image');
		if ($img) { $data['image_path'] = $img; }
		$ok = $model->update($id, $data);
		return $this->json(['success' => $ok]);
	}

	public function delete() {
		$this->requireAccessLevel(4);
		$model = new BannerModel();
		$id = (int)($_POST['id'] ?? 0);
		if (!$id) return $this->json(['success' => false]);
		$ok = $model->delete($id);
		return $this->json(['success' => $ok]);
	}

	public function reorder() {
		$this->requireAccessLevel(4);
		$model = new BannerModel();
		$order = $_POST['order'] ?? [];
		if (!is_array($order)) $order = [];
		$ok = $model->reorder(array_map('intval', $order));
		return $this->json(['success' => $ok]);
	}

	private function handleUpload(string $field): ?string {
		if (!isset($_FILES[$field]) || empty($_FILES[$field]['name'])) return null;
		$dir = 'assets/media/mini-slider';
		if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
		$ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
		$allowed = ['jpg','jpeg','png','webp'];
		if (!in_array($ext, $allowed, true)) return null;
		$name = 'banner_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
		$dest = rtrim($dir,'/\\') . DIRECTORY_SEPARATOR . $name;
		if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) return null;
		return '/' . str_replace(['\\','\\\\'], '/', $dest);
	}

	private function json($data) {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}
}


