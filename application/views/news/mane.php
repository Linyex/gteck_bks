
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
                            <span class="stat-number"><?php echo isset($totalNews) ? $totalNews : (isset($news) ? count($news) : 0); ?></span>
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
<div class="c-layout-page news-container">
    <div class="container">
        <div class="row">
            <!-- Боковая панель (первая в HTML для мобильных) -->
            <div class="col-lg-4 col-md-12 d-lg-block d-md-block sidebar-mobile-first">
                <div class="news-sidebar">
                    <!-- Поиск новостей -->
                    <div class="sidebar-widget search-widget">
                        <h4>🔍 Поиск новостей</h4>
                        <form class="search-form" onsubmit="searchNews(); return false;">
                            <div class="search-input-group">
                                <input type="text" placeholder="Введите ключевые слова..." class="search-input">
                                <button type="submit" class="search-btn">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Категории -->
                    <div class="sidebar-widget categories-widget">
                        <h4>📂 Категории</h4>
                        <?php $categories = isset($categories) ? $categories : []; ?>
                        <ul class="categories-list">
                            <li>
                                <a href="/news" class="category-link active">Все новости</a>
                            </li>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <li>
                                        <a href="/news/category/<?php echo rawurlencode($cat['category_name']); ?>" class="category-link">
                                            <?php echo htmlspecialchars($cat['category_name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><span class="category-link">Категории не найдены</span></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <!-- Последние новости -->
                    <div class="sidebar-widget">
                        <h4><i class="fa fa-clock-o"></i> Последние новости</h4>
                        <?php 
                        $recentNews = $newsModel->getRegularNews(5);
                        if (!empty($recentNews)):
                        ?>
                        <div class="recent-news-list">
                            <?php foreach ($recentNews as $recentItem): ?>
                            <div class="recent-news-item">
                                <?php if (!empty($recentItem['news_image'])): ?>
                                <div class="recent-news-image">
                                    <img src="/<?php echo htmlspecialchars($recentItem['news_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($recentItem['news_title']); ?>">
                                </div>
                                <?php else: ?>
                                <div class="recent-news-image">
                                    <img src="/assets/media/news/default.jpg" 
                                         alt="Изображение по умолчанию">
                                </div>
                                <?php endif; ?>
                                <div class="recent-news-content">
                                    <h5 class="recent-news-title">
                                        <a href="/news/view/<?php echo $recentItem['news_id']; ?>">
                                            <?php echo htmlspecialchars(substr($recentItem['news_title'], 0, 40)) . '...'; ?>
                                        </a>
                                    </h5>
                                    <div class="recent-news-date">
                                        <?php echo date('d.m.Y', strtotime($recentItem['news_date_add'])); ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="empty-state">Новостей пока нет</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Архив убран по требованию -->
                </div>
            </div>
            
            <!-- Основной контент (вторая в HTML для мобильных) -->
            <div class="col-lg-8 col-md-12">
                <?php if (!empty($news)): ?>
                    <div class="news-grid">
                        <?php foreach ($news as $item): ?>
                <div class="news-card" data-aos="fade-up">
                    <?php if (!empty($item['news_image'])): ?>
                    <div class="news-image">
                        <img src="/<?php echo htmlspecialchars($item['news_image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['news_title']); ?>"
                             class="news-img">
                        <div class="news-image-overlay">
                            <div class="news-category-badge">
                                <?php echo htmlspecialchars($item['category_name'] ?? 'Новость'); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="news-card-content">
                        <div class="news-meta">
                            <span><i class="fa fa-calendar"></i> <?php echo date('d.m.Y', strtotime($item['news_date_add'])); ?></span>
                            <span><i class="fa fa-user"></i> <?php echo htmlspecialchars($item['news_author'] ?? 'Администрация'); ?></span>
                            <span class="news-time"><i class="fa fa-clock-o"></i> <?php echo date('H:i', strtotime($item['news_date_add'])); ?></span>
                        </div>
                        <h3 class="news-title">
                            <a href="/news/view/<?php echo $item['news_id']; ?>"><?php echo htmlspecialchars($item['news_title']); ?></a>
                        </h3>
                        <p class="news-excerpt"><?php echo htmlspecialchars(substr($item['news_text'], 0, 200)) . '...'; ?></p>
                        <div class="news-actions">
                            <a href="/news/view/<?php echo $item['news_id']; ?>" class="btn btn-primary">
                                <i class="fa fa-eye"></i> Читать далее
                            </a>
                            <button class="bookmark-btn" onclick="bookmarkNews(<?php echo $item['news_id']; ?>)" data-news-id="<?php echo $item['news_id']; ?>">
                                <i class="fa fa-bookmark"></i> Закладка
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                    </div>
                    
                    <!-- Пагинация -->
                    <?php if (!empty($pagination)): ?>
                        <div class="pagination-wrapper" data-aos="fade-up">
                            <?php echo $pagination; ?>
                        </div>
                    <?php endif; ?>
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
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" data-aos="fade-up">
                        <i class="fa fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
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
    display: flex; /* Added for image and content layout */
    flex-direction: column; /* Added for image and content layout */
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

.news-image {
    position: relative;
    width: 100%;
    height: 250px; /* Fixed height for image */
    overflow: hidden;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1); /* Added border for image */
}

.news-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.news-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Dark overlay */
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.news-card:hover .news-image-overlay {
    opacity: 1;
}

.news-category-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.news-card-content {
    padding: 20px 30px 30px;
    flex-grow: 1; /* Allow content to grow and take available space */
}

.news-card-header {
    padding-bottom: 15px; /* Adjusted padding */
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
    margin-bottom: 15px; /* Added margin */
}

.news-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 10px; /* Adjusted margin */
}

.news-date, .news-category, .news-time {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.news-date i, .news-category i, .news-time i {
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
    padding-top: 0; /* Adjusted padding */
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
    flex-wrap: wrap; /* Allow buttons to wrap */
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

.btn-icon {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 10px; /* Adjusted padding for icon-only button */
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-icon:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    text-decoration: none;
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

.search-input-group {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.9);
    border: 2px solid #e5e7ef;
    border-radius: 12px;
    overflow: hidden;
}

.search-input {
    flex-grow: 1;
    padding: 14px 16px;
    border: none;
    font-size: 16px;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.search-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 0 12px 12px 0;
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

.recent-image {
    width: 60px;
    height: 60px;
    overflow: hidden;
    border-radius: 8px;
}

.recent-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recent-content {
    flex-grow: 1;
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
    
    .news-card {
        flex-direction: column; /* Stack image and content on small screens */
    }

    .news-image {
        height: 200px; /* Adjust image height for smaller screens */
        border-bottom: none; /* Remove border if stacked */
        margin-bottom: 15px; /* Add margin below image */
    }

    .news-card-content {
        padding: 0 20px 20px; /* Adjust content padding */
    }

    .news-card-header {
        padding-bottom: 10px; /* Adjust header padding */
        margin-bottom: 10px; /* Adjust header margin */
    }

    .news-meta {
        flex-direction: column; /* Stack meta items */
        gap: 10px; /* Adjust gap */
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

<!-- Подключение JavaScript для функций новостей -->
<script src="/assets/js/news-functions.js"></script>

<!-- Подключение современного CSS для новостей -->
<link href="/assets/css/news-modern.css?v=2.0.0" rel="stylesheet" type="text/css" />
