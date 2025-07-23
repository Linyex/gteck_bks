// ===== ОПТИМИЗИРОВАННЫЙ JAVASCRIPT GTEC =====

// Регистрация Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('Service Worker зарегистрирован:', registration);
            })
            .catch(error => {
                console.log('Ошибка регистрации Service Worker:', error);
            });
    });
}

// Современный JavaScript без jQuery
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация всех компонентов
    initOptimizations();
    initLazyLoading();
    initAnimations();
    initSearch();
    initNewsSlider();
});

// Основная функция оптимизации
function initOptimizations() {
    // Preload критических изображений
    const criticalImages = document.querySelectorAll('img[data-src]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        criticalImages.forEach(img => imageObserver.observe(img));
    }
    
    // Оптимизация скролла
    let ticking = false;
    function updateOnScroll() {
        if (!ticking) {
            requestAnimationFrame(() => {
                // Обновление элементов при скролле
                ticking = false;
            });
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', updateOnScroll, { passive: true });
}

// Lazy loading для изображений
function initLazyLoading() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    if ('loading' in HTMLImageElement.prototype) {
        // Браузер поддерживает нативный lazy loading
        images.forEach(img => {
            if (img.dataset.src) {
                img.src = img.dataset.src;
            }
        });
    } else {
        // Fallback для старых браузеров
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
}

// Оптимизированные анимации
function initAnimations() {
    // Используем Intersection Observer для анимаций
    const animatedElements = document.querySelectorAll('[data-aos]');
    
    if ('IntersectionObserver' in window) {
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                    animationObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        animatedElements.forEach(el => animationObserver.observe(el));
    }
}

// Оптимизированный поиск
function initSearch() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('Search_text');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchForm || !searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(this.value);
        }, 300);
    });
    
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch(searchInput.value);
    });
}

// Оптимизированный поиск с debounce
function performSearch(query) {
    if (query.length < 3) return;
    
    fetch('/main/ajax', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `Search_text2=${encodeURIComponent(query)}`
    })
    .then(response => response.json())
    .then(data => {
        displaySearchResults(data);
    })
    .catch(error => {
        console.error('Search error:', error);
    });
}

// Отображение результатов поиска
function displaySearchResults(data) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults) return;
    
    if (data.status === 'success') {
        searchResults.innerHTML = data.success.map(result => 
            `<div class="search-result-item">
                <a href="${result.silks}">${result.title}</a>
                <p>${result.fullstr}</p>
            </div>`
        ).join('');
    } else {
        searchResults.innerHTML = `<div class="search-error">${data.error}</div>`;
    }
}

// Оптимизированный слайдер новостей
function initNewsSlider() {
    const slides = document.querySelectorAll('.news-slide');
    const dots = document.querySelectorAll('.news-dot');
    const prevBtn = document.getElementById('newsPrev');
    const nextBtn = document.getElementById('newsNext');
    
    if (!slides.length) return;
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        
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
    
    // Автоматическое переключение
    if (totalSlides > 1) {
        setInterval(nextSlide, 8000);
    }
    
    // Обработчики событий
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
    
    showSlide(0);
}

// Утилиты для оптимизации
const OptimizedUtils = {
    // Debounce функция
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Throttle функция
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },
    
    // Проверка поддержки WebP
    supportsWebP() {
        return new Promise(resolve => {
            const webP = new Image();
            webP.onload = webP.onerror = function() {
                resolve(webP.height === 2);
            };
            webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
        });
    }
};

// Экспорт для использования в других файлах
window.OptimizedUtils = OptimizedUtils; 