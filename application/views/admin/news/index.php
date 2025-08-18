<?php
$header = $header ?? '';
$footer = $footer ?? '';
$error = $error ?? '';
$success = $success ?? '';
$deleted = $deleted ?? '';
?>

<div class="news-management-container">
    <!-- Заголовок и кнопки действий -->
    <div class="news-header">
        <div class="news-title-section">
            <h1 class="news-title">
                <i class="fas fa-newspaper"></i>
                Управление новостями
            </h1>
            <div class="news-stats">
                <span class="stat-item">
                    <i class="fas fa-list"></i>
                    Всего новостей: <strong><?= $total ?? 0 ?></strong>
                </span>
            </div>
        </div>
        
        <div class="news-actions">
            <a href="/admin/news/create" class="btn btn-primary btn-create">
                <i class="fas fa-plus"></i>
                Создать новость
            </a>
            <button class="btn btn-secondary btn-refresh" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i>
                Обновить
            </button>
        </div>
    </div>

    <!-- Уведомления -->
    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Новость успешно сохранена!
        </div>
    <?php endif; ?>
    
    <?php if ($deleted): ?>
        <div class="alert alert-info">
            <i class="fas fa-trash"></i>
            Новость успешно удалена!
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Фильтры и поиск -->
    <div class="news-filters">
        <div class="search-box">
            <input type="text" id="newsSearch" placeholder="Поиск по новостям..." class="search-input">
            <i class="fas fa-search search-icon"></i>
        </div>
        
        <div class="filter-controls">
            <select id="categoryFilter" class="filter-select">
                <option value="">Все категории</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>">
                        <?= htmlspecialchars($category['name']) ?>
                        <?= $category['type'] === 'important' ? ' (Важная)' : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <select id="typeFilter" class="filter-select">
                <option value="">Все типы</option>
                <option value="regular">Обычные новости</option>
                <option value="important">Важные новости</option>
            </select>
            
            <select id="sortFilter" class="filter-select">
                <option value="date_desc">Сначала новые</option>
                <option value="date_asc">Сначала старые</option>
                <option value="title_asc">По названию А-Я</option>
                <option value="title_desc">По названию Я-А</option>
                <option value="category_asc">По категории</option>
            </select>
        </div>
    </div>

    <!-- Список новостей -->
    <div class="news-grid" id="newsGrid">
        <?php if (empty($news)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h3>Новостей пока нет</h3>
                <p>Создайте первую новость, чтобы начать работу</p>
                <a href="/admin/news/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Создать новость
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($news as $item): ?>
                <div class="news-card" data-id="<?= $item['news_id'] ?>" data-category="<?= $item['category_id'] ?? 1 ?>" data-category-type="<?= $item['category_type'] ?? 'regular' ?>">
                    <div class="news-card-header">
                        <div class="news-card-title">
                            <h3><?= htmlspecialchars($item['news_title'] ?? 'Без названия') ?></h3>
                        </div>
                        <div class="news-card-actions">
                            <div class="news-card-buttons" style="display:flex; gap:10px;">
                                <a href="/admin/news/confirm-delete/<?= $item['news_id'] ?>" class="btn btn-danger btn-sm" style="padding: 6px 12px; font-size: 12px;">
                                    <i class="fas fa-trash"></i>
                                    Удалить
                                </a>
                                <a href="/admin/news/edit/<?= $item['news_id'] ?>" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px;">
                                    <i class="fas fa-edit"></i>
                                    Редактировать
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="news-card-content">
                        <div class="news-excerpt">
                            <?php 
                            $text = $item['news_text'] ?? '';
                            $excerpt = strlen($text) > 150 ? substr($text, 0, 150) . '...' : $text;
                            echo htmlspecialchars($excerpt);
                            ?>
                        </div>
                    </div>
                    
                    <div class="news-card-footer">
                        <div class="news-meta">
                            <span class="news-date">
                                <i class="fas fa-calendar"></i>
                                <?= date('d.m.Y H:i', strtotime($item['news_date_add'] ?? 'now')) ?>
                            </span>
                            <span class="news-category">
                                <i class="fas fa-tag"></i>
                                <?= htmlspecialchars($item['category_name'] ?? 'Без категории') ?>
                                <?php if (($item['category_type'] ?? 'regular') === 'important'): ?>
                                    <span class="badge badge-important">Важная</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="news-status">
                            <span class="status-badge status-published">
                                <i class="fas fa-check-circle"></i>
                                Опубликована
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Пагинация -->
    <?php if (!empty($news) && count($news) > 10): ?>
        <div class="pagination-container">
            <nav class="pagination-nav">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Предыдущая</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Следующая</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Подтверждение удаления
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Вы уверены, что хотите удалить эту новость?</p>
                <p class="text-muted small">
                    <i class="fas fa-info-circle"></i>
                    Это действие нельзя отменить. Новость будет удалена навсегда.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Отмена
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i>
                    Удалить
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.news-management-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.news-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.news-title-section {
    display: flex;
    align-items: center;
    gap: 20px;
}

.news-title {
    color: #fff;
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.news-title i {
    color: #00d4ff;
    font-size: 32px;
}

.news-stats {
    display: flex;
    gap: 15px;
}

.stat-item {
    color: #b8c5d6;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.stat-item i {
    color: #00d4ff;
}

.news-actions {
    display: flex;
    gap: 15px;
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
}

.btn-primary {
    background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 212, 255, 0.4);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

.alert-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: #fff;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.alert-info {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    color: #fff;
    border: 1px solid rgba(23, 162, 184, 0.3);
}

.alert-danger {
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    color: #fff;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.news-filters {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 20px;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-input {
    width: 100%;
    padding: 12px 45px 12px 20px;
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    font-size: 16px;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #00d4ff;
    box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
}

.search-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
}

