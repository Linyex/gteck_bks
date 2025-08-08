<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Админ панель'; ?> - CyberAdmin</title>
    
    <!-- Основные стили -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
        <header class="new-header">
            <div class="header-wrapper">
                <!-- Логотип и название -->
                <div class="header-brand">
                    <div class="brand-logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="brand-text">
                        <h1>CyberAdmin</h1>
                        <span>Admin Panel</span>
                    </div>
                </div>

                <!-- Правая часть header -->
                <div class="header-actions"> 
                    <!-- Мобильное меню -->
                    <div class="action-item mobile-menu-toggle">
                        <button class="action-btn" onclick="toggleMobileMenu()">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    
                    <!-- Пользователь -->
                    <div class="action-item">
                        <button class="action-btn user-btn" onclick="toggleUserMenu()">
                            <div class="user-avatar">A</div>
                            <span class="user-name">Admin</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <!-- Уведомления -->
                    <div class="action-item">
                        <button class="action-btn" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>

                    <!-- Настройки -->
                    <div class="action-item">
                        <button class="action-btn" onclick="openSettings()">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Поднавигация для категорий -->
            <div class="sub-navigation" id="subNavigation">
                <!-- Поднавигация будет динамически добавляться через JavaScript -->
            </div>
        </header>

        <!-- Боковая навигация -->
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link <?php echo isset($currentPage) && $currentPage === 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Дашборд</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link <?php echo isset($currentPage) && $currentPage === 'users' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i>
                        <span>Пользователи</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/news" class="nav-link <?php echo isset($currentPage) && $currentPage === 'news' ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper"></i>
                        <span>Новости</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/files" class="nav-link <?php echo isset($currentPage) && $currentPage === 'files' ? 'active' : ''; ?>">
                        <i class="fas fa-file"></i>
                        <span>Файлы</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/photos" class="nav-link <?php echo isset($currentPage) && $currentPage === 'photos' ? 'active' : ''; ?>">
                        <i class="fas fa-images"></i>
                        <span>Медиа</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/analytics" class="nav-link <?php echo isset($currentPage) && $currentPage === 'analytics' ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Аналитика</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/security" class="nav-link <?php echo isset($currentPage) && $currentPage === 'security' ? 'active' : ''; ?>">
                        <i class="fas fa-lock"></i>
                        <span>Безопасность</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/monitoring" class="nav-link <?php echo isset($currentPage) && $currentPage === 'monitoring' ? 'active' : ''; ?>">
                        <i class="fas fa-shield-alt"></i>
                        <span>Мониторинг</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/settings" class="nav-link <?php echo isset($currentPage) && $currentPage === 'settings' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        <span>Настройки</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Выход</span>
                    </a>
                </li>
            </ul>
        </nav>

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
    
    <!-- JavaScript для боковой навигации -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Автоматическое определение активного элемента на основе URL
            const currentPath = window.location.pathname;
            
            document.querySelectorAll('.nav-item').forEach(navItem => {
                const link = navItem.querySelector('.nav-link');
                const href = link.getAttribute('href');
                
                if (href && currentPath.startsWith(href)) {
                    navItem.classList.add('active');
                }
            });
            
            // Закрытие мобильного меню при изменении размера окна
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1200) {
                    document.querySelector('.sidebar-nav').classList.remove('mobile-open');
                    document.querySelector('.admin-main').classList.remove('mobile-open');
                }
            });
            
            // Динамическое создание поднавигации
            function createSubNavigation() {
                const subNav = document.getElementById('subNavigation');
                const currentPath = window.location.pathname;
                
                // Определяем категорию на основе текущего пути
                let category = '';
                let subLinks = [];
                
                if (currentPath.startsWith('/admin/users')) {
                    category = 'Пользователи';
                    subLinks = [
                        { href: '/admin/users', text: 'Список пользователей', icon: 'fas fa-list' },
                        { href: '/admin/users/create', text: 'Создать пользователя', icon: 'fas fa-user-plus' },
                        { href: '/admin/users/groups', text: 'Группы', icon: 'fas fa-users' }
                    ];
                } else if (currentPath.startsWith('/admin/news')) {
                    category = 'Новости';
                    subLinks = [
                        { href: '/admin/news', text: 'Все новости', icon: 'fas fa-list' },
                        { href: '/admin/news/create', text: 'Создать новость', icon: 'fas fa-plus' },
                        { href: '/admin/news/categories', text: 'Категории', icon: 'fas fa-tags' }
                    ];
                } else if (currentPath.startsWith('/admin/files')) {
                    category = 'Файлы';
                    subLinks = [
                        { href: '/admin/files', text: 'Все файлы', icon: 'fas fa-list' },
                        { href: '/admin/files/upload', text: 'Загрузить файл', icon: 'fas fa-upload' },
                        { href: '/admin/control-files', text: 'Контрольные работы', icon: 'fas fa-file-pdf-o' },
                        { href: '/admin/umk-files', text: 'УМК файлы', icon: 'fas fa-book' }
                    ];
                } else if (currentPath.startsWith('/admin/photos')) {
                    category = 'Медиа';
                    subLinks = [
                        { href: '/admin/photos', text: 'Фотографии', icon: 'fas fa-images' },
                        { href: '/admin/photos/upload', text: 'Загрузить фото', icon: 'fas fa-upload' },
                        { href: '/admin/photos/albums', text: 'Альбомы', icon: 'fas fa-folder' }
                    ];
                } else if (currentPath.startsWith('/admin/analytics')) {
                    category = 'Аналитика';
                    subLinks = [
                        { href: '/admin/analytics', text: 'Основная аналитика', icon: 'fas fa-chart-bar' },
                        { href: '/admin/enhanced-analytics', text: 'Расширенная аналитика', icon: 'fas fa-brain' },
                        { href: '/admin/analytics/reports', text: 'Отчеты', icon: 'fas fa-file-alt' }
                    ];
                } else if (currentPath.startsWith('/admin/security')) {
                    category = 'Безопасность';
                    subLinks = [
                        { href: '/admin/security', text: 'Обзор безопасности', icon: 'fas fa-shield-alt' },
                        { href: '/admin/security/audit', text: 'Аудит', icon: 'fas fa-clipboard-list' },
                        { href: '/admin/security/sessions', text: 'Активные сессии', icon: 'fas fa-users' },
                        { href: '/admin/security/logs', text: 'Логи безопасности', icon: 'fas fa-file-alt' }
                    ];
                } else if (currentPath.startsWith('/admin/monitoring')) {
                    category = 'Мониторинг';
                    subLinks = [
                        { href: '/admin/monitoring', text: 'Дашборд мониторинга', icon: 'fas fa-tachometer-alt' },
                        { href: '/admin/monitoring/logs', text: 'Системные логи', icon: 'fas fa-file-alt' },
                        { href: '/admin/monitoring/performance', text: 'Производительность', icon: 'fas fa-chart-line' }
                    ];
                } else if (currentPath.startsWith('/admin/settings')) {
                    category = 'Настройки';
                    subLinks = [
                        { href: '/admin/settings', text: 'Общие настройки', icon: 'fas fa-cog' },
                        { href: '/admin/group-passwords', text: 'Пароли групп', icon: 'fas fa-key' },
                        { href: '/admin/notifications', text: 'Уведомления', icon: 'fas fa-bell' },
                        { href: '/admin/profile', text: 'Профиль', icon: 'fas fa-user' }
                    ];
                }
                
                // Создаем поднавигацию только если есть категория
                if (category && subLinks.length > 0) {
                    let subNavHTML = `
                        <div class="sub-nav-container">
                            <div class="sub-nav-header">
                                <h3>${category}</h3>
                            </div>
                            <div class="sub-nav-links">
                    `;
                    
                    subLinks.forEach(link => {
                        const isActive = currentPath === link.href;
                        subNavHTML += `
                            <a href="${link.href}" class="sub-nav-link ${isActive ? 'active' : ''}">
                                <i class="${link.icon}"></i>
                                <span>${link.text}</span>
                            </a>
                        `;
                    });
                    
                    subNavHTML += `
                            </div>
                        </div>
                    `;
                    
                    subNav.innerHTML = subNavHTML;
                    subNav.style.display = 'block';
                } else {
                    subNav.style.display = 'none';
                }
            }
            
            // Запускаем создание поднавигации
            createSubNavigation();
        });
    </script>
    
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 