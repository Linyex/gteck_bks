<!-- Hero Section для графика работы -->
<section class="abut-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">🕒</span>График работы приемной комиссии</h1>
                    <p class="hero-subtitle">Режим работы, контактные телефоны и горячие линии для абитуриентов</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">9-18</span>
                            <span class="stat-label">Часы работы</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">7</span>
                            <span class="stat-label">Дней в неделю</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">2</span>
                            <span class="stat-label">Горячие линии</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Основной контент -->
<section class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <!-- График работы -->
                <div class="schedule-section" data-aos="fade-up">
                    <div class="section-header">
                        <h2>
                            <i class="fa fa-clock-o"></i>
                            График работы приемной комиссии
                        </h2>
                    </div>
                    
                    <div class="schedule-card">
                        <div class="schedule-icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div class="schedule-content">
                            <h3>Режим работы</h3>
                            <div class="time-info">
                                <div class="time-item">
                                    <span class="time-label">Часы работы:</span>
                                    <span class="time-value">09:00 - 18:00</span>
                                </div>
                                <div class="time-item">
                                    <span class="time-label">Рабочие дни:</span>
                                    <span class="time-value">Понедельник - Суббота</span>
                                </div>
                                <div class="time-item">
                                    <span class="time-label">Воскресенье:</span>
                                    <span class="time-value">Выходной</span>
                                </div>
                            </div>
                            <div class="note">
                                <i class="fa fa-info-circle"></i>
                                <span>В период приемной кампании режим работы может быть продлен</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Контактная информация -->
                <div class="contacts-section" data-aos="fade-up" data-aos-delay="200">
                    <div class="section-header">
                        <h2>
                            <i class="fa fa-phone"></i>
                            Контактные телефоны и горячие линии
                        </h2>
                    </div>
                    
                    <div class="contacts-grid">
                        <!-- Белкоопсоюз -->
                        <div class="contact-card belkoop" data-aos="zoom-in" data-aos-delay="300">
                            <div class="contact-header">
                                <div class="contact-icon">
                                    <i class="fa fa-phone-square"></i>
                                </div>
                                <h3>Белкоопсоюз</h3>
                                <p>Горячая линия управления кадровой, идеологической, организационно-кооперативной работы и образования</p>
                            </div>
                            <div class="contact-phones">
                                <div class="phone-item">
                                    <i class="fa fa-phone"></i>
                                    <a href="tel:+375173113839">+375 17 311-38-39</a>
                                </div>
                                <div class="phone-item">
                                    <i class="fa fa-phone"></i>
                                    <a href="tel:+375173113843">+375 17 311-38-43</a>
                                </div>
                            </div>
                            <div class="contact-status">
                                <i class="fa fa-check-circle"></i>
                                <span>Работает постоянно</span>
                            </div>
                        </div>

                        <!-- Министерство образования -->
                        <div class="contact-card ministry" data-aos="zoom-in" data-aos-delay="400">
                            <div class="contact-header">
                                <div class="contact-icon">
                                    <i class="fa fa-graduation-cap"></i>
                                </div>
                                <h3>Министерство образования</h3>
                                <p>Горячая линия Министерства образования Республики Беларусь</p>
                            </div>
                            <div class="contact-phones">
                                <div class="phone-item">
                                    <i class="fa fa-phone"></i>
                                    <a href="tel:+375172224312">+375 17 222-43-12</a>
                                </div>
                            </div>
                            <div class="contact-status limited">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span>Только в период приемной кампании</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Дополнительная информация -->
                <div class="additional-info" data-aos="fade-up" data-aos-delay="500">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <div class="info-content">
                            <h3>Где нас найти?</h3>
                            <p>Приемная комиссия находится в главном корпусе колледжа. Следуйте указателям или обратитесь к охране на входе.</p>
                            <div class="info-buttons">
                                <a href="/dopage/contact" class="info-btn primary">
                                    <i class="fa fa-map"></i>
                                    Контакты и адрес
                                </a>
                                <a href="/abut" class="info-btn secondary">
                                    <i class="fa fa-arrow-left"></i>
                                    К разделам
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
/* Стили для графика работы приемной комиссии */
.schedule-section, .contacts-section {
    margin-bottom: 50px;
}

