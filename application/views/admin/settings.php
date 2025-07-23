<?php
$page_title = "Настройки системы";
$page_subtitle = "System Settings";
$current_page = "settings";
$page_content = '
<div class="main-header">
    <h1><i class="fas fa-cog"></i> Настройки системы</h1>
    <p>Управление настройками и конфигурацией системы</p>
</div>

<div class="chart-section">
    <div class="chart-header">
        <h3 class="chart-title">
            <i class="fas fa-palette"></i>
            Внешний вид
        </h3>
    </div>
    
    <div class="settings-form">
        <div class="form-group">
            <label for="theme-select">Тема оформления</label>
            <select id="theme-select" class="form-control">
                <option value="cyberpunk">Киберпанк (по умолчанию)</option>
                <option value="dark">Темная</option>
                <option value="light">Светлая</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="background-select">Анимированный фон</label>
            <select id="background-select" class="form-control">
                <option value="matrix">Matrix Rain</option>
                <option value="grid">Cyber Grid</option>
                <option value="holographic">Holographic</option>
                <option value="neural">Neural Network</option>
                <option value="datastream">Data Stream</option>
                <option value="circuit">Circuit Board</option>
                <option value="energy">Energy Field</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="animations-enabled" checked>
                Включить анимации
            </label>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="particles-enabled" checked>
                Показывать частицы
            </label>
        </div>
    </div>
</div>

<div class="chart-section">
    <div class="chart-header">
        <h3 class="chart-title">
            <i class="fas fa-bell"></i>
            Уведомления
        </h3>
    </div>
    
    <div class="settings-form">
        <div class="form-group">
            <label>
                <input type="checkbox" id="email-notifications" checked>
                Email уведомления
            </label>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="browser-notifications" checked>
                Браузерные уведомления
            </label>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="security-alerts" checked>
                Уведомления о безопасности
            </label>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="system-updates" checked>
                Уведомления об обновлениях
            </label>
        </div>
    </div>
</div>

<div class="chart-section">
    <div class="chart-header">
        <h3 class="chart-title">
            <i class="fas fa-shield-alt"></i>
            Безопасность
        </h3>
    </div>
    
    <div class="settings-form">
        <div class="form-group">
            <label for="session-timeout">Таймаут сессии (минуты)</label>
            <input type="number" id="session-timeout" value="30" min="5" max="480">
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="two-factor-auth" checked>
                Двухфакторная аутентификация
            </label>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="login-notifications" checked>
                Уведомления о входе
            </label>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="failed-login-alerts" checked>
                Оповещения о неудачных входах
            </label>
        </div>
    </div>
</div>

<div class="chart-section">
    <div class="chart-header">
        <h3 class="chart-title">
            <i class="fas fa-database"></i>
            Система
        </h3>
    </div>
    
    <div class="settings-form">
        <div class="form-group">
            <label for="data-retention">Хранение данных (дни)</label>
            <input type="number" id="data-retention" value="90" min="7" max="365">
        </div>
        
        <div class="form-group">
            <label for="backup-frequency">Частота резервного копирования</label>
            <select id="backup-frequency">
                <option value="daily">Ежедневно</option>
                <option value="weekly" selected>Еженедельно</option>
                <option value="monthly">Ежемесячно</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="auto-updates" checked>
                Автоматические обновления
            </label>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="debug-mode">
                Режим отладки
            </label>
        </div>
    </div>
</div>

<div class="chart-section">
    <div class="chart-header">
        <h3 class="chart-title">
            <i class="fas fa-language"></i>
            Язык и регион
        </h3>
    </div>
    
    <div class="settings-form">
        <div class="form-group">
            <label for="language-select">Язык интерфейса</label>
            <select id="language-select">
                <option value="ru" selected>Русский</option>
                <option value="en">English</option>
                <option value="de">Deutsch</option>
                <option value="fr">Français</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="timezone-select">Часовой пояс</label>
            <select id="timezone-select">
                <option value="Europe/Moscow" selected>Москва (UTC+3)</option>
                <option value="Europe/London">Лондон (UTC+0)</option>
                <option value="America/New_York">Нью-Йорк (UTC-5)</option>
                <option value="Asia/Tokyo">Токио (UTC+9)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="date-format">Формат даты</label>
            <select id="date-format">
                <option value="DD.MM.YYYY" selected>DD.MM.YYYY</option>
                <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                <option value="YYYY-MM-DD">YYYY-MM-DD</option>
            </select>
        </div>
    </div>
</div>

<div style="text-align: center; margin-top: 30px;">
    <button class="btn btn-blue" onclick="saveSettings()">
        <i class="fas fa-save"></i> Сохранить настройки
    </button>
    <button class="btn" onclick="resetSettings()">
        <i class="fas fa-undo"></i> Сбросить к умолчаниям
    </button>
</div>
';

include __DIR__ . '/layouts/main.php';
?>

<style>
.form-control {
    background: var(--bg-glass);
    border: 1px solid var(--border-subtle);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text-primary);
    font-size: 14px;
    transition: var(--transition-fast);
    width: 100%;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-neon);
    background: var(--bg-glass-hover);
    box-shadow: var(--shadow-neon);
}

.form-control option {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.settings-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    color: var(--text-primary);
    font-weight: 500;
    font-size: 14px;
}

.form-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-right: 8px;
    cursor: pointer;
}

.form-group input[type="number"] {
    background: var(--bg-glass);
    border: 1px solid var(--border-subtle);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text-primary);
    font-size: 14px;
    transition: var(--transition-fast);
}

