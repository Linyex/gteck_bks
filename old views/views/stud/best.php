<?php echo $header ?> 
<h2>Лучшие учащиеся</h2>
<hr>
<?php if($empty1 == (int)0): ?>
<div style="padding-bottom: 20px; display: table; width: 100%;">
    <p style="text-align: center;">На данный момент нет фотографий</p>
</div>
<?php else: ?>
<div style="padding-bottom: 20px; display: table; width: 100%;">
    <div class="photos">

        <?php foreach($images as $item): ?>
        <div class="works">
            <div class="c-content-overlay">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                        <a href="/<?php echo $item['images_file'] ?>" data-fancybox="gallery">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/<?php echo $item['images_file'] ?>'); height: 350px;"></div>
            </div>
            <div class="save-all" style="width: 100%;">
                <p style="text-transform: uppercase; text-align: center; padding-top: 10px;"><?php echo $item['images_text'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<?php endif; ?>



<?php echo $footer ?> 