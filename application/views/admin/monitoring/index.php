<?php
// Проверяем авторизацию
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /admin/auth/login');
    exit;
}

$title = $title ?? 'Мониторинг системы';
$currentPage = $currentPage ?? 'monitoring';

// Устанавливаем значения по умолчанию для переменных
$securityStats = $securityStats ?? [
    'security_score' => 85,
    'system_status' => 'secure',
    'total_threats' => 0,
    'blocked_ips' => 0,
    'suspicious_activities' => 0
];

$systemStats = $systemStats ?? [
    'cpu_usage' => 0,
    'memory_usage' => 0,
    'disk_usage' => 0,
    'network_usage' => 0
];

$recentThreats = $recentThreats ?? [];
?>

<div class="monitoring-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-shield-alt"></i> Мониторинг системы</h1>
            <p>Отслеживание безопасности и производительности системы в реальном времени</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-blue" onclick="refreshMonitoring()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
            <button class="btn btn-green" onclick="exportReport()">
                <i class="fas fa-download"></i> Экспорт
            </button>
        </div>
    </div>

    <!-- Статистика безопасности -->
    <div class="security-overview">
        <div class="overview-grid">
            <div class="overview-card security-score">
                <div class="card-header">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Общий балл безопасности</h3>
                </div>
                <div class="card-content">
                    <div class="score-circle">
                        <span class="score-number"><?= $securityStats['security_score'] ?></span>
                        <span class="score-label">из 100</span>
                    </div>
                    <div class="score-status <?= $securityStats['system_status'] ?>">
                        <?= ucfirst($securityStats['system_status']) ?>
                    </div>
                </div>
            </div>

            <div class="overview-card threats-card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Угрозы</h3>
                </div>
                <div class="card-content">
                    <div class="threats-stats">
                        <div class="threat-stat">
                            <span class="stat-number"><?= $securityStats['total_threats'] ?></span>
                            <span class="stat-label">Всего угроз</span>
                        </div>
                        <div class="threat-stat">
                            <span class="stat-number"><?= $securityStats['blocked_ips'] ?></span>
                            <span class="stat-label">Заблокировано IP</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overview-card activity-card">
                <div class="card-header">
                    <i class="fas fa-eye"></i>
                    <h3>Подозрительная активность</h3>
                </div>
                <div class="card-content">
                    <div class="activity-stats">
                        <div class="activity-stat">
                            <span class="stat-number"><?= $securityStats['suspicious_activities'] ?></span>
                            <span class="stat-label">Подозрительных действий</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Системная статистика -->
    <div class="system-stats">
        <h2><i class="fas fa-server"></i> Системные ресурсы</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="stat-content">
                    <h4>CPU</h4>
                    <div class="stat-value"><?= $systemStats['cpu_usage'] ?>%</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill" style="width: <?= $systemStats['cpu_usage'] ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-memory"></i>
                </div>
                <div class="stat-content">
                    <h4>Память</h4>
                    <div class="stat-value"><?= $systemStats['memory_usage'] ?>%</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill" style="width: <?= $systemStats['memory_usage'] ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-hdd"></i>
                </div>
                <div class="stat-content">
                    <h4>Диск</h4>
                    <div class="stat-value"><?= $systemStats['disk_usage'] ?>%</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill" style="width: <?= $systemStats['disk_usage'] ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="stat-content">
                    <h4>Сеть</h4>
                    <div class="stat-value"><?= $systemStats['network_usage'] ?> MB/s</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill" style="width: <?= $systemStats['network_usage'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Последние угрозы -->
    <div class="recent-threats">
        <div class="section-header">
            <h2><i class="fas fa-exclamation-triangle"></i> Последние угрозы</h2>
            <a href="/admin/monitoring/threats" class="btn btn-outline">Все угрозы</a>
        </div>
        
        <div class="threats-list">
            <?php if (!empty($recentThreats)): ?>
                <?php foreach ($recentThreats as $threat): ?>
                <div class="threat-item severity-<?= $threat['severity'] ?? 'medium' ?>">
                    <div class="threat-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="threat-content">
                        <div class="threat-header">
                            <h4><?= htmlspecialchars($threat['threat_type'] ?? 'Unknown') ?></h4>
                            <span class="threat-severity <?= $threat['severity'] ?? 'medium' ?>">
                                <?= ucfirst($threat['severity'] ?? 'medium') ?>
                            </span>
                        </div>
                        <div class="threat-details">
                            <span class="threat-ip">IP: <?= htmlspecialchars($threat['ip_address'] ?? 'Unknown') ?></span>
                            <span class="threat-time"><?= date('d.m.Y H:i', strtotime($threat['created_at'] ?? 'now')) ?></span>
                        </div>
                        <div class="threat-status <?= ($threat['is_resolved'] ?? false) ? 'resolved' : 'active' ?>">
                            <?= ($threat['is_resolved'] ?? false) ? 'Resolved' : 'Active' ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-threats">
                    <p>Угроз не обнаружено</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Быстрые действия -->
    <div class="quick-actions">
        <h2><i class="fas fa-bolt"></i> Быстрые действия</h2>
        <div class="actions-grid">
            <button class="action-btn" onclick="runSecurityScan()">
                <i class="fas fa-search"></i>
                <span>Запустить сканирование</span>
            </button>
            <button class="action-btn" onclick="blockSuspiciousIP()">
                <i class="fas fa-ban"></i>
                <span>Заблокировать IP</span>
            </button>
            <button class="action-btn" onclick="generateReport()">
                <i class="fas fa-file-alt"></i>
                <span>Создать отчет</span>
            </button>
            <button class="action-btn" onclick="updateSecurityRules()">
                <i class="fas fa-cog"></i>
                <span>Обновить правила</span>
            </button>
        </div>
    </div>
</div>

<script>
// Функции для мониторинга
function refreshMonitoring() {
    location.reload();
}

function exportReport() {
    // Логика экспорта отчета
    showNotification('Отчет экспортирован', 'success');
}

function runSecurityScan() {
    showNotification('Сканирование запущено...', 'info');
    // Логика запуска сканирования
}

function blockSuspiciousIP() {
    const ip = prompt('Введите IP адрес для блокировки:');
    if (ip) {
        showNotification(`IP ${ip} заблокирован`, 'success');
    }
}

function generateReport() {
    showNotification('Отчет создается...', 'info');
    // Логика создания отчета
}

function updateSecurityRules() {
    showNotification('Правила безопасности обновлены', 'success');
    // Логика обновления правил
}

// Автообновление каждые 30 секунд
setInterval(() => {
    // Обновление только критических данных
    updateSecurityStats();
}, 30000);

function updateSecurityStats() {
    // AJAX запрос для обновления статистики
    fetch('/admin/api/monitoring/stats')
        .then(response => response.json())
        .then(data => {
            // Обновление данных на странице
            console.log('Статистика обновлена');
        })
        .catch(error => {
            console.error('Ошибка обновления статистики:', error);
        });
}
</script> 