<?php 
    $page_title = "Учебные материалы"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="ychm-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">📚</span>
            Учебные материалы
        </h1>
        <p class="hero-subtitle">Методические рекомендации и презентации</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="files-section">
            <div class="files-grid">
                <div class="file-card">
                    <div class="file-icon doc"></div>
                    <div class="file-info">
                        <h5>Методические рекомендации</h5>
                        <p>Общие методические материалы для обучения</p>
                        <a href="/assets/dopfiles/Метод рекомендации.doc" class="file-download" target="_blank" download>
                            <i class="fas fa-download"></i> Скачать DOC
                        </a>
                    </div>
                </div>

                <div class="file-card">
                    <div class="file-icon archive"></div>
                    <div class="file-info">
                        <h5>Электробезопасность</h5>
                        <p>Презентация по основам электробезопасности</p>
                        <a href="/assets/prezentacii/Электро.rar" class="file-download" target="_blank" download>
                            <i class="fas fa-download"></i> Скачать RAR
                        </a>
                    </div>
                </div>

                <div class="file-card">
                    <div class="file-icon archive"></div>
                    <div class="file-info">
                        <h5>Структура программы на С</h5>
                        <p>Презентация по программированию на языке C</p>
                        <a href="/assets/prezentacii/Структура.rar" class="file-download" target="_blank" download>
                            <i class="fas fa-download"></i> Скачать RAR
                        </a>
                    </div>
                </div>

                <div class="file-card">
                    <div class="file-icon archive"></div>
                    <div class="file-info">
                        <h5>Создание форм</h5>
                        <p>Презентация по созданию пользовательских форм</p>
                        <a href="/assets/prezentacii/Формы.rar" class="file-download" target="_blank" download>
                            <i class="fas fa-download"></i> Скачать RAR
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>
