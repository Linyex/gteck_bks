<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расширенная аналитика - Админ панель</title>
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="/assets/css/enhanced-analytics.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
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

        <!-- Основной контент -->
        <main class="admin-main">
            <div class="main-container">
                <!-- Заголовок страницы -->
                <div class="page-header">
                    <h1>Расширенная аналитика</h1>
                    <p>Комплексный анализ активности пользователей и безопасности системы</p>
                </div>

                <!-- Основные метрики -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-number"><?= $analytics['total_users'] ?? 0 ?></div>
                            <div class="metric-label">Всего пользователей</div>
                            <div class="metric-trend">
                                <span class="trend-up">+<?= $analytics['new_users_today'] ?? 0 ?> сегодня</span>
                            </div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-number"><?= $analytics['active_users'] ?? 0 ?></div>
                            <div class="metric-label">Активных пользователей</div>
                            <div class="metric-trend">
                                <span class="trend-up">+<?= $analytics['active_sessions'] ?? 0 ?> сессий</span>
                            </div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-number"><?= $analytics['failed_logins_24h'] ?? 0 ?></div>
                            <div class="metric-label">Неудачных входов (24ч)</div>
                            <div class="metric-trend">
                                <span class="trend-down">Безопасность</span>
                            </div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-number"><?= $analytics['suspicious_activities'] ?? 0 ?></div>
                            <div class="metric-label">Подозрительных действий</div>
                            <div class="metric-trend">
                                <span class="trend-warning">Требуют внимания</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Графики и диаграммы -->
                <div class="charts-section">
                    <div class="chart-row">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Активность пользователей</h3>
                                <div class="chart-controls">
                                    <select id="activityPeriod" class="form-select">
                                        <option value="7">7 дней</option>
                                        <option value="30" selected>30 дней</option>
                                        <option value="90">90 дней</option>
                                    </select>
                                    <a href="/admin/enhanced-analytics/activity-details" class="btn btn-sm">
                                        <i class="fas fa-external-link-alt"></i>
                                        Детали
                                    </a>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="activityChart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Географическое распределение</h3>
                                <div class="chart-controls">
                                    <button class="btn btn-sm" onclick="refreshMap()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <div id="geolocationMap" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="chart-row">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Рост пользователей</h3>
                            </div>
                            <div class="chart-container">
                                <canvas id="growthChart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Аномалии безопасности</h3>
                            </div>
                            <div class="chart-container">
                                <canvas id="anomaliesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Реал-тайм активность -->
                <div class="realtime-section">
                    <div class="section-header">
                        <h3>Реал-тайм активность</h3>
                        <div class="section-controls">
                            <span class="status-indicator">
                                <i class="fas fa-circle"></i>
                                Активно
                            </span>
                        </div>
                    </div>
                    
                    <div class="realtime-grid">
                        <div class="realtime-card">
                            <h4>Текущие сессии</h4>
                            <div class="session-list">
                                <?php if (!empty($realtimeData['current_sessions'])): ?>
                                    <?php foreach (array_slice($realtimeData['current_sessions'], 0, 5) as $session): ?>
                                        <div class="session-item">
                                            <div class="session-user">
                                                <i class="fas fa-user"></i>
                                                <span><?= htmlspecialchars($session['user_fio'] ?? $session['user_login'] ?? 'Неизвестный') ?></span>
                                            </div>
                                            <div class="session-time">
                                                <?= date('H:i', strtotime($session['last_activity'] ?? 'now')) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-data">Нет активных сессий</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="realtime-card">
                            <h4>Последние действия</h4>
                            <div class="activity-list">
                                <?php if (!empty($realtimeData['recent_activities'])): ?>
                                    <?php foreach (array_slice($realtimeData['recent_activities'], 0, 5) as $activity): ?>
                                        <div class="activity-item">
                                            <div class="activity-icon">
                                                <i class="fas fa-mouse-pointer"></i>
                                            </div>
                                            <div class="activity-content">
                                                <div class="activity-user">
                                                    <?= htmlspecialchars($activity['user_fio'] ?? $activity['user_login'] ?? 'Неизвестный') ?>
                                                </div>
                                                <div class="activity-action">
                                                    <?= htmlspecialchars($activity['action_type'] ?? 'Действие') ?>
                                                </div>
                                            </div>
                                            <div class="activity-time">
                                                <?= date('H:i', strtotime($activity['activity_time'] ?? 'now')) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-data">Нет данных о действиях</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="realtime-card">
                            <h4>Уведомления безопасности</h4>
                            <div class="alerts-list">
                                <?php if (!empty($realtimeData['security_alerts'])): ?>
                                    <?php foreach (array_slice($realtimeData['security_alerts'], 0, 3) as $alert): ?>
                                        <div class="alert-item alert-<?= $alert['severity'] ?? 'info' ?>">
                                            <div class="alert-icon">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                            <div class="alert-content">
                                                <div class="alert-title">
                                                    <?= htmlspecialchars($alert['title'] ?? 'Уведомление') ?>
                                                </div>
                                                <div class="alert-description">
                                                    <?= htmlspecialchars($alert['description'] ?? '') ?>
                                                </div>
                                            </div>
                                            <div class="alert-time">
                                                <?= date('H:i', strtotime($alert['created_at'] ?? 'now')) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-data">Нет уведомлений</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Данные для графиков
        const chartData = <?= json_encode($chartData ?? []) ?>;
        const analytics = <?= json_encode($analytics ?? []) ?>;
        const geolocationData = <?= json_encode($geolocationData ?? []) ?>;
        const behaviorData = <?= json_encode($behaviorData ?? []) ?>;
        const mlAnomalies = <?= json_encode($mlAnomalies ?? []) ?>;
        const realtimeData = <?= json_encode($realtimeData ?? []) ?>;

        // Инициализация графиков
        document.addEventListener('DOMContentLoaded', function() {
            initActivityChart();
            initGrowthChart();
            initAnomaliesChart();
            initGeolocationMap();
        });

        // График активности
        function initActivityChart() {
            const ctx = document.getElementById('activityChart').getContext('2d');
            const activityData = chartData.activity_timeline || [];
            
            const labels = activityData.map(item => item.date || '');
            const data = activityData.map(item => item.count || 0);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Активность',
                        data: data,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#fff'
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#fff'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#fff'
                            }
                        }
                    }
                }
            });
        }

        // График роста пользователей
        function initGrowthChart() {
            const ctx = document.getElementById('growthChart').getContext('2d');
            const growthData = chartData.user_growth || [];
            
            const labels = growthData.map(item => item.date || '');
            const data = growthData.map(item => item.new_users || 0);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Новые пользователи',
                        data: data,
                        backgroundColor: '#28a745',
                        borderColor: '#20c997',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#fff'
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#fff'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#fff'
                            }
                        }
                    }
                }
            });
        }
        
        // График аномалий
        function initAnomaliesChart() {
            const ctx = document.getElementById('anomaliesChart').getContext('2d');
            const anomaliesData = mlAnomalies.risk_distribution || [];
            
            const labels = anomaliesData.map(item => item.risk_level || '');
            const data = anomaliesData.map(item => item.count || 0);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#28a745',
                            '#ffc107',
                            '#fd7e14',
                            '#dc3545'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#fff'
                            }
                        }
                    }
                }
            });
        }
        
        // Карта геолокации
        function initGeolocationMap() {
            const mapContainer = document.getElementById('geolocationMap');
            if (!mapContainer) return;
            
            const map = L.map('geolocationMap').setView([53.9023, 27.5619], 6); // Центр Беларуси
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Добавляем маркеры активности
            const mapData = geolocationData.map_data || [];
            mapData.forEach(point => {
                if (point.latitude && point.longitude) {
                    L.marker([point.latitude, point.longitude])
                        .bindPopup(`Активность: ${point.count || 0} пользователей`)
                        .addTo(map);
                }
            });
        }
        
        // Обновление карты
        function refreshMap() {
            const mapContainer = document.getElementById('geolocationMap');
            if (mapContainer) {
                mapContainer.innerHTML = '';
                initGeolocationMap();
            }
        }
        
        // Обновление периода активности
        document.getElementById('activityPeriod')?.addEventListener('change', function() {
            // Здесь можно добавить AJAX запрос для обновления данных
            console.log('Период изменен:', this.value);
        });
        
        // Автообновление данных каждые 30 секунд
        setInterval(function() {
            // Здесь можно добавить AJAX запрос для обновления данных
            console.log('Обновление данных...');
        }, 30000);
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
                
                if (currentPath.startsWith('/admin/analytics')) {
                    category = 'Аналитика';
                    subLinks = [
                        { href: '/admin/analytics', text: 'Основная аналитика', icon: 'fas fa-chart-bar' },
                        { href: '/admin/enhanced-analytics', text: 'Расширенная аналитика', icon: 'fas fa-brain' },
                        { href: '/admin/analytics/reports', text: 'Отчеты', icon: 'fas fa-file-alt' }
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
</body>
</html>
