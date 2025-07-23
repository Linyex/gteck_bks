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

        <?php if (isset($_SESSION['password_reset_result'])): ?>
            <div class="alert alert-info">
                <h4>Пароль сброшен</h4>
                <p>Пользователь: <strong><?= htmlspecialchars($_SESSION['password_reset_result']['user_login']) ?></strong></p>
                <p>Новый пароль: <code><?= htmlspecialchars($_SESSION['password_reset_result']['new_password']) ?></code></p>
                <p><small>Сохраните этот пароль! Он больше не будет показан.</small></p>
            </div>
            <?php unset($_SESSION['password_reset_result']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="cyber-card">
            <div class="card-header">
                <h3>Список пользователей (<?= $totalUsers ?>)</h3>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Поиск пользователей..." class="form-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <?php if ($this->isAdmin()): ?>
                                <th>
                                    <input type="checkbox" id="selectAll" title="Выбрать всех">
                                </th>
                            <?php endif; ?>
                            <th>ID</th>
                            <th>Логин</th>
                            <th>ФИО</th>
                            <th>Статус</th>
                            <th>Уровень доступа</th>
                            <th>Последний вход</th>
                            <th>Безопасность</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="user-row" data-user-id="<?= $user['user_id'] ?>">
                                    <?php if ($this->isAdmin()): ?>
                                        <td>
                                            <input type="checkbox" class="user-checkbox" value="<?= $user['user_id'] ?>" 
                                                   <?= $user['user_id'] == $_SESSION['admin_user_id'] ? 'disabled' : '' ?>>
                                        </td>
                                    <?php endif; ?>
                                    <td><?= $user['user_id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($user['user_login']) ?></strong>
                                        <?php if ($user['user_id'] == $_SESSION['admin_user_id']): ?>
                                            <span class="badge badge-current">Вы</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($user['user_fio']) ?></td>
                                    <td>
                                        <?php if ($user['user_status']): ?>
                                            <span class="badge badge-success">Активен</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">
                                                Заблокирован
                                                <?php if (!empty($user['user_block_reason'])): ?>
                                                    <i class="fas fa-info-circle" title="<?= htmlspecialchars($user['user_block_reason']) ?>"></i>
                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $levelText = '';
                                        $levelClass = '';
                                        switch ($user['user_access_level']) {
                                            case 10:
                                                $levelText = 'Администратор';
                                                $levelClass = 'badge-danger';
                                                break;
                                            case 9:
                                                $levelText = 'Дирекция колледжа';
                                                $levelClass = 'badge-warning';
                                                break;
                                            case 8:
                                                $levelText = 'Заведующий отделением';
                                                $levelClass = 'badge-info';
                                                break;
                                            case 7:
                                                $levelText = 'Методист';
                                                $levelClass = 'badge-info';
                                                break;
                                            case 6:
                                                $levelText = 'СППС';
                                                $levelClass = 'badge-info';
                                                break;
                                            default:
                                                $levelText = 'Преподаватель';
                                                $levelClass = 'badge-info';
                                        }
                                        ?>
                                        <span class="badge <?= $levelClass ?>"><?= $levelText ?></span>
                                    </td>
                                    <td>
                                        <?php if ($user['user_last_login']): ?>
                                            <div class="last-login">
                                                <div class="login-time"><?= date('d.m.Y H:i', strtotime($user['user_last_login'])) ?></div>
                                                <?php if ($user['user_last_ip']): ?>
                                                    <div class="login-ip"><?= htmlspecialchars($user['user_last_ip']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Не входил</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="security-indicators">
                                            <?php if (($user['user_failed_login_count'] ?? 0) > 5): ?>
                                                <span class="security-badge security-warning" title="Множественные неудачные попытки входа">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (($user['user_login_count'] ?? 0) > 0): ?>
                                                <span class="security-badge security-info" title="Входов: <?= $user['user_login_count'] ?>">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (($user['user_force_password_change'] ?? 0) == 1): ?>
                                                <span class="security-badge security-danger" title="Требуется смена пароля">
                                                    <i class="fas fa-key"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/admin/users/view/<?= $user['user_id'] ?>" class="btn btn-sm btn-info" title="Просмотр">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($this->isAdmin()): ?>
                                                <a href="/admin/users/edit/<?= $user['user_id'] ?>" class="btn btn-sm btn-blue" title="Редактировать">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Выпадающее меню дополнительных действий -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <form method="POST" action="/admin/users/reset-password/<?= $user['user_id'] ?>" style="display: inline;">
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Сбросить пароль пользователя?')">
                                                                <i class="fas fa-key"></i> Сбросить пароль
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="/admin/users/force-password-change/<?= $user['user_id'] ?>" style="display: inline;">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-lock"></i> Принудительная смена пароля
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="/admin/users/terminate-sessions/<?= $user['user_id'] ?>" style="display: inline;">
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Завершить все сессии пользователя?')">
                                                                <i class="fas fa-sign-out-alt"></i> Завершить сессии
                                                            </button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <?php if ($user['user_status']): ?>
                                                            <button type="button" class="dropdown-item" 
                                                                    onclick="blockUser(<?= $user['user_id'] ?>, '<?= htmlspecialchars($user['user_fio']) ?>')">
                                                                <i class="fas fa-ban"></i> Заблокировать
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" class="dropdown-item" 
                                                                    onclick="unblockUser(<?= $user['user_id'] ?>, '<?= htmlspecialchars($user['user_fio']) ?>')">
                                                                <i class="fas fa-unlock"></i> Разблокировать
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($user['user_id'] != $_SESSION['admin_user_id']): ?>
                                                            <button type="button" class="dropdown-item text-danger" 
                                                                    onclick="deleteUser(<?= $user['user_id'] ?>, '<?= htmlspecialchars($user['user_fio']) ?>')">
                                                                <i class="fas fa-trash"></i> Удалить
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $this->isAdmin() ? '9' : '8' ?>" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <p>Пользователей пока нет</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pagination-container">
                    <nav class="pagination-nav">
                        <?php if ($currentPage > 1): ?>
                            <a href="/admin/users?page=<?= $currentPage - 1 ?>" class="page-link">
                                <i class="fas fa-chevron-left"></i> Назад
                            </a>
                        <?php endif; ?>
                        
                        <div class="page-numbers">
                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <a href="/admin/users?page=<?= $i ?>" class="page-link <?= $i == $currentPage ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="/admin/users?page=<?= $currentPage + 1 ?>" class="page-link">
                                Вперед <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Модальные окна -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Подтверждение удаления</h3>
            <button type="button" class="modal-close" onclick="closeModal('deleteModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Вы действительно хотите удалить пользователя <strong id="userName"></strong>?</p>
            <p class="warning-text">Это действие нельзя отменить!</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">Отмена</button>
            <form id="deleteForm" method="POST">
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        </div>
    </div>
</div>

<div id="blockModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Блокировка пользователя</h3>
            <button type="button" class="modal-close" onclick="closeModal('blockModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Блокировка пользователя <strong id="blockUserName"></strong></p>
            <form id="blockForm" method="POST">
                <div class="form-group">
                    <label for="blockReason" class="form-label">Причина блокировки</label>
                    <textarea id="blockReason" name="reason" class="form-input" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="blockDuration" class="form-label">Тип блокировки</label>
                    <select id="blockDuration" name="duration" class="form-input" onchange="toggleBlockUntil()">
                        <option value="permanent">Навсегда</option>
                        <option value="temporary">Временно</option>
                    </select>
                </div>
                <div class="form-group" id="blockUntilGroup" style="display: none;">
                    <label for="blockUntil" class="form-label">Дата разблокировки</label>
                    <input type="datetime-local" id="blockUntil" name="block_until" class="form-input">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('blockModal')">Отмена</button>
            <button type="submit" form="blockForm" class="btn btn-warning">Заблокировать</button>
        </div>
    </div>
</div>

<div id="unblockModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Разблокировка пользователя</h3>
            <button type="button" class="modal-close" onclick="closeModal('unblockModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Вы действительно хотите разблокировать пользователя <strong id="unblockUserName"></strong>?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('unblockModal')">Отмена</button>
            <form id="unblockForm" method="POST">
                <button type="submit" class="btn btn-success">Разблокировать</button>
            </form>
        </div>
    </div>
</div>

<style>
.security-dashboard {
    margin-bottom: 30px;
}

.security-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    background: linear-gradient(135deg, var(--medium-gray) 0%, var(--light-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: var(--glow-blue);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
}

.stat-card.security-warning {
    border-color: var(--primary-yellow);
    box-shadow: var(--glow-yellow);
}

.stat-card.security-danger {
    border-color: #ff4757;
    box-shadow: 0 0 20px rgba(255, 71, 87, 0.5);
}

.stat-card.security-info {
    border-color: var(--primary-blue);
    box-shadow: var(--glow-blue);
}

.stat-card.security-success {
    border-color: #28a745;
    box-shadow: 0 0 20px rgba(40, 167, 69, 0.5);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 24px;
    color: var(--text-white);
}

.security-warning .stat-icon {
    background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow));
    color: #000;
}

.security-danger .stat-icon {
    background: linear-gradient(135deg, #ff4757, #ff3742);
}

.security-info .stat-icon {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: #000;
}

.security-success .stat-icon {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: #000;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--text-white);
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: var(--text-blue);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.security-alerts {
    margin-bottom: 30px;
}

.security-alerts h3 {
    color: var(--text-yellow);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alerts-container {
    display: grid;
    gap: 10px;
}

.alert-item {
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-yellow);
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    box-shadow: var(--glow-yellow);
    transition: all 0.3s ease;
}

.alert-item:hover {
    transform: translateX(5px);
}

.alert-item.alert-high {
    border-color: #ff4757;
    box-shadow: 0 0 20px rgba(255, 71, 87, 0.3);
}

.alert-item.alert-medium {
    border-color: var(--primary-yellow);
    box-shadow: var(--glow-yellow);
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow));
    color: #000;
}

.alert-high .alert-icon {
    background: linear-gradient(135deg, #ff4757, #ff3742);
    color: #fff;
}

.alert-content {
    flex: 1;
}

.alert-title {
    font-weight: bold;
    color: var(--text-yellow);
    margin-bottom: 5px;
}

.alert-description {
    color: var(--text-white);
    margin-bottom: 5px;
}

.alert-time {
    font-size: 12px;
    color: var(--text-blue);
    opacity: 0.8;
}

.last-login {
    font-size: 12px;
}

.login-time {
    color: var(--text-white);
    font-weight: 500;
}

.login-ip {
    color: var(--text-blue);
    font-family: monospace;
}

.security-indicators {
    display: flex;
    gap: 5px;
}

.security-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    cursor: help;
}

.security-badge.security-warning {
    background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow));
    color: #000;
}

