<!-- Hero Section для категории -->
<section class="news-category-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title">📰 <?php echo htmlspecialchars($namecat); ?></h1>
                    <p class="hero-subtitle">Новости в категории "<?php echo htmlspecialchars($namecat); ?>"</p>
                    <div class="breadcrumb-nav">
                        <a href="/" class="breadcrumb-link">Главная</a>
                        <span class="breadcrumb-separator">/</span>
                        <a href="/news" class="breadcrumb-link">Новости</a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-current"><?php echo htmlspecialchars($namecat); ?></span>
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
                    <!-- Информация о категории -->
                    <div class="sidebar-widget category-info">
                        <h4>📂 Категория: <?php echo htmlspecialchars($namecat); ?></h4>
                        <div class="category-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?php echo count($news); ?></span>
                                <span class="stat-label">Новостей в категории</span>
                            </div>
                        </div>
                    </div>
                    
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
                        <ul class="categories-list">
                            <li><a href="/news" class="category-link">Все новости</a></li>
                            <li><a href="/news/category/abiturient" class="category-link">Абитуриентам</a></li>
                            <li><a href="/news/category/student" class="category-link">Студентам</a></li>
                            <li><a href="/news/category/teacher" class="category-link">Преподавателям</a></li>
                            <li><a href="/news/category/event" class="category-link">События</a></li>
                            <li><a href="/news/category/announcement" class="category-link">Объявления</a></li>
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
                            <i class="fa fa-folder-open"></i>
                        </div>
                        <h3>В этой категории пока нет новостей</h3>
                        <p>Следите за обновлениями, скоро появятся новые материалы</p>
                        <a href="/news" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i>
                            Все новости
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
