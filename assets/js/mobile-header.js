// ===== МОБИЛЬНОЕ МЕНЮ =====

document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileNavClose = document.getElementById('mobile-nav-close');
    const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
    const mobileDropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
    
    // Открытие мобильного меню
    function openMobileMenu() {
        mobileNav.classList.add('active');
        mobileMenuToggle.classList.add('active');
        mobileNavOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Блокируем скролл
    }
    
    // Закрытие мобильного меню
    function closeMobileMenu() {
        mobileNav.classList.remove('active');
        mobileMenuToggle.classList.remove('active');
        mobileNavOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Возвращаем скролл
        
        // Закрываем все открытые dropdown
        mobileDropdownToggles.forEach(toggle => {
            toggle.classList.remove('active');
            const dropdown = toggle.nextElementSibling;
            if (dropdown && dropdown.classList.contains('mobile-dropdown')) {
                dropdown.classList.remove('active');
            }
        });
    }
    
    // Обработчики событий
    mobileMenuToggle.addEventListener('click', openMobileMenu);
    mobileNavClose.addEventListener('click', closeMobileMenu);
    mobileNavOverlay.addEventListener('click', closeMobileMenu);
    
    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
            closeMobileMenu();
        }
    });
    
    // Обработка dropdown в мобильном меню
    mobileDropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const dropdown = this.nextElementSibling;
            const isActive = this.classList.contains('active');
            
            // Закрываем все другие dropdown
            mobileDropdownToggles.forEach(otherToggle => {
                if (otherToggle !== this) {
                    otherToggle.classList.remove('active');
                    const otherDropdown = otherToggle.nextElementSibling;
                    if (otherDropdown && otherDropdown.classList.contains('mobile-dropdown')) {
                        otherDropdown.classList.remove('active');
                    }
                }
            });
            
            // Переключаем текущий dropdown
            this.classList.toggle('active');
            if (dropdown && dropdown.classList.contains('mobile-dropdown')) {
                dropdown.classList.toggle('active');
            }
        });
    });
    
    // Закрытие меню при клике на ссылку
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link:not(.mobile-dropdown-toggle)');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Не закрываем если это dropdown toggle
            if (!this.classList.contains('mobile-dropdown-toggle')) {
                closeMobileMenu();
            }
        });
    });
    
    // Адаптация при изменении размера окна
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            closeMobileMenu();
        }
    });
    
    // Плавная прокрутка для якорных ссылок
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                closeMobileMenu();
            }
        });
    });
    
    // Добавляем анимацию для кнопки меню
    mobileMenuToggle.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1)';
    });
    
    mobileMenuToggle.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
    
    // Улучшенная доступность
    mobileMenuToggle.setAttribute('aria-expanded', 'false');
    mobileMenuToggle.addEventListener('click', function() {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
    });
    
    // Фокус-ловушка для мобильного меню
    mobileNav.addEventListener('keydown', function(e) {
        const focusableElements = mobileNav.querySelectorAll(
            'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select'
        );
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
    });
    
    // Добавляем haptic feedback для мобильных устройств
    if ('vibrate' in navigator) {
        mobileMenuToggle.addEventListener('click', function() {
            navigator.vibrate(50);
        });
        
        mobileNavClose.addEventListener('click', function() {
            navigator.vibrate(50);
        });
    }
    
    console.log('Мобильное меню инициализировано');
}); 