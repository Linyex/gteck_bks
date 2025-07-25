<?php echo $header ?>

<style>
    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .contact-header {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
    }
    
    .contact-header h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
        color: #34495e;
    }
    
    .contact-header p {
        font-size: 1.1em;
        color: #7f8c8d;
    }
    
    .contact-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }
    
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    
    .contact-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 25px;
        border-left: 4px solid #3498db;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .contact-card h3 {
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 1.3em;
    }
    
    .contact-info {
        margin-bottom: 15px;
    }
    
    .contact-info i {
        color: #3498db;
        margin-right: 10px;
        width: 20px;
    }
    
    .contact-info a {
        color: #2c3e50;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .contact-info a:hover {
        color: #3498db;
    }
    
    .map-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }
    
    .map-container {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 20px;
    }
    
    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .working-hours {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .hours-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .hours-table th,
    .hours-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ecf0f1;
    }
    
    .hours-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #2c3e50;
    }
    
    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
        
        .contact-header h1 {
            font-size: 2em;
        }
    }
</style>

<div class="contact-container">
    <div class="contact-header">
        <h1>Контакты</h1>
        <p>Свяжитесь с нами любым удобным способом</p>
    </div>
    
    <!-- Основная контактная информация -->
    <div class="contact-section">
        <h2>Контактная информация</h2>
        <div class="contact-grid">
            <div class="contact-card">
                <h3><i class="fa fa-map-marker"></i> Адрес</h3>
                <div class="contact-info">
                    <i class="fa fa-building"></i>
                    <span>УО "Гомельский торгово-экономический колледж" Белкоопсоюза</span>
                </div>
                <div class="contact-info">
                    <i class="fa fa-map-marker"></i>
                    <span>246017 г. Гомель, ул. Пролетарская, 14</span>
                </div>
            </div>
            
            <div class="contact-card">
                <h3><i class="fa fa-phone"></i> Телефоны</h3>
                <div class="contact-info">
                    <i class="fa fa-phone"></i>
                    <a href="tel:+375232500000">+375 (232) 50-00-00</a>
                </div>
                <div class="contact-info">
                    <i class="fa fa-fax"></i>
                    <a href="tel:+375232500001">+375 (232) 50-00-01</a>
                </div>
                <div class="contact-info">
                    <i class="fa fa-phone"></i>
                    <span>Приемная комиссия: +375 (232) 50-00-02</span>
                </div>
            </div>
            
            <div class="contact-card">
                <h3><i class="fa fa-envelope"></i> Электронная почта</h3>
                <div class="contact-info">
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:info@gtec.by">info@gtec.by</a>
                </div>
                <div class="contact-info">
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:priem@gtec.by">priem@gtec.by</a>
                </div>
                <div class="contact-info">
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:director@gtec.by">director@gtec.by</a>
                </div>
            </div>
            
            <div class="contact-card">
                <h3><i class="fa fa-clock-o"></i> Режим работы</h3>
                <div class="contact-info">
                    <i class="fa fa-clock-o"></i>
                    <span>Понедельник - Пятница: 8:00 - 17:00</span>
                </div>
                <div class="contact-info">
                    <i class="fa fa-clock-o"></i>
                    <span>Суббота: 8:00 - 13:00</span>
                </div>
                <div class="contact-info">
                    <i class="fa fa-clock-o"></i>
                    <span>Воскресенье: выходной</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Карта -->
    <div class="map-section">
        <h2>Как нас найти</h2>
        <p>Мы находимся в центре города Гомель, рядом с остановкой общественного транспорта.</p>
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2350.1234567890123!2d30.98765432109876!3d52.12345678901234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTLCsDA3JzM0LjQiTiAzMMKwNTknMTUuNiJF!5e0!3m2!1sru!2sby!4v1234567890123" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
    
    <!-- Часы работы -->
    <div class="working-hours">
        <h2>Часы работы приемной комиссии</h2>
        <table class="hours-table">
            <thead>
                <tr>
                    <th>День недели</th>
                    <th>Время работы</th>
                    <th>Перерыв</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Понедельник</td>
                    <td>8:00 - 17:00</td>
                    <td>13:00 - 14:00</td>
                </tr>
                <tr>
                    <td>Вторник</td>
                    <td>8:00 - 17:00</td>
                    <td>13:00 - 14:00</td>
                </tr>
                <tr>
                    <td>Среда</td>
                    <td>8:00 - 17:00</td>
                    <td>13:00 - 14:00</td>
                </tr>
                <tr>
                    <td>Четверг</td>
                    <td>8:00 - 17:00</td>
                    <td>13:00 - 14:00</td>
                </tr>
                <tr>
                    <td>Пятница</td>
                    <td>8:00 - 17:00</td>
                    <td>13:00 - 14:00</td>
                </tr>
                <tr>
                    <td>Суббота</td>
                    <td>8:00 - 13:00</td>
                    <td>Без перерыва</td>
                </tr>
                <tr>
                    <td>Воскресенье</td>
                    <td>Выходной</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php echo $footer; ?> 