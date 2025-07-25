<?php

require_once APPLICATION_DIR . 'controllers/admin/api/BaseApiController.php';

class AuthApiController extends BaseApiController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * POST /api/auth/login
     * Аутентификация пользователя
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $data = $this->getPostData();
            
            // Валидация данных
            $this->validateData($data, [
                'username' => 'required|string|min:3|max:50',
                'password' => 'required|string|min:6'
            ]);
            
            $username = $data['username'];
            $password = $data['password'];
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Проверяем пользователя
            $user = Database::fetchOne(
                "SELECT * FROM users WHERE user_login = ? AND user_status = 1",
                [$username]
            );
            
            if (!$user || !password_verify($password, $user['user_password'])) {
                $this->logApiRequest('failed_login', ['username' => $username]);
                $this->sendError('Invalid credentials', 401);
            }
            
            // Проверяем 2FA
            $user2fa = Database::fetchOne(
                "SELECT is_enabled FROM user_2fa WHERE user_id = ?",
                [$user['user_id']]
            );
            
            if ($user2fa && $user2fa['is_enabled']) {
                // Если 2FA включена, возвращаем информацию о необходимости 2FA
                $this->setSuccess([
                    'requires_2fa' => true,
                    'user_id' => $user['user_id'],
                    'username' => $user['user_login']
                ], '2FA required');
                $this->sendResponse();
            }
            
            // Создаем сессию
            session_start();
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user_id'] = $user['user_id'];
            $_SESSION['admin_username'] = $user['user_login'];
            $_SESSION['admin_fio'] = $user['user_fio'];
            $_SESSION['admin_access_level'] = $user['user_access_level'];
            
            // Логируем успешный вход
            $this->logApiRequest('successful_login', [
                'user_id' => $user['user_id'],
                'username' => $user['user_login']
            ]);
            
            // Возвращаем данные пользователя (без пароля)
            unset($user['user_password']);
            
            $this->setSuccess([
                'user' => $user,
                'session_id' => session_id()
            ], 'Login successful');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/auth/2fa/verify
     * Проверка 2FA кода
     */
    public function verify2fa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $data = $this->getPostData();
            
            // Валидация данных
            $this->validateData($data, [
                'user_id' => 'required|int',
                'code' => 'required|string|min:6|max:8'
            ]);
            
            $userId = $data['user_id'];
            $code = $data['code'];
            
            require_once ENGINE_DIR . 'main/db.php';
            require_once ENGINE_DIR . 'libs/GoogleAuthenticator.php';
            
            $authenticator = new GoogleAuthenticator();
            
            // Получаем настройки 2FA пользователя
            $user2fa = Database::fetchOne(
                "SELECT secret_key, backup_codes FROM user_2fa WHERE user_id = ? AND is_enabled = 1",
                [$userId]
            );
            
            if (!$user2fa) {
                $this->sendError('2FA not enabled for this user', 400);
            }
            
            $success = false;
            $usedBackupCode = false;
            
            // Проверяем обычный код
            if ($authenticator->verifyCode($user2fa['secret_key'], $code)) {
                $success = true;
            } else {
                // Проверяем резервный код
                if ($authenticator->verifyBackupCode($userId, $code)) {
                    $success = true;
                    $usedBackupCode = true;
                }
            }
            
            if ($success) {
                // Получаем данные пользователя
                $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$userId]);
                
                // Создаем сессию
                session_start();
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $user['user_id'];
                $_SESSION['admin_username'] = $user['user_login'];
                $_SESSION['admin_fio'] = $user['user_fio'];
                $_SESSION['admin_access_level'] = $user['user_access_level'];
                
                // Логируем успешную 2FA проверку
                $this->logApiRequest('2fa_verified', [
                    'user_id' => $userId,
                    'used_backup_code' => $usedBackupCode
                ]);
                
                // Возвращаем данные пользователя (без пароля)
                unset($user['user_password']);
                
                $this->setSuccess([
                    'user' => $user,
                    'session_id' => session_id(),
                    'used_backup_code' => $usedBackupCode
                ], '2FA verification successful');
                $this->sendResponse();
            } else {
                // Логируем неудачную попытку
                $this->logApiRequest('2fa_failed', [
                    'user_id' => $userId,
                    'code_entered' => $code
                ]);
                
                $this->sendError('Invalid 2FA code', 401);
            }
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/auth/logout
     * Выход из системы
     */
    public function logout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAuth();
            
            $userId = $_SESSION['admin_user_id'];
            
            // Логируем выход
            $this->logApiRequest('logout', ['user_id' => $userId]);
            
            // Очищаем сессию
            session_destroy();
            
            $this->setSuccess(null, 'Logout successful');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * GET /api/auth/me
     * Получение информации о текущем пользователе
     */
    public function me() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAuth();
            
            $userId = $_SESSION['admin_user_id'];
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем данные пользователя
            $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$userId]);
            
            if (!$user) {
                $this->sendError('User not found', 404);
            }
            
            // Получаем настройки 2FA
            $user2fa = Database::fetchOne(
                "SELECT is_enabled FROM user_2fa WHERE user_id = ?",
                [$userId]
            );
            
            // Возвращаем данные пользователя (без пароля)
            unset($user['user_password']);
            $user['2fa_enabled'] = $user2fa ? (bool)$user2fa['is_enabled'] : false;
            
            $this->setSuccess([
                'user' => $user,
                'session_id' => session_id()
            ]);
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/auth/refresh
     * Обновление сессии
     */
    public function refresh() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAuth();
            
            $userId = $_SESSION['admin_user_id'];
            
            // Обновляем время последней активности
            require_once ENGINE_DIR . 'main/db.php';
            
            Database::execute(
                "UPDATE user_sessions SET last_activity = NOW() WHERE user_id = ? AND session_token = ?",
                [$userId, session_id()]
            );
            
            $this->setSuccess([
                'session_id' => session_id(),
                'refreshed_at' => date('Y-m-d H:i:s')
            ], 'Session refreshed');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * POST /api/auth/change-password
     * Смена пароля
     */
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError('Method not allowed', 405);
        }
        
        try {
            $this->requireAuth();
            
            $data = $this->getPostData();
            
            // Валидация данных
            $this->validateData($data, [
                'current_password' => 'required|string|min:6',
                'new_password' => 'required|string|min:6|max:255',
                'confirm_password' => 'required|string|min:6|max:255'
            ]);
            
            $currentPassword = $data['current_password'];
            $newPassword = $data['new_password'];
            $confirmPassword = $data['confirm_password'];
            
            if ($newPassword !== $confirmPassword) {
                $this->sendError('New passwords do not match', 400);
            }
            
            $userId = $_SESSION['admin_user_id'];
            
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем текущий пароль
            $user = Database::fetchOne(
                "SELECT user_password FROM users WHERE user_id = ?",
                [$userId]
            );
            
            if (!password_verify($currentPassword, $user['user_password'])) {
                $this->sendError('Current password is incorrect', 400);
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $userId]
            );
            
            // Логируем смену пароля
            $this->logApiRequest('password_changed', ['user_id' => $userId]);
            
            $this->setSuccess(null, 'Password changed successfully');
            $this->sendResponse();
            
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
} 