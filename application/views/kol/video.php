<?php 
    $page_title = "Видео"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="video-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">🎬</span>
            Видео
        </h1>
        <p class="hero-subtitle">Видеоматериалы о жизни колледжа</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="video-grid">
            <div class="video-card">
                <div class="video-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="video-info">
                    <h4>Видеоролик о колледже для абитуриентов</h4>
                    <p>Презентационный ролик для будущих учащихся</p>
                    <span class="video-status">В разработке</span>
                </div>
            </div>

            <div class="video-card">
                <div class="video-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="video-info">
                    <h4>Видеоролик о колледже</h4>
                    <p>Общий обзор учебного заведения</p>
                    <span class="video-status">В разработке</span>
                </div>
            </div>

            <div class="video-card">
                <div class="video-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="video-info">
                    <h4>Видеоролик международный форум</h4>
                    <p>Участие в международных мероприятиях</p>
                    <span class="video-status">В разработке</span>
                </div>
            </div>

            <div class="video-card">
                <div class="video-icon">
                    <i class="fas fa-music"></i>
                </div>
                <div class="video-info">
                    <h4>Видеоролик музыканты</h4>
                    <p>Творческая жизнь учащихся</p>
                    <span class="video-status">В разработке</span>
                </div>
            </div>

            <div class="video-card">
                <div class="video-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="video-info">
                    <h4>Видеоролик об образовании</h4>
                    <p>Образовательная деятельность колледжа</p>
                    <span class="video-status">В разработке</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>