    <!-- Основные метрики -->
    <div class="metrics-grid">
        <div class="metric-card users">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= $analytics['users']['total'] ?></div>
                <div class="metric-label">Всего пользователей</div>
                <div class="metric-details">
                    <span class="active"><?= $analytics['users']['active'] ?> активных</span>
                    <span class="blocked"><?= $analytics['users']['blocked'] ?> заблокированных</span>
                </div>
            </div>
        </div>

        <div class="metric-card logins">
            <div class="metric-icon">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= $analytics['logins_24h']['total'] ?></div>
                <div class="metric-label">Входов за 24ч</div>
                <div class="metric-details">
                    <span class="success"><?= $analytics['logins_24h']['successful'] ?> успешных</span>
                    <span class="failed"><?= $analytics['logins_24h']['failed'] ?> неудачных</span>
                </div>
            </div>
        </div>

        <div class="metric-card sessions">
            <div class="metric-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= $analytics['sessions']['active'] ?></div>
                <div class="metric-label">Активных сессий</div>
                <div class="metric-details">
                    <span class="created"><?= $analytics['sessions']['created_24h'] ?> создано за 24ч</span>
                    <span class="avg"><?= $analytics['sessions']['avg_duration'] ?> мин средняя</span>
                </div>
            </div>
        </div>

        <div class="metric-card security">
            <div class="metric-icon threat-<?= $analytics['security']['threat_level'] ?>">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= $analytics['security']['suspicious_activities_24h'] ?></div>
                <div class="metric-label">Подозрительных действий</div>
                <div class="metric-details">
                    <span class="threat-level">Уровень угрозы: <?= strtoupper($analytics['security']['threat_level']) ?></span>
                    <span class="blocked-ips"><?= $analytics['security']['blocked_ips'] ?> IP заблокировано</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Графики и диаграммы -->
    <div class="charts-section">
        <div class="chart-container">
            <div class="chart-header">
                <h3>📈 Активность за 7 дней</h3>
                <div class="chart-controls">
                    <button class="btn btn-sm btn-blue" onclick="updateChart('activity')">Обновить</button>
                </div>
            </div>
            <div class="chart-content">
                <canvas id="activityChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3>🔐 Входы за 24 часа</h3>
                <div class="chart-controls">
                    <button class="btn btn-sm btn-blue" onclick="updateChart('logins')">Обновить</button>
                </div>
            </div>
            <div class="chart-content">
                <canvas id="loginsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3>⚠️ Подозрительная активность</h3>
                <div class="chart-controls">
                    <button class="btn btn-sm btn-blue" onclick="updateChart('suspicious')">Обновить</button>
                </div>
            </div>
            <div class="chart-content">
                <canvas id="suspiciousChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Уведомления безопасности -->
    <?php if (!empty($securityNotifications)): ?>
    <div class="notifications-section">
        <div class="section-header">
            <h3><i class="fas fa-bell"></i> Уведомления безопасности</h3>
            <button class="btn btn-sm btn-blue" onclick="markAllAsRead()">Отметить все как прочитанные</button>
        </div>
        <div class="notifications-grid">
            <?php foreach ($securityNotifications as $notification): ?>
            <div class="notification-item severity-<?= $notification['severity'] ?>">
                <div class="notification-icon">
                    <i class="fas fa-<?= $this->getNotificationIcon($notification['notification_type']) ?>"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title"><?= htmlspecialchars($notification['title']) ?></div>
                    <div class="notification-message"><?= htmlspecialchars($notification['message']) ?></div>
                    <div class="notification-time"><?= date('d.m.Y H:i', strtotime($notification['created_at'])) ?></div>
                </div>
                <div class="notification-actions">
                    <button class="btn btn-sm btn-secondary" onclick="markAsRead(<?= $notification['id'] ?>)">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Подозрительные IP адреса -->
    <?php if (!empty($suspiciousIPs)): ?>
    <div class="suspicious-ips-section">
        <div class="section-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Подозрительные IP адреса</h3>
            <button class="btn btn-sm btn-red" onclick="blockAllSuspiciousIPs()">Заблокировать все</button>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>IP Адрес</th>
                        <th>Попыток</th>
                        <th>Успешных</th>
                        <th>Неудачных</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suspiciousIPs as $ip): ?>
                    <tr>
                        <td>
                            <code><?= htmlspecialchars($ip['ip_address']) ?></code>
                        </td>
                        <td><?= $ip['attempts'] ?></td>
                        <td>
                            <span class="badge badge-success"><?= $ip['successful'] ?></span>
                        </td>
                        <td>
                            <span class="badge badge-danger"><?= $ip['failed'] ?></span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="blockIP('<?= htmlspecialchars($ip['ip_address']) ?>')">
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

    <!-- Активность пользователей -->
    <?php if (!empty($userActivity)): ?>
    <div class="user-activity-section">
        <div class="section-header">
            <h3><i class="fas fa-user-clock"></i> Активность пользователей</h3>
            <a href="/admin/analytics/user-activity" class="btn btn-sm btn-blue">Подробнее</a>
        </div>
        <div class="activity-grid">
            <?php foreach ($userActivity as $user): ?>
            <div class="activity-card">
                <div class="activity-user">
                    <div class="user-info">
                        <strong><?= htmlspecialchars($user['user_fio']) ?></strong>
                        <span class="user-login"><?= htmlspecialchars($user['user_login']) ?></span>
                    </div>
                    <div class="activity-stats">
                        <div class="stat-item">
                            <span class="stat-label">Активность:</span>
                            <span class="stat-value"><?= $user['activity_count'] ?> действий</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Последняя активность:</span>
                            <span class="stat-value"><?= $user['last_activity'] ? date('d.m.Y H:i', strtotime($user['last_activity'])) : 'Нет данных' ?></span>
                        </div>
                    </div>
                </div>
                <div class="activity-actions">
                    <a href="/admin/analytics/user-activity?user_id=<?= $user['user_id'] ?>" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Детали
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Стили для аналитики -->
<style>
.analytics-container {
    padding: 20px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.metric-card {
    background: linear-gradient(135deg, var(--medium-gray) 0%, var(--light-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: var(--glow-blue);
    transition: all 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
}

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 24px;
    color: var(--text-white);
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
}

.metric-card.users .metric-icon {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.metric-card.logins .metric-icon {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
}

.metric-card.sessions .metric-icon {
    background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow));
    color: #000;
}

.metric-card.security .metric-icon {
    background: linear-gradient(135deg, #ff4757, #ff3742);
}

.metric-card.security .metric-icon.threat-critical {
    background: linear-gradient(135deg, #ff4757, #ff3742);
    animation: pulse 2s infinite;
}

.metric-card.security .metric-icon.threat-high {
    background: linear-gradient(135deg, #ff6b35, #ff8c42);
}

.metric-card.security .metric-icon.threat-medium {
    background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow));
    color: #000;
}

.metric-card.security .metric-icon.threat-low {
    background: linear-gradient(135deg, #28a745, #20c997);
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(255, 71, 87, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 71, 87, 0); }
}

.metric-content {
    flex: 1;
}

.metric-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--text-white);
    margin-bottom: 5px;
}

