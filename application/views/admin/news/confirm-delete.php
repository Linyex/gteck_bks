<?php
$header = $header ?? '';
$footer = $footer ?? '';
?>

<div class="delete-confirmation-container">
    <!-- Заголовок -->
    <div class="confirmation-header">
        <div class="confirmation-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h1 class="confirmation-title">Подтверждение удаления</h1>
        <p class="confirmation-subtitle">Вы собираетесь удалить новость. Это действие нельзя отменить.</p>
    </div>

    <!-- Информация о новости -->
    <div class="news-preview">
        <div class="news-preview-header">
            <h2 class="news-title"><?= htmlspecialchars($news['news_title'] ?? 'Без названия') ?></h2>
            <div class="news-meta">
                <span class="news-date">
                    <i class="fas fa-calendar"></i>
                    <?= date('d.m.Y H:i', strtotime($news['news_date_add'] ?? 'now')) ?>
                </span>
                <span class="news-category">
                    <i class="fas fa-tag"></i>
                    <?php
                    $categoryId = $news['category_id'] ?? 1;
                    $categories = [
                        1 => 'Общие новости',
                        2 => 'Академические',
                        3 => 'События'
                    ];
                    echo $categories[$categoryId] ?? 'Общие новости';
                    ?>
                </span>
            </div>
        </div>
        
        <div class="news-preview-content">
            <div class="news-excerpt">
                <?php 
                $text = $news['news_text'] ?? '';
                $excerpt = strlen($text) > 300 ? substr($text, 0, 300) . '...' : $text;
                echo nl2br(htmlspecialchars($excerpt));
                ?>
            </div>
            
            <?php if (!empty($news['news_image'])): ?>
                <div class="news-image-preview">
                    <img src="/<?= htmlspecialchars($news['news_image']) ?>" alt="Изображение новости" class="preview-image">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Кнопки действий -->
    <div class="confirmation-actions">
        <a href="/admin/news" class="btn btn-secondary btn-cancel">
            <i class="fas fa-arrow-left"></i>
            Отмена
        </a>
        
        <form method="POST" action="/admin/news/delete/<?= $news['news_id'] ?>" style="display: inline;">
            <button type="submit" class="btn btn-danger btn-confirm" onclick="return confirm('Вы уверены, что хотите удалить эту новость?')">
                <i class="fas fa-trash"></i>
                Удалить новость
            </button>
        </form>
    </div>

    <!-- Дополнительная информация -->
    <div class="confirmation-info">
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="info-content">
                <h3>Что произойдет при удалении:</h3>
                <ul>
                    <li>Новость будет полностью удалена из базы данных</li>
                    <li>Изображение новости (если есть) будет удалено с сервера</li>
                    <li>Новость исчезнет со всех страниц сайта</li>
                    <li>Это действие нельзя отменить</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.delete-confirmation-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.confirmation-header {
    text-align: center;
    margin-bottom: 40px;
}

.confirmation-icon {
    font-size: 48px;
    color: #f59e0b;
    margin-bottom: 20px;
}

.confirmation-title {
    color: #ef4444;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 10px;
}

.confirmation-subtitle {
    color: #9ca3af;
    font-size: 16px;
}

.news-preview {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    border: 1px solid rgba(139, 92, 246, 0.1);
}

.news-preview-header {
    margin-bottom: 20px;
}

.news-title {
    color: #ffffff;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 15px;
}

.news-meta {
    display: flex;
    gap: 20px;
    color: #9ca3af;
    font-size: 14px;
}

.news-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.news-preview-content {
    color: #d1d5db;
    line-height: 1.6;
}

.news-image-preview {
    margin-top: 20px;
}

.preview-image {
    max-width: 100%;
    max-height: 200px;
    border-radius: 8px;
    border: 1px solid rgba(139, 92, 246, 0.2);
}

.confirmation-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 30px;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.btn-secondary {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.confirmation-info {
    background: rgba(59, 130, 246, 0.1);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.info-card {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.info-icon {
    font-size: 24px;
    color: #3b82f6;
    margin-top: 2px;
}

.info-content h3 {
    color: #ffffff;
    font-size: 18px;
    margin-bottom: 10px;
}

.info-content ul {
    color: #d1d5db;
    line-height: 1.6;
    margin: 0;
    padding-left: 20px;
}

.info-content li {
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .delete-confirmation-container {
        padding: 20px;
        margin: 10px;
    }
    
    .confirmation-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style> 