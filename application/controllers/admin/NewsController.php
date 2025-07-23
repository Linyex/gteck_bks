<?php

// Подключаем необходимые файлы
require_once APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';

class NewsController extends BaseAdminController {
    
    public function index() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $news = Database::fetchAll("SELECT * FROM news ORDER BY news_date_add DESC");
            $total = count($news);
            
            return $this->render('admin/news/index', [
                'title' => 'Управление новостями',
                'currentPage' => 'news',
                'news' => $news,
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Не удалось загрузить новости: ' . $e->getMessage()
            ]);
        }
    }
    
    public function create() {
        return $this->render('admin/news/create', [
            'title' => 'Создать новость',
            'currentPage' => 'news',
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
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $news = Database::fetchOne("SELECT * FROM news WHERE news_id = ?", [$id]);
            
            if (!$news) {
                header('Location: /admin/news');
                exit;
            }
            
            return $this->render('admin/news/edit', [
                'title' => 'Редактировать новость',
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
            header('Location: /admin/news');
            exit;
        }
    }
    
    public function update($id) {
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
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute("DELETE FROM news WHERE news_id = ?", [$id]);
            
            header('Location: /admin/news?deleted=1');
            exit;
        } catch (Exception $e) {
            header('Location: /admin/news?error=1');
            exit;
        }
    }
    
    public function destroy($id) {
        return $this->delete($id);
    }
} 