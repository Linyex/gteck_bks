
<!-- Hero Section для новостей -->
<section class="news-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title">📰 Новости колледжа</h1>
                    <p class="hero-subtitle">Будьте в курсе всех событий и важных объявлений</p>
                    <div class="news-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($news); ?></span>
                            <span class="stat-label">Новостей</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo date('Y'); ?></span>
                            <span class="stat-label">Год</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Обновления</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Основной контент -->
<div class="c-layout-page">
    <div class="container">
        <div class="row">
            <!-- Основной контент -->
            <div class="col-lg-8 col-md-12">
                <?php if (!empty($news)): ?>
                    <div class="news-grid">
                        <?php foreach ($news as $index => $item): ?>
                            <div class="news-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                                <div class="news-card-header">
                                    <div class="news-meta">
                                        <span class="news-date">
                                            <i class="fa fa-calendar"></i>
                                            <?php echo date('d.m.Y', strtotime($item['date'])); ?>
                                        </span>
                                        <span class="news-category">
                                            <i class="fa fa-tag"></i>
                                            Новость
                                        </span>
                                    </div>
                                    <h3 class="news-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                                </div>
                                <div class="news-card-body">
                                    <p class="news-excerpt">
                                        <?php echo htmlspecialchars(substr($item['content'], 0, 200)) . '...'; ?>
                                    </p>
                                    <div class="news-actions">
                                        <a href="/news/view/<?php echo $item['id']; ?>" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
                                            Читать далее
                                        </a>
                                        <button class="btn btn-outline share-btn" onclick="shareNews(<?php echo $item['id']; ?>)">
                                            <i class="fa fa-share"></i>
                                            Поделиться
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Пагинация -->
                    <div class="pagination-wrapper" data-aos="fade-up">
                        <nav aria-label="Навигация по страницам">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Предыдущая">
                                        <i class="fa fa-chevron-left"></i>
                                    </a>
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
                                    <a class="page-link" href="#" aria-label="Следующая">
                                        <i class="fa fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php else: ?>
                    <div class="empty-state" data-aos="fade-up">
                        <div class="empty-icon">
                            <i class="fa fa-newspaper-o"></i>
                        </div>
                        <h3>Новостей пока нет</h3>
                        <p>Следите за обновлениями, скоро появятся новые материалы</p>
                        <a href="/" class="btn btn-primary">
                            <i class="fa fa-home"></i>
                            На главную
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Боковая панель -->
            <div class="col-lg-4 col-md-12">
                <div class="news-sidebar">
                    <!-- Поиск новостей -->
                    <div class="sidebar-widget search-widget">
                        <h4>🔍 Поиск новостей</h4>
                        <div class="search-form">
                            <input type="text" placeholder="Введите ключевые слова..." class="search-input">
                            <button type="submit" class="search-btn">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Категории -->
                    <div class="sidebar-widget categories-widget">
                        <h4>📂 Категории</h4>
                        <ul class="categories-list">
                            <li><a href="#" class="category-link active">Все новости</a></li>
                            <li><a href="#" class="category-link">Абитуриентам</a></li>
                            <li><a href="#" class="category-link">Студентам</a></li>
                            <li><a href="#" class="category-link">Преподавателям</a></li>
                            <li><a href="#" class="category-link">События</a></li>
                            <li><a href="#" class="category-link">Объявления</a></li>
                        </ul>
                    </div>
                    
                    <!-- Последние новости -->
                    <div class="sidebar-widget recent-widget">
                        <h4>🕒 Последние новости</h4>
                        <div class="recent-news">
                            <?php 
                            $recentNews = array_slice($news, 0, 5);
                            foreach ($recentNews as $item): 
                            ?>
                            <div class="recent-item">
                                <div class="recent-date"><?php echo date('d.m', strtotime($item['date'])); ?></div>
                                <div class="recent-content">
                                    <h5><a href="/news/view/<?php echo $item['id']; ?>"><?php echo htmlspecialchars(substr($item['title'], 0, 50)) . '...'; ?></a></h5>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Архив -->
                    <div class="sidebar-widget archive-widget">
                        <h4>📅 Архив</h4>
                        <div class="archive-list">
                            <a href="#" class="archive-link">Январь 2025</a>
                            <a href="#" class="archive-link">Декабрь 2024</a>
                            <a href="#" class="archive-link">Ноябрь 2024</a>
                            <a href="#" class="archive-link">Октябрь 2024</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section для новостей */
.news-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 80px 0;
    margin-bottom: 60px;
    position: relative;
    overflow: hidden;
}

.news-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
    pointer-events: none;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 20px;
    text-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.2rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 40px;
    font-weight: 400;
}

.news-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 40px;
}

