<?php
$page_title = "Управление пользователями";
$page_subtitle = "Users Management";
$current_page = "users";
$page_content = '
<div class="main-header">
    <h1><i class="fas fa-users"></i> Управление пользователями</h1>
    <p>Просмотр и управление пользователями системы</p>
</div>

<div class="chart-section">
    <div class="chart-header">
        <h3 class="chart-title">
            <i class="fas fa-user-plus"></i>
            Статистика пользователей
        </h3>
        <div class="chart-controls">
            <button class="btn btn-blue" onclick="addUser()">
                <i class="fas fa-plus"></i> Добавить пользователя
            </button>
            <button class="btn btn-green" onclick="exportUsers()">
                <i class="fas fa-download"></i> Экспорт
            </button>
        </div>
    </div>
    
    <div class="metrics-grid">
        <div class="metric-card users" data-metric="total-users">
            <i class="metric-icon fas fa-users"></i>
            <div class="metric-content">
                <div class="metric-number">1,247</div>
                <div class="metric-label">Всего пользователей</div>
                <div class="metric-details">
                    <span class="active"><i class="fas fa-circle"></i> 892 активных</span>
                    <span class="new"><i class="fas fa-plus"></i> 45 новых</span>
                </div>
            </div>
        </div>

        <div class="metric-card" data-metric="online-users">
            <i class="metric-icon fas fa-circle"></i>
            <div class="metric-content">
                <div class="metric-number">892</div>
                <div class="metric-label">Онлайн сейчас</div>
                <div class="metric-details">
                    <span class="active"><i class="fas fa-check"></i> 71.5% от общего</span>
                    <span class="new"><i class="fas fa-clock"></i> 15 мин назад</span>
                </div>
            </div>
        </div>

        <div class="metric-card" data-metric="new-users">
            <i class="metric-icon fas fa-user-plus"></i>
            <div class="metric-content">
                <div class="metric-number">45</div>
                <div class="metric-label">Новых за неделю</div>
                <div class="metric-details">
                    <span class="active"><i class="fas fa-check"></i> 12 сегодня</span>
                    <span class="new"><i class="fas fa-calendar"></i> 33 на неделе</span>
                </div>
            </div>
        </div>

        <div class="metric-card" data-metric="blocked-users">
            <i class="metric-icon fas fa-user-slash"></i>
            <div class="metric-content">
                <div class="metric-number">8</div>
                <div class="metric-label">Заблокированных</div>
                <div class="metric-details">
                    <span class="blocked"><i class="fas fa-ban"></i> 5 временно</span>
                    <span class="blocked"><i class="fas fa-times"></i> 3 навсегда</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="chart-section">
    <div class="chart-header">
        <h3 class="chart-title">
            <i class="fas fa-table"></i>
            Список пользователей
        </h3>
        <div class="chart-controls">
            <button class="btn btn-purple" onclick="filterUsers()">
                <i class="fas fa-filter"></i> Фильтр
            </button>
            <button class="btn btn-blue" onclick="refreshUsers()">
                <i class="fas fa-sync"></i> Обновить
            </button>
        </div>
    </div>
    
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Статус</th>
                    <th>Последний вход</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Иван Петров</td>
                    <td>ivan@example.com</td>
                    <td><span class="status-active">Активен</span></td>
                    <td>2 минуты назад</td>
                    <td>
                        <button class="btn btn-blue" onclick="editUser(1)">Редактировать</button>
                        <button class="btn btn-red" onclick="blockUser(1)">Заблокировать</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Мария Сидорова</td>
                    <td>maria@example.com</td>
                    <td><span class="status-active">Активен</span></td>
                    <td>5 минут назад</td>
                    <td>
                        <button class="btn btn-blue" onclick="editUser(2)">Редактировать</button>
                        <button class="btn btn-red" onclick="blockUser(2)">Заблокировать</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Алексей Козлов</td>
                    <td>alex@example.com</td>
                    <td><span class="status-blocked">Заблокирован</span></td>
                    <td>1 час назад</td>
                    <td>
                        <button class="btn btn-blue" onclick="editUser(3)">Редактировать</button>
                        <button class="btn btn-green" onclick="unblockUser(3)">Разблокировать</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
