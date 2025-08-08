<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Мониторинг системы'; ?> - CyberAdmin</title>
    
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
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-shield-alt"></i> Мониторинг системы</h1>
            <p>Отслеживание безопасности и производительности системы в реальном времени</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-blue" onclick="refreshMonitoring()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
            <button class="btn btn-orange" onclick="runRealTimeMonitoring()">
                <i class="fas fa-radar"></i> Запустить мониторинг
            </button>
            <button class="btn btn-green" onclick="exportReport()">
                <i class="fas fa-download"></i> Экспорт
            </button>
        </div>
    </div>

    <!-- Статистика безопасности -->
    <div class="stats-grid">
        <div class="stat-card security-score-card">
            <div class="stat-icon">
                <i class="fas fa-shield-alt pulse"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $securityStats['security_score'] ?></div>
                <div class="stat-label">ОБЩИЙ БАЛЛ БЕЗОПАСНОСТИ</div>
                <div class="stat-progress">
                    <div class="progress-bar <?= $securityStats['security_score'] >= 80 ? 'success' : ($securityStats['security_score'] >= 60 ? 'warning' : 'danger') ?>" 
                         style="width: <?= $securityStats['security_score'] ?>%"></div>
                </div>
                <div class="stat-status <?= $securityStats['system_status'] ?>">
                    <?= ucfirst($securityStats['system_status']) ?>
                </div>
                <div class="stat-details">
                    <?php
                    $score = $securityStats['security_score'];
                    if ($score >= 80) {
                        echo '<span class="detail-item success"><i class="fas fa-check"></i> Отличная защита</span>';
                    } elseif ($score >= 60) {
                        echo '<span class="detail-item warning"><i class="fas fa-exclamation-triangle"></i> Требует внимания</span>';
                    } else {
                        echo '<span class="detail-item danger"><i class="fas fa-times"></i> Критический уровень</span>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="stat-card threats-card">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle bounce"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $securityStats['total_threats'] ?></div>
                <div class="stat-label">ВСЕГО УГРОЗ</div>
                <div class="stat-progress">
                    <div class="progress-bar <?= $securityStats['total_threats'] > 10 ? 'danger' : ($securityStats['total_threats'] > 5 ? 'warning' : 'success') ?>" 
                         style="width: <?= min(($securityStats['total_threats'] / 10) * 100, 100) ?>%"></div>
                </div>
                <div class="stat-details">
                    <?php if (isset($securityStats['threats_by_type'])): ?>
                        <?php foreach ($securityStats['threats_by_type'] as $threat): ?>
                            <span class="detail-item">
                                <i class="fas fa-circle"></i> 
                                <?= ucfirst(str_replace('_', ' ', $threat['threat_type'])) ?>: <?= $threat['count'] ?>
                            </span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="stat-card blocked-card">
            <div class="stat-icon">
                <i class="fas fa-ban rotate"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $securityStats['blocked_ips'] ?></div>
                <div class="stat-label">ЗАБЛОКИРОВАНО IP</div>
                <div class="stat-progress">
                    <div class="progress-bar <?= $securityStats['blocked_ips'] > 20 ? 'warning' : 'success' ?>" 
                         style="width: <?= min(($securityStats['blocked_ips'] / 50) * 100, 100) ?>%"></div>
                </div>
                <div class="stat-details">
                    <span class="detail-item">
                        <i class="fas fa-shield-alt"></i> 
                        Защита активна
                    </span>
                </div>
            </div>
        </div>

        <div class="stat-card activity-card">
            <div class="stat-icon">
                <i class="fas fa-eye pulse"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $securityStats['suspicious_activities'] ?></div>
                <div class="stat-label">ПОДОЗРИТЕЛЬНЫХ ДЕЙСТВИЙ</div>
                <div class="stat-progress">
                    <div class="progress-bar <?= $securityStats['suspicious_activities'] > 10 ? 'danger' : ($securityStats['suspicious_activities'] > 5 ? 'warning' : 'success') ?>" 
                         style="width: <?= min(($securityStats['suspicious_activities'] / 20) * 100, 100) ?>%"></div>
                </div>
                <div class="stat-details">
                    <span class="detail-item">
                        <i class="fas fa-search"></i> 
                        Мониторинг активен
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Системная статистика -->
    <div class="cyber-card">
        <div class="cyber-title">
            <i class="fas fa-server"></i> Системные ресурсы
        </div>
        <div class="cyber-grid">
            <div class="cyber-stat">
                <div class="stat-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">CPU</div>
                    <div class="stat-value"><?= $systemStats['cpu_usage'] ?>%</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill <?= $systemStats['cpu_usage'] > 80 ? 'danger' : ($systemStats['cpu_usage'] > 60 ? 'warning' : 'success') ?>" 
                             style="width: <?= $systemStats['cpu_usage'] ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="cyber-stat">
                <div class="stat-icon">
                    <i class="fas fa-memory"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Память</div>
                    <div class="stat-value"><?= $systemStats['memory_usage'] ?>%</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill <?= $systemStats['memory_usage'] > 80 ? 'danger' : ($systemStats['memory_usage'] > 60 ? 'warning' : 'success') ?>" 
                             style="width: <?= $systemStats['memory_usage'] ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="cyber-stat">
                <div class="stat-icon">
                    <i class="fas fa-hdd"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Диск</div>
                    <div class="stat-value"><?= $systemStats['disk_usage'] ?>%</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill <?= $systemStats['disk_usage'] > 80 ? 'danger' : ($systemStats['disk_usage'] > 60 ? 'warning' : 'success') ?>" 
                             style="width: <?= $systemStats['disk_usage'] ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="cyber-stat">
                <div class="stat-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Сеть</div>
                    <div class="stat-value"><?= $systemStats['network_usage'] ?> MB/s</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill <?= $systemStats['network_usage'] > 100 ? 'danger' : ($systemStats['network_usage'] > 50 ? 'warning' : 'success') ?>" 
                             style="width: <?= min(($systemStats['network_usage'] / 200) * 100, 100) ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Последние угрозы -->
    <div class="cyber-card">
        <div class="cyber-title">
            <i class="fas fa-exclamation-triangle"></i> Последние угрозы
        </div>
        
        <div class="cyber-list">
            <?php if (!empty($recentThreats)): ?>
                <?php foreach ($recentThreats as $threat): ?>
                <div class="cyber-list-item threat-item severity-<?= $threat['severity'] ?? 'medium' ?>">
                    <div class="list-item-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="list-item-content">
                        <div class="list-item-title"><?= htmlspecialchars($threat['threat_type'] ?? 'Unknown') ?></div>
                        <div class="list-item-subtitle">
                            IP: <?= htmlspecialchars($threat['ip_address'] ?? 'Unknown') ?> | 
                            <?= date('d.m.Y H:i', strtotime($threat['created_at'] ?? 'now')) ?>
                        </div>
                    </div>
                    <div class="list-item-status">
                        <span class="status-badge <?= $threat['severity'] ?? 'medium' ?>">
                            <?= ucfirst($threat['severity'] ?? 'medium') ?>
                        </span>
                        <span class="status-badge <?= ($threat['is_resolved'] ?? false) ? 'resolved' : 'active' ?>">
                            <?= ($threat['is_resolved'] ?? false) ? 'Resolved' : 'Active' ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="cyber-empty">
                    <i class="fas fa-shield-check"></i>
                    <p>Угроз не обнаружено</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Быстрые действия -->
    <div class="cyber-card">
        <div class="cyber-title">
            <i class="fas fa-bolt"></i> Быстрые действия
        </div>
        <div class="cyber-actions">
            <button class="btn btn-blue" onclick="runSecurityScan()">
                <i class="fas fa-search"></i> Запустить сканирование
            </button>
            <button class="btn btn-yellow" onclick="blockSuspiciousIP()">
                <i class="fas fa-ban"></i> Заблокировать IP
            </button>
            <button class="btn btn-green" onclick="generateReport()">
                <i class="fas fa-file-alt"></i> Создать отчет
            </button>
            <button class="btn btn-purple" onclick="updateSecurityRules()">
                <i class="fas fa-cog"></i> Обновить правила
            </button>
        </div>
    </div>
</div>

<script>
// Функции для мониторинга
function refreshMonitoring() {
    location.reload();
}

function exportReport() {
    // Логика экспорта отчета
    showNotification('Отчет экспортирован', 'success');
}

function runSecurityScan() {
    showNotification('Сканирование запущено...', 'info');
    // Логика запуска сканирования
}

function blockSuspiciousIP() {
    const ip = prompt('Введите IP адрес для блокировки:');
    if (ip) {
        showNotification(`IP ${ip} заблокирован`, 'success');
    }
}

function generateReport() {
    showNotification('Отчет создается...', 'info');
    // Логика создания отчета
}

function updateSecurityRules() {
    showNotification('Правила безопасности обновлены', 'success');
    // Логика обновления правил
}

function runRealTimeMonitoring() {
    showNotification('Запуск мониторинга в реальном времени...', 'info');
    
    // Отключаем кнопку на время выполнения
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Выполняется...';
    
    // AJAX запрос для запуска мониторинга
    fetch('/admin/monitoring/api/run-monitoring', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.threats && data.threats.length > 0) {
                showNotification(`Обнаружено ${data.threats.length} угроз!`, 'error');
                // Можно добавить логику для отображения угроз
            } else {
                showNotification('Мониторинг завершен. Угроз не обнаружено.', 'success');
            }
        } else {
            showNotification('Ошибка при выполнении мониторинга: ' + (data.error || 'Неизвестная ошибка'), 'error');
        }
    })
    .catch(error => {
        console.error('Ошибка мониторинга:', error);
        showNotification('Ошибка при выполнении мониторинга', 'error');
    })
    .finally(() => {
        // Восстанавливаем кнопку
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function showNotification(message, type) {
    // Показ уведомления в cyberpunk стиле
    const notificationDiv = document.createElement('div');
    notificationDiv.className = `cyber-notification cyber-notification-${type}`;
    notificationDiv.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    document.querySelector('.cyber-container').insertBefore(notificationDiv, document.querySelector('.page-header'));
    
    setTimeout(() => {
        notificationDiv.remove();
    }, 5000);
}

// Автообновление каждые 30 секунд
setInterval(() => {
    // Обновление только критических данных
    updateSecurityStats();
}, 30000);

function updateSecurityStats() {
    // AJAX запрос для обновления статистики
    fetch('/admin/api/monitoring/stats')
        .then(response => response.json())
        .then(data => {
            // Обновление данных на странице
            console.log('Статистика обновлена');
        })
        .catch(error => {
            console.error('Ошибка обновления статистики:', error);
        });
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