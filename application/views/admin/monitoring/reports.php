<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Отчеты мониторинга'; ?> - CyberAdmin</title>
    
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
                    <a href="/admin/dashboard" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Дашборд</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Пользователи</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/news" class="nav-link">
                        <i class="fas fa-newspaper"></i>
                        <span>Новости</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/files" class="nav-link">
                        <i class="fas fa-file"></i>
                        <span>Файлы</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/photos" class="nav-link">
                        <i class="fas fa-images"></i>
                        <span>Медиа</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/analytics" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Аналитика</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/security" class="nav-link">
                        <i class="fas fa-lock"></i>
                        <span>Безопасность</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/monitoring" class="nav-link">
                        <i class="fas fa-shield-alt"></i>
                        <span>Мониторинг</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/settings" class="nav-link">
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
            <div class="cyber-container">
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-file-alt"></i> Отчеты мониторинга</h1>
            <p>Создание и управление отчетами по безопасности и производительности системы</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-blue" onclick="generateReport()">
                <i class="fas fa-file-alt"></i> Создать отчет
            </button>
            <button class="btn btn-green" onclick="exportReport()">
                <i class="fas fa-download"></i> Экспорт
            </button>
        </div>
    </div>

    <div class="cyber-card">
        <div class="cyber-title">
            <i class="fas fa-shield-alt"></i> Отчеты безопасности
        </div>
        <div class="cyber-table-wrapper">
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Тип отчета</th>
                        <th>Период</th>
                        <th>Статус</th>
                        <th>Создан</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody id="reportsTable">
                    <tr>
                        <td colspan="6" class="text-center">Отчеты не найдены</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="cyber-grid">
        <div class="cyber-card">
            <div class="cyber-title">
                <i class="fas fa-bolt"></i> Быстрые отчеты
            </div>
            <div class="cyber-list">
                <a href="#" class="cyber-list-item" onclick="generateQuickReport('daily')">
                    <div class="list-item-content">
                        <div class="list-item-title">Ежедневный отчет</div>
                        <div class="list-item-subtitle">За последние 24 часа</div>
                    </div>
                    <div class="list-item-icon">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="cyber-list-item" onclick="generateQuickReport('weekly')">
                    <div class="list-item-content">
                        <div class="list-item-title">Еженедельный отчет</div>
                        <div class="list-item-subtitle">За последние 7 дней</div>
                    </div>
                    <div class="list-item-icon">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="cyber-list-item" onclick="generateQuickReport('monthly')">
                    <div class="list-item-content">
                        <div class="list-item-title">Ежемесячный отчет</div>
                        <div class="list-item-subtitle">За последние 30 дней</div>
                    </div>
                    <div class="list-item-icon">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>

        <div class="cyber-card">
            <div class="cyber-title">
                <i class="fas fa-cogs"></i> Настройки отчетов
            </div>
            <form id="reportSettingsForm" class="cyber-form">
                <div class="form-group">
                    <label for="reportType">Тип отчета</label>
                    <select class="cyber-select" id="reportType">
                        <option value="security">Безопасность</option>
                        <option value="activity">Активность</option>
                        <option value="performance">Производительность</option>
                        <option value="errors">Ошибки</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reportPeriod">Период</label>
                    <select class="cyber-select" id="reportPeriod">
                        <option value="24h">24 часа</option>
                        <option value="7d">7 дней</option>
                        <option value="30d">30 дней</option>
                        <option value="custom">Произвольный</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reportFormat">Формат</label>
                    <select class="cyber-select" id="reportFormat">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                        <option value="json">JSON</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-blue" onclick="saveReportSettings()">
                        <i class="fas fa-save"></i> Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateReport() {
    // Логика создания отчета
    console.log('Создание отчета...');
}

function exportReport() {
    // Логика экспорта отчета
    console.log('Экспорт отчета...');
}

function generateQuickReport(type) {
    // Логика создания быстрого отчета
    console.log('Создание быстрого отчета:', type);
}

function saveReportSettings() {
    // Логика сохранения настроек
    console.log('Сохранение настроек отчета...');
}
</script>
    
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
                
                if (currentPath.startsWith('/admin/monitoring')) {
                    category = 'Мониторинг';
                    subLinks = [
                        { href: '/admin/monitoring', text: 'Дашборд мониторинга', icon: 'fas fa-tachometer-alt' },
                        { href: '/admin/monitoring/logs', text: 'Системные логи', icon: 'fas fa-file-alt' },
                        { href: '/admin/monitoring/threats', text: 'Угрозы', icon: 'fas fa-exclamation-triangle' },
                        { href: '/admin/monitoring/reports', text: 'Отчеты', icon: 'fas fa-chart-bar' },
                        { href: '/admin/monitoring/settings', text: 'Настройки', icon: 'fas fa-cog' }
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