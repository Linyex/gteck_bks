<!-- Hero Section для ошибки -->
<section class="error-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title">❌ Ошибка</h1>
                    <p class="hero-subtitle">Что-то пошло не так, но мы поможем вам вернуться на правильный путь</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Основной контент -->
<div class="c-layout-page">
    <div class="container">
        <div class="content-section" data-aos="fade-up">
            <div class="error-container">
                <div class="error-code">
                    <?php echo isset($errorCode) ? $errorCode : "404"; ?>
                </div>
                <div class="error-title">
                    <?php echo isset($errorCode) ? "Ошибка " . $errorCode : "Страница не найдена"; ?>
                </div>
                <div class="error-message">
                    <?php echo isset($message) ? $message : "Возможно вы нажали на неверную ссылку или ввели неверный URL-адрес."; ?>
                </div>
                <div class="error-actions">
                    <a href="/" class="error-btn primary">
                        <i class="fa fa-home"></i>
                        Вернуться на главную
                    </a>
                    <a href="javascript:history.back()" class="error-btn secondary">
                        <i class="fa fa-arrow-left"></i>
                        Назад
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

