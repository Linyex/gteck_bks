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
    <link href="/assets/css/optimized.css?v=1.2.0" rel="stylesheet" type="text/css" />
    
    <!-- Bootstrap replacement - минимальные стили без конфликтов -->
    <link href="/assets/css/bootstrap-replacement.css?v=1.2.0" rel="stylesheet" type="text/css" />
    
    <!-- Остальные стили БЕЗ Bootstrap -->
    <link href="/assets/css/font-awesome/css/font-awesome.css?v=1.2.0" rel="stylesheet" type="text/css" />
    <link href="/assets/css/main-styles.css?v=1.2.0" rel="stylesheet" type="text/css" />
    <link href="/assets/css/footer.css?v=1.2.0" rel="stylesheet" type="text/css" />
    <link href="/assets/css/main-page.css?v=1.2.0" rel="stylesheet" type="text/css" />
    <link href="/assets/css/banners-soft.css?v=1.2.0" rel="stylesheet" type="text/css" />
    
    <!-- Header CSS загружается последним для максимального приоритета -->
    <link href="/assets/css/header.css?v=1.2.0" rel="stylesheet" type="text/css" />
    
    <!-- Modern Departments CSS -->
    <link href="/assets/css/departments-modern.css?v=1.2.0" rel="stylesheet" type="text/css" />
    
    <!-- Принудительное удаление размытий (загружается последним!) -->
    <link href="/assets/css/clear-blur.css?v=1.0.0" rel="stylesheet" type="text/css" />
    
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.png" />
    
    <!-- Оптимизированные скрипты -->
    <script src="/assets/js/jquery.min.js?v=1.2.0"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="/assets/js/modern-components.js?v=1.2.0"></script>
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
            </header>
        </div>

    <!-- Подключение JavaScript для header -->
    <script src="/assets/js/header.js?v=1.2.0"></script>