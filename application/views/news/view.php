
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
                                    <?php echo date('d.m.Y', strtotime($news['date'])); ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo date('H:i', strtotime($news['date'])); ?>
                                </span>
                                <span class="meta-item">
                                    <i class="fa fa-user"></i>
                                    Администрация
                                </span>
                            </div>
                            <h1 class="article-title"><?php echo htmlspecialchars($news['title']); ?></h1>
                            <div class="article-tags">
                                <span class="tag">Новость</span>
                                <span class="tag">Колледж</span>
                                <span class="tag">ГТЭК</span>
                            </div>
                        </header>
                        
                        <!-- Содержание новости -->
                        <div class="article-content">
                            <div class="content-text">
                                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
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
                            <a href="#" class="nav-link prev-link">
                                <i class="fa fa-chevron-left"></i>
                                <span>Предыдущая новость</span>
                            </a>
                            <a href="#" class="nav-link next-link">
                                <span>Следующая новость</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Похожие новости -->
                    <div class="related-news" data-aos="fade-up">
                        <h3 class="section-title">📰 Похожие новости</h3>
                        <div class="related-grid">
                            <div class="related-card">
                                <div class="related-date">15.01.2025</div>
                                <h4><a href="#">Приемная кампания 2025 года</a></h4>
                                <p>Информация о сроках подачи документов и специальностях...</p>
                            </div>
                            <div class="related-card">
                                <div class="related-date">10.01.2025</div>
                                <h4><a href="#">Новый учебный год</a></h4>
                                <p>Расписание занятий и важные объявления для студентов...</p>
                            </div>
                            <div class="related-card">
                                <div class="related-date">05.01.2025</div>
                                <h4><a href="#">Достижения студентов</a></h4>
                                <p>Поздравляем наших студентов с победами в конкурсах...</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="error-state" data-aos="fade-up">
                        <div class="error-icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <h3>Новость не найдена</h3>
                        <p>Запрашиваемая новость не существует или была удалена.</p>
                        <div class="error-actions">
                            <a href="/news" class="btn btn-primary">
                                <i class="fa fa-newspaper-o"></i>
                                Перейти к списку новостей
                            </a>
                            <a href="/" class="btn btn-outline">
                                <i class="fa fa-home"></i>
                                На главную
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Боковая панель -->
            <div class="col-lg-4 col-md-12">
                <div class="news-sidebar">
                    <!-- Информация о новости -->
                    <div class="sidebar-widget info-widget">
                        <h4>📋 Информация</h4>
                        <div class="info-content">
                            <div class="info-item">
                                <span class="info-label">Дата публикации:</span>
                                <span class="info-value"><?php echo !empty($news) ? date('d.m.Y', strtotime($news['date'])) : 'Не указана'; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Автор:</span>
                                <span class="info-value">Администрация колледжа</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Категория:</span>
                                <span class="info-value">Новости</span>
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
                            <li><a href="/abut/spec">Специальности</a></li>
                            <li><a href="/stud/dnevnoe">Студентам</a></li>
                            <li><a href="/kol/contact">Контакты</a></li>
                            <li><a href="/dopage/faq">FAQ</a></li>
                            <li><a href="/okno/info">Одно окно</a></li>
                        </ul>
                    </div>
                    
                    <!-- Подписка на новости -->
                    <div class="sidebar-widget subscribe-widget">
                        <h4>📧 Подписка на новости</h4>
                        <div class="subscribe-form">
                            <p>Получайте уведомления о новых новостях</p>
                            <input type="email" placeholder="Ваш email" class="subscribe-input">
                            <button type="submit" class="subscribe-btn">
                                <i class="fa fa-paper-plane"></i>
                                Подписаться
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section для просмотра новости */
.news-view-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 0;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
}