.filter-controls {
    display: flex;
    gap: 15px;
}

.filter-select {
    padding: 10px 15px;
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #00d4ff;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.news-card {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.news-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #00d4ff, #0099cc);
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    border-color: rgba(0, 212, 255, 0.3);
}

.news-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.news-card-title h3 {
    color: #fff;
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    line-height: 1.4;
}

.news-card-actions {
    position: relative;
}

.btn-icon {
    padding: 8px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background: rgba(255, 255, 255, 0.2);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: #1a1a2e;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    min-width: 180px;
    z-index: 1000;
    display: none;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    padding: 10px 15px;
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #00d4ff;
}

.dropdown-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
    margin: 5px 0;
}

.text-danger {
    color: #ff6b6b !important;
}

.news-card-content {
    margin-bottom: 15px;
}

.news-excerpt {
    color: #b8c5d6;
    font-size: 14px;
    line-height: 1.6;
}

.news-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.news-meta {
    display: flex;
    gap: 15px;
    font-size: 12px;
    color: #6c757d;
}

.news-date, .news-category {
    display: flex;
    align-items: center;
    gap: 5px;
}

.news-status {
    display: flex;
    align-items: center;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-published {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    grid-column: 1 / -1;
}

.empty-icon {
    font-size: 64px;
    color: #6c757d;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #fff;
    margin-bottom: 10px;
    font-size: 24px;
}

.empty-state p {
    color: #b8c5d6;
    margin-bottom: 30px;
    font-size: 16px;
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.pagination {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 5px;
}

.page-item {
    margin: 0;
}

.page-link {
    padding: 10px 15px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.page-item.active .page-link {
    background: #00d4ff;
    border-color: #00d4ff;
    color: #fff;
}

.page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .news-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .news-filters {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        max-width: none;
    }
    
    .filter-controls {
        justify-content: center;
    }
    
    .news-grid {
        grid-template-columns: 1fr;
    }
    
    .news-card-footer {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}

    /* ПРИНУДИТЕЛЬНЫЕ СТИЛИ ДЛЯ DROPDOWN */
    .news-card-actions .dropdown {
        position: relative !important;
        display: inline-block !important;
    }
    
    .news-card-actions .dropdown-toggle {
        background: rgba(139, 92, 246, 0.1) !important;
        color: var(--primary-color) !important;
        border: 1px solid rgba(139, 92, 246, 0.2) !important;
        padding: 8px 12px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
    }
    
    .news-card-actions .dropdown-toggle:hover {
        background: rgba(139, 92, 246, 0.2) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 2px 8px rgba(139, 92, 246, 0.2) !important;
    }
    
    .news-card-actions .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        min-width: 200px !important;
        padding: 8px 0 !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
        background: white !important;
        border: 1px solid rgba(139, 92, 246, 0.2) !important;
        z-index: 10000 !important;
        display: none !important;
        opacity: 0 !important;
        transform: translateY(-10px) !important;
        transition: all 0.3s ease !important;
        margin-top: 5px !important;
    }
    
    .news-card-actions .dropdown-menu.show {
        display: block !important;
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
    
    .news-card-actions .dropdown-item {
        padding: 10px 20px !important;
        color: var(--text-dark) !important;
        transition: all 0.2s ease !important;
        border: none !important;
        background: none !important;
        width: 100% !important;
        text-align: left !important;
        cursor: pointer !important;
        display: block !important;
        text-decoration: none !important;
        font-size: 14px !important;
    }
    
    .news-card-actions .dropdown-item:hover {
        background: rgba(139, 92, 246, 0.1) !important;
        color: var(--primary-color) !important;
    }
    
    .news-card-actions .dropdown-item.text-danger {
        color: #ef4444 !important;
    }
    
    .news-card-actions .dropdown-item.text-danger:hover {
        background: rgba(239, 68, 68, 0.1) !important;
        color: #dc2626 !important;
    }
    
    .news-card-actions .dropdown-divider {
        height: 1px !important;
        background: rgba(139, 92, 246, 0.2) !important;
        margin: 5px 0 !important;
        border: none !important;
    }
    
    /* Позиционирование dropdown */
    .news-card-actions {
        position: relative;
    }
    
    /* Уведомления */
    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 300px;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        opacity: 0;
        transform: translateY(-100%);
        transition: all 0.3s ease;
    }
    
    .notification-toast.alert-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
    }
    
    .notification-toast.alert-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
    }
    
    /* Модальное окно */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        padding: 20px 25px;
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .modal-footer {
        border-top: 1px solid rgba(139, 92, 246, 0.2);
        padding: 20px 25px;
    }
    
    .modal-title {
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, #4b5563, #374151);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    .news-card.important-news {
        border: 2px solid #ef4444;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05));
    }
    
    .news-card.important-news .news-card-title h3 {
        color: #ef4444;
    }
    
    .badge-important {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        margin-left: 5px;
    }
    
    .news-card[data-category-type="important"] {
        border: 2px solid #ef4444;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05));
    }
    
    .news-card[data-category-type="important"] .news-card-title h3 {
        color: #ef4444;
    }
