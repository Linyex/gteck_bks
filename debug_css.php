<?php
// Проверяем, существует ли CSS файл
$cssFile = 'assets/css/admin-cyberpunk.css';
$cssExists = file_exists($cssFile);
$cssSize = $cssExists ? filesize($cssFile) : 0;

// Проверяем доступность через веб
$cssUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/assets/css/admin-cyberpunk.css';
$cssAccessible = false;
$httpCode = 0;

if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $cssUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $cssAccessible = ($httpCode === 200);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Debug</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #000;
            color: #fff;
            padding: 20px;
        }
        .debug-item {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #333;
            background: #111;
        }
        .success { color: #00ff00; }
        .error { color: #ff0000; }
        .warning { color: #ffff00; }
    </style>
</head>
<body>
    <h1>CSS Debug Information</h1>
    
    <div class="debug-item">
        <strong>CSS File Path:</strong> <?= $cssFile ?>
    </div>
    
    <div class="debug-item <?= $cssExists ? 'success' : 'error' ?>">
        <strong>File Exists:</strong> <?= $cssExists ? 'YES' : 'NO' ?>
    </div>
    
    <?php if ($cssExists): ?>
    <div class="debug-item">
        <strong>File Size:</strong> <?= number_format($cssSize) ?> bytes
    </div>
    <?php endif; ?>
    
    <div class="debug-item">
        <strong>CSS URL:</strong> <?= $cssUrl ?>
    </div>
    
    <div class="debug-item <?= $cssAccessible ? 'success' : 'error' ?>">
        <strong>Web Accessible:</strong> <?= $cssAccessible ? 'YES' : 'NO' ?>
        <?php if (!$cssAccessible): ?>
            (HTTP Code: <?= $httpCode ?>)
        <?php endif; ?>
    </div>
    
    <div class="debug-item">
        <strong>Server Software:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?>
    </div>
    
    <div class="debug-item">
        <strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' ?>
    </div>
    
    <div class="debug-item">
        <strong>Request URI:</strong> <?= $_SERVER['REQUEST_URI'] ?? 'Unknown' ?>
    </div>
    
    <h2>Test CSS Loading</h2>
    <div class="debug-item">
        <a href="test_css.php" style="color: #00ffff;">Test CSS Page</a>
    </div>
    
    <h2>Direct CSS Test</h2>
    <div class="debug-item">
        <a href="/assets/css/admin-cyberpunk.css" style="color: #00ffff;">View CSS File</a>
    </div>
</body>
</html> 