.metric-label {
    font-size: 14px;
    color: var(--text-blue);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.metric-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.metric-details span {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 4px;
    display: inline-block;
}

.metric-details .active,
.metric-details .success,
.metric-details .created {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.metric-details .blocked,
.metric-details .failed {
    background: rgba(255, 71, 87, 0.2);
    color: #ff4757;
}

.metric-details .avg,
.metric-details .threat-level {
    background: rgba(0, 212, 255, 0.2);
    color: var(--primary-blue);
}

.metric-details .blocked-ips {
    background: rgba(255, 193, 7, 0.2);
    color: var(--primary-yellow);
}

.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.chart-container {
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--glow-blue);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.chart-header h3 {
    color: var(--text-yellow);
    margin: 0;
}

.chart-content {
    height: 200px;
    position: relative;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h3 {
    color: var(--text-yellow);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.notifications-grid,
.activity-grid {
    display: grid;
    gap: 15px;
}

.notification-item {
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    box-shadow: var(--glow-blue);
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateX(5px);
}

.notification-item.severity-critical {
    border-color: #ff4757;
    box-shadow: 0 0 20px rgba(255, 71, 87, 0.3);
}

.notification-item.severity-high {
    border-color: #ff6b35;
    box-shadow: 0 0 20px rgba(255, 107, 53, 0.3);
}

.notification-item.severity-medium {
    border-color: var(--primary-yellow);
    box-shadow: var(--glow-yellow);
}

.notification-item.severity-low {
    border-color: #28a745;
    box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: #000;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: bold;
    color: var(--text-white);
    margin-bottom: 5px;
}

.notification-message {
    color: var(--text-blue);
    margin-bottom: 5px;
}

.notification-time {
    font-size: 12px;
    color: var(--text-blue);
    opacity: 0.8;
}

.activity-card {
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 8px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: var(--glow-blue);
    transition: all 0.3s ease;
}

.activity-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
}

.activity-user {
    flex: 1;
}

.user-info {
    margin-bottom: 10px;
}

.user-login {
    font-size: 12px;
    color: var(--text-blue);
    opacity: 0.8;
}

.activity-stats {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
}

.stat-label {
    color: var(--text-blue);
}

.stat-value {
    color: var(--text-white);
    font-weight: 500;
}

@media (max-width: 768px) {
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .chart-content {
        height: 150px;
    }
}
</style>

<!-- JavaScript для графиков и интерактивности -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Данные для графиков
const chartData = <?= json_encode($chartData) ?>;

// Инициализация графиков
let activityChart, loginsChart, suspiciousChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    startAutoRefresh();
});

function initializeCharts() {
    // График активности
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: chartData.activity_7_days.map(item => item.date),
            datasets: [{
                label: 'Активность',
                data: chartData.activity_7_days.map(item => item.count),
                borderColor: '#00d4ff',
                backgroundColor: 'rgba(0, 212, 255, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // График входов
    const loginsCtx = document.getElementById('loginsChart').getContext('2d');
    loginsChart = new Chart(loginsCtx, {
        type: 'bar',
        data: {
            labels: chartData.logins_24h.map(item => item.hour + ':00'),
            datasets: [{
                label: 'Успешные',
                data: chartData.logins_24h.map(item => item.successful),
                backgroundColor: '#28a745'
            }, {
                label: 'Неудачные',
                data: chartData.logins_24h.map(item => item.failed),
                backgroundColor: '#ff4757'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // График подозрительной активности
    const suspiciousCtx = document.getElementById('suspiciousChart').getContext('2d');
    suspiciousChart = new Chart(suspiciousCtx, {
        type: 'line',
        data: {
            labels: chartData.suspicious_7_days.map(item => item.date),
            datasets: [{
                label: 'Подозрительная активность',
                data: chartData.suspicious_7_days.map(item => item.count),
                borderColor: '#ff4757',
                backgroundColor: 'rgba(255, 71, 87, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });
}

function updateChart(type) {
    // Здесь можно добавить AJAX запрос для обновления данных
    console.log('Updating chart:', type);
}

function startAutoRefresh() {
    // Автообновление каждые 30 секунд
    setInterval(function() {
        updateAnalyticsData();
    }, 30000);
}

function updateAnalyticsData() {
    fetch('/admin/analytics/api?type=security_stats')
        .then(response => response.json())
        .then(data => {
            // Обновляем метрики
            updateMetrics(data);
        })
        .catch(error => {
            console.error('Error updating analytics:', error);
        });
}

function updateMetrics(data) {
    // Обновляем числа в метриках
    const metricNumbers = document.querySelectorAll('.metric-number');
    // Здесь можно добавить анимацию обновления чисел
}

function markAsRead(notificationId) {
    fetch('/admin/analytics/mark-notification-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Удаляем уведомление из DOM
            const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notification) {
                notification.remove();
            }
        }
    });
}

function markAllAsRead() {
    fetch('/admin/analytics/mark-all-notifications-read', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Удаляем все уведомления
            document.querySelectorAll('.notification-item').forEach(item => {
                item.remove();
            });
        }
    });
}

function blockIP(ipAddress) {
    if (confirm(`Заблокировать IP адрес ${ipAddress}?`)) {
        fetch('/admin/analytics/block-ip', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ip_address: ipAddress })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('IP адрес заблокирован');
                location.reload();
            } else {
                alert('Ошибка при блокировке IP');
            }
        });
    }
}

function blockAllSuspiciousIPs() {
    if (confirm('Заблокировать все подозрительные IP адреса?')) {
        fetch('/admin/analytics/block-all-suspicious-ips', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Все подозрительные IP адреса заблокированы');
                location.reload();
            } else {
                alert('Ошибка при блокировке IP адресов');
            }
        });
    }
}
</script> 