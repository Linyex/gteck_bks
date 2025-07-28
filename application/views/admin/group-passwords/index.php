<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">🔐 Управление паролями групп</h1>
            <p class="admin-subtitle">Настройка доступа к контрольным работам и УМК</p>
        </div>
        <div class="admin-actions">
            <a href="/admin/group-passwords/create" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                Добавить пароль
            </a>
        </div>
    </div>
</div>

<div class="admin-content">
    <div class="admin-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa fa-list"></i>
                Пароли групп
            </div>
            <div class="card-stats">
                <span class="stat-badge">
                    <i class="fa fa-users"></i>
                    <?php echo count($passwords); ?> групп
                </span>
            </div>
        </div>

        <div class="card-body">
            <?php if (!empty($passwords)): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Группа</th>
                                <th>Описание</th>
                                <th>Статус</th>
                                <th>Создано</th>
                                <th>Обновлено</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($passwords as $password): ?>
                                <tr>
                                    <td>
                                        <div class="group-info">
                                            <div class="group-badge"><?php echo htmlspecialchars($password['group_name']); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="description-cell">
                                            <?php echo htmlspecialchars($password['description'] ?: 'Нет описания'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <label class="switch" data-id="<?php echo $password['id']; ?>">
                                            <input type="checkbox" class="status-toggle" <?php echo $password['is_active'] ? 'checked' : ''; ?>>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <div class="date"><?php echo date('d.m.Y', strtotime($password['created_at'])); ?></div>
                                            <div class="time"><?php echo date('H:i', strtotime($password['created_at'])); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <div class="date"><?php echo date('d.m.Y', strtotime($password['updated_at'])); ?></div>
                                            <div class="time"><?php echo date('H:i', strtotime($password['updated_at'])); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/admin/group-passwords/edit/<?php echo $password['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Редактировать">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                    data-id="<?php echo $password['id']; ?>"
                                                    data-group="<?php echo htmlspecialchars($password['group_name']); ?>"
                                                    title="Удалить">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">🔐</div>
                    <h3>Пароли групп не найдены</h3>
                    <p>Добавьте первый пароль группы для настройки доступа</p>
                    <a href="/admin/group-passwords/create" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        Добавить пароль
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.group-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.group-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
}

.description-cell {
    max-width: 300px;
    color: #6B7280;
    line-height: 1.4;
}

.date-cell {
    text-align: center;
}

.date {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.time {
    color: #9CA3AF;
    font-size: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

/* Switch Styles */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #CBD5E0;
    transition: 0.3s;
    border-radius: 26px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

input:checked + .slider {
    background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
}

input:checked + .slider:before {
    transform: translateX(24px);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #374151;
    margin-bottom: 10px;
    font-size: 1.5rem;
}

.empty-state p {
    color: #6B7280;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .admin-table {
        font-size: 0.9rem;
    }
    
    .description-cell {
        max-width: 200px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Переключатели статуса
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.closest('.switch').dataset.id;
            const isActive = this.checked;
            
            fetch('/admin/group-passwords/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    this.checked = !isActive; // Возврат к предыдущему состоянию
                    showNotification('error', data.message);
                } else {
                    showNotification('success', data.message);
                }
            })
            .catch(error => {
                this.checked = !isActive; // Возврат к предыдущему состоянию
                showNotification('error', 'Ошибка при изменении статуса');
            });
        });
    });

    // Кнопки удаления
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const group = this.dataset.group;
            
            if (confirm(`Вы уверены, что хотите удалить пароль для группы "${group}"?`)) {
                fetch('/admin/group-passwords/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('tr').remove();
                        showNotification('success', data.message);
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    showNotification('error', 'Ошибка при удалении');
                });
            }
        });
    });
});

function showNotification(type, message) {
    // Простая реализация уведомлений
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fa fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
            ${message}
        </div>
    `;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 8px;
        color: white;
        z-index: 1000;
        background: ${type === 'success' ? 'linear-gradient(135deg, #10B981 0%, #34D399 100%)' : 'linear-gradient(135deg, #EF4444 0%, #F87171 100%)'};
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script> 