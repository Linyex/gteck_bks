<?php
$page_title = "Настройки системы";
$page_subtitle = "System Settings";
$current_page = "settings";
$page_content = '
<div class="main-header">
    <h1><i class="fas fa-cog"></i> Настройки системы</h1>
    <p>Управление настройками и конфигурацией системы</p>
</div>

<div class="settings-form">
    <form action="/admin/settings" method="POST">
        <div class="form-section">
            <h3><i class="fas fa-globe"></i> Основные настройки</h3>
            
            <div class="form-group">
                <label for="site_name">Название сайта</label>
                <input type="text" id="site_name" name="site_name" value="' . htmlspecialchars($settings['site_name'] ?? 'NoContrGtec') . '" required>
            </div>
            
            <div class="form-group">
                <label for="site_description">Описание сайта</label>
                <textarea id="site_description" name="site_description" rows="3">' . htmlspecialchars($settings['site_description'] ?? '') . '</textarea>
            </div>
            
            <div class="form-group">
                <label for="admin_email">Email администратора</label>
                <input type="email" id="admin_email" name="admin_email" value="' . htmlspecialchars($settings['admin_email'] ?? '') . '" required>
            </div>
        </div>
        
        <div class="form-section">
            <h3><i class="fas fa-upload"></i> Настройки загрузки</h3>
            
            <div class="form-group">
                <label for="max_file_size">Максимальный размер файла (МБ)</label>
                <input type="number" id="max_file_size" name="max_file_size" value="' . ($settings['max_file_size'] ?? 10) . '" min="1" max="100">
            </div>
            
            <div class="form-group">
                <label for="allowed_file_types">Разрешенные типы файлов</label>
                <input type="text" id="allowed_file_types" name="allowed_file_types" value="' . htmlspecialchars($settings['allowed_file_types'] ?? 'jpg,jpeg,png,gif,pdf,doc,docx') . '" placeholder="jpg,jpeg,png,gif,pdf,doc,docx">
            </div>
        </div>
        
        <div class="form-section">
            <h3><i class="fas fa-users"></i> Настройки пользователей</h3>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="enable_registration" name="enable_registration" ' . (($settings['enable_registration'] ?? 1) ? 'checked' : '') . '>
                    Разрешить регистрацию новых пользователей
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="enable_notifications" name="enable_notifications" ' . (($settings['enable_notifications'] ?? 1) ? 'checked' : '') . '>
                    Включить уведомления
                </label>
            </div>
            
            <div class="form-group">
                <label for="session_timeout">Таймаут сессии (секунды)</label>
                <input type="number" id="session_timeout" name="session_timeout" value="' . ($settings['session_timeout'] ?? 3600) . '" min="300" max="86400">
            </div>
        </div>
        
        <div class="form-section">
            <h3><i class="fas fa-tools"></i> Системные настройки</h3>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" ' . (($settings['maintenance_mode'] ?? 0) ? 'checked' : '') . '>
                    Режим обслуживания
                </label>
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-rocket"></i> Производительность</h3>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="enable_lazy_loading" name="enable_lazy_loading" ' . (($settings['enable_lazy_loading'] ?? 1) ? 'checked' : '') . '>
                    Включить Lazy Loading изображений
                </label>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="enable_service_worker" name="enable_service_worker" ' . (($settings['enable_service_worker'] ?? 1) ? 'checked' : '') . '>
                    Включить Service Worker (офлайн кэш)
                </label>
            </div>

            <div class="form-group">
                <label for="cache_max_age">Максимальный размер кэша (секунды)</label>
                <input type="number" id="cache_max_age" name="cache_max_age" value="' . ($settings['cache_max_age'] ?? 86400) . '" min="0" max="31536000">
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-shield-alt"></i> Безопасность</h3>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="security_enforce_https" name="security_enforce_https" ' . (($settings['security_enforce_https'] ?? 0) ? 'checked' : '') . '>
                    Принудительное перенаправление на HTTPS
                </label>
            </div>

            <div class="form-group">
                <label for="security_content_security_policy">Content Security Policy (CSP)</label>
                <textarea id="security_content_security_policy" name="security_content_security_policy" rows="3">' . htmlspecialchars($settings['security_content_security_policy'] ?? "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'") . '</textarea>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-blue">
                <i class="fas fa-save"></i> Сохранить настройки
            </button>
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-undo"></i> Сбросить
            </button>
        </div>
    </form>
</div>';
?> 