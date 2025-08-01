<?php
if (!isset($title)) {
    $title = 'Ошибка 500';
}
if (!isset($message)) {
    $message = 'Внутренняя ошибка сервера';
}
?>

<div class="error-container">
    <div class="error-content">
        <div class="error-icon">
            <i class="fas fa-bug"></i>
        </div>
        <h1><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($message) ?></p>
        <div class="error-actions">
            <a href="/admin" class="btn btn-blue">
                <i class="fas fa-home"></i> На главную
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Назад
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
    background: rgba(255, 255, 255, 0.05);4

.error-icon {
    font-size: 4rem;
    color: #dc3545;
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