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
                <option value="1">Общие новости</option>
                <option value="2">Академические</option>
                <option value="3">События</option>
            </select>
            
            <select id="sortFilter" class="filter-select">
                <option value="date_desc">Сначала новые</option>
                <option value="date_asc">Сначала старые</option>
                <option value="title_asc">По названию А-Я</option>
                <option value="title_desc">По названию Я-А</option>
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
                <div class="news-card" data-id="<?= $item['news_id'] ?>" data-category="<?= $item['category_id'] ?? 1 ?>">
                    <div class="news-card-header">
                        <div class="news-card-title">
                            <h3><?= htmlspecialchars($item['news_title'] ?? 'Без названия') ?></h3>
                        </div>
                        <div class="news-card-actions">
                            <div class="dropdown">
                                <button class="btn btn-icon dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="/admin/news/edit/<?= $item['news_id'] ?>" class="dropdown-item">
                                        <i class="fas fa-edit"></i>
                                        Редактировать
                                    </a>
                                    <a href="/news/view/<?= $item['news_id'] ?>" class="dropdown-item" target="_blank">
                                        <i class="fas fa-eye"></i>
                                        Просмотреть
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item text-danger" onclick="deleteNews(<?= $item['news_id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                        Удалить
                                    </button>
                                </div>
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
                                <?php
                                $categoryId = $item['category_id'] ?? 1;
                                $categories = [
                                    1 => 'Общие новости',
                                    2 => 'Академические',
                                    3 => 'События'
                                ];
                                echo $categories[$categoryId] ?? 'Общие новости';
                                ?>
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
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Вы уверены, что хотите удалить эту новость?</p>
                <p class="text-muted">Это действие нельзя отменить.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Удалить</button>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Поиск по новостям
    const searchInput = document.getElementById('newsSearch');
    const newsCards = document.querySelectorAll('.news-card');
    
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
    
    // Фильтр по категориям
    const categoryFilter = document.getElementById('categoryFilter');
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
    
    // Сортировка
    const sortFilter = document.getElementById('sortFilter');
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
    
    // Dropdown меню
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle('show');
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
});

// Функция удаления новости
function deleteNews(newsId) {
    if (confirm('Вы уверены, что хотите удалить эту новость?')) {
        window.location.href = `/admin/news/delete/${newsId}`;
    }
}
</script> 