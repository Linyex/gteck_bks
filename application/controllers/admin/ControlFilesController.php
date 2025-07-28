<?php

require_once __DIR__ . '/BaseAdminController.php';
require_once ENGINE_DIR . 'main/db.php';

class ControlFilesController extends BaseAdminController {
    
    public function index() {
        $this->requireAccessLevel(1); // Редакторы и выше
        
        try {
            // Получаем все файлы с группами
            $files = Database::fetchAll("
                SELECT f.*, GROUP_CONCAT(g.groupname) as group_names 
                FROM dkrfiles f 
                LEFT JOIN dkrjointable j ON f.id = j.fileid 
                LEFT JOIN dkrgroups g ON j.groupid = g.id_group 
                GROUP BY f.id 
                ORDER BY f.upload_date DESC
            ");
            
            echo $this->render('admin/control-files/index', [
                'files' => $files,
                'title' => 'Управление файлами контрольных работ',
                'currentPage' => 'control-files'
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
                $group_ids = $_POST['group_ids'] ?? [];
                $description = trim($_POST['description'] ?? '');
                
                if (empty($_FILES['file']['name'])) {
                    throw new Exception('Выберите файл для загрузки');
                }
                
                if (empty($group_ids)) {
                    throw new Exception('Выберите хотя бы одну группу');
                }
                
                $file = $_FILES['file'];
                
                // Проверяем тип файла
                $allowed_types = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($file_ext, $allowed_types)) {
                    throw new Exception('Недопустимый тип файла. Разрешены: ' . implode(', ', $allowed_types));
                }
                
                // Создаем директорию если не существует
                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/files/kontrolnui/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Генерируем уникальное имя файла
                $original_name = !empty($filename) ? $filename : pathinfo($file['name'], PATHINFO_FILENAME);
                $safe_filename = preg_replace('/[^A-Za-z0-9_\-\sА-Яа-яЁё]/u', '', $original_name);
                $final_filename = $safe_filename . '.' . $file_ext;
                
                $target_path = $upload_dir . $final_filename;
                $web_path = '/assets/files/kontrolnui/' . $final_filename;
                
                // Избегаем дублирования имен файлов
                $counter = 1;
                while (file_exists($target_path)) {
                    $final_filename = $safe_filename . '_' . $counter . '.' . $file_ext;
                    $target_path = $upload_dir . $final_filename;
                    $web_path = '/assets/files/kontrolnui/' . $final_filename;
                    $counter++;
                }
                
                // Загружаем файл
                if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                    throw new Exception('Ошибка при загрузке файла');
                }
                
                // Сохраняем в базу данных
                Database::execute("
                    INSERT INTO dkrfiles (filename, path, description, upload_date) 
                    VALUES (?, ?, ?, NOW())
                ", [$final_filename, $web_path, $description]);
                
                // Получаем ID добавленной записи
                $connection = Database::getConnection();
                $file_id = $connection->lastInsertId();
                
                // Связываем с группами
                foreach ($group_ids as $group_id) {
                    Database::execute("
                        INSERT INTO dkrjointable (fileid, groupid) 
                        VALUES (?, ?)
                    ", [$file_id, $group_id]);
                }
                
                $_SESSION['success'] = 'Файл успешно загружен и привязан к группам';
                header('Location: /admin/control-files');
                exit;
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        try {
            // Получаем список групп
            $groups = Database::fetchAll("SELECT * FROM dkrgroups ORDER BY groupname");
            
            echo $this->render('admin/control-files/upload', [
                'groups' => $groups,
                'title' => 'Загрузить файл контрольной работы',
                'currentPage' => 'control-files'
            ]);
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }
    
    public function edit($id = null) {
        $this->requireAccessLevel(1); // Редакторы и выше
        
        if (!$id) {
            header('Location: /admin/control-files');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $filename = trim($_POST['filename']);
                $group_ids = $_POST['group_ids'] ?? [];
                $description = trim($_POST['description'] ?? '');
                
                if (empty($filename)) {
                    throw new Exception('Название файла обязательно');
                }
                
                if (empty($group_ids)) {
                    throw new Exception('Выберите хотя бы одну группу');
                }
                
                // Обновляем информацию о файле
                Database::execute("
                    UPDATE dkrfiles 
                    SET filename = ?, description = ? 
                    WHERE id = ?
                ", [$filename, $description, $id]);
                
                // Удаляем старые связи с группами
                Database::execute("DELETE FROM dkrjointable WHERE fileid = ?", [$id]);
                
                // Создаем новые связи
                foreach ($group_ids as $group_id) {
                    Database::execute("
                        INSERT INTO dkrjointable (fileid, groupid) 
                        VALUES (?, ?)
                    ", [$id, $group_id]);
                }
                
                $_SESSION['success'] = 'Файл успешно обновлен';
                header('Location: /admin/control-files');
                exit;
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        try {
            // Получаем информацию о файле
            $file = Database::fetchOne("SELECT * FROM dkrfiles WHERE id = ?", [$id]);
            if (!$file) {
                throw new Exception('Файл не найден');
            }
            
            // Получаем группы файла
            $file_groups = Database::fetchAll("
                SELECT groupid FROM dkrjointable WHERE fileid = ?
            ", [$id]);
            $file_group_ids = array_column($file_groups, 'groupid');
            
            // Получаем все группы
            $groups = Database::fetchAll("SELECT * FROM dkrgroups ORDER BY groupname");
            
            echo $this->render('admin/control-files/edit', [
                'file' => $file,
                'groups' => $groups,
                'file_group_ids' => $file_group_ids,
                'title' => 'Редактировать файл',
                'currentPage' => 'control-files'
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/control-files');
            exit;
        }
    }
    
    public function delete() {
        $this->requireAccessLevel(5); // Модераторы и выше
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID не указан']);
            return;
        }
        
        try {
            // Получаем информацию о файле
            $file = Database::fetchOne("SELECT * FROM dkrfiles WHERE id = ?", [$id]);
            if (!$file) {
                throw new Exception('Файл не найден');
            }
            
            // Удаляем физический файл
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $file['path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Удаляем связи с группами
            Database::execute("DELETE FROM dkrjointable WHERE fileid = ?", [$id]);
            
            // Удаляем запись из базы
            Database::execute("DELETE FROM dkrfiles WHERE id = ?", [$id]);
            
            $this->jsonResponse(['success' => true, 'message' => 'Файл удален']);
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
            'currentPage' => 'control-files'
        ]);
    }
}
?> 