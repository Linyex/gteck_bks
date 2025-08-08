<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Настройки мониторинга'; ?> - CyberAdmin</title>
    
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
            <h1><i class="fas fa-cogs"></i> Настройки мониторинга</h1>
            <p>Конфигурация параметров логирования, безопасности и уведомлений системы</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-blue" onclick="saveSettings()">
                <i class="fas fa-save"></i> Сохранить настройки
            </button>
            <button class="btn btn-green" onclick="resetSettings()">
                <i class="fas fa-undo"></i> Сбросить
            </button>
        </div>
    </div>

    <div class="cyber-grid">
        <div class="cyber-card">
            <div class="cyber-title">
                <i class="fas fa-file-alt"></i> Настройки логирования
            </div>
            <form id="loggingSettingsForm" class="cyber-form">
                <div class="form-group">
                    <label for="logLevel">Уровень логирования</label>
                    <select class="cyber-select" id="logLevel">
                        <option value="debug">Debug</option>
                        <option value="info">Info</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="logRetention">Хранение логов (дни)</label>
                    <input type="number" class="cyber-input" id="logRetention" value="90" min="1" max="365">
                </div>
                <div class="form-group">
                    <label for="logRotation">Ротация логов</label>
                    <select class="cyber-select" id="logRotation">
                        <option value="daily">Ежедневно</option>
                        <option value="weekly">Еженедельно</option>
                        <option value="monthly">Ежемесячно</option>
                    </select>
                </div>
                <div class="cyber-checkbox">
                    <input type="checkbox" id="enableFileLogging" checked>
                    <label for="enableFileLogging">
                        Включить запись в файлы
                    </label>
                </div>
            </form>
        </div>

        <div class="cyber-card">
            <div class="cyber-title">
                <i class="fas fa-shield-alt"></i> Настройки безопасности
            </div>
            <form id="securitySettingsForm" class="cyber-form">
                <div class="form-group">
                    <label for="failedLoginThreshold">Порог неудачных входов</label>
                    <input type="number" class="cyber-input" id="failedLoginThreshold" value="5" min="1" max="20">
                </div>
                <div class="form-group">
                    <label for="blockDuration">Длительность блокировки (минуты)</label>
                    <input type="number" class="cyber-input" id="blockDuration" value="30" min="5" max="1440">
                </div>
                <div class="form-group">
                    <label for="suspiciousActivityThreshold">Порог подозрительной активности</label>
                    <input type="number" class="cyber-input" id="suspiciousActivityThreshold" value="10" min="1" max="100">
                </div>
                <div class="cyber-checkbox">
                    <input type="checkbox" id="enableAutoBlocking" checked>
                    <label for="enableAutoBlocking">
                        Автоматическая блокировка IP
                    </label>
                </div>
                <div class="cyber-checkbox">
                    <input type="checkbox" id="enableEmailAlerts" checked>
                    <label for="enableEmailAlerts">
                        Email уведомления
                    </label>
                </div>
            </form>
        </div>
    </div>

    <div class="cyber-grid">
        <div class="cyber-card">
            <div class="cyber-title">
                <i class="fas fa-chart-line"></i> Настройки мониторинга
            </div>
            <form id="monitoringSettingsForm" class="cyber-form">
                <div class="form-group">
                    <label for="monitoringInterval">Интервал проверки (секунды)</label>
                    <input type="number" class="cyber-input" id="monitoringInterval" value="60" min="10" max="3600">
                </div>
                <div class="form-group">
                    <label for="alertThreshold">Порог для уведомлений</label>
                    <select class="cyber-select" id="alertThreshold">
                        <option value="low">Низкий</option>
                        <option value="medium">Средний</option>
                        <option value="high">Высокий</option>
                        <option value="critical">Критический</option>
                    </select>
                </div>
                <div class="cyber-checkbox">
                    <input type="checkbox" id="enableRealTimeMonitoring" checked>
                    <label for="enableRealTimeMonitoring">
                        Мониторинг в реальном времени
                    </label>
                </div>
                <div class="cyber-checkbox">
                    <input type="checkbox" id="enablePerformanceMonitoring" checked>
                    <label for="enablePerformanceMonitoring">
                        Мониторинг производительности
                    </label>
                </div>
            </form>
        </div>

        <div class="cyber-card">
            <div class="cyber-title">
                <i class="fas fa-bell"></i> Настройки уведомлений
            </div>
            <form id="notificationSettingsForm" class="cyber-form">
                <div class="form-group">
                    <label for="notificationEmail">Email для уведомлений</label>
                    <input type="email" class="cyber-input" id="notificationEmail" value="admin@nocontrgtec.com">
                </div>
                <div class="form-group">
                    <label for="notificationLevel">Уровень уведомлений</label>
                    <select class="cyber-select" id="notificationLevel">
                        <option value="all">Все</option>
                        <option value="critical">Только критические</option>
                        <option value="high">Высокий и выше</option>
                        <option value="medium">Средний и выше</option>
                    </select>
                </div>
                <div class="cyber-checkbox">
                    <input type="checkbox" id="enableBrowserNotifications" checked>
                    <label for="enableBrowserNotifications">
                        Уведомления в браузере
                    </label>
                </div>
                <div class="cyber-checkbox">
                    <input type="checkbox" id="enableSoundAlerts">
                    <label for="enableSoundAlerts">
                        Звуковые уведомления
                    </label>
                </div>
            </form>
        </div>
    </div>

    <div class="cyber-card">
        <div class="cyber-title">
            <i class="fas fa-tools"></i> Действия
        </div>
        <div class="cyber-actions">
            <button class="btn btn-yellow" onclick="clearLogs()">
                <i class="fas fa-trash"></i> Очистить логи
            </button>
            <button class="btn btn-blue" onclick="exportSettings()">
                <i class="fas fa-download"></i> Экспорт настроек
            </button>
            <button class="btn btn-green" onclick="importSettings()">
                <i class="fas fa-upload"></i> Импорт настроек
            </button>
            <button class="btn btn-red" onclick="resetToDefaults()">
                <i class="fas fa-undo-alt"></i> Сбросить к значениям по умолчанию
            </button>
        </div>
    </div>
