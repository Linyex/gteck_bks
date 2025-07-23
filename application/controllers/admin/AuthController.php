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
                        
                        // Логируем успешный вход
                        $this->logLoginAttempt($user['user_id'], $username, $_SERVER['REMOTE_ADDR'], true, 'Успешный вход');
                        
                        // Создаем сессию
                        $this->createUserSession($user['user_id'], $_SERVER['REMOTE_ADDR']);
                        
                        // Логируем активность
                        $this->logUserActivity($user['user_id'], 'login', 'Успешный вход в систему', $_SERVER['REMOTE_ADDR']);
                        
                        return $this->redirect('/admin/dashboard');
                    } else {
                        $error = 'Неверные логин или пароль';
                        
                        // Логируем неудачную попытку
                        if ($user) {
                            $this->logLoginAttempt($user['user_id'], $username, $_SERVER['REMOTE_ADDR'], false, 'Неверный пароль');
                            $this->logUserActivity($user['user_id'], 'failed_login', 'Неудачная попытка входа', $_SERVER['REMOTE_ADDR']);
                        } else {
                            // Логируем попытку входа с несуществующим пользователем
                            $this->logLoginAttempt(null, $username, $_SERVER['REMOTE_ADDR'], false, 'Несуществующий пользователь');
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
            $this->logUserActivity($_SESSION['admin_user_id'], 'logout', 'Выход из системы', $_SERVER['REMOTE_ADDR']);
            $this->terminateUserSession($_SESSION['admin_user_id']);
        }
        
        // Очищаем сессию
        session_destroy();
        
        return $this->redirect('/admin/login');
    }
    
    private function logLoginAttempt($userId, $username, $ip, $success, $reason = '') {
        try {
            require_once 'engine/main/db.php';
            
            Database::execute(
                "INSERT INTO login_attempts (user_id, username, ip_address, success, attempt_time, failure_reason) VALUES (?, ?, ?, ?, NOW(), ?)",
                [$userId, $username, $ip, $success ? 1 : 0, $reason]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки логирования
        }
    }
    
    private function createUserSession($userId, $ip) {
        try {
            require_once 'engine/main/db.php';
            
            $sessionToken = session_id();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            Database::execute(
                "INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, is_active, created_at, last_activity) VALUES (?, ?, ?, ?, 1, NOW(), NOW())",
                [$userId, $sessionToken, $ip, $userAgent]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки логирования
        }
    }
    
    private function terminateUserSession($userId) {
        try {
            require_once 'engine/main/db.php';
            
            $sessionToken = session_id();
            
            Database::execute(
                "UPDATE user_sessions SET is_active = 0, logout_time = NOW() WHERE user_id = ? AND session_token = ?",
                [$userId, $sessionToken]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки логирования
        }
    }
    
    private function logUserActivity($userId, $actionType, $description, $ip) {
        try {
            require_once 'engine/main/db.php';
            
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            Database::execute(
                "INSERT INTO user_activity (user_id, action_type, activity_description, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                [$userId, $actionType, $description, $ip, $userAgent]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки логирования
        }
    }
} 