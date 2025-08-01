<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

class FilesController extends BaseAdminController {
    
    public function index() {
        try {
            require_once 'engine/main/db.php';
            
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            // Получаем общее количество файлов
            $totalCount = Database::fetchOne("SELECT COUNT(*) as count FROM files");
            $total = $totalCount['count'] ?? 0;
            
            // Получаем файлы с пагинацией
            $files = Database::fetchAll("SELECT * FROM files ORDER BY files_date_add DESC LIMIT ? OFFSET ?", [$limit, $offset]);
            
            $totalPages = ceil($total / $limit);
            
            $this->render('admin/files/index', [
                'title' => 'Управление файлами',
                'currentPage' => 'files',
                'files' => $files,
                'currentPageNum' => $page,
                'totalPages' => $totalPages,
                'total' => $total,
                'additional_css' => [
                    '/assets/css/admin-cyberpunk.css'
                ],
                'additional_js' => [
                    '/assets/js/background-animations.js',
                    '/assets/js/admin-common.js'
                ]
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Не удалось загрузить файлы: ' . $e->getMessage()
            ]);
        }
    }
    
    public function upload() {
        $this->render('admin/files/upload', [
            'title' => 'Загрузить файл',
            'currentPage' => 'files',
            'additional_css' => [
                '/assets/css/admin-cyberpunk.css'
            ],
            'additional_js' => [
                '/assets/js/background-animations.js',
                '/assets/js/admin-common.js'
            ]
        ]);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/files');
            exit;
        }
        
        try {
            require_once 'engine/main/db.php';
            
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $category = $_POST['category'] ?? 'general';
            
            if (empty($title)) {
                throw new Exception('Введите название файла');
            }
            
            // Обработка загруженного файла
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/files/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . $_FILES['file']['name'];
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                    Database::execute(
                        "INSERT INTO files (files_text, files_description, files_category, files_path, files_date_add) VALUES (?, ?, ?, ?, NOW())",
                        [$title, $description, $category, $fileName]
                    );
                    
                    header('Location: /admin/files?success=1');
                    exit;
                } else {
                    throw new Exception('Ошибка при загрузке файла');
                }
            } else {
                throw new Exception('Выберите файл для загрузки');
            }
        } catch (Exception $e) {
            header('Location: /admin/files/upload?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
    
    public function delete($id) {
        try {
            require_once 'engine/main/db.php';
            
            // Получаем информацию о файле
            $file = Database::fetchOne("SELECT * FROM files WHERE files_id = ?", [$id]);
            
            if ($file) {
                // Удаляем физический файл
                $filePath = 'assets/files/' . $file['files_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Удаляем запись из базы данных
                Database::execute("DELETE FROM files WHERE files_id = ?", [$id]);
            }
            
            header('Location: /admin/files?deleted=1');
            exit;
        } catch (Exception $e) {
            header('Location: /admin/files?error=1');
            exit;
        }
    }
    
    public function download($id) {
        try {
            require_once 'engine/main/db.php';
            
            // Получаем информацию о файле
            $file = Database::fetchOne("SELECT * FROM files WHERE files_id = ?", [$id]);
            
            if (!$file) {
                $this->render('admin/error/404', [
                    'title' => 'Файл не найден',
                    'message' => 'Файл с ID ' . $id . ' не найден'
                ]);
            }
            
            $filePath = 'assets/files/' . $file['files_path'];
            
            if (!file_exists($filePath)) {
                $this->render('admin/error/404', [
                    'title' => 'Файл не найден',
                    'message' => 'Физический файл не найден на сервере'
                ]);
            }
            
            // Определяем MIME-тип файла
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);
            
            // Устанавливаем заголовки для скачивания
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . $file['files_text'] . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            
            // Отправляем файл
            readfile($filePath);
            exit;
            
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при скачивании файла: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 