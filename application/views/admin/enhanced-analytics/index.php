<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расширенная аналитика - Админ панель</title>
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <button class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="header-logo">
                        <div class="logo-text">CyberAdmin</div>
                        <div class="logo-subtitle">Enhanced Analytics</div>
                    </div>
                </div>
                
                <div class="header-center">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" class="header-search" placeholder="Поиск по аналитике...">
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="header-item">
                        <button class="header-btn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>
                    <div class="header-item">
                        <button class="header-btn">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                    <div class="header-item">
                        <button class="header-btn">
                            <div class="user-avatar">A</div>
                            <span class="user-name">Admin</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h1>CyberAdmin</h1>
                <p>Enhanced Analytics</p>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="#" class="nav-link active" data-view="overview">
                        <i class="nav-icon fas fa-chart-line"></i>
                        Обзор
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" data-view="users">
                        <i class="nav-icon fas fa-users"></i>
                        Пользователи
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" data-view="security">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        Безопасность
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" data-view="behavior">
                        <i class="nav-icon fas fa-brain"></i>
                        Поведение
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" data-view="reports">
                        <i class="nav-icon fas fa-file-alt"></i>
                        Отчеты
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" data-view="settings">
                        <i class="nav-icon fas fa-cog"></i>
                        Настройки
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="enhanced-analytics-container">
                <!-- Header -->
                <div class="analytics-header">
                    <div class="header-content">
                        <h1>
                            <i class="fas fa-chart-line"></i>
                            Расширенная аналитика
                        </h1>
                        <div class="header-actions">
                            <button class="btn btn-blue" onclick="analytics.exportData('csv')">
                                <i class="fas fa-download"></i>
                                Экспорт
                            </button>
                            <button class="btn btn-purple btn-settings">
                                <i class="fas fa-cog"></i>
                                Настройки
                            </button>
                        </div>
                    </div>
                    
                    <nav class="analytics-nav">
                        <a href="#" class="nav-item active" data-view="overview">
                            <i class="fas fa-chart-line"></i>
                            Обзор
                        </a>
                        <a href="#" class="nav-item" data-view="users">
                            <i class="fas fa-users"></i>
                            Пользователи
                        </a>
                        <a href="#" class="nav-item" data-view="security">
                            <i class="fas fa-shield-alt"></i>
                            Безопасность
                        </a>
                        <a href="#" class="nav-item" data-view="behavior">
                            <i class="fas fa-brain"></i>
                            Поведение
                        </a>
                        <a href="#" class="nav-item" data-view="reports">
                            <i class="fas fa-file-alt"></i>
                            Отчеты
                        </a>
                    </nav>
                </div>

                <!-- Metrics Grid -->
                <div class="metrics-grid">
                    <div class="metric-card users" data-metric="users">
                        <i class="metric-icon fas fa-users"></i>
                        <div class="metric-content">
                            <div class="metric-number">1,247</div>
                            <div class="metric-label">Активные пользователи</div>
                            <div class="metric-details">
                                <span class="active"><i class="fas fa-circle"></i> 892 онлайн</span>
                                <span class="new"><i class="fas fa-plus"></i> 45 новых</span>
                            </div>
                        </div>
                        <div class="metric-trend up">
                            <i class="fas fa-arrow-up"></i>
                            +12.5%
                        </div>
                    </div>

                    <div class="metric-card security" data-metric="security">
                        <i class="metric-icon threat-medium fas fa-shield-alt"></i>
                        <div class="metric-content">
                            <div class="metric-number">23</div>
                            <div class="metric-label">Угрозы безопасности</div>
                            <div class="metric-details">
                                <span class="active"><i class="fas fa-check"></i> 18 заблокировано</span>
                                <span class="blocked"><i class="fas fa-ban"></i> 5 активных</span>
                            </div>
                        </div>
                        <div class="metric-trend down">
                            <i class="fas fa-arrow-down"></i>
                            -8.2%
                        </div>
                    </div>

                    <div class="metric-card behavior" data-metric="behavior">
                        <i class="metric-icon fas fa-brain"></i>
                        <div class="metric-content">
                            <div class="metric-number">89.4%</div>
                            <div class="metric-label">Нормальное поведение</div>
                            <div class="metric-details">
                                <span class="active"><i class="fas fa-check"></i> 1,112 сессий</span>
                                <span class="blocked"><i class="fas fa-exclamation"></i> 12 аномалий</span>
                            </div>
                        </div>
                        <div class="metric-trend up">
                            <i class="fas fa-arrow-up"></i>
                            +2.1%
                        </div>
                    </div>

                    <div class="metric-card" data-metric="performance">
                        <i class="metric-icon fas fa-tachometer-alt"></i>
                        <div class="metric-content">
                            <div class="metric-number">99.8%</div>
                            <div class="metric-label">Время отклика</div>
                            <div class="metric-details">
                                <span class="active"><i class="fas fa-check"></i> 245ms среднее</span>
                                <span class="new"><i class="fas fa-clock"></i> 99.9% uptime</span>
                            </div>
                        </div>
                        <div class="metric-trend up">
                            <i class="fas fa-arrow-up"></i>
                            +0.3%
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="chart-section">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-line"></i>
                            Активность пользователей
                        </h3>
                        <div class="chart-controls">
                            <button class="btn btn-blue">24ч</button>
                            <button class="btn">7д</button>
                            <button class="btn">30д</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="user-activity-chart"></canvas>
                    </div>
                </div>

                <div class="chart-section">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-shield-alt"></i>
                            Угрозы безопасности
                        </h3>
                        <div class="chart-controls">
                            <button class="btn btn-green">Все</button>
                            <button class="btn">Блокированные</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="security-threats-chart"></canvas>
                    </div>
                </div>

                <div class="chart-section">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-brain"></i>
                            Анализ поведения
                        </h3>
                        <div class="chart-controls">
                            <button class="btn btn-purple">Сессии</button>
                            <button class="btn">Аномалии</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="behavior-analysis-chart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/background-animations.js"></script>
    <script src="/assets/js/enhanced-analytics.js"></script>
</body>
</html>