.security-badge.security-info {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: #000;
}

.users-container {
    padding: 20px;
}

.action-bar {
    margin-bottom: 20px;
    display: flex;
    justify-content: flex-end;
}

.search-box {
    position: relative;
    max-width: 300px;
}

.search-box .form-input {
    padding-right: 40px;
}

.search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-blue);
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: #000;
}

.badge-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: #fff;
}

.badge-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: #fff;
}

.badge-warning {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    color: #000;
}

.badge-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: #fff;
}

.badge-current {
    background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow));
    color: #000;
    font-size: 10px;
    margin-left: 5px;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-sm {
    padding: 6px 10px;
    font-size: 12px;
}

.empty-state {
    padding: 40px;
    text-align: center;
    color: var(--text-blue);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 16px;
    opacity: 0.8;
}

.pagination-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

.pagination-nav {
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-numbers {
    display: flex;
    gap: 5px;
}

.page-link {
    padding: 8px 12px;
    border: 1px solid var(--primary-blue);
    background: transparent;
    color: var(--text-white);
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: var(--primary-blue);
    color: #000;
    box-shadow: var(--glow-blue);
}

.page-link.active {
    background: var(--primary-blue);
    color: #000;
    box-shadow: var(--glow-blue);
}

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    backdrop-filter: blur(5px);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 12px;
    padding: 0;
    min-width: 400px;
    box-shadow: var(--glow-blue);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid var(--primary-blue);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    color: var(--text-yellow);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-white);
    font-size: 18px;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: var(--primary-blue);
    color: #000;
}

