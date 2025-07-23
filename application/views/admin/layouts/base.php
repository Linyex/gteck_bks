<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Админ панель'; ?> - CyberAdmin</title>
    
    <!-- Основные стили -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Стили для подменю -->
    <style>
        .nav-item.has-submenu {
            position: relative;
        }
        
        .nav-item.has-submenu .nav-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .submenu-toggle {
            transition: transform 0.3s ease;
            font-size: 0.8em;
        }
        
        .nav-item.has-submenu.active .submenu-toggle {
            transform: rotate(180deg);
        }
        
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.1);
            margin-left: 20px;
            border-left: 2px solid rgba(0, 255, 255, 0.3);
        }
        
        .nav-item.has-submenu.active .submenu {
            max-height: 300px;
        }
        
        .submenu-item {
            display: flex;
            align-items: center;
            padding: 8px 15px;
            color: #b0b0b0;
            text-decoration: none;
            font-size: 0.9em;
            transition: all 0.3s ease;
            border-left: 2px solid transparent;
        }
        
        .submenu-item:hover {
            color: #00ffff;
            background: rgba(0, 255, 255, 0.1);
            border-left-color: #00ffff;
        }
        
        .submenu-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }
        
        .submenu-item.active {
            color: #00ffff;
            background: rgba(0, 255, 255, 0.15);
            border-left-color: #00ffff;
        }
    </style>
    
    <!-- Дополнительные стили -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Дополнительные скрипты в head -->
    <?php if (isset($additional_js_head)): ?>
        <?php foreach ($additional_js_head as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-background">
        <!-- Background will be injected by JavaScript -->
    </div>
    <div class="background-overlay"></div>

    <!-- Admin Container -->
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-container">
                <div class="header-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="header-logo">
                        <div class="logo-text">CyberAdmin</div>
                        <div class="logo-subtitle"><?php echo isset($subtitle) ? $subtitle : 'Admin Panel'; ?></div>
                    </div>
                </div>
                
                <div class="header-center">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" class="header-search" placeholder="Поиск по админке...">
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="header-item">
                        <button class="header-btn" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>
                    <div class="header-item">
                        <button class="header-btn" onclick="openSettings()">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                    <div class="header-item">
                        <button class="header-btn" onclick="toggleUserMenu()">
                            <div class="user-avatar">A</div>
                            <span class="user-name">Admin</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="sidebar-header">
                <h1>CyberAdmin</h1>
                <p>Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <!-- Дашборд -->
                <div class="nav-item">
                    <a href="/admin/dashboard" class="nav-link <?php echo isset($currentPage) && $currentPage === 'dashboard' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <span>Дашборд</span>
                    </a>
                </div>

                <!-- Пользователи -->
                <div class="nav-item has-submenu">
                    <a href="/admin/users" class="nav-link <?php echo isset($currentPage) && $currentPage === 'users' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <span>Пользователи</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/users" class="submenu-item">
                            <i class="fas fa-list"></i> Список пользователей
                        </a>
                        <a href="/admin/users/create" class="submenu-item">
                            <i class="fas fa-user-plus"></i> Создать пользователя
                        </a>
                        <a href="/admin/users?filter=blocked" class="submenu-item">
                            <i class="fas fa-user-slash"></i> Заблокированные
                        </a>
                        <a href="/admin/users?filter=active" class="submenu-item">
                            <i class="fas fa-user-check"></i> Активные
                        </a>
                    </div>
                </div>

                <!-- Новости -->
                <div class="nav-item has-submenu">
                    <a href="/admin/news" class="nav-link <?php echo isset($currentPage) && $currentPage === 'news' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <span>Новости</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/news" class="submenu-item">
                            <i class="fas fa-list"></i> Список новостей
                        </a>
                        <a href="/admin/news/create" class="submenu-item">
                            <i class="fas fa-plus"></i> Создать новость
                        </a>
                        <a href="/admin/news?filter=published" class="submenu-item">
                            <i class="fas fa-check-circle"></i> Опубликованные
                        </a>
                        <a href="/admin/news?filter=draft" class="submenu-item">
                            <i class="fas fa-edit"></i> Черновики
                        </a>
                    </div>
                </div>

                <!-- Файлы -->
                <div class="nav-item has-submenu">
                    <a href="/admin/files" class="nav-link <?php echo isset($currentPage) && $currentPage === 'files' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <span>Файлы</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/files" class="submenu-item">
                            <i class="fas fa-list"></i> Список файлов
                        </a>
                        <a href="/admin/files/upload" class="submenu-item">
                            <i class="fas fa-upload"></i> Загрузить файл
                        </a>
                        <a href="/admin/files?filter=recent" class="submenu-item">
                            <i class="fas fa-clock"></i> Недавние
                        </a>
                        <a href="/admin/files?filter=large" class="submenu-item">
                            <i class="fas fa-weight-hanging"></i> Большие файлы
                        </a>
                    </div>
                </div>

                <!-- Фотографии -->
                <div class="nav-item has-submenu">
                    <a href="/admin/photos" class="nav-link <?php echo isset($currentPage) && $currentPage === 'photos' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-images"></i>
                        <span>Фотографии</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/photos" class="submenu-item">
                            <i class="fas fa-list"></i> Список фотографий
                        </a>
                        <a href="/admin/photos/upload" class="submenu-item">
                            <i class="fas fa-upload"></i> Загрузить фото
                        </a>
                        <a href="/admin/photos?filter=gallery" class="submenu-item">
                            <i class="fas fa-images"></i> Галерея
                        </a>
                        <a href="/admin/photos?filter=albums" class="submenu-item">
                            <i class="fas fa-folder"></i> Альбомы
                        </a>
                    </div>
                </div>

                <!-- Аналитика -->
                <div class="nav-item has-submenu">
                    <a href="/admin/analytics" class="nav-link <?php echo isset($currentPage) && $currentPage === 'analytics' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <span>Аналитика</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/analytics" class="submenu-item">
                            <i class="fas fa-chart-bar"></i> Общая аналитика
                        </a>
                        <a href="/admin/analytics/security" class="submenu-item">
                            <i class="fas fa-shield-alt"></i> Безопасность
                        </a>
                        <a href="/admin/analytics/user-activity" class="submenu-item">
                            <i class="fas fa-user-clock"></i> Активность пользователей
                        </a>
                        <a href="/admin/analytics/sessions" class="submenu-item">
                            <i class="fas fa-desktop"></i> Сессии
                        </a>
                    </div>
                </div>

                <!-- Расширенная аналитика -->
                <div class="nav-item has-submenu">
                    <a href="/admin/enhanced-analytics" class="nav-link <?php echo isset($currentPage) && $currentPage === 'enhanced-analytics' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-brain"></i>
                        <span>Расширенная аналитика</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/enhanced-analytics" class="submenu-item">
                            <i class="fas fa-chart-pie"></i> Обзор
                        </a>
                        <a href="/admin/enhanced-analytics/geolocation" class="submenu-item">
                            <i class="fas fa-globe"></i> Геолокация
                        </a>
                        <a href="/admin/enhanced-analytics/behavior" class="submenu-item">
                            <i class="fas fa-route"></i> Поведение
                        </a>
                        <a href="/admin/enhanced-analytics/ml-anomalies" class="submenu-item">
                            <i class="fas fa-exclamation-triangle"></i> ML Аномалии
                        </a>
                        <a href="/admin/enhanced-analytics/notifications" class="submenu-item">
                            <i class="fas fa-bell"></i> Уведомления
                        </a>
                        <a href="/admin/enhanced-analytics/reports" class="submenu-item">
                            <i class="fas fa-file-alt"></i> Отчеты
                        </a>
                    </div>
                </div>

                <!-- Уведомления -->
                <div class="nav-item has-submenu">
                    <a href="/admin/notifications" class="nav-link <?php echo isset($currentPage) && $currentPage === 'notifications' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-bell"></i>
                        <span>Уведомления</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/notifications" class="submenu-item">
                            <i class="fas fa-list"></i> Все уведомления
                        </a>
                        <a href="/admin/notifications/settings" class="submenu-item">
                            <i class="fas fa-cog"></i> Настройки
                        </a>
                        <a href="/admin/notifications?filter=unread" class="submenu-item">
                            <i class="fas fa-envelope"></i> Непрочитанные
                        </a>
                        <a href="/admin/notifications?filter=critical" class="submenu-item">
                            <i class="fas fa-exclamation-circle"></i> Критические
                        </a>
                    </div>
                </div>

                <!-- Настройки -->
                <div class="nav-item has-submenu">
                    <a href="/admin/settings" class="nav-link <?php echo isset($currentPage) && $currentPage === 'settings' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <span>Настройки</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/settings" class="submenu-item">
                            <i class="fas fa-sliders-h"></i> Общие настройки
                        </a>
                        <a href="/admin/settings?section=security" class="submenu-item">
                            <i class="fas fa-shield-alt"></i> Безопасность
                        </a>
                        <a href="/admin/settings?section=notifications" class="submenu-item">
                            <i class="fas fa-bell"></i> Уведомления
                        </a>
                        <a href="/admin/settings?section=backup" class="submenu-item">
                            <i class="fas fa-database"></i> Резервное копирование
                        </a>
                    </div>
                </div>

                <!-- Профиль -->
                <div class="nav-item has-submenu">
                    <a href="/admin/profile" class="nav-link <?php echo isset($currentPage) && $currentPage === 'profile' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <span>Профиль</span>
                        <i class="fas fa-chevron-down submenu-toggle"></i>
                    </a>
                    <div class="submenu">
                        <a href="/admin/profile" class="submenu-item">
                            <i class="fas fa-user-edit"></i> Редактировать профиль
                        </a>
                        <a href="/admin/profile?section=password" class="submenu-item">
                            <i class="fas fa-key"></i> Сменить пароль
                        </a>
                        <a href="/admin/profile?section=2fa" class="submenu-item">
                            <i class="fas fa-mobile-alt"></i> 2FA
                        </a>
                        <a href="/admin/profile?section=activity" class="submenu-item">
                            <i class="fas fa-history"></i> Активность
                        </a>
                    </div>
                </div>

                <!-- Выход -->
                <div class="nav-item">
                    <a href="/admin/logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <span>Выход</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <?php if (isset($page_content)): ?>
                <?php echo $page_content; ?>
            <?php else: ?>
                <!-- Content will be included here -->
            <?php endif; ?>
        </main>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/background-animations.js"></script>
    <script src="/assets/js/admin-common.js"></script>
    
    <!-- JavaScript для подменю -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Обработка кликов по элементам с подменю
            const submenuItems = document.querySelectorAll('.nav-item.has-submenu .nav-link');
            
            submenuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const navItem = this.closest('.nav-item');
                    const isActive = navItem.classList.contains('active');
                    
                    // Закрываем все другие подменю
                    document.querySelectorAll('.nav-item.has-submenu').forEach(nav => {
                        if (nav !== navItem) {
                            nav.classList.remove('active');
                        }
                    });
                    
                    // Переключаем текущее подменю
                    navItem.classList.toggle('active');
                    
                    // Если подменю открыто, переходим по ссылке
                    if (!isActive) {
                        const href = this.getAttribute('href');
                        if (href && href !== '#') {
                            setTimeout(() => {
                                window.location.href = href;
                            }, 300);
                        }
                    }
                });
            });
            
            // Обработка кликов по элементам подменю
            const submenuLinks = document.querySelectorAll('.submenu-item');
            
            submenuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Убираем активный класс со всех элементов подменю
                    document.querySelectorAll('.submenu-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    // Добавляем активный класс к текущему элементу
                    this.classList.add('active');
                });
            });
            
            // Автоматическое определение активного элемента на основе URL
            const currentPath = window.location.pathname;
            const currentSearch = window.location.search;
            
            // Проверяем основной пункт меню
            document.querySelectorAll('.nav-item.has-submenu').forEach(navItem => {
                const mainLink = navItem.querySelector('.nav-link');
                const href = mainLink.getAttribute('href');
                
                if (href && currentPath.startsWith(href)) {
                    navItem.classList.add('active');
                    
                    // Проверяем элементы подменю
                    const submenuItems = navItem.querySelectorAll('.submenu-item');
                    submenuItems.forEach(subItem => {
                        const subHref = subItem.getAttribute('href');
                        if (subHref && (currentPath + currentSearch).includes(subHref.split('?')[0])) {
                            subItem.classList.add('active');
                        }
                    });
                }
            });
            
            // Обработка клика вне подменю для их закрытия
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.nav-item.has-submenu')) {
                    document.querySelectorAll('.nav-item.has-submenu').forEach(nav => {
                        nav.classList.remove('active');
                    });
                }
            });
        });
    </script>
    
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 