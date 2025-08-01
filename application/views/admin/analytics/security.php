<?php
// Проверяем существование переменных
if (!isset($security_events)) $security_events = [];
if (!isset($failed_logins)) $failed_logins = [];
if (!isset($suspicious_activity)) $suspicious_activity = [];
if (!isset($security_stats)) $security_stats = [
    'failed_logins_24h' => 0,
    'suspicious_activities_24h' => 0,
    'security_events_24h' => 0,
    'unique_failed_ips' => 0
];
if (!isset($suspicious_ips)) $suspicious_ips = [];
if (!isset($security_timeline)) $security_timeline = [];
if (!isset($period)) $period = '7d';
?>

<div class="security-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-shield-alt"></i> Безопасность системы</h1>
            <p>Мониторинг безопасности и обнаружение угроз</p>
        </div>
        <div class="header-actions">
            <select id="securityPeriod" onchange="updateSecurityData()">
                <option value="7d" <?= $period === '7d' ? 'selected' : '' ?>>Последние 7 дней</option>
                <option value="30d" <?= $period === '30d' ? 'selected' : '' ?>>Последние 30 дней</option>
                <option value="90d" <?= $period === '90d' ? 'selected' : '' ?>>Последние 90 дней</option>
            </select>
            <button class="btn btn-red" onclick="blockSuspiciousIPs()">
                <i class="fas fa-ban"></i> Заблокировать подозрительные IP
            </button>
            <button class="btn btn-blue" onclick="refreshSecurityData()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
        </div>
    </div>

    <!-- Статистика безопасности -->
    <div class="security-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $security_stats['failed_logins_24h'] ?></div>
                <div class="stat-label">Неудачных входов за 24ч</div>
                <div class="stat-change <?= $security_stats['failed_logins_24h'] > 10 ? 'negative' : 'positive' ?>">
                    <?= $security_stats['failed_logins_24h'] > 10 ? 'Высокий уровень угрозы' : 'Нормальный уровень' ?>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-secret"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $security_stats['suspicious_activities_24h'] ?></div>
                <div class="stat-label">Подозрительных действий</div>
                <div class="stat-change <?= $security_stats['suspicious_activities_24h'] > 5 ? 'negative' : 'positive' ?>">
                    <?= $security_stats['suspicious_activities_24h'] > 5 ? 'Требует внимания' : 'Безопасно' ?>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-globe"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $security_stats['unique_failed_ips'] ?></div>
                <div class="stat-label">Подозрительных IP адресов</div>
                <div class="stat-change <?= $security_stats['unique_failed_ips'] > 3 ? 'negative' : 'positive' ?>">
                    <?= $security_stats['unique_failed_ips'] > 3 ? 'Множественные источники' : 'Нормально' ?>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shield-virus"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $security_stats['security_events_24h'] ?></div>
                <div class="stat-label">Событий безопасности</div>
                <div class="stat-change <?= $security_stats['security_events_24h'] > 20 ? 'negative' : 'positive' ?>">
                    <?= $security_stats['security_events_24h'] > 20 ? 'Повышенная активность' : 'Стандартно' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- График безопасности -->
    <div class="security-chart">
        <h3><i class="fas fa-chart-line"></i> График событий безопасности</h3>
        <div class="chart-container">
            <canvas id="securityChart" width="800" height="300"></canvas>
        </div>
    </div>

    <!-- Подозрительные IP адреса -->
    <?php if (!empty($suspicious_ips)): ?>
    <div class="suspicious-ips">
        <h3><i class="fas fa-exclamation-triangle"></i> Подозрительные IP адреса</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>IP Адрес</th>
                        <th>Неудачных попыток</th>
                        <th>Уникальных пользователей</th>
                        <th>Последняя попытка</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suspicious_ips as $ip): ?>
                    <tr>
                        <td><?= htmlspecialchars($ip['ip_address']) ?></td>
                        <td><?= $ip['failed_attempts'] ?></td>
                        <td><?= $ip['unique_users'] ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($ip['last_attempt'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-red" onclick="blockIP('<?= $ip['ip_address'] ?>')">
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

    <!-- События безопасности -->
    <div class="security-events">
        <h3><i class="fas fa-list"></i> События безопасности</h3>
        <div class="events-list">
            <?php if (!empty($security_events)): ?>
                <?php foreach ($security_events as $event): ?>
                <div class="event-item event-<?= $event['action_type'] ?>">
                    <div class="event-icon">
                        <i class="fas fa-<?= $event['action_type'] === 'login_failed' ? 'times-circle' : ($event['action_type'] === 'suspicious_activity' ? 'exclamation-triangle' : 'info-circle') ?>"></i>
                    </div>
                    <div class="event-content">
                        <div class="event-title">
                            <?= htmlspecialchars($event['user_login'] ?? 'Неизвестный пользователь') ?> 
                            (<?= htmlspecialchars($event['user_fio'] ?? 'Без имени') ?>)
                        </div>
                        <div class="event-description"><?= htmlspecialchars($event['activity_description'] ?? 'Событие безопасности') ?></div>
                        <div class="event-meta">
                            <span class="event-time"><?= date('d.m.Y H:i:s', strtotime($event['activity_time'])) ?></span>
                            <span class="event-ip">IP: <?= htmlspecialchars($event['ip_address'] ?? 'Неизвестно') ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-events">
                    <i class="fas fa-check-circle"></i>
                    <p>Событий безопасности не обнаружено</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Подключение Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Данные для графика безопасности
const securityData = <?= json_encode($security_timeline) ?>;

// Инициализация графика безопасности
let securityChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeSecurityChart();
});

