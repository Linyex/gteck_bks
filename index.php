<?php

mb_internal_encoding("UTF-8");

define('ENGINE_DIR', dirname(__FILE__) . '/engine/');
define('APPLICATION_DIR', dirname(__FILE__) . '/application/');

// Загружаем основные классы
require_once(ENGINE_DIR . 'Database.php');
require_once(ENGINE_DIR . 'BaseController.php');
require_once(ENGINE_DIR . 'Router.php');

// Создаем роутер
$router = new Router();

// Получаем URL из параметра action
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Диспетчеризируем запрос
$response = $router->dispatch($action);

// Выводим результат
echo $response;
?>
