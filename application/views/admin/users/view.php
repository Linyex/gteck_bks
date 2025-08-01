
<div class="cyberpunk-container">
    <div class="cyberpunk-header">
        <h1 class="cyberpunk-title">👤 Детали пользователя</h1>
        <div class="cyberpunk-actions">
            <a href="/admin/users" class="cyberpunk-btn cyberpunk-btn-secondary">
                <span class="btn-icon">←</span> Назад к списку
            </a>
            <?php if ($_SESSION['admin_access_level'] >= 10): ?>
            <a href="/admin/users/edit/<?= $user['user_id'] ?>" class="cyberpunk-btn cyberpunk-btn-primary">
                <span class="btn-icon">✏️</span> Редактировать
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="cyberpunk-grid">
        <!-- Основная информация -->
        <div class="cyberpunk-card">
            <div class="card-header">
                <h3 class="card-title">📋 Основная информация</h3>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label>ID пользователя:</label>
                        <span class="info-value"><?= htmlspecialchars($user['user_id']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Логин:</label>
                        <span class="info-value"><?= htmlspecialchars($user['user_login']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>ФИО:</label>
                        <span class="info-value"><?= htmlspecialchars($user['user_fio']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Email:</label>
                        <span class="info-value"><?= htmlspecialchars($user['user_email']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Дата регистрации:</label>
                        <span class="info-value"><?= date('d.m.Y H:i', strtotime($user['user_date_reg'])) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Статус:</label>
                        <span class="status-badge status-<?= $user['user_status'] ? 'active' : 'blocked' ?>">
                            <?= $user['user_status'] ? 'Активен' : 'Заблокирован' ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Уровень доступа:</label>
                        <span class="access-level-badge level-<?= $user['user_access_level'] ?>">
                            <?= $this->getAccessLevelName($user['user_access_level']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Анализ безопасности -->
        <div class="cyberpunk-card">
            <div class="card-header">
                <h3 class="card-title">🛡️ Анализ безопасности</h3>
            </div>
            <div class="card-content">
                <div class="security-indicator">
                    <div class="indicator-label">Уровень риска:</div>
                    <div class="risk-level risk-<?= $securityAnalysis['risk_level'] ?>">
                        <?= ucfirst($securityAnalysis['risk_level']) ?>
                    </div>
                </div>
                
                <?php if (!empty($securityAnalysis['issues'])): ?>
                <div class="security-issues">
                    <h4>⚠️ Обнаруженные проблемы:</h4>
                    <ul class="issues-list">
                        <?php foreach ($securityAnalysis['issues'] as $issue): ?>
                        <li class="issue-item"><?= htmlspecialchars($issue) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($securityAnalysis['recommendations'])): ?>
                <div class="security-recommendations">
                    <h4>💡 Рекомендации:</h4>
                    <ul class="recommendations-list">
                        <?php foreach ($securityAnalysis['recommendations'] as $recommendation): ?>
                        <li class="recommendation-item"><?= htmlspecialchars($recommendation) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Статистика активности -->
        <div class="cyberpunk-card">
            <div class="card-header">
                <h3 class="card-title">📊 Статистика активности</h3>
            </div>
            <div class="card-content">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value"><?= $user['user_login_count'] ?? 0 ?></div>
                        <div class="stat-label">Успешных входов</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= $user['user_failed_login_count'] ?? 0 ?></div>
                        <div class="stat-label">Неудачных попыток</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= count($userSessions) ?></div>
                        <div class="stat-label">Активных сессий</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= count($suspiciousActions) ?></div>
                        <div class="stat-label">Подозрительных действий</div>
                    </div>
                </div>
                
                <?php if ($user['user_last_login']): ?>
                <div class="last-activity">
                    <strong>Последний вход:</strong> 
                    <?= date('d.m.Y H:i:s', strtotime($user['user_last_login'])) ?>
                    <?php if ($user['user_last_ip']): ?>
                    <br><strong>IP адрес:</strong> <?= htmlspecialchars($user['user_last_ip']) ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Последние сессии -->
        <div class="cyberpunk-card">
            <div class="card-header">
                <h3 class="card-title">🔐 Последние сессии</h3>
            </div>
            <div class="card-content">
                <?php if (!empty($userSessions)): ?>
                <div class="sessions-list">
                    <?php foreach (array_slice($userSessions, 0, 5) as $session): ?>
                    <div class="session-item <?= $session['suspicious'] ? 'suspicious' : '' ?>">
                        <div class="session-header">
                            <span class="session-time"><?= date('d.m.Y H:i', strtotime($session['login_time'])) ?></span>
                            <?php if ($session['suspicious']): ?>
                            <span class="suspicious-badge">⚠️ Подозрительно</span>
                            <?php endif; ?>
                        </div>
                        <div class="session-details">
                            <div><strong>IP:</strong> <?= htmlspecialchars($session['ip_address']) ?></div>
                            <?php if ($session['location_country']): ?>
                            <div><strong>Местоположение:</strong> <?= htmlspecialchars($session['location_country']) ?></div>
                            <?php endif; ?>
                            <?php if ($session['browser']): ?>
                            <div><strong>Браузер:</strong> <?= htmlspecialchars($session['browser']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="no-data">Нет данных о сессиях</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Подозрительные действия -->
        <?php if (!empty($suspiciousActions)): ?>
        <div class="cyberpunk-card">
            <div class="card-header">
                <h3 class="card-title">⚠️ Подозрительные действия</h3>
            </div>
            <div class="card-content">
                <div class="suspicious-actions">
                    <?php foreach (array_slice($suspiciousActions, 0, 3) as $action): ?>
                    <div class="action-item">
                        <div class="action-time"><?= date('d.m.Y H:i', strtotime($action['activity_time'])) ?></div>
                        <div class="action-description"><?= htmlspecialchars($action['activity_description']) ?></div>
                        <?php if ($action['ip_address']): ?>
                        <div class="action-ip">IP: <?= htmlspecialchars($action['ip_address']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Действия администратора -->
        <?php if ($_SESSION['admin_access_level'] >= 10): ?>
        <div class="cyberpunk-card">
            <div class="card-header">
                <h3 class="card-title">⚙️ Действия администратора</h3>
            </div>
            <div class="card-content">
                <div class="admin-actions">
                    <?php if ($user['user_status']): ?>
                    <button class="cyberpunk-btn cyberpunk-btn-danger" onclick="blockUser(<?= $user['user_id'] ?>)">
                        <span class="btn-icon">🚫</span> Заблокировать
                    </button>
                    <?php else: ?>
                    <button class="cyberpunk-btn cyberpunk-btn-success" onclick="unblockUser(<?= $user['user_id'] ?>)">
                        <span class="btn-icon">✅</span> Разблокировать
                    </button>
                    <?php endif; ?>
                    
                    <button class="cyberpunk-btn cyberpunk-btn-warning" onclick="resetPassword(<?= $user['user_id'] ?>)">
                        <span class="btn-icon">🔑</span> Сбросить пароль
                    </button>
                    
                    <button class="cyberpunk-btn cyberpunk-btn-danger" onclick="deleteUser(<?= $user['user_id'] ?>)">
                        <span class="btn-icon">🗑️</span> Удалить
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Модальные окна -->
<div id="blockUserModal" class="cyberpunk-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>🚫 Блокировка пользователя</h3>
            <span class="modal-close" onclick="closeModal('blockUserModal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="blockUserForm">
                <div class="form-group">
                    <label for="blockReason">Причина блокировки:</label>
                    <textarea id="blockReason" name="reason" class="cyberpunk-input" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="blockDuration">Тип блокировки:</label>
                    <select id="blockDuration" name="duration" class="cyberpunk-select" onchange="toggleBlockUntil()">
                        <option value="permanent">Постоянная</option>
                        <option value="temporary">Временная</option>
                    </select>
                </div>
                <div class="form-group" id="blockUntilGroup" style="display: none;">
                    <label for="blockUntil">Дата разблокировки:</label>
                    <input type="datetime-local" id="blockUntil" name="block_until" class="cyberpunk-input">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="cyberpunk-btn cyberpunk-btn-secondary" onclick="closeModal('blockUserModal')">Отмена</button>
            <button class="cyberpunk-btn cyberpunk-btn-danger" onclick="confirmBlockUser()">Заблокировать</button>
        </div>
    </div>
</div>

<div id="deleteUserModal" class="cyberpunk-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>🗑️ Удаление пользователя</h3>
            <span class="modal-close" onclick="closeModal('deleteUserModal')">&times;</span>
        </div>
        <div class="modal-body">
            <p>⚠️ Внимание! Это действие необратимо.</p>
            <p>Пользователь <strong><?= htmlspecialchars($user['user_fio']) ?></strong> будет удален из системы.</p>
            <p>Все данные пользователя будут потеряны.</p>
        </div>
        <div class="modal-footer">
            <button class="cyberpunk-btn cyberpunk-btn-secondary" onclick="closeModal('deleteUserModal')">Отмена</button>
            <button class="cyberpunk-btn cyberpunk-btn-danger" onclick="confirmDeleteUser()">Удалить</button>
        </div>
    </div>
</div>

<script>
let currentUserId = <?= $user['user_id'] ?>;

function blockUser(userId) {
    currentUserId = userId;
    document.getElementById('blockUserModal').style.display = 'block';
}

function unblockUser(userId) {
    if (confirm('Разблокировать пользователя?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/users/unblock/' + userId;
        document.body.appendChild(form);
        form.submit();
    }
}

function resetPassword(userId) {
    if (confirm('Сбросить пароль пользователя? Новый пароль будет отправлен на email.')) {
        // Здесь будет AJAX запрос для сброса пароля
        alert('Функция сброса пароля будет реализована позже');
    }
}

function deleteUser(userId) {
    currentUserId = userId;
    document.getElementById('deleteUserModal').style.display = 'block';
}

function confirmBlockUser() {
    const form = document.getElementById('blockUserForm');
    const formData = new FormData(form);
    
    fetch('/admin/users/block/' + currentUserId, {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            window.location.reload();
        } else {
            alert('Ошибка при блокировке пользователя');
        }
    });
}

function confirmDeleteUser() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/users/delete/' + currentUserId;
    document.body.appendChild(form);
    form.submit();
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
window.onclick = function(event) {
    const modals = document.querySelectorAll('.cyberpunk-modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
</script>
