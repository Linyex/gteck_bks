    <!-- Статистика угроз -->
         <div class="threat-stats-grid">
        <div class="threat-stat-card">
            <div class="stat-header">
                <h3>Типы угроз за 24 часа</h3>
            </div>
            <div class="stat-content">
                <?php foreach ($securityStats['threat_types'] as $threat): ?>
                <div class="threat-item">
                    <span class="threat-type"><?= $this->getThreatTypeName($threat['action_type']) ?></span>
                    <span class="threat-count"><?= $threat['count'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="threat-stat-card">
            <div class="stat-header">
                <h3>Почасовая статистика</h3>
            </div>
            <div class="stat-content">
                <canvas id="hourlyChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Анализ угроз -->
    <div class="threat-analysis-section">
        <div class="section-header">
            <h3>🔍 Анализ угроз</h3>
        </div>
        
        <!-- Пользователи высокого риска -->
        <?php if (!empty($threatAnalysis['high_risk_users'])): ?>
        <div class="threat-group">
            <h4>⚠️ Пользователи высокого риска</h4>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Пользователь</th>
                            <th>Неудачных попыток</th>
                            <th>Уникальных IP</th>
                            <th>Риск</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($threatAnalysis['high_risk_users'] as $user): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <strong><?= htmlspecialchars($user['user_fio']) ?></strong>
                                    <span class="user-login"><?= htmlspecialchars($user['user_login']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-danger"><?= $user['failed_attempts'] ?></span>
                            </td>
                            <td>
                                <span class="badge badge-warning"><?= $user['unique_ips'] ?></span>
                            </td>
                            <td>
                                <span class="risk-level high">Высокий</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="blockUser(<?= $user['user_id'] ?>)">
                                    <i class="fas fa-ban"></i> Заблокировать
                                </button>
                                <button class="btn btn-sm btn-info" onclick="resetPassword(<?= $user['user_id'] ?>)">
                                    <i class="fas fa-key"></i> Сбросить пароль
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Подозрительные паттерны -->
        <?php if (!empty($threatAnalysis['suspicious_patterns'])): ?>
        <div class="threat-group">
            <h4>🔍 Подозрительные паттерны</h4>
            <div class="patterns-grid">
                <?php foreach ($threatAnalysis['suspicious_patterns'] as $pattern): ?>
                <div class="pattern-card">
                    <div class="pattern-header">
                        <code><?= htmlspecialchars($pattern['ip_address']) ?></code>
                        <span class="pattern-risk">Высокий риск</span>
                    </div>
                    <div class="pattern-stats">
                        <div class="stat-item">
                            <span class="stat-label">Попыток:</span>
                            <span class="stat-value"><?= $pattern['attempts'] ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Уникальных пользователей:</span>
                            <span class="stat-value"><?= $pattern['unique_users'] ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Средний интервал:</span>
                            <span class="stat-value"><?= round($pattern['avg_interval'] ?? 0, 1) ?> мин</span>
                        </div>
                    </div>
                    <div class="pattern-actions">
                        <button class="btn btn-sm btn-danger" onclick="blockIP('<?= htmlspecialchars($pattern['ip_address']) ?>')">
                            <i class="fas fa-ban"></i> Заблокировать IP
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Статистика по IP адресам -->
    <div class="ip-stats-section">
        <div class="section-header">
            <h3>🌐 Статистика по IP адресам</h3>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>IP Адрес</th>
                        <th>Всего попыток</th>
                        <th>Успешных</th>
                        <th>Неудачных</th>
                        <th>Уникальных пользователей</th>
                        <th>Первый запрос</th>
                        <th>Последний запрос</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ipStats as $ip): ?>
                    <tr>
                        <td>
                            <code><?= htmlspecialchars($ip['ip_address']) ?></code>
                        </td>
                        <td><?= $ip['total_attempts'] ?></td>
                        <td>
                            <span class="badge badge-success"><?= $ip['successful_attempts'] ?></span>
                        </td>
                        <td>
                            <span class="badge badge-danger"><?= $ip['failed_attempts'] ?></span>
                        </td>
                        <td><?= $ip['unique_users'] ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($ip['first_attempt'])) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($ip['last_attempt'])) ?></td>
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

    <!-- История инцидентов -->
    <div class="incident-history-section">
        <div class="section-header">
            <h3>📋 История инцидентов</h3>
        </div>
        <div class="incidents-grid">
            <?php foreach ($incidentHistory as $incident): ?>
            <div class="incident-card severity-<?= $incident['severity'] ?>">
                <div class="incident-header">
                    <div class="incident-type">
                        <i class="fas fa-<?= $this->getIncidentIcon($incident['notification_type']) ?>"></i>
                        <span><?= $incident['type_name'] ?></span>
                    </div>
                    <div class="incident-time">
                        <?= date('d.m.Y H:i', strtotime($incident['created_at'])) ?>
                    </div>
                </div>
                <div class="incident-content">
                    <div class="incident-title"><?= htmlspecialchars($incident['title']) ?></div>
                    <div class="incident-message"><?= htmlspecialchars($incident['message']) ?></div>
                </div>
                <div class="incident-severity">
                    <span class="severity-badge severity-<?= $incident['severity'] ?>">
                        <?= ucfirst($incident['severity']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.security-analytics-container {
    padding: 20px;
}

.threat-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.threat-stat-card {
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--glow-blue);
}

