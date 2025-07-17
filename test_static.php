<?php
// Тест загрузки статических файлов
$cssPath = 'assets/css/admin-cyberpunk.css';
$cssExists = file_exists($cssPath);
$cssSize = $cssExists ? filesize($cssPath) : 0;

echo "<h1>Тест статических файлов</h1>";
echo "<p>CSS файл: $cssPath</p>";
echo "<p>Существует: " . ($cssExists ? 'ДА' : 'НЕТ') . "</p>";
if ($cssExists) {
    echo "<p>Размер: " . number_format($cssSize) . " байт</p>";
}

// Проверяем доступность через веб
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$cssUrl = "$protocol://$host/assets/css/admin-cyberpunk.css";

echo "<p>URL: <a href='$cssUrl' target='_blank'>$cssUrl</a></p>";

// Тест прямого доступа
if ($cssExists) {
    echo "<h2>Содержимое CSS файла (первые 500 символов):</h2>";
    echo "<pre style='background: #000; color: #0f0; padding: 10px; max-height: 200px; overflow: auto;'>";
    echo htmlspecialchars(substr(file_get_contents($cssPath), 0, 500));
    echo "</pre>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Тест CSS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-box { 
            border: 2px solid #ffd700; 
            background: #000; 
            color: #00d4ff; 
            padding: 20px; 
            margin: 10px 0;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }
    </style>
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
</head>
<body>
    <div class="test-box">
        <h1 style="color: var(--text-yellow);">Тест CSS переменных</h1>
        <p style="color: var(--text-blue);">Если вы видите желтый заголовок и синий текст, CSS загружается правильно!</p>
    </div>
    
    <div class="test-box">
        <h2>Проверка шрифтов</h2>
        <p style="font-family: 'Orbitron', monospace; color: var(--text-yellow);">Orbitron шрифт</p>
        <p style="font-family: 'Rajdhani', sans-serif; color: var(--text-blue);">Rajdhani шрифт</p>
    </div>
    
    <div class="test-box">
        <h2>Проверка кнопок</h2>
        <button style="
            background: linear-gradient(45deg, var(--primary-yellow), var(--primary-blue));
            border: 2px solid var(--primary-yellow);
            border-radius: 10px;
            color: var(--cyber-black);
            padding: 15px 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
        ">КИБЕРПАНК КНОПКА</button>
    </div>
</body>
</html> 