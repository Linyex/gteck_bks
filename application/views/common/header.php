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
    
    <!-- Bootstrap replacement - минимальные стили без конфликтов -->
    <link href="/assets/css/bootstrap-replacement.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    
    <!-- Остальные стили БЕЗ Bootstrap -->
    <link href="/assets/css/font-awesome/css/font-awesome.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/main-styles.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/footer.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/main-page.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/assets/css/banners-soft.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    
    <!-- Header CSS загружается последним для максимального приоритета -->
    <link href="/assets/css/header.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    
    <!-- Modern Departments CSS -->
    <link href="/assets/css/departments-modern.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    
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
        <nav class="gtec-navbar">
            <div class="header-container">
                <!-- Левая часть: Логотип и название -->
                <div class="brand-section">
                    <a class="brand-link" href="/">
                        <div class="logo-container">
                            <img src="/assets/media/logo.svg" alt="ГТЭК" class="logo" onerror="this.style.display='none'">
                            <div class="logo-fallback">🎓</div>
                        </div>
                        <div class="brand-text">
                            <div class="brand-title">ГТЭК</div>
                            <div class="brand-subtitle">Гомельский торгово-экономический колледж Белкоопсоюза</div>
                        </div>
                    </a>
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
                                   <li><a href="/kol/achiv">Наши достижения</a></li>
                                   <li><a href="/kol/foto">Фотографии</a></li>
                                   <li><a href="/dopage/faq">Часто задаваемые вопросы</a></li>
                                   <li><a href="/assets/files/О видеонаблюдении.pdf">О видеонаблюдении</a></li>
                               </ul>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" href="/news">Новости</a>
                           </li>
                                                       <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="/abut" style="cursor: pointer;">Абитуриенту</a>
                               <ul class="dropdown-menu">
                                   <li><a href="/abut/sroki">Сроки приема документов</a></li>
                                   <li><a href="/abut/spec">Специальности</a></li>
                                   <li><a href="/abut/prof">Кабинет профориентации</a></li>
                                   <li><a href="/assets/files/Правила.pdf" target="_blank">Правила приема</a></li>
                                   <li><a href="/abut/plan">Цифры приема</a></li>
                                   <li><a href="/assets/files/Перечень документов.pdf" target="_blank">Документы для поступления</a></li>
                                   <li><a href="/assets/files/Телефоны.pdf" target="_blank">Телефоны горячей линии</a></li>
                                   <li><a href="/abut/grafik">Режим работы</a></li>
                                   <li><a href="/abut/vups">Наши выпускники</a></li>
                               </ul>
                           </li>
                                                       <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="/stud" style="cursor: pointer;">Учащемуся</a>
                               <ul class="dropdown-menu">
                                   <li><a href="/stud/dnevnoe">Дневное отделение</a></li>
                                   <li><a href="/stud/zaoch">Заочная форма получения образования</a></li>
                                   <li><a href="/stud/prof">Дополнительное образование</a></li>
                                   <li class="dropdown-submenu">
                                       <a href="#">Воспитательная и идеологическая работа</a>
                                       <ul class="dropdown-submenu-list">
                                           <li><a href="https://gomel-region.by/ru/edi-ru">Единый день информирования</a></li>
                                           <li><a href="/dopage/social">Социальный педагог</a></li>
                                           <li><a href="/dopage/psixolog">Педагог-психолог</a></li>
                                           <li><a href="/dopage/profs">Профсоюз</a></li>
                                           <li><a href="/dopage/brsm">БРСМ</a></li>
                                           <li><a href="/dopage/muzei">Музей</a></li>
                                           <li><a href="/dopage/pravila">Правила безопасного поведения</a></li>
                                           <li><a href="https://vospitanie.adu.by/shkola-aktivnogo-grazhdanina/shag-dlya-viii-xi-klassov-informatsionnye-materialy-prezentatsii.html">ШАГ</a></li>
                                           <li><a href="/stud/dis">Дистанционное родительское собрание</a></li>
                                       </ul>
                                   </li>
                                   <li><a href="/stud/ychm">Учебные материалы</a></li>
                                   <li><a href="/stud/byx">Бухгалтерия</a></li>
                                   <li><a href="/stud/yslugi">Платные услуги</a></li>
                                   <li><a href="/stud/hostel">Общежитие</a></li>
                                   <li class="dropdown-submenu">
                                       <a href="#">Спортивная жизнь колледжа</a>
                                       <ul class="dropdown-submenu-list">
                                           <li><a href="/dopage/bestsport">Лучшие спортсмены</a></li>
                                           <li><a href="/dopage/sportkr">Секции</a></li>
                                           <li><a href="/dopage/sport">Спортивно-массовая работа</a></li>
                                       </ul>
                                   </li>
                                   <li class="dropdown-submenu">
                                       <a href="#">Производственное обучение</a>
                                       <ul class="dropdown-submenu-list">
                                           <li><a href="/dopage/ychpraktika">Учебные практики</a></li>
                                           <li><a href="/dopage/praktiki">Технологические и преддипломные</a></li>
                                       </ul>
                                   </li>
                                   <li class="dropdown-submenu">
                                       <a href="#">Художественная самодеятельность</a>
                                       <ul class="dropdown-submenu-list">
                                           <li><a href="/dopage/ychxod">Участники художественной самодеятельности</a></li>
                                           <li><a href="/dopage/kryjki">Кружки</a></li>
                                       </ul>
                                   </li>
                                   <li><a href="/stud/library">Библиотека</a></li>
                                   <li><a href="https://profbiblioteka.by">Электронная библиотечная система РИПО</a></li>
                               </ul>
                           </li>
                                                       <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="/prepod" style="cursor: pointer;">Преподавателю</a>
                               <ul class="dropdown-menu">
                                   <li><a href="/prepod/metod">Методическая работа</a></li>
                                   <li><a href="/prepod/kyrator">Куратору</a></li>
                               </ul>
                           </li>
                                                       <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="/okno" style="cursor: pointer;">Одно окно</a>
                               <ul class="dropdown-menu">
                                   <li><a href="/okno/info">Общая информация в службе "Одно окно"</a></li>
                                   <li><a href="/okno/proc">Административные процедуры</a></li>
                                   <li><a href="/okno/poryad">Порядок подачи заявлений</a></li>
                                   <li><a href="/okno/forma">Формы документов</a></li>
                                   <li><a href="/okno/osnovi">Основные нормативные правовые акты по общим вопросам осуществления административных процедур</a></li>
                                   <li><a href="/assets/files/Политика.pdf" target="_blank">Защита персональных данных. Политика</a></li>
                                   <li><a href="/assets/files/Политика2.pdf" target="_blank">Система менеджмента здоровья и безопасности труда. Политика</a></li>
                               </ul>
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
                </div>
            </div>
        </nav>
    </header>

    <!-- Подключение JavaScript для header -->
    <script src="/assets/js/header.js?v=<?php echo time(); ?>"></script>