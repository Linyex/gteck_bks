<?php echo $header ?>
<h2>Просмотр новости</h2>
<hr>
<div class="col-md-12" style="padding: 0; display: inline-block;">
    <div class="col-md-12">
        <article class="news pp3" style="margin-bottom: 0; border-bottom: 0;">
            <div class="col-md-4" style="padding: 0;">
                <div class="c-content-overlay">
                    <div class="c-overlay-wrapper">
                        <div class="c-overlay-content">
                            <a href="/<?php echo $news['news_image'] ?>" data-fancybox="gallery" data-caption="<?php echo $news['news_title'] ?>">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    </div>
                    <img class="c-overlay-object img-responsive" src="/<?php echo $news['news_image'] ?>" alt="">
                </div>
            </div>
            <div class="col-md-8 news-info">
                <div class="news-title" style="width: 100%;">
                    <a href="#"><?php echo $news['news_title'] ?></a>
                </div>
                <div class="news-time">
                    <span>Опубликовано: <time datetime=""><?php echo date("d.m", strtotime($news['news_date_add'])) ?>.<?php echo date("Y", strtotime($news['news_date_add'])) ?></time></span>
                </div>
                <div class="news-category">
                    <a href="/news/category/mane/<?php echo $news['category_name'] ?>"><?php echo $news['category_text'] ?></a>
                </div>

                <div class="col-md-12" style="padding: 0;">
                    <p style="text-indent: 25px;" align="justify"><?php echo htmlspecialchars_decode($news['news_text']) ?></p>
                </div>
            </div>
        </article>
        <?php if (!empty($newsphoto)): ?>
        <h2>Фотоотчет</h2>
        <hr>
        <div style="padding-top: 10px; padding-bottom: 20px; display: table; width: 100%;">
            <div class="photos">
                <?php foreach($newsphoto as $item): ?>
                <div class="works">
                    <div class="c-content-overlay">
                        <div class="c-overlay-wrapper">
                            <div class="c-overlay-content">
                                <a href="/<?php echo $item['news_img'] ?>" data-fancybox="gallery">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                        </div>
                        <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/<?php echo $item['news_img'] ?>');"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php echo $footer ?>
