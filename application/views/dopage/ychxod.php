<!-- Hero Section для художественной самодеятельности -->
<section class="stud-hero artistic-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">🎭</span>Художественная самодеятельность</h1>
                    <p class="hero-subtitle">Участники творческих коллективов колледжа</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?php echo count($images ?? []); ?>"><?php echo count($images ?? []); ?></span>
                            <span class="stat-label">Фотографий</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">🎨</span>
                            <span class="stat-label">Творчество</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">⭐</span>
                            <span class="stat-label">Таланты</span>
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
        
        <?php if($empty1 == (int)0): ?>
            <!-- Состояние без фотографий -->
            <div class="content-section" data-aos="fade-up" data-aos-delay="200">
                <div class="empty-gallery-state">
                    <div class="empty-icon" data-aos="zoom-in" data-aos-delay="300">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Фотографии временно отсутствуют</h3>
                    <p>Галерея участников художественной самодеятельности находится в процессе обновления</p>
                    <div class="empty-suggestion">
                        <p>Следите за обновлениями - скоро здесь появятся новые фотографии наших талантливых студентов!</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Галерея фотографий -->
            <div class="content-section" data-aos="fade-up" data-aos-delay="200">
                <h2 class="section-title">📸 Галерея участников</h2>
                <div class="artistic-gallery">
                    <div class="gallery-grid">
                        <?php foreach($images as $index => $item): ?>
                            <div class="gallery-item artistic-photo" data-aos="zoom-in" data-aos-delay="<?php echo 300 + ($index * 100); ?>">
                                <div class="photo-container">
                                    <div class="photo-overlay">
                                        <a href="/<?php echo $item['images_file']; ?>" 
                                           data-fancybox="artistic-gallery" 
                                           class="photo-view-btn">
                                            <i class="fa fa-search-plus"></i>
                                            <span>Увеличить</span>
                                        </a>
                                    </div>
                                    <img src="/<?php echo $item['images_file']; ?>" 
                                         alt="<?php echo htmlspecialchars($item['images_text']); ?>" 
                                         class="artistic-image">
                                </div>
                                <div class="photo-caption">
                                    <h4><?php echo htmlspecialchars($item['images_text']); ?></h4>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Информация о коллективах -->
            <div class="content-section" data-aos="fade-up" data-aos-delay="400">
                <h2 class="section-title">🎪 Наши творческие коллективы</h2>
                <div class="collectives-info">
                    <div class="collective-card" data-aos="slide-right" data-aos-delay="500">
                        <div class="collective-icon">💃</div>
                        <div class="collective-details">
                            <h4>Хореография "Ритмы жизни"</h4>
                            <p>Танцевальный коллектив, участник множества городских и областных конкурсов</p>
                        </div>
                    </div>
                    <div class="collective-card" data-aos="slide-left" data-aos-delay="600">
                        <div class="collective-icon">🎭</div>
                        <div class="collective-details">
                            <h4>Театральный "Креатив"</h4>
                            <p>Театральная студия для развития актёрского мастерства и сценических навыков</p>
                        </div>
                    </div>
                    <div class="collective-card" data-aos="slide-right" data-aos-delay="700">
                        <div class="collective-icon">🎵</div>
                        <div class="collective-details">
                            <h4>Вокальные группы</h4>
                            <p>Солисты и вокальные ансамбли для участия в творческих мероприятиях</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Призыв к участию -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="600">
            <div class="participation-banner artistic-cta">
                <h3>🌟 Раскройте свои таланты!</h3>
                <p>Присоединяйтесь к творческим коллективам колледжа. Развивайте свои способности, участвуйте в конкурсах и фестивалях, находите единомышленников!</p>
                <div class="cta-actions">
                    <a href="/dopage/kryjki" class="cta-btn primary">
                        <i class="fas fa-star"></i> Наши кружки
                    </a>
                    <a href="/dopage/contact" class="cta-btn secondary">
                        <i class="fas fa-phone"></i> Связаться с нами
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>