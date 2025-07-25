 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?>Гомельский торгово-экономический колледж</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="gtec-bks.by" name="url" />
    <link rel="stylesheet" href="/assets/css/timesnewroman/timesnewromanpsmt.ttf" type="text/css">
    <link href="/assets/css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/lampa.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/gtec.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN THEME STYLES -->
    <link href="/assets/css/chief-slider/chief-slider.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/components-rtl.css" id="style_components" rel="stylesheet" type="text/css" />
    <link href="/assets/css/slick.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/slick-theme.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/modpanel.css" rel="stylesheet" type="text/css">

    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="/favicon.png" />
    <link rel="stylesheet" href="/assets/css/fancybox.css">
    <link rel="stylesheet" href="/assets/css/yatranslate.css">
    <!-- FORMS -->
    <script src="/assets/js/private/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/private/jquery.form.js" type="text/javascript"></script>
    <script src="/assets/js/private/jquery.flot.js" type="text/javascript"></script>
    <script src="/assets/js/private/jquery.flot.time.js" type="text/javascript"></script>
    <script src="/assets/js/private/main.js" type="text/javascript"></script>
    <script src="/assets/js/yatranslate.js"></script>
    <script src="/assets/js/uhpv.js" type="text/javascript"></script>
    <style>
        .content {
            margin-top: 50px;
        }
    </style>
</head>
<div id="otvet"></div>
<div style="display: none;"><?php if (!empty($error)): ?>
    <?php echo $error ?>
<?php elseif (!empty($success)): ?>
    <?php echo $success ?>
<?php elseif (!empty($warning)): ?>
    <?php echo $warning ?>
