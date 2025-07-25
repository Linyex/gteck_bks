<?php

// Подключаем необходимые файлы
require_once ENGINE_DIR . 'BaseController.php';
require_once APPLICATION_DIR . 'controllers/admin/BaseAdminController.php';
require_once ENGINE_DIR . 'libs/GoogleAuthenticator.php';

class TwoFactorController extends BaseAdminController {
    
    private $authenticator;
    
    public function __construct() {
        parent::__construct();
        $this->authenticator = new GoogleAuthenticator();
    }
    
    /**
     * Показывает страницу настройки 2FA
     */
    public function index() {
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            $userId = $_SESSION['admin_user_id'];
            
            // Получаем текущие настройки 2FA
            $user2fa = Database::fetchOne(
                "SELECT * FROM user_2fa WHERE user_id = ?",
                [$userId]
            );
            
            // Если 2FA не настроена, создаем новую
            if (!$user2fa) {
                $secret = $this->authenticator->generateSecret();
                $backupCodes = $this->authenticator->generateBackupCodes();
                
                Database::execute(
                    "INSERT INTO user_2fa (user_id, secret_key, backup_codes, is_enabled) VALUES (?, ?, ?, 0)",
                    [$userId, $secret, json_encode($backupCodes)]
                );
                
                $user2fa = [
                    'secret_key' => $secret,
                    'backup_codes' => json_encode($backupCodes),
                    'is_enabled' => 0
                ];
            }
            
            // Генерируем QR код
            $qrCodeUrl = $this->authenticator->generateQRCode(
                $_SESSION['admin_username'],
                $user2fa['secret_key'],
                'Админ панель',
                ['issuer' => 'NoContrGTEC Admin']
            );
            
            return $this->render('admin/2fa/index', [
                'title' => 'Двухфакторная аутентификация',
                'currentPage' => '2fa',
                'secret' => $user2fa['secret_key'],
                'qrCode' => $qrCodeUrl,
                'backupCodes' => json_decode($user2fa['backup_codes'], true),
                'isEnabled' => $user2fa['is_enabled'],
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
                'message' => 'Не удалось загрузить настройки 2FA: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Активирует 2FA
     */
    public function enable() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/2fa');
        }
        
        $code = $this->getPost('code');
        $userId = $_SESSION['admin_user_id'];
        
        if (empty($code)) {
            return $this->render('admin/2fa/index', [
                'title' => 'Двухфакторная аутентификация',
                'error' => 'Введите код подтверждения'
            ]);
        }
        
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем секретный ключ пользователя
            $user2fa = Database::fetchOne(
                "SELECT secret_key FROM user_2fa WHERE user_id = ?",
                [$userId]
            );
            
            if (!$user2fa) {
                return $this->render('admin/2fa/index', [
                    'title' => 'Двухфакторная аутентификация',
                    'error' => '2FA не настроена'
                ]);
            }
            
            // Проверяем код
            if ($this->authenticator->verifyCode($user2fa['secret_key'], $code)) {
                // Активируем 2FA
                Database::execute(
                    "UPDATE user_2fa SET is_enabled = 1 WHERE user_id = ?",
                    [$userId]
                );
                
                // Обновляем статус в таблице пользователей
                Database::execute(
                    "UPDATE users SET 2fa_enabled = 1 WHERE user_id = ?",
                    [$userId]
                );
                
                // Логируем активность
                $this->logUserActivity($userId, '2fa_enabled', 'Активирована двухфакторная аутентификация', $_SERVER['REMOTE_ADDR']);
                
                return $this->render('admin/2fa/success', [
                    'title' => '2FA активирована',
                    'message' => 'Двухфакторная аутентификация успешно активирована'
                ]);
            } else {
                return $this->render('admin/2fa/index', [
                    'title' => 'Двухфакторная аутентификация',
                    'error' => 'Неверный код подтверждения'
                ]);
            }
        } catch (Exception $e) {
            return $this->render('admin/2fa/index', [
                'title' => 'Двухфакторная аутентификация',
                'error' => 'Ошибка при активации 2FA: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Деактивирует 2FA
     */
    public function disable() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/2fa');
        }
        
        $code = $this->getPost('code');
        $userId = $_SESSION['admin_user_id'];
        
        if (empty($code)) {
            return $this->render('admin/2fa/index', [
                'title' => 'Двухфакторная аутентификация',
                'error' => 'Введите код подтверждения'
            ]);
        }
        
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем секретный ключ пользователя
            $user2fa = Database::fetchOne(
                "SELECT secret_key FROM user_2fa WHERE user_id = ? AND is_enabled = 1",
                [$userId]
            );
            
            if (!$user2fa) {
                return $this->render('admin/2fa/index', [
                    'title' => 'Двухфакторная аутентификация',
                    'error' => '2FA не активирована'
                ]);
            }
            
            // Проверяем код
            if ($this->authenticator->verifyCode($user2fa['secret_key'], $code)) {
                // Деактивируем 2FA
                Database::execute(
                    "UPDATE user_2fa SET is_enabled = 0 WHERE user_id = ?",
                    [$userId]
                );
                
                // Обновляем статус в таблице пользователей
                Database::execute(
                    "UPDATE users SET 2fa_enabled = 0 WHERE user_id = ?",
                    [$userId]
                );
                
                // Логируем активность
                $this->logUserActivity($userId, '2fa_disabled', 'Деактивирована двухфакторная аутентификация', $_SERVER['REMOTE_ADDR']);
                
                return $this->render('admin/2fa/success', [
                    'title' => '2FA деактивирована',
                    'message' => 'Двухфакторная аутентификация успешно деактивирована'
                ]);
            } else {
                return $this->render('admin/2fa/index', [
                    'title' => 'Двухфакторная аутентификация',
                    'error' => 'Неверный код подтверждения'
                ]);
            }
        } catch (Exception $e) {
            return $this->render('admin/2fa/index', [
                'title' => 'Двухфакторная аутентификация',
                'error' => 'Ошибка при деактивации 2FA: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Генерирует новые резервные коды
     */
    public function regenerateBackupCodes() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/2fa');
        }
        
        $userId = $_SESSION['admin_user_id'];
        
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Генерируем новые резервные коды
            $backupCodes = $this->authenticator->generateBackupCodes();
            
            // Обновляем коды в базе
            Database::execute(
                "UPDATE user_2fa SET backup_codes = ? WHERE user_id = ?",
                [json_encode($backupCodes), $userId]
            );
            
            // Очищаем использованные коды
            Database::execute(
                "DELETE FROM used_backup_codes WHERE user_id = ?",
                [$userId]
            );
            
            // Логируем активность
            $this->logUserActivity($userId, '2fa_backup_regenerated', 'Сгенерированы новые резервные коды', $_SERVER['REMOTE_ADDR']);
            
            return $this->render('admin/2fa/backup_codes', [
                'title' => 'Новые резервные коды',
                'backupCodes' => $backupCodes,
                'message' => 'Новые резервные коды сгенерированы'
            ]);
        } catch (Exception $e) {
            return $this->render('admin/2fa/index', [
                'title' => 'Двухфакторная аутентификация',
                'error' => 'Ошибка при генерации резервных кодов: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Проверяет 2FA код при входе
     */
    public function verify() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/login');
        }
        
        $code = $this->getPost('code');
        $userId = $_SESSION['temp_user_id'] ?? null;
        
        if (!$userId || empty($code)) {
            return $this->redirect('/admin/login');
        }
        
        try {
            require_once ENGINE_DIR . 'main/db.php';
            
            // Получаем настройки 2FA пользователя
            $user2fa = Database::fetchOne(
                "SELECT secret_key, backup_codes FROM user_2fa WHERE user_id = ? AND is_enabled = 1",
                [$userId]
            );
            
            if (!$user2fa) {
                return $this->redirect('/admin/login');
            }
            
            $success = false;
            $usedBackupCode = false;
            
            // Проверяем обычный код
            if ($this->authenticator->verifyCode($user2fa['secret_key'], $code)) {
                $success = true;
            } else {
                // Проверяем резервный код
                if ($this->authenticator->verifyBackupCode($userId, $code)) {
                    $success = true;
                    $usedBackupCode = true;
                }
            }
            
            if ($success) {
                // Получаем данные пользователя
                $user = Database::fetchOne("SELECT * FROM users WHERE user_id = ?", [$userId]);
                
                // Создаем сессию
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $user['user_id'];
                $_SESSION['admin_username'] = $user['user_login'];
                $_SESSION['admin_fio'] = $user['user_fio'];
                $_SESSION['admin_access_level'] = $user['user_access_level'];
                
                // Удаляем временную сессию
                unset($_SESSION['temp_user_id']);
                
                // Логируем успешную 2FA проверку
                $this->logUserActivity($userId, '2fa_verified', 
                    $usedBackupCode ? '2FA проверка через резервный код' : '2FA проверка успешна', 
                    $_SERVER['REMOTE_ADDR']
                );
                
                return $this->redirect('/admin/dashboard');
            } else {
                // Логируем неудачную попытку
                $this->logUserActivity($userId, '2fa_failed', 'Неудачная 2FA проверка', $_SERVER['REMOTE_ADDR']);
                
                return $this->render('admin/2fa/verify', [
                    'title' => 'Подтверждение 2FA',
                    'error' => 'Неверный код подтверждения',
                    'userId' => $userId
                ]);
            }
        } catch (Exception $e) {
            return $this->render('admin/2fa/verify', [
                'title' => 'Подтверждение 2FA',
                'error' => 'Ошибка при проверке кода: ' . $e->getMessage(),
                'userId' => $userId
            ]);
        }
    }
    
    /**
     * Показывает страницу проверки 2FA
     */
    public function showVerify() {
        $userId = $_SESSION['temp_user_id'] ?? null;
        
        if (!$userId) {
            return $this->redirect('/admin/login');
        }
        
        return $this->render('admin/2fa/verify', [
            'title' => 'Подтверждение 2FA',
            'userId' => $userId
        ]);
    }
} 