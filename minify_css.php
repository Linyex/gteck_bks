<?php
/**
 * 🗜️ МИНИФИКАТОР CSS ДЛЯ ГТЭК
 * Уменьшает размер CSS файлов на 30-50%
 */

class CSSMinifier {
    public function minifyFile($filePath) {
        if (!file_exists($filePath)) {
            return false;
        }
        
        $css = file_get_contents($filePath);
        $originalSize = strlen($css);
        
        $minified = $this->minify($css);
        $newSize = strlen($minified);
        $saved = $originalSize - $newSize;
        $percent = round(($saved / $originalSize) * 100, 1);
        
        // Создаем минифицированную версию
        $minFilePath = str_replace('.css', '.min.css', $filePath);
        file_put_contents($minFilePath, $minified);
        
        return [
            'original' => $originalSize,
            'minified' => $newSize,
            'saved' => $saved,
            'percent' => $percent,
            'file' => $minFilePath
        ];
    }
    
    public function minify($css) {
        // Удаляем комментарии (кроме важных /*! */)
        $css = preg_replace('!/\*(?![!])[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Удаляем лишние пробелы и переносы строк
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Удаляем пробелы вокруг специальных символов
        $css = preg_replace('/\s*([{}:;,>+~])\s*/', '$1', $css);
        
        // Удаляем пробелы в начале и конце
        $css = trim($css);
        
        // Удаляем лишние точки с запятой
        $css = preg_replace('/;+/', ';', $css);
        
        // Удаляем последнюю точку с запятой перед }
        $css = preg_replace('/;\s*}/', '}', $css);
        
        // Сокращаем цвета hex
        $css = preg_replace('/#([a-f0-9])\1([a-f0-9])\2([a-f0-9])\3/i', '#$1$2$3', $css);
        
        // Удаляем лишние нули
        $css = preg_replace('/(:|\s)0+\.(\d+)/S', '$1.$2', $css);
        $css = preg_replace('/(:|\s)\.0+(\d+)/S', '$1.$2', $css);
        $css = preg_replace('/(:|\s)0+(px|em|%|ex|cm|mm|in|pt|pc)/S', '$1', $css);
        
        return $css;
    }
    
    public function optimizeDirectory($directory) {
        echo "🗜️ Минификация CSS в: $directory\n";
        
        $cssFiles = glob($directory . '/*.css');
        $totalSaved = 0;
        $count = 0;
        
        foreach ($cssFiles as $file) {
            // Пропускаем уже минифицированные файлы
            if (strpos($file, '.min.css') !== false) {
                continue;
            }
            
            $result = $this->minifyFile($file);
            if ($result) {
                $totalSaved += $result['saved'];
                $count++;
                
                echo "✅ " . basename($file) . " → " . basename($result['file']) . "\n";
                echo "   Размер: " . round($result['original']/1024, 1) . "KB → " . round($result['minified']/1024, 1) . "KB\n";
                echo "   Экономия: " . round($result['saved']/1024, 1) . "KB ({$result['percent']}%)\n\n";
            }
        }
        
        echo "📊 ИТОГО: $count файлов, " . round($totalSaved/1024, 1) . "KB сэкономлено\n";
        return $totalSaved;
    }
}

// Использование
if (php_sapi_name() === 'cli' || isset($_GET['minify'])) {
    $minifier = new CSSMinifier();
    
    echo "🗜️ МИНИФИКАЦИЯ CSS ФАЙЛОВ ГТЭК\n";
    echo "=============================\n";
    
    $directories = [
        'assets/css'
    ];
    
    $totalSaved = 0;
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $saved = $minifier->optimizeDirectory($dir);
            $totalSaved += $saved;
        }
    }
    
    echo "\n🎉 ЗАВЕРШЕНО! Общая экономия: " . round($totalSaved/1024, 1) . "KB\n";
    echo "💡 Создайте символические ссылки на .min.css файлы в продакшене\n";
    
} else {
    echo "<h1>🗜️ Минификация CSS ГТЭК</h1>";
    echo "<p><strong>Найденные CSS файлы:</strong></p>";
    echo "<ul>";
    $files = glob('assets/css/*.css');
    foreach ($files as $file) {
        if (strpos($file, '.min.css') === false) {
            $size = filesize($file);
            echo "<li>" . basename($file) . " - " . round($size/1024, 1) . "KB</li>";
        }
    }
    echo "</ul>";
    echo "<p><a href='?minify=1' style='background:#17a2b8;color:white;padding:10px;text-decoration:none;border-radius:5px;'>🗜️ МИНИФИЦИРОВАТЬ CSS</a></p>";
}
?> 