<div class="2fa-verify-container">
    <div class="cyber-card verify-card">
        <div class="verify-header">
            <div class="verify-icon">
                <i class="fas fa-shield-alt pulse"></i>
            </div>
            <h2 class="cyber-title" data-text="ПОДТВЕРЖДЕНИЕ 2FA">
                ПОДТВЕРЖДЕНИЕ 2FA
            </h2>
            <p class="verify-subtitle">
                Введите код из Google Authenticator для завершения входа
            </p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger shake">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/admin/2fa/verify" class="verify-form">
            <input type="hidden" name="user_id" value="<?= $userId ?>">
            
            <div class="code-input-container">
                <label for="code" class="code-label">
                    <i class="fas fa-key"></i> КОД ПОДТВЕРЖДЕНИЯ
                </label>
                
                <div class="code-input-group">
                    <input type="text" id="code" name="code" maxlength="6" 
                           pattern="[0-9]{6}" required 
                           placeholder="000000"
                           class="cyber-input code-input"
                           autocomplete="off">
                    <div class="code-input-overlay">
                        <div class="code-dots">
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                    </div>
                </div>
                
                <small class="code-hint">
                    Введите 6-значный код из приложения Google Authenticator
                </small>
            </div>
            
            <div class="verify-actions">
                <button type="submit" class="cyber-btn verify-btn">
                    <i class="fas fa-check"></i> ПОДТВЕРДИТЬ
                </button>
                
                <button type="button" onclick="showBackupCodeModal()" class="cyber-btn backup-btn">
                    <i class="fas fa-key"></i> РЕЗЕРВНЫЙ КОД
                </button>
            </div>
        </form>
        
        <div class="verify-help">
            <h4>
                <i class="fas fa-question-circle"></i> НУЖНА ПОМОЩЬ?
            </h4>
            <ul>
                <li>Убедитесь, что время на устройстве синхронизировано</li>
                <li>Проверьте, что код введен правильно</li>
                <li>Используйте резервный код, если потеряли доступ к приложению</li>
                <li>Обратитесь к администратору при проблемах</li>
            </ul>
        </div>
    </div>
</div>

<!-- Модальное окно для резервного кода -->
<div id="backupModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <i class="fas fa-key"></i> РЕЗЕРВНЫЙ КОД
            </h3>
            <button type="button" onclick="closeBackupModal()" class="close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <p>
                Если у вас нет доступа к Google Authenticator, используйте один из резервных кодов.
            </p>
            
            <form method="POST" action="/admin/2fa/verify" class="backup-form">
                <input type="hidden" name="user_id" value="<?= $userId ?>">
                
                <div class="form-group">
                    <label for="backupCode">
                        <i class="fas fa-key"></i> РЕЗЕРВНЫЙ КОД
                    </label>
                    <input type="text" id="backupCode" name="code" maxlength="8" 
                           pattern="[0-9]{8}" required 
                           placeholder="00000000"
                           class="cyber-input">
                    <small>Введите 8-значный резервный код</small>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" class="cyber-btn">
                        <i class="fas fa-check"></i> ПОДТВЕРДИТЬ
                    </button>
                    <button type="button" onclick="closeBackupModal()" class="cyber-btn danger-btn">
                        <i class="fas fa-times"></i> ОТМЕНА
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.2fa-verify-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.7));
}

.verify-card {
    max-width: 500px;
    width: 100%;
    text-align: center;
    animation: fadeInUp 0.5s ease;
}

.verify-header {
    margin-bottom: 30px;
}

.verify-icon {
    font-size: 4em;
    color: #00ffff;
    margin-bottom: 20px;
}

.verify-subtitle {
    color: #cccccc;
    font-size: 1.1em;
    margin-top: 10px;
}

.code-input-container {
    margin-bottom: 30px;
}

.code-label {
    display: block;
    color: #00ffff;
    margin-bottom: 15px;
    font-weight: bold;
    font-size: 1.1em;
}

.code-input-group {
    position: relative;
    margin-bottom: 10px;
}

.code-input {
    width: 100%;
    background: rgba(0, 0, 0, 0.5);
    border: 2px solid #00ffff;
    color: transparent;
    padding: 20px;
    border-radius: 10px;
    font-size: 1.5em;
    text-align: center;
    letter-spacing: 8px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.code-input:focus {
    outline: none;
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
    border-color: #ff00ff;
}

.code-input-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.code-dots {
    display: flex;
    gap: 15px;
}

.dot {
    width: 20px;
    height: 20px;
    border: 2px solid #00ffff;
    border-radius: 50%;
    background: transparent;
    transition: all 0.3s ease;
}

.dot.filled {
    background: #00ffff;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
}

.code-hint {
    color: #cccccc;
    font-size: 0.9em;
}

.verify-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 30px;
}

