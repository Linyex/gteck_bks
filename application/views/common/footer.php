            <!-- END: PAGE CONTENT -->
            </div>
            
            <!-- Banner Slider -->
            <div class="banner-section" data-aos="fade-up" style="background: var(--glass-bg); backdrop-filter: var(--glass-backdrop); border-radius: var(--radius-xl); margin: var(--space-8) 0; padding: var(--space-8); box-shadow: var(--shadow-soft); width: 100%; max-width: none;">
                <div class="container-fluid">
                    <h3 class="section-title">Информационные баннеры</h3>
<?php
try {
    require_once ENGINE_DIR . 'main/db.php';
    require_once APPLICATION_DIR . 'models/BannerModel.php';
    $bm = new BannerModel();
    $footerBanners = $bm->listPublic();
} catch (Exception $e) {
    $footerBanners = [];
}
?>
<?php if (!empty($footerBanners)): ?>
                    <div class="banner-slider">
<?php $i=0; foreach ($footerBanners as $b): ?>
                        <div class="banner-item<?php echo $i===0 ? ' active' : ''; ?>">
                            <a href="<?php echo htmlspecialchars($b['link_url'] ?: '#'); ?>" target="_blank">
                                <div class="banner-image" style="background-image: url(<?php echo htmlspecialchars($b['image_path']); ?>);"></div>
                            </a>
                        </div>
<?php $i++; endforeach; ?>
                    </div>
                    <!-- Banner Navigation -->
                    <div class="banner-nav">
<?php for ($j=0; $j<$i; $j++): ?>
                        <div class="banner-dot<?php echo $j===0 ? ' active' : ''; ?>" data-slide="<?php echo $j; ?>"></div>
<?php endfor; ?>
                    </div>
