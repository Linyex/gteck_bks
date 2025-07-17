<?php

class AuthController extends BaseController {
    
    public function login() {
        // Если уже авторизован, перенаправляем на дашборд
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
            return $this->redirect('/admin/dashboard');
        }
        
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            
            if (empty($username) || empty($password)) {
                $error = 'Пожалуйста, заполните все поля';
            } else {
                try {
                    require_once 'engine/main/db.php';
                    
                    $user = Database::fetchOne("SELECT * FROM users WHERE user_login = ? AND user_status = 1", [$username]);
                    
                    if ($user && password_verify($password, $user['user_password'])) {
                        // Успешная аутентификация
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_user_id'] = $user['user_id'];
                        $_SESSION['admin_username'] = $user['user_login'];
                        $_SESSION['admin_fio'] = $user['user_fio'];
                        $_SESSION['admin_access_level'] = $user['user_access_level'];
                        
                        // Логируем вход
                        $this->logLogin($user['user_id'], $_SERVER['REMOTE_ADDR'], 'success');
                        
                        return $this->redirect('/admin/dashboard');
                    } else {
                        $error = 'Неверные логин или пароль';
                        
                        // Логируем неудачную попытку
                        if ($user) {
                            $this->logLogin($user['user_id'], $_SERVER['REMOTE_ADDR'], 'failed');
                        }
                    }
                } catch (Exception $e) {
                    $error = 'Ошибка подключения к базе данных';
                }
            }
        }
        
        return $this->render('admin/auth/login', [
            'error' => $error,
            'title' => 'Вход в панель управления'
        ]);
    }
    
    public function logout() {
        // Логируем выход
        if (isset($_SESSION['admin_user_id'])) {
            $this->logLogout($_SESSION['admin_user_id'], $_SERVER['REMOTE_ADDR']);
        }
        
        // Очищаем сессию
        session_destroy();
        
        return $this->redirect('/admin/login');
    }
    
    private function logLogin($userId, $ip, $status) {
        try {
            require_once 'engine/main/db.php';
            
            // Проверяем, есть ли таблица auth_log
            $tableExists = Database::fetchOne("SHOW TABLES LIKE 'auth_log'");
            
            if ($tableExists) {
                Database::execute("INSERT INTO auth_log (user_id, ip_address, status, login_time) VALUES (?, ?, ?, NOW())", 
                    [$userId, $ip, $status]);
            }
        } catch (Exception $e) {
            // Игнорируем ошибки логирования
        }
    }
    
    private function logLogout($userId, $ip) {
        try {
            require_once 'engine/main/db.php';
            
            $tableExists = Database::fetchOne("SHOW TABLES LIKE 'auth_log'");
            
            if ($tableExists) {
                Database::execute("INSERT INTO auth_log (user_id, ip_address, status, login_time) VALUES (?, ?, 'logout', NOW())", 
                    [$userId, $ip]);
            }
        } catch (Exception $e) {
            // Игнорируем ошибки логирования
        }
    }
} 