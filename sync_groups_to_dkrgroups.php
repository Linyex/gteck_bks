<?php
require_once __DIR__ . '/engine/main/db.php';

try {
    echo "🔄 Синхронизация групп...\n";
    
    // Получаем все группы из таблицы groups
    $groups_from_main_table = Database::fetchAll("SELECT DISTINCT groupname FROM groups WHERE groupname IS NOT NULL AND groupname != '' ORDER BY groupname");
    
    echo "📊 Найдено " . count($groups_from_main_table) . " групп в основной таблице\n";
    
    // Получаем существующие группы в dkrgroups
    $existing_dkr_groups = Database::fetchAll("SELECT groupname FROM dkrgroups");
    $existing_names = array_column($existing_dkr_groups, 'groupname');
    
    echo "📋 Существующих групп в dkrgroups: " . count($existing_dkr_groups) . "\n";
    
    $added_count = 0;
    
    // Добавляем недостающие группы
    foreach ($groups_from_main_table as $group) {
        $groupname = trim($group['groupname']);
        
        if (!empty($groupname) && !in_array($groupname, $existing_names)) {
            Database::execute("INSERT INTO dkrgroups (groupname) VALUES (?)", [$groupname]);
            echo "✅ Добавлена группа: $groupname\n";
            $added_count++;
        }
    }
    
    // Также добавляем стандартные группы если их нет
    $standard_groups = [
        'T111', 'T101', 'Э101', 'Ю101',
        'T211', 'T201', 'Э201', 'Ю201', 
        'T301', 'Э301', 'Б301'
    ];
    
    foreach ($standard_groups as $groupname) {
        if (!in_array($groupname, $existing_names)) {
            // Проверяем, не добавили ли мы её уже
            $check = Database::fetchOne("SELECT id_group FROM dkrgroups WHERE groupname = ?", [$groupname]);
            if (!$check) {
                Database::execute("INSERT INTO dkrgroups (groupname) VALUES (?)", [$groupname]);
                echo "✅ Добавлена стандартная группа: $groupname\n";
                $added_count++;
            }
        }
    }
    
    echo "\n🎉 Синхронизация завершена!\n";
    echo "➕ Добавлено новых групп: $added_count\n";
    
    // Показываем итоговый список
    $final_groups = Database::fetchAll("SELECT * FROM dkrgroups ORDER BY groupname");
    echo "\n📋 Итоговый список групп в dkrgroups:\n";
    foreach ($final_groups as $group) {
        echo "   - {$group['groupname']} (ID: {$group['id_group']})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}
?> 