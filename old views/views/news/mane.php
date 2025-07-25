<?php echo $header ?>
<div style="display: table; width: 100%;">
    <h2>Новости</h2>
    <hr> 
    <?php if(empty($news)): ?>
        <div style="width: 100%; display: table; text-align: center; margin-top: 20px; margin-bottom: 20px;">На данный момент нет новостей</div>
    <?php else: ?>
    <?php foreach($news as $item): ?>
    <div class="col-md-12">
        <article class="news">
            <div class="col-md-4 news-img">
                <div class="c-content-overlay">
                    <div class="c-overlay-wrapper">
                        <div class="c-overlay-content">
                            <a href="/<?php echo $item['news_image'] ?>" data-fancybox="gallery" data-caption="<?php echo $item['news_title'] ?>">
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
<?php echo $footer ?>