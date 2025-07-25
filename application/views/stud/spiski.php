<!-- Hero Section для списков групп -->
<section class="stud-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">📋</span>Списки групп</h1>
                    <p class="hero-subtitle">Актуальные списки учебных групп по всем отделениям и формам обучения</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">2</span>
                            <span class="stat-label">Отделения</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">4</span>
                            <span class="stat-label">Категории</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Актуальность</span>
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
        <!-- Информационный блок -->
        <div class="info-block" data-aos="fade-up">
            <div class="info-icon">📄</div>
            <div class="info-content">
                <h3>Информация о списках</h3>
                <p>Здесь представлены актуальные списки учебных групп, разделенные по отделениям и уровням образования. Все файлы доступны для скачивания.</p>
            </div>
        </div>

        <!-- Дневное отделение -->
        <div class="department-section" data-aos="fade-up" data-aos-delay="200">
            <div class="department-header">
                <div class="department-icon">🌅</div>
                <h2>Дневное отделение</h2>
                <p>Очная форма получения образования</p>
            </div>

            <!-- На основе общего базового образования -->
            <div class="category-card" data-aos="fade-up" data-aos-delay="300">
                <div class="category-header">
                    <div class="category-icon">🎓</div>
                    <div class="category-info">
                        <h3>На основе общего базового образования</h3>
                        <p>Списки групп для поступивших после 9 класса</p>
                    </div>
                </div>
                <div class="files-list">
                    <?php $hasFiles1 = false; ?>
                    <?php foreach($listgr as $item): ?>
                        <?php if ($item['listgr_status'] == (int)1): ?>
                            <?php $hasFiles1 = true; ?>
                            <div class="file-item">
                                <div class="file-icon">📄</div>
                                <div class="file-info">
                                    <h4><?php echo htmlspecialchars($item['listgr_text']) ?></h4>
                                    <span class="file-type">PDF / DOCX</span>
                                </div>
                                <a href="/<?php echo $item['listgr_file'] ?>" target="_blank" download class="download-btn">
                                    <i class="fa fa-download"></i>
                                    Скачать
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if(!$hasFiles1): ?>
                        <div class="no-files">
                            <div class="no-files-icon">📭</div>
                            <p>На данный момент нет списков</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- На основе общего среднего образования -->
            <div class="category-card" data-aos="fade-up" data-aos-delay="400">
                <div class="category-header">
                    <div class="category-icon">🎯</div>
                    <div class="category-info">
                        <h3>На основе общего среднего образования</h3>
                        <p>Списки групп для поступивших после 11 класса</p>
                    </div>
                </div>
                <div class="files-list">
                    <?php $hasFiles2 = false; ?>
                    <?php foreach($listgr as $item): ?>
                        <?php if ($item['listgr_status'] == (int)2): ?>
                            <?php $hasFiles2 = true; ?>
                            <div class="file-item">
                                <div class="file-icon">📄</div>
                                <div class="file-info">
                                    <h4><?php echo htmlspecialchars($item['listgr_text']) ?></h4>
                                    <span class="file-type">PDF / DOCX</span>
                                </div>
                                <a href="/<?php echo $item['listgr_file'] ?>" target="_blank" download class="download-btn">
                                    <i class="fa fa-download"></i>
                                    Скачать
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if(!$hasFiles2): ?>
                        <div class="no-files">
                            <div class="no-files-icon">📭</div>
                            <p>На данный момент нет списков</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Заочное отделение -->
        <div class="department-section" data-aos="fade-up" data-aos-delay="500">
            <div class="department-header">
                <div class="department-icon">🌙</div>
                <h2>Заочное отделение</h2>
                <p>Заочная форма получения образования</p>
            </div>

            <!-- На основе общего среднего образования -->
            <div class="category-card" data-aos="fade-up" data-aos-delay="600">
                <div class="category-header">
                    <div class="category-icon">🎯</div>
                    <div class="category-info">
                        <h3>На основе общего среднего образования</h3>
                        <p>Списки групп заочной формы обучения после 11 класса</p>
                    </div>
                </div>
                <div class="files-list">
                    <?php $hasFiles3 = false; ?>
                    <?php foreach($listgr as $item): ?>
                        <?php if ($item['listgr_status'] == (int)3): ?>
                            <?php $hasFiles3 = true; ?>
                            <div class="file-item">
                                <div class="file-icon">📄</div>
                                <div class="file-info">
                                    <h4><?php echo htmlspecialchars($item['listgr_text']) ?></h4>
                                    <span class="file-type">PDF / DOCX</span>
                                </div>
                                <a href="/<?php echo $item['listgr_file'] ?>" target="_blank" download class="download-btn">
                                    <i class="fa fa-download"></i>
                                    Скачать
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if(!$hasFiles3): ?>
                        <div class="no-files">
                            <div class="no-files-icon">📭</div>
                            <p>На данный момент нет списков</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- На основе ПТО -->
            <div class="category-card" data-aos="fade-up" data-aos-delay="700">
                <div class="category-header">
                    <div class="category-icon">🔧</div>
                    <div class="category-info">
                        <h3>На основе профессионально-технического образования</h3>
                        <p>Списки групп заочной формы обучения на базе ПТО</p>
                    </div>
                </div>
                <div class="files-list">
                    <?php $hasFiles4 = false; ?>
                    <?php foreach($listgr as $item): ?>
                        <?php if ($item['listgr_status'] == (int)4): ?>
                            <?php $hasFiles4 = true; ?>
                            <div class="file-item">
                                <div class="file-icon">📄</div>
                                <div class="file-info">
                                    <h4><?php echo htmlspecialchars($item['listgr_text']) ?></h4>
                                    <span class="file-type">PDF / DOCX</span>
                                </div>
                                <a href="/<?php echo $item['listgr_file'] ?>" target="_blank" download class="download-btn">
                                    <i class="fa fa-download"></i>
                                    Скачать
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if(!$hasFiles4): ?>
                        <div class="no-files">
                            <div class="no-files-icon">📭</div>
                            <p>На данный момент нет списков</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Контактная информация -->
        <div class="contact-section" data-aos="fade-up" data-aos-delay="800">
            <div class="contact-card">
                <div class="contact-icon">📞</div>
                <div class="contact-content">
                    <h3>Нужна помощь?</h3>
                    <p>По вопросам списков групп и зачисления обращайтесь в приемную комиссию или учебную часть</p>
                    <div class="contact-buttons">
                        <a href="/dopage/contact" class="contact-btn">
                            <i class="fa fa-phone"></i>
                            Приемная комиссия
                        </a>
                        <a href="/stud" class="contact-btn secondary">
                            <i class="fa fa-arrow-left"></i>
                            К разделам
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Списки групп - стили */
.c-layout-page {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
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
        radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.08) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.08) 0%, transparent 50%);
    pointer-events: none;
    z-index: 1;
}

