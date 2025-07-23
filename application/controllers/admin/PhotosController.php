<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

class PhotosController extends BaseAdminController {
    
    public function index() {
        try {
            require_once 'engine/main/db.php';
            
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            // Получаем общее количество фотографий
            $totalCount = Database::fetchOne("SELECT COUNT(*) as count FROM photos");
            $total = $totalCount['count'] ?? 0;
            
            // Получаем фотографии с пагинацией
            $photos = Database::fetchAll("SELECT * FROM photos ORDER BY photos_date_add DESC LIMIT ? OFFSET ?", [$limit, $offset]);
            
            $totalPages = ceil($total / $limit);
            
            return $this->render('admin/photos/index', [
                'title' => 'Управление фотографиями',
                'currentPage' => 'photos',
                'photos' => $photos,
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Не удалось загрузить фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function upload() {
        return $this->render('admin/photos/upload', [
            'title' => 'Загрузить фотографию',
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
            header('Location: /admin/photos');
            exit;
        }
        
        try {
            require_once 'engine/main/db.php';
            
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $category = $_POST['category'] ?? 'general';
            
            if (empty($title)) {
                throw new Exception('Введите название фотографии');
            }
            
            // Обработка загруженной фотографии
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/photos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . $_FILES['photo']['name'];
                $filePath = $uploadDir . $fileName;
                
                // Проверяем тип файла
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                    throw new Exception('Неподдерживаемый тип файла');
                }
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                    Database::execute(
                        "INSERT INTO photos (photos_title, photos_description, photos_category, photos_path, photos_date_add) VALUES (?, ?, ?, ?, NOW())",
                        [$title, $description, $category, $fileName]
                    );
                    
                    header('Location: /admin/photos?success=1');
                    exit;
                } else {
                    throw new Exception('Ошибка при загрузке фотографии');
                }
            } else {
                throw new Exception('Выберите фотографию для загрузки');
            }
        } catch (Exception $e) {
            header('Location: /admin/photos/upload?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
    
    public function delete($id) {
        try {
            require_once 'engine/main/db.php';
            
            // Получаем информацию о фотографии
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if ($photo) {
                // Удаляем физический файл
                $filePath = 'assets/photos/' . $photo['photos_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Удаляем запись из базы данных
                Database::execute("DELETE FROM photos WHERE photos_id = ?", [$id]);
            }
            
            header('Location: /admin/photos?deleted=1');
            exit;
        } catch (Exception $e) {
            header('Location: /admin/photos?error=1');
            exit;
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                return $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            return $this->render('admin/photos/edit', [
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
            return $this->render('admin/error/error', [
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
                return $this->render('admin/error/404', [
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при обновлении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 
            exit;
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                return $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            return $this->render('admin/photos/edit', [
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
            return $this->render('admin/error/error', [
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
                return $this->render('admin/error/404', [
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при обновлении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 
            exit;
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                return $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            return $this->render('admin/photos/edit', [
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
            return $this->render('admin/error/error', [
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
                return $this->render('admin/error/404', [
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при обновлении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 
            exit;
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                return $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            return $this->render('admin/photos/edit', [
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
            return $this->render('admin/error/error', [
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
                return $this->render('admin/error/404', [
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при обновлении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 
            exit;
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                return $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            return $this->render('admin/photos/edit', [
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
            return $this->render('admin/error/error', [
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
                return $this->render('admin/error/404', [
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при обновлении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 
            exit;
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                return $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            return $this->render('admin/photos/edit', [
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
            return $this->render('admin/error/error', [
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
                return $this->render('admin/error/404', [
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при обновлении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 
            exit;
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                return $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            return $this->render('admin/photos/edit', [
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
            return $this->render('admin/error/error', [
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
                return $this->render('admin/error/404', [
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при обновлении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 
            exit;
        }
    }
    
    public function edit($id) {
        try {
            require_once 'engine/main/db.php';
            
            $photo = Database::fetchOne("SELECT * FROM photos WHERE photos_id = ?", [$id]);
            
            if (!$photo) {
                return $this->render('admin/error/404', [
                    'title' => 'Фотография не найдена',
                    'message' => 'Фотография с ID ' . $id . ' не найдена'
                ]);
            }
            
            return $this->render('admin/photos/edit', [
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
            return $this->render('admin/error/error', [
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
                return $this->render('admin/error/404', [
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
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при обновлении фотографии: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id) {
        return $this->delete($id); // Алиас для метода delete
    }
} 