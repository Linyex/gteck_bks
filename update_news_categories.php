<?php
require_once __DIR__ . '/engine/main/db.php';

try {
    $db = Database::getConnection();
    $type = Database::getConnectionType();
    
    echo "🔄 Обновление категорий новостей...\n\n";
    
    if ($type === 'pdo') {
        // Создаем таблицу категорий новостей
        $db->exec("
            CREATE TABLE IF NOT EXISTS `news_categories` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL UNIQUE,
                `slug` VARCHAR(100) NOT NULL UNIQUE,
                `type` ENUM('regular', 'important') DEFAULT 'regular',
                `sort_order` INT DEFAULT 0,
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        
        // Очищаем старые категории
        $db->exec("DELETE FROM news_categories");
        
        // Добавляем новые категории
        $categories = [
            // Обычные новости
            ['name' => 'Общие новости', 'slug' => 'general', 'type' => 'regular', 'sort_order' => 1],
            ['name' => 'Спортивная жизнь', 'slug' => 'sports', 'type' => 'regular', 'sort_order' => 2],
            ['name' => 'Педагог-психолог', 'slug' => 'psychologist', 'type' => 'regular', 'sort_order' => 3],
            ['name' => 'Социальный педагог', 'slug' => 'social-pedagogue', 'type' => 'regular', 'sort_order' => 4],
            ['name' => 'Абитуриенту', 'slug' => 'applicant', 'type' => 'regular', 'sort_order' => 5],
            ['name' => 'Дневное отделение', 'slug' => 'full-time', 'type' => 'regular', 'sort_order' => 6],
            ['name' => 'Заочное отделение', 'slug' => 'part-time', 'type' => 'regular', 'sort_order' => 7],
            ['name' => 'Отделение проф. подготовки', 'slug' => 'vocational', 'type' => 'regular', 'sort_order' => 8],
            ['name' => 'Общежитие', 'slug' => 'dormitory', 'type' => 'regular', 'sort_order' => 9],
            ['name' => 'Сотрудничество', 'slug' => 'cooperation', 'type' => 'regular', 'sort_order' => 10],
            ['name' => 'Информационно-воспитательная работа', 'slug' => 'educational', 'type' => 'regular', 'sort_order' => 11],
            
            // Важные новости
            ['name' => 'Важные новости', 'slug' => 'important', 'type' => 'important', 'sort_order' => 100]
        ];
        
        $stmt = $db->prepare("
            INSERT INTO news_categories (name, slug, type, sort_order) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($categories as $category) {
            $stmt->execute([
                $category['name'],
                $category['slug'],
                $category['type'],
                $category['sort_order']
            ]);
            echo "✅ Добавлена категория: {$category['name']} ({$category['type']})\n";
        }
        
        // Обновляем существующие новости
        echo "\n🔄 Обновление существующих новостей...\n";
        
        // Получаем все новости
        $news = Database::fetchAll("SELECT news_id, category_id FROM news");
        
        foreach ($news as $item) {
            // По умолчанию относим к "Общие новости"
            $newCategoryId = 1; // ID категории "Общие новости"
            
            Database::execute(
                "UPDATE news SET category_id = ? WHERE news_id = ?",
                [$newCategoryId, $item['news_id']]
            );
        }
        
        echo "✅ Обновлено " . count($news) . " новостей\n";
        
    } else {
        // MySQLi версия
        $db->query("
            CREATE TABLE IF NOT EXISTS `news_categories` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL UNIQUE,
                `slug` VARCHAR(100) NOT NULL UNIQUE,
                `type` ENUM('regular', 'important') DEFAULT 'regular',
                `sort_order` INT DEFAULT 0,
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        
        // Очищаем старые категории
        $db->query("DELETE FROM news_categories");
        
        // Добавляем новые категории
        $categories = [
            // Обычные новости
            ['name' => 'Общие новости', 'slug' => 'general', 'type' => 'regular', 'sort_order' => 1],
            ['name' => 'Спортивная жизнь', 'slug' => 'sports', 'type' => 'regular', 'sort_order' => 2],
            ['name' => 'Педагог-психолог', 'slug' => 'psychologist', 'type' => 'regular', 'sort_order' => 3],
            ['name' => 'Социальный педагог', 'slug' => 'social-pedagogue', 'type' => 'regular', 'sort_order' => 4],
            ['name' => 'Абитуриенту', 'slug' => 'applicant', 'type' => 'regular', 'sort_order' => 5],
            ['name' => 'Дневное отделение', 'slug' => 'full-time', 'type' => 'regular', 'sort_order' => 6],
            ['name' => 'Заочное отделение', 'slug' => 'part-time', 'type' => 'regular', 'sort_order' => 7],
            ['name' => 'Отделение проф. подготовки', 'slug' => 'vocational', 'type' => 'regular', 'sort_order' => 8],
            ['name' => 'Общежитие', 'slug' => 'dormitory', 'type' => 'regular', 'sort_order' => 9],
            ['name' => 'Сотрудничество', 'slug' => 'cooperation', 'type' => 'regular', 'sort_order' => 10],
            ['name' => 'Информационно-воспитательная работа', 'slug' => 'educational', 'type' => 'regular', 'sort_order' => 11],
            
            // Важные новости
            ['name' => 'Важные новости', 'slug' => 'important', 'type' => 'important', 'sort_order' => 100]
        ];
        
        foreach ($categories as $category) {
            $stmt = $db->prepare("
                INSERT INTO news_categories (name, slug, type, sort_order) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param('sssi', 
                $category['name'],
                $category['slug'],
                $category['type'],
                $category['sort_order']
            );
            $stmt->execute();
            echo "✅ Добавлена категория: {$category['name']} ({$category['type']})\n";
        }
        
        // Обновляем существующие новости
        echo "\n🔄 Обновление существующих новостей...\n";
        
        $news = Database::fetchAll("SELECT news_id, category_id FROM news");
        
        foreach ($news as $item) {
            $newCategoryId = 1; // ID категории "Общие новости"
            
            Database::execute(
                "UPDATE news SET category_id = ? WHERE news_id = ?",
                [$newCategoryId, $item['news_id']]
            );
        }
        
        echo "✅ Обновлено " . count($news) . " новостей\n";
    }
    
    echo "\n🎉 Категории новостей успешно обновлены!\n";
    echo "📊 Статистика:\n";
    echo "- Обычные категории: 11\n";
    echo "- Важные категории: 1\n";
    echo "- Всего категорий: 12\n";
    
} catch (Exception $e) {
    die("❌ Ошибка при обновлении категорий: " . $e->getMessage());
}
?> 