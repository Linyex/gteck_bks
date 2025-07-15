<?php echo $header ?> 
<h2>Фотографии</h2>
<hr>
<?php if($empty1 == (int)0): ?>
<div style="padding-bottom: 20px; display: table; width: 100%;">
    <p style="text-align: center;">На данный момент нет альбомов</p>
</div>
<?php else: ?>
<div style="padding-bottom: 20px; display: table; width: 100%;">
    <div class="photos" style="display: flex; align-items: flex-start; flex-flow: wrap;">

        <?php foreach($photos as $item): ?>
        <div class="works">
            <div class="c-content-overlay">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                        <a href="/<?php echo stristr($item['photos_images'], ':', true); ?>" data-fancybox="gallery">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/<?php echo stristr($item['photos_images'], ':', true); ?>'); height: 250px;"></div>
            </div>
                <div class="news-about" style="width: 100%; text-align: center; display: table; padding: 10px;">
                    <a href="/kol/foto/view/mane/<?php echo $item['photos_id'] ?>" style="width: 100%; text-transform: uppercase; font-size: 16px;"><?php echo $item['photos_title'] ?></a>
                </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>
<div class="bottom-nav" id="bottom-nav">
    <?php echo $pagination ?> 
</div>
<?php echo $footer ?> 