</div>

<script>
function saveSettings() {
    // Собираем все настройки
    const settings = {
        logging: {
            level: document.getElementById('logLevel').value,
            retention: document.getElementById('logRetention').value,
            rotation: document.getElementById('logRotation').value,
            enableFileLogging: document.getElementById('enableFileLogging').checked
        },
        security: {
            failedLoginThreshold: document.getElementById('failedLoginThreshold').value,
            blockDuration: document.getElementById('blockDuration').value,
            suspiciousActivityThreshold: document.getElementById('suspiciousActivityThreshold').value,
            enableAutoBlocking: document.getElementById('enableAutoBlocking').checked,
            enableEmailAlerts: document.getElementById('enableEmailAlerts').checked
        },
        monitoring: {
            interval: document.getElementById('monitoringInterval').value,
            alertThreshold: document.getElementById('alertThreshold').value,
            enableRealTimeMonitoring: document.getElementById('enableRealTimeMonitoring').checked,
            enablePerformanceMonitoring: document.getElementById('enablePerformanceMonitoring').checked
        },
        notifications: {
            email: document.getElementById('notificationEmail').value,
            level: document.getElementById('notificationLevel').value,
            enableBrowserNotifications: document.getElementById('enableBrowserNotifications').checked,
            enableSoundAlerts: document.getElementById('enableSoundAlerts').checked
        }
    };

    // Отправляем настройки на сервер
    fetch('/admin/monitoring/settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Настройки сохранены успешно');
        } else {
            showAlert('error', 'Ошибка при сохранении настроек');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Ошибка при сохранении настроек');
    });
}

function resetSettings() {
    // Сброс к текущим значениям
    location.reload();
}

function clearLogs() {
    if (confirm('Вы уверены, что хотите очистить все логи?')) {
        fetch('/admin/monitoring/clear-logs', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Логи очищены успешно');
            } else {
                showAlert('error', 'Ошибка при очистке логов');
            }
        });
    }
}

function exportSettings() {
    // Экспорт настроек
    console.log('Экспорт настроек...');
}

function importSettings() {
    // Импорт настроек
    console.log('Импорт настроек...');
}

function resetToDefaults() {
    if (confirm('Вы уверены, что хотите сбросить все настройки к значениям по умолчанию?')) {
        // Сброс к значениям по умолчанию
        console.log('Сброс к значениям по умолчанию...');
    }
}

function showAlert(type, message) {
    // Показ уведомления в cyberpunk стиле
    const alertDiv = document.createElement('div');
    alertDiv.className = `cyber-alert cyber-alert-${type === 'success' ? 'success' : 'error'}`;
    alertDiv.innerHTML = `
        <div class="alert-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    document.querySelector('.cyber-container').insertBefore(alertDiv, document.querySelector('.page-header'));
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
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