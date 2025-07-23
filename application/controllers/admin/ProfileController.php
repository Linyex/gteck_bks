<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

class ProfileController extends BaseAdminController {
    
    public function index() {
        return $this->render('admin/profile/index', [
            'title' => 'Профиль',
            'currentPage' => 'profile',
            'user' => $this->adminUser
        ]);
    }
    public function update() {
        try {
            require_once 'engine/main/db.php';
            
            $fio = $_POST['fio'] ?? '';
            $email = $_POST['email'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            
            if (empty($fio)) {
                return $this->render('admin/profile/index', [
                    'title' => 'Профиль',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'error' => 'Введите ФИО'
                ]);
            }
            
            // Если пользователь хочет изменить пароль
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    return $this->render('admin/profile/index', [
                        'title' => 'Профиль',
                        'currentPage' => 'profile',
                        'user' => $this->adminUser,
                        'error' => 'Введите текущий пароль'
                    ]);
                }
                
                // Проверяем текущий пароль
                if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                    return $this->render('admin/profile/index', [
                        'title' => 'Профиль',
                        'currentPage' => 'profile',
                        'user' => $this->adminUser,
                        'error' => 'Неверный текущий пароль'
                    ]);
                }
                
                // Хешируем новый пароль
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                Database::execute(
                    "UPDATE users SET user_fio = ?, user_email = ?, user_password = ? WHERE user_id = ?",
                    [$fio, $email, $hashedPassword, $this->adminUser['user_id']]
                );
            } else {
                Database::execute(
                    "UPDATE users SET user_fio = ?, user_email = ? WHERE user_id = ?",
                    [$fio, $email, $this->adminUser['user_id']]
                );
            }
            
