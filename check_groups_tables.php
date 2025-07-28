<?php
require_once __DIR__ . '/engine/main/db.php';

try {
    echo "🔍 Проверка таблиц с группами...\n\n";
    
    // Проверяем таблицу groups
    echo "📋 ТАБЛИЦА 'groups':\n";
    echo "===================\n";
    
    try {
        $groups_main = Database::fetchAll("SELECT DISTINCT groupname FROM groups WHERE groupname IS NOT NULL AND groupname != '' ORDER BY groupname");
        echo "✅ Найдено уникальных групп: " . count($groups_main) . "\n";
        
        if (!empty($groups_main)) {
            echo "📝 Список групп:\n";
            foreach ($groups_main as $group) {
                echo "   - " . $group['groupname'] . "\n";
            }
        } else {
            echo "❌ Таблица groups пуста или не содержит групп\n";
        }
    } catch (Exception $e) {
        echo "❌ Ошибка при чтении таблицы groups: " . $e->getMessage() . "\n";
    }
    
    echo "\n📋 ТАБЛИЦА 'dkrgroups':\n";
    echo "=====================\n";
    
    try {
        $dkr_groups = Database::fetchAll("SELECT * FROM dkrgroups ORDER BY groupname");
        echo "✅ Найдено групп: " . count($dkr_groups) . "\n";
        
        if (!empty($dkr_groups)) {
            echo "📝 Список групп:\n";
            foreach ($dkr_groups as $group) {
                echo "   - ID: {$group['id_group']}, Название: {$group['groupname']}\n";
            }
        } else {
            echo "❌ Таблица dkrgroups пуста\n";
        }
    } catch (Exception $e) {
        echo "❌ Ошибка при чтении таблицы dkrgroups: " . $e->getMessage() . "\n";
    }
    
    echo "\n📋 ТАБЛИЦА 'dkrjointable' (связи файлов с группами):\n";
    echo "=================================================\n";
    
    try {
        $joints = Database::fetchAll("SELECT * FROM dkrjointable LIMIT 10");
        echo "✅ Найдено связей (первые 10): " . count($joints) . "\n";
        
        if (!empty($joints)) {
            echo "📝 Примеры связей:\n";
            foreach ($joints as $joint) {
                echo "   - File ID: {$joint['fileid']}, Group ID: {$joint['groupid']}\n";
            }
        } else {
            echo "❌ Таблица dkrjointable пуста\n";
        }
    } catch (Exception $e) {
        echo "❌ Ошибка при чтении таблицы dkrjointable: " . $e->getMessage() . "\n";
    }
    
    echo "\n📋 ТАБЛИЦА 'dkrfiles' (файлы контрольных работ):\n";
    echo "===============================================\n";
    
    try {
        $files = Database::fetchAll("SELECT * FROM dkrfiles LIMIT 5");
        echo "✅ Найдено файлов (первые 5): " . count($files) . "\n";
        
        if (!empty($files)) {
            echo "📝 Примеры файлов:\n";
            foreach ($files as $file) {
                echo "   - ID: {$file['id']}, Файл: {$file['filename']}, Дата: {$file['upload_date']}\n";
            }
        } else {
            echo "❌ Таблица dkrfiles пуста\n";
        }
    } catch (Exception $e) {
        echo "❌ Ошибка при чтении таблицы dkrfiles: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎯 ЗАКЛЮЧЕНИЕ:\n";
    
    $groups_count = 0;
    $dkr_groups_count = 0;
    
    try {
        $groups_count = count(Database::fetchAll("SELECT DISTINCT groupname FROM groups WHERE groupname IS NOT NULL AND groupname != ''"));
        $dkr_groups_count = count(Database::fetchAll("SELECT * FROM dkrgroups"));
    } catch (Exception $e) {
        // игнорируем ошибки
    }
    
    if ($groups_count > 0 && $dkr_groups_count == 0) {
        echo "➡️  Требуется синхронизация: перенести группы из 'groups' в 'dkrgroups'\n";
    } elseif ($groups_count == 0 && $dkr_groups_count == 0) {
        echo "⚠️  Обе таблицы пусты - нужно добавить стандартные группы\n";
    } elseif ($dkr_groups_count > 0) {
        echo "✅ Таблица dkrgroups заполнена, система должна работать\n";
    }
    
} catch (Exception $e) {
    echo "❌ Общая ошибка: " . $e->getMessage() . "\n";
}
?> 