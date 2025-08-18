
<!-- Hero Section для новости -->
<section class="news-view-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content" data-aos="fade-up">
                    <div class="breadcrumb-nav">
                        <a href="/" class="breadcrumb-link">Главная</a>
                        <span class="breadcrumb-separator">/</span>
                        <a href="/news" class="breadcrumb-link">Новости</a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-current">Просмотр</span>
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
                    <article class="news-article" data-aos="fade-up">
                        <!-- Заголовок новости -->
                        <header class="article-header">
                            <div class="article-meta">
                                <span class="meta-item">
                                    <i class="fa fa-calendar"></i>
                                    <?php echo date('d.m.Y', strtotime($news['news_date_add'])); ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo date('H:i', strtotime($news['news_date_add'])); ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fa fa-user"></i>
                                    Администрация
                                </span>
                                <?php if (!empty($news['category_name'])): ?>
                                <span class="meta-item">
                                    <i class="fa fa-tag"></i>
                                    <?php echo htmlspecialchars($news['category_name']); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <h1 class="article-title"><?php echo htmlspecialchars($news['news_title']); ?></h1>
                            <div class="article-tags">
                                <span class="tag">Новость</span>
                                <span class="tag">Колледж</span>
                                <span class="tag">ГТЭК</span>
                            </div>
                        </header>
                        
                        <!-- Изображение новости -->
                        <?php if (!empty($news['news_image'])): ?>
                        <div class="article-image">
                            <img src="/<?php echo htmlspecialchars($news['news_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($news['news_title']); ?>" 
                                 class="article-img">
                            <div class="image-overlay">
                                <div class="image-caption">
                                    <?php echo htmlspecialchars($news['news_title']); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Содержание новости -->
                        <div class="article-content">
                            <div class="content-text">
                                <?php echo nl2br(htmlspecialchars($news['news_text'])); ?>
                            </div>
                        </div>
                        
                        <!-- Действия с новостью -->
                        <footer class="article-footer">
                            <div class="article-actions">
                                <button class="btn btn-primary share-btn" onclick="shareArticle()">
                                    <i class="fa fa-share"></i>
                                    Поделиться
                                </button>
                                <button class="btn btn-outline print-btn" onclick="window.print()">
                                    <i class="fa fa-print"></i>
                                    Печать
                                </button>
                                <button class="btn btn-icon bookmark-btn" onclick="bookmarkArticle()">
                                    <i class="fa fa-bookmark-o"></i>
                                </button>
                                <a href="/news" class="btn btn-outline">
                                    <i class="fa fa-arrow-left"></i>
                                    Назад к списку
                                </a>
                            </div>
                        </footer>
                    </article>
                    
                    <!-- Навигация по новостям -->
                    <div class="news-navigation" data-aos="fade-up">
                        <div class="nav-links">
                            <?php if (!empty($adjacent['prev'])): ?>
                            <a href="/news/view/<?php echo (int)$adjacent['prev']['news_id']; ?>" class="nav-link prev-link">
                                <i class="fa fa-chevron-left"></i>
                                <span><?php echo htmlspecialchars(mb_strimwidth($adjacent['prev']['news_title'], 0, 40, '…')); ?></span>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($adjacent['next'])): ?>
                            <a href="/news/view/<?php echo (int)$adjacent['next']['news_id']; ?>" class="nav-link next-link">
                                <span><?php echo htmlspecialchars(mb_strimwidth($adjacent['next']['news_title'], 0, 40, '…')); ?></span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                <!-- Дополнительные изображения -->
                <?php if (!empty($news['additional_images'])): ?>
                <div class="additional-images" data-aos="fade-up">
                    <h3 class="section-title">📸 Дополнительные фотографии</h3>
                    <div class="images-gallery">
                        <?php foreach ($news['additional_images'] as $image): ?>
                        <div class="gallery-item">
                            <div class="gallery-image">
                                <img src="/<?php echo htmlspecialchars($image['img_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($image['img_alt'] ?? $news['news_title']); ?>"
                                     class="gallery-img"
                                     onclick="openImageModal(this)">
                                <div class="image-overlay">
                                    <div class="image-caption">
                                        <?php echo htmlspecialchars($image['img_alt'] ?? $news['news_title']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                    
                    <!-- Похожие новости -->
                    <div class="related-news" data-aos="fade-up">
                        <div class="section-title">
                            <span>📰 Похожие новости</span>
                            <div class="slider-controls">
                                <button class="slider-btn" id="prevBtn" onclick="slideRelatedNews('prev')">
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                                <button class="slider-btn" id="nextBtn" onclick="slideRelatedNews('next')">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="related-slider">
                            <div class="related-slider-container" id="relatedSlider">
                                <?php 
                                // Получаем похожие новости из той же категории
                                $newsModel = $this->loadModel('news');
                                $relatedNews = $newsModel->getNewsByCategory($news['category_id'], 6);
                                
                                if (!empty($relatedNews)):
                                    foreach ($relatedNews as $relatedItem):
                                        // Пропускаем текущую новость
                                        if ($relatedItem['news_id'] == $news['news_id']) continue;
                                ?>
                            <div class="related-card">
                                    <?php if (!empty($relatedItem['news_image'])): ?>
                                    <div class="related-image">
                                        <img src="/<?php echo htmlspecialchars($relatedItem['news_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($relatedItem['news_title']); ?>">
                                    </div>
                                    <?php else: ?>
                                    <div class="related-image">
                                        <img src="/assets/media/news/default.jpg" 
                                             alt="Изображение по умолчанию">
                                    </div>
                                    <?php endif; ?>
                                    <div class="related-content">
                                        <div class="related-date"><?php echo date('d.m.Y', strtotime($relatedItem['news_date_add'])); ?></div>
                                        <h4><a href="/news/view/<?php echo $relatedItem['news_id']; ?>"><?php echo htmlspecialchars(substr($relatedItem['news_title'], 0, 50)) . '...'; ?></a></h4>
                                        <p><?php echo htmlspecialchars(substr($relatedItem['news_text'], 0, 100)) . '...'; ?></p>
                                    </div>
                            </div>
                                <?php 
                                    endforeach;
                                else:
                                    // Если нет похожих новостей, показываем последние новости
                                    $latestNews = $newsModel->getRegularNews(6);
                                    foreach ($latestNews as $latestItem):
                                        if ($latestItem['news_id'] == $news['news_id']) continue;
                                ?>
                            <div class="related-card">
                                    <?php if (!empty($latestItem['news_image'])): ?>
                                    <div class="related-image">
                                        <img src="/<?php echo htmlspecialchars($latestItem['news_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($latestItem['news_title']); ?>">
                                    </div>
                                    <?php else: ?>
                                    <div class="related-image">
                                        <img src="/assets/media/news/default.jpg" 
                                             alt="Изображение по умолчанию">
                                    </div>
                                    <?php endif; ?>
                                    <div class="related-content">
                                        <div class="related-date"><?php echo date('d.m.Y', strtotime($latestItem['news_date_add'])); ?></div>
                                        <h4><a href="/news/view/<?php echo $latestItem['news_id']; ?>"><?php echo htmlspecialchars(substr($latestItem['news_title'], 0, 50)) . '...'; ?></a></h4>
                                        <p><?php echo htmlspecialchars(substr($latestItem['news_text'], 0, 100)) . '...'; ?></p>
                                    </div>
                                </div>
                                <?php 
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state" data-aos="fade-up">
                        <div class="empty-icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <h3>Новость не найдена</h3>
                        <p>Запрашиваемая новость не существует или была удалена</p>
                            <a href="/news" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i>
                            Вернуться к новостям
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Боковая панель -->
            <div class="col-lg-4 col-md-12">
                <div class="news-sidebar">
                    <!-- Информация о новости -->
                    <div class="sidebar-widget info-widget">
                        <h4>ℹ️ Информация</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="info-label">Дата публикации:</span>
                                <span class="info-value"><?php echo isset($news['news_date_add']) ? date('d.m.Y H:i', strtotime($news['news_date_add'])) : 'Не указана'; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Категория:</span>
                                <span class="info-value"><?php echo htmlspecialchars($news['category_name'] ?? 'Общие'); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Автор:</span>
                                <span class="info-value">Администрация колледжа</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Просмотров:</span>
                                <span class="info-value">1,234</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Быстрые ссылки -->
                    <div class="sidebar-widget links-widget">
                        <h4>🔗 Быстрые ссылки</h4>
                        <ul class="quick-links">
                            <li><a href="/news" class="quick-link">Все новости</a></li>
                            <li><a href="/news/category/abiturient" class="quick-link">Новости для абитуриентов</a></li>
                            <li><a href="/news/category/student" class="quick-link">Новости для студентов</a></li>
                            <li><a href="/news/category/teacher" class="quick-link">Новости для преподавателей</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Подключение JavaScript для функций новостей -->
<script src="/assets/js/news-functions.js"></script>

<!-- Подключение современного CSS для новостей -->
<link href="/assets/css/news-modern.css?v=2.0.0" rel="stylesheet" type="text/css" />