.section-header {
    text-align: center;
    margin-bottom: 40px;
}

.section-header h2 {
    font-size: 2rem;
    color: #1F2937;
    margin-bottom: 10px;
}

.section-header h2 i {
    color: #8B5CF6;
    margin-right: 15px;
}

/* График работы */
.schedule-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.2);
    display: flex;
    align-items: center;
    gap: 30px;
}

.schedule-icon {
    font-size: 4rem;
    color: #8B5CF6;
    flex-shrink: 0;
}

.schedule-content h3 {
    font-size: 1.5rem;
    color: #1F2937;
    margin-bottom: 20px;
    font-weight: 700;
}

.time-info {
    margin-bottom: 20px;
}

.time-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(139, 92, 246, 0.1);
}

.time-item:last-child {
    border-bottom: none;
}

.time-label {
    font-weight: 600;
    color: #6B7280;
}

.time-value {
    font-weight: 700;
    color: #1F2937;
    background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.note {
    background: rgba(59, 130, 246, 0.1);
    padding: 15px;
    border-radius: 10px;
    border-left: 4px solid #3B82F6;
    display: flex;
    align-items: center;
    gap: 10px;
}

.note i {
    color: #3B82F6;
}

.note span {
    color: #1F2937;
    font-size: 0.95rem;
}

/* Контактная сетка */
.contacts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
}

.contact-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.contact-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.contact-card.belkoop::before {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
}

.contact-card.ministry::before {
    background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.contact-header {
    text-align: center;
    margin-bottom: 25px;
}

.contact-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.contact-card.belkoop .contact-icon {
    color: #10B981;
}

.contact-card.ministry .contact-icon {
    color: #3B82F6;
}

.contact-header h3 {
    font-size: 1.3rem;
    color: #1F2937;
    margin-bottom: 10px;
    font-weight: 700;
}

.contact-header p {
    color: #6B7280;
    font-size: 0.95rem;
    line-height: 1.5;
}

.contact-phones {
    margin-bottom: 20px;
}

.phone-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 15px;
    background: rgba(139, 92, 246, 0.05);
    border-radius: 10px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.phone-item:hover {
    background: rgba(139, 92, 246, 0.1);
    transform: translateX(5px);
}

.phone-item:last-child {
    margin-bottom: 0;
}

.phone-item i {
    color: #8B5CF6;
}

.phone-item a {
    color: #1F2937;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
}

.phone-item a:hover {
    color: #8B5CF6;
    text-decoration: none;
}

.contact-status {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 500;
}

.contact-status:not(.limited) {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.contact-status.limited {
    background: rgba(245, 158, 11, 0.1);
    color: #D97706;
}

.contact-status i {
    font-size: 1rem;
}

/* Дополнительная информация */
.additional-info {
    margin-top: 50px;
}

.info-card {
    background: linear-gradient(135deg, rgba(236, 254, 255, 0.8) 0%, rgba(219, 234, 254, 0.8) 100%);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 20px;
    padding: 40px;
    display: flex;
    align-items: center;
    gap: 30px;
}

.info-icon {
    font-size: 4rem;
    color: #3B82F6;
    flex-shrink: 0;
}

.info-content h3 {
    font-size: 1.5rem;
    color: #1F2937;
    margin-bottom: 15px;
    font-weight: 700;
}

.info-content p {
    color: #6B7280;
    margin-bottom: 25px;
    line-height: 1.6;
}

.info-buttons {
    display: flex;
    gap: 15px;
}

.info-btn {
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.info-btn.primary {
    background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
    color: white;
}

.info-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    color: white;
    text-decoration: none;
}

.info-btn.secondary {
    background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
    color: white;
}

.info-btn.secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(107, 114, 128, 0.3);
    color: white;
    text-decoration: none;
}

/* Адаптивность */
@media (max-width: 768px) {
    .schedule-card, .info-card {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .contacts-grid {
        grid-template-columns: 1fr;
    }
    
    .time-item {
        flex-direction: column;
        gap: 5px;
        text-align: center;
    }
    
    .info-buttons {
        flex-direction: column;
    }
    
    .section-header h2 {
        font-size: 1.5rem;
    }
}
</style>