';

include __DIR__ . '/layouts/main.php';
?>

<style>
.status-active {
    color: #00ff88;
    font-weight: 600;
}

.status-blocked {
    color: #ff4444;
    font-weight: 600;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.admin-table th {
    background: var(--bg-glass);
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: var(--primary-neon);
    border-bottom: 1px solid var(--border-subtle);
}

.admin-table td {
    padding: 12px;
    border-bottom: 1px solid var(--border-subtle);
    color: var(--text-secondary);
}

.admin-table tr:hover {
    background: var(--bg-glass-hover);
}

.table-container {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid var(--border-subtle);
}
</style>

<script>
function addUser() {
    if (typeof adminPanel !== "undefined") {
        adminPanel.showModal("Добавить пользователя", `
            <div class="settings-form">
                <div class="form-group">
                    <label for="user-name">Имя</label>
                    <input type="text" id="user-name" placeholder="Введите имя">
                </div>
                <div class="form-group">
                    <label for="user-email">Email</label>
                    <input type="email" id="user-email" placeholder="Введите email">
                </div>
                <div class="form-group">
                    <label for="user-role">Роль</label>
                    <select id="user-role">
                        <option value="user">Пользователь</option>
                        <option value="admin">Администратор</option>
                        <option value="moderator">Модератор</option>
                    </select>
                </div>
            </div>
        `);
    }
}

function editUser(id) {
    if (typeof adminPanel !== "undefined") {
        adminPanel.showModal("Редактировать пользователя", `
            <div class="settings-form">
                <div class="form-group">
                    <label for="user-name">Имя</label>
                    <input type="text" id="user-name" value="Пользователь ${id}">
                </div>
                <div class="form-group">
                    <label for="user-email">Email</label>
                    <input type="email" id="user-email" value="user${id}@example.com">
                </div>
                <div class="form-group">
                    <label for="user-role">Роль</label>
                    <select id="user-role">
                        <option value="user">Пользователь</option>
                        <option value="admin">Администратор</option>
                        <option value="moderator">Модератор</option>
                    </select>
                </div>
            </div>
        `);
    }
}

function blockUser(id) {
    if (confirm("Вы уверены, что хотите заблокировать пользователя?")) {
        if (typeof adminPanel !== "undefined") {
            adminPanel.showNotification("Пользователь заблокирован", "success");
        }
    }
}

function unblockUser(id) {
    if (confirm("Вы уверены, что хотите разблокировать пользователя?")) {
        if (typeof adminPanel !== "undefined") {
            adminPanel.showNotification("Пользователь разблокирован", "success");
        }
    }
}

function filterUsers() {
    if (typeof adminPanel !== "undefined") {
        adminPanel.showModal("Фильтр пользователей", `
            <div class="settings-form">
                <div class="form-group">
                    <label for="filter-status">Статус</label>
                    <select id="filter-status">
                        <option value="all">Все</option>
                        <option value="active">Активные</option>
                        <option value="blocked">Заблокированные</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter-role">Роль</label>
                    <select id="filter-role">
                        <option value="all">Все роли</option>
                        <option value="user">Пользователь</option>
                        <option value="admin">Администратор</option>
                        <option value="moderator">Модератор</option>
                    </select>
                </div>
            </div>
        `);
    }
}

function exportUsers() {
    if (typeof adminPanel !== "undefined") {
        adminPanel.showNotification("Экспорт пользователей начат", "info");
    }
}

function refreshUsers() {
    if (typeof adminPanel !== "undefined") {
        adminPanel.showNotification("Список пользователей обновлен", "success");
    }
}
</script>