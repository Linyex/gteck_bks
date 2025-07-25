<?php 
    $page_title = "Списки зачисленных"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="spiski-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">📋</span>
            Списки зачисленных
        </h1>
        <p class="hero-subtitle">Результаты приемной кампании 2023 года</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="lists-section">
            <div class="section-intro">
                <div class="info-card">
                    <h3>Списки зачисленных на уровень среднего специального образования в 2023 году</h3>
                    <p>в учреждение образования «Гомельский торгово-экономический колледж» Белкоопсоюза на платной основе</p>
                </div>
            </div>

            <div class="education-sections">
                <div class="education-block">
                    <div class="education-header">
                        <h4>На основе общего базового образования (9 классов)</h4>
                    </div>
                    
                    <div class="form-group">
                        <h5 class="form-title">Дневная форма получения образования</h5>
                        <div class="file-item">
                            <div class="file-icon doc"></div>
                            <div class="file-details">
                                <span class="file-name">Списки зачисленных 2023</span>
                                <a href="/assets/dopfiles/Списки зачисленных 2023.docx" 
                                   target="_blank" download class="file-download-btn">
                                    <i class="fas fa-download"></i> Скачать
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="education-block">
                    <div class="education-header">
                        <h4>На основе общего среднего образования (11 классов)</h4>
                    </div>
                    
                    <div class="form-group">
                        <h5 class="form-title">Дневная форма получения образования</h5>
                        <div class="file-item">
                            <div class="file-icon doc"></div>
                            <div class="file-details">
                                <span class="file-name">Списки зачисленных 2023</span>
                                <a href="/assets/dopfiles/Списки зачисленных 11 кл. 2023.docx" 
                                   target="_blank" download class="file-download-btn">
                                    <i class="fas fa-download"></i> Скачать
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h5 class="form-title">Заочная форма получения образования</h5>
                        <div class="file-item">
                            <div class="file-icon doc"></div>
                            <div class="file-details">
                                <span class="file-name">Списки зачисленных 2023</span>
                                <a href="/assets/dopfiles/Списки зачисленных  заочное 2023.docx" 
                                   target="_blank" download class="file-download-btn">
                                    <i class="fas fa-download"></i> Скачать
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="education-block">
                    <div class="education-header">
                        <h4>На основе профессионально-технического образования (ПТО)</h4>
                    </div>
                    
                    <div class="form-group">
                        <h5 class="form-title">Заочная форма получения образования</h5>
                        <div class="file-item">
                            <div class="file-icon doc"></div>
                            <div class="file-details">
                                <span class="file-name">Списки зачисленных 2023</span>
                                <a href="/assets/dopfiles/Списки зачисленных заочное ПТО 2023.docx" 
                                   target="_blank" download class="file-download-btn">
                                    <i class="fas fa-download"></i> Скачать
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>