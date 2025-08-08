<?php
$currentPage = 'users';
?>

<!-- Users Dashboard Content -->
<div class="users-dashboard">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-users"></i>
            <h1>Управление пользователями</h1>
        </div>
        <div class="page-subtitle">
            Просмотр, создание и управление пользователями системы
        </div>
        <div class="page-actions">
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="fas fa-user-plus"></i>
                Добавить пользователя
            </a>
        </div>
    </div>

    <!-- Панель статистики безопасности -->
    <div class="security-dashboard">
        <div class="security-stats">
            <div class="stat-card security-warning">
                <div class="stat-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= $securityStats['blocked_users'] ?? 0 ?></div>
                    <div class="stat-label">Заблокированных пользователей</div>
                </div>
            </div>
            
            <div class="stat-card security-danger">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= $securityStats['suspicious_logins_24h'] ?? 0 ?></div>
                    <div class="stat-label">Подозрительных входов (24ч)</div>
                </div>
            </div>
            
            <div class="stat-card security-info">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= $securityStats['failed_logins_24h'] ?? 0 ?></div>
                    <div class="stat-label">Неудачных попыток (24ч)</div>
                </div>
            </div>
            
            <div class="stat-card security-success">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= $securityStats['active_sessions'] ?? 0 ?></div>
                    <div class="stat-label">Активных сессий</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Уведомления о безопасности -->
    <?php if (!empty($suspiciousActivities)): ?>
        <div class="security-alerts">
            <h3><i class="fas fa-bell"></i> Уведомления безопасности</h3>
            <div class="alerts-container">
                <?php foreach ($suspiciousActivities as $activity): ?>
                    <div class="alert-item alert-<?= $activity['severity'] ?>">
                        <div class="alert-icon">
                            <i class="fas fa-<?= $activity['type'] === 'suspicious_login' ? 'user-secret' : 'exclamation-triangle' ?>"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title"><?= htmlspecialchars($activity['user']) ?></div>
                            <div class="alert-description"><?= htmlspecialchars($activity['description']) ?></div>
                            <div class="alert-time"><?= date('d.m.Y H:i', strtotime($activity['time'])) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="users-container">
        <?php if ($this->isAdmin()): ?>
            <div class="action-bar">
                <a href="/admin/users/create" class="btn btn-blue">
                    <i class="fas fa-plus"></i> Добавить пользователя
                </a>
                
                <!-- Массовые действия -->
                <div class="mass-actions">
                    <select id="massActionSelect" class="form-select">
                        <option value="">Массовые действия...</option>
                        <option value="block">Заблокировать выбранных</option>
                        <option value="unblock">Разблокировать выбранных</option>
                        <option value="delete">Удалить выбранных</option>
                        <option value="change_access">Изменить уровень доступа</option>
                        <option value="force_logout">Завершить сессии</option>
                    </select>
                    <button type="button" id="executeMassAction" class="btn btn-warning" disabled>
                        <i class="fas fa-play"></i> Выполнить
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Уведомления -->
        <?php if (isset($_SESSION['mass_action_result'])): ?>
            <div class="alert alert-<?= !empty($_SESSION['mass_action_result']['errors']) ? 'danger' : 'success' ?>">
                <h4>Результат массового действия</h4>
                <p>Успешно обработано: <?= $_SESSION['mass_action_result']['success_count'] ?> пользователей</p>
                <?php if (!empty($_SESSION['mass_action_result']['errors'])): ?>
                    <ul>
                        <?php foreach ($_SESSION['mass_action_result']['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php unset($_SESSION['mass_action_result']); ?>
        <?php endif; ?>

        <!-- Поиск пользователей -->
        <div class="search-section">
            <div class="search-container">
                <input type="text" id="userSearch" placeholder="Поиск пользователей..." class="search-input">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>

        <!-- Таблица пользователей -->
        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllUsers"></th>
                        <th>ID Логин</th>
                        <th>ФИО</th>
                        <th>Статус</th>
                        <th>Уровень доступа</th>
                        <th>Последний вход</th>
                        <th>Безопасность</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="user-row" data-user-id="<?= $user['user_id'] ?>">
                            <td>
                                <input type="checkbox" class="user-checkbox" value="<?= $user['user_id'] ?>">
                            </td>
                            <td>
                                <div class="user-login">
                                    <span class="login-id"><?= htmlspecialchars($user['user_id']) ?></span>
                                    <span class="login-name"><?= htmlspecialchars($user['user_login']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="user-info">
                                    <span class="user-name"><?= htmlspecialchars($user['user_fio']) ?></span>
                                    <?php if ($user['user_email']): ?>
                                        <span class="user-email"><?= htmlspecialchars($user['user_email']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="user-status">
                                    <?php if ($user['is_online']): ?>
                                        <span class="status-badge status-online">
                                            <i class="fas fa-circle"></i> ОНЛАЙН
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-offline">
                                            <i class="fas fa-circle"></i> ОФФЛАЙН
                                        </span>
                                    <?php endif; ?>
                                     <?php $isActive = (int)($user['user_is_active'] ?? $user['user_status'] ?? $user['is_active'] ?? 0); ?>
                                     <?php if ($isActive): ?>
                                        <span class="status-badge status-active">АКТИВЕН</span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">НЕАКТИВЕН</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="access-level">
                                    <span class="level-badge level-<?= $user['user_access_level'] ?>">
                                        <?= $this->getAccessLevelName($user['user_access_level']) ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="last-login">
                                    <div class="login-time"><?= date('d.m.Y H:i', strtotime($user['user_last_login'])) ?></div>
                                    <div class="login-ip"><?= htmlspecialchars($user['user_last_ip'] ?? 'N/A') ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="security-info">
                                    <?php $failedLogins = (int)($user['user_failed_logins'] ?? 0); ?>
                                    <?php if ($failedLogins > 0): ?>
                                        <span class="security-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <?= $failedLogins ?> неудачных попыток
                                        </span>
                                    <?php endif; ?>
                                    <?php $isBlocked = (int)($user['user_is_blocked'] ?? ((isset($user['user_status']) && (int)$user['user_status'] === 0) ? 1 : 0)); ?>
                                    <?php if ($isBlocked): ?>
                                        <span class="security-danger">
                                            <i class="fas fa-ban"></i> Заблокирован
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="user-actions">
                                    <a href="/admin/users/view/<?= $user['user_id'] ?>" class="btn btn-sm btn-info" title="Просмотр">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/users/edit/<?= $user['user_id'] ?>" class="btn btn-sm btn-warning" title="Редактировать">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-secondary" onclick="showUserMenu(<?= $user['user_id'] ?>)" title="Действия">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Пагинация -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?= $currentPage - 1 ?>" class="page-link">
                        <i class="fas fa-chevron-left"></i> Предыдущая
                    </a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="?page=<?= $i ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1 ?>" class="page-link">
                        Следующая <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Функции для работы с пользователями
function showUserMenu(userId) {
    // Показать контекстное меню для пользователя
    console.log('Показать меню для пользователя:', userId);
}

// Массовые действия
document.getElementById('selectAllUsers').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateMassActionButton();
});

document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateMassActionButton);
});

function updateMassActionButton() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const executeButton = document.getElementById('executeMassAction');
    const massActionSelect = document.getElementById('massActionSelect');
    
    executeButton.disabled = checkedBoxes.length === 0 || massActionSelect.value === '';
}

document.getElementById('massActionSelect').addEventListener('change', updateMassActionButton);

document.getElementById('executeMassAction').addEventListener('click', function() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const action = document.getElementById('massActionSelect').value;
    
    if (checkedBoxes.length === 0 || !action) {
        alert('Выберите пользователей и действие');
        return;
    }
    
    const userIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (confirm(`Выполнить действие "${action}" для ${userIds.length} пользователей?`)) {
        // Отправка запроса на сервер
        fetch('/admin/users/mass-action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: action,
                user_ids: userIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Ошибка при выполнении действия: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при выполнении действия');
        });
    }
});

// Поиск пользователей
document.getElementById('userSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script> 