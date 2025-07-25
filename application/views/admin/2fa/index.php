<div class="2fa-container">
    <div class="cyber-card">
        <h2 class="cyber-title" data-text="ДВУХФАКТОРНАЯ АУТЕНТИФИКАЦИЯ">
            <i class="fas fa-shield-alt"></i> ДВУХФАКТОРНАЯ АУТЕНТИФИКАЦИЯ
        </h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <div class="2fa-status">
            <div class="status-indicator <?= $isEnabled ? 'enabled' : 'disabled' ?>">
                <i class="fas fa-<?= $isEnabled ? 'check-circle' : 'times-circle' ?>"></i>
                <span>2FA <?= $isEnabled ? 'АКТИВНА' : 'НЕ АКТИВНА' ?></span>
            </div>
        </div>
        
        <?php if (!$isEnabled): ?>
            <!-- Настройка 2FA -->
            <div class="setup-section">
                <h3 class="section-title">
                    <i class="fas fa-cog"></i> НАСТРОЙКА 2FA
                </h3>
                
                <div class="qr-section">
                    <div class="qr-container">
                        <img src="<?= $qrCode ?>" alt="QR Code" class="qr-code">
                        <div class="qr-overlay">
                            <i class="fas fa-qrcode"></i>
                        </div>
                    </div>
                    
                    <div class="qr-info">
                        <h4>Как настроить:</h4>
                        <ol>
                            <li>Установите Google Authenticator на ваш телефон</li>
                            <li>Отсканируйте QR код или введите секретный ключ</li>
                            <li>Введите код из приложения для активации</li>
                        </ol>
                        
                        <div class="secret-key">
                            <label>Секретный ключ:</label>
                            <div class="secret-display">
                                <input type="text" value="<?= $secret ?>" readonly id="secretKey">
                                <button type="button" onclick="copySecret()" class="copy-btn">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form method="POST" action="/admin/2fa/enable" class="2fa-form">
                    <div class="form-group">
                        <label for="code">
                            <i class="fas fa-key"></i> КОД ПОДТВЕРЖДЕНИЯ
                        </label>
                        <input type="text" id="code" name="code" maxlength="6" 
                               pattern="[0-9]{6}" required 
                               placeholder="000000"
                               class="cyber-input">
                        <small>Введите 6-значный код из Google Authenticator</small>
                    </div>
                    
                    <button type="submit" class="cyber-btn enable-btn">
                        <i class="fas fa-shield-alt"></i> АКТИВИРОВАТЬ 2FA
                    </button>
                </form>
            </div>
        <?php else: ?>
            <!-- Управление 2FA -->
            <div class="management-section">
                <h3 class="section-title">
                    <i class="fas fa-cogs"></i> УПРАВЛЕНИЕ 2FA
                </h3>
                
                <div class="management-actions">
                    <form method="POST" action="/admin/2fa/disable" class="disable-form">
                        <div class="form-group">
                            <label for="disableCode">
                                <i class="fas fa-key"></i> КОД ДЛЯ ОТКЛЮЧЕНИЯ
                            </label>
                            <input type="text" id="disableCode" name="code" maxlength="6" 
                                   pattern="[0-9]{6}" required 
                                   placeholder="000000"
                                   class="cyber-input">
                        </div>
                        
                        <button type="submit" class="cyber-btn danger-btn">
                            <i class="fas fa-times"></i> ОТКЛЮЧИТЬ 2FA
                        </button>
                    </form>
                    
                    <form method="POST" action="/admin/2fa/regenerate-backup" class="regenerate-form">
                        <button type="submit" class="cyber-btn warning-btn">
                            <i class="fas fa-refresh"></i> НОВЫЕ РЕЗЕРВНЫЕ КОДЫ
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Резервные коды -->
        <div class="backup-codes-section">
            <h3 class="section-title">
                <i class="fas fa-key"></i> РЕЗЕРВНЫЕ КОДЫ
            </h3>
            
            <div class="backup-info">
                <p>
                    <i class="fas fa-info-circle"></i>
                    Сохраните эти коды в безопасном месте. Они понадобятся, если вы потеряете доступ к Google Authenticator.
                </p>
            </div>
            
            <div class="backup-codes">
                <?php foreach ($backupCodes as $index => $code): ?>
                    <div class="backup-code" data-code="<?= $code ?>">
                        <span class="code-number"><?= $index + 1 ?></span>
                        <span class="code-value"><?= $code ?></span>
                        <button type="button" onclick="copyBackupCode('<?= $code ?>')" class="copy-btn">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="backup-actions">
                <button type="button" onclick="printBackupCodes()" class="cyber-btn">
                    <i class="fas fa-print"></i> ПЕЧАТАТЬ
                </button>
                <button type="button" onclick="downloadBackupCodes()" class="cyber-btn">
                    <i class="fas fa-download"></i> СКАЧАТЬ
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.2fa-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.2fa-status {
    margin-bottom: 30px;
    text-align: center;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 15px 25px;
    border-radius: 10px;
    font-weight: bold;
    font-size: 1.1em;
}

.status-indicator.enabled {
    background: rgba(0, 255, 0, 0.1);
    color: #00ff00;
    border: 1px solid #00ff00;
}

.status-indicator.disabled {
    background: rgba(255, 0, 0, 0.1);
    color: #ff0000;
    border: 1px solid #ff0000;
}

.setup-section, .management-section {
    margin-bottom: 30px;
}

.section-title {
    color: #00ffff;
    margin-bottom: 20px;
    font-size: 1.3em;
    display: flex;
    align-items: center;
    gap: 10px;
}

