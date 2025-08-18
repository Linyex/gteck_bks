<?php

require_once __DIR__ . '/BaseAdminController.php';
require_once ENGINE_DIR . 'main/db.php';

class ControlFilesController extends BaseAdminController {
    
    public function index() {
        // Доступ: Преподаватель (1), Зав. отделением (3), Зам. по учебной (5), Директор (6), Админ (10)
        $this->requireAccessLevel(1);
        
        try {
            // Параметры фильтрации
            $filter_group = $_GET['group'] ?? '';
            $search = $_GET['search'] ?? '';
            
            // Базовый SQL запрос
            $sql = "
                SELECT f.*, GROUP_CONCAT(j.group_name) as group_names
                FROM dkrfiles f 
                LEFT JOIN dkrjointable j ON f.id = j.fileid
            ";
            
            $where_conditions = [];
            $params = [];
            
            // Фильтр по группам
            if (!empty($filter_group)) {
                $where_conditions[] = "j.group_name = ?";
                $params[] = $filter_group;
            }
            
            // Поиск по названию файла
            if (!empty($search)) {
                $where_conditions[] = "f.filename LIKE ?";
                $params[] = '%' . $search . '%';
            }
            
            if (!empty($where_conditions)) {
                $sql .= " WHERE " . implode(' AND ', $where_conditions);
            }
            
            $sql .= " GROUP BY f.id ORDER BY f.upload_date DESC";
            
            $files = Database::fetchAll($sql, $params);
            
            // Добавляем размер файла к каждому файлу
            foreach ($files as &$file) {
                $file_path = $_SERVER['DOCUMENT_ROOT'] . $file['path'];
                if (file_exists($file_path)) {
                    $file['filesize'] = filesize($file_path);
                } else {
                    $file['filesize'] = 0;
                }
            }
            
            // Получаем список всех групп для фильтра
            $groups = Database::fetchAll("
                SELECT DISTINCT group_name 
                FROM dkrjointable 
                ORDER BY group_name
            ");
            
            // Статистика
            $stats = [
                'total_files' => count($files),
                'total_size' => 0,
                'groups_count' => count($groups)
            ];
            
            // Подсчитываем общий размер файлов
            foreach ($files as $file) {
                $stats['total_size'] += $file['filesize'];
            }
            
            echo $this->render('admin/control-files/index', [
                'files' => $files,
                'groups' => $groups,
                'stats' => $stats,
                'filter_group' => $filter_group,
                'search' => $search,
                'title' => 'Управление файлами контрольных работ',
                'currentPage' => 'control-files',
                'formatFileSize' => [$this, 'formatFileSize']
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
                $group_names = $_POST['group_names'] ?? [];
                $description = trim($_POST['description'] ?? '');
                
                if (empty($_FILES['file']['name'])) {
                    throw new Exception('Выберите файл для загрузки');
                }
                
                if (empty($group_names)) {
                    throw new Exception('Выберите хотя бы одну группу');
                }
                
                $file = $_FILES['file'];
                
                // Проверяем тип файла (добавлен rtf)
                $allowed_types = ['pdf', 'doc', 'docx', 'rtf', 'txt', 'zip', 'rar'];
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
                    INSERT INTO dkrfiles (filename, path, upload_date) 
                    VALUES (?, ?, NOW())
                ", [$final_filename, $web_path]);
                
                // Получаем ID добавленной записи
                $connection = Database::getConnection();
                $file_id = $connection->lastInsertId();
                
                // Связываем с группами
                foreach ($group_names as $group_name) {
                    Database::execute("
                        INSERT INTO dkrjointable (fileid, group_name) 
                        VALUES (?, ?)
                    ", [$file_id, $group_name]);
                }
                
                $_SESSION['success'] = 'Файл успешно загружен и привязан к группам';
                header('Location: /admin/control-files');
                exit;
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        try {
            // Получаем список групп (только активные группы с паролями)
            $groups = Database::fetchAll("
                SELECT group_name 
                FROM group_passwords 
                WHERE is_active = 1 
                ORDER BY group_name
            ");
            
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
                $group_names = $_POST['group_names'] ?? [];
                $description = trim($_POST['description'] ?? '');
                
                if (empty($filename)) {
                    throw new Exception('Название файла обязательно');
                }
                
                if (empty($group_names)) {
                    throw new Exception('Выберите хотя бы одну группу');
                }
                
                // Обновляем информацию о файле
                Database::execute("
                    UPDATE dkrfiles 
                    SET filename = ? 
                    WHERE id = ?
                ", [$filename, $id]);
                
                // Удаляем старые связи с группами
                Database::execute("DELETE FROM dkrjointable WHERE fileid = ?", [$id]);
                
                // Создаем новые связи
                foreach ($group_names as $group_name) {
                    Database::execute("
                        INSERT INTO dkrjointable (fileid, group_name) 
                        VALUES (?, ?)
                    ", [$id, $group_name]);
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
                SELECT group_name FROM dkrjointable WHERE fileid = ?
            ", [$id]);
            $file_group_names = array_column($file_groups, 'group_name');
            
            // Получаем все группы (только активные группы с паролями)
            $groups = Database::fetchAll("
                SELECT group_name 
                FROM group_passwords 
                WHERE is_active = 1 
                ORDER BY group_name
            ");
            
            echo $this->render('admin/control-files/edit', [
                'file' => $file,
                'groups' => $groups,
                'file_group_names' => $file_group_names,
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
    
    /**
     * Форматирует размер файла в читаемый вид
     */
    protected function formatFileSize($bytes) {
        if ($bytes === 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}
?> 