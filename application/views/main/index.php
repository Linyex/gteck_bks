<?php 
// Проверяем, определена ли переменная $header
if (!isset($header)) {
    $header = '';
}
echo $header; 
?>

<!-- Дополнительные образовательные анимации фона -->
<div class="academic-symbol" style="content: '📖';">📖</div>
<div class="academic-symbol" style="content: '🎨';">🎨</div>
<div class="academic-symbol" style="content: '🔧';">🔧</div>
<div class="academic-symbol" style="content: '📊';">📊</div>
<div class="academic-symbol" style="content: '🌱';">🌱</div>

<!-- Научные формулы -->
<div class="science-formula" style="content: 'H₂O';">H₂O</div>
<div class="science-formula" style="content: 'CO₂';">CO₂</div>
<div class="science-formula" style="content: 'NaCl';">NaCl</div>
<div class="science-formula" style="content: 'Fe₂O₃';">Fe₂O₃</div>

<!-- Лабораторные колбы -->
<div class="lab-flask"></div>
<div class="lab-flask"></div>
<div class="lab-flask"></div>

<!-- Микроскопы с линзами -->
<div class="microscope-lens"></div>
<div class="microscope-lens"></div>

<!-- Книжные стопки с текстом -->
<div class="book-stack"></div>
<div class="book-stack"></div>
<div class="book-stack"></div>

<!-- Атомные модели -->
<div class="atom-model"></div>
<div class="atom-model"></div>

<!-- Научные диаграммы -->
<div class="science-diagram"></div>
<div class="science-diagram"></div>

