<?php
// Устанавливаем текущую страницу для активного состояния навигации
$currentPage = 'security';
?>

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
                <div class="metric-value">8</div>
                <div class="metric-label">Всего действий</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">3</div>
                <div class="metric-label">Успешных входов</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">0</div>
                <div class="metric-label">Неудачных попыток</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">1</div>
                <div class="metric-label">Админ действий</div>
            </div>
        </div>
    </div>

    <!-- Security Overview -->
    <div class="security-overview">
        <div class="overview-header">
            <h2><i class="fas fa-clock"></i> Обзор безопасности</h2>
            <button class="btn btn-primary" onclick="refreshSecurity()">
                <i class="fas fa-sync-alt"></i> ОБНОВИТЬ
            </button>
        </div>

        <div class="overview-content">
            <div class="system-status">
                <div class="status-indicator">
                    <div class="status-dot status-green"></div>
                    <span>Система защищена</span>
                </div>
                <p>Все компоненты безопасности работают нормально</p>
            </div>

            <div class="threats-section">
                <h3>Последние угрозы</h3>
                <div class="threat-list">
                    <div class="threat-item">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>test_action 23.07.2025 14:27</span>
                    </div>
                    <div class="threat-item">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>login_success 23.07.2025 14:27</span>
                    </div>
                    <div class="threat-item">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>admin_action 23.07.2025 14:27</span>
                    </div>
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
            <a href="/admin/security/logs" class="action-link">
                <i class="fas fa-file-alt"></i>
                <span>Просмотр логов безопасности</span>
            </a>
            <a href="/admin/security/ip-blocking" class="action-link">
                <i class="fas fa-ban"></i>
                <span>Блокировка IP</span>
            </a>
            <a href="/admin/security/blacklist" class="action-link">
                <i class="fas fa-list"></i>
                <span>Управление черным списком</span>
            </a>
            <a href="/admin/security/sessions" class="action-link">
                <i class="fas fa-users"></i>
                <span>Активные сессии</span>
            </a>
            <a href="/admin/security/user-sessions" class="action-link">
                <i class="fas fa-user-clock"></i>
                <span>Управление сессиями пользователей</span>
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