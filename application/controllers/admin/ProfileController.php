<?php

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
} 