<?php

require_once ENGINE_DIR . 'controllers/admin/BaseAdminController.php';

class TestController extends BaseAdminController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Простая тестовая страница
     */
    public function index() {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Тест</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; background: #1a1a1a; color: white; }
                .test-box { background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; margin: 20px 0; }
                .success { color: #00ff00; }
                .error { color: #ff0000; }
            </style>
        </head>
        <body>
            <h1>Тестовая страница</h1>
            <div class='test-box'>
                <h2>Проверка системы:</h2>
                <p class='success'>✓ Контроллер работает</p>
                <p class='success'>✓ Роутер работает</p>
                <p class='success'>✓ Базовый контроллер загружен</p>
                <p>Время: " . date('Y-m-d H:i:s') . "</p>
                <p>Пользователь: " . ($this->adminUser ? $this->adminUser['user_fio'] : 'Не авторизован') . "</p>
            </div>
            <div class='test-box'>
                <h2>Проверка базы данных:</h2>";
        
        try {
            require_once ENGINE_DIR . 'main/db.php';
            $result = Database::fetchOne("SELECT COUNT(*) as count FROM users");
            echo "<p class='success'>✓ База данных работает. Пользователей: " . $result['count'] . "</p>";
        } catch (Exception $e) {
            echo "<p class='error'>✗ Ошибка БД: " . $e->getMessage() . "</p>";
        }
        
        echo "</div>
            <div class='test-box'>
                <h2>Проверка файлов:</h2>
                <p>Layout файл: " . (file_exists(APPLICATION_DIR . 'views/admin/layouts/base.php') ? '✓ Найден' : '✗ Не найден') . "</p>
                <p>CSS файл: " . (file_exists('assets/css/admin-cyberpunk.css') ? '✓ Найден' : '✗ Не найден') . "</p>
            </div>
            <a href='/admin' style='color: #00ffff;'>← Вернуться в админку</a>
        </body>
        </html>";
    }
} 