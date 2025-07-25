<?php 
    $page_title = "О видеонаблюдении"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="videokontr-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">📹</span>
            О видеонаблюдении
        </h1>
        <p class="hero-subtitle">Положение о системе видеонаблюдения в колледже</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="info-card">
            <h3 class="section-title">Положение о видеонаблюдении</h3>
            <p class="section-intro">
                Настоящее Положение устанавливает порядок осуществления видеонаблюдения в колледже и общежитии колледжа, 
                а также прилегающей к ним территории, определяет цели, задачи и способы его осуществления, 
                порядок внедрения, доступа к записям, их хранение и уничтожение.
            </p>
        </div>

        <div class="regulations-section">
            <div class="regulation-item">
                <h4>1.1. Нормативная база</h4>
                <p>
                    Настоящее Положение разработано в соответствии с Положением о применении систем безопасности 
                    и систем видеонаблюдения, утвержденным постановлением Совета Министров Республики Беларусь 
                    от 11.12.2012 №1135, Законом Республики Беларусь от 07.05.2021 №99-З «О защите персональных данных».
                </p>
            </div>

            <div class="regulation-item">
                <h4>1.2. Основные термины и определения</h4>
                <div class="terms-grid">
                    <div class="term-card">
                        <h5>Видеозапись</h5>
                        <p>Зафиксированная на электронном носителе информация, с целью ее хранения и последующего воспроизведения</p>
                    </div>
                    
                    <div class="term-card">
                        <h5>Видеоинформация</h5>
                        <p>Информация в виде изображения, полученная в процессе видеонаблюдения</p>
                    </div>
                    
                    <div class="term-card">
                        <h5>Видеонаблюдение</h5>
                        <p>Процесс получения видеоинформации об объектах и территориях с применением специальных устройств</p>
                    </div>
                    
                    <div class="term-card">
                        <h5>Общественный порядок</h5>
                        <p>Система общественных отношений, определяющая права и обязанности участников</p>
                    </div>
                    
                    <div class="term-card">
                        <h5>Система видеонаблюдения</h5>
                        <p>Составляющая системы безопасности для соблюдения общественного порядка в режиме реального времени</p>
                    </div>
                </div>
            </div>

            <div class="regulation-item important">
                <h4>1.3. Основные принципы</h4>
                <div class="principles-list">
                    <div class="principle-item">
                        <i class="fas fa-eye"></i>
                        <span>Система видеонаблюдения является открытой</span>
                    </div>
                    <div class="principle-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Ведется с целью обеспечения безопасности работников и посетителей</span>
                    </div>
                    <div class="principle-item">
                        <i class="fas fa-user-shield"></i>
                        <span>Не направлена на сбор информации о конкретном человеке</span>
                    </div>
                    <div class="principle-item">
                        <i class="fas fa-info-circle"></i>
                        <span>Доступна для ознакомления на официальном сайте колледжа</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>