/* Информационный блок */
.info-block {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 40px;
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    z-index: 2;
}

.info-block .info-icon {
    font-size: 3rem;
    flex-shrink: 0;
}

.info-block .info-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 10px;
}

.info-block .info-content p {
    color: #6B7280;
    margin: 0;
    line-height: 1.6;
}

/* Department Sections */
.department-section {
    background: rgba(255,255,255,0.9);
    border-radius: 20px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    margin-bottom: 40px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    position: relative;
    z-index: 2;
}

.department-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.department-icon {
    font-size: 3rem;
    flex-shrink: 0;
}

.department-header h2 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: white;
}

.department-header p {
    color: rgba(255,255,255,0.9);
    margin: 0;
    font-size: 1.1rem;
}

/* Category Cards */
.category-card {
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
}

.category-card:last-child {
    border-bottom: none;
}

.category-header {
    background: rgba(102, 126, 234, 0.05);
    padding: 25px 30px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.category-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.category-info h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 5px 0;
}

.category-info p {
    color: #6B7280;
    margin: 0;
    font-size: 0.95rem;
}

/* Files List */
.files-list {
    padding: 30px;
}

.file-item {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    padding: 20px 25px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.file-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.05), transparent);
    transition: left 0.6s ease;
}

.file-item:hover::before {
    left: 100%;
}

.file-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
}

.file-item:last-child {
    margin-bottom: 0;
}

.file-icon {
    font-size: 2rem;
    flex-shrink: 0;
    color: #667eea;
}

.file-info {
    flex: 1;
}

.file-info h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1F2937;
    margin: 0 0 5px 0;
    line-height: 1.3;
}

.file-type {
    font-size: 0.85rem;
    color: #6B7280;
    background: rgba(102, 126, 234, 0.1);
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 500;
}

.download-btn {
    background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    text-decoration: none;
    color: white;
}

/* No Files State */
.no-files {
    text-align: center;
    padding: 40px 20px;
    color: #6B7280;
}

.no-files-icon {
    font-size: 4rem;
    margin-bottom: 15px;
    opacity: 0.6;
}

.no-files p {
    font-size: 1.1rem;
    margin: 0;
    font-style: italic;
}

/* Contact Section */
.contact-section {
    margin-top: 50px;
}

.contact-card {
    background: linear-gradient(135deg, rgba(236, 254, 255, 0.8) 0%, rgba(219, 234, 254, 0.8) 100%);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 20px;
    padding: 40px;
    display: flex;
    align-items: center;
    gap: 25px;
    text-align: center;
}

.contact-icon {
    font-size: 4rem;
    flex-shrink: 0;
}

.contact-content h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 12px;
}

.contact-content p {
    color: #6B7280;
    margin-bottom: 25px;
    line-height: 1.6;
}

.contact-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.contact-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.contact-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    text-decoration: none;
    color: white;
}

.contact-btn.secondary {
    background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
}

.contact-btn.secondary:hover {
    color: white;
    box-shadow: 0 8px 24px rgba(107, 114, 128, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .c-layout-page {
        margin: 12px;
        padding: 20px;
    }
    
    .department-header {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .category-header {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .file-item {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
        text-align: center;
    }
    
    .download-btn {
        align-self: center;
    }
    
    .info-block, .contact-card {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .contact-buttons {
        flex-direction: column;
    }
}
</style>  