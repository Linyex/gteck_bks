<?php
// Простой тест админки
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
require_once 'application/controllers/admin/DashboardController.php';

// Создаем контроллер дашборда
$controller = new DashboardController();
$response = $controller->index();

echo $response;
?> 