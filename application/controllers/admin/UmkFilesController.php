<?php

require_once __DIR__ . '/BaseAdminController.php';

class UmkFilesController extends BaseAdminController {
    
    public function index() {
        $this->requireAccessLevel(1); // Редакторы и выше
        
        try {
            // Получаем все файлы УМК из директории
            $umk_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/files/ymk/';
            $files = [];
            
            if (is_dir($umk_dir)) {
                $file_list = scandir($umk_dir);
                foreach ($file_list as $filename) {
                    if ($filename !== '.' && $filename !== '..' && is_file($umk_dir . $filename)) {
                        $file_path = $umk_dir . $filename;
                        $files[] = [
                            'filename' => $filename,
                            'path' => '/assets/files/ymk/' . $filename,
                            'size' => filesize($file_path),
                            'upload_date' => date('Y-m-d H:i:s', filemtime($file_path))
                        ];
                    }
                }
            }
            
            // Сортируем по дате (новые первыми)
            usort($files, function($a, $b) {
                return strtotime($b['upload_date']) - strtotime($a['upload_date']);
            });
            
            echo $this->render('admin/umk-files/index', [
                'files' => $files,
                'title' => 'Управление файлами УМК',
                'currentPage' => 'umk-files'
            ]);
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }
    
    public function upload() {
        $this->requireAccessLevel(1); // Редакторы и выше
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $filename = trim($_POST['filename'] ?? '');
                $description = trim($_POST['description'] ?? '');
                
                if (empty($_FILES['file']['name'])) {
                    throw new Exception('Выберите файл для загрузки');
                }
                
                $file = $_FILES['file'];
                
                // Проверяем тип файла
                $allowed_types = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($file_ext, $allowed_types)) {
                    throw new Exception('Недопустимый тип файла. Разрешены: ' . implode(', ', $allowed_types));
                }
                
                // Создаем директорию если не существует
                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/files/ymk/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Генерируем имя файла
                $original_name = !empty($filename) ? $filename : pathinfo($file['name'], PATHINFO_FILENAME);
                $safe_filename = preg_replace('/[^A-Za-z0-9_\-\sА-Яа-яЁё]/u', '', $original_name);
                $final_filename = $safe_filename . '.' . $file_ext;
                
                $target_path = $upload_dir . $final_filename;
                
                // Избегаем дублирования имен файлов
                $counter = 1;
                while (file_exists($target_path)) {
                    $final_filename = $safe_filename . '_' . $counter . '.' . $file_ext;
                    $target_path = $upload_dir . $final_filename;
                    $counter++;
                }
                
                // Загружаем файл
                if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                    throw new Exception('Ошибка при загрузке файла');
                }
                
                $_SESSION['success'] = 'Файл УМК успешно загружен';
                header('Location: /admin/umk-files');
                exit;
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        echo $this->render('admin/umk-files/upload', [
            'title' => 'Загрузить файл УМК',
            'currentPage' => 'umk-files'
        ]);
    }
    
    public function delete() {
        $this->requireAccessLevel(5); // Модераторы и выше
        
        $filename = $_POST['filename'] ?? null;
        if (!$filename) {
            $this->jsonResponse(['success' => false, 'message' => 'Имя файла не указано']);
            return;
        }
        
        try {
            // Проверяем безопасность пути
            $safe_filename = basename($filename);
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/files/ymk/' . $safe_filename;
            
            if (!file_exists($file_path)) {
                throw new Exception('Файл не найден');
            }
            
            // Удаляем файл
            if (!unlink($file_path)) {
                throw new Exception('Не удалось удалить файл');
            }
            
            $this->jsonResponse(['success' => true, 'message' => 'Файл удален']);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function rename() {
        $this->requireAccessLevel(1); // Редакторы и выше
        
        $old_filename = $_POST['old_filename'] ?? null;
        $new_filename = $_POST['new_filename'] ?? null;
        
        if (!$old_filename || !$new_filename) {
            $this->jsonResponse(['success' => false, 'message' => 'Не указаны имена файлов']);
            return;
        }
        
        try {
            // Проверяем безопасность путей
            $safe_old_filename = basename($old_filename);
            $safe_new_filename = preg_replace('/[^A-Za-z0-9_\-\sА-Яа-яЁё\.]/u', '', $new_filename);
            
            $old_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/files/ymk/' . $safe_old_filename;
            $new_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/files/ymk/' . $safe_new_filename;
            
            if (!file_exists($old_path)) {
                throw new Exception('Исходный файл не найден');
            }
            
            if (file_exists($new_path)) {
                throw new Exception('Файл с новым именем уже существует');
            }
            
            // Переименовываем файл
            if (!rename($old_path, $new_path)) {
                throw new Exception('Не удалось переименовать файл');
            }
            
            $this->jsonResponse(['success' => true, 'message' => 'Файл переименован', 'new_filename' => $safe_new_filename]);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function handleError($e) {
        $_SESSION['error'] = 'Произошла ошибка: ' . $e->getMessage();
        echo $this->render('admin/error/500', [
            'title' => 'Ошибка сервера',
            'message' => $e->getMessage(),
            'currentPage' => 'umk-files'
        ]);
    }
}
?> 