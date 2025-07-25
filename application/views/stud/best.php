<?php 
    $page_title = "Лучшие учащиеся"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="best-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">🏆</span>
            Лучшие учащиеся
        </h1>
        <p class="hero-subtitle">Наши выдающиеся студенты</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <?php if($empty1 == (int)0): ?>
            <div class="empty-state">
                <div class="empty-icon">📷</div>
                <h3>Фотографии временно недоступны</h3>
                <p>На данный момент нет фотографий лучших учащихся</p>
            </div>
        <?php else: ?>
            <div class="photos-gallery">
                <div class="gallery-grid">
                    <?php foreach($images as $item): ?>
                        <div class="gallery-item">
                            <div class="image-container">
                                <img src="/<?php echo $item['images_file'] ?>" alt="<?php echo htmlspecialchars($item['images_text']) ?>" class="gallery-image" loading="lazy">
                                <div class="image-overlay">
                                    <a href="/<?php echo $item['images_file'] ?>" data-fancybox="gallery" class="zoom-btn">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="image-caption">
                                <h4><?php echo $item['images_text'] ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>



 