<?php endif; ?>
                </div>
            </div>

            <!-- College Info -->
            <div class="college-info" data-aos="fade-up" style="background: var(--glass-bg); backdrop-filter: var(--glass-backdrop); border-radius: var(--radius-xl); margin: var(--space-8) 0; padding: var(--space-8); box-shadow: var(--shadow-soft);">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="section-title">Краткая информация о колледже</h2>
                            <hr class="section-divider">
                            <div class="info-content">
                                <p>Cтарейшее учебное заведение потребительской кооперации Республики Беларусь. Свою историю колледж ведет с 1944 года. Сегодня колледж – это современное учреждение с обновленной материальной базой, квалифицированными педагогическими кадрами, обеспечивающими должное качеству обучения будущих бухгалтеров, экономистов, товароведов, программистов, юристов. Успехи, достигнутые колледжем в подготовке кадров, трижды отмечались наградой "Почетный знак "Белкоопсоюза".</p>
                                <p>В учреждении образования за время его существования подготовлено более 30 000 специалистов со средним специальным образованием и более 11 000 работников массовых профессий.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="modern-footer">
                <div class="container">
                    <div class="row">
                        <!-- Информация о колледже -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="footer-logo">
                                <h4 class="footer-heading">Гомельский торгово-экономический колледж</h4>
                            </div>
                            <p class="footer-text">
                                Старейшее учебное заведение потребительской кооперации Республики Беларусь. 
                                Свою историю колледж ведет с 1944 года.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-link">
                                    <i class="fa fa-vk"></i>
                                </a>
                                <a href="#" class="social-link">
                                    <i class="fa fa-telegram"></i>
                                </a>
                                <a href="#" class="social-link">
                                    <i class="fa fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link">
                                    <i class="fa fa-youtube"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Абитуриентам -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <h4 class="footer-heading">Абитуриентам</h4>
                            <ul class="footer-links">
                                <li><a href="/abut">О колледже</a></li>
                                <li><a href="/abut/spec">Специальности</a></li>
                                <li><a href="/abut/vups">ВУПС</a></li>
                                <li><a href="/abut/plan">План приема</a></li>
                                <li><a href="/abut/prof">Профориентация</a></li>
                                <li><a href="/abut/sroki">Сроки подачи</a></li>
                            </ul>
                        </div>

                        <!-- Учащимся -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <h4 class="footer-heading">Учащимся</h4>
                            <ul class="footer-links">
                                <li><a href="/stud">Учебный раздел</a></li>
                                <li><a href="/stud/kontrolnui">Контрольные работы</a></li>
                                <li><a href="/stud/library">Библиотека</a></li>
                                <li><a href="/stud/hostel">Общежитие</a></li>
                                <li><a href="/stud/ymk">УМК</a></li>
                                <li><a href="/stud/dnevnoe">Дневное отделение</a></li>
                            </ul>
                        </div>

                        <!-- Контакты -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <h4 class="footer-heading">Контакты</h4>
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fa fa-map-marker"></i>
                                    <span>246017, г. Гомель, ул. Привокзальная, 4</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fa fa-phone"></i>
                                    <span>+375 232 33-70-02</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fa fa-phone"></i>
                                    <span>Приемная комиссия: +375 232 20-22-14</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fa fa-envelope"></i>
                                    <span>gtec@mail.gomel.by</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fa fa-clock-o"></i>
                                    <span>Пн-Сб: 9:00 - 18:00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Нижняя часть футера -->
                    <div class="footer-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="copyright">
                                    © 2025 Гомельский торгово-экономический колледж. Все права защищены.
                                </p>
                            </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="footer-bottom-links">
                                    <a>Сайт разработан - Тозиком Данилой Андреевичем</a>
                                    <a href="https://github.com/Linyex" class="link-git link-animated"><i class="fa fa-github"></i>GitHub</a>
                                    <a href="https://t.me/Nerso_LS" class="link-telegram link-animated"><i class="fa fa-telegram"></i>Telegram</a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Оптимизированные JS Scripts -->
            <script src="/assets/js/gtec.js" defer></script>
            <script src="/assets/js/optimized.js?v=1.2.0" defer></script>
            <?php if (!empty($settings['enable_lazy_loading'])): ?>
            <script src="/assets/js/lazy-loading.js?v=1.2.0" defer></script>
            <?php endif; ?>
            <script src="/assets/js/main-page.js" defer></script>

            <?php if (!empty($settings['enable_service_worker'])): ?>
            <script>
                if ('serviceWorker' in navigator) {
                    window.addEventListener('load', function() {
                        navigator.serviceWorker.register('/sw.js').catch(function(err){
                            console.warn('SW registration failed', err);
                        });
                    });
                }
            </script>
            <?php endif; ?>
            
            <script type="text/javascript">
                $(document).ready(function() {
                    // Banner slider functionality
                    let currentBanner = 0;
                    const banners = document.querySelectorAll('.banner-item');
                    const dots = document.querySelectorAll('.banner-dot');
                    const totalBanners = banners.length;

                    function showBanner(index) {
                        banners.forEach((banner, i) => {
                            banner.classList.remove('active');
                            if (i === index) {
                                banner.classList.add('active');
                            }
                        });
                        
                        dots.forEach((dot, i) => {
                            dot.classList.remove('active');
                            if (i === index) {
                                dot.classList.add('active');
                            }
                        });
                    }

                    function nextBanner() {
                        currentBanner = (currentBanner + 1) % totalBanners;
                        showBanner(currentBanner);
                    }

                    // Auto-advance banners every 5 seconds
                    if (totalBanners > 1) {
                        setInterval(nextBanner, 5000);
                    }

                    // Click on dots to change banner
                    dots.forEach((dot, index) => {
                        dot.addEventListener('click', () => {
                            currentBanner = index;
                            showBanner(currentBanner);
                        });
                    });

                    // Show first banner
                    showBanner(0);

                    // Hero slider
                    let currentSlide = 0;
                    const slides = document.querySelectorAll('.slider-item');
                    const totalSlides = slides.length;

                    function showSlide(index) {
                        slides.forEach((slide, i) => {
                            slide.classList.remove('active');
                            if (i === index) {
                                slide.classList.add('active');
                            }
                        });
                    }

                    function nextSlide() {
                        currentSlide = (currentSlide + 1) % totalSlides;
                        showSlide(currentSlide);
                    }

                    // Auto-advance slides
                    if (totalSlides > 1) {
                        setInterval(nextSlide, 5000);
                    }

                    // Show first slide
                    showSlide(0);

                    // Back to top functionality
                    $('.js-back-to-top').on('click', function() {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 800);
                    });
                });
            </script>
        </body>
        </html>
