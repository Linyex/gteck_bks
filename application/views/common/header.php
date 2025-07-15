<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?>Гомельский торгово-экономический колледж</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="gtec-bks.by" name="url" />
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap, FontAwesome, Custom CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <!-- Modern Design CSS -->
    <link href="/assets/css/modern.css" rel="stylesheet" type="text/css" />
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.png" />
    <script src="/assets/js/private/jquery.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
      $(function(){ AOS.init({ once: true, duration: 700 }); });
    </script>
</head>
<body>
<!-- Hi-Tech Background Elements -->
<div class="tech-element"></div>
<div class="tech-element"></div>
<div class="tech-element"></div>
<div class="tech-element"></div>
<div class="tech-element"></div>

<!-- Animated Grid Lines -->
<div class="grid-line horizontal"></div>
<div class="grid-line vertical"></div>
<div class="grid-line horizontal" style="top: 33%; animation-delay: 5s;"></div>
<div class="grid-line vertical" style="left: 66%; animation-delay: 3s;"></div>

<header class="header clearfix" id="headtop" data-aos="fade-down">
    <div class="container">
        <div class="header-main-row">
            <div class="header-left">
                <div class="logo-st">
                    <a href="/" class="logotype" title="На главную">
                        <img src="/assets/media/img/logos/logo2.png" alt="ГТЭК">
                    </a>
                </div>
                <div class="c-brand">
                    <h4>Учреждение образования<br>"Гомельский торгово-экономический колледж"<br>Белкоопсоюза</h4>
                </div>
            </div>
            <nav class="header-nav">
                <ul class="topmenu">
                    <li><a href="/">Главная</a></li>
                    <li><a style="cursor: pointer;">О колледже
                        <ul class="submenu">
                            <li><a href="/kol/grafik">Режим работы колледжа</a></li>
                            <li><a href="/kol/history">История</a></li>
                            <li><a href="/kol/contact">Контакты</a></li>
                            <li><a href="/kol/achiv">Наши достижения</a></li>
                            <li><a href="/kol/foto">Фотографии</a></li>
                            <li><a href="/dopage/faq">Часто задаваемые вопросы</a></li>
                            <li><a href="/assets/files/О видеонаблюдении.pdf">О видеонаблюдении</a></li>
                        </ul>
                    </a></li>
                    <li><a href="/news">Новости</a></li>
                    <li><a style="cursor: pointer;">Абитуриенту
                        <ul class="submenu">
                            <li><a href="/abut/sroki">Сроки приёма документов</a></li>
                            <li><a href="/abut/spec">Специальности</a></li>
                            <li><a href="/abut/prof">Кабинет профориентации</a></li>
                            <li><a href="/assets/files/Правила.pdf" target="_blank">Правила приёма</a></li>
                            <li><a href="/abut/plan">Цифры приёма</a></li>
                            <li><a href="/assets/files/Перечень документов.pdf" target="_blank">Документы для поступления</a></li>
                            <li><a href="/assets/files/Телефоны.pdf" target="_blank">Телефоны горячей линии</a></li>
                            <li><a href="/abut/grafik">Режим работы</a></li>
                            <li><a href="/abut/vups">Наши выпускники</a></li>
                        </ul>
                    </a></li>
                    <li><a style="cursor: pointer;">Учащемуся
                        <ul class="submenu">
                            <li><a href="/stud/dnevnoe">Дневное отделение</a></li>
                            <li><a href="/stud/zaoch">Заочная форма получения образования</a></li>
                            <li><a href="/stud/prof">Дополнительное образование</a></li>
                            <li><a href="#">Воспитательная и идеологическая работа
                                <ul class="submenu2">
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
                            </a></li>
                            <li><a href="/stud/ychm">Учебные материалы</a></li>
                            <li><a href="/stud/byx">Бухгалтерия</a></li>
                            <li><a href="/stud/yslugi">Платные услуги</a></li>
                            <li><a href="/stud/hostel">Общежитие</a></li>
                            <li><a href="#">Спортивная жизнь колледжа
                                <ul class="submenu2">
                                    <li><a href="/dopage/bestsport">Лучшие спортсмены</a></li>
                                    <li><a href="/dopage/sportkr">Секции</a></li>
                                    <li><a href="/dopage/sport">Спортивно-массовая работа</a></li>
                                </ul>
                            </a></li>
                            <li><a href="#">Производственное обучение
                                <ul class="submenu2">
                                    <li><a href="/dopage/ychpraktika">Учебные практики</a></li>
                                    <li><a href="/dopage/praktiki">Технологические и преддипломные</a></li>
                                </ul>
                            </a></li>
                            <li><a href="#">Художественная самодеятельность
                                <ul class="submenu2">
                                    <li><a href="/dopage/ychxod">Участники художественной самодеятельности</a></li>
                                    <li><a href="/dopage/kryjki">Кружки</a></li>
                                </ul>
                            </a></li>
                            <li><a href="/stud/library">Библиотека</a></li>
                            <li><a href="https://profbiblioteka.by">Электронная библиотечная система РИПО</a></li>
                        </ul>
                    </a></li>
                    <li><a style="cursor: pointer;">Преподавателю
                        <ul class="submenu">
                            <li><a href="/prepod/metod">Методическая работа</a></li>
                            <li><a href="/prepod/kyrator">Куратору</a></li>
                        </ul>
                    </a></li>
                    <li><a style="cursor: pointer;">Одно окно
                        <ul class="submenu">
                            <li><a href="/okno/info">Общая информация в службе "Одно окно"</a></li>
                            <li><a href="/okno/proc">Административные процедуры</a></li>
                            <li><a href="/okno/poryad">Порядок подачи заявлений</a></li>
                            <li><a href="/okno/forma">Формы документов</a></li>
                            <li><a href="/okno/osnovi">Основные нормативные правовые акты по общим вопросам осуществления административных процедур</a></li>
                            <li><a href="/assets/files/Политика.pdf" target="_blank">Защита персональных данных. Политика</a></li>
                            <li><a href="/assets/files/Политика2.pdf" target="_blank">Система менеджмента здоровья и безопасности труда. Политика</a></li>
                        </ul>
                    </a></li>
                    <li><a href="/dopage/faq">FAQ</a></li>
                </ul>
            </nav>
            <div class="header-right">
                <div class="lang lang_fixed pc-lang" id="pc-lang">
                    <div class="lang-flags">
                        <img class="lang__img lang__img_select" src="/assets/media/lang/RUS.webp" alt="ru" title="Русский">
                        <img class="lang__img" src="/assets/media/lang/GE.webp" alt="de" title="Deutsch">
                        <img class="lang__img" src="/assets/media/lang/KIT.webp" alt="zh" title="中文">
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
            </div>
        </div>
    </div>
</header>

<!-- Scroll to top button -->
<button class="scroll-to-top" id="scrollToTop" title="Наверх">
    <i class="fa fa-chevron-up"></i>
</button>

<!-- END: HEADER -->
<div id="close-overlay"></div>
<div id="close-login"></div>
<div class="btn-close" id="btn-close"></div>

<script>
// Header scroll effect
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Scroll to top functionality
const scrollToTopBtn = document.getElementById('scrollToTop');

window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.add('show');
    } else {
        scrollToTopBtn.classList.remove('show');
    }
});

scrollToTopBtn.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>