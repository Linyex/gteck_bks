<?php 
    $page_title = "Сроки приема документов"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="sroki-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">📅</span>
            Сроки приема документов
        </h1>
        <p class="hero-subtitle">Важные даты для поступающих</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="admission-section">
            <div class="section-header">
                <h3 class="section-title">Среднее специальное образование - 2025</h3>
            </div>
            
            <div class="admission-cards">
                <div class="admission-card">
                    <div class="education-level">
                        <h4>9 классов</h4>
                        <p>Дневная форма обучения</p>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-label">Прием документов</div>
                            <div class="timeline-dates">18 июля - 12 августа</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-label">Зачисление</div>
                            <div class="timeline-dates">до 15 августа</div>
                        </div>
                    </div>
                </div>

                <div class="admission-card">
                    <div class="education-level">
                        <h4>11 классов</h4>
                        <p>Дневная и заочная форма обучения</p>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-label">Прием документов</div>
                            <div class="timeline-dates">18 июля - 14 августа</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-label">Зачисление</div>
                            <div class="timeline-dates">до 17 августа</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admission-section">
            <div class="section-header">
                <h3 class="section-title">Профессионально-техническое образование - 2025</h3>
            </div>
            
            <div class="admission-cards">
                <div class="admission-card full-width">
                    <div class="education-level">
                        <h4>9 классов</h4>
                        <p>Дневная форма обучения</p>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-label">Прием документов</div>
                            <div class="timeline-dates">15 июня - 23 августа</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-label">Зачисление</div>
                            <div class="timeline-dates">до 26 августа</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="documents-section">
            <div class="document-card">
                <div class="document-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="document-info">
                    <h4>Порядок приёма абитуриентов 2025</h4>
                    <p>Официальный документ с подробными требованиями</p>
                    <a href="/assets/files/№ 88 от 27.03.2025 Об утверждении Порядка приема на 2025 год.pdf" 
                       target="_blank" class="document-download">
                        <i class="fas fa-download"></i> Скачать PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>

