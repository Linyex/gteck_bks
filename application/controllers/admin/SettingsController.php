<?php

// Подключаем необходимые файлы
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

class SettingsController extends BaseAdminController {
    
    public function index() {
        try {
            require_once 'engine/main/db.php';
            
            // Получаем текущие настройки
            $settings = $this->getSettings();
            
            $this->render('admin/settings/index', [
                'title' => 'Настройки системы',
                'currentPage' => 'settings',
                'settings' => $settings,
                'additional_css' => [
                    '/assets/css/admin-cyberpunk.css'
                ],
                'additional_js' => [
                    '/assets/js/background-animations.js',
                    '/assets/js/admin-common.js'
                ]
            ]);
        } catch (Exception $e) {
            $this->render('admin/error/error', [
                'title' => 'Ошибка',
                'message' => 'Не удалось загрузить настройки: ' . $e->getMessage()
            ]);
        }
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/settings');
            exit;
        }
        
        try {
            require_once 'engine/main/db.php';
            
            $settings = [
                'site_name' => $_POST['site_name'] ?? '',
                'site_description' => $_POST['site_description'] ?? '',
                'admin_email' => $_POST['admin_email'] ?? '',
                'max_file_size' => (int)($_POST['max_file_size'] ?? 10),
                'allowed_file_types' => $_POST['allowed_file_types'] ?? '',
                'enable_registration' => isset($_POST['enable_registration']) ? 1 : 0,
                'enable_notifications' => isset($_POST['enable_notifications']) ? 1 : 0,
                'session_timeout' => (int)($_POST['session_timeout'] ?? 3600),
                'maintenance_mode' => isset($_POST['maintenance_mode']) ? 1 : 0
            ];
            
            // Обновляем настройки
            foreach ($settings as $key => $value) {
                Database::execute(
                    "INSERT INTO settings (setting_key, setting_value, updated_at) 
                     VALUES (?, ?, NOW()) 
                     ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()",
                    [$key, $value, $value]
                );
            }
            
            header('Location: /admin/settings?success=1');
            exit;
        } catch (Exception $e) {
            header('Location: /admin/settings?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
    
    private function getSettings() {
        try {
            $settings = Database::fetchAll("SELECT setting_key, setting_value FROM settings");
            $result = [];
            
            foreach ($settings as $setting) {
                $result[$setting['setting_key']] = $setting['setting_value'];
            }
            
            // Значения по умолчанию
            $defaults = [
                'site_name' => 'NoContrGtec',
                'site_description' => 'Административная панель',
                'admin_email' => 'admin@example.com',
                'max_file_size' => 10,
                'allowed_file_types' => 'jpg,jpeg,png,gif,pdf,doc,docx',
                'enable_registration' => 1,
                'enable_notifications' => 1,
                'session_timeout' => 3600,
                'maintenance_mode' => 0
            ];
            
            return array_merge($defaults, $result);
        } catch (Exception $e) {
            return [
                'site_name' => 'NoContrGtec',
                'site_description' => 'Административная панель',
                'admin_email' => 'admin@example.com',
                'max_file_size' => 10,
                'allowed_file_types' => 'jpg,jpeg,png,gif,pdf,doc,docx',
                'enable_registration' => 1,
                'enable_notifications' => 1,
                'session_timeout' => 3600,
                'maintenance_mode' => 0
            ];
        }
    }
} 