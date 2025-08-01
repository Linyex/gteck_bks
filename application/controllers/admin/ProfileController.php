<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

class ProfileController extends BaseAdminController {
    
    public function index() {
        $this->render('admin/profile/index', [
            'title' => 'Профиль пользователя',
            'currentPage' => 'profile',
            'user' => $this->adminUser
        ]);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/profile');
        }
        
        try {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            // Валидация
            if (empty($name)) {
                throw new Exception('Имя обязательно для заполнения');
            }
            
            if (empty($email)) {
                throw new Exception('Email обязателен для заполнения');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Неверный формат email');
            }
            
            // Обновляем данные в базе данных
            require_once 'engine/main/db.php';
            Database::execute(
                "UPDATE users SET user_name = ?, user_email = ?, user_phone = ? WHERE user_id = ?",
                [$name, $email, $phone, $this->adminUser['user_id']]
            );
            
            // Обновляем данные в сессии
            $_SESSION['admin_user_name'] = $name;
            $_SESSION['admin_user_email'] = $email;
            
            return $this->redirect('/admin/profile?updated=1');
            
        } catch (Exception $e) {
            $this->render('admin/profile/index', [
                'title' => 'Профиль пользователя',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('admin/profile/change-password', [
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
            $this->render('admin/profile/change-password', [
                'title' => 'Смена пароля',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function setup2FA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('admin/profile/setup-2fa', [
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
                
                $this->render('admin/profile/setup-2fa', [
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
            $this->render('admin/profile/setup-2fa', [
                'title' => 'Настройка двухфакторной аутентификации',
                'currentPage' => 'profile',
                'user' => $this->adminUser,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generate2FASecret() {
        return $this->base32_encode(random_bytes(20));
    }
    
    private function generate2FAQRCode($secret) {
        $url = "otpauth://totp/" . urlencode($this->adminUser['user_email']) . "?secret=" . $secret . "&issuer=AdminPanel";
        return "https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=" . urlencode($url);
    }
    
    private function base32_encode($data) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $base = strlen($alphabet);
        $encoded = '';
        
        $bits = 0;
        $buffer = 0;
        
        for ($i = 0; $i < strlen($data); $i++) {
            $buffer = ($buffer << 8) | ord($data[$i]);
            $bits += 8;
            
            while ($bits >= 5) {
                $bits -= 5;
                $encoded .= $alphabet[($buffer >> $bits) & 31];
            }
        }
        
        if ($bits > 0) {
            $encoded .= $alphabet[($buffer << (5 - $bits)) & 31];
        }
        
        return $encoded;
    }
} 