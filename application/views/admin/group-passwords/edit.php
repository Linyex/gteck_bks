<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">✏️ Редактировать пароль группы</h1>
            <p class="admin-subtitle">Изменение настроек доступа для группы <?php echo htmlspecialchars($password['group_name']); ?></p>
        </div>
        <div class="admin-actions">
            <a href="/admin/group-passwords" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left"></i>
                Назад к списку
            </a>
        </div>
    </div>
</div>

<div class="admin-content">
    <div class="form-container">
        <div class="admin-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa fa-edit"></i>
                    Параметры пароля группы
                </div>
                <div class="card-badge">
                    <span class="status-badge <?php echo $password['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $password['is_active'] ? 'Активен' : 'Неактивен'; ?>
                    </span>
                </div>
            </div>

            <div class="card-body">
                <form method="POST" class="admin-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="group_name" class="form-label required">
                                <i class="fa fa-users"></i>
                                Название группы
                            </label>
                            <input type="text" 
                                   id="group_name" 
                                   name="group_name" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($password['group_name']); ?>"
                                   pattern="[А-Яа-яA-Za-z0-9-]+"
                                   title="Только буквы, цифры и дефис"
                                   required>
                            <div class="form-help">
                                Код группы (например: T111, Э201, Б301)
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fa fa-lock"></i>
                                Новый пароль
                            </label>
                            <div class="password-field">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control" 
                                       placeholder="Оставьте пустым, чтобы не менять"
                                       minlength="3">
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-help">
                                Оставьте поле пустым, если не хотите менять пароль
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="description" class="form-label">
                                <i class="fa fa-info-circle"></i>
                                Описание
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Краткое описание группы"><?php echo htmlspecialchars($password['description']); ?></textarea>
                            <div class="form-help">
                                Описание для удобства администрирования
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           class="checkbox-input"
                                           <?php echo $password['is_active'] ? 'checked' : ''; ?>>
                                    <span class="checkbox-custom"></span>
                                    <span class="checkbox-text">
                                        <strong>Активный пароль</strong>
                                        <span class="checkbox-description">Разрешить доступ к материалам по этому паролю</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>
                            Сохранить изменения
                        </button>
                        <a href="/admin/group-passwords" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i>
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Информация о группе -->
        <div class="admin-card info-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa fa-info-circle"></i>
                    Информация
                </div>
            </div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-label">Создано:</div>
                        <div class="info-value">
                            <?php echo date('d.m.Y в H:i', strtotime($password['created_at'])); ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Обновлено:</div>
                        <div class="info-value">
                            <?php echo date('d.m.Y в H:i', strtotime($password['updated_at'])); ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Статус:</div>
                        <div class="info-value">
                            <span class="status-badge <?php echo $password['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $password['is_active'] ? '✅ Активен' : '❌ Неактивен'; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="security-notice">
                    <div class="notice-icon">🔐</div>
                    <div class="notice-content">
                        <h4>Безопасность</h4>
                        <p>При изменении пароля все студенты группы должны будут использовать новый пароль для доступа к материалам.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.card-badge {
    display: flex;
    align-items: center;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.active {
    background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
    color: white;
}

.status-badge.inactive {
    background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
    color: white;
}

.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6B7280;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #374151;
}

.checkbox-group {
    padding: 20px;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    cursor: pointer;
    margin: 0;
}

.checkbox-input {
    display: none;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #D1D5DB;
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.checkbox-input:checked + .checkbox-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

.checkbox-input:checked + .checkbox-custom::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.checkbox-text {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.checkbox-description {
    color: #6B7280;
    font-size: 0.9rem;
    font-weight: normal;
}

.form-actions {
    display: flex;
    gap: 15px;
    padding-top: 20px;
    border-top: 1px solid #E5E7EB;
}

.info-card {
    height: fit-content;
}

.info-list {
    margin-bottom: 25px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.info-value {
    color: #6B7280;
    font-size: 0.9rem;
}

.security-notice {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: rgba(255, 193, 7, 0.1);
    border-radius: 12px;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.notice-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.notice-content h4 {
    margin: 0 0 8px 0;
    color: #92400E;
    font-size: 1rem;
    font-weight: 600;
}

.notice-content p {
    margin: 0;
    color: #A16207;
    font-size: 0.9rem;
    line-height: 1.5;
}

@media (max-width: 992px) {
    .form-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .admin-header-content {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = field.nextElementSibling;
    const icon = toggle.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fa fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fa fa-eye';
    }
}

// Валидация формы
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.admin-form');
    const groupNameInput = document.getElementById('group_name');
    const passwordInput = document.getElementById('password');
    
    // Валидация названия группы
    groupNameInput.addEventListener('input', function() {
        const value = this.value.trim();
        const pattern = /^[А-Яа-яA-Za-z0-9-]+$/;
        
        if (value && !pattern.test(value)) {
            this.setCustomValidity('Используйте только буквы, цифры и дефис');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Валидация пароля
    passwordInput.addEventListener('input', function() {
        const value = this.value.trim();
        
        if (value && value.length < 3) {
            this.setCustomValidity('Пароль должен содержать минимум 3 символа');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Обработка отправки формы
    form.addEventListener('submit', function(e) {
        const groupName = groupNameInput.value.trim();
        const password = passwordInput.value.trim();
        
        if (!groupName) {
            e.preventDefault();
            showNotification('error', 'Название группы обязательно для заполнения');
            return;
        }
        
        if (password && password.length < 3) {
            e.preventDefault();
            showNotification('error', 'Пароль должен содержать минимум 3 символа');
            return;
        }
    });
});

function showNotification(type, message) {
    const notification = document.createElement('div');
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
        font-weight: 500;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script> 