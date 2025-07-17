<?php
// Прямой тест CSS файла
$cssFile = 'assets/css/admin-cyberpunk.css';

if (file_exists($cssFile)) {
    header('Content-Type: text/css; charset=utf-8');
    header('Cache-Control: public, max-age=86400');
    readfile($cssFile);
} else {
    http_response_code(404);
    echo "CSS file not found: $cssFile";
}
?> 