<?php echo $header ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slider = new ChiefSlider('.slider', {
            loop: true,
            autoplay: true,
            interval: 7000,
        });
    });
</script>
<div style="display: table; width: 100%;">
<div class="col-md-12" style="margin-bottom: 20px; padding: 0;">
    <div class="col-md-8" style="padding: 0; ">
        <div style="width: 100%;height: 25px;background-image: url(/assets/media/fon.png);background-size: contain;height: 2;opacity: 0.7;"></div>
        <div class="slider">
            <div class="slider__wrapper">
            <div class="slider__items">

            <div class="slider__item">
                <img src="/assets/media/priem.png" alt="">
            </div>
                    <div class="slider__item">
                        <img src="/assets/media/kolledj.jpg" alt="">
                        <span class="slider__content_section">Учреждение образования
                                "Гомельский торгово-экономический колледж"
                                Белкоопсоюза</span>
                    </div>
                    
                    <div class="slider__item"> <img src="/assets/media/img/slider/belka.jpg" alt="">
                        <span class="slider__content_section" id="speci">Обучение по специальностям: <br>1. Правоведение <br>2. Торговая деятельность<br>3. Бух. учет, анализ и контроль<br>4. Планово-экономическая и аналитическая деятельность <br>5. Разработка и сопровождение программного обеспечения информационных систем</span>
                    </div>
                    
                </div>
            </div>
            <a href="#" class="slider__control" data-slide="prev"></a>
            <a href="#" class="slider__control" data-slide="next"></a>
            <ol class="slider__indicators">
                <li data-slide-to="0"></li>
                <li data-slide-to="1"></li>
                <li data-slide-to="2"></li>
            </ol>
        </div>
    </div>
    <div class="poisk-g2 zero2">
        <div class="poisk-group-2">
            <div id="showtitle2"></div>
            <form id="searchForm2" method="POST">
                <input type="text" name="Search_text2" id="Search_text2" class="forma" placeholder="Поиск по сайту..">
                <button class="fa fa-search" style="color: #fff;"></button>
            </form>

            <div class="search-mini" id="showres2">

            </div>
        </div>
    </div>
    <script>
        $('#searchForm2').ajaxForm({
            url: '/main/mane/ajax',
            dataType: 'json',
            success: function(data) {
                switch (data.status) {
                    case 'error':
                        $("#showres2").html("");
                        var blocks = document.getElementById("showres2");
                        blocks.setAttribute("style", "display: none;")
                        $("#showtitle2").html("<p>" + data.error + "</p>");
                        break;
                    case 'success':
                        $("#showres2").html("");
                        var blocks = document.getElementById("showres2");
                        blocks.setAttribute("style", "display: block;")
                        var total = data.success[0]['total'];
                        $("#showtitle2").html("<p>" + "По запросу '" + data.success[0]['stroka'] + "', было найдено " + data.success[0]['total'] + " похожих результатов:" + "</p>");
                        for (var i = 0; i <= total; i++) {
                            $("#showres2").append("<div id='pi"+ (i+1) + "' class='news-about'><p>"+ (i+1) + ". "+ data.success[i]['fullstr'] + "<a href='/"+ data.success[i]['silks'] + "' target='_blank'>Подробнее</a></p></div>");
                        }
                        break;
                }
            },
            beforeSubmit: function(arr, $form, options) {
                $('button[type=submit]').prop('disabled', true);
            }
        });
    </script>
    <div class="col-mdi-4 block-slide">
    <div class="mobile-side-buttons">
        <div class="header-mobi" style="margin-top: 0;">Дополнительная информация</div>
        <div>
        <?php if (!empty($lastzamena['zamena_file'])): ?>
            <a href="/<?php echo ($lastzamena['zamena_file']) ?> " target="_blank">Изменения в расписании <br><?php echo $lastzamena['zamena_text'] ?></a>
        <?php else: ?>
            <a style="text-align: center;">Изменений в <br> расписании нет</a>
        <?php endif; ?>
        
        </div>

        <div>
            <a href="https://docs.google.com/spreadsheets/d/1YGUg5U5KBQWBqi88gi8SCAEQTnf_CtDZs_U1usbUj7o/edit?usp=sharing"> Ход приёма <br> документов</a>
        </div>
            
        <!--
        <div>
            <a href="http://178.124.196.1:8881/stat2/hs/hsgetstat/allstat/?unp=400058708" target="_blank">Ход подачи <br> документов </a>
        </div> -->
        <div>
            <a href="/assets/files/№ 88 от 27.03.2025 Об утверждении Порядка приема на 2025 год.pdf" target="_blank">Порядок приёма <br> абитуриентов 2025 год</a>
        
        </div>

    </div>
        <div class="header-mobi" style="margin-top: 0;">Контакты</div>
        <div class="mobile-side-buttons" style="text-align: center;">
           Адрес колледжа: 246017, г. Гомель, ул. Привокзальная, 4 <br>E-mail: gtec@mail.gomel.by<br>Письма принимаются только с доменов ru,by<br>Телефон/факс (приемная директора): 8(0232) 33-70-02 
           <br>Приемная комиссия: +375 232 20-22-14, 8(0232) 33-70-03
        </div>
        
    <div>
            <a>
        <div></div>
            </a>
        </div>
    </div>

    </div>
<h2>Новости</h2>
<hr>
<div class="col-md-12" style="padding: 0; display: inline-block;">
    <?php if(empty($news)): ?>
        <div style="width: 100%; display: table; text-align: center; margin-top: 20px; margin-bottom: 20px;">На данный момент нет новостей</div>
    <?php else: ?>
    <?php foreach($news as $item): ?>
    <div class="col-md-12">
        <article class="news">
            <div class="col-mdn-4 news-img">
                <div class="c-content-overlay">
                    <div class="c-overlay-wrapper">
                        <div class="c-overlay-content">
                            <a href="/<?php echo $item['news_image'] ?>" data-fancybox="gallery">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    </div>
                    <img class="c-overlay-object img-responsive" src="/<?php echo $item['news_image'] ?>" alt="">
                </div>
            </div>
            <div class="col-md-8 news-info">
                <div class="news-title">
                    <a href="/news/view/mane/<?php echo $item['news_id'] ?>"><?php echo $item['news_title'] ?></a>
                </div>
                <div class="news-time">
                    <span>Опубликовано: <time datetime=""><?php echo date("d.m", strtotime($item['news_date_add'])) ?>.<?php echo date("Y", strtotime($item['news_date_add'])) ?></time></span>
                </div>
                <div class="news-category">
                    <a href="/news/category/mane/<?php echo $item['category_name'] ?>"><?php echo $item['category_text'] ?></a>
                </div>

                <div class="col-md-12" style="padding: 0;">
                    <p style="text-indent: 25px;" align="justify">
                    <p style="text-indent: 25px;" align="justify">
                       <?php $string = mb_substr($item['news_text'], 0, 250);
				             $string = rtrim($string, "!,.-");
				             echo htmlspecialchars_decode($string) . " …"; ?></p>
                </div>
                <div class="news-about">
                    <a href="/news/view/mane/<?php echo $item['news_id'] ?>">Подробнее</a>
                </div>
            </div>
        </article>
    </div>
    <?php endforeach; ?>
    <div class="bottom-nav" id="bottom-nav">
        <?php echo $pagination ?> 
    </div>
    <?php endif; ?>

</div>
</div>
<?php echo $footer ?>