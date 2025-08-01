<?php
// Проверяем существование переменных
if (!isset($active_sessions)) $active_sessions = [];
if (!isset($session_stats)) $session_stats = [
    'total_sessions' => 0,
    'active_sessions' => 0,
    'inactive_sessions' => 0,
    'unique_users' => 0,
    'avg_duration_minutes' => 0
];
if (!isset($daily_sessions)) $daily_sessions = [];
if (!isset($suspicious_sessions)) $suspicious_sessions = [];
?>

<div class="sessions-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-key"></i> Управление сессиями</h1>
            <p>Мониторинг и управление активными сессиями пользователей</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-red" onclick="terminateAllSessions()">
                <i class="fas fa-ban"></i> Завершить все сессии
            </button>
            <button class="btn btn-orange" onclick="cleanupOldSessions()">
                <i class="fas fa-broom"></i> Очистить старые
            </button>
            <button class="btn btn-blue" onclick="refreshSessionsData()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
            <button class="btn btn-green" onclick="exportSessionsData()">
                <i class="fas fa-download"></i> Экспорт
            </button>
        </div>
    </div>

    <!-- Статистика сессий -->
    <div class="sessions-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $session_stats['unique_users'] ?></div>
                <div class="stat-label">Уникальных пользователей</div>
                <div class="stat-change positive">
                    Активные пользователи
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-key"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $session_stats['active_sessions'] ?></div>
                <div class="stat-label">Активных сессий</div>
                <div class="stat-change <?= $session_stats['active_sessions'] > 10 ? 'negative' : 'positive' ?>">
                    <?= $session_stats['active_sessions'] > 10 ? 'Много активных сессий' : 'Нормальное количество' ?>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= round($session_stats['avg_duration_minutes']) ?></div>
                <div class="stat-label">Средняя длительность (мин)</div>
                <div class="stat-change positive">
                    Среднее время сессии
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-history"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $session_stats['total_sessions'] ?></div>
                <div class="stat-label">Всего сессий за неделю</div>
                <div class="stat-change positive">
                    Общая статистика
                </div>
            </div>
        </div>
    </div>

    <!-- График сессий -->
    <div class="sessions-chart">
        <h3><i class="fas fa-chart-line"></i> График сессий по дням</h3>
        <div class="chart-container">
            <canvas id="sessionsChart" width="800" height="300"></canvas>
        </div>
    </div>

    <!-- Активные сессии -->
    <div class="active-sessions">
        <h3><i class="fas fa-list"></i> Активные сессии (<?= count($active_sessions) ?>)</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Логин</th>
                        <th>Email</th>
                        <th>IP Адрес</th>
                        <th>Длительность</th>
                        <th>Последняя активность</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($active_sessions)): ?>
                        <?php foreach ($active_sessions as $session): ?>
                        <tr class="session-row" data-session-id="<?= $session['id'] ?>">
                            <td><?= htmlspecialchars($session['user_fio'] ?? 'Без имени') ?></td>
                            <td><?= htmlspecialchars($session['user_login']) ?></td>
                            <td><?= htmlspecialchars($session['user_email'] ?? 'Нет email') ?></td>
                            <td>
                                <span class="ip-address"><?= htmlspecialchars($session['ip_address'] ?? 'Неизвестно') ?></span>
                                <?php if (isSuspiciousIP($session['ip_address'])): ?>
                                    <span class="suspicious-badge">Подозрительный</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="duration">
                                    <?= formatDuration($session['duration_minutes']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="last-activity" data-time="<?= $session['last_activity'] ?>">
                                    <?= date('d.m.Y H:i:s', strtotime($session['last_activity'])) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-red" onclick="terminateSession(<?= $session['id'] ?>)">
                                    <i class="fas fa-times"></i> Завершить
                                </button>
                                <button class="btn btn-sm btn-blue" onclick="viewSessionDetails(<?= $session['id'] ?>)">
                                    <i class="fas fa-eye"></i> Детали
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-data">Активных сессий не найдено</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Подозрительные сессии -->
    <?php if (!empty($suspicious_sessions)): ?>
    <div class="suspicious-sessions">
        <h3><i class="fas fa-exclamation-triangle"></i> Подозрительные сессии (<?= count($suspicious_sessions) ?>)</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Логин</th>
                        <th>IP Адрес</th>
                        <th>Причина</th>
                        <th>Создана</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suspicious_sessions as $session): ?>
                    <tr class="suspicious-row">
                        <td><?= htmlspecialchars($session['user_fio'] ?? 'Без имени') ?></td>
                        <td><?= htmlspecialchars($session['user_login']) ?></td>
                        <td>
                            <span class="ip-address suspicious"><?= htmlspecialchars($session['ip_address'] ?? 'Неизвестно') ?></span>
                        </td>
                        <td>
                            <span class="suspicious-reason">Подозрительная активность</span>
                        </td>
                        <td><?= date('d.m.Y H:i:s', strtotime($session['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-red" onclick="terminateSession(<?= $session['id'] ?>)">
                                <i class="fas fa-ban"></i> Заблокировать
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Подключение Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Данные для графика сессий
const sessionsData = <?= json_encode($daily_sessions) ?>;

// Инициализация графика сессий
let sessionsChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeSessionsChart();
    updateSessionTimers();
    setInterval(updateSessionTimers, 60000); // Обновляем каждую минуту
});

function initializeSessionsChart() {
    const ctx = document.getElementById('sessionsChart').getContext('2d');
    
    sessionsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: sessionsData.map(item => item.date),
            datasets: [{
                label: 'Созданные сессии',
                data: sessionsData.map(item => item.created_count),
                borderColor: '#2196F3',
                backgroundColor: 'rgba(33, 150, 243, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Активные сессии',
                data: sessionsData.map(item => item.active_count),
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
}

// Обновление таймеров сессий
function updateSessionTimers() {
    const lastActivityElements = document.querySelectorAll('.last-activity');
    
    lastActivityElements.forEach(element => {
        const timeStr = element.getAttribute('data-time');
        if (timeStr) {
            const lastActivity = new Date(timeStr);
            const now = new Date();
            const diffMinutes = Math.floor((now - lastActivity) / (1000 * 60));
            
            if (diffMinutes < 1) {
                element.innerHTML = 'Только что';
            } else if (diffMinutes < 60) {
                element.innerHTML = `${diffMinutes} мин назад`;
            } else {
                const diffHours = Math.floor(diffMinutes / 60);
                element.innerHTML = `${diffHours} ч ${diffMinutes % 60} мин назад`;
            }
        }
    });
}

// Завершение конкретной сессии
function terminateSession(sessionId) {
    if (confirm('Завершить эту сессию?')) {
        showLoading();
        
        fetch('/admin/security/sessions/terminate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                session_id: sessionId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Сессия завершена');
                location.reload();
            } else {
                alert('Ошибка при завершении сессии');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Ошибка:', error);
            hideLoading();
        });
    }
}

// Завершение всех сессий
function terminateAllSessions() {
    if (confirm('Завершить ВСЕ активные сессии? Это отключит всех пользователей!')) {
        showLoading();
        
        fetch('/admin/security/sessions/terminate-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Все сессии завершены');
                location.reload();
            } else {
                alert('Ошибка при завершении сессий');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Ошибка:', error);
            hideLoading();
        });
    }
}