.stat-item {
    text-align: center;
    color: #1F2937 !important;
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Основной контент */
.c-layout-page {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 24px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    margin: 24px;
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.c-layout-page::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(79, 172, 254, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(240, 147, 251, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

/* Карточки новостей */
.news-grid {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.news-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.news-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.6s ease;
}

.news-card:hover::before {
    left: 100%;
}

.news-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 48px rgba(0,0,0,0.15);
}

.news-card-header {
    padding: 30px 30px 20px;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
}

.news-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.news-date, .news-category {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.news-date i, .news-category i {
    color: #667eea;
}

.news-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    line-height: 1.3;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.news-card-body {
    padding: 20px 30px 30px;
}

.news-excerpt {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 25px;
    font-size: 16px;
}

.news-actions {
    display: flex;
    gap: 15px;
    align-items: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 14px;
    padding: 12px 24px;
    border-radius: 12px;
    position: relative;
    overflow: hidden;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s ease;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    text-decoration: none;
}

/* Пустое состояние */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.empty-icon {
    font-size: 4rem;
    color: #667eea;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 24px;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 30px;
    font-size: 16px;
}

/* Пагинация */
.pagination-wrapper {
    margin-top: 50px;
    text-align: center;
}

.pagination {
    display: inline-flex;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    margin: 0;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 12px;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 600;
}

.page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    text-decoration: none;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Боковая панель */
.news-sidebar {
    position: sticky;
    top: 100px;
}

.sidebar-widget {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.sidebar-widget::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.6s ease;
}

.sidebar-widget:hover::before {
    left: 100%;
}

.sidebar-widget:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.sidebar-widget h4 {
    padding: 25px 25px 15px;
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
}

/* Поиск */
.search-form {
    padding: 20px 25px 25px;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 14px 50px 14px 16px;
    border: 2px solid #e5e7ef;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.search-btn {
    position: absolute;
    right: 35px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.search-btn:hover {
    transform: translateY(-50%) scale(1.05);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Категории */
.categories-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-link {
    display: block;
    padding: 15px 25px;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
    position: relative;
}

.category-link:last-child {
    border-bottom: none;
}

.category-link:hover {
    color: #667eea;
    background: rgba(102, 126, 234, 0.05);
    transform: translateX(8px);
    text-decoration: none;
}

.category-link.active {
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    font-weight: 600;
}

/* Последние новости */
.recent-news {
    padding: 20px 25px 25px;
}

.recent-item {
    display: flex;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
}

.recent-item:last-child {
    border-bottom: none;
}

.recent-date {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    min-width: 50px;
    text-align: center;
}

.recent-content h5 {
    margin: 0;
    font-size: 14px;
    line-height: 1.4;
}

.recent-content a {
    color: #495057;
    text-decoration: none;
    transition: color 0.3s ease;
}

.recent-content a:hover {
    color: #667eea;
}

/* Архив */
.archive-list {
    padding: 20px 25px 25px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.archive-link {
    color: #6c757d;
    text-decoration: none;
    padding: 10px 0;
    transition: all 0.3s ease;
    position: relative;
    padding-left: 20px;
}

.archive-link::before {
    content: '📅';
    position: absolute;
    left: 0;
    transition: all 0.3s ease;
}

.archive-link:hover {
    color: #667eea;
    transform: translateX(8px);
    text-decoration: none;
}

.archive-link:hover::before {
    transform: translateX(4px);
}

/* Адаптивность */
@media (max-width: 1200px) {
    .news-sidebar {
        position: static;
        margin-top: 40px;
    }
}

@media (max-width: 768px) {
    .news-hero {
        padding: 60px 0;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .news-stats {
        gap: 20px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .c-layout-page {
        margin: 16px;
        padding: 30px 20px;
    }
    
    .news-card-header {
        padding: 20px 20px 15px;
    }
    
    .news-card-body {
        padding: 15px 20px 20px;
    }
    
    .news-title {
        font-size: 20px;
    }
    
    .news-actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .news-stats {
        flex-direction: column;
        gap: 15px;
    }
    
    .news-meta {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<script>
function shareNews(newsId) {
    if (navigator.share) {
        navigator.share({
            title: 'Новость ГТЭК',
            text: 'Интересная новость с сайта колледжа',
            url: `/news/view/${newsId}`
        });
    } else {
        // Fallback для браузеров без поддержки Web Share API
        const url = `/news/view/${newsId}`;
        navigator.clipboard.writeText(url).then(() => {
            alert('Ссылка скопирована в буфер обмена!');
        });
    }
}

// Анимация при скролле
$(document).ready(function() {
    $(window).on('scroll', function() {
        $('.news-card').each(function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animate-in');
            }
        });
    });
});
</script>
