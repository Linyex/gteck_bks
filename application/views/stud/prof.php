<!-- Hero Section для дополнительного образования -->
<section class="stud-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">🎓</span>Дополнительное образование</h1>
                    <p class="hero-subtitle">Отделение дополнительного образования и повышения квалификации</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">3</span>
                            <span class="stat-label">Программы</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">5</span>
                            <span class="stat-label">Курсов</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Сертификация</span>
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
        <!-- Заведующий отделением -->
        <div class="manager-section" data-aos="fade-up">
            <div class="manager-card">
                <div class="manager-icon">👩‍💼</div>
                <div class="manager-info">
                    <h3>Заведующий отделением</h3>
                    <h4>Яроцкая Наталья Александровна</h4>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fa fa-map-marker"></i>
                            <span>Кабинет 302</span>
                        </div>
                        <div class="contact-item">
                            <i class="fa fa-phone"></i>
                            <span>8 (0232) 29-37-39</span>
                        </div>
                        <div class="contact-item">
                            <i class="fa fa-clock-o"></i>
                            <span>ПН-ЧТ 8:00-17:00, ПТ 8:00-16:10</span>
                        </div>
                        <div class="contact-item">
                            <i class="fa fa-coffee"></i>
                            <span>Обед: 12:30-13:20</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Информация о дополнительном образовании -->
        <div class="info-section" data-aos="fade-up" data-aos-delay="200">
            <div class="info-card">
                <div class="info-header">
                    <div class="info-icon">📚</div>
                    <h3>О дополнительном образовании взрослых</h3>
                </div>
                <div class="info-content">
                    <p>Дополнительное образование взрослых – вид дополнительного образования, направленный на профессиональное развитие слушателя и удовлетворение его познавательных потребностей.</p>
                    <p>Образовательные программы реализуются в очной форме получения образования. Занятия организуются в течение учебного года по мере комплектования групп.</p>
                    <p>По окончании обучения слушателю выдается документ государственного образца согласно Кодексу Республики Беларусь.</p>
                </div>
            </div>
        </div>

        <!-- Образовательные программы -->
        <div class="programs-section" data-aos="fade-up" data-aos-delay="300">
            <h2 class="section-title">📋 Образовательные программы</h2>
            
            <div class="programs-grid">
                <div class="program-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="program-icon">📈</div>
                    <div class="program-content">
                        <h3>Повышение квалификации рабочих (служащих)</h3>
                        <p>Программы для совершенствования профессиональных навыков и компетенций</p>
                    </div>
                </div>
                
                <div class="program-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="program-icon">🔧</div>
                    <div class="program-content">
                        <h3>Профессиональная подготовка рабочих (служащих)</h3>
                        <p>Получение новых профессиональных навыков и квалификации</p>
                    </div>
                </div>
                
                <div class="program-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="program-icon">💡</div>
                    <div class="program-content">
                        <h3>Обучающие курсы</h3>
                        <p>Специализированные курсы по различным направлениям деятельности</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <?php if(!empty($filesa)): ?>
        <div class="additional-info-section" data-aos="fade-up" data-aos-delay="700">
            <h2 class="section-title">📄 Дополнительная информация</h2>
            
            <div class="files-grid">
                <?php foreach($filesa as $item): ?>
                    <?php if ($item['files_ekzamen'] == (int)1): ?>
                        <div class="file-card">
                            <div class="file-icon">📄</div>
                            <div class="file-info">
                                <h4><?php echo htmlspecialchars($item['files_text']) ?></h4>
                                <span class="file-type">Документ</span>
                            </div>
                            <a href="/<?php echo $item['files_file'] ?>" target="_blank" class="view-btn">
                                <i class="fa fa-eye"></i>
                                Просмотр
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Обучающие курсы -->
        <div class="courses-section" data-aos="fade-up" data-aos-delay="800">
            <h2 class="section-title">💻 Обучающие курсы</h2>
            
            <div class="courses-grid">
                <div class="course-card">
                    <div class="course-icon">🌍</div>
                    <div class="course-info">
                        <h3>Курсы английского языка</h3>
                        <p>Изучение английского языка для профессионального развития</p>
                    </div>
                    <a href="/assets/dopfiles/Курсы английского.doc" target="_blank" download class="download-btn">
                        <i class="fa fa-download"></i>
                        Скачать
                    </a>
                </div>
                
                <div class="course-card">
                    <div class="course-icon">💼</div>
                    <div class="course-info">
                        <h3>Бизнес-курсы</h3>
                        <p>Программы для развития предпринимательских навыков</p>
                    </div>
                    <a href="/assets/dopfiles/Бизнес-курсы.doc" target="_blank" download class="download-btn">
                        <i class="fa fa-download"></i>
                        Скачать
                    </a>
                </div>
                
                <div class="course-card">
                    <div class="course-icon">🚀</div>
                    <div class="course-info">
                        <h3>Выездные семинары</h3>
                        <p>Семинары для организаций всех форм собственности</p>
                    </div>
                    <a href="/assets/dopfiles/Выездные семинары.doc" target="_blank" download class="download-btn">
                        <i class="fa fa-download"></i>
                        Скачать
                    </a>
                </div>
            </div>
        </div>

        <!-- Документы -->
        <div class="documents-section" data-aos="fade-up" data-aos-delay="900">
            <h2 class="section-title">📋 Документы</h2>
            
            <div class="documents-grid">
                <div class="document-item">
                    <div class="document-icon">📋</div>
                    <div class="document-info">
                        <h4>Регламент проведения обучения</h4>
                        <span class="document-type">DOC</span>
                    </div>
                    <a href="/assets/dopfiles/Регламент обучения.doc" target="_blank" download class="download-btn">
                        <i class="fa fa-download"></i>
                        Скачать
                    </a>
                </div>
                
                <div class="document-item">
                    <div class="document-icon">📜</div>
                    <div class="document-info">
                        <h4>Договор о платных услугах в сфере образования</h4>
                        <span class="document-type">DOC</span>
                    </div>
                    <a href="/assets/dopfiles/Договор об услугах.doc" target="_blank" download class="download-btn">
                        <i class="fa fa-download"></i>
                        Скачать
                    </a>
                </div>
                
                <div class="document-item">
                    <div class="document-icon">👤</div>
                    <div class="document-info">
                        <h4>Учетная карточка слушателя</h4>
                        <span class="document-type">DOC</span>
                    </div>
                    <a href="/assets/dopfiles/Карточка слушателя.doc" target="_blank" download class="download-btn">
                        <i class="fa fa-download"></i>
                        Скачать
                    </a>
                </div>
            </div>
        </div>

        <!-- Контактная информация -->
        <div class="contact-section" data-aos="fade-up" data-aos-delay="1000">
            <div class="contact-card">
                <div class="contact-icon">📞</div>
                <div class="contact-content">
                    <h3>Записаться на курсы</h3>
                    <p>По всем вопросам дополнительного образования обращайтесь к заведующему отделением</p>
                    <div class="contact-buttons">
                        <a href="tel:80232293739" class="contact-btn">
                            <i class="fa fa-phone"></i>
                            Позвонить
                        </a>
                        <a href="/dopage/contact" class="contact-btn secondary">
                            <i class="fa fa-map-marker"></i>
                            Как добраться
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Дополнительное образование - стили */
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

