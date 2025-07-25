<?php
// Простой тест оптимизации изображения
echo "🚀 Тест оптимизации изображения\n";

$file = 'assets/media/145.jpg';
echo "Файл: $file\n";

if (!file_exists($file)) {
    die("❌ Файл не найден!\n");
}

$originalSize = filesize($file);
echo "Оригинальный размер: " . round($originalSize/1024/1024, 2) . "MB\n";

// Проверяем GD
if (!extension_loaded('gd')) {
    die("❌ GD extension не доступен!\n");
}
echo "✅ GD extension работает\n";

// Загружаем изображение
$image = imagecreatefromjpeg($file);
if (!$image) {
    die("❌ Не удалось загрузить изображение!\n");
}
echo "✅ Изображение загружено\n";

// Получаем размеры
$width = imagesx($image);
$height = imagesy($image);
echo "Размеры: {$width}x{$height}px\n";

// Изменяем размер
$maxWidth = 1200;
$maxHeight = 800;

if ($width > $maxWidth || $height > $maxHeight) {
    $ratio = min($maxWidth/$width, $maxHeight/$height);
    $newWidth = round($width * $ratio);
    $newHeight = round($height * $ratio);
    
    $resized = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // Сохраняем оптимизированную версию
    $optimizedFile = 'assets/media/145_optimized.jpg';
    if (imagejpeg($resized, $optimizedFile, 85)) {
        $newSize = filesize($optimizedFile);
        $saved = $originalSize - $newSize;
        $percent = round(($saved/$originalSize) * 100, 1);
        
        echo "✅ Создан оптимизированный файл: $optimizedFile\n";
        echo "Новый размер: " . round($newSize/1024/1024, 2) . "MB\n";
        echo "Экономия: " . round($saved/1024/1024, 2) . "MB ($percent%)\n";
        echo "Новые размеры: {$newWidth}x{$newHeight}px\n";
    } else {
        echo "❌ Ошибка сохранения файла!\n";
    }
    
    imagedestroy($resized);
} else {
    echo "ℹ️ Изображение уже оптимального размера\n";
}

imagedestroy($image);
echo "🎉 Тест завершен!\n";
?> 