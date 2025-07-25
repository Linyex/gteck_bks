<?php echo $header ?>
<h2>Просмотр фотографий из альбома "<?php echo($photos['photos_title']) ?>"</h2>
<hr>
<?php if (!empty($photos)): ?>
    <?php if (!empty($massfiles)): ?>
    <div class="col-md-12" style="padding: 0; display: inline-block;">
        <div style="padding-top: 10px; padding-bottom: 20px; display: table; width: 100%;">
            <div class="photos">
                <?php foreach($massfiles as $item): ?>
                <?php if (!empty($item)): ?>
                <div class="works">
                    <div class="c-content-overlay">
                        <div class="c-overlay-wrapper">
                            <div class="c-overlay-content">
                                <a href="/<?php echo $item ?>" data-fancybox="gallery">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                        </div>
                        <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/<?php echo $item ?>');"></div>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>
<?php echo $footer ?>
