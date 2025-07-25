<?php
/**
 * Страница настроек мониторинга
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Настройки мониторинга</h1>
                <div class="page-actions">
                    <button class="btn btn-primary" onclick="saveSettings()">
                        <i class="fas fa-save"></i> Сохранить настройки
                    </button>
                    <button class="btn btn-secondary" onclick="resetSettings()">
                        <i class="fas fa-undo"></i> Сбросить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Настройки логирования</h5>
                </div>
                <div class="card-body">
                    <form id="loggingSettingsForm">
                        <div class="form-group">
                            <label for="logLevel">Уровень логирования</label>
                            <select class="form-control" id="logLevel">
                                <option value="debug">Debug</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="error">Error</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="logRetention">Хранение логов (дни)</label>
                            <input type="number" class="form-control" id="logRetention" value="90" min="1" max="365">
                        </div>
                        <div class="form-group">
                            <label for="logRotation">Ротация логов</label>
                            <select class="form-control" id="logRotation">
                                <option value="daily">Ежедневно</option>
                                <option value="weekly">Еженедельно</option>
                                <option value="monthly">Ежемесячно</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="enableFileLogging" checked>
                            <label class="form-check-label" for="enableFileLogging">
                                Включить запись в файлы
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Настройки безопасности</h5>
                </div>
                <div class="card-body">
                    <form id="securitySettingsForm">
                        <div class="form-group">
                            <label for="failedLoginThreshold">Порог неудачных входов</label>
                            <input type="number" class="form-control" id="failedLoginThreshold" value="5" min="1" max="20">
                        </div>
                        <div class="form-group">
                            <label for="blockDuration">Длительность блокировки (минуты)</label>
                            <input type="number" class="form-control" id="blockDuration" value="30" min="5" max="1440">
                        </div>
                        <div class="form-group">
                            <label for="suspiciousActivityThreshold">Порог подозрительной активности</label>
                            <input type="number" class="form-control" id="suspiciousActivityThreshold" value="10" min="1" max="100">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="enableAutoBlocking" checked>
                            <label class="form-check-label" for="enableAutoBlocking">
                                Автоматическая блокировка IP
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="enableEmailAlerts" checked>
                            <label class="form-check-label" for="enableEmailAlerts">
                                Email уведомления
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Настройки мониторинга</h5>
                </div>
                <div class="card-body">
                    <form id="monitoringSettingsForm">
                        <div class="form-group">
                            <label for="monitoringInterval">Интервал проверки (секунды)</label>
                            <input type="number" class="form-control" id="monitoringInterval" value="60" min="10" max="3600">
                        </div>
                        <div class="form-group">
                            <label for="alertThreshold">Порог для уведомлений</label>
                            <select class="form-control" id="alertThreshold">
                                <option value="low">Низкий</option>
                                <option value="medium">Средний</option>
                                <option value="high">Высокий</option>
                                <option value="critical">Критический</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="enableRealTimeMonitoring" checked>
                            <label class="form-check-label" for="enableRealTimeMonitoring">
                                Мониторинг в реальном времени
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="enablePerformanceMonitoring" checked>
                            <label class="form-check-label" for="enablePerformanceMonitoring">
                                Мониторинг производительности
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Настройки уведомлений</h5>
                </div>
                <div class="card-body">
                    <form id="notificationSettingsForm">
                        <div class="form-group">
                            <label for="notificationEmail">Email для уведомлений</label>
                            <input type="email" class="form-control" id="notificationEmail" value="admin@nocontrgtec.com">
                        </div>
                        <div class="form-group">
                            <label for="notificationLevel">Уровень уведомлений</label>
                            <select class="form-control" id="notificationLevel">
                                <option value="all">Все</option>
                                <option value="critical">Только критические</option>
                                <option value="high">Высокий и выше</option>
                                <option value="medium">Средний и выше</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="enableBrowserNotifications" checked>
                            <label class="form-check-label" for="enableBrowserNotifications">
                                Уведомления в браузере
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="enableSoundAlerts">
                            <label class="form-check-label" for="enableSoundAlerts">
                                Звуковые уведомления
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Действия</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <button class="btn btn-warning" onclick="clearLogs()">
                            <i class="fas fa-trash"></i> Очистить логи
                        </button>
                        <button class="btn btn-info" onclick="exportSettings()">
                            <i class="fas fa-download"></i> Экспорт настроек
                        </button>
                        <button class="btn btn-secondary" onclick="importSettings()">
                            <i class="fas fa-upload"></i> Импорт настроек
                        </button>
                        <button class="btn btn-danger" onclick="resetToDefaults()">
                            <i class="fas fa-undo-alt"></i> Сбросить к значениям по умолчанию
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function saveSettings() {
    // Собираем все настройки
    const settings = {
        logging: {
            level: document.getElementById('logLevel').value,
            retention: document.getElementById('logRetention').value,
            rotation: document.getElementById('logRotation').value,
            enableFileLogging: document.getElementById('enableFileLogging').checked
        },
        security: {
            failedLoginThreshold: document.getElementById('failedLoginThreshold').value,
            blockDuration: document.getElementById('blockDuration').value,
            suspiciousActivityThreshold: document.getElementById('suspiciousActivityThreshold').value,
            enableAutoBlocking: document.getElementById('enableAutoBlocking').checked,
            enableEmailAlerts: document.getElementById('enableEmailAlerts').checked
        },
        monitoring: {
            interval: document.getElementById('monitoringInterval').value,
            alertThreshold: document.getElementById('alertThreshold').value,
            enableRealTimeMonitoring: document.getElementById('enableRealTimeMonitoring').checked,
            enablePerformanceMonitoring: document.getElementById('enablePerformanceMonitoring').checked
        },
        notifications: {
            email: document.getElementById('notificationEmail').value,
            level: document.getElementById('notificationLevel').value,
            enableBrowserNotifications: document.getElementById('enableBrowserNotifications').checked,
            enableSoundAlerts: document.getElementById('enableSoundAlerts').checked
        }
    };

    // Отправляем настройки на сервер
    fetch('/admin/monitoring/settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Настройки сохранены успешно');
        } else {
            showAlert('error', 'Ошибка при сохранении настроек');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Ошибка при сохранении настроек');
    });
}

function resetSettings() {
    // Сброс к текущим значениям
    location.reload();
}

function clearLogs() {
    if (confirm('Вы уверены, что хотите очистить все логи?')) {
        fetch('/admin/monitoring/clear-logs', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Логи очищены успешно');
            } else {
                showAlert('error', 'Ошибка при очистке логов');
            }
        });
    }
}

function exportSettings() {
    // Экспорт настроек
    console.log('Экспорт настроек...');
}

function importSettings() {
    // Импорт настроек
    console.log('Импорт настроек...');
}

function resetToDefaults() {
    if (confirm('Вы уверены, что хотите сбросить все настройки к значениям по умолчанию?')) {
        // Сброс к значениям по умолчанию
        console.log('Сброс к значениям по умолчанию...');
    }
}

function showAlert(type, message) {
    // Показ уведомления
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row'));
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script> 