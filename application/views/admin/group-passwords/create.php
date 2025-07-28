<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">➕ Добавить пароль группы</h1>
            <p class="admin-subtitle">Создание нового пароля для доступа к материалам группы</p>
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
                    <i class="fa fa-key"></i>
                    Параметры пароля группы
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
                                   placeholder="Например: T111, Э201, Б301"
                                   pattern="[А-Яа-яA-Za-z0-9-]+"
                                   title="Только буквы, цифры и дефис"
                                   required>
                            <div class="form-help">
                                Введите код группы (например: T111, Э201, Б301)
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label required">
                                <i class="fa fa-lock"></i>
                                Пароль
                            </label>
                            <div class="password-field">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control" 
                                       placeholder="Введите пароль группы"
                                       minlength="3"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-help">
                                Пароль должен содержать минимум 3 символа
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
                                      placeholder="Краткое описание группы (специальность, курс)"></textarea>
                            <div class="form-help">
                                Опциональное описание для удобства администрирования
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>
                            Сохранить пароль
                        </button>
                        <a href="/admin/group-passwords" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i>
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="admin-card info-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa fa-lightbulb-o"></i>
                    Рекомендации
                </div>
            </div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon">🔐</div>
                        <div class="info-content">
                            <h4>Безопасность паролей</h4>
                            <p>Используйте уникальные пароли для каждой группы. Избегайте простых комбинаций.</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">👥</div>
                        <div class="info-content">
                            <h4>Названия групп</h4>
                            <p>Используйте стандартные коды групп: T111, Э201, Б301 и т.д.</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📝</div>
                        <div class="info-content">
                            <h4>Описания</h4>
                            <p>Добавляйте описания для упрощения администрирования и идентификации групп.</p>
                        </div>
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

.form-label.required::after {
    content: ' *';
    color: #EF4444;
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
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-item {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.info-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
    width: 40px;
    text-align: center;
}

.info-content h4 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
}

.info-content p {
    margin: 0;
    color: #6B7280;
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
    
    // Проверка уникальности названия группы
    groupNameInput.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value) {
            // Здесь можно добавить AJAX проверку уникальности
        }
    });
    
    // Валидация пароля
    passwordInput.addEventListener('input', function() {
        const value = this.value.trim();
        
        if (value.length < 3) {
            this.setCustomValidity('Пароль должен содержать минимум 3 символа');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Обработка отправки формы
    form.addEventListener('submit', function(e) {
        const groupName = groupNameInput.value.trim();
        const password = passwordInput.value.trim();
        
        if (!groupName || !password) {
            e.preventDefault();
            showNotification('error', 'Заполните все обязательные поля');
            return;
        }
        
        if (password.length < 3) {
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