.verify-btn {
    background: linear-gradient(135deg, rgba(0, 255, 0, 0.1), rgba(0, 255, 0, 0.2));
    border: 1px solid #00ff00;
    color: #00ff00;
    padding: 15px 30px;
    font-size: 1.1em;
}

.backup-btn {
    background: linear-gradient(135deg, rgba(255, 165, 0, 0.1), rgba(255, 165, 0, 0.2));
    border: 1px solid #ffa500;
    color: #ffa500;
    padding: 15px 30px;
    font-size: 1.1em;
}

.verify-help {
    background: rgba(0, 255, 255, 0.05);
    border: 1px solid rgba(0, 255, 255, 0.2);
    border-radius: 10px;
    padding: 20px;
    text-align: left;
}

.verify-help h4 {
    color: #00ffff;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.verify-help ul {
    color: #ffffff;
    padding-left: 20px;
}

.verify-help li {
    margin-bottom: 8px;
}

/* Модальное окно */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    animation: fadeIn 0.3s ease;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.8));
    border: 1px solid #00ffff;
    border-radius: 15px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    animation: slideIn 0.3s ease;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(0, 255, 255, 0.3);
}

.modal-header h3 {
    color: #00ffff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.close-btn {
    background: none;
    border: none;
    color: #ff0000;
    font-size: 1.5em;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.close-btn:hover {
    background: rgba(255, 0, 0, 0.1);
}

.modal-body p {
    color: #ffffff;
    margin-bottom: 20px;
}

.backup-form .form-group {
    margin-bottom: 20px;
}

.backup-form label {
    display: block;
    color: #00ffff;
    margin-bottom: 8px;
    font-weight: bold;
}

.backup-form input {
    width: 100%;
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid #00ffff;
    color: #ffffff;
    padding: 12px;
    border-radius: 5px;
    font-size: 1.1em;
    text-align: center;
    letter-spacing: 2px;
}

.backup-form small {
    color: #cccccc;
    font-size: 0.9em;
    margin-top: 5px;
    display: block;
}

.modal-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 20px;
}

.danger-btn {
    background: linear-gradient(135deg, rgba(255, 0, 0, 0.1), rgba(255, 0, 0, 0.2));
    border: 1px solid #ff0000;
    color: #ff0000;
}

/* Анимации */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translate(-50%, -60%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.pulse { animation: pulse 2s infinite; }
.shake { animation: shake 0.5s ease-in-out; }

/* Адаптивность */
@media (max-width: 768px) {
    .verify-actions {
        flex-direction: column;
    }
    
    .code-input {
        font-size: 1.2em;
        letter-spacing: 4px;
    }
    
    .code-dots {
        gap: 10px;
    }
    
    .dot {
        width: 15px;
        height: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const dots = document.querySelectorAll('.dot');
    
    // Автофокус на поле ввода
    codeInput.focus();
    
    // Обработка ввода кода
    codeInput.addEventListener('input', function() {
        const value = this.value.replace(/\D/g, '').slice(0, 6);
        this.value = value;
        
        // Обновляем точки
        dots.forEach((dot, index) => {
            if (index < value.length) {
                dot.classList.add('filled');
            } else {
                dot.classList.remove('filled');
            }
        });
        
        // Автоматическая отправка при вводе 6 цифр
        if (value.length === 6) {
            setTimeout(() => {
                this.form.submit();
            }, 500);
        }
    });
    
    // Обработка клавиш
    codeInput.addEventListener('keydown', function(e) {
        // Разрешаем только цифры, backspace, delete, tab, escape
        if (!/[0-9]/.test(e.key) && 
            !['Backspace', 'Delete', 'Tab', 'Escape', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            e.preventDefault();
        }
    });
    
    // Обработка вставки
    codeInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        const numbers = pastedText.replace(/\D/g, '').slice(0, 6);
        this.value = numbers;
        
        // Обновляем точки
        dots.forEach((dot, index) => {
            if (index < numbers.length) {
                dot.classList.add('filled');
            } else {
                dot.classList.remove('filled');
            }
        });
    });
});

function showBackupCodeModal() {
    document.getElementById('backupModal').style.display = 'block';
    document.getElementById('backupCode').focus();
}

function closeBackupCodeModal() {
    document.getElementById('backupModal').style.display = 'none';
    document.getElementById('code').focus();
}

// Закрытие модального окна при клике вне его
window.addEventListener('click', function(e) {
    const modal = document.getElementById('backupModal');
    if (e.target === modal) {
        closeBackupCodeModal();
    }
});

// Обработка клавиши Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBackupCodeModal();
    }
});
</script> 