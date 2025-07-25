<?php 
    $page_title = "Режим работы"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="grafik-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">🕐</span>
            Режим работы
        </h1>
        <p class="hero-subtitle">График работы колледжа</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="schedule-card">
                    <h3 class="card-title">Согласно правилам внутреннего трудового распорядка</h3>
                    
                    <div class="schedule-info">
                        <div class="schedule-header">
                            <h4>Режим работы колледжа</h4>
                        </div>
                        
                        <div class="schedule-details">
                            <div class="schedule-row">
                                <div class="days">Понедельник - Четверг</div>
                                <div class="time">8:00 - 17:00</div>
                            </div>
                            <div class="schedule-row">
                                <div class="days">Пятница</div>
                                <div class="time">8:00 - 16:10</div>
                            </div>
                            <div class="schedule-row break">
                                <div class="days">Обеденный перерыв</div>
                                <div class="time">12:30 - 13:20</div>
                            </div>
                            <div class="schedule-row weekend">
                                <div class="days">Выходные дни</div>
                                <div class="time">Суббота, Воскресенье</div>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <p><strong>Адрес:</strong> ул. Привокзальная, 246017, г. Гомель</p>
                            <p><strong>Телефон:</strong> (8-0232) 33-70-02</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>