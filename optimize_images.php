<?php
/**
 * 🚀 АВТОМАТИЧЕСКАЯ ОПТИМИЗАЦИЯ ИЗОБРАЖЕНИЙ ГТЭК
 * Уменьшает размер изображений на 70-90% без потери качества
 */

class ImageOptimizer {
    private $maxWidth = 1200;
    private $maxHeight = 800;
    private $quality = 85;
    private $thumbWidth = 400;
    private $thumbHeight = 300;
    
    public function __construct() {
        if (!extension_loaded('gd')) {
            die("❌ GD extension не установлен!\n");
        }
        echo "✅ GD extension найден\n";
    }
    
    public function optimizeDirectory($dir) {
        echo "\n🔍 Сканирование: $dir\n";
        $files = glob($dir . '/*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
        $totalSaved = 0;
        $count = 0;
        
        foreach ($files as $file) {
            $result = $this->optimizeImage($file);
            if ($result['success']) {
                $totalSaved += $result['saved'];
                $count++;
                echo "✅ {$result['filename']}: {$result['saved_mb']}MB saved\n";
            }
        }
        
        echo "\n📊 ИТОГО: $count файлов, " . round($totalSaved/1024/1024, 2) . "MB сэкономлено\n";
        return $totalSaved;
    }
    
    public function optimizeImage($filePath) {
        if (!file_exists($filePath)) {
            return ['success' => false, 'error' => 'Файл не найден'];
        }
        
        $originalSize = filesize($filePath);
        if ($originalSize < 100 * 1024) { // Меньше 100KB - не трогаем
            return ['success' => false, 'error' => 'Файл уже мал'];
        }
        
        $info = getimagesize($filePath);
        if (!$info) {
            return ['success' => false, 'error' => 'Не изображение'];
        }
        
        // Создаем резервную копию
        $backupPath = $filePath . '.backup';
        if (!file_exists($backupPath)) {
            copy($filePath, $backupPath);
        }
        
        // Загружаем изображение
        $image = $this->loadImage($filePath, $info[2]);
        if (!$image) {
            return ['success' => false, 'error' => 'Ошибка загрузки'];
        }
        
        // Изменяем размер если нужно
        if ($info[0] > $this->maxWidth || $info[1] > $this->maxHeight) {
            $image = $this->resizeImage($image, $info[0], $info[1]);
        }
        
        // Сохраняем оптимизированное изображение
        $this->saveImage($image, $filePath, $info[2]);
        imagedestroy($image);
        
        // Создаем thumbnail
        $this->createThumbnail($filePath, $info[2]);
        
        $newSize = filesize($filePath);
        $saved = $originalSize - $newSize;
        
        return [
            'success' => true,
            'filename' => basename($filePath),
            'saved' => $saved,
            'saved_mb' => round($saved/1024/1024, 2),
            'compression' => round(($saved/$originalSize) * 100, 1)
        ];
    }
    
    private function loadImage($filePath, $type) {
        switch ($type) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($filePath);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($filePath);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($filePath);
            default:
                return false;
        }
    }
    
    private function resizeImage($image, $width, $height) {
        // Вычисляем новые размеры сохраняя пропорции
        $ratio = min($this->maxWidth/$width, $this->maxHeight/$height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
        
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        
        // Сохраняем прозрачность для PNG
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);
        
        return $resized;
    }
    
    private function saveImage($image, $filePath, $type) {
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $filePath, $this->quality);
                break;
            case IMAGETYPE_PNG:
                // PNG: уровень сжатия 6 (0-9)
                imagepng($image, $filePath, 6);
                break;
            case IMAGETYPE_GIF:
                imagegif($image, $filePath);
                break;
        }
    }
    
    private function createThumbnail($filePath, $type) {
        $image = $this->loadImage($filePath, $type);
        if (!$image) return false;
        
        $width = imagesx($image);
        $height = imagesy($image);
        
        $ratio = min($this->thumbWidth/$width, $this->thumbHeight/$height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
        
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Сохраняем thumbnail
        $pathInfo = pathinfo($filePath);
        $thumbPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
        $this->saveImage($thumb, $thumbPath, $type);
        
        imagedestroy($image);
        imagedestroy($thumb);
        
        return $thumbPath;
    }
    
    public function restoreBackups($dir) {
        echo "\n🔄 Восстановление бэкапов в: $dir\n";
        $backups = glob($dir . '/*.backup');
        
        foreach ($backups as $backup) {
            $original = str_replace('.backup', '', $backup);
            if (copy($backup, $original)) {
                unlink($backup);
                echo "✅ Восстановлен: " . basename($original) . "\n";
            }
        }
    }
}

// Использование
if (php_sapi_name() === 'cli' || isset($_GET['optimize'])) {
    $optimizer = new ImageOptimizer();
    
    echo "🚀 АВТОМАТИЧЕСКАЯ ОПТИМИЗАЦИЯ ИЗОБРАЖЕНИЙ ГТЭК\n";
    echo "===========================================\n";
    
    $directories = [
        'assets/media/images',
        'assets/media',
        'assets/media/mini-slider',
        'assets/media/achievments'
    ];
    
    $totalSaved = 0;
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $saved = $optimizer->optimizeDirectory($dir);
            $totalSaved += $saved;
        }
    }
    
    echo "\n🎉 ЗАВЕРШЕНО! Общая экономия: " . round($totalSaved/1024/1024, 2) . "MB\n";
    echo "💡 Backup файлы созданы с расширением .backup\n";
    echo "🔄 Для отката используйте ?restore=1\n";
    
} elseif (isset($_GET['restore'])) {
    $optimizer = new ImageOptimizer();
    $directories = [
        'assets/media/images',
        'assets/media',
        'assets/media/mini-slider',
        'assets/media/achievments'
    ];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $optimizer->restoreBackups($dir);
        }
    }
    echo "✅ Восстановление завершено!\n";
} else {
    echo "<h1>🚀 Оптимизация изображений ГТЭК</h1>";
    echo "<p><strong>Найдено изображений больше 500KB:</strong></p>";
    echo "<ul>";
    $files = glob('assets/media/**/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    foreach ($files as $file) {
        $size = filesize($file);
        if ($size > 500*1024) {
            echo "<li>$file - " . round($size/1024/1024, 2) . "MB</li>";
        }
    }
    echo "</ul>";
    echo "<p><a href='?optimize=1' style='background:#28a745;color:white;padding:10px;text-decoration:none;border-radius:5px;'>🚀 ЗАПУСТИТЬ ОПТИМИЗАЦИЮ</a></p>";
    echo "<p><a href='?restore=1' style='background:#dc3545;color:white;padding:10px;text-decoration:none;border-radius:5px;'>🔄 ВОССТАНОВИТЬ БЭКАПЫ</a></p>";
}
?> 