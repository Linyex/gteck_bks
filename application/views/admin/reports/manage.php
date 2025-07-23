<?php
/**
 * Представление для управления отчетами
 * Система отчетов - настройка и управление автоматическими отчетами
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Главная</a></li>
                        <li class="breadcrumb-item active">Управление отчетами</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Управление отчетами
                </h4>
            </div>
        </div>
    </div>

    <!-- Статистика отчетов -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-primary-lighten text-primary rounded">
                                    <i class="fas fa-file-alt"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="totalReports">0</h5>
                            <p class="text-muted mb-0">Всего отчетов</p>
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
                                    <i class="fas fa-clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="scheduledReports">0</h5>
                            <p class="text-muted mb-0">Запланировано</p>
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
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="lastReportDate">-</h5>
                            <p class="text-muted mb-0">Последний отчет</p>
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
                                <span class="avatar-title bg-info-lighten text-info rounded">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="reportRecipients">0</h5>
                            <p class="text-muted mb-0">Получателей</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Настройки отчетов -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-cog text-primary me-2"></i>
                        Настройки автоматических отчетов
                    </h4>
                </div>
                <div class="card-body">
                    <form id="reportSettingsForm">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Ежедневные отчеты</h6>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dailyReportsEnabled" checked>
                                        <label class="form-check-label" for="dailyReportsEnabled">
                                            Включить ежедневные отчеты
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Время отправки</label>
                                    <input type="time" class="form-control" id="dailyReportTime" value="09:00">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Получатели (email)</label>
                                    <textarea class="form-control" id="dailyReportRecipients" rows="3" 
                                              placeholder="admin@example.com, manager@example.com"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Типы отчетов</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dailySecurityReport" checked>
                                        <label class="form-check-label" for="dailySecurityReport">
                                            Безопасность
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dailyAnalyticsReport" checked>
                                        <label class="form-check-label" for="dailyAnalyticsReport">
                                            Аналитика
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dailyActivityReport" checked>
                                        <label class="form-check-label" for="dailyActivityReport">
                                            Активность пользователей
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-3">Еженедельные отчеты</h6>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="weeklyReportsEnabled" checked>
                                        <label class="form-check-label" for="weeklyReportsEnabled">
                                            Включить еженедельные отчеты
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">День недели</label>
                                    <select class="form-select" id="weeklyReportDay">
                                        <option value="1">Понедельник</option>
                                        <option value="2">Вторник</option>
                                        <option value="3">Среда</option>
                                        <option value="4">Четверг</option>
                                        <option value="5">Пятница</option>
                                        <option value="6">Суббота</option>
                                        <option value="0">Воскресенье</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Время отправки</label>
                                    <input type="time" class="form-control" id="weeklyReportTime" value="10:00">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Получатели (email)</label>
                                    <textarea class="form-control" id="weeklyReportRecipients" rows="3" 
                                              placeholder="admin@example.com, director@example.com"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Формат отчета</label>
                                    <select class="form-select" id="weeklyReportFormat">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel</option>
                                        <option value="html">HTML</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">Дополнительные настройки</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Включить графики</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="includeCharts" checked>
                                                <label class="form-check-label" for="includeCharts">
                                                    Добавлять графики в отчеты
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Включить рекомендации</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="includeRecommendations" checked>
                                                <label class="form-check-label" for="includeRecommendations">
                                                    Добавлять рекомендации по безопасности
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Уведомления об ошибках</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="notifyReportErrors" checked>
                                                <label class="form-check-label" for="notifyReportErrors">
                                                    Уведомлять об ошибках генерации
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Архивирование</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="archiveReports" checked>
                                                <label class="form-check-label" for="archiveReports">
                                                    Архивировать старые отчеты
                                                </label>
                                            </div>
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
                                <button type="button" class="btn btn-outline-secondary" onclick="loadReportSettings()">
                                    <i class="fas fa-refresh me-1"></i>
                                    Загрузить настройки
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="generateTestReport()">
                                    <i class="fas fa-file-alt me-1"></i>
                                    Создать тестовый отчет
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
                        <i class="fas fa-calendar-alt text-success me-2"></i>
                        Расписание отчетов
                    </h4>
                </div>
                <div class="card-body">
                    <div id="reportSchedule">
                        <!-- Расписание будет загружено динамически -->
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6>Быстрые действия</h6>
                        <button class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="generateSecurityReport()">
                            <i class="fas fa-shield-alt me-1"></i>
                            Отчет по безопасности
                        </button>
                        <button class="btn btn-outline-info btn-sm w-100 mb-2" onclick="generateAnalyticsReport()">
                            <i class="fas fa-chart-line me-1"></i>
                            Аналитический отчет
                        </button>
                        <button class="btn btn-outline-warning btn-sm w-100 mb-2" onclick="generateActivityReport()">
                            <i class="fas fa-users me-1"></i>
                            Отчет по активности
                        </button>
                        <button class="btn btn-outline-secondary btn-sm w-100" onclick="exportAllReports()">
                            <i class="fas fa-download me-1"></i>
                            Экспорт всех отчетов
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- История отчетов -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-history text-info me-2"></i>
                        История отчетов
                    </h4>
                    <div class="card-header-actions">
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshReports()">
                            <i class="fas fa-sync-alt me-1"></i>
                            Обновить
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="exportReportsHistory()">
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
                                    <th>Дата создания</th>
                                    <th>Тип отчета</th>
                                    <th>Формат</th>
                                    <th>Размер</th>
                                    <th>Статус</th>
                                    <th>Получатели</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody id="reportsHistory">
                                <!-- Данные будут загружены динамически -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Пагинация -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <nav aria-label="Навигация по отчетам">
                                <ul class="pagination justify-content-center" id="reportsPagination">
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

<!-- Модальное окно для предварительного просмотра -->
<div class="modal fade" id="reportPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye text-primary me-2"></i>
                    Предварительный просмотр отчета
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reportPreviewContent">
                <!-- Содержимое будет загружено динамически -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="downloadReport()">
                    <i class="fas fa-download me-1"></i>
                    Скачать
                </button>
                <button type="button" class="btn btn-success" onclick="sendReport()">
                    <i class="fas fa-paper-plane me-1"></i>
                    Отправить
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Текущая страница для пагинации
let currentPage = 1;
const itemsPerPage = 15;
let currentReportId = null;

// Загрузка данных при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    loadReportStats();
    loadReportSettings();
    loadReportSchedule();
    loadReportsHistory();
});

// Загрузка статистики отчетов
function loadReportStats() {
    fetch('/admin/api/report-stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalReports').textContent = data.total || 0;
            document.getElementById('scheduledReports').textContent = data.scheduled || 0;
            document.getElementById('lastReportDate').textContent = data.last_report_date || '-';
            document.getElementById('reportRecipients').textContent = data.recipients || 0;
        })
        .catch(error => {
            console.error('Ошибка загрузки статистики:', error);
        });
}

// Загрузка настроек отчетов
function loadReportSettings() {
    fetch('/admin/api/report-settings')
        .then(response => response.json())
        .then(data => {
            // Ежедневные отчеты
            document.getElementById('dailyReportsEnabled').checked = data.daily?.enabled || false;
            document.getElementById('dailyReportTime').value = data.daily?.time || '09:00';
            document.getElementById('dailyReportRecipients').value = data.daily?.recipients || '';
            document.getElementById('dailySecurityReport').checked = data.daily?.types?.security || false;
            document.getElementById('dailyAnalyticsReport').checked = data.daily?.types?.analytics || false;
            document.getElementById('dailyActivityReport').checked = data.daily?.types?.activity || false;
            
            // Еженедельные отчеты
            document.getElementById('weeklyReportsEnabled').checked = data.weekly?.enabled || false;
            document.getElementById('weeklyReportDay').value = data.weekly?.day || 1;
            document.getElementById('weeklyReportTime').value = data.weekly?.time || '10:00';
            document.getElementById('weeklyReportRecipients').value = data.weekly?.recipients || '';
            document.getElementById('weeklyReportFormat').value = data.weekly?.format || 'pdf';
            
            // Дополнительные настройки
            document.getElementById('includeCharts').checked = data.settings?.include_charts || false;
            document.getElementById('includeRecommendations').checked = data.settings?.include_recommendations || false;
            document.getElementById('notifyReportErrors').checked = data.settings?.notify_errors || false;
            document.getElementById('archiveReports').checked = data.settings?.archive_reports || false;
        })
        .catch(error => {
            console.error('Ошибка загрузки настроек:', error);
        });
}

// Сохранение настроек отчетов
document.getElementById('reportSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const settings = {
        daily: {
            enabled: document.getElementById('dailyReportsEnabled').checked,
            time: document.getElementById('dailyReportTime').value,
            recipients: document.getElementById('dailyReportRecipients').value,
            types: {
                security: document.getElementById('dailySecurityReport').checked,
                analytics: document.getElementById('dailyAnalyticsReport').checked,
                activity: document.getElementById('dailyActivityReport').checked
            }
        },
        weekly: {
            enabled: document.getElementById('weeklyReportsEnabled').checked,
            day: parseInt(document.getElementById('weeklyReportDay').value),
            time: document.getElementById('weeklyReportTime').value,
            recipients: document.getElementById('weeklyReportRecipients').value,
            format: document.getElementById('weeklyReportFormat').value
        },
        settings: {
            include_charts: document.getElementById('includeCharts').checked,
            include_recommendations: document.getElementById('includeRecommendations').checked,
            notify_errors: document.getElementById('notifyReportErrors').checked,
            archive_reports: document.getElementById('archiveReports').checked
        }
    };
    
    fetch('/admin/api/save-report-settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Настройки отчетов успешно сохранены');
            loadReportSchedule(); // Обновляем расписание
        } else {
            showError('Ошибка при сохранении настроек');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showError('Ошибка при сохранении настроек');
    });
});

// Загрузка расписания отчетов
function loadReportSchedule() {
    fetch('/admin/api/report-schedule')
        .then(response => response.json())
        .then(data => {
            const scheduleDiv = document.getElementById('reportSchedule');
            scheduleDiv.innerHTML = '';
            
            data.schedule.forEach(item => {
                const scheduleItem = document.createElement('div');
                scheduleItem.className = 'mb-3 p-3 border rounded';
                scheduleItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${item.title}</h6>
                            <small class="text-muted">${item.schedule}</small>
                        </div>
                        <span class="badge bg-${item.enabled ? 'success' : 'secondary'}">
                            ${item.enabled ? 'Активно' : 'Отключено'}
                        </span>
                    </div>
                `;
                scheduleDiv.appendChild(scheduleItem);
            });
        })
        .catch(error => {
            console.error('Ошибка загрузки расписания:', error);
        });
}

// Генерация тестового отчета
function generateTestReport() {
    fetch('/admin/api/generate-test-report', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Тестовый отчет создан');
            loadReportsHistory(); // Обновляем историю
        } else {
            showError(data.message || 'Ошибка создания отчета');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showError('Ошибка при создании отчета');
    });
}

// Генерация отчета по безопасности
function generateSecurityReport() {
    fetch('/admin/api/generate-security-report', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Отчет по безопасности создан');
            loadReportsHistory();
        } else {
            showError(data.message || 'Ошибка создания отчета');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showError('Ошибка при создании отчета');
    });
}

// Генерация аналитического отчета
function generateAnalyticsReport() {
    fetch('/admin/api/generate-analytics-report', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Аналитический отчет создан');
            loadReportsHistory();
        } else {
            showError(data.message || 'Ошибка создания отчета');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showError('Ошибка при создании отчета');
    });
}

// Генерация отчета по активности
function generateActivityReport() {
    fetch('/admin/api/generate-activity-report', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Отчет по активности создан');
            loadReportsHistory();
        } else {
            showError(data.message || 'Ошибка создания отчета');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showError('Ошибка при создании отчета');
    });
}

// Экспорт всех отчетов
function exportAllReports() {
    window.open('/admin/api/export-all-reports', '_blank');
}

// Загрузка истории отчетов
function loadReportsHistory(page = 1) {
    currentPage = page;
    
    fetch(`/admin/api/reports-history?page=${page}&limit=${itemsPerPage}`)
        .then(response => response.json())
        .then(data => {
            updateReportsTable(data.reports);
            updateReportsPagination(data.total, data.current_page, data.total_pages);
        })
        .catch(error => {
            console.error('Ошибка загрузки истории:', error);
            showError('Ошибка загрузки истории отчетов');
        });
}

// Обновление таблицы отчетов
function updateReportsTable(reports) {
    const tbody = document.getElementById('reportsHistory');
    tbody.innerHTML = '';
    
    reports.forEach(report => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatDateTime(report.created_at)}</td>
            <td>
                <span class="badge bg-${getReportTypeColor(report.type)}">
                    ${getReportTypeLabel(report.type)}
                </span>
            </td>
            <td>
                <span class="badge bg-secondary">
                    ${report.format.toUpperCase()}
                </span>
            </td>
            <td>${formatFileSize(report.size)}</td>
            <td>
                <span class="badge bg-${getReportStatusColor(report.status)}">
                    ${getReportStatusLabel(report.status)}
                </span>
            </td>
            <td>${report.recipients_count || 0}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="previewReport(${report.id})">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-success" onclick="downloadReport(${report.id})">
                    <i class="fas fa-download"></i>
                </button>
                <button class="btn btn-sm btn-outline-info" onclick="sendReport(${report.id})">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Обновление пагинации отчетов
function updateReportsPagination(total, currentPage, totalPages) {
    const pagination = document.getElementById('reportsPagination');
    pagination.innerHTML = '';
    
    // Кнопка "Предыдущая"
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage <= 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `
        <a class="page-link" href="#" onclick="loadReportsHistory(${currentPage - 1})">
            <i class="fas fa-chevron-left"></i>
        </a>
    `;
    pagination.appendChild(prevLi);
    
    // Номера страниц
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="loadReportsHistory(${i})">${i}</a>`;
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
        <a class="page-link" href="#" onclick="loadReportsHistory(${currentPage + 1})">
            <i class="fas fa-chevron-right"></i>
        </a>
    `;
    pagination.appendChild(nextLi);
}

// Предварительный просмотр отчета
function previewReport(reportId) {
    currentReportId = reportId;
    
    fetch(`/admin/api/report-preview/${reportId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('reportPreviewContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Информация об отчете</h6>
                        <p><strong>Тип:</strong> ${getReportTypeLabel(data.type)}</p>
                        <p><strong>Формат:</strong> ${data.format.toUpperCase()}</p>
                        <p><strong>Размер:</strong> ${formatFileSize(data.size)}</p>
                        <p><strong>Создан:</strong> ${formatDateTime(data.created_at)}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Статистика</h6>
                        <p><strong>Страниц:</strong> ${data.pages || 'N/A'}</p>
                        <p><strong>Графиков:</strong> ${data.charts || 0}</p>
                        <p><strong>Таблиц:</strong> ${data.tables || 0}</p>
                        <p><strong>Получателей:</strong> ${data.recipients_count || 0}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6>Содержание отчета</h6>
                        <div class="alert alert-info">
                            ${data.summary || 'Описание отчета недоступно'}
                        </div>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('reportPreviewModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Ошибка загрузки предварительного просмотра:', error);
            showError('Ошибка загрузки предварительного просмотра');
        });
}

// Скачивание отчета
function downloadReport(reportId = null) {
    const id = reportId || currentReportId;
    
    if (!id) {
        showError('ID отчета не найден');
        return;
    }
    
    window.open(`/admin/api/download-report/${id}`, '_blank');
}

// Отправка отчета
function sendReport(reportId = null) {
    const id = reportId || currentReportId;
    
    if (!id) {
        showError('ID отчета не найден');
        return;
    }
    
    if (confirm('Отправить отчет по email?')) {
        fetch(`/admin/api/send-report/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Отчет отправлен');
                loadReportsHistory(currentPage); // Обновляем текущую страницу
            } else {
                showError(data.message || 'Ошибка отправки отчета');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            showError('Ошибка при отправке отчета');
        });
    }
}

// Обновление отчетов
function refreshReports() {
    loadReportStats();
    loadReportsHistory(currentPage);
}

// Экспорт истории отчетов
function exportReportsHistory() {
    window.open('/admin/api/export-reports-history', '_blank');
}

// Вспомогательные функции
function getReportTypeColor(type) {
    const colors = {
        'security': 'danger',
        'analytics': 'info',
        'activity': 'warning',
        'test': 'secondary',
        'comprehensive': 'primary'
    };
    return colors[type] || 'secondary';
}

function getReportTypeLabel(type) {
    const labels = {
        'security': 'Безопасность',
        'analytics': 'Аналитика',
        'activity': 'Активность',
        'test': 'Тестовый',
        'comprehensive': 'Комплексный'
    };
    return labels[type] || type;
}

function getReportStatusColor(status) {
    const colors = {
        'generating': 'warning',
        'completed': 'success',
        'failed': 'danger',
        'sent': 'info'
    };
    return colors[status] || 'secondary';
}

function getReportStatusLabel(status) {
    const labels = {
        'generating': 'Создается',
        'completed': 'Завершен',
        'failed': 'Ошибка',
        'sent': 'Отправлен'
    };
    return labels[status] || status;
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

.border {
    border: 1px solid #dee2e6 !important;
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

.alert {
    border-radius: 0.375rem;
    border: 1px solid transparent;
}

.alert-info {
    color: #055160;
    background-color: #cff4fc;
    border-color: #b6effb;
}
</style> 