            header('Location: /admin/profile');
            exit;
        } catch (Exception $e) {
            return $this->render('admin/profile/index', [
                'title' => 'Профиль',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => 'Ошибка при обновлении профиля: ' . $e->getMessage()
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Валидация
            if (empty($currentPassword)) {
                throw new Exception('Введите текущий пароль');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Введите новый пароль');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Пароли не совпадают');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Новый пароль должен содержать минимум 8 символов');
            }
            
            // Проверяем текущий пароль
            if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                throw new Exception('Неверный текущий пароль');
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $this->adminUser['user_id']]
            );
            
            return $this->redirect('/admin/profile?password_changed=1');
            
        } catch (Exception $e) {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'enable') {
                // Генерируем секретный ключ для 2FA
                $secret = $this->generate2FASecret();
                
                // Сохраняем секрет в базе данных
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = ?, user_2fa_enabled = 1 WHERE user_id = ?",
                    [$secret, $this->adminUser['user_id']]
                );
                
                return $this->render('admin/profile/setup-2fa', [
                    'title' => 'Настройка двухфакторной аутентификации',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'secret' => $secret,
                    'qr_code' => $this->generate2FAQRCode($secret),
                    'success' => '2FA успешно включена'
                ]);
                
            } elseif ($action === 'disable') {
                // Отключаем 2FA
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = NULL, user_2fa_enabled = 0 WHERE user_id = ?",
                    [$this->adminUser['user_id']]
                );
                
                return $this->redirect('/admin/profile?2fa_disabled=1');
            }
            
        } catch (Exception $e) {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        // Генерируем случайный секретный ключ для Google Authenticator
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generate2FAQRCode($secret) {
        // Генерируем QR-код для Google Authenticator
        $issuer = 'NoContrGtec Admin';
        $account = $this->adminUser['user_email'];
        $url = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
        
        // Здесь можно использовать библиотеку для генерации QR-кода
        // Пока возвращаем URL для отладки
        return $url;
    }
} 
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Валидация
            if (empty($currentPassword)) {
                throw new Exception('Введите текущий пароль');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Введите новый пароль');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Пароли не совпадают');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Новый пароль должен содержать минимум 8 символов');
            }
            
            // Проверяем текущий пароль
            if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                throw new Exception('Неверный текущий пароль');
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $this->adminUser['user_id']]
            );
            
            return $this->redirect('/admin/profile?password_changed=1');
            
        } catch (Exception $e) {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'enable') {
                // Генерируем секретный ключ для 2FA
                $secret = $this->generate2FASecret();
                
                // Сохраняем секрет в базе данных
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = ?, user_2fa_enabled = 1 WHERE user_id = ?",
                    [$secret, $this->adminUser['user_id']]
                );
                
                return $this->render('admin/profile/setup-2fa', [
                    'title' => 'Настройка двухфакторной аутентификации',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'secret' => $secret,
                    'qr_code' => $this->generate2FAQRCode($secret),
                    'success' => '2FA успешно включена'
                ]);
                
            } elseif ($action === 'disable') {
                // Отключаем 2FA
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = NULL, user_2fa_enabled = 0 WHERE user_id = ?",
                    [$this->adminUser['user_id']]
                );
                
                return $this->redirect('/admin/profile?2fa_disabled=1');
            }
            
        } catch (Exception $e) {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        // Генерируем случайный секретный ключ для Google Authenticator
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generate2FAQRCode($secret) {
        // Генерируем QR-код для Google Authenticator
        $issuer = 'NoContrGtec Admin';
        $account = $this->adminUser['user_email'];
        $url = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
        
        // Здесь можно использовать библиотеку для генерации QR-кода
        // Пока возвращаем URL для отладки
        return $url;
    }
} 
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Валидация
            if (empty($currentPassword)) {
                throw new Exception('Введите текущий пароль');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Введите новый пароль');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Пароли не совпадают');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Новый пароль должен содержать минимум 8 символов');
            }
            
            // Проверяем текущий пароль
            if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                throw new Exception('Неверный текущий пароль');
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $this->adminUser['user_id']]
            );
            
            return $this->redirect('/admin/profile?password_changed=1');
            
        } catch (Exception $e) {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'enable') {
                // Генерируем секретный ключ для 2FA
                $secret = $this->generate2FASecret();
                
                // Сохраняем секрет в базе данных
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = ?, user_2fa_enabled = 1 WHERE user_id = ?",
                    [$secret, $this->adminUser['user_id']]
                );
                
                return $this->render('admin/profile/setup-2fa', [
                    'title' => 'Настройка двухфакторной аутентификации',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'secret' => $secret,
                    'qr_code' => $this->generate2FAQRCode($secret),
                    'success' => '2FA успешно включена'
                ]);
                
            } elseif ($action === 'disable') {
                // Отключаем 2FA
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = NULL, user_2fa_enabled = 0 WHERE user_id = ?",
                    [$this->adminUser['user_id']]
                );
                
                return $this->redirect('/admin/profile?2fa_disabled=1');
            }
            
        } catch (Exception $e) {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        // Генерируем случайный секретный ключ для Google Authenticator
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generate2FAQRCode($secret) {
        // Генерируем QR-код для Google Authenticator
        $issuer = 'NoContrGtec Admin';
        $account = $this->adminUser['user_email'];
        $url = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
        
        // Здесь можно использовать библиотеку для генерации QR-кода
        // Пока возвращаем URL для отладки
        return $url;
    }
} 
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Валидация
            if (empty($currentPassword)) {
                throw new Exception('Введите текущий пароль');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Введите новый пароль');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Пароли не совпадают');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Новый пароль должен содержать минимум 8 символов');
            }
            
            // Проверяем текущий пароль
            if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                throw new Exception('Неверный текущий пароль');
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $this->adminUser['user_id']]
            );
            
            return $this->redirect('/admin/profile?password_changed=1');
            
        } catch (Exception $e) {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'enable') {
                // Генерируем секретный ключ для 2FA
                $secret = $this->generate2FASecret();
                
                // Сохраняем секрет в базе данных
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = ?, user_2fa_enabled = 1 WHERE user_id = ?",
                    [$secret, $this->adminUser['user_id']]
                );
                
                return $this->render('admin/profile/setup-2fa', [
                    'title' => 'Настройка двухфакторной аутентификации',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'secret' => $secret,
                    'qr_code' => $this->generate2FAQRCode($secret),
                    'success' => '2FA успешно включена'
                ]);
                
            } elseif ($action === 'disable') {
                // Отключаем 2FA
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = NULL, user_2fa_enabled = 0 WHERE user_id = ?",
                    [$this->adminUser['user_id']]
                );
                
                return $this->redirect('/admin/profile?2fa_disabled=1');
            }
            
        } catch (Exception $e) {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        // Генерируем случайный секретный ключ для Google Authenticator
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generate2FAQRCode($secret) {
        // Генерируем QR-код для Google Authenticator
        $issuer = 'NoContrGtec Admin';
        $account = $this->adminUser['user_email'];
        $url = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
        
        // Здесь можно использовать библиотеку для генерации QR-кода
        // Пока возвращаем URL для отладки
        return $url;
    }
} 
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Валидация
            if (empty($currentPassword)) {
                throw new Exception('Введите текущий пароль');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Введите новый пароль');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Пароли не совпадают');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Новый пароль должен содержать минимум 8 символов');
            }
            
            // Проверяем текущий пароль
            if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                throw new Exception('Неверный текущий пароль');
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $this->adminUser['user_id']]
            );
            
            return $this->redirect('/admin/profile?password_changed=1');
            
        } catch (Exception $e) {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'enable') {
                // Генерируем секретный ключ для 2FA
                $secret = $this->generate2FASecret();
                
                // Сохраняем секрет в базе данных
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = ?, user_2fa_enabled = 1 WHERE user_id = ?",
                    [$secret, $this->adminUser['user_id']]
                );
                
                return $this->render('admin/profile/setup-2fa', [
                    'title' => 'Настройка двухфакторной аутентификации',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'secret' => $secret,
                    'qr_code' => $this->generate2FAQRCode($secret),
                    'success' => '2FA успешно включена'
                ]);
                
            } elseif ($action === 'disable') {
                // Отключаем 2FA
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = NULL, user_2fa_enabled = 0 WHERE user_id = ?",
                    [$this->adminUser['user_id']]
                );
                
                return $this->redirect('/admin/profile?2fa_disabled=1');
            }
            
        } catch (Exception $e) {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        // Генерируем случайный секретный ключ для Google Authenticator
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generate2FAQRCode($secret) {
        // Генерируем QR-код для Google Authenticator
        $issuer = 'NoContrGtec Admin';
        $account = $this->adminUser['user_email'];
        $url = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
        
        // Здесь можно использовать библиотеку для генерации QR-кода
        // Пока возвращаем URL для отладки
        return $url;
    }
} 
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Валидация
            if (empty($currentPassword)) {
                throw new Exception('Введите текущий пароль');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Введите новый пароль');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Пароли не совпадают');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Новый пароль должен содержать минимум 8 символов');
            }
            
            // Проверяем текущий пароль
            if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                throw new Exception('Неверный текущий пароль');
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $this->adminUser['user_id']]
            );
            
            return $this->redirect('/admin/profile?password_changed=1');
            
        } catch (Exception $e) {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'enable') {
                // Генерируем секретный ключ для 2FA
                $secret = $this->generate2FASecret();
                
                // Сохраняем секрет в базе данных
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = ?, user_2fa_enabled = 1 WHERE user_id = ?",
                    [$secret, $this->adminUser['user_id']]
                );
                
                return $this->render('admin/profile/setup-2fa', [
                    'title' => 'Настройка двухфакторной аутентификации',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'secret' => $secret,
                    'qr_code' => $this->generate2FAQRCode($secret),
                    'success' => '2FA успешно включена'
                ]);
                
            } elseif ($action === 'disable') {
                // Отключаем 2FA
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = NULL, user_2fa_enabled = 0 WHERE user_id = ?",
                    [$this->adminUser['user_id']]
                );
                
                return $this->redirect('/admin/profile?2fa_disabled=1');
            }
            
        } catch (Exception $e) {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        // Генерируем случайный секретный ключ для Google Authenticator
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generate2FAQRCode($secret) {
        // Генерируем QR-код для Google Authenticator
        $issuer = 'NoContrGtec Admin';
        $account = $this->adminUser['user_email'];
        $url = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
        
        // Здесь можно использовать библиотеку для генерации QR-кода
        // Пока возвращаем URL для отладки
        return $url;
    }
} 
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Валидация
            if (empty($currentPassword)) {
                throw new Exception('Введите текущий пароль');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Введите новый пароль');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Пароли не совпадают');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Новый пароль должен содержать минимум 8 символов');
            }
            
            // Проверяем текущий пароль
            if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                throw new Exception('Неверный текущий пароль');
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $this->adminUser['user_id']]
            );
            
            return $this->redirect('/admin/profile?password_changed=1');
            
        } catch (Exception $e) {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'enable') {
                // Генерируем секретный ключ для 2FA
                $secret = $this->generate2FASecret();
                
                // Сохраняем секрет в базе данных
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = ?, user_2fa_enabled = 1 WHERE user_id = ?",
                    [$secret, $this->adminUser['user_id']]
                );
                
                return $this->render('admin/profile/setup-2fa', [
                    'title' => 'Настройка двухфакторной аутентификации',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'secret' => $secret,
                    'qr_code' => $this->generate2FAQRCode($secret),
                    'success' => '2FA успешно включена'
                ]);
                
            } elseif ($action === 'disable') {
                // Отключаем 2FA
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = NULL, user_2fa_enabled = 0 WHERE user_id = ?",
                    [$this->adminUser['user_id']]
                );
                
                return $this->redirect('/admin/profile?2fa_disabled=1');
            }
            
        } catch (Exception $e) {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        // Генерируем случайный секретный ключ для Google Authenticator
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generate2FAQRCode($secret) {
        // Генерируем QR-код для Google Authenticator
        $issuer = 'NoContrGtec Admin';
        $account = $this->adminUser['user_email'];
        $url = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
        
        // Здесь можно использовать библиотеку для генерации QR-кода
        // Пока возвращаем URL для отладки
        return $url;
    }
} 
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Валидация
            if (empty($currentPassword)) {
                throw new Exception('Введите текущий пароль');
            }
            
            if (empty($newPassword)) {
                throw new Exception('Введите новый пароль');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Пароли не совпадают');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Новый пароль должен содержать минимум 8 символов');
            }
            
            // Проверяем текущий пароль
            if (!password_verify($currentPassword, $this->adminUser['user_password'])) {
                throw new Exception('Неверный текущий пароль');
            }
            
            // Хешируем новый пароль
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Обновляем пароль в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_password = ? WHERE user_id = ?",
                [$hashedPassword, $this->adminUser['user_id']]
            );
            
            return $this->redirect('/admin/profile?password_changed=1');
            
        } catch (Exception $e) {
            return $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser
            ]);
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'enable') {
                // Генерируем секретный ключ для 2FA
                $secret = $this->generate2FASecret();
                
                // Сохраняем секрет в базе данных
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = ?, user_2fa_enabled = 1 WHERE user_id = ?",
                    [$secret, $this->adminUser['user_id']]
                );
                
                return $this->render('admin/profile/setup-2fa', [
                    'title' => 'Настройка двухфакторной аутентификации',
                    'currentPage' => 'profile',
                    'user' => $this->adminUser,
                    'secret' => $secret,
                    'qr_code' => $this->generate2FAQRCode($secret),
                    'success' => '2FA успешно включена'
                ]);
                
            } elseif ($action === 'disable') {
                // Отключаем 2FA
                require_once 'engine/main/db.php';
                Database::execute(
                    "UPDATE users SET user_2fa_secret = NULL, user_2fa_enabled = 0 WHERE user_id = ?",
                    [$this->adminUser['user_id']]
                );
                
                return $this->redirect('/admin/profile?2fa_disabled=1');
            }
            
        } catch (Exception $e) {
            return $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        // Генерируем случайный секретный ключ для Google Authenticator
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $secret;
    }
    
    private function generate2FAQRCode($secret) {
        // Генерируем QR-код для Google Authenticator
        $issuer = 'NoContrGtec Admin';
        $account = $this->adminUser['user_email'];
        $url = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}";
        
        // Здесь можно использовать библиотеку для генерации QR-кода
        // Пока возвращаем URL для отладки
        return $url;
    }
} 