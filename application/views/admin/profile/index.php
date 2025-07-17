<div class="admin-main">
    <div class="main-header">
        <h1>Профиль администратора</h1>
        <p>Управление личными данными и настройками аккаунта</p>
    </div>

    <div class="profile-container">
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <strong>Ошибка:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <strong>Успешно!</strong> Профиль обновлен
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <div class="card-header">
                <h2>Личные данные</h2>
            </div>
            
            <form method="POST" action="/admin/profile/update" class="profile-form">
                <div class="form-group">
                    <label for="fio" class="form-label">ФИО</label>
                    <input type="text" id="fio" name="fio" class="form-input" 
                           value="<?= htmlspecialchars($user['user_fio'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="<?= htmlspecialchars($user['user_email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">Логин</label>
                    <input type="text" id="username" class="form-input" 
                           value="<?= htmlspecialchars($user['user_login'] ?? '') ?>" readonly>
                    <small>Логин нельзя изменить</small>
                </div>

                <div class="form-group">
                    <label for="access_level" class="form-label">Уровень доступа</label>
                    <input type="text" id="access_level" class="form-input" 
                           value="<?= $this->getAccessLevelName($user['user_access_level'] ?? 1) ?>" readonly>
                </div>

                <hr class="form-divider">

                <div class="form-group">
                    <label for="current_password" class="form-label">Текущий пароль</label>
                    <input type="password" id="current_password" name="current_password" class="form-input">
                    <small>Введите только если хотите изменить пароль</small>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">Новый пароль</label>
                    <input type="password" id="new_password" name="new_password" class="form-input">
                    <small>Минимум 6 символов</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Подтвердите новый пароль</label>
                    <input type="password" id="confirm_password" class="form-input">
                    <small>Повторите новый пароль</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-blue">Сохранить изменения</button>
                    <a href="/admin/dashboard" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>

        <div class="profile-info">
            <div class="info-card">
                <h3>Информация о сессии</h3>
                <p><strong>Последний вход:</strong> <?= date('d.m.Y H:i:s') ?></p>
                <p><strong>IP адрес:</strong> <?= $_SERVER['REMOTE_ADDR'] ?? 'Неизвестно' ?></p>
                <p><strong>Браузер:</strong> <?= $_SERVER['HTTP_USER_AGENT'] ?? 'Неизвестно' ?></p>
            </div>
        </div>
    </div>
</div>

<style>
.profile-container {
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
}

.profile-card {
    background: linear-gradient(135deg, var(--medium-gray) 0%, var(--light-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: var(--glow-blue);
    position: relative;
    overflow: hidden;
}

.profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary-yellow), var(--primary-blue), var(--primary-yellow));
    animation: scanline 3s linear infinite;
}

.card-header h2 {
    color: var(--text-yellow);
    font-size: 24px;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.profile-form {
    display: grid;
    gap: 20px;
}

.form-divider {
    border: none;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--primary-blue), transparent);
    margin: 30px 0;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    flex-wrap: wrap;
}

.btn-secondary {
    background: linear-gradient(135deg, var(--medium-gray), var(--light-gray));
    border: 1px solid var(--primary-blue);
    color: var(--text-white);
}

.btn-secondary:hover {
    background: linear-gradient(135deg, var(--light-gray), var(--medium-gray));
    box-shadow: var(--glow-blue);
}

.profile-info {
    display: grid;
    gap: 20px;
}

.info-card {
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-yellow);
    border-radius: 8px;
    padding: 20px;
    box-shadow: var(--glow-yellow);
}

.info-card h3 {
    color: var(--text-yellow);
    margin-bottom: 15px;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-card p {
    margin: 8px 0;
    color: var(--text-white);
}

.info-card strong {
    color: var(--text-blue);
}

small {
    color: var(--text-blue);
    font-size: 12px;
    opacity: 0.8;
}

@media (max-width: 768px) {
    .profile-container {
        padding: 10px;
    }
    
    .profile-card {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function validatePasswords() {
        if (newPassword.value && confirmPassword.value) {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Пароли не совпадают');
            } else if (newPassword.value.length < 6) {
                newPassword.setCustomValidity('Пароль должен содержать минимум 6 символов');
            } else {
                newPassword.setCustomValidity('');
                confirmPassword.setCustomValidity('');
            }
        }
    }
    
    newPassword.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
});
</script> 