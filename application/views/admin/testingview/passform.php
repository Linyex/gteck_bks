<?php
require_once __DIR__ . '/../../engine/helpers.php';

$group = '';
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<?php include_once __DIR__ . '/../../views/model view/header.php'?>

<body>
    <form class="card" action="src/actions/register.php" method="post" enctype="multipart/form-data">
        <h2>Регистрация паролей групп</h2>
    
        
        <div class="grid">
            <label for="password">
                Пароль группы
                <input type="password" id="password" name="password">
            </label>
        </div>
        
        <div class="">
            <case for="group">
                Выберете группу
                <option value="T111">Т-111</option>
                <option value="T101">Т-101</option>
                <option value="T211">Т-211</option>
                <option value="T201">Т-201</option>
                <option value="T301">Т-301</option>
                <option value="Э101">Э-101</option>
                <option value="Э201">Э-201</option>
                <option value="Э301">Э-301</option>
                <option value="Б201">Б-201</option>
                <option value="Б301">Б-301</option>
            </case>
        </div>