</style>

<script>
console.log('🔍 Загрузка скрипта новостей...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM загружен');
    
    // Проверяем наличие элементов
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const dropdownMenus = document.querySelectorAll('.dropdown-menu');
    
    console.log('🔍 Найдено элементов:');
    console.log('- dropdown-toggle:', dropdownToggles.length);
    console.log('- dropdown-menu:', dropdownMenus.length);
    
    // Показываем информацию о каждом элементе
    dropdownToggles.forEach((toggle, index) => {
        console.log(`Dropdown ${index + 1}:`, toggle);
        console.log(`- nextElementSibling:`, toggle.nextElementSibling);
        console.log(`- classList:`, toggle.classList.toString());
    });
    
    // Поиск по новостям
    const searchInput = document.getElementById('newsSearch');
    const newsCards = document.querySelectorAll('.news-card');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            newsCards.forEach(card => {
                const title = card.querySelector('.news-card-title h3').textContent.toLowerCase();
                const excerpt = card.querySelector('.news-excerpt').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Фильтр по категориям
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            const selectedCategory = this.value;
            
            newsCards.forEach(card => {
                const cardCategory = card.dataset.category;
                
                if (!selectedCategory || cardCategory === selectedCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Сортировка
    const sortFilter = document.getElementById('sortFilter');
    if (sortFilter) {
        sortFilter.addEventListener('change', function() {
            const sortType = this.value;
            const newsGrid = document.getElementById('newsGrid');
            const cards = Array.from(newsCards);
            
            cards.sort((a, b) => {
                const titleA = a.querySelector('.news-card-title h3').textContent;
                const titleB = b.querySelector('.news-card-title h3').textContent;
                const dateA = new Date(a.querySelector('.news-date').textContent.replace(/[^\d.]/g, ''));
                const dateB = new Date(b.querySelector('.news-date').textContent.replace(/[^\d.]/g, ''));
                
                switch(sortType) {
                    case 'date_desc':
                        return dateB - dateA;
                    case 'date_asc':
                        return dateA - dateB;
                    case 'title_asc':
                        return titleA.localeCompare(titleB);
                    case 'title_desc':
                        return titleB.localeCompare(titleA);
                    default:
                        return 0;
                }
            });
            
            cards.forEach(card => newsGrid.appendChild(card));
        });
    }
    
    // Dropdown меню - упрощенная версия
    function toggleDropdown(button) {
        console.log('🖱️ Клик по dropdown кнопке:', button);
        
        // Закрываем все другие dropdown
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== button.nextElementSibling) {
                menu.classList.remove('show');
                console.log('Закрыт dropdown:', menu);
            }
        });
        
        // Переключаем текущий dropdown
        const dropdown = button.nextElementSibling;
        console.log('Dropdown элемент:', dropdown);
        
        if (dropdown && dropdown.classList.contains('dropdown-menu')) {
            dropdown.classList.toggle('show');
            console.log('Dropdown состояние:', dropdown.classList.contains('show') ? 'открыт' : 'закрыт');
        } else {
            console.error('❌ Не найден dropdown элемент!');
        }
    }
    
    // Добавляем обработчики для всех dropdown кнопок
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown(this);
        });
    });
    
    // Закрытие dropdown при клике вне его
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
    
    // Закрытие dropdown при нажатии Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
    
    // Инициализация Bootstrap модального окна
    if (typeof bootstrap !== 'undefined') {
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            const modal = new bootstrap.Modal(deleteModal);
        }
    }
});

