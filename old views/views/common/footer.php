            <!-- END: PAGE CONTENT -->
            </div>
            <div class="col-md-3 block-side-p" style="float: right; background: #f4f4f4; z-index: 1;">
                <aside class="sid" id="side">
                    <div style="font-size: 15px;">
                        <div class="block-side">
                            <div class="poisk-g">
                                <div class="poisk-group-2">
                                    <input type="text" class="forma" id="search" name="search" placeholder="Поиск по сайту..">
                                    <button class="fa fa-search" onClick="sendtext()"></button>
                                </div>
                            </div>
                            <script>
                                function sendtext() {
                                    var poisktext = document.getElementById("search").value;
                                    document.location.href = '/search/?&' + poisktext;

                                }

                            </script>
                            <?php if(($status1['statusb_code'] == (int)2) || ($status3['statusb_code'] == (int)2)): ?>
                            <div class="header-mobi">Приемная компания</div>
                            <div class="mobile-side-buttons">
                                <?php if($status1['statusb_code'] == (int)2): ?>
                                <a href="/abut/sroki" class="green">Сроки приема <br>документов</a>
                                <?php endif; ?>
                                <?php if($status3['statusb_code'] == (int)2): ?>
                                <a href="/abut/spiski" class="green">Списки зачисленных <br>абитуриентов</a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <div class="header-mobi">Быстрая навигация</div>
                            <nav class="fast-menu">
                                <ul class="c-menu c-arrow-dot c-theme">
                                    <li><a href="/stud/dnevnoe">Дневное отделение</a></li>
                                    <li><a href="/stud/zaoch">Заочная форма получения образования</a></li>
                                    <li><a href="/stud/prof">Дополнительное образование</a></li>
                                    <li><a href="/dopage/vesti">Вести колледжа</a></li>
                                </ul>
                            </nav>
                            <div class="header-mobi"><a href="#" style="font-size: 18px; color: #333;">Электронные обращения</a></div>
                            <div class="mobile-side-buttons">
                                <a href="/message" style="padding: 5px;">Электронные обращения </a>
                            </div>

                            <div class="header-mobi"> </div>
                        </div>
                        <div class="c-post">
                            <div class="news-about">
                                <a href="/dopage/priomp" style="float: none; color: #ffffff; background: #415c8d; border: solid 1px #7a7a7a; padding: 5px; margin: 10px 0; width: 100%; display: block; font-size: 14px; text-transform: uppercase;">График приема граждан</a>
                            </div>

                            Вышестоящая организация: <br>
                            Белкоопсоюз, г. Минск, пр. Победителей, 17 <br>
                            Режим работы Белкоопсоюза: <br>
                            с 8.30 до 17.30, обед с 12.30 до 13.30 <br>
                            Контактный телефон отдела образования и науки Белкоопсоюза: <br>
                            - тел. 8 (017) 311-38-43; <br>
                            Контактный телефон
                            Министерство образования
                            "горячая линия" <br> +375 17 222 43 12  <br>
                            (работает только в период приёмной кампании)
                        </div>
                    </div>
                    <div class="header-mobi">Полезные ссылки</div>
                    <div style="font-size: 15px;">
                        <nav class="fast-menu">
                            <ul class="c-menu c-arrow-dot c-theme">
                                <li><a href="http://president.gov.by/" target="_blank">Президент Республики Беларусь</a></li>
                                <li><a href="https://edu.gov.by/" target="_blank">Министерство образования<br>Республики Беларусь</a></li>
                                <li><a href="https://bks.gov.by/" target="_blank">Белорусский республиканский союз потребительских обществ</a></li>
                                <li><a href="https://arw.gov.by/" target="_blank">Администрация Железнодорожного района г. Гомеля</a></li>
                                <li><a href="http://www.i-bteu.by/" target="_blank">УО «Белорусский торгово-экономический университет потребительской кооперации»</a></li>
                                <li><a href="http://www.bartc.by/" target="_blank">УО "Барановичский технологический колледж" Белкоопсоюза</a></li>
                                <li><a href="http://gkeu.bks.by/" target="_blank">УО "Гродненский колледж экономики и управления" Белкоопсоюза</a></li>
                                <li><a href="http://mtk-bks.by/" target="_blank">Минский филиал УО "Белорусский торгово-экономический университет потребительской кооперации"</a></li>
                                <li><a href="http://mogtk-bks.by/" target="_blank">Филиал УО "Белорусский торгово-экономический университет потребительской кооперации"
                                        "Могилевский торговый колледж"</a></li>
                                <li><a href="http://mtec.by/" target="_blank">УО "Молодеченский торгово-экономический колледж" Белкоопсоюза</a></li>
                    
                            </ul>
                        </nav>
                    </div>
                </aside>
            </div>
            </div>
            <!-- END: SLIDER BOOK -->

            <!-- BEGIN: SLIDER ITEM -->
            <div class="c-layout-page" style="color: #464646; padding-top: 20px;">
                <div class="c-content-box ">
                    <div class="top-side center clearfix">
                        <section class="vertical-center-4 sliderfoot" data-sizes="50vw">
                        
                        <div>   
                                <a href="https://fest-sbv.gck.by/" target="_blank">            
                                    <div  style=" height: 100px; background-image: url(/assets/media/mini-slider/Имидж_рус_900х300.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>

                        <div>   
                                <a href="https://gomel.gov.by/ru/content/god-blagoustroystva" target="_blank">            
                                    <div  style=" height: 100px; background-image: url(/assets/media/mini-slider/god_blagoustroistvo.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>


                            <div>
                                <a href="/dopage/oblisp" target="_blank">
                                    <div style=" height: 100px; background-image: url(/assets/media/mini-slider/Oblisp.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>
                            
                            <div>
                                <a href="https://president.gov.by/ru/documents/direktiva-no-11-ot-2-aprela-2025-g" target="_blank">
                                    <div style=" height: 100px; background-image: url(/assets/media/mini-slider/11_direct.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>

                            <div>
                                <a href="https://president.gov.by/ru/documents/direktiva-no-12-ot-9-aprela-2025-g" target="_blank">
                                    <div style=" height: 100px; background-image: url(/assets/media/mini-slider/12_directiva.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>

                            <div>   
                                <a href="https://gomel-region.by/ru/80-ru" target="_blank">            
                                    <div  style=" height: 100px; background-image: url(/assets/media/mini-slider/80_let_pobedy.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>


                            <div>
                                <a href="http://xn----7sbgfh2alwzdhpc0c.xn--90ais/organization/15516/org-page" target="_blank">
                                    <div style=" height: 100px; background-image: url(/assets/media/mini-slider/yslygi.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>
                            
                            <div>
                                <a href="https://pomogut.by/"target="_blank">
                                    <div style=" height: 100px; background-image: url(/assets/media/mini-slider/pomow.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>
                           
                            <div>
                                <a href="https://president.gov.by/ru/documents/direktiva-1-ot-11-marta-2004-g-1397" target="_blank">
                                    <div style=" height: 100px; background-image: url(/assets/media/mini-slider/der1.jpg); background-size: contain; background-position: center; background-repeat: no-repeat;">
                                    </div>
                                </a>
                            </div>
                            
                        </section>
                    </div>
                </div>
            </div>
            <!-- END: SLIDER ITEM -->

            <!-- BEGIN: PAGE CONTENT -->
            <div class="c-layout-page" style="color: #464646;">
                <div class="c-content-box c-size-sm" style="padding-top: 0;">
                    <div class="top-side center clearfix">
                        <h1>Краткая информация о колледже</h1>
                        <hr>
                        <article>
                            <p>Cтарейшее учебное заведение потребительской кооперации Республики Беларусь. Свою историю колледж ведет с 1944 года. Сегодня колледж – это современное учреждение с обновленной материальной базой, квалифицированными педагогическими кадрами, обеспечивающими должное качеству обучения будущих бухгалтеров, экономистов, товароведов, программистов, юристов. Успехи, достигнутые колледжем в подготовке кадров, трижды отмечались наградой "Почетный знак "Белкоопсоюза".</p>
                            <p>В учреждении образования за время его существования подготовлено более 30 000 специалистов со средним специальным образованием и более 11 000 работников массовых профессий.</p>
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
 
                                            <li><a href="https://vk.com/id322365773" target="_blank"><i class="fa fa-vk"></i></a></li>
                                            <li><a href="/dopage/vesti"><i class="fa fa-newspaper-o"></i></a></li>
                                            <li><a href="tel:80232337002"><i class="fa fa-phone"></i></a></li>
											<li><a href="https://www.youtube.com/channel/UC4gAJzPJcC9gkXnDDbKoUFw" target="_blank"><i class="fa fa-youtube"></i></a></li>
                                        </ul>
                                    </nav>

                                </div>
                            </div>
                            <div class="col-md-6" style="height: 100%; margin-top: 20px; display: inline-block;">
                                <div class="c-container c-first">
                                    <div style="text-align: center;">
                                        <p class="c-font-grey">2022 © ГТЭК
                                            <span style="color: darkseagreen;">Зарегистрирован в Гос. регистре информационных ресурсов Беларуси </span>13.08.2013 г. ИР 5141303587.
                
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
										<p>принимаются сообщения </p>
										<p> из доменов .by и .ru </p>
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
            <script src="/assets/js/jquery.min.js"></script>
            <script src="/assets/js/jquery-migrate.min.js"></script>
            <script src="/assets/js/fancybox.umd.js"></script>
            <script src="/assets/js/bootstrap.js"></script>
            <script src="/assets/js/jquery.back-to-top.js"></script>
            <script src="/assets/js/gtec.js"></script>
            <script src="/assets/js/chief-slider/chief-slider.js"></script>
            <script src="/assets/js/slick.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $(".vertical-center-4").slick({
                        dots: true,
                        arrows: false,
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 3000,
                        responsive: [{
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                    infinite: true,
                                    dots: true
                                }
                            },
                            {
                                breakpoint: 600,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                });

            </script>
            </body>

            </html>
