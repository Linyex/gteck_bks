<?php
$title = $title ?? 'Создание пользователя';
$currentPage = 'users';
?>

<!-- Users Create Content -->
<div class="users-create-dashboard">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-user-plus"></i>
            <h1>Создание пользователя</h1>
        </div>
        <div class="page-subtitle">
            Добавление нового пользователя в систему с настройкой прав доступа
        </div>
        <div class="page-actions">
            <a href="/admin/users" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Назад к списку
            </a>
        </div>
    </div>

    <!-- Уведомления об ошибках -->
    <?php if (!empty($errors)): ?>
        <div class="admin-card error-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Ошибки валидации
                </div>
            </div>
            <div class="card-body">
                <div class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <div class="error-item">
                            <i class="fas fa-times-circle"></i>
                            <span><?php echo htmlspecialchars($error); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Основная форма -->
    <div class="admin-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-user-plus"></i>
                Информация о пользователе
            </div>
        </div>
        <div class="card-body">
            <form method="POST" class="admin-form" id="createUserForm">
                <div class="form-grid">
                    <!-- Основная информация -->
                    <div class="form-group">
                        <label for="login" class="form-label required">
                            <i class="fas fa-user"></i>
                            Логин
                        </label>
                        <input type="text" 
                               id="login" 
                               name="login" 
                               class="form-control" 
                               value="<?php echo htmlspecialchars($formData['login'] ?? ''); ?>"
                               required 
                               pattern="[a-zA-Z0-9_-]+"
                               minlength="3"
                               maxlength="50">
                        <div class="form-help">
                            Минимум 3 символа, только буквы, цифры, дефис и знак подчеркивания
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fio" class="form-label required">
                            <i class="fas fa-id-card"></i>
                            ФИО
                        </label>
                        <input type="text" 
                               id="fio" 
                               name="fio" 
                               class="form-control" 
                               value="<?php echo htmlspecialchars($formData['fio'] ?? ''); ?>"
                               required>
                        <div class="form-help">
                            Полное имя пользователя
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                        <div class="form-help">
                            Необязательно, для восстановления пароля
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="access_level" class="form-label required">
                            <i class="fas fa-shield-alt"></i>
                            Уровень доступа
                        </label>
                        <select id="access_level" name="access_level" class="form-control" required>
                            <option value="">Выберите уровень доступа</option>
                            <option value="1" <?php echo ($formData['access_level'] ?? '') == '1' ? 'selected' : ''; ?>>Преподаватель (1)</option>
                            <option value="2" <?php echo ($formData['access_level'] ?? '') == '2' ? 'selected' : ''; ?>>Методист (2)</option>
                            <option value="3" <?php echo ($formData['access_level'] ?? '') == '3' ? 'selected' : ''; ?>>Зав. отделением (3)</option>
                            <option value="4" <?php echo ($formData['access_level'] ?? '') == '4' ? 'selected' : ''; ?>>Зам. директора по воспитательной работе (4)</option>
                            <option value="5" <?php echo ($formData['access_level'] ?? '') == '5' ? 'selected' : ''; ?>>Зам. директора по учебной работе (5)</option>
                            <option value="6" <?php echo ($formData['access_level'] ?? '') == '6' ? 'selected' : ''; ?>>Директор (6)</option>
                            <option value="7" <?php echo ($formData['access_level'] ?? '') == '7' ? 'selected' : ''; ?>>Социальный педагог (7)</option>
                            <option value="8" <?php echo ($formData['access_level'] ?? '') == '8' ? 'selected' : ''; ?>>Психолог (8)</option>
                            <option value="10" <?php echo ($formData['access_level'] ?? '') == '10' ? 'selected' : ''; ?>>Администратор (10)</option>
                        </select>
                        <div class="form-help">
                            Определяет права доступа пользователя в системе
                        </div>
                    </div>

                    <!-- Пароль -->
                    <div class="form-group full-width">
                        <label for="password" class="form-label required">
                            <i class="fas fa-lock"></i>
                            Пароль
                        </label>
                        <div class="password-input-group">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control" 
                                   required 
                                   minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Введите пароль</div>
                        </div>
                        <div class="form-help">
                            Минимум 8 символов, включая буквы, цифры и специальные символы
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm" class="form-label required">
                            <i class="fas fa-lock"></i>
                            Подтверждение пароля
                        </label>
                        <div class="password-input-group">
                            <input type="password" 
                                   id="password_confirm" 
                                   name="password_confirm" 
                                   class="form-control" 
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-help">
                            Повторите пароль для подтверждения
                        </div>
                    </div>
                </div>

                <!-- Дополнительные настройки -->
                <div class="form-section">
                    <h3><i class="fas fa-cog"></i> Дополнительные настройки</h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i>
                                Статус аккаунта
                            </label>
                            <div class="checkbox-group">
                                <label class="checkbox-item">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1" 
                                           <?php echo ($formData['is_active'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                    Активный аккаунт
                                </label>
                            </div>
                            <div class="form-help">
                                Неактивные пользователи не могут войти в систему
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bell"></i>
                                Уведомления
                            </label>
                            <div class="checkbox-group">
                                <label class="checkbox-item">
                                    <input type="checkbox" 
                                           name="email_notifications" 
                                           value="1" 
                                           <?php echo ($formData['email_notifications'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                    Email уведомления
                                </label>
                            </div>
                            <div class="form-help">
                                Отправлять уведомления на email пользователя
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Создать пользователя
                    </button>
                    <a href="/admin/users" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Функция переключения видимости пароля
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const toggle = input.nextElementSibling;
    const icon = toggle.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Проверка силы пароля
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    let strength = 0;
    let text = '';
    let color = '';
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    switch(strength) {
        case 0:
        case 1:
            text = 'Очень слабый';
            color = '#ef4444';
            break;
        case 2:
            text = 'Слабый';
            color = '#f97316';
            break;
        case 3:
            text = 'Средний';
            color = '#eab308';
            break;
        case 4:
            text = 'Хороший';
            color = '#22c55e';
            break;
        case 5:
            text = 'Отличный';
            color = '#10b981';
            break;
    }
    
    strengthFill.style.width = (strength * 20) + '%';
    strengthFill.style.backgroundColor = color;
    strengthText.textContent = text;
    strengthText.style.color = color;
});

// Проверка совпадения паролей
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    
    if (confirm && password !== confirm) {
        this.setCustomValidity('Пароли не совпадают');
    } else {
        this.setCustomValidity('');
    }
});
</script> 