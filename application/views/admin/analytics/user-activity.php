<?php
// Проверяем существование переменных
if (!isset($user_activity)) $user_activity = [];
if (!isset($top_users)) $top_users = [];
if (!isset($popular_actions)) $popular_actions = [];
if (!isset($hourly_activity)) $hourly_activity = [];
if (!isset($weekly_activity)) $weekly_activity = [];
if (!isset($period)) $period = '7d';
if (!isset($limit)) $limit = 50;
?>

<div class="user-activity-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-users"></i> Активность пользователей</h1>
            <p>Анализ поведения и активности пользователей системы</p>
        </div>
        <div class="header-actions">
            <select id="activityPeriod" onchange="updateActivityData()">
                <option value="7d" <?= $period === '7d' ? 'selected' : '' ?>>Последние 7 дней</option>
                <option value="30d" <?= $period === '30d' ? 'selected' : '' ?>>Последние 30 дней</option>
                <option value="90d" <?= $period === '90d' ? 'selected' : '' ?>>Последние 90 дней</option>
            </select>
            <select id="activityLimit" onchange="updateActivityData()">
                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>Топ 20</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>Топ 50</option>
                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>Топ 100</option>
            </select>
            <button class="btn btn-blue" onclick="refreshActivityData()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
            <button class="btn btn-green" onclick="exportActivityData()">
                <i class="fas fa-download"></i> Экспорт
            </button>
        </div>
    </div>

    <!-- Статистика активности -->
    <div class="activity-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= count($user_activity) ?></div>
                <div class="stat-label">Записей активности</div>
                <div class="stat-change positive">
                    За период <?= $period === '7d' ? '7 дней' : ($period === '30d' ? '30 дней' : '90 дней') ?>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= count($top_users) ?></div>
                <div class="stat-label">Активных пользователей</div>
                <div class="stat-change positive">
                    Показано топ <?= $limit ?> пользователей
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= count($popular_actions) ?></div>
                <div class="stat-label">Типов действий</div>
                <div class="stat-change positive">
                    Популярные действия пользователей
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= array_sum(array_column($hourly_activity, 'count')) ?></div>
                <div class="stat-label">Действий по часам</div>
                <div class="stat-change positive">
                    Анализ временных паттернов
                </div>
            </div>
        </div>
    </div>

    <!-- Графики активности -->
    <div class="activity-charts">
        <div class="chart-section">
            <h3><i class="fas fa-chart-line"></i> Активность по дням</h3>
            <div class="chart-container">
                <canvas id="activityChart" width="800" height="300"></canvas>
            </div>
        </div>

        <div class="chart-section">
            <h3><i class="fas fa-chart-bar"></i> Активность по часам</h3>
            <div class="chart-container">
                <canvas id="hourlyChart" width="800" height="300"></canvas>
            </div>
        </div>

        <div class="chart-section">
            <h3><i class="fas fa-chart-pie"></i> Активность по дням недели</h3>
            <div class="chart-container">
                <canvas id="weeklyChart" width="800" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Топ пользователей -->
    <div class="top-users">
        <h3><i class="fas fa-trophy"></i> Топ пользователей по активности</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Логин</th>
                        <th>Количество действий</th>
                        <th>Активных дней</th>
                        <th>Последняя активность</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($top_users)): ?>
                        <?php foreach ($top_users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['user_fio'] ?? 'Без имени') ?></td>
                            <td><?= htmlspecialchars($user['user_login']) ?></td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= min(100, ($user['activity_count'] / max(array_column($top_users, 'activity_count'))) * 100) ?>%"></div>
                                    <span class="progress-text"><?= $user['activity_count'] ?></span>
                                </div>
                            </td>
                            <td><?= $user['active_days'] ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($user['last_activity'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-blue" onclick="viewUserDetails(<?= $user['user_id'] ?>)">
                                    <i class="fas fa-eye"></i> Детали
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="no-data">Данные отсутствуют</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Популярные действия -->
    <div class="popular-actions">
        <h3><i class="fas fa-list"></i> Популярные действия</h3>
        <div class="actions-grid">
            <?php if (!empty($popular_actions)): ?>
                <?php foreach ($popular_actions as $action): ?>
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-<?= getActionIcon($action['action_type']) ?>"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title"><?= getActionTitle($action['action_type']) ?></div>
                        <div class="action-stats">
                            <span class="action-count"><?= $action['count'] ?> раз</span>
                            <span class="action-users"><?= $action['unique_users'] ?> пользователей</span>
                        </div>
                        <div class="action-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= min(100, ($action['count'] / max(array_column($popular_actions, 'count'))) * 100) ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">Данные отсутствуют</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Подключение Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Данные для графиков
const activityData = <?= json_encode($user_activity) ?>;
const hourlyData = <?= json_encode($hourly_activity) ?>;
const weeklyData = <?= json_encode($weekly_activity) ?>;

// Инициализация графиков
let activityChart, hourlyChart, weeklyChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // График активности по дням
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: activityData.map(item => item.date),
            datasets: [{
                label: 'Активность пользователей',
                data: activityData.map(item => item.count),
                borderColor: '#2196F3',
                backgroundColor: 'rgba(33, 150, 243, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
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

    // График активности по часам
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    hourlyChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: hourlyData.map(item => item.hour + ':00'),
            datasets: [{
                label: 'Активность по часам',
                data: hourlyData.map(item => item.count),
                backgroundColor: 'rgba(76, 175, 80, 0.8)',
                borderColor: '#4CAF50',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
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

    // График активности по дням недели
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    const dayNames = ['', 'Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];
    
    weeklyChart = new Chart(weeklyCtx, {
        type: 'doughnut',
        data: {
            labels: weeklyData.map(item => dayNames[item.day_of_week]),
            datasets: [{
                data: weeklyData.map(item => item.count),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#FF6384'
                ],
                borderWidth: 2,
                borderColor: '#1a1a1a'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        color: '#fff'
                    }
                }
            }
        }
    });
}

// Обновление данных активности
function updateActivityData() {
    const period = document.getElementById('activityPeriod').value;
    const limit = document.getElementById('activityLimit').value;
    
    showLoading();
    
    fetch(`/admin/analytics/user-activity?period=${period}&limit=${limit}`)
        .then(response => response.text())
        .then(html => {
            location.reload();
        })
        .catch(error => {
            console.error('Ошибка обновления данных:', error);
            hideLoading();
        });
}

// Обновление данных активности
function refreshActivityData() {
    location.reload();
}

// Экспорт данных активности
function exportActivityData() {
    const data = {
        user_activity: activityData,
        top_users: <?= json_encode($top_users) ?>,
        popular_actions: <?= json_encode($popular_actions) ?>,
        hourly_activity: hourlyData,
        weekly_activity: weeklyData,
        period: '<?= $period ?>',
        timestamp: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'user_activity_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.json';
    a.click();
    URL.revokeObjectURL(url);
}

// Просмотр деталей пользователя
function viewUserDetails(userId) {
    window.open(`/admin/users/view/${userId}`, '_blank');
}

// Утилиты
function showLoading() {
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

<?php
// Вспомогательные функции для отображения
function getActionIcon($actionType) {
    $icons = [
        'login_success' => 'sign-in-alt',
        'login_failed' => 'times-circle',
        'logout' => 'sign-out-alt',
        'page_view' => 'eye',
        'file_upload' => 'upload',
        'file_download' => 'download',
        'user_edit' => 'user-edit',
        'user_create' => 'user-plus',
        'user_delete' => 'user-times',
        'suspicious_activity' => 'exclamation-triangle',
        'session_deactivated' => 'user-clock'
    ];
    
    return $icons[$actionType] ?? 'cog';
}

function getActionTitle($actionType) {
    $titles = [
        'login_success' => 'Успешный вход',
        'login_failed' => 'Неудачный вход',
        'logout' => 'Выход из системы',
        'page_view' => 'Просмотр страницы',
        'file_upload' => 'Загрузка файла',
        'file_download' => 'Скачивание файла',
        'user_edit' => 'Редактирование пользователя',
        'user_create' => 'Создание пользователя',
        'user_delete' => 'Удаление пользователя',
        'suspicious_activity' => 'Подозрительная активность',
        'session_deactivated' => 'Деактивация сессии'
    ];
    
    return $titles[$actionType] ?? 'Действие';
}
?>

<style>
/* Стили для страницы активности пользователей */
.user-activity-container {
    padding: 20px;
}

.activity-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid #333;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    font-size: 2em;
    color: #2196F3;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2em;
    font-weight: bold;
    color: #fff;
}

.stat-label {
    color: #ccc;
    margin-bottom: 5px;
}

.stat-change {
    font-size: 0.9em;
    padding: 2px 8px;
    border-radius: 3px;
}

.stat-change.positive {
    background: rgba(76, 175, 80, 0.2);
    color: #4CAF50;
}

.activity-charts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.chart-section {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid #333;
    border-radius: 10px;
    padding: 20px;
}

.chart-container {
    height: 300px;
    position: relative;
}

.top-users {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid #333;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #333;
}

.data-table th {
    background: rgba(0, 0, 0, 0.5);
    font-weight: bold;
}

.progress-bar {
    width: 100%;
    height: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    position: relative;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #2196F3, #4CAF50);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 0.9em;
    font-weight: bold;
}

.popular-actions {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid #333;
    border-radius: 10px;
    padding: 20px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.action-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid #333;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.action-icon {
    font-size: 1.5em;
    color: #2196F3;
}

.action-content {
    flex: 1;
}

.action-title {
    font-weight: bold;
    color: #fff;
    margin-bottom: 5px;
}

.action-stats {
    display: flex;
    gap: 15px;
    font-size: 0.9em;
    color: #ccc;
    margin-bottom: 8px;
}

.action-progress {
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
}

.action-progress .progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #2196F3, #4CAF50);
    border-radius: 3px;
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #888;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #333;
    border-top: 4px solid #2196F3;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style> 