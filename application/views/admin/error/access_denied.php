<?php
if (!isset($title)) {
    $title = 'Доступ запрещен';
}
if (!isset($message)) {
    $message = 'У вас нет прав для доступа к этой странице';
}
?>

<div class="error-container">
    <div class="error-content">
        <div class="error-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h1><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($message) ?></p>
        <div class="error-actions">
            <a href="/admin" class="btn btn-blue">
                <i class="fas fa-home"></i> На главную
            </a>
            <a href="/admin/auth/login" class="btn btn-secondary">
                <i class="fas fa-sign-in-alt"></i> Войти
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
    color: #ffc107;
    margin-bottom: 20px;
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