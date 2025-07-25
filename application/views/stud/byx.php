<?php 
    $page_title = "Бухгалтерия"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="byx-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">💼</span>
            Бухгалтерия
        </h1>
        <p class="hero-subtitle">Финансовая информация и контакты</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="info-card">
                    <h4 class="card-title">Сотрудники бухгалтерии</h4>
                    <div class="staff-table">
                        <div class="staff-row header">
                            <div class="position">Должность</div>
                            <div class="contact">Кабинет, телефон</div>
                        </div>
                        <div class="staff-row">
                            <div class="position">Главный бухгалтер</div>
                            <div class="contact">каб. 215, тел. 33 70 04</div>
                        </div>
                        <div class="staff-row">
                            <div class="position">Бухгалтер по расчётам с учащимися</div>
                            <div class="contact">каб. 215, тел. 33 70 13</div>
                        </div>
                        <div class="staff-row">
                            <div class="position">Экономист</div>
                            <div class="contact">каб. 215, тел. 33 70 13</div>
                        </div>
                    </div>
                    
                    <div class="schedule-info">
                        <h5>Время работы</h5>
                        <p><strong>ПН-ЧТ:</strong> 8.00-17.00<br>
                        <strong>ПТ:</strong> 8.00-16.10<br>
                        <strong>Обед:</strong> 12.30-13.20<br>
                        <strong>Выходные:</strong> суббота, воскресенье</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="info-card">
                    <h4 class="card-title">Стипендии</h4>
                    <p>Стипендии выплачиваются учащимся, обучающимся по направлениям организаций потребительской кооперации.</p>
                </div>

                <div class="info-card mt-3">
                    <h4 class="card-title">АИС "Расчет"</h4>
                    <div class="payment-links">
                        <a href="https://www.raschet.by/" target="_blank" class="payment-link">
                            <i class="fas fa-external-link-alt"></i> www.RASHET.BY
                        </a>
                        <a href="/assets/dopfiles/оплата в системе Расчет (ЕРИП).pdf" download class="payment-link">
                            <i class="fas fa-download"></i> Оплата в системе "Расчет" (ЕРИП)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="info-card">
                    <h4 class="card-title">Наши реквизиты</h4>
                    <div class="contact-info">
                        <p><strong>Адрес:</strong> Учреждение образования «Гомельский торгово-экономический колледж» Белкоопсоюза</p>
                        <p>ул. Привокзальная, 246017, г. Гомель</p>
                        <p><strong>Телефон/факс:</strong> (8-0232) 33-70-02 (приемная директора)</p>
                        <p><strong>E-mail:</strong> gtec@mail.gomel.by</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="info-card">
                    <h4 class="card-title">Банковские реквизиты</h4>
                    <div class="bank-info">
                        <p><strong>Расчетный счет:</strong> BY80BELB30151300250080226000</p>
                        <p><strong>Банк:</strong> ОАО «Банк БелВЭБ» г. Минск</p>
                        <p><strong>БИК:</strong> BELBBY2X</p>
                        <p><strong>Адрес банка:</strong> пр-т. Ленина, 10, 246050, г. Гомель</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="payment-section">
            <h4 class="section-title">Сроки оплаты</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="payment-card">
                        <h5>Дневная форма обучения</h5>
                        <div class="price-tag">2940.0 бел.руб. в год</div>
                        <div class="payment-schedule">
                            <div class="payment-period">
                                <span class="deadline">до 1 декабря</span>
                                <span class="period">за период январь-март</span>
                            </div>
                            <div class="payment-period">
                                <span class="deadline">до 1 марта</span>
                                <span class="period">за период апрель-июнь</span>
                            </div>
                            <div class="payment-period">
                                <span class="deadline">до 1 июня</span>
                                <span class="period">за период июль-сентябрь</span>
                            </div>
                            <div class="payment-period">
                                <span class="deadline">до 1 сентября</span>
                                <span class="period">за период октябрь-декабрь</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="payment-card">
                        <h5>Заочная форма обучения</h5>
                        <div class="price-tag">1080.0 бел.руб. в год</div>
                        <div class="payment-schedule">
                            <div class="payment-period">
                                <span class="deadline">до 1 декабря</span>
                                <span class="period">за период январь-июнь</span>
                            </div>
                            <div class="payment-period">
                                <span class="deadline">до 1 июня</span>
                                <span class="period">за период июль-декабрь</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>
 