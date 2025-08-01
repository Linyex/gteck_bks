# 🔌 API документация системы ГТЭК

## 📋 Обзор API

Система предоставляет REST API для управления группами, файлами и пользователями. Все запросы возвращают JSON ответы.

## 🔐 Аутентификация

### Сессионная аутентификация
```php
// Логин пользователя
POST /admin/auth/login
{
    "username": "admin",
    "password": "password"
}

// Ответ
{
    "success": true,
    "message": "Успешная авторизация",
    "user": {
        "id": 1,
        "username": "admin",
        "access_level": 10
    }
}
```

### Проверка пароля группы
```php
// Проверка пароля группы
POST /stud/check-group-password
{
    "group_name": "Группа 1",
    "password": "password"
}

// Ответ
{
    "success": true,
    "message": "Пароль верный",
    "session_data": {
        "group_name": "Группа 1",
        "access_time": 1640995200,
        "expires": 1641081600
    }
}
```

## 👥 Управление группами

### Получение списка групп
```php
GET /admin/group-passwords/

// Ответ
{
    "success": true,
    "groups": [
        {
            "id": 1,
            "group_name": "Группа 1",
            "description": "Тестовая группа",
            "is_active": 1,
            "created_at": "2024-01-01 12:00:00"
        }
    ]
}
```

### Создание группы
```php
POST /admin/group-passwords/create
{
    "group_name": "Новая группа",
    "password": "secure_password",
    "description": "Описание группы"
}

// Ответ
{
    "success": true,
    "message": "Группа создана успешно",
    "group_id": 2
}
```

### Редактирование группы
```php
POST /admin/group-passwords/edit/1
{
    "group_name": "Обновленная группа",
    "description": "Новое описание"
}

// Ответ
{
    "success": true,
    "message": "Группа обновлена"
}
```

### Удаление группы
```php
POST /admin/group-passwords/delete
{
    "id": 1
}

// Ответ
{
    "success": true,
    "message": "Группа и пароль удалены"
}
```

### Переключение статуса группы
```php
POST /admin/group-passwords/toggle/1

// Ответ
{
    "success": true,
    "message": "Статус группы изменен",
    "is_active": 0
}
```

## 📁 Управление файлами контрольных работ

### Получение списка файлов
```php
GET /admin/control-files/

// Ответ
{
    "success": true,
    "files": [
        {
            "id": 1,
            "filename": "Контрольная работа 1.pdf",
            "path": "/assets/files/kontrolnui/file1.pdf",
            "description": "Описание файла",
            "group_names": "Группа 1,Группа 2",
            "upload_date": "2024-01-01 12:00:00",
            "file_size": "2.5 MB"
        }
    ]
}
```

### Загрузка файла
```php
POST /admin/control-files/upload
Content-Type: multipart/form-data

{
    "filename": "Новый файл",
    "file": [binary_data],
    "description": "Описание файла",
    "group_names": ["Группа 1", "Группа 2"]
}

// Ответ
{
    "success": true,
    "message": "Файл загружен успешно",
    "file_id": 3
}
```

### Редактирование файла
```php
POST /admin/control-files/edit/1
{
    "filename": "Обновленное название",
    "description": "Новое описание",
    "group_names": ["Группа 1"]
}

// Ответ
{
    "success": true,
    "message": "Файл обновлен"
}
```

### Удаление файла
```php
POST /admin/control-files/delete
{
    "id": 1
}

// Ответ
{
    "success": true,
    "message": "Файл удален"
}
```

## 📚 Управление УМК

### Получение списка файлов УМК
```php
GET /admin/umk-files/

// Ответ
{
    "success": true,
    "files": [
        {
            "id": 1,
            "filename": "УМК по математике.pdf",
            "path": "/assets/files/ymk/umk1.pdf",
            "description": "Учебно-методический комплекс",
            "group_names": "Группа 1",
            "upload_date": "2024-01-01 12:00:00",
            "file_size": "5.2 MB"
        }
    ]
}
```

### Загрузка файла УМК
```php
POST /admin/umk-files/upload
Content-Type: multipart/form-data

{
    "filename": "УМК по физике",
    "file": [binary_data],
    "description": "Учебно-методический комплекс по физике",
    "group_names": ["Группа 1", "Группа 2"]
}

// Ответ
{
    "success": true,
    "message": "УМК загружен успешно",
    "file_id": 2
}
```

### Редактирование УМК
```php
POST /admin/umk-files/edit/1
{
    "filename": "Обновленный УМК",
    "description": "Новое описание УМК",
    "group_names": ["Группа 1"]
}

// Ответ
{
    "success": true,
    "message": "УМК обновлен"
}
```

### Удаление УМК
```php
POST /admin/umk-files/delete
{
    "id": 1
}

// Ответ
{
    "success": true,
    "message": "УМК удален"
}
```

## 👨‍🎓 Студенческий доступ

