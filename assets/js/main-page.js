// ===== JAVASCRIPT ДЛЯ ГЛАВНОЙ СТРАНИЦЫ =====

$(document).ready(function() {
    // AJAX поиск
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        var searchText = $('#Search_text').val();
        
        if (searchText.length < 3) {
            $('#searchResults').html('<p style="color: #e74c3c;">Введите минимум 3 символа</p>');
            return;
        }
        
        $.ajax({
            url: '/main/ajax',
            method: 'POST',
            data: {
                Search_text2: searchText
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    var results = '';
                    data.success.forEach(function(item) {
                        results += '<div class="search-result-item">';
                        results += '<h5>' + item.title + '</h5>';
                        results += '<p>' + item.fullstr + '</p>';
                        results += '<a href="/' + item.silks + '" class="search-link">Перейти</a>';
                        results += '</div>';
                    });
                    $('#searchResults').html(results);
                } else {
                    $('#searchResults').html('<p style="color: #e74c3c;">' + data.error + '</p>');
                }
            },
            error: function() {
                $('#searchResults').html('<p style="color: #e74c3c;">Ошибка поиска</p>');
            }
        });
    });
    
    // Инициализация AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Плавная прокрутка для якорных ссылок
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });

    // Анимация статистики
    function animateStats() {
        $('.stat-number').each(function() {
            var $this = $(this);
            var countTo = $this.text();
            
            $({ countNum: 0 }).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $this.text(countTo);
                }
            });
        });
    }

    // Запуск анимации статистики при появлении в поле зрения
    var statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateStats();
                statsObserver.unobserve(entry.target);
            }
        });
    });

    $('.hero-stats').each(function() {
        statsObserver.observe(this);
    });

    // Эффект параллакса для фоновых элементов
    $(window).scroll(function() {
        var scrolled = $(this).scrollTop();
        
        $('.tech-element').each(function(index) {
            var speed = 0.5 + (index * 0.1);
            var yPos = -(scrolled * speed);
            $(this).css('transform', 'translateY(' + yPos + 'px)');
        });
    });

    // Hover эффекты для карточек
    $('.section-card, .admission-card').hover(
        function() {
            $(this).addClass('hovered');
        },
        function() {
            $(this).removeClass('hovered');
        }
    );

    // Плавное появление элементов при скролле
    function checkScroll() {
        var windowTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        
        $('.content-section').each(function() {
            var elementTop = $(this).offset().top;
            var elementHeight = $(this).height();
            
            if (elementTop < windowTop + windowHeight - 100) {
                $(this).addClass('visible');
            }
        });
    }

    $(window).scroll(checkScroll);
    checkScroll();

    // Интерактивные эффекты для кнопок
    $('.section-btn').on('mouseenter', function() {
        $(this).addClass('btn-hover');
    }).on('mouseleave', function() {
        $(this).removeClass('btn-hover');
    });

    // Анимация загрузки страницы
    $(window).on('load', function() {
        $('body').addClass('loaded');
        
        setTimeout(function() {
            $('.hero-content').addClass('fade-in');
        }, 300);
    });

    // Обработка ошибок AJAX
    $(document).ajaxError(function(event, xhr, settings, error) {
        console.error('AJAX Error:', error);
        $('#searchResults').html('<p style="color: #e74c3c;">Произошла ошибка при загрузке данных</p>');
    });

    // Оптимизация производительности
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Пересчет позиций элементов при изменении размера окна
            checkScroll();
        }, 250);
    });
}); 

// ===== СЛАЙДЕР ВАЖНОЙ ИНФОРМАЦИИ =====
function initImportantInfoSlider() {
    const slides = document.querySelectorAll('.info-slide');
    const dots = document.querySelectorAll('.info-dot');
    let currentSlide = 0;
    const totalSlides = slides.length;

    function showSlide(index) {
        // Скрываем все слайды
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
        
        // Обновляем точки навигации
        dots.forEach((dot, i) => {
            dot.classList.remove('active');
            if (i === index) {
                dot.classList.add('active');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    // Автоматическое переключение каждые 6 секунд
    if (totalSlides > 1) {
        setInterval(nextSlide, 6000);
    }

    // Клик по точкам навигации
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Показываем первый слайд
    showSlide(0);
}

// ===== СЛАЙДЕР ВАЖНЫХ НОВОСТЕЙ =====
function initImportantNewsSlider() {
    const slides = document.querySelectorAll('.news-slide');
    const dots = document.querySelectorAll('.news-dot');
    const prevBtn = document.getElementById('newsPrev');
    const nextBtn = document.getElementById('newsNext');
    let currentSlide = 0;
    const totalSlides = slides.length;

    function showSlide(index) {
        // Скрываем все слайды
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
        
        // Обновляем точки навигации
        dots.forEach((dot, i) => {
            dot.classList.remove('active');
            if (i === index) {
                dot.classList.add('active');
            }
        });
        
        // Обновляем состояние кнопок
        if (prevBtn && nextBtn) {
            prevBtn.disabled = index === 0;
            nextBtn.disabled = index === totalSlides - 1;
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }

    // Автоматическое переключение каждые 8 секунд
    if (totalSlides > 1) {
        setInterval(nextSlide, 8000);
    }

    // Клик по кнопкам навигации
    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }

    // Клик по точкам навигации
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Показываем первый слайд
    showSlide(0);
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Существующий код...
    
    // Инициализация слайдера важной информации
    initImportantInfoSlider();
    
    // Инициализация слайдера важных новостей
    initImportantNewsSlider();
}); 