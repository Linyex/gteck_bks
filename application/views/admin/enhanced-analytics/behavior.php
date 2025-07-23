<?php
/**
 * Представление для анализа поведения пользователей
 * Расширенная аналитика - поведенческий анализ
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Главная</a></li>
                        <li class="breadcrumb-item"><a href="/admin/analytics">Аналитика</a></li>
                        <li class="breadcrumb-item active">Поведенческий анализ</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-brain text-primary me-2"></i>
                    Поведенческий анализ пользователей
                </h4>
            </div>
        </div>
    </div>

    <!-- Фильтры и настройки -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Период анализа</label>
                            <select class="form-select" id="behaviorPeriod">
                                <option value="7">Последние 7 дней</option>
                                <option value="30" selected>Последние 30 дней</option>
                                <option value="90">Последние 90 дней</option>
                                <option value="365">Последний год</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Тип поведения</label>
                            <select class="form-select" id="behaviorType">
                                <option value="all">Все типы</option>
                                <option value="login_patterns">Паттерны входа</option>
                                <option value="navigation_patterns">Навигационные паттерны</option>
                                <option value="feature_usage">Использование функций</option>
                                <option value="time_patterns">Временные паттерны</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Группа пользователей</label>
                            <select class="form-select" id="userGroup">
                                <option value="all">Все группы</option>
                                <option value="students">Студенты</option>
                                <option value="teachers">Преподаватели</option>
                                <option value="admins">Администраторы</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button class="btn btn-primary" onclick="loadBehaviorData()">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Обновить
                                </button>
                                <button class="btn btn-outline-secondary" onclick="exportBehaviorData()">
                                    <i class="fas fa-download me-1"></i>
                                    Экспорт
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Основные метрики -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-primary-lighten text-primary rounded">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="activeUsers">0</h5>
                            <p class="text-muted mb-0">Активных пользователей</p>
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
                            <h5 class="mb-1" id="avgSessionTime">0 мин</h5>
                            <p class="text-muted mb-0">Среднее время сессии</p>
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
                                    <i class="fas fa-route"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="avgPageViews">0</h5>
                            <p class="text-muted mb-0">Среднее количество страниц</p>
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
                                    <i class="fas fa-chart-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1" id="behaviorScore">0%</h5>
                            <p class="text-muted mb-0">Поведенческий скор</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Графики поведения -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-area text-primary me-2"></i>
                        Активность пользователей по времени
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-pie text-success me-2"></i>
                        Распределение по типам активности
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="activityTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Паттерны навигации -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-sitemap text-warning me-2"></i>
                        Популярные пути навигации
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Путь</th>
                                    <th>Количество переходов</th>
                                    <th>Среднее время</th>
                                    <th>Процент от общего</th>
                                    <th>Тренд</th>
                                </tr>
                            </thead>
                            <tbody id="navigationPaths">
                                <!-- Данные будут загружены динамически -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Аномалии поведения -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        Обнаруженные аномалии поведения
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Пользователь</th>
                                    <th>Тип аномалии</th>
                                    <th>Описание</th>
                                    <th>Уровень риска</th>
                                    <th>Дата обнаружения</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody id="behaviorAnomalies">
                                <!-- Данные будут загружены динамически -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Детальная статистика -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-bar text-info me-2"></i>
                        Статистика по времени суток
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="hourlyChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Активность по дням недели
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="weeklyChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для детального просмотра -->
<div class="modal fade" id="behaviorDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-clock text-primary me-2"></i>
                    Детальная информация о поведении
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="behaviorDetailContent">
                <!-- Содержимое будет загружено динамически -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="exportUserBehavior()">
                    <i class="fas fa-download me-1"></i>
                    Экспорт данных
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Глобальные переменные для графиков
let activityChart, activityTypeChart, hourlyChart, weeklyChart;

// Загрузка данных при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    loadBehaviorData();
    initializeCharts();
});

// Инициализация графиков
function initializeCharts() {
    // График активности по времени
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Активные пользователи',
                data: [],
                borderColor: '#3b7ddd',
                backgroundColor: 'rgba(59, 125, 221, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // График типов активности
    const activityTypeCtx = document.getElementById('activityTypeChart').getContext('2d');
    activityTypeChart = new Chart(activityTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Входы', 'Навигация', 'Действия', 'Файлы'],
            datasets: [{
                data: [25, 30, 25, 20],
                backgroundColor: ['#3b7ddd', '#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // График по часам
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    hourlyChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
            datasets: [{
                label: 'Активность',
                data: [10, 5, 25, 45, 35, 20],
                backgroundColor: '#3b7ddd'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // График по дням недели
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
            datasets: [{
                label: 'Активность',
                data: [65, 70, 80, 75, 60, 40, 30],
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Загрузка данных поведения
function loadBehaviorData() {
    const period = document.getElementById('behaviorPeriod').value;
    const type = document.getElementById('behaviorType').value;
    const group = document.getElementById('userGroup').value;

    // Показываем индикатор загрузки
    showLoading();

    fetch(`/admin/api/behavior-analytics?period=${period}&type=${type}&group=${group}`)
        .then(response => response.json())
        .then(data => {
            updateBehaviorMetrics(data.metrics);
            updateBehaviorCharts(data.charts);
            updateNavigationPaths(data.navigation);
            updateBehaviorAnomalies(data.anomalies);
            hideLoading();
        })
        .catch(error => {
            console.error('Ошибка загрузки данных:', error);
            hideLoading();
            showError('Ошибка загрузки данных поведения');
        });
}

// Обновление метрик
function updateBehaviorMetrics(metrics) {
    document.getElementById('activeUsers').textContent = metrics.activeUsers || 0;
    document.getElementById('avgSessionTime').textContent = (metrics.avgSessionTime || 0) + ' мин';
    document.getElementById('avgPageViews').textContent = metrics.avgPageViews || 0;
    document.getElementById('behaviorScore').textContent = (metrics.behaviorScore || 0) + '%';
}

// Обновление графиков
function updateBehaviorCharts(charts) {
    if (charts.activity) {
        activityChart.data.labels = charts.activity.labels;
        activityChart.data.datasets[0].data = charts.activity.data;
        activityChart.update();
    }

    if (charts.activityTypes) {
        activityTypeChart.data.labels = charts.activityTypes.labels;
        activityTypeChart.data.datasets[0].data = charts.activityTypes.data;
        activityTypeChart.update();
    }

    if (charts.hourly) {
        hourlyChart.data.datasets[0].data = charts.hourly;
        hourlyChart.update();
    }

    if (charts.weekly) {
        weeklyChart.data.datasets[0].data = charts.weekly;
        weeklyChart.update();
    }
}

// Обновление путей навигации
function updateNavigationPaths(navigation) {
    const tbody = document.getElementById('navigationPaths');
    tbody.innerHTML = '';

    navigation.forEach(path => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <i class="fas fa-route text-primary me-2"></i>
                    <span>${path.path}</span>
                </div>
            </td>
            <td>
                <span class="badge bg-primary">${path.transitions}</span>
            </td>
            <td>${path.avgTime} сек</td>
            <td>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" style="width: ${path.percentage}%"></div>
                </div>
                <small class="text-muted">${path.percentage}%</small>
            </td>
            <td>
                <span class="badge ${path.trend === 'up' ? 'bg-success' : path.trend === 'down' ? 'bg-danger' : 'bg-secondary'}">
                    <i class="fas fa-arrow-${path.trend === 'up' ? 'up' : path.trend === 'down' ? 'down' : 'minus'}"></i>
                </span>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Обновление аномалий поведения
function updateBehaviorAnomalies(anomalies) {
    const tbody = document.getElementById('behaviorAnomalies');
    tbody.innerHTML = '';

    anomalies.forEach(anomaly => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <img src="${anomaly.avatar || '/assets/images/default-avatar.png'}" 
                         class="rounded-circle me-2" width="32" height="32">
                    <div>
                        <h6 class="mb-0">${anomaly.username}</h6>
                        <small class="text-muted">${anomaly.email}</small>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge bg-${anomaly.type === 'suspicious' ? 'danger' : 'warning'}">
                    ${anomaly.type === 'suspicious' ? 'Подозрительно' : 'Аномально'}
                </span>
            </td>
            <td>${anomaly.description}</td>
            <td>
                <span class="badge bg-${anomaly.riskLevel === 'high' ? 'danger' : anomaly.riskLevel === 'medium' ? 'warning' : 'info'}">
                    ${anomaly.riskLevel === 'high' ? 'Высокий' : anomaly.riskLevel === 'medium' ? 'Средний' : 'Низкий'}
                </span>
            </td>
            <td>${formatDate(anomaly.detectedAt)}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="viewBehaviorDetail(${anomaly.id})">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-warning" onclick="markAnomalyResolved(${anomaly.id})">
                    <i class="fas fa-check"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Просмотр детальной информации
function viewBehaviorDetail(anomalyId) {
    fetch(`/admin/api/behavior-detail/${anomalyId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('behaviorDetailContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Информация о пользователе</h6>
                        <p><strong>Имя:</strong> ${data.user.name}</p>
                        <p><strong>Email:</strong> ${data.user.email}</p>
                        <p><strong>Группа:</strong> ${data.user.group}</p>
                        <p><strong>Последний вход:</strong> ${formatDate(data.user.lastLogin)}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Детали аномалии</h6>
                        <p><strong>Тип:</strong> ${data.anomaly.type}</p>
                        <p><strong>Описание:</strong> ${data.anomaly.description}</p>
                        <p><strong>Уровень риска:</strong> ${data.anomaly.riskLevel}</p>
                        <p><strong>Обнаружено:</strong> ${formatDate(data.anomaly.detectedAt)}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6>Последние действия</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Время</th>
                                        <th>Действие</th>
                                        <th>IP адрес</th>
                                        <th>Устройство</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.recentActivity.map(activity => `
                                        <tr>
                                            <td>${formatDateTime(activity.timestamp)}</td>
                                            <td>${activity.action}</td>
                                            <td>${activity.ipAddress}</td>
                                            <td>${activity.device}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('behaviorDetailModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Ошибка загрузки деталей:', error);
            showError('Ошибка загрузки детальной информации');
        });
}

// Отметить аномалию как разрешенную
function markAnomalyResolved(anomalyId) {
    if (confirm('Отметить аномалию как разрешенную?')) {
        fetch(`/admin/api/resolve-anomaly/${anomalyId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Аномалия отмечена как разрешенная');
                loadBehaviorData(); // Перезагружаем данные
            } else {
                showError('Ошибка при обновлении статуса');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            showError('Ошибка при обновлении статуса');
        });
    }
}

// Экспорт данных поведения
function exportBehaviorData() {
    const period = document.getElementById('behaviorPeriod').value;
    const type = document.getElementById('behaviorType').value;
    const group = document.getElementById('userGroup').value;

    window.open(`/admin/api/export-behavior?period=${period}&type=${type}&group=${group}`, '_blank');
}

// Экспорт данных пользователя
function exportUserBehavior() {
    const anomalyId = document.querySelector('#behaviorDetailModal').dataset.anomalyId;
    window.open(`/admin/api/export-user-behavior/${anomalyId}`, '_blank');
}

// Вспомогательные функции
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('ru-RU');
}

function formatDateTime(dateString) {
    return new Date(dateString).toLocaleString('ru-RU');
}

function showLoading() {
    // Показываем индикатор загрузки
    document.body.classList.add('loading');
}

function hideLoading() {
    // Скрываем индикатор загрузки
    document.body.classList.remove('loading');
}

function showSuccess(message) {
    // Показываем уведомление об успехе
    Swal.fire({
        icon: 'success',
        title: 'Успешно!',
        text: message,
        timer: 3000
    });
}

function showError(message) {
    // Показываем уведомление об ошибке
    Swal.fire({
        icon: 'error',
        title: 'Ошибка!',
        text: message
    });
}
</script>

<style>
.loading {
    cursor: wait;
}

.loading::after {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
}

.progress {
    background-color: #e9ecef;
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
</style> 