### Получение доступных групп
```php
GET /stud/get-groups

// Ответ
{
    "success": true,
    "groups": [
        {
            "group_name": "Группа 1",
            "description": "Описание группы"
        }
    ]
}
```

### Получение файлов контрольных работ
```php
GET /stud/get-kontrolnui-files?group_name=Группа 1

// Ответ
{
    "success": true,
    "files": [
        {
            "id": 1,
            "filename": "Контрольная работа 1.pdf",
            "path": "/assets/files/kontrolnui/file1.pdf",
            "description": "Описание файла",
            "upload_date": "2024-01-01 12:00:00",
            "file_size": "2.5 MB"
        }
    ]
}
```

### Получение файлов УМК
```php
GET /stud/get-umk-files?group_name=Группа 1

// Ответ
{
    "success": true,
    "files": [
        {
            "id": 1,
            "filename": "УМК по математике.pdf",
            "path": "/assets/files/ymk/umk1.pdf",
            "description": "Учебно-методический комплекс",
            "upload_date": "2024-01-01 12:00:00",
            "file_size": "5.2 MB"
        }
    ]
}
```

## 📊 Аналитика

### Получение статистики
```php
GET /admin/analytics/stats

// Ответ
{
    "success": true,
    "stats": {
        "total_users": 150,
        "active_groups": 12,
        "total_files": 45,
        "total_umk_files": 23,
        "active_sessions": 8,
        "downloads_today": 156
    }
}
```

### Получение активности пользователей
```php
GET /admin/analytics/user-activity?days=7

// Ответ
{
    "success": true,
    "activity": [
        {
            "date": "2024-01-01",
            "visits": 45,
            "downloads": 23,
            "unique_users": 12
        }
    ]
}
```

### Получение геолокационных данных
```php
GET /admin/analytics/geolocation

// Ответ
{
    "success": true,
    "locations": [
        {
            "country": "Беларусь",
            "city": "Гродно",
            "visits": 156,
            "percentage": 85.2
        }
    ]
}
```

## 🔍 Поиск

### Поиск по новостям
```php
GET /news/search?q=математика&category=Общие

// Ответ
{
    "success": true,
    "results": [
        {
            "id": 1,
            "title": "Новости по математике",
            "content": "Содержание новости...",
            "category": "Общие",
            "created_at": "2024-01-01 12:00:00"
        }
    ]
}
```

## 🛡️ Обработка ошибок

### Стандартный формат ошибки
```json
{
    "success": false,
    "error": "Описание ошибки",
    "error_code": "ERROR_CODE",
    "details": {
        "field": "Дополнительная информация"
    }
}
```

### Коды ошибок
- `AUTH_REQUIRED` - Требуется аутентификация
- `ACCESS_DENIED` - Отказано в доступе
- `INVALID_PASSWORD` - Неверный пароль
- `GROUP_NOT_FOUND` - Группа не найдена
- `FILE_NOT_FOUND` - Файл не найден
- `UPLOAD_ERROR` - Ошибка загрузки файла
- `VALIDATION_ERROR` - Ошибка валидации
- `DATABASE_ERROR` - Ошибка базы данных

## 📝 Примеры использования

### JavaScript (jQuery)
```javascript
// Проверка пароля группы
$.post('/stud/check-group-password', {
    group_name: 'Группа 1',
    password: 'password'
})
.done(function(response) {
    if (response.success) {
        console.log('Доступ разрешен');
    } else {
        console.log('Ошибка: ' + response.error);
    }
});

// Загрузка файла
var formData = new FormData();
formData.append('filename', 'Новый файл');
formData.append('file', fileInput.files[0]);
formData.append('description', 'Описание файла');
formData.append('group_names[]', 'Группа 1');

$.ajax({
    url: '/admin/control-files/upload',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        if (response.success) {
            console.log('Файл загружен');
        }
    }
});
```

### PHP
```php
// Проверка пароля группы
$response = GroupPasswordChecker::checkPassword($groupName, $password);
if ($response) {
    echo "Доступ разрешен";
} else {
    echo "Неверный пароль";
}

// Получение файлов группы
$files = Database::fetchAll("
    SELECT f.*, GROUP_CONCAT(j.group_name) as group_names
    FROM dkrfiles f
    LEFT JOIN dkrjointable j ON f.id = j.fileid
    WHERE j.group_name = ?
    GROUP BY f.id
    ORDER BY f.upload_date DESC
", [$groupName]);
```

## 🔧 Настройка CORS

### Для внешних запросов
```php
// В начале скрипта
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

## 📊 Мониторинг API

### Логирование запросов
```php
// Логирование всех API запросов
$log_data = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'url' => $_SERVER['REQUEST_URI'],
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT']
];

file_put_contents('api_log.txt', json_encode($log_data) . "\n", FILE_APPEND);
```

---

*Версия API: 2.0.0*
*Последнее обновление: Декабрь 2024* 