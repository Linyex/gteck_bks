<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?>Гомельский торгово-экономический колледж</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="gtec-bks.by" name="url" />
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
    
    <!-- Мобильная адаптация -->
    <link href="/assets/css/mobile-adaptive.css?v=1.0.0" rel="stylesheet" type="text/css" />
    
    <!-- Мобильная шапка -->
    <link href="/assets/css/mobile-header.css?v=1.0.0" rel="stylesheet" type="text/css" />
    
    <!-- Modern Departments CSS -->
    <link href="/assets/css/departments-modern.css?v=1.2.0" rel="stylesheet" type="text/css" />
    
    <!-- Принудительное удаление размытий (загружается последним!) -->
    <link href="/assets/css/clear-blur.css?v=1.0.0" rel="stylesheet" type="text/css" />
    
    <!-- Исправления для header (загружается последним!) -->
    <link href="/assets/css/header-fixes.min.css?v=1.0.0" rel="stylesheet" type="text/css" />
    
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.png" />
    
    <!-- Оптимизированные скрипты -->
    <script src="/assets/js/jquery.min.js?v=1.2.0"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js" defer></script>
    <script src="/assets/js/modern-components.js?v=1.2.0" defer></script>
</head>
<body>
    
    <!-- HEADER -->
    <div class="c-layout-header" id="header">
        <header class="header-container">
            <!-- Левая часть - Логотип и название -->
            <div class="brand-section">
                <div class="logo-container">
                    <a href="/" class="logo-link">
                        <img class="logo-svg" width="40" height="40" src="/assets/img/logos/logos.png" alt="ГТЭК" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="logo-fallback" style="display: none;">🎓</div>
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
                                <a class="nav-link dropdown-toggle" style="cursor: pointer;">О колледже</a>
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
                                <a class="nav-link dropdown-toggle" style="cursor: pointer;">Одно окно</a>
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

                <!-- Правая часть: кнопка для слабовидящих как выпадающее меню -->
                <div class="actions-section">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle accessibility-btn" style="cursor: pointer; display: inline-flex; align-items:center; gap:8px;">
                            <i class="fa fa-eye"></i>
                            <span class="accessibility-text">для слабовидящих</span>
                        </a>
                        <ul class="dropdown-menu" style="min-width: 260px;">
                            <li><a href="#" class="js-accessibility-toggle">Переключить режим</a></li>
                            <li><a href="#" class="js-font-plus">Увеличить шрифт</a></li>
                            <li><a href="#" class="js-font-minus">Уменьшить шрифт</a></li>
                            <li><a href="#" class="js-contrast-toggle">Высокая контрастность</a></li>
                            <li><a href="#" class="js-reset-accessibility">Сбросить настройки</a></li>
                        </ul>
                    </div>
                    
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
                            <li class="mobile-dropdown-item">
                                <a href="/kol/achiv" class="mobile-dropdown-link">Наши достижения</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/kol/foto" class="mobile-dropdown-link">Фотографии</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/dopage/faq" class="mobile-dropdown-link">Часто задаваемые вопросы</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/assets/files/О видеонаблюдении.pdf" class="mobile-dropdown-link">О видеонаблюдении</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="mobile-nav-item">
                        <a href="/news" class="mobile-nav-link">Новости</a>
                    </li>
                    
                    <li class="mobile-nav-item">
                        <button class="mobile-nav-link mobile-dropdown-toggle">
                            Абитуриенту
                        </button>
                        <ul class="mobile-dropdown">
                            <li class="mobile-dropdown-item">
                                <a href="/abut/sroki" class="mobile-dropdown-link">Сроки приема документов</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/abut/spec" class="mobile-dropdown-link">Специальности</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/abut/prof" class="mobile-dropdown-link">Кабинет профориентации</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/assets/files/Правила.pdf" class="mobile-dropdown-link" target="_blank">Правила приема</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/abut/plan" class="mobile-dropdown-link">Цифры приема</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/assets/files/Перечень документов.pdf" class="mobile-dropdown-link" target="_blank">Документы для поступления</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/assets/files/Телефоны.pdf" class="mobile-dropdown-link" target="_blank">Телефоны горячей линии</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/abut/grafik" class="mobile-dropdown-link">Режим работы</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/abut/vups" class="mobile-dropdown-link">Наши выпускники</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="mobile-nav-item">
                        <button class="mobile-nav-link mobile-dropdown-toggle">
                            Учащемуся
                        </button>
                        <ul class="mobile-dropdown">
                            <li class="mobile-dropdown-item">
                                <a href="/stud/dnevnoe" class="mobile-dropdown-link">Дневное отделение</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/stud/zaoch" class="mobile-dropdown-link">Заочная форма получения образования</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/stud/prof" class="mobile-dropdown-link">Дополнительное образование</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/stud/ychm" class="mobile-dropdown-link">Учебные материалы</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/stud/byx" class="mobile-dropdown-link">Бухгалтерия</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/stud/yslugi" class="mobile-dropdown-link">Платные услуги</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/stud/hostel" class="mobile-dropdown-link">Общежитие</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/stud/library" class="mobile-dropdown-link">Библиотека</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="https://profbiblioteka.by" class="mobile-dropdown-link">Электронная библиотечная система РИПО</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="mobile-nav-item">
                        <button class="mobile-nav-link mobile-dropdown-toggle">
                            Преподавателю
                        </button>
                        <ul class="mobile-dropdown">
                            <li class="mobile-dropdown-item">
                                <a href="/prepod/metod" class="mobile-dropdown-link">Методическая работа</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/prepod/kyrator" class="mobile-dropdown-link">Куратору</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="mobile-nav-item">
                        <button class="mobile-nav-link mobile-dropdown-toggle">
                            Одно окно
                        </button>
                        <ul class="mobile-dropdown">
                            <li class="mobile-dropdown-item">
                                <a href="/okno/info" class="mobile-dropdown-link">Общая информация в службе "Одно окно"</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/okno/proc" class="mobile-dropdown-link">Административные процедуры</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/okno/poryad" class="mobile-dropdown-link">Порядок подачи заявлений</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/okno/forma" class="mobile-dropdown-link">Формы документов</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/okno/osnovi" class="mobile-dropdown-link">Основные нормативные правовые акты</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/assets/files/Политика.pdf" class="mobile-dropdown-link" target="_blank">Защита персональных данных</a>
                            </li>
                            <li class="mobile-dropdown-item">
                                <a href="/assets/files/Политика2.pdf" class="mobile-dropdown-link" target="_blank">Система менеджмента здоровья</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="mobile-nav-item">
                        <a href="/dopage/faq" class="mobile-nav-link">FAQ</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Оверлей для закрытия мобильного меню -->
        <div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>

    <!-- Подключение JavaScript для header -->
    <script src="/assets/js/header.js?v=1.2.0" defer></script>
    <script src="/assets/js/mobile-header.js?v=1.0.0" defer></script>