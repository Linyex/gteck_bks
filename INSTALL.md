# 🚀 Инструкция по установке системы ГТЭК

## 📋 Быстрая установка

### 1. Подготовка сервера

#### Требования к системе:
- **PHP**: 7.4 или выше
- **MySQL**: 5.7 или выше  
- **Веб-сервер**: Apache 2.4+ или Nginx
- **Права на запись**: для директорий загрузки файлов

#### Проверка требований:
```bash
# Проверка версии PHP
php -v

# Проверка модулей PHP
php -m | grep -E "(pdo|mysqli|session|fileinfo)"

# Проверка MySQL
mysql --version
```

### 2. Клонирование проекта

```bash
# Клонирование репозитория
git clone https://github.com/Linyex/gteck_bks.git
cd gteck_bks

# Установка прав доступа
chmod 755 assets/files/
chmod 644 application/config.php
```

### 3. Настройка базы данных

#### Создание базы данных:
```sql
CREATE DATABASE gteck_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gteck_db;
```

#### Создание таблиц:
```sql
-- Таблица пользователей (если не существует)
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(100),
    `access_level` int(11) DEFAULT 1,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
);

-- Таблица групп
CREATE TABLE `group_passwords` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `group_name` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `description` text,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `group_name` (`group_name`)
);

-- Таблица файлов контрольных работ
CREATE TABLE `dkrfiles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `filename` varchar(255) NOT NULL,
    `path` varchar(500) NOT NULL,
    `description` text,
    `upload_date` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

-- Связи файлов с группами
CREATE TABLE `dkrjointable` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `fileid` int(11) NOT NULL,
    `group_name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fileid` (`fileid`),
    KEY `group_name` (`group_name`)
);

-- Таблица файлов УМК
CREATE TABLE `umk_files` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `filename` varchar(255) NOT NULL,
    `path` varchar(500) NOT NULL,
    `description` text,
    `upload_date` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

-- Связи УМК с группами
CREATE TABLE `umk_jointable` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `fileid` int(11) NOT NULL,
    `group_name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fileid` (`fileid`),
    KEY `group_name` (`group_name`)
);

-- Таблица новостей
CREATE TABLE `news` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `content` text,
    `category` varchar(100),
    `image` varchar(500),
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `is_published` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`)
);
```

### 4. Настройка конфигурации

#### Редактирование файла конфигурации:
```php
// application/config.php
<?php
// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'gteck_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Настройки приложения
define('SITE_URL', 'http://your-domain.com');
define('UPLOAD_PATH', '/assets/files/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB

// Настройки безопасности
define('SESSION_TIMEOUT', 24 * 60 * 60); // 24 часа
define('PASSWORD_MIN_LENGTH', 6);
?>
```

### 5. Создание директорий

```bash
# Создание директорий для файлов
mkdir -p assets/files/kontrolnui
mkdir -p assets/files/ymk
mkdir -p assets/files/news

# Установка прав доступа
chmod 755 assets/files/
chmod 755 assets/files/kontrolnui/
chmod 755 assets/files/ymk/
chmod 755 assets/files/news/

# Создание файла .htaccess для защиты
echo "Deny from all" > assets/files/.htaccess
```

### 6. Настройка веб-сервера

#### Apache (.htaccess):
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

# Защита конфигурационных файлов
<Files "*.php">
    <RequireAll>
        Require all granted
    </RequireAll>
</Files>

# Защита от доступа к системным файлам
<FilesMatch "^(config|engine|application)">
    Require all denied
</FilesMatch>
```

#### Nginx:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/gteck_bks;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Защита файлов
    location ~ /(config|engine|application) {
        deny all;
    }
}
```

### 7. Создание администратора

#### Через SQL:
```sql
INSERT INTO users (username, password, email, access_level) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@gteck.by', 10);
-- Пароль: password
```

#### Через PHP скрипт:
```php
<?php
require_once 'engine/main/db.php';

$username = 'admin';
$password = 'your_secure_password';
$email = 'admin@gteck.by';
$access_level = 10;

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, email, access_level) VALUES (?, ?, ?, ?)";
Database::execute($sql, [$username, $hashed_password, $email, $access_level]);

echo "Администратор создан успешно!";
?>
```

### 8. Тестирование установки

#### Проверка подключения к БД:
```bash
php -r "
require_once 'engine/main/db.php';
try {
    \$pdo = Database::getConnection();
    echo '✅ Подключение к БД успешно\n';
} catch (Exception \$e) {
    echo '❌ Ошибка: ' . \$e->getMessage() . '\n';
}
"
```

#### Проверка прав доступа:
```bash
# Проверка записи в директории
touch assets/files/test.txt
rm assets/files/test.txt
echo "✅ Права доступа настроены правильно"
```

### 9. Первоначальная настройка

#### Создание тестовых групп:
```sql
INSERT INTO group_passwords (group_name, password, description) VALUES
('Группа 1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Тестовая группа 1'),
('Группа 2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Тестовая группа 2');
-- Пароль: password
```

#### Создание тестовых новостей:
```sql
INSERT INTO news (title, content, category) VALUES
('Добро пожаловать!', 'Система успешно установлена и готова к работе.', 'Общие'),
('Инструкция по использованию', 'Подробная инструкция по работе с системой.', 'Инструкции');
```

## 🔧 Дополнительная настройка

### Настройка SSL (HTTPS):
```apache
# Apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### Настройка кэширования:
```apache
# Кэширование статических файлов
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
</IfModule>
```

### Настройка безопасности:
```php
// В application/config.php
// Ограничение размера загружаемых файлов
define('MAX_FILE_SIZE', 25 * 1024 * 1024); // 25MB

// Разрешенные типы файлов
define('ALLOWED_FILE_TYPES', [
    'pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'ppt', 'pptx'
]);

// Настройки сессий
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
```

## 🚨 Устранение проблем

### Ошибка подключения к БД:
```bash
# Проверка настроек MySQL
mysql -u your_username -p -h localhost

# Проверка прав пользователя
SHOW GRANTS FOR 'your_username'@'localhost';
```

### Ошибка загрузки файлов:
```bash
# Проверка прав доступа
ls -la assets/files/

# Установка правильных прав
chmod 755 assets/files/
chown www-data:www-data assets/files/
```

### Ошибка маршрутизации:
```bash
# Проверка модуля rewrite
apache2ctl -M | grep rewrite

# Включение модуля
a2enmod rewrite
systemctl restart apache2
```

## 📞 Поддержка

При возникновении проблем:
1. Проверьте логи ошибок: `tail -f error_log`
2. Проверьте логи Apache/Nginx
3. Убедитесь в правильности настроек БД
4. Проверьте права доступа к файлам

---

*Версия документации: 2.0.0*
*Последнее обновление: Декабрь 2024* 