.qr-section {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 30px;
    margin-bottom: 30px;
}

.qr-container {
    position: relative;
    text-align: center;
}

.qr-code {
    max-width: 200px;
    border: 2px solid #00ffff;
    border-radius: 10px;
    background: white;
}

.qr-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 3em;
    color: rgba(0, 255, 255, 0.3);
    pointer-events: none;
}

.qr-info h4 {
    color: #00ffff;
    margin-bottom: 15px;
}

.qr-info ol {
    color: #ffffff;
    margin-bottom: 20px;
    padding-left: 20px;
}

.qr-info li {
    margin-bottom: 8px;
}

.secret-key {
    margin-top: 20px;
}

.secret-key label {
    display: block;
    color: #00ffff;
    margin-bottom: 10px;
    font-weight: bold;
}

.secret-display {
    display: flex;
    gap: 10px;
}

.secret-display input {
    flex: 1;
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid #00ffff;
    color: #ffffff;
    padding: 10px;
    border-radius: 5px;
    font-family: 'Courier New', monospace;
    font-size: 1.1em;
}

.copy-btn {
    background: rgba(0, 255, 255, 0.1);
    border: 1px solid #00ffff;
    color: #00ffff;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.copy-btn:hover {
    background: rgba(0, 255, 255, 0.2);
    transform: scale(1.05);
}

.2fa-form {
    margin-top: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    color: #00ffff;
    margin-bottom: 8px;
    font-weight: bold;
}

.cyber-input {
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

.cyber-input:focus {
    outline: none;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
}

.form-group small {
    color: #cccccc;
    font-size: 0.9em;
    margin-top: 5px;
    display: block;
}

.enable-btn {
    background: linear-gradient(135deg, rgba(0, 255, 0, 0.1), rgba(0, 255, 0, 0.2));
    border: 1px solid #00ff00;
    color: #00ff00;
}

.management-actions {
    display: grid;
    gap: 20px;
}

.danger-btn {
    background: linear-gradient(135deg, rgba(255, 0, 0, 0.1), rgba(255, 0, 0, 0.2));
    border: 1px solid #ff0000;
    color: #ff0000;
}

.warning-btn {
    background: linear-gradient(135deg, rgba(255, 165, 0, 0.1), rgba(255, 165, 0, 0.2));
    border: 1px solid #ffa500;
    color: #ffa500;
}

.backup-codes-section {
    margin-top: 40px;
}

.backup-info {
    background: rgba(0, 255, 255, 0.05);
    border: 1px solid rgba(0, 255, 255, 0.2);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
}

.backup-info p {
    color: #ffffff;
    margin: 0;
}

.backup-codes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.backup-code {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: rgba(0, 255, 255, 0.05);
    border: 1px solid rgba(0, 255, 255, 0.2);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.backup-code:hover {
    background: rgba(0, 255, 255, 0.1);
    transform: translateY(-2px);
}

.code-number {
    background: #00ffff;
    color: #000000;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9em;
}

.code-value {
    flex: 1;
    font-family: 'Courier New', monospace;
    font-size: 1.1em;
    color: #00ffff;
    font-weight: bold;
}

.backup-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
}

@media (max-width: 768px) {
    .qr-section {
        grid-template-columns: 1fr;
    }
    
    .backup-codes {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }
    
    .backup-actions {
        flex-direction: column;
    }
}
</style>

<script>
function copySecret() {
    const secretInput = document.getElementById('secretKey');
    secretInput.select();
    document.execCommand('copy');
    
    // Показываем уведомление
    showNotification('Секретный ключ скопирован!', 'success');
}

function copyBackupCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        showNotification('Резервный код скопирован!', 'success');
    }).catch(() => {
        // Fallback для старых браузеров
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Резервный код скопирован!', 'success');
    });
}

function printBackupCodes() {
    const codes = Array.from(document.querySelectorAll('.backup-code')).map(code => {
        const number = code.querySelector('.code-number').textContent;
        const value = code.querySelector('.code-value').textContent;
        return `${number}. ${value}`;
    }).join('\n');
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Резервные коды 2FA</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .code { margin: 10px 0; font-family: monospace; }
                    .warning { color: red; font-weight: bold; }
                </style>
            </head>
            <body>
                <h1>Резервные коды двухфакторной аутентификации</h1>
                <p class="warning">ВАЖНО: Сохраните эти коды в безопасном месте!</p>
                <div class="codes">
                    ${codes.split('\n').map(code => `<div class="code">${code}</div>`).join('')}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function downloadBackupCodes() {
    const codes = Array.from(document.querySelectorAll('.backup-code')).map(code => {
        const number = code.querySelector('.code-number').textContent;
        const value = code.querySelector('.code-value').textContent;
        return `${number}. ${value}`;
    }).join('\n');
    
    const content = `Резервные коды двухфакторной аутентификации\n\nВАЖНО: Сохраните эти коды в безопасном месте!\n\n${codes}`;
    
    const blob = new Blob([content], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'backup_codes_2fa.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showNotification('Резервные коды скачаны!', 'success');
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
        ${message}
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'rgba(0, 255, 0, 0.9)' : 'rgba(255, 0, 0, 0.9)'};
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Автофокус на поле ввода кода
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code') || document.getElementById('disableCode');
    if (codeInput) {
        codeInput.focus();
    }
});

// Автоматический переход к следующему полю при вводе
document.querySelectorAll('input[pattern]').forEach(input => {
    input.addEventListener('input', function() {
        if (this.value.length === this.maxLength) {
            this.nextElementSibling?.focus();
        }
    });
});
</script> 