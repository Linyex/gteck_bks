<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

class PhotosController extends BaseAdminController {
    
    public function index() {
        try {
            require_once 'engine/main/db.php';
            
            $photos = Database::fetchAll("SELECT * FROM photos ORDER BY photos_date_add DESC");
            
            $this->render('admin/photos/index', [
                'title' => 'Управление фотографиями',
                'currentPage' => 'photos',
                'photos' => $photos,
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
                'message' => 'Ошибка при загрузке фотографий: ' . $e->getMessage()
            ]);
        }
    }
    
    public function upload() {
        $this->render('admin/photos/upload', [
            'title' => 'Загрузка фотографии',
            'currentPage' => 'photos',
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
            return $this->redirect('/admin/photos');
        }
        
        try {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $category = $_POST['category'] ?? 'general';
            
            if (empty($title)) {
                throw new Exception('Введите название фотографии');
            }
            
            if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Выберите файл для загрузки');
            }
            
            $uploadDir = 'assets/photos/';
            $fileName = time() . '_' . $_FILES['photo']['name'];
            $filePath = $uploadDir . $fileName;
            
            // Проверяем тип файла
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                throw new Exception('Неподдерживаемый тип файла');
            }
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                require_once 'engine/main/db.php';
                
                Database::execute(
                    "INSERT INTO photos (photos_title, photos_description, photos_path, photos_category, photos_date_add) VALUES (?, ?, ?, ?, NOW())",
                    [$title, $description, $fileName, $category]
                );
                
                return $this->redirect('/admin/photos?uploaded=1');
            } else {
                throw new Exception('Ошибка при загрузке файла');
            }
            
        } catch (Exception $e) {
            $this->render('admin/photos/upload', [
                'title' => 'Загрузка фотографии',
                'currentPage' => 'photos',
                'error' => $e->getMessage(),
                'additional_css' => [
                    '/assets/css/admin-cyberpunk.css'
                ],
                'additional_js' => [
                    '/assets/js/background-animations.js',
                    '/assets/js/admin-common.js'
                ]
            ]);
        }
    }
    
    public function delete($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            // Удаляем файл
            $filePath = 'assets/photos/' . $photo['photos_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Удаляем запись из базы данных
            Database::execute("DELETE FROM photos WHERE photos_id = ?", [$id]);
            
            return $this->redirect('/admin/photos?deleted=1');
            
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при удалении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            $this->render('admin/photos/edit', [
                'title' => 'Редактирование фотографии',
                'currentPage' => 'photos',
                'photo' => $photo,
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
                'message' => 'Ошибка при загрузке фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/photos');
        }
        
        try {
            require_once 'engine/main/db.php';
            
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $category = $_POST['category'] ?? 'general';
            
            if (empty($title)) {
                throw new Exception('Введите название фотографии');
            }
            
            // Проверяем, существует ли фотография
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            if (!$photo) {
                $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            // Обновляем информацию о фотографии
            Database::execute(
                "UPDATE photos SET photos_title = ?, photos_description = ?, photos_category = ? WHERE photos_id = ?",
                [$title, $description, $category, $id]
            );
            
            // Обработка новой фотографии, если загружена
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/photos/';
                
                // Удаляем старый файл
                $oldFilePath = $uploadDir . $photo['photos_path'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                
                // Загружаем новый файл
                $fileName = time() . '_' . $_FILES['photo']['name'];
                $filePath = $uploadDir . $fileName;
                
                // Проверяем тип файла
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                    throw new Exception('Неподдерживаемый тип файла');
                }
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                    Database::execute(
                        "UPDATE photos SET photos_path = ? WHERE photos_id = ?",
                        [$fileName, $id]
                    );
                } else {
                    throw new Exception('Ошибка при загрузке новой фотографии');
                }
            }
            
            return $this->redirect('/admin/photos?updated=1');
            
        } catch (Exception $e) {
            $this->render('admin/photos/edit', [
                'title' => 'Редактирование фотографии',
                'currentPage' => 'photos',
                'photo' => $photo ?? null,
                'error' => $e->getMessage(),
                'additional_css' => [
                    '/assets/css/admin-cyberpunk.css'
                ],
                'additional_js' => [
                    '/assets/js/background-animations.js',
                    '/assets/js/admin-common.js'
                ]
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 