// Функция удаления новости
function deleteNews(newsId) {
    // Показываем модальное окно подтверждения
    const modal = document.getElementById('deleteModal');
    const modalInstance = new bootstrap.Modal(modal);
    
    // Устанавливаем ID новости для удаления
    modal.dataset.newsId = newsId;
    
    // Показываем модальное окно
    modalInstance.show();
}

// Функция подтверждения удаления
function confirmDelete() {
    const modal = document.getElementById('deleteModal');
    const newsId = modal.dataset.newsId;
    
    // Показываем индикатор загрузки
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Удаление...';
    confirmBtn.disabled = true;
    
    // Выполняем AJAX запрос
    fetch(`/admin/news/delete/${newsId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Скрываем модальное окно
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
            
            // Удаляем карточку новости из DOM
            const newsCard = document.querySelector(`[data-id="${newsId}"]`);
            if (newsCard) {
                newsCard.style.opacity = '0';
                newsCard.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    newsCard.remove();
                    
                    // Обновляем счетчик
                    const totalElement = document.querySelector('.news-stats .stat-item strong');
                    if (totalElement) {
                        const currentTotal = parseInt(totalElement.textContent);
                        totalElement.textContent = currentTotal - 1;
                    }
                    
                    // Показываем уведомление об успехе
                    showNotification('Новость успешно удалена!', 'success');
                }, 300);
            }
        } else {
            throw new Error(data.message || 'Ошибка при удалении');
        }
    })
    .catch(error => {
        console.error('Ошибка удаления:', error);
        showNotification('Ошибка при удалении новости: ' + error.message, 'error');
        
        // Восстанавливаем кнопку
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    });
}

// Функция показа уведомлений
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} notification-toast`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Анимация появления
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 100);
    
    // Автоматическое скрытие через 3 секунды
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script> 