.stat-header h3 {
    color: var(--text-yellow);
    margin-bottom: 15px;
}

.threat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.threat-item:last-child {
    border-bottom: none;
}

.threat-type {
    color: var(--text-white);
    font-weight: 500;
}

.threat-count {
    background: var(--primary-blue);
    color: #000;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}

.threat-analysis-section {
    margin-bottom: 30px;
}

.threat-group {
    margin-bottom: 30px;
}

.threat-group h4 {
    color: var(--text-yellow);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.patterns-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
}

.pattern-card {
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-red);
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 0 20px rgba(255, 71, 87, 0.3);
}

.pattern-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.pattern-header code {
    background: rgba(255, 71, 87, 0.2);
    color: #ff4757;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
}

.pattern-risk {
    background: #ff4757;
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}

.pattern-stats {
    margin-bottom: 15px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 14px;
}

.stat-label {
    color: var(--text-blue);
}

.stat-value {
    color: var(--text-white);
    font-weight: 500;
}

.risk-level {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}

.risk-level.high {
    background: #ff4757;
    color: #fff;
}

.risk-level.medium {
    background: var(--primary-yellow);
    color: #000;
}

.risk-level.low {
    background: #28a745;
    color: #fff;
}

.incidents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 15px;
}

.incident-card {
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 8px;
    padding: 15px;
    box-shadow: var(--glow-blue);
    transition: all 0.3s ease;
}

.incident-card:hover {
    transform: translateY(-2px);
}

.incident-card.severity-critical {
    border-color: #ff4757;
    box-shadow: 0 0 20px rgba(255, 71, 87, 0.3);
}

.incident-card.severity-high {
    border-color: #ff6b35;
    box-shadow: 0 0 20px rgba(255, 107, 53, 0.3);
}

.incident-card.severity-medium {
    border-color: var(--primary-yellow);
    box-shadow: var(--glow-yellow);
}

.incident-card.severity-low {
    border-color: #28a745;
    box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
}

.incident-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.incident-type {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-white);
    font-weight: 500;
}

.incident-time {
    font-size: 12px;
    color: var(--text-blue);
}

.incident-content {
    margin-bottom: 10px;
}

.incident-title {
    font-weight: bold;
    color: var(--text-white);
    margin-bottom: 5px;
}

.incident-message {
    color: var(--text-blue);
    font-size: 14px;
}

.incident-severity {
    text-align: right;
}

.severity-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}

.severity-badge.severity-critical {
    background: #ff4757;
    color: #fff;
}

.severity-badge.severity-high {
    background: #ff6b35;
    color: #fff;
}

.severity-badge.severity-medium {
    background: var(--primary-yellow);
    color: #000;
}

.severity-badge.severity-low {
    background: #28a745;
    color: #fff;
}

@media (max-width: 768px) {
    .threat-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .patterns-grid {
        grid-template-columns: 1fr;
    }
    
    .incidents-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// График почасовой статистики
document.addEventListener('DOMContentLoaded', function() {
    const hourlyData = <?= json_encode($securityStats['hourly_stats']) ?>;
    
    const ctx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: hourlyData.map(item => item.hour + ':00'),
            datasets: [{
                label: 'Угрозы',
                data: hourlyData.map(item => item.count),
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
});

function blockUser(userId) {
    if (confirm('Заблокировать пользователя?')) {
        fetch('/admin/users/block/' + userId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                reason: 'Высокий риск безопасности',
                duration: 'temporary',
                block_until: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString().slice(0, 16)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Пользователь заблокирован');
                location.reload();
            } else {
                alert('Ошибка при блокировке пользователя');
            }
        });
    }
}

function resetPassword(userId) {
    if (confirm('Сбросить пароль пользователя?')) {
        fetch('/admin/users/reset-password/' + userId, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Пароль сброшен. Новый пароль: ' + data.new_password);
            } else {
                alert('Ошибка при сбросе пароля');
            }
        });
    }
}

function blockIP(ipAddress) {
    if (confirm(`Заблокировать IP адрес ${ipAddress}?`)) {
        fetch('/admin/analytics/block-ip', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                ip_address: ipAddress,
                reason: 'Подозрительная активность',
                duration: 24 // часы
            })
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
</script> 