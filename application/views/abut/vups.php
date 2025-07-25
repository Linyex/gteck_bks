<?php 
    $page_title = "Наши выпускники"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="vups-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">🎓</span>
            Наши выпускники
        </h1>
        <p class="hero-subtitle">Выдающиеся личности - гордость колледжа</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="graduates-grid">
            <div class="graduate-card">
                <div class="graduate-header">
                    <h3 class="graduate-name">Терех Кондрат Зигмундович</h3>
                    <a href="stories/abit/114.doc" class="biography-link" target="_blank">
                        <i class="fas fa-file-alt"></i> Биография
                    </a>
                </div>
                <div class="graduate-achievements">
                    <div class="achievement-item">
                        <span class="achievement-period">1977-1984</span>
                        <span class="achievement-text">Председатель правления Белкоопсоюза</span>
                    </div>
                    <div class="achievement-item">
                        <span class="achievement-period">1984-1986</span>
                        <span class="achievement-text">Заместитель председателя Совета Министров Республики Беларусь</span>
                    </div>
                    <div class="achievement-item">
                        <span class="achievement-period">1986-1991</span>
                        <span class="achievement-text">Министр торговли СССР</span>
                    </div>
                </div>
            </div>

            <div class="graduate-card">
                <div class="graduate-header">
                    <h3 class="graduate-name">Котченко Федор Петрович</h3>
                    <a href="stories/abit/654.doc" class="biography-link" target="_blank">
                        <i class="fas fa-file-alt"></i> Биография
                    </a>
                </div>
                <div class="graduate-achievements">
                    <div class="achievement-item special">
                        <span class="hero-badge">Герой Советского Союза</span>
                    </div>
                </div>
            </div>

            <div class="graduate-card">
                <div class="graduate-header">
                    <h3 class="graduate-name">Горчанюк Роман Васильевич</h3>
                </div>
                <div class="graduate-achievements">
                    <div class="achievement-item">
                        <span class="achievement-period">1997-2006</span>
                        <span class="achievement-text">Председатель правления Брестского облпотребсоюза</span>
                    </div>
                </div>
            </div>

            <div class="graduate-card">
                <div class="graduate-header">
                    <h3 class="graduate-name">Кастырина Мария Васильевна</h3>
                </div>
                <div class="graduate-achievements">
                    <div class="achievement-item">
                        <span class="achievement-period">1994-1997</span>
                        <span class="achievement-text">Зам. председателя Правления Белкоопсоюза</span>
                    </div>
                    <div class="achievement-item">
                        <span class="achievement-period">1997-2001</span>
                        <span class="achievement-text">Консультант Правления Белкоопсоюза</span>
                    </div>
                </div>
            </div>

            <div class="graduate-card">
                <div class="graduate-header">
                    <h3 class="graduate-name">Петрошевич Ирина Николаевна</h3>
                </div>
                <div class="graduate-achievements">
                    <div class="achievement-item">
                        <span class="achievement-period">2003-2011</span>
                        <span class="achievement-text">Начальник финансового управления Белкоопсоюза</span>
                    </div>
                </div>
            </div>

            <div class="graduate-card">
                <div class="graduate-header">
                    <h3 class="graduate-name">Ледовская Раиса Леонидовна</h3>
                </div>
                <div class="graduate-achievements">
                    <div class="achievement-item">
                        <span class="achievement-period">2011-2018</span>
                        <span class="achievement-text">Начальник финансового управления Белкоопсоюза</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>
