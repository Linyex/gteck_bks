<!DOCTYPE html>
<html lang="ru" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title>Панель управления | Гомельский торгово-экономический колледж</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="gtec-bks.by" name="url" />
    <link rel="stylesheet" href="/assets/css/timesnewroman/timesnewromanpsmt.ttf" type="text/css">
    <link href="/assets/css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/bootstrap-replacement.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/lampa.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/gtec.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN THEME STYLES -->
    <link href="/assets/css/chief-slider/chief-slider.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/components-rtl.css" id="style_components" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="/favicon.png" />
    <link rel="stylesheet" href="/assets/css/owl.carousel.css">
    <link rel="stylesheet" href="/assets/css/owl.theme.green.css">
    <link rel="stylesheet" href="/assets/css/fancybox.css">
    <link rel="stylesheet" href="/assets/css/admincss.css">
    <!-- FORMS -->
    <script src="/assets/js/private/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/private/jquery.form.js" type="text/javascript"></script>
    <script src="/assets/js/private/jquery.flot.js" type="text/javascript"></script>
    <script src="/assets/js/private/jquery.flot.time.js" type="text/javascript"></script>
    <script src="/assets/js/private/main.js" type="text/javascript"></script>
    <script src="/assets/js/uploadf.js" type="text/javascript"></script>
    <style>
        .content {
            margin-top: 50px;
        }
    </style>

</head>

<div id="otvet"></div>
<body style="background: #f4f4f4;">
    <!-- BEGIN: HEADER -->
    <!-- Новый год лампочки -->
    <header class="header clearfix">
        <div class="header-top">
            <!-- Закоментировать блок zoka пр включении лампочек -->
            <div class="zoka">
                <a href="#">&nbsp;</a>
            </div>
        </div>
        
        <div class="head_i center fx-middle">
            <div class="logo-st" style="margin-bottom: 10px;">
                <a href="/admin" class="logotype" title="На главную">
                    <img src="/assets/media/img/logos/logo2.png" alt="">
                </a>
                <div class="c-brand" align="left">
                    <h4 class="c-font-23 c-font-white c-desktop-logo-inverse c-desktop-logo">Учреждение образования<br class="">
                        "Гомельский торгово-экономеский колледж"<br>Белкоопсоюза</h4>
                </div>
                <div class="mob-button d">
                    <a id="menu-btn" href="#" class="menu-btn bb">
                        <span></span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <!-- END: HEADER -->
    <div id="close-overlay"></div>
    <div id="close-login"></div>
    <div class="btn-close" href="#" id="btn-close"></div>
    <!-- BEGIN: MOBILE MENU -->
    <div class="side-panel" id="side-panel">
        <div class="lb-in">
            <a href="/admin">УО "Гтэк" Белкоопсоюза</a>
            <div class="l-over"></div>
        </div>
        <div class="side-box" style="padding: 20px;">
            <div class="header-mobi" style="margin-top: 0;">Панель управления</div>
            <nav class="fast-menu">
                <ul class="c-menu c-arrow-dot c-theme">
                    <li><a href="/">Вернуться на главную</a></li>
                    <?php if ($user_access_level >= 3): ?>
                    <li><a href="/admin/news">Новости</a></li>
                    <li><a href="/admin/pages">Страницы</a></li>
                    <?php endif; ?>
                    <li><a href="/admin/settings">Настройки пользователя</a></li>
                    <li><a href="/admin/logout">Выйти из профиля</a></li>
                </ul>
            </nav>
            <?php if ($user_access_level >= 3): ?>
            <div class="header-mobi">Отделения</div>
            <nav class="fast-menu">
                <ul class="c-menu c-arrow-dot c-theme">
                    <li><a href="/admin/prof">Отделение проффесиональной подготовки</a></li>
                    <li><a href="/admin/dnevnoe">Дневное отделение</a></li>
                    <li><a href="/admin/zaoch">Заочное отделение</a></li>
                </ul>
            </nav>
            <div class="header-mobi">Дополнительное меню</div>
            <nav class="fast-menu">
                <ul class="c-menu c-arrow-dot c-theme">
                    <li><a href="/admin/abutedit">Абитуриенту</a></li>
                    <li><a href="/admin/abut">Списки зачисленных</a></li>
                    <li><a href="/admin/listgr">Списки групп</a></li>
                    <li><a href="/admin/vesti">Вести колледжа</a></li>
                    <li><a href="/admin/bestych">Лучшие учащиеся</a></li>
                    <li><a href="/admin/bestsport">Лучшие спортсмены</a></li>
                    <li><a href="/admin/photos">Фотографии</a></li>
                    <li><a href="/admin/xydsamo">Участники художественной самодеятельности</a></li>
                </ul>
            </nav>
            <?php endif; ?>
            </div>
    </div>
    <!-- END: MOBILE MENU -->

    <!-- BEGIN: SLIDER BOOK -->
    <div class="top-side center clearfix" style="z-index: 1;">
        <div class="col-md-9" style="background: #fff; padding: 20px; display: block;">