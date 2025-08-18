<?php
/**
 * Представление для анализа поведения пользователей
 * Расширенная аналитика - поведенческий анализ
 */
?>

<div class="behavior-container">
    <!-- Заголовок -->
    <div class="behavior-header">
        <div class="header-content">
            <h1><i class="fas fa-brain"></i> Поведенческий анализ пользователей</h1>
            <div class="header-actions">
                <button class="btn btn-blue" onclick="refreshBehaviorData()">
                    <i class="fas fa-sync-alt"></i> Обновить
                </button>
                <button class="btn btn-green" onclick="exportBehaviorData()">
                    <i class="fas fa-download"></i> Экспорт
                </button>
            </div>
        </div>
        
        <!-- Навигация -->
        <nav class="behavior-nav">
            <a href="/admin/enhanced-analytics" class="nav-item">
                <i class="fas fa-tachometer-alt"></i> Обзор
            </a>
            <a href="/admin/enhanced-analytics/geolocation" class="nav-item">
                <i class="fas fa-map-marker-alt"></i> Геолокация
            </a>
            <a href="/admin/enhanced-analytics/behavior" class="nav-item active">
                <i class="fas fa-user-clock"></i> Поведение
            </a>
            <a href="/admin/enhanced-analytics/ml-anomalies" class="nav-item">
                <i class="fas fa-brain"></i> ML Аномалии
            </a>
            <a href="/admin/enhanced-analytics/notifications" class="nav-item">
                <i class="fas fa-bell"></i> Уведомления
            </a>
            <a href="/admin/enhanced-analytics/reports" class="nav-item">
                <i class="fas fa-file-alt"></i> Отчеты
            </a>
        </nav>
    </div>

    <!-- Фильтры и настройки -->
    <div class="filters-section">
        <div class="filter-card">
            <div class="filter-row">
                <div class="filter-item">
                    <label>Период анализа</label>
                    <select id="behaviorPeriod" class="form-select">
                        <option value="7">Последние 7 дней</option>
                        <option value="30" selected>Последние 30 дней</option>
                        <option value="90">Последние 90 дней</option>
                        <option value="365">Последний год</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Тип поведения</label>
                    <select id="behaviorType" class="form-select">
                        <option value="all">Все типы</option>
                        <option value="login_patterns">Паттерны входа</option>
                        <option value="navigation_patterns">Навигационные паттерны</option>
                        <option value="feature_usage">Использование функций</option>
                        <option value="time_patterns">Временные паттерны</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Группа пользователей</label>
                    <select id="userGroup" class="form-select">
                        <option value="all">Все группы</option>
                        <option value="students">Учащиеся</option>
                        <option value="teachers">Преподаватели</option>
                        <option value="admins">Администраторы</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>&nbsp;</label>
                    <div class="filter-buttons">
                        <button class="btn btn-primary" onclick="loadBehaviorData()">
                            <i class="fas fa-sync-alt"></i> Обновить
                        </button>
                        <button class="btn btn-secondary" onclick="exportBehaviorData()">
                            <i class="fas fa-download"></i> Экспорт
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Основные метрики -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number" id="activeUsers"><?= $behaviorData['session_metrics']['avg_session_duration'] ?? 0 ?></div>
                <div class="metric-label">Средняя продолжительность сессии (мин)</div>
                <div class="metric-trend">
                    <span class="trend-up">+<?= $behaviorData['engagement_metrics']['form_interactions'] ?? 0 ?> взаимодействий</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-mouse-pointer"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number" id="avgClicks"><?= $behaviorData['session_metrics']['avg_clicks'] ?? 0 ?></div>
                <div class="metric-label">Среднее количество кликов</div>
                <div class="metric-trend">
                    <span class="trend-up">+<?= $behaviorData['engagement_metrics']['file_downloads'] ?? 0 ?> загрузок</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-scroll"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number" id="scrollDepth"><?= $behaviorData['session_metrics']['avg_scroll_depth'] ?? 0 ?>%</div>
                <div class="metric-label">Средняя глубина прокрутки</div>
                <div class="metric-trend">
                    <span class="trend-info">Анализ вовлеченности</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number" id="bounceRate"><?= $behaviorData['conversion_funnel']['bounce_rate'] ?? 0 ?>%</div>
                <div class="metric-label">Процент отказов</div>
                <div class="metric-trend">
                    <span class="trend-down">Улучшение удержания</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Графики поведения -->
    <div class="charts-section">
        <div class="chart-row">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Паттерны навигации</h3>
                    <div class="chart-controls">
                        <select id="navigationPeriod" class="form-select">
                            <option value="7">7 дней</option>
                            <option value="30" selected>30 дней</option>
                            <option value="90">90 дней</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="navigationChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Временные паттерны</h3>
                    <div class="chart-controls">
                        <button class="btn btn-sm" onclick="refreshTimePatterns()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="timePatternsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-row">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Воронка конверсии</h3>
                </div>
                <div class="chart-container">
                    <canvas id="conversionFunnelChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Популярные страницы</h3>
                </div>
                <div class="chart-container">
                    <canvas id="popularPagesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Анализ путей пользователей -->
    <div class="user-journeys-section">
        <div class="section-header">
            <h3><i class="fas fa-route"></i> Анализ путей пользователей</h3>
        </div>
        <div class="journeys-list">
            <?php if (!empty($behaviorData['user_journeys'])): ?>
                <?php foreach (array_slice($behaviorData['user_journeys'], 0, 5) as $journey): ?>
                    <div class="journey-item">
                        <div class="journey-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="journey-content">
                            <div class="journey-user">Пользователь #<?= $journey['user_id'] ?? 'Неизвестный' ?></div>
                            <div class="journey-path">
                                <?php 
                                $path = $journey['path_sequence'] ?? '';
                                $pages = explode(' → ', $path);
                                foreach ($pages as $index => $page): ?>
                                    <span class="path-step"><?= htmlspecialchars($page) ?></span>
                                    <?php if ($index < count($pages) - 1): ?>
                                        <i class="fas fa-arrow-right"></i>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="journey-stats">
                                <span class="duration"><?= $journey['session_duration'] ?? 0 ?> мин</span>
                                <span class="pages"><?= $journey['total_pages'] ?? 0 ?> страниц</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    <p>Нет данных о путях пользователей</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Тепловая карта активности -->
    <div class="heatmap-section">
        <div class="section-header">
            <h3><i class="fas fa-fire"></i> Тепловая карта активности</h3>
        </div>
        <div class="heatmap-container">
            <div class="heatmap-grid">
                <?php
                // Создаем тепловую карту активности по часам и дням
                $hours = range(0, 23);
                $days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
                
                foreach ($days as $dayIndex => $day): ?>
                    <div class="heatmap-day">
                        <div class="day-label"><?= $day ?></div>
                        <?php foreach ($hours as $hour): ?>
                            <?php 
                            // Симулируем данные активности (в реальности брать из БД)
                            $activity = rand(0, 100);
                            $intensity = $activity > 80 ? 'high' : ($activity > 50 ? 'medium' : ($activity > 20 ? 'low' : 'none'));
                            ?>
                            <div class="heatmap-cell <?= $intensity ?>" 
                                 title="<?= $day ?> <?= $hour ?>:00 - <?= $activity ?> действий">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="heatmap-legend">
                <div class="legend-item">
                    <div class="legend-color high"></div>
                    <span>Высокая активность</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color medium"></div>
                    <span>Средняя активность</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color low"></div>
                    <span>Низкая активность</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color none"></div>
                    <span>Нет активности</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Данные для графиков
    const behaviorData = <?= json_encode($behaviorData ?? []) ?>;
    
    // Инициализация графиков
    document.addEventListener('DOMContentLoaded', function() {
        initNavigationChart();
        initTimePatternsChart();
        initConversionFunnelChart();
        initPopularPagesChart();
    });
    
    // График паттернов навигации
    function initNavigationChart() {
        const ctx = document.getElementById('navigationChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
                datasets: [{
                    label: 'Средняя продолжительность сессии',
                    data: [45, 52, 48, 61, 55, 38, 42],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Количество сессий',
                    data: [120, 145, 132, 168, 156, 98, 112],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#fff' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#fff' }
                    },
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#fff' }
                    }
                }
            }
        });
    }
    
    // График временных паттернов
    function initTimePatternsChart() {
        const ctx = document.getElementById('timePatternsChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['00-06', '06-12', '12-18', '18-24'],
                datasets: [{
                    label: 'Активность по часам',
                    data: [15, 45, 85, 65],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#fff' }
                    },
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#fff' }
                    }
                }
            }
        });
    }
    
    // График воронки конверсии
    function initConversionFunnelChart() {
        const ctx = document.getElementById('conversionFunnelChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Посетители', 'Просмотры', 'Взаимодействия', 'Конверсии'],
                datasets: [{
                    data: [1000, 750, 450, 120],
                    backgroundColor: [
                        '#667eea',
                        '#28a745',
                        '#ffc107',
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
                        labels: { color: '#fff' }
                    }
                }
            }
        });
    }
    
    // График популярных страниц
    function initPopularPagesChart() {
        const ctx = document.getElementById('popularPagesChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: ['Главная', 'Новости', 'Профиль', 'Файлы', 'Поиск'],
                datasets: [{
                    label: 'Просмотры',
                    data: [450, 320, 280, 190, 150],
                    backgroundColor: 'rgba(102, 126, 234, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#fff' }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#fff' }
                    }
                }
            }
        });
    }
    
    // Обновление данных поведения
    function refreshBehaviorData() {
        console.log('Обновление данных поведения...');
        location.reload();
    }
    
    // Экспорт данных
    function exportBehaviorData() {
        console.log('Экспорт данных поведения...');
        alert('Функция экспорта будет добавлена позже');
    }
    
    // Загрузка данных поведения
    function loadBehaviorData() {
        const period = document.getElementById('behaviorPeriod').value;
        const type = document.getElementById('behaviorType').value;
        const group = document.getElementById('userGroup').value;
        
        console.log('Загрузка данных:', { period, type, group });
        // Здесь можно добавить AJAX запрос для обновления данных
    }
    
    // Обновление временных паттернов
    function refreshTimePatterns() {
        console.log('Обновление временных паттернов...');
        // Здесь можно добавить AJAX запрос для обновления графика
    }
    
    // Обновление периода навигации
    document.getElementById('navigationPeriod')?.addEventListener('change', function() {
        console.log('Период навигации изменен:', this.value);
        // Здесь можно добавить AJAX запрос для обновления графика
    });
