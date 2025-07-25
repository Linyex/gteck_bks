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
                    require_once ENGINE_DIR . 'main/db.php';
                    
                    $user = Database::fetchOne("SELECT * FROM users WHERE user_login = ? AND user_status = 1", [$username]);
                    
                    if ($user && password_verify($password, $user['user_password'])) {
                        // Проверяем, включена ли 2FA (проверяем существование таблицы)
                        $user2fa = null;
                        try {
                            $tables2fa = Database::fetchAll("SHOW TABLES LIKE 'user_2fa'");
                            if (count($tables2fa) > 0) {
                                $user2fa = Database::fetchOne(
                                    "SELECT is_enabled FROM user_2fa WHERE user_id = ?",
                                    [$user['user_id']]
                                );
                            }
                        } catch (Exception $e) {
                            // Игнорируем ошибки 2FA
                        }
                        
                        if ($user2fa && $user2fa['is_enabled']) {
                            // Если 2FA включена, сохраняем временную сессию и перенаправляем на проверку
                            $_SESSION['temp_user_id'] = $user['user_id'];
                            $_SESSION['temp_username'] = $user['user_login'];
                            $_SESSION['temp_fio'] = $user['user_fio'];
                            $_SESSION['temp_access_level'] = $user['user_access_level'];
                            
                            // Логируем попытку входа с 2FA
                            $this->logLoginAttempt($user['user_id'], $username, $_SERVER['REMOTE_ADDR'], true, 'Вход с 2FA');
                            
                            return $this->redirect('/admin/2fa/verify');
                        } else {
                            // Если 2FA не включена, создаем обычную сессию
                            $_SESSION['admin_logged_in'] = true;
                            $_SESSION['admin_user_id'] = $user['user_id'];
                            $_SESSION['admin_username'] = $user['user_login'];
                            $_SESSION['admin_fio'] = $user['user_fio'];
                            $_SESSION['admin_access_level'] = $user['user_access_level'];
                            
                            // Логируем успешный вход
                            $this->logLoginAttempt($user['user_id'], $username, $_SERVER['REMOTE_ADDR'], true, 'Успешный вход');
                            
                            // Обновляем информацию о последнем входе
                            $this->updateLastLogin($user['user_id'], $_SERVER['REMOTE_ADDR']);
                            
                            // Создаем сессию
                            $this->createUserSession($user['user_id'], $_SERVER['REMOTE_ADDR']);
                            
                            // Логируем активность
                            $this->logUserActivity($user['user_id'], 'login', 'Успешный вход в систему', $_SERVER['REMOTE_ADDR']);
                            
                            return $this->redirect('/admin/dashboard');
                        }
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
        
        // Очищаем все сессии
        session_destroy();
        
        return $this->redirect('/admin/login');
    }
    
    private function logLoginAttempt($userId, $username, $ip, $success, $reason = '') {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
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
            require_once ENGINE_DIR . 'main/db.php';
            
            $sessionToken = session_id();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            Database::execute(
                "INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, created_at, last_activity) VALUES (?, ?, ?, ?, NOW(), NOW())",
                [$userId, $sessionToken, $ip, $userAgent]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки создания сессии
        }
    }
    
    private function terminateUserSession($userId) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $sessionToken = session_id();
            
            // Деактивируем сессию вместо удаления
            Database::execute(
                "UPDATE user_sessions SET is_active = 0, last_activity = NOW() WHERE user_id = ? AND session_token = ?",
                [$userId, $sessionToken]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки завершения сессии
        }
    }
    
    private function updateLastLogin($userId, $ip) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute(
                "UPDATE users SET user_last_login = NOW(), user_last_ip = ?, user_login_count = user_login_count + 1 WHERE user_id = ?",
                [$ip, $userId]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки обновления
        }
    }
    
    private function logUserActivity($userId, $actionType, $description, $ip) {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute(
                "INSERT INTO user_activity_log (user_id, action_type, description, ip_address, activity_time) VALUES (?, ?, ?, ?, NOW())",
                [$userId, $actionType, $description, $ip]
            );
        } catch (Exception $e) {
            // Игнорируем ошибки логирования активности
        }
    }
} 