function initializeSecurityChart() {
    const ctx = document.getElementById('securityChart').getContext('2d');
    
    securityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: securityData.map(item => item.date),
            datasets: [{
                label: 'Неудачные входы',
                data: securityData.map(item => item.failed_logins),
                borderColor: '#f44336',
                backgroundColor: 'rgba(244, 67, 54, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Подозрительная активность',
                data: securityData.map(item => item.suspicious_activities),
                borderColor: '#ff9800',
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

// Обновление данных безопасности
function updateSecurityData() {
    const period = document.getElementById('securityPeriod').value;
    
    showLoading();
    
    fetch(`/admin/analytics/security?period=${period}`)
        .then(response => response.text())
        .then(html => {
            // Обновляем страницу
            location.reload();
        })
        .catch(error => {
            console.error('Ошибка обновления данных:', error);
            hideLoading();
        });
}

// Обновление данных безопасности
function refreshSecurityData() {
    location.reload();
}

// Блокировка подозрительных IP
function blockSuspiciousIPs() {
    if (confirm('Заблокировать все подозрительные IP адреса?')) {
        showLoading();
        
        fetch('/admin/security/ip-blacklist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'block_suspicious_ips'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Подозрительные IP адреса заблокированы');
                location.reload();
            } else {
                alert('Ошибка при блокировке IP адресов');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Ошибка:', error);
            hideLoading();
        });
    }
}

// Блокировка конкретного IP
function blockIP(ipAddress) {
    if (confirm(`Заблокировать IP адрес ${ipAddress}?`)) {
        showLoading();
        
        fetch('/admin/security/ip-blacklist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                ip_address: ipAddress,
                reason: 'Множественные неудачные попытки входа'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`IP адрес ${ipAddress} заблокирован`);
                location.reload();
            } else {
                alert('Ошибка при блокировке IP адреса');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Ошибка:', error);
            hideLoading();
        });
    }
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

<style>
/* Стили для страницы безопасности */
.security-container {
    padding: 20px;
}

.security-stats {
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
    color: #ff9800;
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

.security-chart {
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

.suspicious-ips {
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

.security-events {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid #333;
    border-radius: 10px;
    padding: 20px;
}

.events-list {
    max-height: 500px;
    overflow-y: auto;
}

.event-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    border-bottom: 1px solid #333;
    transition: background 0.3s;
}

.event-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

.event-icon {
    font-size: 1.5em;
    color: #ff9800;
    margin-top: 5px;
}

.event-content {
    flex: 1;
}

.event-title {
    font-weight: bold;
    color: #fff;
    margin-bottom: 5px;
}

.event-description {
    color: #ccc;
    margin-bottom: 5px;
}

.event-meta {
    display: flex;
    gap: 15px;
    font-size: 0.9em;
    color: #888;
}

.event-login_failed .event-icon {
    color: #f44336;
}

.event-suspicious_activity .event-icon {
    color: #ff9800;
}

.event-session_deactivated .event-icon {
    color: #2196F3;
}

.no-events {
    text-align: center;
    padding: 40px;
    color: #4CAF50;
}

.no-events i {
    font-size: 3em;
    margin-bottom: 15px;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #333;
    border-top: 4px solid #ff9800;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style> 