<?php

require_once __DIR__ . '/BaseAdminController.php';
require_once ENGINE_DIR . 'main/db.php';
require_once APPLICATION_DIR . 'models/ContentModel.php';

class ContentController extends BaseAdminController {
    public function index() {
        // Доступ: 4 (Зам. по воспитательной), 6 (Директор), 10 (Админ)
        $this->requireAccessLevel(4);
        $model = new ContentModel();
        $overrides = $model->listOverrides(500);
        echo $this->render('admin/content/index', [
            'title' => 'Управление контентом сайта',
            'currentPage' => 'content',
            'overrides' => $overrides,
        ]);
    }

    public function save() {
        $this->requireAccessLevel(4);
        $path = trim($_POST['path'] ?? '*');
        $selector = trim($_POST['selector'] ?? '');
        $content = $_POST['content'] ?? '';
        $isHtml = isset($_POST['is_html']) ? 1 : 0;
        if ($selector === '') {
            return $this->json(['success' => false, 'message' => 'CSS селектор обязателен']);
        }
        $model = new ContentModel();
        $model->saveOverride($path, $selector, $content, $isHtml, $_SESSION['user_id'] ?? null);
        return $this->json(['success' => true]);
    }

    public function delete() {
        $this->requireAccessLevel(4);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) return $this->json(['success' => false]);
        $model = new ContentModel();
        $model->deleteOverride($id);
        return $this->json(['success' => true]);
    }

    private function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // ===== Прямая правка файлов (без runtime API на публичной стороне) =====
    public function listFiles() {
        $this->requireAccessLevel(4);
        $base = realpath(APPLICATION_DIR . 'views');
        $files = [];
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
        foreach ($rii as $file) {
            if ($file->isDir()) continue;
            $path = $file->getPathname();
            if (!preg_match('/\.(php|html|htm)$/i', $path)) continue;
            $rel = str_replace($base, '', $path);
            $rel = ltrim(str_replace('\\', '/', $rel), '/');
            $files[] = $rel;
        }
        return $this->json(['success' => true, 'files' => $files]);
    }

    public function open() {
        $this->requireAccessLevel(4);
        $file = $_GET['file'] ?? '';
        $content = $this->safeRead($file);
        if ($content === false) return $this->json(['success' => false, 'message' => 'Файл недоступен']);
        return $this->json(['success' => true, 'file' => $file, 'content' => $content]);
    }

    public function saveFile() {
        $this->requireAccessLevel(4);
        $file = $_POST['file'] ?? '';
        $content = $_POST['content'] ?? '';
        $ok = $this->safeWrite($file, $content);
        return $this->json(['success' => $ok === true, 'message' => $ok === true ? 'Сохранено' : ($ok ?: 'Ошибка сохранения')]);
    }

    private function safeRead(string $relative) {
        $base = realpath(APPLICATION_DIR . 'views');
        $full = realpath($base . DIRECTORY_SEPARATOR . $relative);
        if ($full === false || strpos($full, $base) !== 0) return false;
        if (!is_file($full) || !is_readable($full)) return false;
        return file_get_contents($full);
    }

    private function safeWrite(string $relative, string $content) {
        $base = realpath(APPLICATION_DIR . 'views');
        $full = realpath($base . DIRECTORY_SEPARATOR . $relative);
        if ($full === false || strpos($full, $base) !== 0) return 'Недопустимый путь';
        if (!is_file($full) || !is_writable($full)) return 'Файл недоступен для записи';
        // создаём бэкап
        $backupDir = APPLICATION_DIR . '../backups/content/' . date('Ymd-His');
        if (!is_dir($backupDir)) @mkdir($backupDir, 0755, true);
        @copy($full, $backupDir . '/' . basename($full));
        $bytes = file_put_contents($full, $content);
        return $bytes !== false ? true : 'Не удалось записать файл';
    }
}