// Очистка старых сессий
function cleanupOldSessions() {
    if (confirm('Очистить старые неактивные сессии?')) {
        showLoading();
        
        fetch('/admin/security/sessions/cleanup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Старые сессии очищены');
                location.reload();
            } else {
                alert('Ошибка при очистке сессий');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Ошибка:', error);
            hideLoading();
        });
    }
}

// Обновление данных сессий
function refreshSessionsData() {
    location.reload();
}

// Экспорт данных сессий
function exportSessionsData() {
    const data = {
        active_sessions: <?= json_encode($active_sessions) ?>,
        session_stats: <?= json_encode($session_stats) ?>,
        daily_sessions: sessionsData,
        suspicious_sessions: <?= json_encode($suspicious_sessions) ?>,
        timestamp: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sessions_data_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.json';
    a.click();
    URL.revokeObjectURL(url);
}

// Просмотр деталей сессии
function viewSessionDetails(sessionId) {
    // Можно открыть модальное окно или перейти на страницу деталей
    alert('Функция просмотра деталей сессии будет добавлена позже');
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
// Вспомогательные функции
function isSuspiciousIP($ip) {
    // Простая проверка на подозрительные IP
    $suspiciousPatterns = [
        '/^192\.168\./', // Локальные сети
        '/^10\./',       // Локальные сети
        '/^172\.(1[6-9]|2[0-9]|3[0-1])\./', // Локальные сети
        '/^127\./',      // Локальный хост
    ];
    
    foreach ($suspiciousPatterns as $pattern) {
        if (preg_match($pattern, $ip)) {
            return false; // Локальные IP не считаются подозрительными
        }
    }
    
    return true; // Внешние IP считаются подозрительными
}

function formatDuration($minutes) {
    if ($minutes < 1) {
        return 'Меньше минуты';
    } elseif ($minutes < 60) {
        return $minutes . ' мин';
    } else {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return $hours . ' ч ' . $mins . ' мин';
    }
}
?>

<style>
/* Стили для страницы сессий */
.sessions-container {
    padding: 20px;
}

.sessions-stats {
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

.stat-change.negative {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
}

.sessions-chart {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid #333;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
}

.chart-container {
    height: 300px;
    position: relative;
}

.active-sessions,
.suspicious-sessions {
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

.ip-address {
    font-family: monospace;
    color: #2196F3;
}

.ip-address.suspicious {
    color: #f44336;
}

.suspicious-badge {
    background: #f44336;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.8em;
    margin-left: 5px;
}

.duration {
    color: #4CAF50;
    font-weight: bold;
}

.last-activity {
    color: #ccc;
    font-size: 0.9em;
}

.suspicious-reason {
    color: #ff9800;
    font-weight: bold;
}

.session-row:hover,
.suspicious-row:hover {
    background: rgba(255, 255, 255, 0.05);
}

.suspicious-row {
    background: rgba(244, 67, 54, 0.1);
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