<?php endif; ?></div>
<body style="background: #f4f4f4;">
    <!-- Новый год лампочки -->
    <?php if($status2['statusb_code'] == (int)2): ?>
    <ul class="lightrope" style="z-index: 99;">
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li><li></li>
      <li></li>
    </ul> 
    <?php endif; ?>
    <!-- BEGIN: HEADER -->
    <header class="header clearfix" id="headtop">
        <?php if($status2['statusb_code'] == (int)2): ?>
        <div class="header-top" style="margin-bottom: 20px;">
            <div class="zoka" style="display: none;">
                <a href="#">&nbsp;</a>
            </div>
        </div>
        <?php else: ?>
        <div class="header-top" id="pre-zorka">
            <div class="zoka" id="zorka">
                <a href="#">&nbsp;</a>
            </div>
        </div>
        <?php endif; ?>
        <div class="head_i center fx-middle">
            <div class="logo-st" style="margin-bottom: 10px;">
                <?php if($status2['statusb_code'] == (int)2): ?>
                <a href="/" class="head-year">
                    <img src="/assets/media/img/logos/head.png" alt="">
                </a>
                <?php endif; ?>
                <a href="/" class="logotype" title="На главную">
                    <img src="/assets/media/img/logos/logo2.png" alt="">
                </a>
                <div class="c-brand" style="width: 100%;">
                    <h4 class="c-font-23 c-font-white c-desktop-logo-inverse c-desktop-logo">Учреждение образования<br class="">
                        "Гомельский торгово-экономический колледж"<br>Белкоопсоюза</h4>
                    <h4 class="c-font-23 c-font-white c-desktop-logo-inverse c-desktop-logo mobile">УО "Гомельский торгово-экономический колледж"<br>Белкоопсоюза</h4>
                </div>
                <div class="lang lang_fixed pc-lang" id="pc-lang">
                    <div id="ytWidget" style="display: none;"></div>
                    <div class="lang__link lang__link_select" data-lang-active>
                        <img class="lang__img lang__img_select" src="/assets/media/lang/RUS.webp" alt="Ru">
                    </div>
                    <div class="lang__list" data-lang-list>
                        <a class="lang__link lang__link_sub" data-ya-lang="ru">
                            <img class="lang__img" src="/assets/media/lang/RUS.webp" alt="ru">
                        </a>
                        <a class="lang__link lang__link_sub" data-ya-lang="be">
                            <img class="lang__img" src="/assets/media/lang/lang__be.png" alt="be">
                        </a>
                        <a class="lang__link lang__link_sub" data-ya-lang="en">
                            <img class="lang__img" src="/assets/media/lang/US.png" alt="en">
                        </a>
                        <a class="lang__link lang__link_sub" data-ya-lang="de">
                            <img class="lang__img" src="/assets/media/lang/GE.webp" alt="de">
                        </a>
                        <a class="lang__link lang__link_sub" data-ya-lang="zh">
                            <img class="lang__img" src="/assets/media/lang/KIT.webp" alt="zh">
                        </a>
                    </div>
                </div>
                <div class="eye pc-eye" id="specialButton">
                    <div class="eye-img">
                        <img src="/assets/media/eye2.png" alt="">
                    </div>
                    <div class="eye-text">
                        <p>для слабовидящих</p>
                    </div>
                </div>
                <div class="mob-button">
                    <a id="menu-btn" href="#" class="menu-btn bb"> <span></span> </a>
                </div>
            </div>
            <div class="pc-nav">
                <nav>
                    <ul class="topmenu">
                        <li><a href="/">Главная</a></li>
                        <li><a style="cursor: pointer;">О колледже</a>
                            <ul class="submenu">
                                <li><a href="/kol/grafik">Режим работы колледжа</a></li>
                                <li><a href="/kol/history">История</a></li>
                                <li><a href="/kol/contact">Контакты</a></li>
                                <li><a href="/kol/achiv">Наши достижения</a></li>
                                <li><a href="/kol/foto">Фотографии</a></li>
                                <li><a href="/dopage/faq">Часто задаваемые вопросы</a></li>
                                <li><a href="/assets/files/О видеонаблюдении.pdf">О видеонаблюдении</a></li>
                            </ul>
                        </li>
                        <li><a href="/news">Новости</a></li>
                        <li><a style="cursor: pointer;">Абитуриенту</a>
                            <ul class="submenu">
                                <li><a href="/abut/sroki">Сроки приема документов</a></li>
                                <li><a href="/abut/spec">Специальности</a></li>
                                <li><a href="/abut/prof">Кабинет профориентации</a></li>
                                <li><a href="/assets/files/Правила.pdf" target="_blank">Правила приема</a></li>
                                <li><a href="/abut/plan">Цифры приема</a></li>
                                <li><a href="/assets/files/Перечень документов.pdf" target="_blank">Документы для поступления</a></li>
                                <li><a href="/assets/files/Телефоны.pdf" target="_blank">Телефоны горячей линии</a></li>
                                <li><a href="/abut/grafik">Режим работы</a></li>
                                <li><a href="/abut/vups">Наши выпускники</a></li>
    <!--                            <li><a href="/abut/spiski">Списки зачисленных</a></li>          -->
                            </ul>
                        </li>
                        <li><a style="cursor: pointer;">Учащемуся</a>
                            <ul class="submenu">
                                <li><a href="/stud/dnevnoe">Дневное отделение</a></li>
                                <li><a href="/stud/zaoch">Заочная форма получения образования</a></li>
                                <li><a href="/stud/prof">Дополнительное образование</a></li>
                                <li><a href="#">Воспитательная и идеологическая работа</a>
                                    <ul class="submenu2">
                                        <li><a href="https://gomel-region.by/ru/edi-ru">Единый день информирования</a></li>
                                        <li><a href="/dopage/social">Социальный педагог</a></li>
                                        <li><a href="/dopage/psixolog">Педагог-психолог</a></li>
                                        <li><a href="/dopage/profs">Профсоюз</a></li>
                                        <li><a href="/dopage/brsm">БРСМ</a></li>
                                        <li><a href="/dopage/muzei">Музей</a></li>
                                        <li><a href="/dopage/pravila">Правила безопасного поведения</a></li>
                                        <li><a href="https://vospitanie.adu.by/shkola-aktivnogo-grazhdanina/shag-dlya-viii-xi-klassov-informatsionnye-materialy-prezentatsii.html">ШАГ</a></li>
                                        <li><a href="/stud/dis">Дистанционное родительское собрание</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="/stud/ychm">Учебные материалы</a></li>
                                <li><a href="/stud/byx">Бухгалтерия</a></li>
                                <li><a href="/stud/yslugi">Платные услуги</a></li>
                                <li><a href="/stud/hostel">Общежитие</a></li>
                            
                                <li><a href="#">Спортивная жизнь колледжа</a>
                                    <ul class="submenu2">
                                        <li><a href="/dopage/bestsport">Лучшие спротсмены</a></li>
                                        <li><a href="/dopage/sportkr">Секции</a></li>
                                        <li><a href="/dopage/sport">Спортивно-массовая работа</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Производственное обучение</a>
                                    <ul class="submenu2">
                                        <li><a href="/dopage/ychpraktika">Учебные практики</a></li>
                                        <li><a href="/dopage/praktiki">Технологические и преддипломные</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Художественная самодеятельность</a>
                                    <ul class="submenu2">
                                        <li><a href="/dopage/ychxod">Участники художественной самодеятельности</a></li>
                                        <li><a href="/dopage/kryjki">Кружки</a></li>
                                    </ul>
                                </li>
                                <li><a href="/stud/library">Библиотека</a></li>
                                <li><a href="https://profbiblioteka.by">Электронная библиотечная система РИПО</a></li>

                            </ul>
                        </li>
                        <li><a style="cursor: pointer;">Преподавателю</a>
                            <ul class="submenu">
                                <li><a href="/prepod/metod">Методическая работа</a></li>
                                <li><a href="/prepod/kyrator">Куратору</a></li>
                            </ul>
                        </li>
                        <li><a style="cursor: pointer;">Одно окно</a>
                            <ul class="submenu">
                                <li><a href="/okno/info">Общая информация в службе "Одно окно"</a></li>
                                <li><a href="/okno/proc">Административные процедуры</a></li>
                                <li><a href="/okno/poryad">Порядок подачи заявлений</a></li>
                                <li><a href="/okno/forma">Формы документов</a></li>
                                <li><a href="/okno/osnovi">Основные нормативные правовые акты по общим вопросам осуществления административных процедур</a>
                                </li>
                                <li><a href="/assets/files/Политика.pdf" target="_blank">Защита персональных данных. Политика</a></li>
                                <li><a href="/assets/files/Политика2.pdf" target="_blank">Система менеджмента здоровья и безопасности труда. Политика</a></li>
                            </ul>
                        </li>
                        <li><a href="/dopage/faq">FAQ</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <!-- END: HEADER -->

    <div id="close-overlay"></div>
    <div id="close-login"></div>
    <div class="btn-close" id="btn-close"></div>

    <!-- BEGIN: MOBILE MENU -->
    <div class="side-panel" id="side-panel">
        <div class="lb-in">
            <a href="#">УО "Гтэк" Белкоопсоюза</a>
            <div class="l-over"></div>
        </div>
        <div class="side-box">
            <div class="lang lang_fixed" style="display: table; position: relative; top: 0; right: 0; text-align: center; align-items: center; left: 0; width: 100%">
                <div class="lang__list" data-lang-list>
                    <a class="lang__link lang__link_sub" data-ya-lang="ru">
                        <img class="lang__img" src="/assets/media/lang/RUS.webp" alt="ru">
                    </a>
                    <a class="lang__link lang__link_sub" data-ya-lang="be">
                        <img class="lang__img" src="/assets/media/lang/lang__be.png" alt="be">
                    </a>
                    <a class="lang__link lang__link_sub" data-ya-lang="en">
                        <img class="lang__img" src="/assets/media/lang/US.png" alt="en">
                    </a>
                    <a class="lang__link lang__link_sub" data-ya-lang="de">
                        <img class="lang__img" src="/assets/media/lang/GE.webp" alt="de">
                    </a>
                    <a class="lang__link lang__link_sub" data-ya-lang="zh">
                        <img class="lang__img" src="/assets/media/lang/KIT.webp" alt="zh">
                    </a>
                </div>
            </div>
            <div class="eye" id="specialButton2" style="position: relative; right: 0; top: 0; text-align: center; width: 100%; left: 0; display: inline-flex; align-items: center; margin-top: 20px; margin-left: 0; margin-right: 0;">
                <div class="eye-img" style="border: solid #494949 1px;">
                    <img src="/assets/media/eye2.png" alt="">
                </div>
                <div class="eye-text eye-mob" style="flex: 1; text-align: center; border: solid #494949 1px; border-radius: 0;">
                    <p style="width: 100%; text-shadow: none;">для слабовидящих</p>
                </div>
            </div>
            <div class="header-mobi">Навигация</div>
            <div id="m_body">
                <ul>
                    <li><a href="/">Главная</a></li>
                    <li><a href="#" onclick="menuOpen('s_mn_4');return(false)">О колледже</a>
                        <ul id="s_mn_4">
                            <li><a href="/kol/grafik">Режим работы колледжа</a></li>
                            <li><a href="/kol/history">История</a></li>
                            <li><a href="/kol/contact">Контакты</a></li>
                            <li><a href="/kol/achiv">Наши достижения</a></li>
                            <li><a href="/kol/foto">Фотографии</a></li>
                            <li><a href="/dopage/faq">Часто задаваемые вопросы</a></li>
                        </ul>
                    </li>
                    <li><a href="/news">Новости</a></li>
                    <li><a href="#" onclick="menuOpen('s_mn_1');return(false)">Абитуриенту</a>
                        <ul id="s_mn_1">
                            <li><a href="/abut/sroki">Сроки приема документов</a></li>
                            <li><a href="/abut/spec">Специальности</a></li>
                            <li><a href="/abut/prof">Кабинет профориентации</a></li>
                            <li><a href="/assets/files/Правила.pdf" target="_blank">Правила приема</a></li>
                            <li><a href="/abut/plan">Цифры приема</a></li>
                            <li><a href="/assets/files/Перечень документов.pdf" target="_blank">Документы для поступления</a></li>
                            <li><a href="/assets/files/Порядок приема.pdf" target="_blank">Порядок приема в учреждения образования потребительской кооперации</a></li>
                            <li><a href="/abut/grafik">Режим работы</a></li>
                            <li><a href="/abut/vups">Наши выпускники</a></li>
     <!--                       <li><a href="/abut/spiski">Списки зачисленных</a></li> -->
                        </ul>
                    </li>
                    <li><a href="#" onclick="menuOpen('s_mn_2');return(false)">Учащемуся</a>
                        <ul id="s_mn_2">
                            <li><a href="/stud/dnevnoe">Дневное отделение</a></li>
                            <li><a href="/stud/zaoch">Заочное отделение</a></li>
                            <li><a href="/stud/prof">Отделение профессиональной подготовки</a></li>
                            <li><a href="#" onclick="menuOpen('s_mn_6');return(false)">Информационно-воспитательная работа</a>
                                <ul id="s_mn_6" style="padding: 0 60px;">
                                    <li><a href="/stud/best">Лучшие учащиеся</a></li>
                                    <li><a href="/dopage/social">Социальный педагог</a></li>
                                    <li><a href="/dopage/psixolog">Педагог-психолог</a></li>
                                    <li><a href="/dopage/profs">Профсоюз</a></li>
                                    <li><a href="/dopage/brsm">БРСМ</a></li>
                                    <li><a href="/dopage/muzei">Музей</a></li>
                                    <li><a href="/dopage/pravila">Правила безопасного поведения</a></li>
                                    <li><a href="/stud/dis">Дистанционное родительское собрание «Профилактика противоправных нарушений у подростков. ОБЖ»</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="/stud/ychm">Учебные материалы</a></li>
                            <li><a href="/stud/byx">Бухгалтерия</a></li>
                            <li><a href="/stud/yslugi">Платные услуги</a></li>
                            <li><a href="/stud/hostel">Общежитие</a></li>
          <!--                  <li><a href="/stud/spiski">Списки групп</a></li>    -->
                            <li><a href="#" onclick="menuOpen('s_mn_7');return(false)">Спортивная жизнь колледжа</a>
                                <ul id="s_mn_7" style="padding: 0 60px;">
                                    <li><a href="/dopage/bestsport">Лучшие спротсмены</a></li>
                                    <li><a href="/dopage/sportkr">Секции</a></li>
                                    <li><a href="/dopage/sport">Спортивно-массовая работа</a></li>
                                </ul>
                            </li>
                            <li><a href="#" onclick="menuOpen('s_mn_8');return(false)">Производственное обучение</a>
                                <ul id="s_mn_8" style="padding: 0 60px;">
                                    <li><a href="/dopage/ychpraktika">Учебные практики</a></li>
                                    <li><a href="/dopage/praktiki">Технологические и преддипломные</a></li>
                                </ul>
                            </li>
                            <li><a href="#" onclick="menuOpen('s_mn_9');return(false)">Художественная самодеятельность</a>
                                <ul id="s_mn_9" style="padding: 0 60px;">
                                    <li><a href="/dopage/ychxod">Участники художественной самодеятельности</a></li>
                                    <li><a href="/dopage/kryjki">Кружки</a></li>
                                </ul>
                            </li>
                            <li><a href="/stud/library">Библиотека</a></li>
                        </ul>
                    </li>
                    <li><a href="#" onclick="menuOpen('s_mn_3');return(false)">Преподавателю</a>
                        <ul id="s_mn_3">
                            <li><a href="/prepod/metod">Методическая работа</a></li>
                            <li><a href="/prepod/kyrator">Куратору</a></li>
                        </ul>
                    </li>
                    <li><a href="#" onclick="menuOpen('s_mn_5');return(false)">Одно окно</a>
                        <ul id="s_mn_5">
                            <li><a href="/okno/info">Общая информация в службе "Одно окно"</a></li>
                            <li><a href="/okno/rejim">Режим работы колледжа</a></li>
                            <li><a href="/okno/proc">Административные процедуры</a></li>
                            <li><a href="/okno/poryad">Порядок подачи заявлений</a></li>
                            <li><a href="/okno/forma">Формы документов</a></li>
                            <li><a href="/okno/osnovi">Основные нормативные правовые акты по общим вопросам осуществления административных процедур</a>
                            </li>
                            <li><a href="/assets/files/Политика.pdf" target="_blank">Защита персональных данных. Политика</a></li>
                            <li><a href="/assets/files/Политика2.pdf" target="_blank">Система менеджмента здоровья и безопасности труда. Политика</a></li>
                        </ul>
                    </li>
                    <li><a href="/dopage/faq">Часто задаваемые вопросы</a></li>
                    <li><a href="/search">Поиск на странице</a></li>
                </ul>
            </div>

            <div class="header-mobi">Быстрая навигация</div>
            <nav class="fast-menu">
                <ul class="c-menu c-arrow-dot c-theme">
                    <li><a href="/stud/dnevnoe">Дневное отделение</a></li>
                    <li><a href="/stud/zaoch">Заочное отделение</a></li>
                    <li><a href="/stud/prof">Отделение профессиональной<br>подготовки</a></li>
                    <li><a href="/dopage/vesti">Вести колледжа</a></li>
                </ul>
            </nav>
            <div class="header-mobi"><a href="#" style="font-size: 18px; color: #333;">Электронные обращения</a></div>
            <div class="mobile-side-buttons">
                <?php if($status1['statusb_code'] == (int)2): ?>
                    <a href="/abut/sroki" class="green">Сроки приема <br>документов</a>
                <?php endif; ?>
                <?php if($status3['statusb_code'] == (int)2): ?>
    <!--                <a href="/abut/spiski" class="green">Списки зачисленных <br>абитуриентов</a> -->
                <?php endif; ?>
                <a href="/message" style="padding: 5px;">Электронные обращения </a>
            </div>
            <div class="header-mobi">Контакты</div>
            <div class="c-post">
                Адрес колледжа: 246017, г. Гомель,
                ул. Привокзальная, 4<br>
                E-mail: gtec@mail.gomel.by<br>
				принимаются сообщения из доменов .by и .ru
                Телефон/факс (приемная директора): 8(0232) 33-70-02
                Приемная комиссия: +375 232 20-22-14,
                8(0232) 33-70-03<br><br>
                <div class="news-about">
                    <a href="/dopage/priomp" style="float: none; color: #ffffff; background: #415c8d; border: solid 1px #7a7a7a; padding: 5px; margin: 10px 0; width: 100%; display: block; font-size: 14px; text-transform: uppercase;">График приема граждан</a>
                </div>
                Вышестоящая организация: <br>
                Белкоопсоюз, г. Минск, пр. Победителей, 17 <br>
                Режим работы Белкоопсоюза: <br>
                с 8.30 до 17.30, обед с 12.30 до 13.30 <br>
                Контактный телефон отдела образования и науки Белкоопсоюза: <br>
                - тел. 8 (017) 226-91-05; <br>
                Контактный телефон
                    Министерство образования
                    "горячая линия" <br> +375 17 222 43 12   <br>
                    (работает только в период приёмной кампании)
            </div>
        </div>
    </div>
    <!-- END: MOBILE MENU -->
    <div class="top-side center clearfix" style="z-index: 1;">
        <div class="col-md-9" style="background: #fff; padding: 15px; display: block;">
            <!-- BEGIN: PAGE CONTENT -->