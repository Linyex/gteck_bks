        </div>
        <div class="col-md-3" style="float: right; background: #f4f4f4; z-index: 1; display: block;">
            <aside class="side" id="side">
                <div style="font-size: 15px;">
                    <div class="header-mobi" style="margin-top: 0;">Панель управления</div>
                    <nav class="fast-menu">
                        <ul class="c-menu c-arrow-dot c-theme">
                            <li><a href="/">Вернуться на главную</a></li>
                            <?php if ($user_access_level >= 3): ?>
                            <li><a href="/admin/news">Новости </a></li>
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
            </aside>
        </div>
    </div>
    <!-- END: SLIDER BOOK -->
    <!-- BEGIN: PAGE CONTENT -->
    <div class="c-layout-page" style="color: #464646;">
        <div class="c-content-box c-size-sm">
            <div class="top-side center clearfix">
                <h1>Краткая информация о колледже</h1>
                <hr>
                <article>
                    <p>Cтарейшее учебное заведение потребительской кооперации Республики Беларусь. Свою историю колледж ведет с 1944 года. Сегодня колледж – это современное учреждение с обновленной материальной базой, квалифицированными педагогическими кадрами, обеспечивающими должное качеству обучения будущих бухгалтеров, экономистов, товароведов, программистов, продавцов. Успехи, достигнутые колледжем в подготовке кадров, дважды отмечались наградой "Почетный знак "Белкоопсоюза".</p>
                    <p>В учреждении образования за время его существования подготовлено более 27 000 специалистов со средним специальным образованием и более 11 000 работников массовых профессий.</p>
                </article>
            </div>
        </div>
    </div>
    <!-- BEGIN: FOOTER -->
    <a name="footer"></a>
    <footer class="c-layout-footer c-layout-footer-3 c-bg-gray">
        <div class="c-prefooter">
            <div class="top-side center clearfix">
                <div class="row">
                    <div class="col-md-3">
                        <div class="c-container">
                            <div class="c-font-grey2">Контакты</div>
                            <nav class="soc-se" style="padding: 0;">
                                <ul class="c-socials navik">
                                    <li><a href="https://ok.ru/group/52605402808452" target="_blank"><i class="fa fa-odnoklassniki"></i></a></li>
                                    <li><a href="#"><i class="fa fa-youtube-play"></i></a></li>
                                    <li><a href="https://vk.com/gomeltec" target="_blank"><i class="fa fa-vk"></i></a></li>
                                    <li><a href="/dopage/vesti"><i class="fa fa-newspaper-o"></i></a></li>
                                    <li><a href="tel:80232337003"><i class="fa fa-phone"></i></a></li>
                                </ul>
                            </nav>
                            
                        </div>
                    </div>
                    <div class="col-md-6" style="height: 100%; margin-top: 20px; display: inline-block;">
                        <div class="c-container c-first">
                            <div style="text-align: center;">
                                <p class="c-font-grey">2022 © ГТЭК
                                    <span style="color: darkseagreen;">Зарегистрирован в Гос. регистре информационных ресурсов Беларуси </span>13.08.2013 г. ИР 5141303587. 
                                    <span style="color: darkseagreen;">Сайт колледжа разработал <a href="https://vk.com/carbonex" target="_blank" style="color: white;"> Дробышевский И.Н.</a></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="c-container c-first">
                            <div class="c-addres" style="text-align: right;">
                                <p>г. Гомель, ул. Привокзальная, 4</p>
                                <p>тел. 8 (0232) 33-70-02</p>
                                <p>gtec@mail.gomel.by </p>
								<div class="c-addres" style="text-oblique: right;"
								<p>принимаются сообщения </p>
								<p> из доменов .by и .ru</p>
						
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- END: FOOTER -->
    <a href="javascript:void(0);" class="js-back-to-top back-to-top">Наверх</a>
    <!-- BEGIN: JS SCRIPTS -->
    <script src="/assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/fancybox.umd.js" type="text/javascript"></script>
    <script src="/assets/js/bootstrap.js" type="text/javascript"></script>
    <script src="/assets/js/jquery.back-to-top.js" type="text/javascript"></script>
    <script src="/assets/js/gtec.js" type="text/javascript"></script>
</body>

</html>