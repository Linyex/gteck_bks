<!-- Hero Section для FAQ -->
<section class="stud-hero faq-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">❓</span>Часто задаваемые вопросы</h1>
                    <p class="hero-subtitle">Ответы на самые популярные вопросы о поступлении и обучении</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">4</span>
                            <span class="stat-label">Популярных вопроса</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Честных ответов</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Доступность</span>
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
        
        <!-- FAQ секция -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="200">
            <h2 class="section-title">💡 Самые частые вопросы</h2>
            <div class="faq-container">
                
                <div class="faq-item" data-aos="slide-up" data-aos-delay="300">
                    <div class="faq-question">
                        <div class="faq-icon">📝</div>
                        <h4>Необходимы ли результаты централизованного тестирования при поступлении в колледж?</h4>
                        <div class="faq-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <p><strong>Нет.</strong> Зачисление в колледж производится по конкурсу среднего балла аттестата об общем базовом или общем среднем образовании.</p>
                    </div>
                </div>

                <div class="faq-item" data-aos="slide-up" data-aos-delay="400">
                    <div class="faq-question">
                        <div class="faq-icon">🎓</div>
                        <h4>Можно ли после колледжа поступить в учреждение высшего образования?</h4>
                        <div class="faq-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <p><strong>Да.</strong> Поступление в учреждение высшего образования для выпускников колледжей осуществляется без централизованного тестирования, а по результатам профильных испытаний по дисциплинам учебного плана среднего специального образования, форма проведения которых определяется учреждением высшего образования.</p>
                    </div>
                </div>

                <div class="faq-item" data-aos="slide-up" data-aos-delay="500">
                    <div class="faq-question">
                        <div class="faq-icon">🏠</div>
                        <h4>Имеется ли в колледже общежитие?</h4>
                        <div class="faq-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <p><strong>Да.</strong> Местом для проживания в общежитии обеспечиваются все нуждающиеся по их заявлению.</p>
                    </div>
                </div>

                <div class="faq-item" data-aos="slide-up" data-aos-delay="600">
                    <div class="faq-question">
                        <div class="faq-icon">💰</div>
                        <h4>Есть ли бюджетные места в колледже?</h4>
                        <div class="faq-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <p><strong>Нет.</strong> Обучение в колледже платное. Оплату за обучение могут производить физические лица, организации потребительской кооперации, другие юридические лица.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Дополнительная помощь -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="700">
            <h2 class="section-title">🆘 Нужна дополнительная помощь?</h2>
            <div class="help-section">
                <div class="help-card" data-aos="zoom-in" data-aos-delay="800">
                    <div class="help-icon">📞</div>
                    <h4>Свяжитесь с нами</h4>
                    <p>Если ваш вопрос не нашёл ответа, обращайтесь по телефону приемной комиссии</p>
                    <a href="/dopage/contact" class="help-link">
                        <i class="fas fa-arrow-right"></i> Контакты
                    </a>
                </div>
                <div class="help-card" data-aos="zoom-in" data-aos-delay="900">
                    <div class="help-icon">📧</div>
                    <h4>Электронное обращение</h4>
                    <p>Подайте официальное обращение через электронную систему</p>
                    <a href="/message" class="help-link">
                        <i class="fas fa-arrow-right"></i> Обращения
                    </a>
                </div>
                <div class="help-card" data-aos="zoom-in" data-aos-delay="1000">
                    <div class="help-icon">🏢</div>
                    <h4>Личное посещение</h4>
                    <p>Посетите приемную комиссию для личной консультации</p>
                    <a href="/okno/info" class="help-link">
                        <i class="fas fa-arrow-right"></i> Одно окно
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// FAQ аккордеон
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const toggle = item.querySelector('.faq-toggle i');
        
        question.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            
            // Закрываем все остальные
            faqItems.forEach(otherItem => {
                otherItem.classList.remove('active');
                otherItem.querySelector('.faq-toggle i').style.transform = 'rotate(0deg)';
            });
            
            // Переключаем текущий
            if (!isActive) {
                item.classList.add('active');
                toggle.style.transform = 'rotate(180deg)';
            }
        });
    });
});
</script>