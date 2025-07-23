<!-- Hero Section -->
<section class="hero-section" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-12">
                <div class="hero-content">
                    <h1 class="hero-title" data-aos="fade-right" data-aos-delay="200">
                        УВАЖАЕМЫЙ АБИТУРИЕНТ!
                    </h1>
                    <div class="hero-info" data-aos="fade-up" data-aos-delay="400">
                        <div class="info-item">
                            <div class="info-icon">📅</div>
                            <div class="info-content">
                                <p><strong>с 15 июня 2025</strong> начинается прием документов на уровень профессионально-технического образования</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">⏰</div>
                            <div class="info-content">
                                <h4>Сроки приема документов:</h4>
                                <ul>
                                    <li>на основе общего базового образования - <strong>с 15 июня по 23 августа</strong></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">🕘</div>
                            <div class="info-content">
                                <h4>Время работы приёмной комиссии:</h4>
                                <p><strong>с 9.00 до 18.00, понедельник-суббота</strong></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">📞</div>
                            <div class="info-content">
                                <h4>Телефоны приемной комиссии:</h4>
                                <p><strong>+375 232 20-22-14, +375 232 33-70-03</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="hero-image" data-aos="fade-left" data-aos-delay="600">
                    <div class="image-container">
                        <img src="/assets/media/img/graduation-cap.png" alt="Выпускник" class="img-fluid">
                        <div class="image-glow"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    padding: 80px 0;
    margin-bottom: 60px;
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(79, 172, 254, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(240, 147, 251, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.hero-content {
    padding: 40px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 24px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.hero-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.8s ease;
}

.hero-content:hover::before {
    left: 100%;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    color: #dc3545;
    margin-bottom: 40px;
    text-align: center;
    text-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
    position: relative;
}

.hero-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 120px;
    height: 4px;
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    border-radius: 2px;
}

.hero-info {
    color: #495057;
}

.info-item {
    margin-bottom: 30px;
    padding: 20px;
    background: rgba(255,255,255,0.8);
    border-radius: 16px;
    border-left: 5px solid #667eea;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.info-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.05), transparent);
    transition: left 0.6s ease;
}

.info-item:hover::before {
    left: 100%;
}

.info-item:hover {
    transform: translateX(8px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
}

.info-icon {
    font-size: 2rem;
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.info-content {
    flex: 1;
}

.info-item h4 {
    color: #667eea;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.info-item p {
    margin-bottom: 8px;
    line-height: 1.6;
    font-size: 16px;
    color: #495057;
}

.info-item ul {
    margin: 0;
    padding-left: 20px;
}

.info-item li {
    margin-bottom: 8px;
    line-height: 1.6;
    font-size: 16px;
    color: #495057;
}

.hero-image {
    text-align: center;
    padding: 20px;
}

.image-container {
    position: relative;
    display: inline-block;
}

.hero-image img {
    max-width: 350px;
    border-radius: 20px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    z-index: 2;
}

.image-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.2) 0%, transparent 70%);
    transform: translate(-50%, -50%);
    border-radius: 50%;
    z-index: 1;
    animation: pulse 3s ease-in-out infinite;
}

.hero-image img:hover {
    transform: scale(1.05) rotate(2deg);
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
}

@keyframes pulse {
    0%, 100% { 
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
    50% { 
        transform: translate(-50%, -50%) scale(1.1);
        opacity: 0.6;
    }
}

/* Адаптивность */
@media (max-width: 1200px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .info-icon {
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 60px 0;
    }
    
    .hero-content {
        padding: 30px 20px;
        margin-bottom: 30px;
    }
    
    .hero-title {
        font-size: 2rem;
        margin-bottom: 30px;
    }
    
    .info-item {
        padding: 15px;
        margin-bottom: 20px;
        flex-direction: column;
        text-align: center;
    }
    
    .info-icon {
        align-self: center;
        margin-bottom: 10px;
    }
    
    .hero-image img {
        max-width: 280px;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    
    .info-item h4 {
        font-size: 16px;
    }
    
    .info-item p, .info-item li {
        font-size: 14px;
    }
    
    .hero-image img {
        max-width: 250px;
    }
}
</style> 