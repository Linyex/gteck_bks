<?php $navPage = 'security'; ?>

<!-- Security Dashboard Content -->
<div class="security-dashboard">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-shield-alt"></i>
            <h1>Безопасность системы</h1>
        </div>
        <div class="page-subtitle">
            Мониторинг и управление безопасностью системы
        </div>
    </div>

    <!-- Security Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value"><?= (int)($stats['total_actions'] ?? 0) ?></div>
                <div class="metric-label">Всего действий</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value"><?= (int)($stats['successful_logins'] ?? 0) ?></div>
                <div class="metric-label">Успешных входов</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value"><?= (int)($stats['failed_logins'] ?? 0) ?></div>
                <div class="metric-label">Неудачных попыток</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value"><?= (int)($stats['admin_actions'] ?? 0) ?></div>
                <div class="metric-label">Админ действий</div>
            </div>
        </div>
    </div>

    <!-- Security Overview -->
    <div class="security-overview">
        <div class="overview-header">
            <h2><i class="fas fa-clock"></i> Обзор безопасности</h2>
            <div style="display:flex; gap:10px; align-items:center;">
                <div class="filter-group">
                    <label>Период:</label>
                    <select id="securityPeriod" class="form-select" style="min-width:140px;">
                        <option value="7">7 дней</option>
                        <option value="14">14 дней</option>
                        <option value="30" selected>30 дней</option>
                        <option value="90">90 дней</option>
                    </select>
                </div>
                <button class="btn btn-primary" onclick="SecurityDashboard.refresh()">
                    <i class="fas fa-sync-alt"></i> ОБНОВИТЬ
                </button>
            </div>
        </div>

        <div class="overview-content">
            <div class="system-status">
                <?php $failed = (int)($stats['failed_logins'] ?? 0); $ok = $failed === 0; ?>
                <div class="status-indicator">
                    <div class="status-dot <?= $ok ? 'status-green' : 'status-yellow' ?>"></div>
                    <span><?= $ok ? 'Система защищена' : 'Требуется внимание' ?></span>
                </div>
                <p>
                    <?= $ok ? 'Все компоненты безопасности работают нормально' : ('Неудачных попыток за период: ' . $failed) ?>
                </p>
            </div>

            <div class="threats-section">
                <h3>Последние события</h3>
                <div class="threat-list">
                    <?php if (!empty($recentEvents)): ?>
                        <?php foreach ($recentEvents as $ev): ?>
                            <div class="threat-item">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span><?= htmlspecialchars($ev['action_type']) ?> <?= date('d.m.Y H:i', strtotime($ev['created_at'])) ?></span>
                                <?php if (!empty($ev['user_fio'])): ?>
                                    <small> — <?= htmlspecialchars($ev['user_fio']) ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="threat-item"><span>Событий нет</span></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="recommendations-section">
                <h3>Рекомендации</h3>
                <div class="recommendation-list">
                    <div class="recommendation-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Регулярно проверяйте логи безопасности</span>
                    </div>
                    <div class="recommendation-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Мониторьте подозрительную активность</span>
                    </div>
                    <div class="recommendation-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Обновляйте пароли пользователей</span>
                    </div>
                </div>
            </div>
            
            <div class="charts-section" style="margin-top:20px;">
                <h3>Графики</h3>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <canvas id="chartByType" height="140"></canvas>
                    </div>
                    <div>
                        <canvas id="chartLogins" height="140"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2><i class="fas fa-bolt"></i> Быстрые действия</h2>
        <div class="actions-grid">
            <a href="/admin/security/audit" class="action-link">
                <i class="fas fa-clipboard-list"></i>
                <span>Просмотр аудита</span>
            </a>
            <a href="/admin/security/ip-blacklist" class="action-link">
                <i class="fas fa-ban"></i>
                <span>Блокировка IP</span>
            </a>
            <a href="/admin/security/sessions" class="action-link">
                <i class="fas fa-users"></i>
                <span>Активные сессии</span>
            </a>
            <a href="/admin/security/audit" class="action-link">
                <i class="fas fa-file-alt"></i>
                <span>Логи безопасности</span>
            </a>
        </div>
        <button class="btn btn-success export-btn">
            <i class="fas fa-download"></i> Экспорт логов
        </button>
    </div>
</div>

<script>
function refreshSecurity() {
    // Логика обновления данных безопасности
    console.log('Обновление данных безопасности...');
}
</script> 

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php if (!empty($additional_js)): ?>
    <?php foreach ($additional_js as $js): ?>
        <script src="<?= $js ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<script>
// Мини-дэшборд с графиками
const SecurityDashboard = (function(){
    let chartType, chartLogins;
    function buildCharts(byType, series){
        const ctx1 = document.getElementById('chartByType');
        const ctx2 = document.getElementById('chartLogins');
        if (!ctx1 || !ctx2) return;
        const labels1 = (byType||[]).map(x=>x.action_type);
        const data1 = (byType||[]).map(x=>parseInt(x.cnt||0,10));
        if (chartType) chartType.destroy();
        chartType = new Chart(ctx1, {
            type: 'doughnut',
            data: { labels: labels1, datasets: [{ data: data1, backgroundColor: ['#00ffff','#00ff88','#ffcc00','#ff6666','#8888ff'] }] },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
        const labels2 = (series.successful_logins||[]).map(x=>x.d);
        const succ = (series.successful_logins||[]).map(x=>parseInt(x.c||0,10));
        const fail = (series.failed_logins||[]).map(x=>parseInt(x.c||0,10));
        if (chartLogins) chartLogins.destroy();
        chartLogins = new Chart(ctx2, {
            type: 'line',
            data: { labels: labels2, datasets: [
                { label:'Успешные входы', data: succ, borderColor:'#00ff88', tension:0.3 },
                { label:'Неудачные входы', data: fail, borderColor:'#ff6666', tension:0.3 }
            ] },
            options: { responsive:true, plugins:{ legend:{ position:'bottom' } } }
        });
    }
    async function refresh(){
        const days = document.getElementById('securityPeriod')?.value || 30;
        const res = await fetch('/admin/security/api/stats?days=' + encodeURIComponent(days), {credentials:'same-origin'});
        if (!res.ok) return;
        const data = await res.json();
        if (!data.success) return;
        buildCharts(data.by_type || [], data.series || {});
        // При желании можно обновлять карточки метрик здесь
    }
    return { refresh };
})();

document.getElementById('securityPeriod')?.addEventListener('change', ()=>SecurityDashboard.refresh());
window.addEventListener('load', ()=> SecurityDashboard.refresh());
</script>