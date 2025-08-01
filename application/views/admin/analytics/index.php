<?php
// Проверяем существование переменных
if (!isset($analytics)) {
    $analytics = [
        'users' => ['total' => 0, 'active' => 0, 'blocked' => 0],
        'logins_24h' => ['total' => 0, 'successful' => 0, 'failed' => 0],
        'sessions' => ['active' => 0, 'created_24h' => 0, 'avg_duration' => 0],
        'security' => ['threat_level' => 'low', 'suspicious_activities_24h' => 0, 'blocked_ips' => 0]
    ];
}

if (!isset($monitoring_data)) {
    $monitoring_data = [
        'status' => 'ok',
        'checks' => [
            'performance' => ['status' => 'ok', 'issues' => []],
            'security' => ['status' => 'ok', 'issues' => []],
            'storage' => ['status' => 'ok', 'issues' => []],
            'database' => ['status' => 'ok', 'issues' => []]
        ]
    ];
}

if (!isset($securityNotifications)) {
    $securityNotifications = [];
}

// Получаем реальные данные для графиков
try {
    require_once ENGINE_DIR . 'main/db.php';
    
    // Данные активности пользователей за последние 7 дней
    $userActivityData = Database::fetchAll("
        SELECT DATE(activity_time) as date, COUNT(*) as count
        FROM user_activity 
        WHERE activity_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(activity_time)
        ORDER BY date
    ");
    
    // Данные входов за последние 7 дней
    $loginData = Database::fetchAll("
        SELECT DATE(activity_time) as date, 
               SUM(CASE WHEN action_type = 'login_success' THEN 1 ELSE 0 END) as successful,
               SUM(CASE WHEN action_type = 'login_failed' THEN 1 ELSE 0 END) as failed
        FROM user_activity 
        WHERE activity_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        AND action_type IN ('login_success', 'login_failed')
        GROUP BY DATE(activity_time)
        ORDER BY date
    ");
    
    // Данные сессий
    $sessionData = Database::fetchAll("
        SELECT DATE(created_at) as date, COUNT(*) as count
        FROM user_sessions 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date
    ");
    
} catch (Exception $e) {
    $userActivityData = [];
    $loginData = [];
    $sessionData = [];
}
?>

<div class="analytics-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-chart-line"></i> Аналитика и мониторинг</h1>
            <p>Комплексная аналитика системы и мониторинг производительности</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-blue" onclick="refreshAnalytics()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
            <button class="btn btn-purple" onclick="exportData()">
                <i class="fas fa-download"></i> Экспорт
            </button>
            <button class="btn btn-green" onclick="showRealTimeData()">
                <i class="fas fa-broadcast-tower"></i> Реал-тайм
            </button>
        </div>
    </div>

    <!-- Основные метрики -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value"><?= $analytics['users']['total'] ?></div>
                <div class="metric-label">Всего пользователей</div>
                <div class="metric-change positive">+<?= $analytics['users']['active'] ?> активных</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value"><?= $analytics['logins_24h']['total'] ?></div>
                <div class="metric-label">Входов за 24ч</div>
                <div class="metric-change <?= $analytics['logins_24h']['successful'] > $analytics['logins_24h']['failed'] ? 'positive' : 'negative' ?>">
                    <?= $analytics['logins_24h']['successful'] ?> успешных
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value"><?= $analytics['sessions']['active'] ?></div>
                <div class="metric-label">Активных сессий</div>
                <div class="metric-change positive">+<?= $analytics['sessions']['created_24h'] ?> новых</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value"><?= $analytics['security']['suspicious_activities_24h'] ?></div>
                <div class="metric-label">Подозрительных действий</div>
                <div class="metric-change <?= $analytics['security']['threat_level'] === 'low' ? 'positive' : 'negative' ?>">
                    Уровень: <?= strtoupper($analytics['security']['threat_level']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Статус системы -->
    <div class="system-status">
        <h3><i class="fas fa-server"></i> Статус системы</h3>
        <div class="status-grid">
            <?php foreach ($monitoring_data['checks'] as $check => $data): ?>
            <div class="status-card status-<?= $data['status'] ?>">
                <div class="status-icon">
                    <i class="fas fa-<?= $check === 'performance' ? 'tachometer-alt' : ($check === 'security' ? 'shield-alt' : ($check === 'storage' ? 'hdd' : 'database')) ?>"></i>
                </div>
                <div class="status-content">
                    <h4><?= ucfirst($check) ?></h4>
                    <div class="status-badge status-<?= $data['status'] ?>">
                        <?= strtoupper($data['status']) ?>
                    </div>
                    <?php if (!empty($data['issues'])): ?>
                    <div class="status-issues">
                        <small><?= count($data['issues']) ?> проблем</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Интерактивные графики -->
    <div class="charts-section">
        <h3><i class="fas fa-chart-area"></i> Графики активности</h3>
        <div class="charts-controls">
            <select id="chartPeriod" onchange="updateCharts()">
                <option value="7d">Последние 7 дней</option>
                <option value="30d">Последние 30 дней</option>
                <option value="90d">Последние 90 дней</option>
            </select>
            <button class="btn btn-sm btn-blue" onclick="updateCharts()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
        </div>
        
        <div class="charts-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <h4>Активность пользователей</h4>
                    <div class="chart-legend">
                        <span class="legend-item"><span class="legend-color" style="background: #4CAF50;"></span> Активность</span>
                    </div>
                </div>
                <div class="chart-content">
                    <canvas id="userActivityChart" width="400" height="200"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h4>Входы в систему</h4>
                    <div class="chart-legend">
                        <span class="legend-item"><span class="legend-color" style="background: #2196F3;"></span> Успешные</span>
                        <span class="legend-item"><span class="legend-color" style="background: #f44336;"></span> Неудачные</span>
                    </div>
                </div>
                <div class="chart-content">
                    <canvas id="loginChart" width="400" height="200"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h4>Создание сессий</h4>
                    <div class="chart-legend">
                        <span class="legend-item"><span class="legend-color" style="background: #FF9800;"></span> Новые сессии</span>
                    </div>
                </div>
                <div class="chart-content">
                    <canvas id="sessionChart" width="400" height="200"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h4>Безопасность</h4>
                    <div class="chart-legend">
                        <span class="legend-item"><span class="legend-color" style="background: #9C27B0;"></span> Подозрительные действия</span>
                    </div>
                </div>
                <div class="chart-content">
                    <canvas id="securityChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Реал-тайм активность -->
    <div class="realtime-section" id="realtimeSection" style="display: none;">
        <h3><i class="fas fa-broadcast-tower"></i> Реал-тайм активность</h3>
        <div class="realtime-grid">
            <div class="realtime-card">
                <div class="realtime-header">
                    <h4>Активные пользователи</h4>
                    <div class="realtime-indicator"></div>
                </div>
                <div class="realtime-value" id="realtimeUsers">0</div>
            </div>
            
            <div class="realtime-card">
                <div class="realtime-header">
                    <h4>Последние действия</h4>
                    <div class="realtime-indicator"></div>
                </div>
                <div class="realtime-list" id="realtimeActions">
                    <div class="realtime-item">Загрузка...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Уведомления -->
    <?php if (!empty($securityNotifications)): ?>
    <div class="notifications-section">
        <h3><i class="fas fa-bell"></i> Уведомления безопасности</h3>
        <div class="notifications-list">
            <?php foreach ($securityNotifications as $notification): ?>
            <div class="notification-item notification-<?= $notification['type'] ?? 'info' ?>">
                <div class="notification-icon">
                    <i class="fas fa-<?= $notification['type'] === 'warning' ? 'exclamation-triangle' : ($notification['type'] === 'error' ? 'times-circle' : 'info-circle') ?>"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title"><?= $notification['title'] ?? 'Уведомление' ?></div>
                    <div class="notification-message"><?= $notification['message'] ?? '' ?></div>
                    <div class="notification-time"><?= $notification['time'] ?? '' ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Подключение Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Данные для графиков
const chartData = {
    userActivity: <?= json_encode($userActivityData) ?>,
    loginData: <?= json_encode($loginData) ?>,
    sessionData: <?= json_encode($sessionData) ?>
};

// Инициализация графиков
let userActivityChart, loginChart, sessionChart, securityChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadRealTimeData();
});

function initializeCharts() {
    // График активности пользователей
    const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
    userActivityChart = new Chart(userActivityCtx, {
        type: 'line',
        data: {
            labels: chartData.userActivity.map(item => item.date),
            datasets: [{
                label: 'Активность',
                data: chartData.userActivity.map(item => item.count),
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4,
                fill: true
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

    // График входов
    const loginCtx = document.getElementById('loginChart').getContext('2d');
    loginChart = new Chart(loginCtx, {
        type: 'bar',
        data: {
            labels: chartData.loginData.map(item => item.date),
            datasets: [{
                label: 'Успешные',
                data: chartData.loginData.map(item => item.successful),
                backgroundColor: '#2196F3'
            }, {
                label: 'Неудачные',
                data: chartData.loginData.map(item => item.failed),
                backgroundColor: '#f44336'
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

    // График сессий
    const sessionCtx = document.getElementById('sessionChart').getContext('2d');
    sessionChart = new Chart(sessionCtx, {
        type: 'line',
        data: {
            labels: chartData.sessionData.map(item => item.date),
            datasets: [{
                label: 'Новые сессии',
                data: chartData.sessionData.map(item => item.count),
                borderColor: '#FF9800',
                backgroundColor: 'rgba(255, 152, 0, 0.1)',
                tension: 0.4,
                fill: true
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

    // График безопасности (заглушка)
    const securityCtx = document.getElementById('securityChart').getContext('2d');
    securityChart = new Chart(securityCtx, {
        type: 'doughnut',
        data: {
            labels: ['Нормальная активность', 'Подозрительная активность'],
            datasets: [{
                data: [85, 15],
                backgroundColor: ['#4CAF50', '#f44336']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Обновление графиков
function updateCharts() {
    const period = document.getElementById('chartPeriod').value;
    
    // Показываем индикатор загрузки
    showLoading();
    
    // Загружаем новые данные
    fetch(`/admin/analytics/api/chart-data?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateChartData(data.data);
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Ошибка загрузки данных:', error);
            hideLoading();
        });
}

// Обновление данных графиков
function updateChartData(data) {
    if (data.userActivity) {
        userActivityChart.data.labels = data.userActivity.map(item => item.date);
        userActivityChart.data.datasets[0].data = data.userActivity.map(item => item.count);
        userActivityChart.update();
    }
    
    if (data.loginData) {
        loginChart.data.labels = data.loginData.map(item => item.date);
        loginChart.data.datasets[0].data = data.loginData.map(item => item.successful);
        loginChart.data.datasets[1].data = data.loginData.map(item => item.failed);
        loginChart.update();
    }
    
    if (data.sessionData) {
        sessionChart.data.labels = data.sessionData.map(item => item.date);
        sessionChart.data.datasets[0].data = data.sessionData.map(item => item.count);
        sessionChart.update();
    }
}

// Реал-тайм данные
function loadRealTimeData() {
    fetch('/admin/analytics/api/realtime')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateRealTimeData(data.data);
            }
        })
        .catch(error => {
            console.error('Ошибка загрузки реал-тайм данных:', error);
        });
}

function updateRealTimeData(data) {
    document.getElementById('realtimeUsers').textContent = data.activeUsers || 0;
    
    const actionsList = document.getElementById('realtimeActions');
    if (data.recentActions) {
        actionsList.innerHTML = data.recentActions.map(action => 
            `<div class="realtime-item">${action}</div>`
        ).join('');
    }
}

// Показать/скрыть реал-тайм данные
function showRealTimeData() {
    const section = document.getElementById('realtimeSection');
    if (section.style.display === 'none') {
        section.style.display = 'block';
        loadRealTimeData();
        // Обновляем каждые 30 секунд
        setInterval(loadRealTimeData, 30000);
    } else {
        section.style.display = 'none';
    }
}

// Обновление аналитики
function refreshAnalytics() {
    location.reload();
}

// Экспорт данных
function exportData() {
    const data = {
        analytics: <?= json_encode($analytics) ?>,
        monitoring: <?= json_encode($monitoring_data) ?>,
        charts: chartData,
        timestamp: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'analytics_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.json';
    a.click();
    URL.revokeObjectURL(url);
}

// Утилиты
function showLoading() {
    // Показываем индикатор загрузки
    const loading = document.createElement('div');
    loading.id = 'loading';
    loading.innerHTML = '<div class="loading-spinner"></div>';
    loading.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;';
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.getElementById('loading');
    if (loading) {
        loading.remove();
    }
}
</script>

<style>
/* Дополнительные стили для аналитики */
.charts-controls {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    align-items: center;
}

.charts-controls select {
    padding: 8px 12px;
    border: 1px solid #333;
    background: #1a1a1a;
    color: white;
    border-radius: 5px;
}

.realtime-section {
    margin-top: 30px;
    padding: 20px;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    border: 1px solid #333;
}

.realtime-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 20px;
}

.realtime-card {
    background: rgba(0, 0, 0, 0.2);
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #333;
}

.realtime-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.realtime-indicator {
    width: 8px;
    height: 8px;
    background: #4CAF50;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.realtime-value {
    font-size: 2em;
    font-weight: bold;
    color: #4CAF50;
}

.realtime-list {
    max-height: 200px;
    overflow-y: auto;
}

.realtime-item {
    padding: 5px 0;
    border-bottom: 1px solid #333;
    font-size: 0.9em;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #333;
    border-top: 4px solid #4CAF50;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.chart-legend {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8em;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}
</style> 