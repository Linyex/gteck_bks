<!-- Hero Section для фото лучших спортсменов -->
<section class="stud-hero best-athletes-photo-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">📸</span>Лучшие спортсмены</h1>
                    <p class="hero-subtitle">Фотогалерея наших спортивных звёзд</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-count="<?php echo count($images ?? []); ?>"><?php echo count($images ?? []); ?></span>
                            <span class="stat-label">Фотографий</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">🏆</span>
                            <span class="stat-label">Чемпионы</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">⭐</span>
                            <span class="stat-label">Достижения</span>
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
                <div class="empty-gallery-state sports-empty">
                    <div class="empty-icon" data-aos="zoom-in" data-aos-delay="300">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Фотографии временно отсутствуют</h3>
                    <p>Галерея наших лучших спортсменов обновляется</p>
                    <div class="empty-suggestion">
                        <p>Скоро здесь появятся фотографии наших чемпионов и победителей соревнований!</p>
                    </div>
                    
                    <!-- Мотивационная секция -->
                    <div class="motivation-section" data-aos="slide-up" data-aos-delay="400">
                        <h4>🏆 А пока вы можете:</h4>
                        <div class="motivation-actions">
                            <a href="/dopage/bestsport" class="motivation-btn">
                                <i class="fas fa-trophy"></i>
                                <span>Узнать о достижениях</span>
                            </a>
                            <a href="/dopage/sportkr" class="motivation-btn">
                                <i class="fas fa-running"></i>
                                <span>Записаться в секцию</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Галерея фотографий спортсменов -->
            <div class="content-section" data-aos="fade-up" data-aos-delay="200">
                <h2 class="section-title">🏆 Галерея чемпионов</h2>
                <div class="athletes-gallery">
                    <div class="gallery-grid sports-grid">
                        <?php foreach($images as $index => $item): ?>
                            <div class="athlete-photo-card" data-aos="zoom-in" data-aos-delay="<?php echo 300 + ($index * 100); ?>">
                                <div class="photo-container sports-photo">
                                    <div class="photo-overlay champion-overlay">
                                        <div class="overlay-content">
                                            <a href="/<?php echo $item['images_file']; ?>" 
                                               data-fancybox="athletes-gallery" 
                                               class="photo-view-btn champion-btn">
                                                <i class="fa fa-search-plus"></i>
                                                <span>Увеличить</span>
                                            </a>
                                        </div>
                                        <div class="champion-badge">
                                            <i class="fas fa-medal"></i>
                                        </div>
                                    </div>
                                    <img src="/<?php echo $item['images_file']; ?>" 
                                         alt="<?php echo htmlspecialchars($item['images_text']); ?>" 
                                         class="athlete-image">
                                </div>
                                <div class="athlete-info">
                                    <h4 class="athlete-name"><?php echo htmlspecialchars($item['images_text']); ?></h4>
                                    <div class="achievement-indicator">
                                        <i class="fas fa-star"></i>
                                        <span>Лучший спортсмен</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Статистика достижений -->
            <div class="content-section" data-aos="fade-up" data-aos-delay="400">
                <h2 class="section-title">📊 Наши достижения</h2>
                <div class="achievements-stats">
                    <div class="stat-card gold" data-aos="flip-left" data-aos-delay="500">
                        <div class="stat-icon">🥇</div>
                        <h4>Золотые медали</h4>
                        <p>Множество первых мест в различных соревнованиях</p>
                    </div>
                    <div class="stat-card silver" data-aos="flip-left" data-aos-delay="600">
                        <div class="stat-icon">🥈</div>
                        <h4>Призовые места</h4>
                        <p>Стабильные результаты на областных турнирах</p>
                    </div>
                    <div class="stat-card bronze" data-aos="flip-left" data-aos-delay="700">
                        <div class="stat-icon">🏆</div>
                        <h4>Кубки и награды</h4>
                        <p>Множественные победы в командных соревнованиях</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Виды спорта -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="600">
            <h2 class="section-title">🏃‍♂️ Виды спорта</h2>
            <div class="sports-types">
                <div class="sport-type-card" data-aos="slide-right" data-aos-delay="700">
                    <div class="sport-icon">⚽</div>
                    <h4>Футбол</h4>
                    <p>Сборная команда - чемпионы республиканской спартакиады</p>
                </div>
                <div class="sport-type-card" data-aos="slide-left" data-aos-delay="800">
                    <div class="sport-icon">🏐</div>
                    <h4>Волейбол</h4>
                    <p>Мужская и женская команды с отличными результатами</p>
                </div>
                <div class="sport-type-card" data-aos="slide-right" data-aos-delay="900">
                    <div class="sport-icon">🏀</div>
                    <h4>Баскетбол</h4>
                    <p>Активное участие в межколледжных турнирах</p>
                </div>
                <div class="sport-type-card" data-aos="slide-left" data-aos-delay="1000">
                    <div class="sport-icon">🏓</div>
                    <h4>Настольный теннис</h4>
                    <p>Индивидуальные и командные достижения</p>
                </div>
                <div class="sport-type-card" data-aos="slide-right" data-aos-delay="1100">
                    <div class="sport-icon">🏃‍♀️</div>
                    <h4>Лёгкая атлетика</h4>
                    <p>Личные рекорды студентов в различных дисциплинах</p>
                </div>
                <div class="sport-type-card" data-aos="slide-left" data-aos-delay="1200">
                    <div class="sport-icon">♟️</div>
                    <h4>Шашки и шахматы</h4>
                    <p>Интеллектуальные виды спорта с призовыми местами</p>
                </div>
            </div>
        </div>

        <!-- Мотивация для студентов -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="1000">
            <div class="motivation-banner sports-motivation">
                <h3>🌟 Стань частью спортивной семьи!</h3>
                <p>Наши лучшие спортсмены начинали так же, как и ты. Присоединяйся к спортивным секциям, тренируйся усердно, и возможно, твоё фото тоже появится в этой галерее чемпионов!</p>
                <div class="motivation-stats">
                    <div class="motivate-item">
                        <span class="motivate-number">💪</span>
                        <span class="motivate-text">Сила воли</span>
                    </div>
                    <div class="motivate-item">
                        <span class="motivate-number">🎯</span>
                        <span class="motivate-text">Целеустремлённость</span>
                    </div>
                    <div class="motivate-item">
                        <span class="motivate-number">🏆</span>
                        <span class="motivate-text">Победа</span>
                    </div>
                </div>
                <div class="cta-actions">
                    <a href="/dopage/sportkr" class="cta-btn primary">
                        <i class="fas fa-running"></i> Записаться в секцию
                    </a>
                    <a href="/dopage/bestsport" class="cta-btn secondary">
                        <i class="fas fa-trophy"></i> Наши достижения
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>