<!-- Modern Hero Section -->
<section class="main-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-12">
                <div class="hero-content" data-aos="fade-up">
                    <h1 class="hero-title">
                        Добро пожаловать в <span class="highlight">УО "Гомельский торгово-экономический колледж" Белкоопсоюза</span>
                    </h1>
                    <p class="hero-subtitle">
                        Старейшее учебное заведение потребительской кооперации Республики Беларусь. 
                        Свою историю колледж ведет с 1944 года.
                    </p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-count="80">80</span>
                            <span class="stat-label">Лет истории</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="4">4</span>
                            <span class="stat-label">Специальности</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="30">30K+</span>
                            <span class="stat-label">Выпускников</span>
                        </div>
                    </div>
                    
                    <!-- Слайдер важной информации -->
                    <div class="important-info-slider" data-aos="fade-up" data-aos-delay="300">
                        <h3 class="slider-title">
                            <i class="fa fa-star"></i> Важная информация
                        </h3>
                        <div class="info-slider">
                            <div class="info-slide active">
                                <div class="info-content">
                                    <div class="info-icon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                    <div class="info-text">
                                        <h4>Приемная кампания 2025</h4>
                                        <p>Начался прием документов на 2025/2026 учебный год. Спешите подать заявление!</p>
                                        <a href="/abut" class="info-link">Подробнее <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-slide">
                                <div class="info-content">
                                    <div class="info-icon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <div class="info-text">
                                        <h4>День открытых дверей</h4>
                                        <p>Приглашаем абитуриентов и их родителей на день открытых дверей 15 марта 2025 года</p>
                                        <a href="/news" class="info-link">Подробнее <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-slide">
                                <div class="info-content">
                                    <div class="info-icon">
                                        <i class="fa fa-trophy"></i>
                                    </div>
                                    <div class="info-text">
                                        <h4>Достижения студентов</h4>
                                        <p>Наши студенты заняли первое место в республиканской олимпиаде по экономике</p>
                                        <a href="/news" class="info-link">Подробнее <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-slide">
                                <div class="info-content">
                                    <div class="info-icon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div class="info-text">
                                        <h4>Новые специальности</h4>
                                        <p>Открыт набор на новую специальность "Разработка и сопровождение программного обеспечения"</p>
                                        <a href="/abut" class="info-link">Подробнее <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Навигация слайдера -->
                        <div class="info-slider-nav">
                            <div class="info-dot active" data-slide="0"></div>
                            <div class="info-dot" data-slide="1"></div>
                            <div class="info-dot" data-slide="2"></div>
                            <div class="info-dot" data-slide="3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<div class="c-layout-page">
    <div class="container">
        <div class="row">
            <!-- Основной контент -->
            <div class="col-lg-8 col-md-12">
                <!-- Важные новости -->
                <div class="content-section" data-aos="fade-up">
                    <h2 class="section-title">
                        <i class="fa fa-star"></i> Важные новости
                    </h2>
                    <div class="important-news-slider">
                        <div class="news-slider-track">
                            <?php if (!empty($importantNews)): ?>
                                <?php foreach ($importantNews as $index => $news_item): ?>
                                    <div class="news-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <div class="news-card">
                                            <div class="news-image">
                                                <?php if (!empty($news_item['news_image'])): ?>
                                                    <img src="/<?php echo $news_item['news_image']; ?>"
                                                         alt="<?php echo htmlspecialchars($news_item['news_title']); ?>"
                                                         class="news-img"
                                                         onerror="this.src='/assets/media/news/default.jpg'">
                                                <?php else: ?>
                                                    <div class="newspaper-animation">
                                                        <div class="newspaper-sheet" style="--delay: 0s; --direction: 1;">
                                                            <div class="newspaper-content">
                                                                <div class="newspaper-text">📰</div>
                                                                <div class="newspaper-text">📄</div>
                                                                <div class="newspaper-text">📋</div>
                                                            </div>
                                                        </div>
                                                        <div class="newspaper-sheet" style="--delay: 2s; --direction: -1;">
                                                            <div class="newspaper-content">
                                                                <div class="newspaper-text">📰</div>
                                                                <div class="newspaper-text">📄</div>
                                                                <div class="newspaper-text">📋</div>
                                                            </div>
                                                        </div>
                                                        <div class="newspaper-sheet" style="--delay: 4s; --direction: 1;">
                                                            <div class="newspaper-content">
                                                                <div class="newspaper-text">📰</div>
                                                                <div class="newspaper-text">📄</div>
                                                                <div class="newspaper-text">📋</div>
                                                            </div>
                                                        </div>
                                                        <div class="newspaper-sheet" style="--delay: 6s; --direction: -1;">
                                                            <div class="newspaper-content">
                                                                <div class="newspaper-text">📰</div>
                                                                <div class="newspaper-text">📄</div>
                                                                <div class="newspaper-text">📋</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="news-content">
                                                <div class="news-date"><?php echo date('d.m.Y', strtotime($news_item['news_date_add'])); ?></div>
                                                <h3><?php echo htmlspecialchars($news_item['news_title']); ?></h3>
                                                <p><?php echo htmlspecialchars(mb_substr($news_item['news_text'], 0, 150)) . (mb_strlen($news_item['news_text']) > 150 ? '...' : ''); ?></p>
                                                <a href="/news/view/<?php echo $news_item['news_id']; ?>" class="news-link">Читать далее <i class="fa fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="news-slide active">
                                    <div class="news-card">
                                        <div class="news-image">
                                            <div class="newspaper-animation">
                                                <div class="newspaper-sheet" style="--delay: 0s; --direction: 1;">
                                                    <div class="newspaper-content">
                                                        <div class="newspaper-text">📰</div>
                                                        <div class="newspaper-text">📄</div>
                                                        <div class="newspaper-text">📋</div>
                                                    </div>
                                                </div>
                                                <div class="newspaper-sheet" style="--delay: 2s; --direction: -1;">
                                                    <div class="newspaper-content">
                                                        <div class="newspaper-text">📰</div>
                                                        <div class="newspaper-text">📄</div>
                                                        <div class="newspaper-text">📋</div>
                                                    </div>
                                                </div>
                                                <div class="newspaper-sheet" style="--delay: 4s; --direction: 1;">
                                                    <div class="newspaper-content">
                                                        <div class="newspaper-text">📰</div>
                                                        <div class="newspaper-text">📄</div>
                                                        <div class="newspaper-text">📋</div>
                                                    </div>
                                                </div>
                                                <div class="newspaper-sheet" style="--delay: 6s; --direction: -1;">
                                                    <div class="newspaper-content">
                                                        <div class="newspaper-text">📰</div>
                                                        <div class="newspaper-text">📄</div>
                                                        <div class="newspaper-text">📋</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="news-content">
                                            <div class="news-date"><?php echo date('d.m.Y'); ?></div>
                                            <h3>Важные новости</h3>
                                            <p>Здесь будут отображаться важные новости колледжа. Следите за обновлениями!</p>
                                            <a href="/news" class="news-link">Все новости <i class="fa fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Навигация слайдера -->
                        <?php if (!empty($importantNews) && count($importantNews) > 1): ?>
                            <div class="news-slider-nav">
                                <button class="news-nav-btn prev" id="newsPrev">
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                                <div class="news-dots">
                                    <?php foreach ($importantNews as $index => $news_item): ?>
                                        <div class="news-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="news-nav-btn next" id="newsNext">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Обычные новости -->
                <div class="content-section" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="section-title">
                        <i class="fa fa-newspaper-o"></i> Последние новости
                    </h2>
                    <div class="regular-news-grid">
                        <?php if (!empty($regularNews)): ?>
                            <?php foreach ($regularNews as $news_item): ?>
                                <div class="news-item">
                                    <div class="news-item-image">
                                        <?php if (!empty($news_item['news_image'])): ?>
                                            <img src="/<?php echo $news_item['news_image']; ?>" 
                                                 alt="<?php echo htmlspecialchars($news_item['news_title']); ?>"
                                                 class="news-img">
                                        <?php else: ?>
                                            <div class="news-placeholder">
                                                <i class="fa fa-newspaper-o"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="news-item-content">
                                        <div class="news-item-meta">
                                            <span class="news-date"><?php echo date('d.m.Y', strtotime($news_item['news_date_add'])); ?></span>
                                            <?php if (!empty($news_item['category_name'])): ?>
                                                <span class="news-category"><?php echo htmlspecialchars($news_item['category_name']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="news-item-title"><?php echo htmlspecialchars($news_item['news_title']); ?></h3>
                                        <p class="news-item-excerpt">
                                            <?php echo htmlspecialchars(mb_substr($news_item['news_text'], 0, 100)) . (mb_strlen($news_item['news_text']) > 100 ? '...' : ''); ?>
                                        </p>
                                        <a href="/news/view/<?php echo $news_item['news_id']; ?>" class="news-item-link">
                                            Читать далее <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-news-message">
                                <i class="fa fa-newspaper-o"></i>
                                <h3>Новостей пока нет</h3>
                                <p>Следите за обновлениями на сайте</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="news-more">
                        <a href="/news" class="news-more-btn">
                            <i class="fa fa-list"></i> Все новости
                        </a>
                    </div>
                </div>
                
                <!-- Основные разделы -->
                <div class="content-section" data-aos="fade-up" data-aos-delay="400">
                    <h2 class="section-title">🚀 Основные разделы</h2>
                    <div class="sections-grid">
                        <div class="section-card">
                            <div class="section-icon">📰</div>
                            <div class="section-content">
                                <h3>Новости</h3>
                                <p>Последние новости и события колледжа</p>
                                <div class="section-features">
                                    <span class="feature">📖 Читать</span>
                                    <span class="feature">📅 Архив</span>
                                    <span class="feature">🔔 Уведомления</span>
                                </div>
                                <a href="/news" class="section-btn">
                                    <i class="fa fa-arrow-right"></i>
                                    Перейти к новостям
                                </a>
                            </div>
                        </div>
                        
                        <div class="section-card">
                            <div class="section-icon">🎓</div>
                            <div class="section-content">
                                <h3>Абитуриентам</h3>
                                <p>Информация для поступающих в колледж</p>
                                <div class="section-features">
                                    <span class="feature">📚 Специальности</span>
                                    <span class="feature">📋 Документы</span>
                                    <span class="feature">📅 Сроки</span>
                                </div>
                                <a href="/abut" class="section-btn">
                                    <i class="fa fa-arrow-right"></i>
                                    Информация для абитуриентов
                                </a>
                            </div>
                        </div>
                        
                        <div class="section-card">
                            <div class="section-icon">👨‍🎓</div>
                            <div class="section-content">
                                <h3>Студентам</h3>
                                <p>Материалы и ресурсы для студентов</p>
                                <div class="section-features">
                                    <span class="feature">📝 Контрольные</span>
                                    <span class="feature">📚 Библиотека</span>
                                    <span class="feature">🏠 Общежитие</span>
                                </div>
                                <a href="/stud" class="section-btn">
                                    <i class="fa fa-arrow-right"></i>
                                    Студенческий раздел
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Быстрые ссылки -->
                <div class="content-section" data-aos="fade-up" data-aos-delay="600">
                    <h2 class="section-title">🔗 Быстрые ссылки</h2>
                    <div class="quick-links-grid">
                        <div class="links-column">
                            <h4>📋 Полезные разделы</h4>
                            <ul class="links-list">
                                <li><a href="/okno">Одно окно</a></li>
                                <li><a href="/message">FAQ</a></li>
                                <li><a href="/search">Поиск по сайту</a></li>
                                <li><a href="/prepod">Преподавателям</a></li>
                            </ul>
                        </div>
                        <div class="links-column">
                            <h4>📞 Контакты</h4>
                            <ul class="links-list">
                                <li><a href="/kol/contact">Контакты</a></li>
                                <li><a href="/kol/grafik">График работы</a></li>
                                <li><a href="/kol/foto">Фотогалерея</a></li>
                                <li><a href="/kol/video">Видео</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Боковая панель -->
            <div class="col-lg-4 col-md-12">
                <div class="widgets-sidebar">
                    <!-- Поиск -->
                    <div class="search-widget" data-aos="fade-left">
                        <h4><i class="fa fa-search"></i> Поиск по сайту</h4>
                        <form id="searchForm" method="POST">
                            <div class="search-input">
                                <input type="text" name="Search_text" id="Search_text" placeholder="Поиск по сайту..">
                                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                        <div class="search-results" id="searchResults"></div>
                    </div>

                    <!-- Информация -->
                    <div class="info-widget" data-aos="fade-left" data-aos-delay="100">
                        <h4><i class="fa fa-info-circle"></i> Дополнительная информация</h4>
                        <?php if (!empty($lastzamena['zamena_file'])): ?>
                            <div class="info-item">
                                <a href="/<?php echo ($lastzamena['zamena_file']) ?>" target="_blank" class="info-link">
                                    <i class="fa fa-calendar"></i>
                                    <span>Изменения в расписании<br><?php echo $lastzamena['zamena_text'] ?></span>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="info-item">
                                <span class="info-text"><i class="fa fa-check"></i> Изменений в расписании нет</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <a href="https://docs.google.com/spreadsheets/d/1YGUg5U5KBQWBqi88gi8SCAEQTnf_CtDZs_U1usbUj7o/edit?usp=sharing" target="_blank" class="info-link">
                                <i class="fa fa-chart-line"></i>
                                <span>Ход приёма документов</span>
                            </a>
                        </div>
                        
                        <div class="info-item">
                            <a href="/assets/files/№ 88 от 27.03.2025 Об утверждении Порядка приема на 2025 год.pdf" target="_blank" class="info-link">
                                <i class="fa fa-file-pdf-o"></i>
                                <span>Порядок приёма абитуриентов 2025 год</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Контакты -->
                    <div class="contacts-widget" data-aos="fade-left" data-aos-delay="200">
                        <h4><i class="fa fa-phone"></i> Контакты</h4>
                        <div class="contact-info">
                            <p><i class="fa fa-map-marker"></i> 246017, г. Гомель, ул. Привокзальная, 4</p>
                            <p><i class="fa fa-envelope"></i> gtec@mail.gomel.by</p>
                            <p><i class="fa fa-phone"></i> 8(0232) 33-70-02</p>
                            <p><i class="fa fa-phone"></i> Приемная комиссия: +375 232 20-22-14</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Подключение JavaScript -->
<script src="/assets/js/main-page.js?v=1.2.0"></script>

<?php 
// Проверяем, определена ли переменная $footer
if (!isset($footer)) {
    $footer = '';
}
echo $footer; 
?>
