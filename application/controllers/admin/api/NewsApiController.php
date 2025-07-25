<?php

require_once APPLICATION_DIR . 'controllers/admin/api/BaseApiController.php';

class NewsApiController extends BaseApiController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * GET /api/news
     * Получение списка новостей
     */
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(1); // Редакторы и выше
            
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
            $search = $_GET['search'] ?? '';
            $category = $_GET['category'] ?? '';
            $status = $_GET['status'] ?? '';
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Формируем базовый запрос
            $query = "SELECT news_id, news_title, news_text, news_image, news_date_add, category_id FROM news WHERE 1=1";
            $params = [];
            
            // Добавляем фильтры
            if (!empty($search)) {
                $query .= " AND (news_title LIKE ? OR news_text LIKE ?)";
                $searchParam = "%$search%";
                $params = array_merge($params, [$searchParam, $searchParam]);
            }
            
            if ($category !== '') {
                $query .= " AND category_id = ?";
                $params[] = (int)$category;
            }
            
            $query .= " ORDER BY news_date_add DESC";
            
            // Получаем данные с пагинацией
            $result = $this->paginate($query, $params, $page, $perPage);
            
            // Получаем статистику
            $stats = $this->getNewsStats();
            
            $this->setSuccess([
                'news' => $result['data'],
                'pagination' => $result['pagination'],
                'stats' => $stats
            ]);
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * GET /api/news/{id}
     * Получение информации о новости
     */
    public function show($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(1);
            
            require_once ENGINE_DIR . 'main/db.php';
            
            $news = Database::fetchOne(
                "SELECT * FROM news WHERE news_id = ?",
                [$id]
            );
            
            if (!$news) {
                $this->sendError('News not found', 404);
            }
            
            $this->setSuccess($news);
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/news
     * Создание новой новости
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(1);
            
            $data = $this->getPostData();
            
            // Валидация данных
            $this->validateData($data, [
                'news_title' => 'required|string|min:3|max:255',
                'news_text' => 'required|string|min:10',
                'category_id' => 'required|int'
            ]);
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Обработка изображения
            $imagePath = null;
            if (isset($data['news_image']) && !empty($data['news_image'])) {
                $imagePath = $this->processImage($data['news_image']);
            }
            
            // Создаем новость
            Database::execute(
                "INSERT INTO news (news_title, news_text, news_image, news_date_add, category_id) VALUES (?, ?, ?, NOW(), ?)",
                [
                    $data['news_title'],
                    $data['news_text'],
                    $imagePath,
                    $data['category_id']
                ]
            );
            
            $newsId = Database::lastInsertId();
            
            // Логируем создание новости
            $this->logApiRequest('news_created', [
                'news_id' => $newsId,
                'created_by' => $_SESSION['admin_user_id']
            ]);
            
            // Получаем созданную новость
            $news = Database::fetchOne(
                "SELECT * FROM news WHERE news_id = ?",
                [$newsId]
            );
            
            $this->setSuccess($news, 'News created successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * PUT /api/news/{id}
     * Обновление новости
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(1);
            
            $data = $this->getPostData();
            
            // Валидация данных
            $this->validateData($data, [
                'news_title' => 'required|string|min:3|max:255',
                'news_text' => 'required|string|min:10',
                'category_id' => 'required|int'
            ]);
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем существование новости
            $existingNews = Database::fetchOne(
                "SELECT news_id, news_image FROM news WHERE news_id = ?",
                [$id]
            );
            
            if (!$existingNews) {
                $this->sendError('News not found', 404);
            }
            
            // Обработка изображения
            $imagePath = $existingNews['news_image'];
            if (isset($data['news_image']) && !empty($data['news_image'])) {
                // Удаляем старое изображение
                if ($imagePath && file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $imagePath = $this->processImage($data['news_image']);
            }
            
            // Обновляем новость
            Database::execute(
                "UPDATE news SET news_title = ?, news_text = ?, news_image = ?, category_id = ? WHERE news_id = ?",
                [
                    $data['news_title'],
                    $data['news_text'],
                    $imagePath,
                    $data['category_id'],
                    $id
                ]
            );
            
            // Логируем обновление
            $this->logApiRequest('news_updated', [
                'news_id' => $id,
                'updated_by' => $_SESSION['admin_user_id']
            ]);
            
            // Получаем обновленную новость
            $news = Database::fetchOne(
                "SELECT * FROM news WHERE news_id = ?",
                [$id]
            );
            
            $this->setSuccess($news, 'News updated successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * DELETE /api/news/{id}
     * Удаление новости
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(5); // Только модераторы и выше
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем существование новости
            $news = Database::fetchOne(
                "SELECT news_id, news_image FROM news WHERE news_id = ?",
                [$id]
            );
            
            if (!$news) {
                $this->sendError('News not found', 404);
            }
            
            // Удаляем изображение
            if ($news['news_image'] && file_exists($news['news_image'])) {
                unlink($news['news_image']);
            }
            
            // Удаляем новость
            Database::execute("DELETE FROM news WHERE news_id = ?", [$id]);
            
            // Логируем удаление
            $this->logApiRequest('news_deleted', [
                'news_id' => $id,
                'deleted_by' => $_SESSION['admin_user_id']
            ]);
            
            $this->setSuccess(null, 'News deleted successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/news/upload-image
     * Загрузка изображения для новости
     */
    public function uploadImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAccessLevel(1);
            
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $this->sendError('No image uploaded or upload error', 400);
            }
            
            $file = $_FILES['image'];
            
            // Проверяем тип файла
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                $this->sendError('Invalid file type. Only JPEG, PNG, GIF and WebP are allowed', 400);
            }
            
            // Проверяем размер файла (5MB максимум)
            if ($file['size'] > 5 * 1024 * 1024) {
                $this->sendError('File too large. Maximum size is 5MB', 400);
            }
            
            // Создаем директорию если не существует
            $uploadDir = 'uploads/news/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Генерируем уникальное имя файла
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            // Перемещаем файл
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                $this->sendError('Failed to save uploaded file', 500);
            }
            
            // Логируем загрузку
            $this->logApiRequest('image_uploaded', [
                'filename' => $filename,
                'uploaded_by' => $_SESSION['admin_user_id']
            ]);
            
            $this->setSuccess([
                'filename' => $filename,
                'filepath' => $filepath,
                'url' => '/' . $filepath
            ], 'Image uploaded successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Обработка изображения из base64
     */
    private function processImage($base64Data) {
        // Проверяем формат base64
        if (strpos($base64Data, 'data:image/') !== 0) {
            throw new Exception('Invalid image format');
        }
        
        // Извлекаем данные
        $data = explode(',', $base64Data);
        $imageData = base64_decode($data[1]);
        
        // Определяем тип изображения
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $imageData);
        finfo_close($finfo);
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Invalid image type');
        }
        
        // Создаем директорию если не существует
        $uploadDir = 'uploads/news/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Генерируем уникальное имя файла
        $extension = str_replace('image/', '', $mimeType);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Сохраняем файл
        if (!file_put_contents($filepath, $imageData)) {
            throw new Exception('Failed to save image');
        }
        
        return $filepath;
    }
    
    /**
     * Получение статистики новостей
     */
    private function getNewsStats() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $stats = [];
            
            // Общее количество новостей
            $total = Database::fetchOne("SELECT COUNT(*) as count FROM news");
            $stats['total'] = $total['count'] ?? 0;
            
            // Новости за последние 30 дней
            $recent = Database::fetchOne(
                "SELECT COUNT(*) as count FROM news WHERE news_date_add >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
            );
            $stats['recent_30_days'] = $recent['count'] ?? 0;
            
            // Новости за последние 7 дней
            $recentWeek = Database::fetchOne(
                "SELECT COUNT(*) as count FROM news WHERE news_date_add >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
            );
            $stats['recent_7_days'] = $recentWeek['count'] ?? 0;
            
            // Новости сегодня
            $today = Database::fetchOne(
                "SELECT COUNT(*) as count FROM news WHERE DATE(news_date_add) = CURDATE()"
            );
            $stats['today'] = $today['count'] ?? 0;
            
            return $stats;
        } catch (Exception $e) {
            return [];
        }
    }
} 