<!-- Hero Section для новостей колледжа -->
<section class="stud-hero vesti-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">📰</span>Вести колледжа</h1>
                    <p class="hero-subtitle">Актуальные новости и события нашего учебного заведения</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?php echo count($filesa ?? []); ?>"><?php echo count($filesa ?? []); ?></span>
                            <span class="stat-label">Новостей</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">📅</span>
                            <span class="stat-label">Регулярно</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Достоверность</span>
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
        
        <!-- Новости -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="200">
            <h2 class="section-title">📋 Список новостей</h2>
            
            <?php if (!empty($filesa)): ?>
                <div class="news-list">
                    <?php foreach($filesa as $index => $item): ?>
                        <div class="news-item" data-aos="slide-up" data-aos-delay="<?php echo 300 + ($index * 100); ?>">
                            <div class="news-icon">📄</div>
                            <div class="news-content">
                                <h4 class="news-title"><?php echo htmlspecialchars($item['files_text']); ?></h4>
                                <div class="news-meta">
                                    <span class="news-type">Документ</span>
                                    <span class="news-format">
                                        <?php
                                        $ext = strtolower(pathinfo($item['files_file'], PATHINFO_EXTENSION));
                                        echo strtoupper($ext);
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="news-actions">
                                <a href="/<?php echo htmlspecialchars($item['files_file']); ?>" 
                                   download 
                                   class="news-download-btn">
                                    <i class="fas fa-download"></i>
                                    <span>Скачать</span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-news-state" data-aos="zoom-in" data-aos-delay="300">
                    <div class="empty-icon">📭</div>
                    <h4>Новости отсутствуют</h4>
                    <p>На данный момент нет новых вестей колледжа</p>
                    <div class="empty-suggestion">
                        <p>Следите за обновлениями - новости появятся в ближайшее время!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Информация о новостях -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="400">
            <h2 class="section-title">ℹ️ О новостях колледжа</h2>
            <div class="info-grid">
                <div class="info-card" data-aos="flip-left" data-aos-delay="500">
                    <div class="info-icon">🔄</div>
                    <h4>Регулярные обновления</h4>
                    <p>Новости публикуются по мере поступления важной информации</p>
                </div>
                <div class="info-card" data-aos="flip-left" data-aos-delay="600">
                    <div class="info-icon">📢</div>
                    <h4>Официальная информация</h4>
                    <p>Все новости проходят проверку администрации колледжа</p>
                </div>
                <div class="info-card" data-aos="flip-left" data-aos-delay="700">
                    <div class="info-icon">💾</div>
                    <h4>Архив документов</h4>
                    <p>Важные документы сохраняются для скачивания</p>
                </div>
            </div>
        </div>

    </div>
</div>