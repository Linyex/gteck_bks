<?php
if (!isset($title)) {
    $title = 'Техническое обслуживание';
}
if (!isset($message)) {
    $message = 'Система находится на техническом обслуживании. Пожалуйста, попробуйте позже.';
}
?>

<div class="error-container">
    <div class="error-content">
        <div class="error-icon">
            <i class="fas fa-tools"></i>
        </div>
        <h1><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($message) ?></p>
        <div class="error-actions">
            <a href="javascript:location.reload()" class="btn btn-blue">
                <i class="fas fa-sync-alt"></i> Обновить
            </a>
            <a href="/admin" class="btn btn-secondary">
                <i class="fas fa-home"></i> На главную
            </a>
        </div>
    </div>
</div>

<style>
.error-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 20px;
}

.error-content {
    text-align: center;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 40px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    max-width: 500px;
}

.error-icon {
    font-size: 4rem;
    color: #17a2b8;
    margin-bottom: 20px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.error-content h1 {
    font-size: 2rem;
    color: var(--text-primary);
    margin-bottom: 15px;
}

.error-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.error-actions .btn {
    padding: 12px 20px;
    font-size: 0.9rem;
}
</style> 