</script>

<style>
    .behavior-container {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        color: white;
    }
    
    .behavior-header {
        margin-bottom: 30px;
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .header-content h1 {
        margin: 0;
        font-size: 2.5em;
    }
    
    .behavior-nav {
        display: flex;
        gap: 10px;
        background: rgba(255,255,255,0.1);
        padding: 10px;
        border-radius: 10px;
    }
    
    .nav-item {
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 5px;
        transition: background 0.3s;
    }
    
    .nav-item:hover, .nav-item.active {
        background: rgba(255,255,255,0.2);
    }
    
    .filters-section {
        margin-bottom: 30px;
    }
    
    .filter-card {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }
    
    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }
    
    .filter-item label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    
    .form-select {
        width: 100%;
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 8px;
        border-radius: 5px;
    }
    
    .form-select option {
        background: #333;
        color: white;
    }
    
    .filter-buttons {
        display: flex;
        gap: 10px;
    }
    
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .metric-card {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .metric-icon {
        font-size: 2em;
        opacity: 0.8;
    }
    
    .metric-number {
        font-size: 2em;
        font-weight: bold;
    }
    
    .metric-label {
        font-size: 0.9em;
        opacity: 0.8;
    }
    
    .metric-trend {
        font-size: 0.8em;
        margin-top: 5px;
    }
    
    .charts-section {
        margin-bottom: 30px;
    }
    
    .chart-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .chart-card {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .chart-container {
        height: 300px;
        position: relative;
    }
    
    .user-journeys-section {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    
    .section-header {
        margin-bottom: 20px;
    }
    
    .journeys-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .journey-item {
        background: rgba(255,255,255,0.1);
        padding: 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .journey-icon {
        font-size: 1.2em;
        color: #667eea;
    }
    
    .journey-user {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .journey-path {
        font-size: 0.9em;
        margin-bottom: 5px;
    }
    
    .path-step {
        background: rgba(102, 126, 234, 0.3);
        padding: 2px 8px;
        border-radius: 12px;
        margin-right: 5px;
    }
    
    .journey-stats {
        font-size: 0.8em;
        opacity: 0.8;
    }
    
    .journey-stats span {
        margin-right: 15px;
    }
    
    .heatmap-section {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
    }
    
    .heatmap-container {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }
    
    .heatmap-grid {
        display: flex;
        gap: 5px;
    }
    
    .heatmap-day {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .day-label {
        text-align: center;
        font-size: 0.8em;
        margin-bottom: 5px;
    }
    
    .heatmap-cell {
        width: 20px;
        height: 20px;
        border-radius: 2px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .heatmap-cell:hover {
        transform: scale(1.2);
    }
    
    .heatmap-cell.none { background: rgba(255,255,255,0.1); }
    .heatmap-cell.low { background: rgba(40, 167, 69, 0.3); }
    .heatmap-cell.medium { background: rgba(255, 193, 7, 0.5); }
    .heatmap-cell.high { background: rgba(220, 53, 69, 0.7); }
    
    .heatmap-legend {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 2px;
    }
    
    .legend-color.none { background: rgba(255,255,255,0.1); }
    .legend-color.low { background: rgba(40, 167, 69, 0.3); }
    .legend-color.medium { background: rgba(255, 193, 7, 0.5); }
    .legend-color.high { background: rgba(220, 53, 69, 0.7); }
    
    .no-data {
        text-align: center;
        padding: 40px;
        opacity: 0.7;
    }
    
    .no-data i {
        font-size: 3em;
        margin-bottom: 15px;
    }
    
    .btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .btn:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .btn-primary { background: #007bff; }
    .btn-secondary { background: #6c757d; }
    .btn-blue { background: #007bff; }
    .btn-green { background: #28a745; }
    
    .trend-up { color: #28a745; }
    .trend-down { color: #dc3545; }
    .trend-info { color: #17a2b8; }
</style> 