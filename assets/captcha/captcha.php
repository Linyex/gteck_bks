<?php
session_start();

$id = 'captcha';
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
}

$captchaStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
// получаем первые 6 символов после их перемешивания с помощью функции str_shuffle
$captchaStr = substr(str_shuffle($captchaStr), 0, 6);
// инициализируем переменной сессии с помощью сгенерированной подстроки captchastring
$_SESSION[$id] = $captchaStr;

// генерируем CAPTCHA
$image = imagecreatefrompng(dirname(__FILE__) . '/background.png');
$colour = imagecolorallocate($image, 44, 44, 44);
$font = dirname(__FILE__) . '/oswald.ttf';
$rotate = rand(-10, 10);

imagettftext($image, 36, $rotate, 56, 64, $colour, $font, $captchaStr);
// будем передавать изображение в формате png
header('Content-type: image/png');
//выводим изображение
imagepng($image);