<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Логи системы'; ?> - CyberAdmin</title>
    
    <!-- Основные стили -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Дополнительные стили -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Дополнительные скрипты в head -->
    <?php if (isset($additional_js_head)): ?>
        <?php foreach ($additional_js_head as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-background">
        <!-- Background will be injected by JavaScript -->
    </div>
    <div class="background-overlay"></div>

    <!-- Admin Container -->
    <div class="admin-container">
        <!-- Header -->
        <header class="new-header">
            <div class="header-wrapper">
                <!-- Логотип и название -->
                <div class="header-brand">
                    <div class="brand-logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="brand-text">
                        <h1>CyberAdmin</h1>
                        <span>Admin Panel</span>
                    </div>
                </div>

                <!-- Правая часть header -->
                <div class="header-actions"> 
                    <!-- Мобильное меню -->
                    <div class="action-item mobile-menu-toggle">
                        <button class="action-btn" onclick="toggleMobileMenu()">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    
                    <!-- Пользователь -->
                    <div class="action-item">
                        <button class="action-btn user-btn" onclick="toggleUserMenu()">
                            <div class="user-avatar">A</div>
                            <span class="user-name">Admin</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <!-- Уведомления -->
                    <div class="action-item">
                        <button class="action-btn" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>

                    <!-- Настройки -->
                    <div class="action-item">
                        <button class="action-btn" onclick="openSettings()">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Поднавигация для категорий -->
            <div class="sub-navigation" id="subNavigation">
                <!-- Поднавигация будет динамически добавляться через JavaScript -->
            </div>
        </header>

        <!-- Боковая навигация -->
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Дашборд</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Пользователи</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/news" class="nav-link">
                        <i class="fas fa-newspaper"></i>
                        <span>Новости</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/files" class="nav-link">
                        <i class="fas fa-file"></i>
                        <span>Файлы</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/photos" class="nav-link">
                        <i class="fas fa-images"></i>
                        <span>Медиа</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/analytics" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Аналитика</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/security" class="nav-link">
                        <i class="fas fa-lock"></i>
                        <span>Безопасность</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/monitoring" class="nav-link">
                        <i class="fas fa-shield-alt"></i>
                        <span>Мониторинг</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/settings" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Настройки</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/admin/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Выход</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="logs-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-file-alt"></i> Логи системы</h1>
            <p>Просмотр и анализ системных логов в реальном времени</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-blue" onclick="refreshLogs()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
            <button class="btn btn-green" onclick="exportLogs()">
                <i class="fas fa-download"></i> Экспорт
            </button>
            <button class="btn btn-purple" onclick="clearLogs()">
                <i class="fas fa-trash"></i> Очистить
            </button>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="logs-filters">
        <div class="filter-group">
            <label for="levelFilter">Уровень:</label>
            <select id="levelFilter" onchange="filterLogs()">
                <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>Все уровни</option>
                <option value="info" <?= $filter === 'info' ? 'selected' : '' ?>>Info</option>
                <option value="warning" <?= $filter === 'warning' ? 'selected' : '' ?>>Warning</option>
                <option value="error" <?= $filter === 'error' ? 'selected' : '' ?>>Error</option>
                <option value="debug" <?= $filter === 'debug' ? 'selected' : '' ?>>Debug</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="sourceFilter">Источник:</label>
            <select id="sourceFilter" onchange="filterLogs()">
                <option value="all">Все источники</option>
                <option value="system">Система</option>
                <option value="security">Безопасность</option>
                <option value="database">База данных</option>
                <option value="application">Приложение</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="dateFilter">Дата:</label>
            <input type="date" id="dateFilter" onchange="filterLogs()">
        </div>
        
        <div class="filter-group">
            <label for="searchFilter">Поиск:</label>
            <input type="text" id="searchFilter" placeholder="Поиск в логах..." onkeyup="filterLogs()">
        </div>
    </div>

    <!-- Статистика логов -->
    <div class="logs-stats">
        <div class="stat-item">
            <i class="fas fa-file-alt"></i>
            <span class="stat-number"><?= count($logs) ?></span>
            <span class="stat-label">Всего записей</span>
        </div>
        <div class="stat-item">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="stat-number"><?= count(array_filter($logs, fn($log) => $log['level'] === 'error')) ?></span>
            <span class="stat-label">Ошибок</span>
        </div>
        <div class="stat-item">
            <i class="fas fa-clock"></i>
            <span class="stat-number"><?= date('H:i:s') ?></span>
            <span class="stat-label">Последнее обновление</span>
        </div>
    </div>

    <!-- Таблица логов -->
    <div class="logs-table-container">
        <table class="logs-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Время</th>
                    <th>Уровень</th>
                    <th>Источник</th>
                    <th>Сообщение</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr class="log-row level-<?= $log['level'] ?>" data-level="<?= $log['level'] ?>" data-source="<?= $log['source'] ?>">
                    <td class="log-id">#<?= $log['id'] ?></td>
                    <td class="log-time"><?= date('d.m.Y H:i:s', strtotime($log['timestamp'])) ?></td>
                    <td class="log-level">
                        <span class="level-badge <?= $log['level'] ?>">
                            <?= strtoupper($log['level']) ?>
                        </span>
                    </td>
                    <td class="log-source">
                        <span class="source-badge"><?= ucfirst($log['source']) ?></span>
                    </td>
                    <td class="log-message"><?= htmlspecialchars($log['message']) ?></td>
                    <td class="log-actions">
                        <button class="btn-icon" onclick="viewLogDetails(<?= $log['id'] ?>)" title="Подробности">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-icon" onclick="copyLog(<?= $log['id'] ?>)" title="Копировать">
                            <i class="fas fa-copy"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Пагинация -->
    <?php if (isset($pagination) && $pagination['total'] > $pagination['per_page']): ?>
    <div class="pagination">
        <?php if ($pagination['current'] > 1): ?>
            <a href="?page=<?= $pagination['current'] - 1 ?>&filter=<?= $filter ?>" class="page-link">
                <i class="fas fa-chevron-left"></i> Назад
            </a>
        <?php endif; ?>
        
        <?php foreach ($pagination['pages'] as $page): ?>
            <a href="?page=<?= $page ?>&filter=<?= $filter ?>" 
               class="page-link <?= $page == $pagination['current'] ? 'active' : '' ?>">
                <?= $page ?>
            </a>
        <?php endforeach; ?>
        
        <?php if ($pagination['current'] < ceil($pagination['total'] / $pagination['per_page'])): ?>
            <a href="?page=<?= $pagination['current'] + 1 ?>&filter=<?= $filter ?>" class="page-link">
                Вперед <i class="fas fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Модальное окно для деталей лога -->
<div id="logDetailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Детали записи лога</h3>
            <button class="modal-close" onclick="closeLogDetails()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="logDetailsContent">
                <!-- Содержимое будет загружено динамически -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-blue" onclick="exportLogDetails()">
                <i class="fas fa-download"></i> Экспорт
            </button>
            <button class="btn btn-outline" onclick="closeLogDetails()">
                Закрыть
            </button>
        </div>
    </div>
</div>

<script>
// Функции для работы с логами
function refreshLogs() {
    location.reload();
}

function exportLogs() {
    const levelFilter = document.getElementById('levelFilter').value;
    const sourceFilter = document.getElementById('sourceFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    
    // Логика экспорта логов
    showNotification('Логи экспортированы', 'success');
}

function clearLogs() {
    if (confirm('Вы уверены, что хотите очистить все логи? Это действие нельзя отменить.')) {
        // Логика очистки логов
        showNotification('Логи очищены', 'success');
        setTimeout(() => location.reload(), 1000);
    }
}

function filterLogs() {
    const levelFilter = document.getElementById('levelFilter').value;
    const sourceFilter = document.getElementById('sourceFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
    
    const rows = document.querySelectorAll('.log-row');
    
    rows.forEach(row => {
        const level = row.getAttribute('data-level');
        const source = row.getAttribute('data-source');
        const message = row.querySelector('.log-message').textContent.toLowerCase();
        const time = row.querySelector('.log-time').textContent;
        
        let show = true;
        
        // Фильтр по уровню
        if (levelFilter !== 'all' && level !== levelFilter) {
            show = false;
        }
        
        // Фильтр по источнику
        if (sourceFilter !== 'all' && source !== sourceFilter) {
            show = false;
        }
        
        // Фильтр по дате
        if (dateFilter && !time.includes(dateFilter.split('-').reverse().join('.'))) {
            show = false;
        }
        
        // Фильтр по поиску
        if (searchFilter && !message.includes(searchFilter)) {
            show = false;
        }
        
        row.style.display = show ? 'table-row' : 'none';
    });
}

function viewLogDetails(logId) {
    // Загрузка деталей лога
    fetch(`/admin/api/monitoring/logs/${logId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('logDetailsContent').innerHTML = `
                <div class="log-details">
                    <div class="detail-item">
                        <strong>ID:</strong> ${data.id}
                    </div>
                    <div class="detail-item">
                        <strong>Время:</strong> ${data.timestamp}
                    </div>
                    <div class="detail-item">
                        <strong>Уровень:</strong> <span class="level-badge ${data.level}">${data.level.toUpperCase()}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Источник:</strong> ${data.source}
                    </div>
                    <div class="detail-item">
                        <strong>Сообщение:</strong> ${data.message}
                    </div>
                    <div class="detail-item">
                        <strong>Дополнительные данные:</strong>
                        <pre>${JSON.stringify(data.metadata || {}, null, 2)}</pre>
                    </div>
                </div>
            `;
            document.getElementById('logDetailsModal').style.display = 'flex';
        })
        .catch(error => {
            console.error('Ошибка загрузки деталей лога:', error);
            showNotification('Ошибка загрузки деталей', 'error');
        });
}

function closeLogDetails() {
    document.getElementById('logDetailsModal').style.display = 'none';
}

function copyLog(logId) {
    const row = document.querySelector(`[data-id="${logId}"]`);
    const logText = `${row.querySelector('.log-time').textContent} [${row.querySelector('.log-level').textContent}] ${row.querySelector('.log-message').textContent}`;
    
    navigator.clipboard.writeText(logText).then(() => {
        showNotification('Лог скопирован в буфер обмена', 'success');
    }).catch(() => {
        showNotification('Ошибка копирования', 'error');
    });
}

function exportLogDetails() {
    // Логика экспорта деталей лога
    showNotification('Детали лога экспортированы', 'success');
}

// Автообновление логов каждые 10 секунд
setInterval(() => {
    // Проверяем новые логи
    checkNewLogs();
}, 10000);

function checkNewLogs() {
    fetch('/admin/api/monitoring/logs/new')
        .then(response => response.json())
        .then(data => {
            if (data.newLogs && data.newLogs.length > 0) {
                // Добавляем новые логи в таблицу
                data.newLogs.forEach(log => {
                    addNewLogRow(log);
                });
                
                showNotification(`Получено ${data.newLogs.length} новых записей`, 'info');
            }
        })
        .catch(error => {
            console.error('Ошибка проверки новых логов:', error);
        });
}

function addNewLogRow(log) {
    const tbody = document.querySelector('.logs-table tbody');
    const newRow = document.createElement('tr');
    newRow.className = `log-row level-${log.level}`;
    newRow.setAttribute('data-level', log.level);
    newRow.setAttribute('data-source', log.source);
    
    newRow.innerHTML = `
        <td class="log-id">#${log.id}</td>
        <td class="log-time">${new Date(log.timestamp).toLocaleString()}</td>
        <td class="log-level">
            <span class="level-badge ${log.level}">${log.level.toUpperCase()}</span>
        </td>
        <td class="log-source">
            <span class="source-badge">${log.source}</span>
        </td>
        <td class="log-message">${log.message}</td>
        <td class="log-actions">
            <button class="btn-icon" onclick="viewLogDetails(${log.id})" title="Подробности">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn-icon" onclick="copyLog(${log.id})" title="Копировать">
                <i class="fas fa-copy"></i>
            </button>
        </td>
    `;
    
    tbody.insertBefore(newRow, tbody.firstChild);
    
    // Добавляем анимацию для новой строки
    newRow.style.animation = 'newLogHighlight 2s ease-out';
}

// Закрытие модального окна при клике вне его
window.onclick = function(event) {
    const modal = document.getElementById('logDetailsModal');
    if (event.target === modal) {
        closeLogDetails();
    }
}
</script>

<style>
/* Стили для новых логов */
@keyframes newLogHighlight {
    0% {
        background-color: rgba(0, 255, 255, 0.3);
        transform: translateX(-10px);
    }
    100% {
        background-color: transparent;
        transform: translateX(0);
    }
}

.logs-container {
    padding: 20px;
}

.logs-filters {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.filter-group select,
.filter-group input {
    padding: 8px 12px;
    border: 1px solid var(--border-subtle);
    border-radius: 6px;
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.logs-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    background: var(--bg-glass);
    border-radius: 8px;
    border: 1px solid var(--border-subtle);
}

.stat-item i {
    color: var(--primary-neon);
}

.stat-number {
    font-weight: bold;
    color: var(--text-primary);
}

.stat-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.logs-table-container {
    background: var(--bg-glass);
    border-radius: 12px;
    border: 1px solid var(--border-subtle);
    overflow: hidden;
    margin-bottom: 20px;
}

.logs-table {
    width: 100%;
    border-collapse: collapse;
}

.logs-table th {
    background: var(--bg-tertiary);
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-subtle);
}

.logs-table td {
    padding: 12px 15px;
    border-bottom: 1px solid var(--border-subtle);
    color: var(--text-secondary);
}

.logs-table tr:hover {
    background: rgba(0, 255, 255, 0.05);
}

.level-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.level-badge.info {
    background: rgba(0, 150, 255, 0.2);
    color: #0096ff;
}

.level-badge.warning {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.level-badge.error {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.level-badge.debug {
    background: rgba(108, 117, 125, 0.2);
    color: #6c757d;
}

.source-badge {
    padding: 4px 8px;
    background: rgba(0, 255, 255, 0.1);
    color: var(--primary-neon);
    border-radius: 4px;
    font-size: 0.8rem;
}

.log-actions {
    display: flex;
    gap: 5px;
}

.btn-icon {
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: all var(--transition-fast);
}

.btn-icon:hover {
    color: var(--primary-neon);
    background: rgba(0, 255, 255, 0.1);
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.page-link {
    padding: 8px 12px;
    border: 1px solid var(--border-subtle);
    border-radius: 6px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all var(--transition-fast);
}

.page-link:hover,
.page-link.active {
    background: var(--primary-neon);
    color: var(--bg-primary);
    border-color: var(--primary-neon);
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: var(--bg-secondary);
    border: 1px solid var(--border-neon);
    border-radius: 12px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--border-subtle);
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 1.2rem;
    padding: 5px;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px;
    border-top: 1px solid var(--border-subtle);
}

.log-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-item strong {
    color: var(--text-primary);
}

.detail-item pre {
    background: var(--bg-tertiary);
    padding: 10px;
    border-radius: 6px;
    overflow-x: auto;
    font-size: 0.9rem;
    color: var(--text-secondary);
}
</style>

    <!-- Scripts -->
    <script src="/assets/js/background-animations.js"></script>
    <script src="/assets/js/admin-common.js"></script>
    
    <!-- JavaScript для боковой навигации -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Автоматическое определение активного элемента на основе URL
            const currentPath = window.location.pathname;
            
            document.querySelectorAll('.nav-item').forEach(navItem => {
                const link = navItem.querySelector('.nav-link');
                const href = link.getAttribute('href');
                
                if (href && currentPath.startsWith(href)) {
                    navItem.classList.add('active');
                }
            });
            
            // Закрытие мобильного меню при изменении размера окна
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1200) {
                    document.querySelector('.sidebar-nav').classList.remove('mobile-open');
                    document.querySelector('.admin-main').classList.remove('mobile-open');
                }
            });
            
            // Динамическое создание поднавигации
            function createSubNavigation() {
                const subNav = document.getElementById('subNavigation');
                const currentPath = window.location.pathname;
                
                // Определяем категорию на основе текущего пути
                let category = '';
                let subLinks = [];
                
                if (currentPath.startsWith('/admin/monitoring')) {
                    category = 'Мониторинг';
                    subLinks = [
                        { href: '/admin/monitoring', text: 'Дашборд мониторинга', icon: 'fas fa-tachometer-alt' },
                        { href: '/admin/monitoring/logs', text: 'Системные логи', icon: 'fas fa-file-alt' },
                        { href: '/admin/monitoring/threats', text: 'Угрозы', icon: 'fas fa-exclamation-triangle' },
                        { href: '/admin/monitoring/reports', text: 'Отчеты', icon: 'fas fa-chart-bar' },
                        { href: '/admin/monitoring/settings', text: 'Настройки', icon: 'fas fa-cog' }
                    ];
                }
                
                // Создаем поднавигацию только если есть категория
                if (category && subLinks.length > 0) {
                    let subNavHTML = `
                        <div class="sub-nav-container">
                            <div class="sub-nav-header">
                                <h3>${category}</h3>
                            </div>
                            <div class="sub-nav-links">
                    `;
                    
                    subLinks.forEach(link => {
                        const isActive = currentPath === link.href;
                        subNavHTML += `
                            <a href="${link.href}" class="sub-nav-link ${isActive ? 'active' : ''}">
                                <i class="${link.icon}"></i>
                                <span>${link.text}</span>
                            </a>
                        `;
                    });
                    
                    subNavHTML += `
                            </div>
                        </div>
                    `;
                    
                    subNav.innerHTML = subNavHTML;
                    subNav.style.display = 'block';
                } else {
                    subNav.style.display = 'none';
                }
            }
            
            // Запускаем создание поднавигации
            createSubNavigation();
        });
    </script>
    
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 