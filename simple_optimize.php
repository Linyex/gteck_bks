<?php
// Быстрая оптимизация без сложной логики
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "🔍 Диагностика системы...\n";
echo "PHP версия: " . phpversion() . "\n";
echo "GD доступен: " . (extension_loaded('gd') ? 'Да' : 'Нет') . "\n";

if (extension_loaded('gd')) {
    $info = gd_info();
    echo "JPEG поддержка: " . ($info['JPEG Support'] ? 'Да' : 'Нет') . "\n";
    echo "PNG поддержка: " . ($info['PNG Support'] ? 'Да' : 'Нет') . "\n";
}

echo "\n📊 Анализ файлов больше 1MB...\n";

$largeFiles = [];
$patterns = [
    'assets/media/*.jpg',
    'assets/media/*.jpeg', 
    'assets/media/*.png',
    'assets/media/images/*.jpg',
    'assets/media/images/*.jpeg',
    'assets/media/images/*.png',
    'assets/media/mini-slider/*.jpg',
    'assets/media/mini-slider/*.jpeg',
    'assets/media/mini-slider/*.png'
];

foreach ($patterns as $pattern) {
    $files = glob($pattern);
    foreach ($files as $file) {
        if (file_exists($file)) {
            $size = filesize($file);
            if ($size > 1024*1024) { // Больше 1MB
                $largeFiles[$file] = $size;
            }
        }
    }
}

echo "Найдено больших файлов: " . count($largeFiles) . "\n\n";

foreach ($largeFiles as $file => $size) {
    $sizeMB = round($size/1024/1024, 2);
    echo "📁 $file - {$sizeMB}MB\n";
    
    // Попробуем создать оптимизированную версию
    if (preg_match('/\.(jpg|jpeg)$/i', $file)) {
        $optimizedFile = str_replace(['.jpg', '.jpeg'], '_opt.jpg', $file);
        
        try {
            // Простое сжатие без изменения размера
            $img = imagecreatefromjpeg($file);
            if ($img) {
                if (imagejpeg($img, $optimizedFile, 75)) {
                    $newSize = filesize($optimizedFile);
                    $saved = $size - $newSize;
                    $percent = round(($saved/$size) * 100, 1);
                    echo "   ✅ Создан $optimizedFile - сэкономлено " . round($saved/1024/1024, 2) . "MB ($percent%)\n";
                } else {
                    echo "   ❌ Ошибка сохранения\n";
                }
                imagedestroy($img);
            } else {
                echo "   ❌ Ошибка загрузки изображения\n";
            }
        } catch (Exception $e) {
            echo "   ❌ Ошибка: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
}

echo "🎉 Анализ завершен!\n";
?> 