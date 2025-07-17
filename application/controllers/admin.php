<?php

class adminController extends BaseController {
    
    public function index() {
        return $this->render('admin/index', [
            'title' => 'Административная панель'
        ]);
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            
            // Проверяем пользователя в базе данных
            try {
                require_once 'engine/main/db.php';
                
                $user = Database::fetchOne("SELECT * FROM users WHERE user_login = ?", [$username]);
                
                if ($user && password_verify($password, $user['user_password'])) {
                    // Успешная аутентификация
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_user_id'] = $user['user_id'];
                    $_SESSION['admin_username'] = $user['user_login'];
                    $_SESSION['admin_fio'] = $user['user_fio'];
                    
                    return $this->redirect('/admin');
                } else {
                    $error = 'Неверные логин или пароль';
                }
            } catch (Exception $e) {
                $error = 'Ошибка подключения к базе данных: ' . $e->getMessage();
            }
        }
        
        return $this->render('admin/login', [
            'error' => $error ?? null,
            'title' => 'Вход в админ-панель'
        ]);
    }
    
    public function logout() {
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_user_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_fio']);
        return $this->redirect('/admin/login');
    }
}
