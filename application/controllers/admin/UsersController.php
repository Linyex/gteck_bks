<?php

class UsersController extends BaseAdminController {
    
    public function index() {
        $this->requireAccessLevel(5); // Только модераторы и выше
        
        try {
            require_once 'engine/main/db.php';
            
            // Получаем список пользователей с пагинацией
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            $users = Database::fetchAll("SELECT * FROM users ORDER BY user_date_reg DESC LIMIT ? OFFSET ?", [$limit, $offset]);
            $totalUsers = Database::fetchOne("SELECT COUNT(*) as count FROM users")['count'];
            $totalPages = ceil($totalUsers / $limit);
            
            return $this->render('admin/users/index', [
                'title' => 'Управление пользователями',
                'users' => $users,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalUsers' => $totalUsers
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке пользователей: ' . $e->getMessage()
            ]);
        }
    }
    
    public function create() {
        $this->requireAccessLevel(10); // Только администраторы
        
        return $this->render('admin/users/create', [
            'title' => 'Создание пользователя'
        ]);
    }
    
    public function store() {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        $username = $this->getPost('username');
        $password = $this->getPost('password');
        $fio = $this->getPost('fio');
        $status = (int)$this->getPost('status', 1);
        $accessLevel = (int)$this->getPost('access_level', 1);
        
        // Валидация
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Логин обязателен';
        }
        
        if (empty($password)) {
            $errors[] = 'Пароль обязателен';
        }
        
        if (empty($fio)) {
            $errors[] = 'ФИО обязательно';
        }
        
        if (strlen($password) < 6) {
            $errors[] = 'Пароль должен содержать минимум 6 символов';
        }
        
        try {
            require_once 'engine/main/db.php';
            
            // Проверяем, не существует ли уже пользователь с таким логином
            $existingUser = Database::fetchOne("SELECT user_id FROM users WHERE user_login = ?", [$username]);
            if ($existingUser) {
                $errors[] = 'Пользователь с таким логином уже существует';
            }
            
            if (empty($errors)) {
                // Создаем хеш пароля
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                // Создаем пользователя
                Database::execute("INSERT INTO users (user_login, user_password, user_fio, user_status, user_access_level, user_date_reg) VALUES (?, ?, ?, ?, ?, NOW())", 
                    [$username, $passwordHash, $fio, $status, $accessLevel]);
                
                return $this->redirect('/admin/users');
            }
        } catch (Exception $e) {
            $errors[] = 'Ошибка при создании пользователя: ' . $e->getMessage();
        }
        
        return $this->render('admin/users/create', [
            'title' => 'Создание пользователя',
            'errors' => $errors,
            'old' => [
                'username' => $username,
                'fio' => $fio,
                'status' => $status,
                'access_level' => $accessLevel
            ]
        ]);
    }
    
    public function edit($id) {
        $this->requireAccessLevel(5);
        
        try {
            require_once 'engine/main/db.php';
            
            $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$id]);
            
            if (!$user) {
                return $this->render('admin/error/404', [
                    'title' => 'Пользователь не найден',
                    'message' => 'Пользователь с ID ' . $id . ' не найден'
                ]);
            }
            
            return $this->render('admin/users/edit', [
                'title' => 'Редактирование пользователя',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при загрузке пользователя: ' . $e->getMessage()
            ]);
        }
    }
    
    public function update($id) {
        $this->requireAccessLevel(5);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        $fio = $this->getPost('fio');
        $status = (int)$this->getPost('status', 1);
        $accessLevel = (int)$this->getPost('access_level', 1);
        $password = $this->getPost('password');
        
        // Валидация
        $errors = [];
        
        if (empty($fio)) {
            $errors[] = 'ФИО обязательно';
        }
        
        if (!empty($password) && strlen($password) < 6) {
            $errors[] = 'Пароль должен содержать минимум 6 символов';
        }
        
        try {
            require_once 'engine/main/db.php';
            
            // Проверяем, существует ли пользователь
            $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$id]);
            if (!$user) {
                return $this->render('admin/error/404', [
                    'title' => 'Пользователь не найден',
                    'message' => 'Пользователь с ID ' . $id . ' не найден'
                ]);
            }
            
            if (empty($errors)) {
                // Обновляем пользователя
                $sql = "UPDATE users SET user_fio = ?, user_status = ?, user_access_level = ?";
                $params = [$fio, $status, $accessLevel];
                
                // Если указан новый пароль, обновляем его
                if (!empty($password)) {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $sql .= ", user_password = ?";
                    $params[] = $passwordHash;
                }
                
                $sql .= " WHERE user_id = ?";
                $params[] = $id;
                
                Database::execute($sql, $params);
                
                return $this->redirect('/admin/users');
            }
        } catch (Exception $e) {
            $errors[] = 'Ошибка при обновлении пользователя: ' . $e->getMessage();
        }
        
        return $this->render('admin/users/edit', [
            'title' => 'Редактирование пользователя',
            'user' => $user,
            'errors' => $errors
        ]);
    }
    
    public function delete($id) {
        $this->requireAccessLevel(10);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/users');
        }
        
        try {
            require_once 'engine/main/db.php';
            
            // Проверяем, не пытается ли пользователь удалить сам себя
            if ($id == $_SESSION['admin_user_id']) {
                return $this->render('admin/error/error', [
                    'title' => 'Ошибка',
                    'message' => 'Вы не можете удалить свой собственный аккаунт'
                ]);
            }
            
            // Удаляем пользователя
            Database::execute("DELETE FROM users WHERE user_id = ?", [$id]);
            
            return $this->redirect('/admin/users');
        } catch (Exception $e) {
            return $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Ошибка при удалении пользователя: ' . $e->getMessage()
            ]);
        }
    }
} 