.form-group input[type="number"]:focus {
    outline: none;
    border-color: var(--primary-neon);
    background: var(--bg-glass-hover);
    box-shadow: var(--shadow-neon);
}
</style>

<script>
function saveSettings() {
    // Собираем все настройки
    const settings = {
        theme: document.getElementById("theme-select").value,
        background: document.getElementById("background-select").value,
        animations: document.getElementById("animations-enabled").checked,
        particles: document.getElementById("particles-enabled").checked,
        emailNotifications: document.getElementById("email-notifications").checked,
        browserNotifications: document.getElementById("browser-notifications").checked,
        securityAlerts: document.getElementById("security-alerts").checked,
        systemUpdates: document.getElementById("system-updates").checked,
        sessionTimeout: document.getElementById("session-timeout").value,
        twoFactorAuth: document.getElementById("two-factor-auth").checked,
        loginNotifications: document.getElementById("login-notifications").checked,
        failedLoginAlerts: document.getElementById("failed-login-alerts").checked,
        dataRetention: document.getElementById("data-retention").value,
        backupFrequency: document.getElementById("backup-frequency").value,
        autoUpdates: document.getElementById("auto-updates").checked,
        debugMode: document.getElementById("debug-mode").checked,
        language: document.getElementById("language-select").value,
        timezone: document.getElementById("timezone-select").value,
        dateFormat: document.getElementById("date-format").value
    };
    
    // Сохраняем в localStorage
    localStorage.setItem("admin-settings", JSON.stringify(settings));
    
    // Обновляем фон если изменился
    if (window.backgroundAnimations && settings.background) {
        window.backgroundAnimations.switchBackground(settings.background);
    }
    
    if (typeof adminPanel !== "undefined") {
        adminPanel.showNotification("Настройки сохранены успешно", "success");
    }
}

function resetSettings() {
    if (confirm("Вы уверены, что хотите сбросить все настройки к значениям по умолчанию?")) {
        // Сбрасываем все поля к значениям по умолчанию
        document.getElementById("theme-select").value = "cyberpunk";
        document.getElementById("background-select").value = "matrix";
        document.getElementById("animations-enabled").checked = true;
        document.getElementById("particles-enabled").checked = true;
        document.getElementById("email-notifications").checked = true;
        document.getElementById("browser-notifications").checked = true;
        document.getElementById("security-alerts").checked = true;
        document.getElementById("system-updates").checked = true;
        document.getElementById("session-timeout").value = "30";
        document.getElementById("two-factor-auth").checked = true;
        document.getElementById("login-notifications").checked = true;
        document.getElementById("failed-login-alerts").checked = true;
        document.getElementById("data-retention").value = "90";
        document.getElementById("backup-frequency").value = "weekly";
        document.getElementById("auto-updates").checked = true;
        document.getElementById("debug-mode").checked = false;
        document.getElementById("language-select").value = "ru";
        document.getElementById("timezone-select").value = "Europe/Moscow";
        document.getElementById("date-format").value = "DD.MM.YYYY";
        
        // Обновляем фон
        if (window.backgroundAnimations) {
            window.backgroundAnimations.switchBackground("matrix");
        }
        
        if (typeof adminPanel !== "undefined") {
            adminPanel.showNotification("Настройки сброшены к значениям по умолчанию", "info");
        }
    }
}

// Загружаем сохраненные настройки при загрузке страницы
document.addEventListener("DOMContentLoaded", () => {
    const savedSettings = localStorage.getItem("admin-settings");
    if (savedSettings) {
        const settings = JSON.parse(savedSettings);
        
        // Применяем сохраненные настройки
        if (settings.theme) document.getElementById("theme-select").value = settings.theme;
        if (settings.background) document.getElementById("background-select").value = settings.background;
        if (settings.animations !== undefined) document.getElementById("animations-enabled").checked = settings.animations;
        if (settings.particles !== undefined) document.getElementById("particles-enabled").checked = settings.particles;
        if (settings.emailNotifications !== undefined) document.getElementById("email-notifications").checked = settings.emailNotifications;
        if (settings.browserNotifications !== undefined) document.getElementById("browser-notifications").checked = settings.browserNotifications;
        if (settings.securityAlerts !== undefined) document.getElementById("security-alerts").checked = settings.securityAlerts;
        if (settings.systemUpdates !== undefined) document.getElementById("system-updates").checked = settings.systemUpdates;
        if (settings.sessionTimeout) document.getElementById("session-timeout").value = settings.sessionTimeout;
        if (settings.twoFactorAuth !== undefined) document.getElementById("two-factor-auth").checked = settings.twoFactorAuth;
        if (settings.loginNotifications !== undefined) document.getElementById("login-notifications").checked = settings.loginNotifications;
        if (settings.failedLoginAlerts !== undefined) document.getElementById("failed-login-alerts").checked = settings.failedLoginAlerts;
        if (settings.dataRetention) document.getElementById("data-retention").value = settings.dataRetention;
        if (settings.backupFrequency) document.getElementById("backup-frequency").value = settings.backupFrequency;
        if (settings.autoUpdates !== undefined) document.getElementById("auto-updates").checked = settings.autoUpdates;
        if (settings.debugMode !== undefined) document.getElementById("debug-mode").checked = settings.debugMode;
        if (settings.language) document.getElementById("language-select").value = settings.language;
        if (settings.timezone) document.getElementById("timezone-select").value = settings.timezone;
        if (settings.dateFormat) document.getElementById("date-format").value = settings.dateFormat;
    }
});
</script>