.modal-body {
    padding: 20px;
}

.modal-body p {
    margin: 0 0 10px 0;
    color: var(--text-white);
}

.warning-text {
    color: var(--primary-yellow) !important;
    font-size: 14px;
    font-style: italic;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid var(--primary-blue);
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    .security-stats {
        grid-template-columns: 1fr;
    }
    
    .users-container {
        padding: 10px;
    }
    
    .action-bar {
        justify-content: center;
    }
    
    .search-box {
        max-width: 100%;
        margin-top: 15px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modal-content {
        min-width: 90%;
        margin: 20px;
    }
}
</style>

<script>
// Массовые действия
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const massActionSelect = document.getElementById('massActionSelect');
    const executeMassActionBtn = document.getElementById('executeMassAction');
    
    // Обработчик "выбрать всех"
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = this.checked;
                }
            });
            updateMassActionButton();
        });
    }
    
    // Обработчики отдельных чекбоксов
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllCheckbox();
            updateMassActionButton();
        });
    });
    
    // Обработчик выбора массового действия
    massActionSelect.addEventListener('change', function() {
        updateMassActionButton();
    });
    
    // Обработчик выполнения массового действия
    executeMassActionBtn.addEventListener('click', function() {
        const action = massActionSelect.value;
        const selectedUsers = getSelectedUsers();
        
        if (!action || selectedUsers.length === 0) {
            return;
        }
        
        let confirmMessage = '';
        let showAccessLevelInput = false;
        
        switch (action) {
            case 'block':
                confirmMessage = `Заблокировать ${selectedUsers.length} пользователей?`;
                break;
            case 'unblock':
                confirmMessage = `Разблокировать ${selectedUsers.length} пользователей?`;
                break;
            case 'delete':
                confirmMessage = `УДАЛИТЬ ${selectedUsers.length} ПОЛЬЗОВАТЕЛЕЙ? Это действие нельзя отменить!`;
                break;
            case 'change_access':
                confirmMessage = `Изменить уровень доступа для ${selectedUsers.length} пользователей?`;
                showAccessLevelInput = true;
                break;
            case 'force_logout':
                confirmMessage = `Завершить сессии для ${selectedUsers.length} пользователей?`;
                break;
        }
        
        if (showAccessLevelInput) {
            showAccessLevelModal(selectedUsers);
        } else if (confirm(confirmMessage)) {
            executeMassAction(action, selectedUsers);
        }
    });
    
    function updateSelectAllCheckbox() {
        const checkedBoxes = Array.from(userCheckboxes).filter(cb => cb.checked);
        const enabledBoxes = Array.from(userCheckboxes).filter(cb => !cb.disabled);
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedBoxes.length === enabledBoxes.length && enabledBoxes.length > 0;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < enabledBoxes.length;
        }
    }
    
    function updateMassActionButton() {
        const selectedUsers = getSelectedUsers();
        const action = massActionSelect.value;
        
        executeMassActionBtn.disabled = selectedUsers.length === 0 || !action;
    }
    
    function getSelectedUsers() {
        return Array.from(userCheckboxes)
            .filter(cb => cb.checked && !cb.disabled)
            .map(cb => cb.value);
    }
    
    function executeMassAction(action, userIds) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/users/mass-action';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        userIds.forEach(userId => {
            const userInput = document.createElement('input');
            userInput.type = 'hidden';
            userInput.name = 'user_ids[]';
            userInput.value = userId;
            form.appendChild(userInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
    
    function showAccessLevelModal(userIds) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Изменить уровень доступа</h3>
                    <button type="button" class="modal-close" onclick="this.closest('.modal-overlay').remove()">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Выбрано пользователей: ${userIds.length}</p>
                    <div class="form-group">
                        <label for="newAccessLevel">Новый уровень доступа:</label>
                        <select id="newAccessLevel" class="form-select">
                            <option value="1">Пользователь</option>
                            <option value="5">Модератор</option>
                            <option value="10">Администратор</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="this.closest('.modal-overlay').remove()">Отмена</button>
                    <button type="button" class="btn btn-warning" onclick="executeMassActionWithAccessLevel(${JSON.stringify(userIds)})">Изменить</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
});

