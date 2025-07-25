<?php 
    $page_title = "Фотографии"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="foto-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">📸</span>
            Фотографии
        </h1>
        <p class="hero-subtitle">Моменты жизни нашего колледжа</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <?php if($empty1 == (int)0): ?>
            <div class="empty-state">
                <div class="empty-icon">📷</div>
                <h3>Альбомы временно недоступны</h3>
                <p>На данный момент нет фотоальбомов</p>
            </div>
        <?php else: ?>
            <div class="photo-albums">
                <div class="albums-grid">
                    <?php foreach($photos as $item): ?>
                        <div class="album-card">
                            <div class="album-image-container">
                                <img src="/<?php echo stristr($item['photos_images'], ':', true); ?>" 
                                     alt="<?php echo htmlspecialchars($item['photos_title']) ?>" 
                                     class="album-cover" loading="lazy">
                                <div class="album-overlay">
                                    <a href="/<?php echo stristr($item['photos_images'], ':', true); ?>" 
                                       data-fancybox="gallery" class="zoom-btn">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="album-info">
                                <h4 class="album-title">
                                    <a href="/kol/foto/view/mane/<?php echo $item['photos_id'] ?>">
                                        <?php echo $item['photos_title'] ?>
                                    </a>
                                </h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if(!empty($pagination)): ?>
                <div class="pagination-wrapper">
                    <?php echo $pagination ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?> 