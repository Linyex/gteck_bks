<?php

/**
 * Сервис drag-and-drop загрузки файлов
 * Обеспечивает удобную загрузку файлов через drag-and-drop интерфейс
 */
class DragDropService {
    
    private $uploadDir = 'uploads/';
    private $maxFileSize = 10 * 1024 * 1024; // 10MB
    private $allowedTypes = [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'document' => ['pdf', 'doc', 'docx', 'txt', 'rtf'],
        'archive' => ['zip', 'rar', '7z'],
        'video' => ['mp4', 'avi', 'mov', 'wmv'],
        'audio' => ['mp3', 'wav', 'ogg', 'flac']
    ];
    
    public function __construct() {
        // Создаем директорию для загрузок если не существует
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    /**
     * Обрабатывает загрузку файлов
     */
    public function handleUpload($files, $options = []) {
        try {
            $results = [];
            $errors = [];
            
            // Настройки
            $category = $options['category'] ?? 'general';
            $userId = $options['user_id'] ?? ($_SESSION['admin_user_id'] ?? null);
            $description = $options['description'] ?? '';
            
            // Создаем директорию для категории
            $categoryDir = $this->uploadDir . $category . '/';
            if (!is_dir($categoryDir)) {
                mkdir($categoryDir, 0755, true);
            }
            
            foreach ($files as $file) {
                $result = $this->processFile($file, $categoryDir, $userId, $description);
                
                if ($result['success']) {
                    $results[] = $result;
                } else {
                    $errors[] = $result;
                }
            }
            
            return [
                'success' => empty($errors),
                'uploaded' => $results,
                'errors' => $errors,
                'total' => count($files),
                'successful' => count($results)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Обрабатывает один файл
     */
    private function processFile($file, $uploadDir, $userId, $description) {
        try {
            // Проверяем ошибки загрузки
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'error' => $this->getUploadErrorMessage($file['error']),
                    'filename' => $file['name']
                ];
            }
            
            // Проверяем размер файла
            if ($file['size'] > $this->maxFileSize) {
                return [
                    'success' => false,
                    'error' => 'File too large. Maximum size is ' . $this->formatBytes($this->maxFileSize),
                    'filename' => $file['name']
                ];
            }
            
            // Проверяем тип файла
            $fileInfo = $this->getFileInfo($file);
            if (!$this->isAllowedType($fileInfo['extension'])) {
                return [
                    'success' => false,
                    'error' => 'File type not allowed: ' . $fileInfo['extension'],
                    'filename' => $file['name']
                ];
            }
            
            // Генерируем уникальное имя файла
            $filename = $this->generateUniqueFilename($fileInfo['name'], $fileInfo['extension']);
            $filepath = $uploadDir . $filename;
            
            // Перемещаем файл
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                return [
                    'success' => false,
                    'error' => 'Failed to save uploaded file',
                    'filename' => $file['name']
                ];
            }
            
            // Создаем миниатюру для изображений
            $thumbnail = null;
            if ($fileInfo['type'] === 'image') {
                $thumbnail = $this->createThumbnail($filepath, $uploadDir);
            }
            
            // Сохраняем информацию в базу данных
            $fileId = $this->saveFileInfo($fileInfo, $filepath, $thumbnail, $userId, $description);
            
            return [
                'success' => true,
                'file_id' => $fileId,
                'original_name' => $file['name'],
                'filename' => $filename,
                'filepath' => $filepath,
                'url' => '/' . $filepath,
                'thumbnail' => $thumbnail ? '/' . $thumbnail : null,
                'size' => $file['size'],
                'type' => $fileInfo['type'],
                'extension' => $fileInfo['extension'],
                'mime_type' => $fileInfo['mime_type']
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'filename' => $file['name'] ?? 'Unknown'
            ];
        }
    }
    
    /**
     * Получает информацию о файле
     */
    private function getFileInfo($file) {
        $pathInfo = pathinfo($file['name']);
        $extension = strtolower($pathInfo['extension'] ?? '');
        
        return [
            'name' => $pathInfo['filename'],
            'extension' => $extension,
            'mime_type' => $file['type'],
            'type' => $this->getFileType($extension)
        ];
    }
    
    /**
     * Определяет тип файла
     */
    private function getFileType($extension) {
        foreach ($this->allowedTypes as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }
        
        return 'other';
    }
    
    /**
     * Проверяет разрешенный тип файла
     */
    private function isAllowedType($extension) {
        foreach ($this->allowedTypes as $extensions) {
            if (in_array($extension, $extensions)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Генерирует уникальное имя файла
     */
    private function generateUniqueFilename($name, $extension) {
        $timestamp = time();
        $random = substr(md5(uniqid()), 0, 8);
        $safeName = $this->sanitizeFilename($name);
        
        return "{$safeName}_{$timestamp}_{$random}.{$extension}";
    }
    
    /**
     * Очищает имя файла
     */
    private function sanitizeFilename($filename) {
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
        $filename = preg_replace('/_+/', '_', $filename);
        $filename = trim($filename, '_');
        
        return $filename ?: 'file';
    }
    
    /**
     * Создает миниатюру для изображения
     */
    private function createThumbnail($imagePath, $uploadDir) {
        try {
            $imageInfo = getimagesize($imagePath);
            if (!$imageInfo) {
                return null;
            }
            
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $type = $imageInfo[2];
            
            // Максимальные размеры миниатюры
            $maxWidth = 200;
            $maxHeight = 200;
            
            // Вычисляем новые размеры
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = round($width * $ratio);
            $newHeight = round($height * $ratio);
            
            // Создаем изображение
            $source = $this->createImageFromFile($imagePath, $type);
            if (!$source) {
                return null;
            }
            
            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
            
            // Сохраняем прозрачность для PNG
            if ($type === IMAGETYPE_PNG) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
                $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
                imagefill($thumbnail, 0, 0, $transparent);
            }
            
            // Изменяем размер
            imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            // Сохраняем миниатюру
            $thumbnailPath = $uploadDir . 'thumb_' . basename($imagePath);
            $this->saveImage($thumbnail, $thumbnailPath, $type);
            
            // Освобождаем память
            imagedestroy($source);
            imagedestroy($thumbnail);
            
            return $thumbnailPath;
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Создает изображение из файла
     */
    private function createImageFromFile($path, $type) {
        switch ($type) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($path);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($path);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($path);
            case IMAGETYPE_WEBP:
                return imagecreatefromwebp($path);
            default:
                return null;
        }
    }
    
    /**
     * Сохраняет изображение
     */
    private function saveImage($image, $path, $type) {
        switch ($type) {
            case IMAGETYPE_JPEG:
                return imagejpeg($image, $path, 90);
            case IMAGETYPE_PNG:
                return imagepng($image, $path, 9);
            case IMAGETYPE_GIF:
                return imagegif($image, $path);
            case IMAGETYPE_WEBP:
                return imagewebp($image, $path, 90);
            default:
                return false;
        }
    }
    
    /**
     * Сохраняет информацию о файле в базу данных
     */
    private function saveFileInfo($fileInfo, $filepath, $thumbnail, $userId, $description) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $result = Database::execute(
                "INSERT INTO files (file_name, file_path, file_thumbnail, file_type, file_extension, file_size, file_description, uploaded_by, upload_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    $fileInfo['name'],
                    $filepath,
                    $thumbnail,
                    $fileInfo['type'],
                    $fileInfo['extension'],
                    filesize($filepath),
                    $description,
                    $userId
                ]
            );
            
            return Database::lastInsertId();
            
        } catch (Exception $e) {
            throw new Exception('Failed to save file info: ' . $e->getMessage());
        }
    }
    
    /**
     * Получает сообщение об ошибке загрузки
     */
    private function getUploadErrorMessage($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'File exceeds upload_max_filesize directive';
            case UPLOAD_ERR_FORM_SIZE:
                return 'File exceeds MAX_FILE_SIZE directive';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }
    
    /**
     * Форматирует размер файла
     */
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Удаляет файл
     */
    public function deleteFile($fileId) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $file = Database::fetchOne(
                "SELECT file_path, file_thumbnail FROM files WHERE file_id = ?",
                [$fileId]
            );
            
            if (!$file) {
                return [
                    'success' => false,
                    'error' => 'File not found'
                ];
            }
            
            // Удаляем основной файл
            if (file_exists($file['file_path'])) {
                unlink($file['file_path']);
            }
            
            // Удаляем миниатюру
            if ($file['file_thumbnail'] && file_exists($file['file_thumbnail'])) {
                unlink($file['file_thumbnail']);
            }
            
            // Удаляем запись из базы данных
            Database::execute("DELETE FROM files WHERE file_id = ?", [$fileId]);
            
            return [
                'success' => true,
                'message' => 'File deleted successfully'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Получает список файлов
     */
    public function getFilesList($filters = [], $page = 1, $perPage = 20) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $where = "WHERE 1=1";
            $params = [];
            
            if (!empty($filters['type'])) {
                $where .= " AND file_type = ?";
                $params[] = $filters['type'];
            }
            
            if (!empty($filters['user_id'])) {
                $where .= " AND uploaded_by = ?";
                $params[] = $filters['user_id'];
            }
            
            if (!empty($filters['search'])) {
                $where .= " AND (file_name LIKE ? OR file_description LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $query = "SELECT * FROM files $where ORDER BY upload_date DESC";
            
            // Пагинация
            $offset = ($page - 1) * $perPage;
            $query .= " LIMIT $perPage OFFSET $offset";
            
            $files = Database::fetchAll($query, $params);
            
            // Получаем общее количество
            $countQuery = "SELECT COUNT(*) as total FROM files $where";
            $total = Database::fetchOne($countQuery, $params);
            
            return [
                'files' => $files,
                'total' => $total['total'] ?? 0,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil(($total['total'] ?? 0) / $perPage)
            ];
            
        } catch (Exception $e) {
            return [
                'files' => [],
                'total' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Получает статистику загрузок
     */
    public function getUploadStats() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $stats = [];
            
            // Общее количество файлов
            $total = Database::fetchOne("SELECT COUNT(*) as count FROM files");
            $stats['total_files'] = $total['count'] ?? 0;
            
            // Общий размер файлов
            $size = Database::fetchOne("SELECT SUM(file_size) as total_size FROM files");
            $stats['total_size'] = $size['total_size'] ?? 0;
            
            // Файлы по типам
            $types = Database::fetchAll("SELECT file_type, COUNT(*) as count FROM files GROUP BY file_type");
            $stats['by_type'] = [];
            foreach ($types as $type) {
                $stats['by_type'][$type['file_type']] = $type['count'];
            }
            
            // Файлы за последние 30 дней
            $recent = Database::fetchOne(
                "SELECT COUNT(*) as count FROM files WHERE upload_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
            );
            $stats['recent_30_days'] = $recent['count'] ?? 0;
            
            return $stats;
            
        } catch (Exception $e) {
            return [];
        }
    }
} 