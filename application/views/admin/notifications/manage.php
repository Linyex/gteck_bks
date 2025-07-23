<?php
/**
 * Представление для управления уведомлениями
 * Система уведомлений - управление настройками и отправкой
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Главная</a></li>
                        <li class="breadcrumb-item active">Управление уведомлениями</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-bell text-primary me-2"></i>
                    Управление уведомлениями
                </h4>
            </div>
        </div>
    </div>

    <!-- Статистика уведомлений -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-primary-lighten text-primary rounded">
                                    <i class="fas fa-paper-plane"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="totalNotifications">0</h5>
                            <p class="text-muted mb-0">Всего отправлено</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-success-lighten text-success rounded">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="successNotifications">0</h5>
                            <p class="text-muted mb-0">Успешно доставлено</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-warning-lighten text-warning rounded">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="pendingNotifications">0</h5>
                            <p class="text-muted mb-0">В ожидании</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-danger-lighten text-danger rounded">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="failedNotifications">0</h5>
                            <p class="text-muted mb-0">Ошибки доставки</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Настройки уведомлений -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-cog text-primary me-2"></i>
                        Настройки уведомлений
                    </h4>
                </div>
                <div class="card-body">
                    <form id="notificationSettingsForm">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Email уведомления</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">SMTP сервер</label>
                                    <input type="text" class="form-control" id="smtpServer" 
                                           value="smtp.gmail.com" placeholder="smtp.gmail.com">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">SMTP порт</label>
                                    <input type="number" class="form-control" id="smtpPort" 
                                           value="587" placeholder="587">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email отправителя</label>
                                    <input type="email" class="form-control" id="senderEmail" 
                                           placeholder="noreply@example.com">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Пароль приложения</label>
                                    <input type="password" class="form-control" id="senderPassword" 
                                           placeholder="Пароль приложения">
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="emailEnabled" checked>
                                        <label class="form-check-label" for="emailEnabled">
                                            Включить email уведомления
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-3">Telegram уведомления</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Telegram Bot Token</label>
                                    <input type="text" class="form-control" id="telegramToken" 
                                           placeholder="1234567890:ABCdefGHIjklMNOpqrsTUVwxyz">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Chat ID администратора</label>
                                    <input type="text" class="form-control" id="telegramChatId" 
                                           placeholder="123456789">
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="telegramEnabled" checked>
                                        <label class="form-check-label" for="telegramEnabled">
                                            Включить Telegram уведомления
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="testTelegramConnection()">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Тест подключения
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">Типы уведомлений</h6>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="notifyLoginAttempts" checked>
                                            <label class="form-check-label" for="notifyLoginAttempts">
                                                Попытки входа
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="notifySuspiciousActivity" checked>
                                            <label class="form-check-label" for="notifySuspiciousActivity">
                                                Подозрительная активность
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="notifySystemErrors" checked>
                                            <label class="form-check-label" for="notifySystemErrors">
                                                Ошибки системы
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="notifySecurityAlerts" checked>
                                            <label class="form-check-label" for="notifySecurityAlerts">
                                                Безопасность
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="notifyAnalytics" checked>
                                            <label class="form-check-label" for="notifyAnalytics">
                                                Аналитика
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="notifyReports" checked>
                                            <label class="form-check-label" for="notifyReports">
                                                Отчеты
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Сохранить настройки
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="loadSettings()">
                                    <i class="fas fa-refresh me-1"></i>
                                    Загрузить настройки
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-paper-plane text-success me-2"></i>
                        Тестовая отправка
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Тип уведомления</label>
                        <select class="form-select" id="testNotificationType">
                            <option value="test">Тестовое уведомление</option>
                            <option value="security">Безопасность</option>
                            <option value="analytics">Аналитика</option>
                            <option value="error">Ошибка</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Сообщение</label>
                        <textarea class="form-control" id="testMessage" rows="3" 
                                  placeholder="Введите тестовое сообщение...">Это тестовое уведомление от системы аналитики.</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="testEmail" checked>
                            <label class="form-check-label" for="testEmail">
                                Отправить на email
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="testTelegram" checked>
                            <label class="form-check-label" for="testTelegram">
                                Отправить в Telegram
                            </label>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-success w-100" onclick="sendTestNotification()">
                        <i class="fas fa-paper-plane me-1"></i>
                        Отправить тестовое уведомление
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- История уведомлений -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-history text-info me-2"></i>
                        История уведомлений
                    </h4>
                    <div class="card-header-actions">
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshNotifications()">
                            <i class="fas fa-sync-alt me-1"></i>
                            Обновить
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="exportNotifications()">
                            <i class="fas fa-download me-1"></i>
                            Экспорт
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Тип</th>
                                    <th>Канал</th>
                                    <th>Получатель</th>
                                    <th>Сообщение</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody id="notificationsHistory">
                                <!-- Данные будут загружены динамически -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Пагинация -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <nav aria-label="Навигация по уведомлениям">
                                <ul class="pagination justify-content-center" id="notificationsPagination">
                                    <!-- Пагинация будет загружена динамически -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для детального просмотра -->
<div class="modal fade" id="notificationDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Детали уведомления
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="notificationDetailContent">
                <!-- Содержимое будет загружено динамически -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="resendNotification()">
                    <i class="fas fa-redo me-1"></i>
                    Отправить повторно
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Текущая страница для пагинации
let currentPage = 1;
const itemsPerPage = 20;

// Загрузка данных при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    loadNotificationStats();
    loadSettings();
    loadNotificationsHistory();
});

// Загрузка статистики уведомлений
function loadNotificationStats() {
    fetch('/admin/api/notification-stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalNotifications').textContent = data.total || 0;
            document.getElementById('successNotifications').textContent = data.success || 0;
            document.getElementById('pendingNotifications').textContent = data.pending || 0;
            document.getElementById('failedNotifications').textContent = data.failed || 0;
        })
        .catch(error => {
            console.error('Ошибка загрузки статистики:', error);
        });
}

// Загрузка настроек
function loadSettings() {
    fetch('/admin/api/notification-settings')
        .then(response => response.json())
        .then(data => {
            // Email настройки
            document.getElementById('smtpServer').value = data.email?.smtp_server || '';
            document.getElementById('smtpPort').value = data.email?.smtp_port || 587;
            document.getElementById('senderEmail').value = data.email?.sender_email || '';
            document.getElementById('emailEnabled').checked = data.email?.enabled || false;
            
            // Telegram настройки
            document.getElementById('telegramToken').value = data.telegram?.bot_token || '';
            document.getElementById('telegramChatId').value = data.telegram?.chat_id || '';
            document.getElementById('telegramEnabled').checked = data.telegram?.enabled || false;
            
            // Типы уведомлений
            document.getElementById('notifyLoginAttempts').checked = data.types?.login_attempts || false;
            document.getElementById('notifySuspiciousActivity').checked = data.types?.suspicious_activity || false;
            document.getElementById('notifySystemErrors').checked = data.types?.system_errors || false;
            document.getElementById('notifySecurityAlerts').checked = data.types?.security_alerts || false;
            document.getElementById('notifyAnalytics').checked = data.types?.analytics || false;
            document.getElementById('notifyReports').checked = data.types?.reports || false;
        })
        .catch(error => {
            console.error('Ошибка загрузки настроек:', error);
        });
}

// Сохранение настроек
document.getElementById('notificationSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const settings = {
        email: {
            smtp_server: document.getElementById('smtpServer').value,
            smtp_port: parseInt(document.getElementById('smtpPort').value),
            sender_email: document.getElementById('senderEmail').value,
            sender_password: document.getElementById('senderPassword').value,
            enabled: document.getElementById('emailEnabled').checked
        },
        telegram: {
            bot_token: document.getElementById('telegramToken').value,
            chat_id: document.getElementById('telegramChatId').value,
            enabled: document.getElementById('telegramEnabled').checked
        },
        types: {
            login_attempts: document.getElementById('notifyLoginAttempts').checked,
            suspicious_activity: document.getElementById('notifySuspiciousActivity').checked,
            system_errors: document.getElementById('notifySystemErrors').checked,
            security_alerts: document.getElementById('notifySecurityAlerts').checked,
            analytics: document.getElementById('notifyAnalytics').checked,
            reports: document.getElementById('notifyReports').checked
        }
    };
    
    fetch('/admin/api/save-notification-settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Настройки успешно сохранены');
        } else {
            showError('Ошибка при сохранении настроек');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showError('Ошибка при сохранении настроек');
    });
});

// Тест подключения к Telegram
function testTelegramConnection() {
    const token = document.getElementById('telegramToken').value;
    const chatId = document.getElementById('telegramChatId').value;
    
    if (!token || !chatId) {
        showError('Заполните токен бота и Chat ID');
        return;
    }
    
    fetch('/admin/api/test-telegram-connection', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ token, chat_id: chatId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Подключение к Telegram успешно установлено');
        } else {
            showError(data.message || 'Ошибка подключения к Telegram');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showError('Ошибка при тестировании подключения');
    });
}

// Отправка тестового уведомления
function sendTestNotification() {
    const type = document.getElementById('testNotificationType').value;
    const message = document.getElementById('testMessage').value;
    const sendEmail = document.getElementById('testEmail').checked;
    const sendTelegram = document.getElementById('testTelegram').checked;
    
    if (!message.trim()) {
        showError('Введите сообщение для отправки');
        return;
    }
    
    if (!sendEmail && !sendTelegram) {
        showError('Выберите хотя бы один канал отправки');
        return;
    }
    
    fetch('/admin/api/send-test-notification', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            type,
            message,
            channels: {
                email: sendEmail,
                telegram: sendTelegram
            }
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Тестовое уведомление отправлено');
            loadNotificationsHistory(); // Обновляем историю
        } else {
            showError(data.message || 'Ошибка отправки уведомления');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showError('Ошибка при отправке уведомления');
    });
}

// Загрузка истории уведомлений
function loadNotificationsHistory(page = 1) {
    currentPage = page;
    
    fetch(`/admin/api/notifications-history?page=${page}&limit=${itemsPerPage}`)
        .then(response => response.json())
        .then(data => {
            updateNotificationsTable(data.notifications);
            updateNotificationsPagination(data.total, data.current_page, data.total_pages);
        })
        .catch(error => {
            console.error('Ошибка загрузки истории:', error);
            showError('Ошибка загрузки истории уведомлений');
        });
}

// Обновление таблицы уведомлений
function updateNotificationsTable(notifications) {
    const tbody = document.getElementById('notificationsHistory');
    tbody.innerHTML = '';
    
    notifications.forEach(notification => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatDateTime(notification.created_at)}</td>
            <td>
                <span class="badge bg-${getNotificationTypeColor(notification.type)}">
                    ${getNotificationTypeLabel(notification.type)}
                </span>
            </td>
            <td>
                <span class="badge bg-${notification.channel === 'email' ? 'primary' : 'info'}">
                    ${notification.channel === 'email' ? 'Email' : 'Telegram'}
                </span>
            </td>
            <td>${notification.recipient}</td>
            <td>
                <div class="text-truncate" style="max-width: 200px;" title="${notification.message}">
                    ${notification.message}
                </div>
            </td>
            <td>
                <span class="badge bg-${getStatusColor(notification.status)}">
                    ${getStatusLabel(notification.status)}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="viewNotificationDetail(${notification.id})">
                    <i class="fas fa-eye"></i>
                </button>
                ${notification.status === 'failed' ? `
                    <button class="btn btn-sm btn-outline-warning" onclick="resendNotification(${notification.id})">
                        <i class="fas fa-redo"></i>
                    </button>
                ` : ''}
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Обновление пагинации
function updateNotificationsPagination(total, currentPage, totalPages) {
    const pagination = document.getElementById('notificationsPagination');
    pagination.innerHTML = '';
    
    // Кнопка "Предыдущая"
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage <= 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `
        <a class="page-link" href="#" onclick="loadNotificationsHistory(${currentPage - 1})">
            <i class="fas fa-chevron-left"></i>
        </a>
    `;
    pagination.appendChild(prevLi);
    
    // Номера страниц
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="loadNotificationsHistory(${i})">${i}</a>`;
            pagination.appendChild(li);
        } else if (i === currentPage - 3 || i === currentPage + 3) {
            const li = document.createElement('li');
            li.className = 'page-item disabled';
            li.innerHTML = '<span class="page-link">...</span>';
            pagination.appendChild(li);
        }
    }
    
    // Кнопка "Следующая"
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage >= totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `
        <a class="page-link" href="#" onclick="loadNotificationsHistory(${currentPage + 1})">
            <i class="fas fa-chevron-right"></i>
        </a>
    `;
    pagination.appendChild(nextLi);
}

// Просмотр деталей уведомления
function viewNotificationDetail(notificationId) {
    fetch(`/admin/api/notification-detail/${notificationId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('notificationDetailContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Основная информация</h6>
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Тип:</strong> ${getNotificationTypeLabel(data.type)}</p>
                        <p><strong>Канал:</strong> ${data.channel === 'email' ? 'Email' : 'Telegram'}</p>
                        <p><strong>Получатель:</strong> ${data.recipient}</p>
                        <p><strong>Статус:</strong> ${getStatusLabel(data.status)}</p>
                        <p><strong>Создано:</strong> ${formatDateTime(data.created_at)}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Детали отправки</h6>
                        <p><strong>Отправлено:</strong> ${data.sent_at ? formatDateTime(data.sent_at) : 'Не отправлено'}</p>
                        <p><strong>Попыток:</strong> ${data.attempts || 0}</p>
                        <p><strong>Ошибка:</strong> ${data.error_message || 'Нет'}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6>Сообщение</h6>
                        <div class="alert alert-info">
                            ${data.message}
                        </div>
                    </div>
                </div>
            `;
            
            // Сохраняем ID для повторной отправки
            document.getElementById('notificationDetailModal').dataset.notificationId = notificationId;
            
            const modal = new bootstrap.Modal(document.getElementById('notificationDetailModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Ошибка загрузки деталей:', error);
            showError('Ошибка загрузки детальной информации');
        });
}

// Повторная отправка уведомления
function resendNotification(notificationId = null) {
    const id = notificationId || document.getElementById('notificationDetailModal').dataset.notificationId;
    
    if (!id) {
        showError('ID уведомления не найден');
        return;
    }
    
    if (confirm('Отправить уведомление повторно?')) {
        fetch(`/admin/api/resend-notification/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Уведомление отправлено повторно');
                loadNotificationsHistory(currentPage); // Обновляем текущую страницу
                const modal = bootstrap.Modal.getInstance(document.getElementById('notificationDetailModal'));
                if (modal) modal.hide();
            } else {
                showError(data.message || 'Ошибка повторной отправки');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            showError('Ошибка при повторной отправке');
        });
    }
}

// Обновление истории
function refreshNotifications() {
    loadNotificationStats();
    loadNotificationsHistory(currentPage);
}

// Экспорт уведомлений
function exportNotifications() {
    window.open('/admin/api/export-notifications', '_blank');
}

// Вспомогательные функции
function getNotificationTypeColor(type) {
    const colors = {
        'test': 'secondary',
        'security': 'danger',
        'analytics': 'info',
        'error': 'warning',
        'login_attempts': 'primary',
        'suspicious_activity': 'danger'
    };
    return colors[type] || 'secondary';
}

function getNotificationTypeLabel(type) {
    const labels = {
        'test': 'Тест',
        'security': 'Безопасность',
        'analytics': 'Аналитика',
        'error': 'Ошибка',
        'login_attempts': 'Вход',
        'suspicious_activity': 'Подозрительно'
    };
    return labels[type] || type;
}

function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'sent': 'success',
        'failed': 'danger',
        'delivered': 'success'
    };
    return colors[status] || 'secondary';
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'В ожидании',
        'sent': 'Отправлено',
        'failed': 'Ошибка',
        'delivered': 'Доставлено'
    };
    return labels[status] || status;
}

function formatDateTime(dateString) {
    return new Date(dateString).toLocaleString('ru-RU');
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Успешно!',
        text: message,
        timer: 3000
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Ошибка!',
        text: message
    });
}
</script>

<style>
.card-header-actions {
    display: flex;
    gap: 0.5rem;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.alert {
    border-radius: 0.375rem;
    border: 1px solid transparent;
}

.alert-info {
    color: #055160;
    background-color: #cff4fc;
    border-color: #b6effb;
}

.pagination .page-link {
    color: #3b7ddd;
}

.pagination .page-item.active .page-link {
    background-color: #3b7ddd;
    border-color: #3b7ddd;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
}
</style> 