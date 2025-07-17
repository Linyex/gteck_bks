<?php
// Тест layout админки
session_start();

// Симулируем вход в админку
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_user_id'] = 1;
$_SESSION['admin_username'] = 'admin';
$_SESSION['admin_fio'] = 'Администратор';
$_SESSION['admin_access_level'] = 10;

// Подключаем необходимые файлы
require_once 'engine/main/db.php';
require_once 'engine/BaseController.php';
require_once 'application/controllers/admin/BaseAdminController.php';

// Создаем тестовый контроллер
class TestController extends BaseAdminController {
    public function test() {
        return $this->render('admin/dashboard/index', [
            'title' => 'Тест Layout',
            'currentPage' => 'dashboard',
            'totalUsers' => 10,
            'totalNews' => 5,
            'totalFiles' => 3,
            'totalPhotos' => 7,
            'recentActivity' => []
        ]);
    }
}

// Запускаем тест
$controller = new TestController();
$response = $controller->test();

echo $response;
?> 