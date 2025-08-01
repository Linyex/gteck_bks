<?php
$title = $title ?? 'Создание пользователя';
$currentPage = 'users';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Админ панель</title>
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .password-strength {
            margin-top: 5px;
            padding: 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .strength-weak { background: rgba(244, 67, 54, 0.2); border: 1px solid #f44336; }
        .strength-medium { background: rgba(255, 152, 0, 0.2); border: 1px solid #ff9800; }
        .strength-strong { background: rgba(76, 175, 80, 0.2); border: 1px solid #4caf50; }
        .form-help { font-size: 12px; color: #888; margin-top: 5px; }
        .error-message { color: #f44336; font-size: 14px; margin-top: 5px; }
        .success-message { color: #4caf50; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="admin-container">
        
        <div class="admin-content">
            <div class="admin-header">
                <div class="admin-title">
                    <h1><i class="fa fa-user-plus"></i> Создание пользователя</h1>
                    <p>Добавление нового пользователя в систему</p>
                </div>
                <div class="admin-actions">
                    <a href="/admin/users" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Назад к списку
                    </a>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="admin-alert alert-error">
                    <i class="fa fa-exclamation-triangle"></i>
                    <div class="alert-content">
                        <h4>Ошибки валидации:</h4>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="admin-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa fa-user-plus"></i>
                        Информация о пользователе
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" class="admin-form" id="createUserForm">
                        <div class="form-grid">
                            <!-- Основная информация -->
                            <div class="form-group">
                                <label for="login" class="form-label required">
                                    <i class="fa fa-user"></i>
                                    Логин
                                </label>
                                <input type="text" 
                                       id="login" 
                                       name="login" 
                                       class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['login'] ?? ''); ?>"
                                       required 
                                       pattern="[a-zA-Z0-9_]+"
                                       minlength="3"
                                       maxlength="50">
                                <div class="form-help">
                                    Минимум 3 символа, только буквы, цифры и знак подчеркивания
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fio" class="form-label required">
                                    <i class="fa fa-id-card"></i>
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
                                <label for="email" class="form-label required">
                                    <i class="fa fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                                       required>
                                <div class="form-help">
                                    Email обязателен для регистрации
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="access_level" class="form-label required">
                                    <i class="fa fa-shield-alt"></i>
                                    Уровень доступа
                                </label>
                                <select id="access_level" name="access_level" class="form-control" required>
                                    <?php foreach ($accessLevels as $value => $label): ?>
                                        <option value="<?php echo htmlspecialchars($value); ?>" 
                                                <?php echo ($formData['access_level'] ?? 1) == $value ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-help">
                                    Детальный контроль прав доступа
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status" class="form-label required">
                                    <i class="fa fa-toggle-on"></i>
                                    Статус
                                </label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="1" <?php echo ($formData['status'] ?? 1) == 1 ? 'selected' : ''; ?>>Активен</option>
                                    <option value="0" <?php echo ($formData['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Заблокирован</option>
                                </select>
                                <div class="form-help">
                                    Активные пользователи могут входить в систему
                                </div>
                            </div>
                        </div>

                        <!-- Пароль -->
                        <div class="form-group full-width">
                            <label for="password" class="form-label required">
                                <i class="fa fa-lock"></i>
                                Пароль
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control" 
                                   required 
                                   minlength="8">
                            <div class="form-help">
                                Минимум 8 символов, рекомендуется использовать буквы, цифры и специальные символы
                            </div>
                            <div id="password-strength" class="password-strength" style="display: none;"></div>
                        </div>

                        <div class="form-group full-width">
                            <label for="confirm_password" class="form-label required">
                                <i class="fa fa-lock"></i>
                                Подтверждение пароля
                            </label>
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   class="form-control" 
                                   required 
                                   minlength="8">
                            <div class="form-help">
                                Повторите пароль для подтверждения
                            </div>
                            <div id="password-match" class="error-message" style="display: none;">
                                Пароли не совпадают
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i>
                                Создать пользователя
                            </button>
                            <a href="/admin/users" class="btn btn-outline-secondary">
                                <i class="fa fa-times"></i>
                                Отмена
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Рекомендации -->
            <div class="admin-card info-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa fa-info-circle"></i>
                        Рекомендации по безопасности
                    </div>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon">🔐</div>
                            <div class="info-content">
                                <h4>Пароли</h4>
                                <p>Используйте сложные пароли с буквами, цифрами и специальными символами. Минимум 8 символов.</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">👤</div>
                            <div class="info-content">
                                <h4>Логины</h4>
                                <p>Логин должен быть уникальным и содержать только буквы, цифры и знак подчеркивания.</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">📧</div>
                            <div class="info-content">
                                <h4>Email</h4>
                                <p>Указание email поможет пользователю восстановить пароль в случае необходимости.</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">🛡️</div>
                            <div class="info-content">
                                <h4>Права доступа</h4>
                                <p>Внимательно выбирайте уровень доступа. Администраторы имеют полный доступ к системе.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordStrength = document.getElementById('password-strength');
            const passwordMatch = document.getElementById('password-match');
            const form = document.getElementById('createUserForm');

            // Проверка силы пароля
            function checkPasswordStrength(password) {
                let strength = 0;
                let feedback = [];

                if (password.length >= 8) strength++;
                else feedback.push('Минимум 8 символов');

                if (/[a-z]/.test(password)) strength++;
                else feedback.push('Добавьте строчные буквы');

                if (/[A-Z]/.test(password)) strength++;
                else feedback.push('Добавьте заглавные буквы');

                if (/[0-9]/.test(password)) strength++;
                else feedback.push('Добавьте цифры');

                if (/[^A-Za-z0-9]/.test(password)) strength++;
                else feedback.push('Добавьте специальные символы');

                if (password.length >= 12) strength++;
                else feedback.push('Для большей безопасности используйте 12+ символов');

                let strengthClass = 'strength-weak';
                let strengthText = 'Слабый пароль';

                if (strength >= 4) {
                    strengthClass = 'strength-medium';
                    strengthText = 'Средний пароль';
                }
                if (strength >= 5) {
                    strengthClass = 'strength-strong';
                    strengthText = 'Сильный пароль';
                }

                return {
                    strength: strength,
                    class: strengthClass,
                    text: strengthText,
                    feedback: feedback
                };
            }

            // Обновление индикатора силы пароля
            function updatePasswordStrength() {
                const password = passwordInput.value;
                if (password.length > 0) {
                    const result = checkPasswordStrength(password);
                    passwordStrength.className = 'password-strength ' + result.class;
                    passwordStrength.innerHTML = `
                        <strong>${result.text}</strong><br>
                        ${result.feedback.length > 0 ? 'Рекомендации: ' + result.feedback.join(', ') : 'Пароль соответствует требованиям'}
                    `;
                    passwordStrength.style.display = 'block';
                } else {
                    passwordStrength.style.display = 'none';
                }
            }

            // Проверка совпадения паролей
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (confirmPassword.length > 0 && password !== confirmPassword) {
                    passwordMatch.style.display = 'block';
                    return false;
                } else {
                    passwordMatch.style.display = 'none';
                    return true;
                }
            }

            // Обработчики событий
            passwordInput.addEventListener('input', updatePasswordStrength);
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);

            // Валидация формы
            form.addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    passwordMatch.style.display = 'block';
                    confirmPasswordInput.focus();
                    return false;
                }

                const strength = checkPasswordStrength(password);
                if (strength.strength < 3) {
                    if (!confirm('Пароль слабый. Рекомендуется использовать более сложный пароль. Продолжить?')) {
                        e.preventDefault();
                        passwordInput.focus();
                        return false;
                    }
                }
            });

            // Автогенерация логина из ФИО
            const fioInput = document.getElementById('fio');
            const loginInput = document.getElementById('login');

            fioInput.addEventListener('blur', function() {
                if (loginInput.value === '') {
                    const fio = fioInput.value.trim();
                    if (fio) {
                        // Создаем логин из ФИО
                        let login = fio.toLowerCase()
                            .replace(/[^а-яa-z0-9\s]/g, '')
                            .replace(/\s+/g, '_')
                            .substring(0, 20);
                        
                        // Убираем лишние символы
                        login = login.replace(/[^a-z0-9_]/g, '');
                        
                        if (login) {
                            loginInput.value = login;
                        }
                    }
                }
            });
        });
    </script>
</body>
</html> 