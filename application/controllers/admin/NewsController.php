<?php

// Подключаем необходимые файлы
require_once APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';

class NewsController extends BaseAdminController {
    
    public function index() {
        // Доступ к новостям: 4 (Зам. по воспитательной), 6 (Директор), 7 (Соц. педагог), 8 (Психолог), 10 (Админ)
        if (!$this->hasAnyAccessLevel([4,6,7,8,10])) { $this->requireAccessLevel(999); }
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем новости с категориями
            $news = Database::fetchAll("
                SELECT n.*, nc.name as category_name, nc.type as category_type 
                FROM news n 
                LEFT JOIN news_categories nc ON n.category_id = nc.id 
                ORDER BY n.news_date_add DESC
            ");
            
            // Получаем категории для фильтра
            $categories = Database::fetchAll("
                SELECT * FROM news_categories 
                WHERE is_active = 1 
                ORDER BY sort_order ASC
            ");
            
            $total = count($news);
            
            $this->render('admin/news/index', [
                'title' => 'Управление новостями',
                'currentPage' => 'news',
                'news' => $news,
                'categories' => $categories,
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
                'message' => 'Не удалось загрузить новости: ' . $e->getMessage()
            ]);
        }
    }
    
    public function create() {
        if (!$this->hasAnyAccessLevel([4,6,7,8,10])) { $this->requireAccessLevel(999); }
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем категории для формы
            $categories = Database::fetchAll("
                SELECT * FROM news_categories 
                WHERE is_active = 1 
                ORDER BY sort_order ASC
            ");
            
            $this->render('admin/news/create', [
                'title' => 'Создать новость',
                'currentPage' => 'news',
                'categories' => $categories,
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
                'message' => 'Не удалось загрузить категории: ' . $e->getMessage()
            ]);
        }
    }
    
    public function store() {
        if (!$this->hasAnyAccessLevel([4,6,7,8,10])) { $this->requireAccessLevel(999); }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/news');
            exit;
        }
        
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $categoryId = $_POST['category_id'] ?? 1;
            $userId = $_SESSION['admin_user_id'] ?? 1;
            
            if (empty($title) || empty($content)) {
                throw new Exception('Заполните все обязательные поля');
            }
            
            // Обработка загруженного изображения
            $imagePath = '';
            if (isset($_FILES['main_photo']) && $_FILES['main_photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/news/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['main_photo']['name']);
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['main_photo']['tmp_name'], $uploadPath)) {
                    $imagePath = $uploadPath;
                }
            }
            
            // Если изображение не загружено, используем пустое значение
            if (empty($imagePath)) {
                $imagePath = '';
            }
            
            Database::execute(
                "INSERT INTO news (news_title, news_text, news_image, category_id, user_id, news_date_add) VALUES (?, ?, ?, ?, ?, NOW())",
                [$title, $content, $imagePath, $categoryId, $userId]
            );
            
            header('Location: /admin/news?success=1');
            exit;
        } catch (Exception $e) {
            header('Location: /admin/news/create?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
    
    public function edit($id) {
        if (!$this->hasAnyAccessLevel([4,6,7,8,10])) { $this->requireAccessLevel(999); }
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем новость с категорией
            $news = Database::fetchOne("
                SELECT n.*, nc.name as category_name, nc.type as category_type 
                FROM news n 
                LEFT JOIN news_categories nc ON n.category_id = nc.id 
                WHERE n.news_id = ?
            ", [$id]);
            
            if (!$news) {
                header('Location: /admin/news');
                exit;
            }
            
            // Получаем категории для формы
            $categories = Database::fetchAll("
                SELECT * FROM news_categories 
                WHERE is_active = 1 
                ORDER BY sort_order ASC
            ");
            
            $this->render('admin/news/edit', [
                'title' => 'Редактировать новость',
                'currentPage' => 'news',
                'news' => $news,
                'categories' => $categories,
                'additional_css' => [
                    '/assets/css/admin-cyberpunk.css'
                ],
                'additional_js' => [
                    '/assets/js/background-animations.js',
                    '/assets/js/admin-common.js'
                ]
            ]);
        } catch (Exception $e) {
            header('Location: /admin/news');
            exit;
        }
    }
    
    public function update($id) {
        if (!$this->hasAnyAccessLevel([4,6,7,8,10])) { $this->requireAccessLevel(999); }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/news');
            exit;
        }
        
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $categoryId = $_POST['category_id'] ?? 1;
            
            if (empty($title) || empty($content)) {
                throw new Exception('Заполните все обязательные поля');
            }
            
            // Обработка загруженного изображения
            $imagePath = '';
            if (isset($_FILES['main_photo']) && $_FILES['main_photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/news/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['main_photo']['name']);
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['main_photo']['tmp_name'], $uploadPath)) {
                    $imagePath = $uploadPath;
                }
            }
            
            // Если изображение не загружено, оставляем старое
            if (empty($imagePath)) {
                Database::execute(
                    "UPDATE news SET news_title = ?, news_text = ?, category_id = ? WHERE news_id = ?",
                    [$title, $content, $categoryId, $id]
                );
            } else {
                Database::execute(
                    "UPDATE news SET news_title = ?, news_text = ?, news_image = ?, category_id = ? WHERE news_id = ?",
                    [$title, $content, $imagePath, $categoryId, $id]
                );
            }
            
            header('Location: /admin/news?success=1');
            exit;
        } catch (Exception $e) {
            header('Location: /admin/news/edit/' . $id . '?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
    
    public function delete($id) {
        $this->requireAccessLevel(4);
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем существование новости
            $news = Database::fetchOne("SELECT * FROM news WHERE news_id = ?", [$id]);
            if (!$news) {
                if ($this->isAjaxRequest()) {
                    return $this->jsonResponse(['success' => false, 'message' => 'Новость не найдена']);
                }
                header('Location: /admin/news?error=not_found');
                exit;
            }
            
            // Удаляем файл изображения, если он есть
            if (!empty($news['news_image']) && file_exists($news['news_image'])) {
                unlink($news['news_image']);
            }
            
            // Удаляем новость из базы данных
            Database::execute("DELETE FROM news WHERE news_id = ?", [$id]);
            
            if ($this->isAjaxRequest()) {
                return $this->jsonResponse(['success' => true, 'message' => 'Новость успешно удалена']);
            }
            
            header('Location: /admin/news?deleted=1');
            exit;
        } catch (Exception $e) {
            if ($this->isAjaxRequest()) {
                return $this->jsonResponse(['success' => false, 'message' => 'Ошибка при удалении: ' . $e->getMessage()]);
            }
            header('Location: /admin/news?error=1');
            exit;
        }
    }
    
    // Проверка AJAX запроса
    protected function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    // Отправка JSON ответа
    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public function destroy($id) {
        return $this->delete($id);
    }
    
    // Страница подтверждения удаления новости
    public function confirmDelete($id) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем информацию о новости
            $news = Database::fetchOne("SELECT * FROM news WHERE news_id = ?", [$id]);
            
            if (!$news) {
                header('Location: /admin/news?error=not_found');
                exit;
            }
            
            $this->render('admin/news/confirm-delete', [
                'title' => 'Подтверждение удаления',
                'currentPage' => 'news',
                'news' => $news,
                'additional_css' => [
                    '/assets/css/admin-cyberpunk.css'
                ],
                'additional_js' => [
                    '/assets/js/background-animations.js',
                    '/assets/js/admin-common.js'
                ]
            ]);
        } catch (Exception $e) {
            header('Location: /admin/news?error=1');
            exit;
        }
    }
} 