// Глобальная функция для выполнения массового действия с уровнем доступа
function executeMassActionWithAccessLevel(userIds) {
    const newAccessLevel = document.getElementById('newAccessLevel').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/users/mass-action';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'change_access';
    form.appendChild(actionInput);
    
    const accessLevelInput = document.createElement('input');
    accessLevelInput.type = 'hidden';
    accessLevelInput.name = 'new_access_level';
    accessLevelInput.value = newAccessLevel;
    form.appendChild(accessLevelInput);
    
    userIds.forEach(userId => {
        const userInput = document.createElement('input');
        userInput.type = 'hidden';
        userInput.name = 'user_ids[]';
        userInput.value = userId;
        form.appendChild(userInput);
    });
    
    document.body.appendChild(form);
    form.submit();
}

function deleteUser(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = '/admin/users/delete/' + userId;
    document.getElementById('deleteModal').style.display = 'block';
}

function blockUser(userId, userName) {
    document.getElementById('blockUserName').textContent = userName;
    document.getElementById('blockForm').action = '/admin/users/block/' + userId;
    document.getElementById('blockModal').style.display = 'block';
}

function unblockUser(userId, userName) {
    document.getElementById('unblockUserName').textContent = userName;
    document.getElementById('unblockForm').action = '/admin/users/unblock/' + userId;
    document.getElementById('unblockModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function toggleBlockUntil() {
    const duration = document.getElementById('blockDuration').value;
    const blockUntilGroup = document.getElementById('blockUntilGroup');
    
    if (duration === 'temporary') {
        blockUntilGroup.style.display = 'block';
    } else {
        blockUntilGroup.style.display = 'none';
    }
}

// Закрытие модальных окон при клике вне их
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

// Поиск пользователей
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Автообновление уведомлений каждые 30 секунд
setInterval(function() {
    // Здесь можно добавить AJAX запрос для обновления уведомлений
    console.log('Checking for new security notifications...');
}, 30000);

// Добавляем стили для массовых действий
const style = document.createElement('style');
style.textContent = `
.mass-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.mass-actions select {
    min-width: 200px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: var(--text-white);
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    background: var(--medium-gray);
    border: 1px solid var(--primary-blue);
    color: var(--text-white);
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dropdown-toggle:hover {
    background: var(--primary-blue);
    color: #000;
}

.dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: var(--dark-gray);
    border: 1px solid var(--primary-blue);
    border-radius: 4px;
    min-width: 200px;
    z-index: 1000;
    box-shadow: var(--glow-blue);
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.dropdown-item {
    display: block;
    width: 100%;
    padding: 10px 15px;
    background: none;
    border: none;
    color: var(--text-white);
    text-align: left;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background: var(--primary-blue);
    color: #000;
}

.dropdown-item.text-danger:hover {
    background: var(--primary-red);
    color: #fff;
}

.dropdown-divider {
    height: 1px;
    background: var(--primary-blue);
    margin: 5px 0;
}

#selectAll {
    cursor: pointer;
}

.user-checkbox {
    cursor: pointer;
}

.user-checkbox:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}
`;
document.head.appendChild(style);
</script> 