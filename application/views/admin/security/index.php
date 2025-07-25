<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - CyberAdmin</title>

    <!-- Основные стили -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Дополнительные стили -->
    <?php foreach ($additional_css as $css): ?>
        <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-background"></div>
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
                        <div class="logo-subtitle">Security Dashboard</div>
                    </div>
                </div>

                <div class="header-center">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" class="header-search" placeholder="Поиск по безопасности...">
                    </div>
                </div>

                <div class="header-right">
                    <div class="header-item">
                        <button class="header-btn" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">5</span>
                        </button>
                    </div>
                    <div class="header-item">
                        <button class="header-btn" onclick="openSettings()">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                    <div class="header-item">
                        <div class="user-menu">
                            <button class="user-btn" onclick="toggleUserMenu()">
                                <i class="fas fa-user"></i>
                                <span><?= $adminUser['user_fio'] ?? 'Admin' ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown">
                                <a href="/admin/profile"><i class="fas fa-user"></i> Профиль</a>
                                <a href="/admin/settings"><i class="fas fa-cog"></i> Настройки</a>
                                <a href="/admin/logout"><i class="fas fa-sign-out-alt"></i> Выход</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-title">Основное</div>
                    <a href="/admin" class="nav-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Дашборд</span>
                    </a>
                    <a href="/admin/users" class="nav-item">
                        <i class="fas fa-users"></i>
                        <span>Пользователи</span>
                    </a>
                    <a href="/admin/news" class="nav-item">
                        <i class="fas fa-newspaper"></i>
                        <span>Новости</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Безопасность</div>
                    <a href="/admin/security" class="nav-item active">
                        <i class="fas fa-shield-alt"></i>
                        <span>Безопасность</span>
                    </a>
                    <a href="/admin/security/audit" class="nav-item">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Аудит</span>
                    </a>
                    <a href="/admin/security/ip-blacklist" class="nav-item">
                        <i class="fas fa-ban"></i>
                        <span>Блокировка IP</span>
                    </a>
                    <a href="/admin/security/sessions" class="nav-item">
                        <i class="fas fa-key"></i>
                        <span>Сессии</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Аналитика</div>
                    <a href="/admin/analytics" class="nav-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Аналитика</span>
                    </a>
                    <a href="/admin/enhanced-analytics" class="nav-item">
                        <i class="fas fa-chart-bar"></i>
                        <span>Расширенная аналитика</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Система</div>
                    <a href="/admin/notifications" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Уведомления</span>
                    </a>
                    <a href="/admin/settings" class="nav-item">
                        <i class="fas fa-cog"></i>
                        <span>Настройки</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="content-header">
                <h1><i class="fas fa-shield-alt"></i> Безопасность системы</h1>
                <p>Мониторинг и управление безопасностью системы</p>
            </div>

            <!-- Security Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $stats['total_actions'] ?? 0 ?></div>
                        <div class="stat-label">Всего действий</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $stats['successful_logins'] ?? 0 ?></div>
                        <div class="stat-label">Успешных входов</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $stats['failed_logins'] ?? 0 ?></div>
                        <div class="stat-label">Неудачных попыток</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $stats['admin_actions'] ?? 0 ?></div>
                        <div class="stat-label">Админ действий</div>
                    </div>
                </div>
            </div>

            <!-- Security Overview -->
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-chart-pie"></i> Обзор безопасности</h2>
                    <div class="section-actions">
                        <button class="btn btn-primary" onclick="refreshStats()">
                            <i class="fas fa-sync-alt"></i> Обновить
                        </button>
                    </div>
                </div>

                <div class="security-overview">
                    <div class="overview-card">
                        <h3>Статус системы</h3>
                        <div class="status-indicator online">
                            <i class="fas fa-circle"></i>
                            <span>Система защищена</span>
                        </div>
                        <p>Все компоненты безопасности работают нормально</p>
                    </div>

                    <div class="overview-card">
                        <h3>Последние угрозы</h3>
                        <div class="threats-list">
                            <?php if (!empty($recentEvents)): ?>
                                <?php foreach (array_slice($recentEvents, 0, 3) as $event): ?>
                                    <div class="threat-item">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span><?= htmlspecialchars($event['action_type']) ?></span>
                                        <small><?= date('d.m.Y H:i', strtotime($event['created_at'])) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-threats">Угроз не обнаружено</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="overview-card">
                        <h3>Рекомендации</h3>
                        <div class="recommendations">
                            <div class="recommendation">
                                <i class="fas fa-check-circle"></i>
                                <span>Регулярно проверяйте логи безопасности</span>
                            </div>
                            <div class="recommendation">
                                <i class="fas fa-check-circle"></i>
                                <span>Мониторьте подозрительную активность</span>
                            </div>
                            <div class="recommendation">
                                <i class="fas fa-check-circle"></i>
                                <span>Обновляйте пароли пользователей</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-bolt"></i> Быстрые действия</h2>
                </div>

                <div class="quick-actions">
                    <a href="/admin/security/audit" class="action-card">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Просмотр аудита</h3>
                        <p>Просмотр логов безопасности</p>
                    </a>

                    <a href="/admin/security/ip-blacklist" class="action-card">
                        <i class="fas fa-ban"></i>
                        <h3>Блокировка IP</h3>
                        <p>Управление черным списком</p>
                    </a>

                    <a href="/admin/security/sessions" class="action-card">
                        <i class="fas fa-key"></i>
                        <h3>Активные сессии</h3>
                        <p>Управление сессиями пользователей</p>
                    </a>

                    <button class="action-card" onclick="exportSecurityLogs()">
                        <i class="fas fa-download"></i>
                        <h3>Экспорт логов</h3>
                        <p>Скачать логи безопасности</p>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    <!-- Дополнительные скрипты -->
    <?php foreach ($additional_js as $js): ?>
        <script src="<?= $js ?>"></script>
    <?php endforeach; ?>

    <script>
        // Функции для работы с безопасностью
        function refreshStats() {
            fetch('/admin/security/api/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function exportSecurityLogs() {
            window.open('/admin/security/export-logs?format=json', '_blank');
        }

        // Общие функции админки
        function toggleSidebar() {
            document.querySelector('.admin-container').classList.toggle('sidebar-collapsed');
        }

        function toggleNotifications() {
            // Логика уведомлений
        }

        function openSettings() {
            window.location.href = '/admin/settings';
        }

        function toggleUserMenu() {
            document.querySelector('.user-dropdown').classList.toggle('active');
        }

        // Закрытие dropdown при клике вне его
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-menu')) {
                document.querySelector('.user-dropdown').classList.remove('active');
            }
        });
    </script>
</body>
</html> 