/* Manager Section */
.manager-section {
    margin-bottom: 40px;
    position: relative;
    z-index: 2;
}

.manager-card {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 20px;
    padding: 30px;
    display: flex;
    align-items: center;
    gap: 25px;
}

.manager-icon {
    font-size: 4rem;
    flex-shrink: 0;
}

.manager-info h3 {
    font-size: 1.25rem;
    color: #6B7280;
    margin-bottom: 5px;
    font-weight: 500;
}

.manager-info h4 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 20px;
}

.contact-info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #6B7280;
}

.contact-item i {
    width: 16px;
    color: #667eea;
}

/* Info Section */
.info-section {
    margin-bottom: 40px;
    position: relative;
    z-index: 2;
}

.info-card {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.info-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.info-header .info-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.info-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    color: white;
}

.info-content {
    padding: 30px;
}

.info-content p {
    color: #6B7280;
    line-height: 1.7;
    margin-bottom: 20px;
}

.info-content p:last-child {
    margin-bottom: 0;
}

/* Section Title */
.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1F2937;
    margin-bottom: 30px;
    position: relative;
    z-index: 2;
}

/* Programs Grid */
.programs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 50px;
}

.program-card {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.program-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.05), transparent);
    transition: left 0.6s ease;
}

.program-card:hover::before {
    left: 100%;
}

.program-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.15);
}

.program-icon {
    font-size: 3rem;
    margin-bottom: 20px;
    display: block;
}

.program-content h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 15px;
    line-height: 1.3;
}

.program-content p {
    color: #6B7280;
    line-height: 1.6;
    margin: 0;
}

/* Files Grid */
.files-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 50px;
}

.file-card {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    padding: 20px 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.file-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
}

.file-icon {
    font-size: 2rem;
    color: #667eea;
    flex-shrink: 0;
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

.view-btn, .download-btn {
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

.view-btn:hover, .download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    text-decoration: none;
    color: white;
}

/* Courses Grid */
.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 50px;
}

.course-card {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.course-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.05), transparent);
    transition: left 0.6s ease;
}

.course-card:hover::before {
    left: 100%;
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
}

.course-icon {
    font-size: 2.5rem;
    flex-shrink: 0;
}

.course-info {
    flex: 1;
}

.course-info h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.course-info p {
    color: #6B7280;
    margin: 0;
    line-height: 1.6;
}

/* Documents Grid */
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 50px;
}

.document-item {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    padding: 20px 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.document-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
}

.document-icon {
    font-size: 2rem;
    color: #667eea;
    flex-shrink: 0;
}

.document-info {
    flex: 1;
}

.document-info h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1F2937;
    margin: 0 0 5px 0;
    line-height: 1.3;
}

.document-type {
    font-size: 0.85rem;
    color: #6B7280;
    background: rgba(102, 126, 234, 0.1);
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 500;
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
    
    .manager-card {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .contact-info {
        grid-template-columns: 1fr;
    }
    
    .programs-grid, .courses-grid, .documents-grid {
        grid-template-columns: 1fr;
    }
    
    .course-card, .document-item, .file-card {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .download-btn, .view-btn {
        align-self: center;
    }
    
    .contact-card {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .contact-buttons {
        flex-direction: column;
    }
    
    .section-title {
        font-size: 2rem;
    }
}
</style>