.news-view-hero::before {
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

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.breadcrumb-link {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
}

.breadcrumb-link:hover {
    color: white;
    text-decoration: none;
}

.breadcrumb-separator {
    color: rgba(255,255,255,0.5);
    font-size: 14px;
}

.breadcrumb-current {
    color: white;
    font-weight: 600;
    font-size: 14px;
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

/* Статья */
.news-article {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    margin-bottom: 40px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.news-article::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.6s ease;
}

.news-article:hover::before {
    left: 100%;
}

.news-article:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

/* Заголовок статьи */
.article-header {
    padding: 40px 40px 30px;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
}

.article-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.meta-item i {
    color: #667eea;
}

.article-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2c3e50;
    margin: 0 0 20px 0;
    line-height: 1.3;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.article-tags {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.tag {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* Содержание статьи */
.article-content {
    padding: 30px 40px;
}

.content-text {
    color: #495057;
    line-height: 1.8;
    font-size: 16px;
    text-align: justify;
}

.content-text p {
    margin-bottom: 20px;
}

.content-text h2, .content-text h3 {
    color: #2c3e50;
    margin: 30px 0 15px 0;
    font-weight: 700;
}

.content-text h2 {
    font-size: 24px;
}

.content-text h3 {
    font-size: 20px;
}

.content-text ul, .content-text ol {
    margin: 20px 0;
    padding-left: 30px;
}

.content-text li {
    margin-bottom: 10px;
}

/* Футер статьи */
.article-footer {
    padding: 30px 40px 40px;
    border-top: 1px solid rgba(102, 126, 234, 0.1);
}

.article-actions {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
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

/* Навигация по новостям */
.news-navigation {
    margin-bottom: 40px;
}

.nav-links {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 12px;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.nav-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    text-decoration: none;
}

/* Похожие новости */
.related-news {
    margin-bottom: 40px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.related-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
}

.related-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.related-date {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 10px;
}

.related-card h4 {
    margin: 0 0 10px 0;
    font-size: 16px;
    font-weight: 600;
}

.related-card h4 a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.related-card h4 a:hover {
    color: #667eea;
}

.related-card p {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
}

/* Ошибка */
.error-state {
    text-align: center;
    padding: 80px 20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.error-icon {
    font-size: 4rem;
    color: #dc3545;
    margin-bottom: 20px;
}

.error-state h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 24px;
}

.error-state p {
    color: #6c757d;
    margin-bottom: 30px;
    font-size: 16px;
}

.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
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

/* Информация */
.info-content {
    padding: 20px 25px 25px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #6c757d;
    font-size: 14px;
    font-weight: 500;
}

.info-value {
    color: #495057;
    font-size: 14px;
    font-weight: 600;
}

/* Быстрые ссылки */
.quick-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.quick-links li {
    margin-bottom: 0;
}

.quick-links a {
    display: block;
    padding: 15px 25px;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
    position: relative;
}

.quick-links a:last-child {
    border-bottom: none;
}

.quick-links a:hover {
    color: #667eea;
    background: rgba(102, 126, 234, 0.05);
    transform: translateX(8px);
    text-decoration: none;
}

/* Подписка */
.subscribe-form {
    padding: 20px 25px 25px;
}

.subscribe-form p {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 15px;
}

.subscribe-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7ef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
    margin-bottom: 15px;
}

.subscribe-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.subscribe-btn {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.subscribe-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Адаптивность */
@media (max-width: 1200px) {
    .news-sidebar {
        position: static;
        margin-top: 40px;
    }
}

@media (max-width: 768px) {
    .news-view-hero {
        padding: 30px 0;
    }
    
    .c-layout-page {
        margin: 16px;
        padding: 30px 20px;
    }
    
    .article-header {
        padding: 25px 25px 20px;
    }
    
    .article-title {
        font-size: 2rem;
    }
    
    .article-content {
        padding: 20px 25px;
    }
    
    .article-footer {
        padding: 20px 25px 25px;
    }
    
    .article-actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .nav-links {
        flex-direction: column;
    }
    
    .related-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .article-title {
        font-size: 1.8rem;
    }
    
    .article-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .breadcrumb-nav {
        font-size: 12px;
    }
}
</style>

<script>
function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            text: 'Интересная новость с сайта ГТЭК',
            url: window.location.href
        });
    } else {
        // Fallback для браузеров без поддержки Web Share API
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Ссылка скопирована в буфер обмена!');
        });
    }
}

// Анимация при скролле
$(document).ready(function() {
    $(window).on('scroll', function() {
        $('.news-article, .related-card').each(function() {
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
