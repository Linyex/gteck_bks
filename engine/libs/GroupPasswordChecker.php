<?php

class GroupPasswordChecker {
    
    /**
     * Проверяет пароль группы
     * 
     * @param string $groupName Название группы
     * @param string $password Введенный пароль
     * @return array Результат проверки с информацией о доступе
     */
    public static function checkPassword($groupName, $password) {
        try {
            // Получаем данные группы из базы
            $group = Database::fetchOne(
                "SELECT * FROM group_passwords WHERE group_name = ? AND is_active = 1",
                [$groupName]
            );
            
            if (!$group) {
                return false; // Группа не найдена
            }
            
            // Проверяем пароль с правильным полем password (как в БД)
            if (password_verify($password, $group['password'])) {
                // Сохраняем в сессию информацию о доступе
                $_SESSION['group_access'] = [
                    'group_name' => $group['group_name'],
                    'access_time' => time(),
                    'expires' => time() + (24 * 60 * 60) // 24 часа
                ];
                
                return true; // Пароль верный
            }
            
            return false; // Пароль неверный
        } catch (Exception $e) {
            error_log("GroupPasswordChecker error: " . $e->getMessage());
            return false; // Ошибка БД
        }
    }
    
    /**
     * Проверяет, есть ли у пользователя доступ к группе
     * 
     * @param string|null $requiredGroup Требуемая группа (если null, то любая активная группа)
     * @return bool
     */
    public static function hasAccess($requiredGroup = null) {
        if (!isset($_SESSION['group_access'])) {
            return false;
        }
        
        $access = $_SESSION['group_access'];
        
        // Проверяем, не истек ли доступ
        if (time() > $access['expires']) {
            unset($_SESSION['group_access']);
            return false;
        }
        
        // Если требуется конкретная группа
        if ($requiredGroup !== null) {
            return $access['group_name'] === $requiredGroup;
        }
        
        return true;
    }
    
    /**
     * Получает информацию о текущем доступе
     * 
     * @return array|null
     */
    public static function getCurrentAccess() {
        if (!self::hasAccess()) {
            return null;
        }
        
        return $_SESSION['group_access'];
    }
    
    /**
     * Получает список всех активных групп
     * 
     * @return array
     */
    public static function getActiveGroups() {
        try {
            return Database::fetchAll(
                "SELECT group_name, description FROM group_passwords WHERE is_active = 1 ORDER BY group_name"
            );
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Проверяет пароль методом сравнения (для обратной совместимости)
     * 
     * @param string $password Введенный пароль
     * @return array Результат проверки
     */
    public static function checkPasswordLegacy($password) {
        $password = strtolower(trim($password));
        
        // Карта старых паролей к группам
        $legacyPasswords = [
            't111' => 'T111',
            't101' => 'T101', 
            'э101' => 'Э101',
            'ю101' => 'Ю101',
            't211' => 'T211',
            't201' => 'T201',
            'э201' => 'Э201', 
            'ю201' => 'Ю201',
            't301' => 'T301',
            'э301' => 'Э301',
            'б301' => 'Б301',
            '111' => 'T111',
            '101' => 'T101',
            '211' => 'T211',
            '201' => 'T201',
            '301' => 'T301'
        ];
        
        if (isset($legacyPasswords[$password])) {
            $groupName = $legacyPasswords[$password];
            
            // Проверяем, что группа активна в новой системе
            try {
                $group = Database::fetchOne(
                    "SELECT * FROM group_passwords WHERE group_name = ? AND is_active = 1",
                    [$groupName]
                );
                
                if ($group) {
                    $_SESSION['group_access'] = [
                        'group_name' => $groupName,
                        'access_time' => time(),
                        'expires' => time() + (24 * 60 * 60)
                    ];
                    
                    return [
                        'success' => true,
                        'group' => $groupName,
                        'message' => 'Доступ предоставлен'
                    ];
                }
            } catch (Exception $e) {
                // Игнорируем ошибки БД
            }
        }
        
        return [
            'success' => false,
            'group' => null,
            'message' => 'Неверный пароль'
        ];
    }
    
    /**
     * Очищает доступ (выход)
     */
    public static function clearAccess() {
        unset($_SESSION['group_access']);
    }
    
    /**
     * Проверяет, принадлежит ли файл к группе пользователя
     * 
     * @param string $filePath Путь к файлу
     * @param string $userGroup Группа пользователя
     * @return bool
     */
    public static function canAccessFile($filePath, $userGroup) {
        // Логика для определения принадлежности файла к группе
        // Можно использовать имя файла, путь или метаданные
        
        $filename = basename($filePath);
        $groupInFilename = self::extractGroupFromFilename($filename);
        
        if ($groupInFilename && $groupInFilename === $userGroup) {
            return true;
        }
        
        // Если не удалось определить группу из имени файла,
        // разрешаем доступ (для общих файлов)
        return true;
    }
    
    /**
     * Извлекает название группы из имени файла
     * 
     * @param string $filename Имя файла
     * @return string|null
     */
    private static function extractGroupFromFilename($filename) {
        // Ищем паттерны групп в имени файла
        $patterns = [
            '/[ТT]-?111/i' => 'T111',
            '/[ТT]-?101/i' => 'T101',
            '/[ЭЕE]-?101/i' => 'Э101',
            '/[ЮЯY]-?101/i' => 'Ю101',
            '/[ТT]-?211/i' => 'T211',
            '/[ТT]-?201/i' => 'T201',
            '/[ЭЕE]-?201/i' => 'Э201',
            '/[ЮЯY]-?201/i' => 'Ю201',
            '/[ТT]-?301/i' => 'T301',
            '/[ЭЕE]-?301/i' => 'Э301',
            '/[БB]-?301/i' => 'Б301'
        ];
        
        foreach ($patterns as $pattern => $group) {
            if (preg_match($pattern, $filename)) {
                return $group;
            }
        }
        
        return null;
    }
}
?> 