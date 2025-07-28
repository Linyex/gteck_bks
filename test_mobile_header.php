<?php
// Тестовая страница для проверки мобильной адаптации
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title>Тест мобильной шапки</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    
    <!-- CSS переменные -->
    <style>
        :root {
            --primary-color: #8B5CF6;
            --primary-dark: #7C3AED;
            --secondary-color: #10B981;
            --text-dark: #374151;
            --text-muted: #6B7280;
            --text-light: #9CA3AF;
        }
    </style>
    
    <!-- Подключаем стили -->
    <link href="/assets/css/header.css?v=1.2.0" rel="stylesheet" type="text/css" />
    <link href="/assets/css/mobile-header.css?v=1.0.0" rel="stylesheet" type="text/css" />
    <link href="/assets/css/font-awesome/css/font-awesome.css?v=1.2.0" rel="stylesheet" type="text/css" />
</head>
<body>
    
    <!-- HEADER -->
    <div class="c-layout-header" id="header">
        <header class="header-container">
            <!-- Левая часть - Логотип и название -->
            <div class="brand-section">
                <div class="logo-container">
                    <a href="/" class="logo-link">
                        <svg class="logo-svg" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="8" fill="url(#logoGradient)"/>
                            <path d="M8 12h24v4H20v12h-4V16H8v-4z" fill="white"/>
                            <defs>
                                <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#8B5CF6"/>
                                    <stop offset="100%" style="stop-color:#3B82F6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </a>
                </div>
                <div class="brand-text">
                    <div class="brand-name">ГТЭК</div>
                    <div class="brand-subtitle">Гомельский торгово-экономический<br>колледж Белкоопсоюза</div>
                </div>
            </div>

            <!-- Центральная навигация -->
            <div class="navigation-section">
                <ul class="main-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Главная</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/kol" style="cursor: pointer;">О колледже</a>
                        <ul class="dropdown-menu">
                            <li><a href="/kol/grafik">Режим работы колледжа</a></li>
                            <li><a href="/kol/history">История</a></li>
                            <li><a href="/kol/contact">Контакты</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/news">Новости</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dopage/faq">FAQ</a>
                    </li>
                </ul>
            </div>

            <!-- Правая часть: кнопка для слабовидящих -->
            <div class="actions-section">
                <a href="#" class="accessibility-btn" id="accessibility-toggle">
                    <i class="fa fa-eye"></i>
                    <span class="accessibility-text">для слабовидящих</span>
                </a>
                
                <!-- Мобильная кнопка меню -->
                <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Открыть меню">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </header>
    </div>

    <!-- Мобильная навигация -->
    <div class="mobile-nav" id="mobile-nav">
        <div class="mobile-nav-header">
            <div class="mobile-nav-brand">
                <div class="brand-name">ГТЭК</div>
            </div>
            <button class="mobile-nav-close" id="mobile-nav-close" aria-label="Закрыть меню">
                <i class="fa fa-times"></i>
            </button>
        </div>
        
        <div class="mobile-nav-content">
            <ul class="mobile-nav-list">
                <li class="mobile-nav-item">
                    <a href="/" class="mobile-nav-link">Главная</a>
                </li>
                
                <li class="mobile-nav-item">
                    <button class="mobile-nav-link mobile-dropdown-toggle">
                        О колледже
                    </button>
                    <ul class="mobile-dropdown">
                        <li class="mobile-dropdown-item">
                            <a href="/kol/grafik" class="mobile-dropdown-link">Режим работы колледжа</a>
                        </li>
                        <li class="mobile-dropdown-item">
                            <a href="/kol/history" class="mobile-dropdown-link">История</a>
                        </li>
                        <li class="mobile-dropdown-item">
                            <a href="/kol/contact" class="mobile-dropdown-link">Контакты</a>
                        </li>
                    </ul>
                </li>
                
                <li class="mobile-nav-item">
                    <a href="/news" class="mobile-nav-link">Новости</a>
                </li>
                
                <li class="mobile-nav-item">
                    <a href="/dopage/faq" class="mobile-nav-link">FAQ</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Оверлей для закрытия мобильного меню -->
    <div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>

    <!-- Тестовый контент -->
    <div style="padding: 20px; margin-top: 100px;">
        <h1>Тест мобильной адаптации шапки</h1>
        <p>Уменьшите окно браузера до 1024px или меньше, чтобы увидеть гамбургер-меню.</p>
        <p>Текущая ширина экрана: <span id="screen-width"></span>px</p>
        <p>Гамбургер-кнопка видна: <span id="hamburger-visible"></span></p>
    </div>

    <!-- JavaScript -->
    <script src="/assets/js/mobile-header-debug.js?v=1.0.0"></script>
    <script>
        // Показываем информацию о размере экрана
        function updateScreenInfo() {
            const width = window.innerWidth;
            const hamburger = document.querySelector('.mobile-menu-toggle');
            const isVisible = hamburger.style.display !== 'none' && hamburger.offsetParent !== null;
            
            document.getElementById('screen-width').textContent = width;
            document.getElementById('hamburger-visible').textContent = isVisible ? 'ДА' : 'НЕТ';
        }
        
        window.addEventListener('load', updateScreenInfo);
        window.addEventListener('resize', updateScreenInfo);
        
        // Добавляем кнопку для тестирования
        setTimeout(() => {
            const testDiv = document.createElement('div');
            testDiv.innerHTML = `
                <button onclick="testMobileMenu()" style="margin: 10px; padding: 10px; background: #8B5CF6; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    🧪 Тест мобильного меню
                </button>
            `;
            document.body.appendChild(testDiv);
        }, 1000);
    </script>
</body>
</html> 