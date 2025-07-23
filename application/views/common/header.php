<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?>Гомельский торгово-экономический колледж</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="gtec-bks.by" name="url" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Preload критических ресурсов -->
    <link rel="preload" href="/assets/css/optimized.css" as="style">
    <link rel="preload" href="/assets/js/main-page.js" as="script">
    <link rel="preload" href="/assets/font-manrope/Manrope-VariableFont_wght.ttf" as="font" type="font/ttf" crossorigin>
    
    <!-- Критические стили -->
    <link href="/assets/css/optimized.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    
    <!-- Остальные стили -->
    <link href="/assets/css/bootstrap.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/font-awesome/css/font-awesome.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/main-styles.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/header.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/footer.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/main-page.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/banners-soft.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.png" />
    
    <!-- Оптимизированные скрипты -->
    <script src="/assets/js/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="/assets/js/modern-components.js?v=<?php echo time(); ?>"></script>
</head>
<body>
    <!-- Анимированные элементы фона - Образовательная тематика -->
    <div class="tech-element"></div>
    <div class="tech-element"></div>
    <div class="tech-element"></div>
    <div class="tech-element"></div>
    <div class="tech-element"></div>
    
    <!-- Образовательные символы -->
    <div class="education-symbol" style="content: '🎓';">🎓</div>
    <div class="education-symbol" style="content: '📚';">📚</div>
    <div class="education-symbol" style="content: '🔬';">🔬</div>
    <div class="education-symbol" style="content: '⚡';">⚡</div>
    <div class="education-symbol" style="content: '💡';">💡</div>
    <div class="education-symbol" style="content: '🎯';">🎯</div>
    
    <!-- Научные молекулы -->
    <div class="science-molecule"></div>
    <div class="science-molecule"></div>
    <div class="science-molecule"></div>
    
    <!-- Атомные орбиты -->
    <div class="atomic-orbit"></div>
    <div class="atomic-orbit"></div>
    <div class="atomic-orbit"></div>
    
    <!-- Книжные страницы -->
    <div class="book-page"></div>
    <div class="book-page"></div>
    <div class="book-page"></div>
    
    <!-- Лабораторные пробирки -->
    <div class="lab-tube"></div>
    <div class="lab-tube"></div>
    <div class="lab-tube"></div>
    
    <!-- Микроскопы -->
    <div class="microscope"></div>
    <div class="microscope"></div>

    <!-- Header -->
    <header class="c-layout-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Логотип и название -->
                <div class="navbar-brand-container">
                    <a class="navbar-brand" href="/">
                        <div class="logo-container">
                            <img src="/assets/media/logo.png" alt="ГТЭК" class="logo" onerror="this.style.display='none'">
                            <div class="logo-fallback">🎓</div>
                        </div>
                        <div class="brand-text">
                            <div class="brand-title">ГТЭК</div>
                            <div class="brand-subtitle">Учреждение образования "Гомельский торгово-экономический колледж"</div>
                        </div>
                    </a>
                </div>

                <!-- Мобильная кнопка -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Навигация -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/" role="button" data-bs-toggle="dropdown">
                                Главная
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/">Главная страница</a></li>
                                <li><a class="dropdown-item" href="/news">Новости</a></li>
                                <li><a class="dropdown-item" href="/abut">Абитуриентам</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/news" role="button" data-bs-toggle="dropdown">
                                Новости
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/news">Все новости</a></li>
                                <li><a class="dropdown-item" href="/news/category/events">События</a></li>
                                <li><a class="dropdown-item" href="/news/category/announcements">Объявления</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/abut" role="button" data-bs-toggle="dropdown">
                                Абитуриенту
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/abut">О колледже</a></li>
                                <li><a class="dropdown-item" href="/abut/spec">Специальности</a></li>
                                <li><a class="dropdown-item" href="/abut/vups">ВУПС</a></li>
                                <li><a class="dropdown-item" href="/abut/plan">План приема</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/stud" role="button" data-bs-toggle="dropdown">
                                Учащемуся
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/stud">Студенческий раздел</a></li>
                                <li><a class="dropdown-item" href="/stud/kontrolnui">Контрольные работы</a></li>
                                <li><a class="dropdown-item" href="/stud/library">Библиотека</a></li>
                                <li><a class="dropdown-item" href="/stud/hostel">Общежитие</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/prepod" role="button" data-bs-toggle="dropdown">
                                Преподавателю
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/prepod">Преподавательский раздел</a></li>
                                <li><a class="dropdown-item" href="/prepod/metod">Методические материалы</a></li>
                                <li><a class="dropdown-item" href="/prepod/kyrator">Кураторская работа</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/okno" role="button" data-bs-toggle="dropdown">
                                Одно окно
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/okno">Одно окно</a></li>
                                <li><a class="dropdown-item" href="/okno/forma">Формы документов</a></li>
                                <li><a class="dropdown-item" href="/okno/info">Полезная информация</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/message" role="button" data-bs-toggle="dropdown">
                                FAQ
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/message">Часто задаваемые вопросы</a></li>
                                <li><a class="dropdown-item" href="/message/contact">Контакты</a></li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Правая часть header -->
                    <div class="header-actions">
                        <!-- Языковые флаги -->
                        <div class="language-flags">
                            <img src="/assets/media/flags/ru.png" alt="Русский" class="flag" onerror="this.style.display='none'">
                            <img src="/assets/media/flags/de.png" alt="Deutsch" class="flag" onerror="this.style.display='none'">
                            <img src="/assets/media/flags/cn.png" alt="中文" class="flag" onerror="this.style.display='none'">
                        </div>

                        <!-- Кнопка для слабовидящих -->
                        <a href="#" class="accessibility-btn">
                            <i class="fa fa-eye"></i>
                            <span class="accessibility-text">для слабовидящих</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Подключение JavaScript для header -->
    <script src="/assets/js/header.js?v=<?php echo time(); ?>"></script>