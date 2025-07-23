// ===== JAVASCRIPT ДЛЯ HEADER =====

document.addEventListener('DOMContentLoaded', function() {
    
    // Инициализация анимаций загрузки
    initHeaderAnimations();
    
    // Обработка скролла для изменения стиля шапки
    initScrollEffects();
    
    // Обработка hover эффектов
    initHoverEffects();
    
    // Обработка dropdown меню
    initDropdownEffects();
    
    // Обработка языковых флагов
    initLanguageFlags();
    
    // Обработка кнопки для слабовидящих
    initAccessibilityButton();
    
    // Обработка мобильного меню
    initMobileMenu();
    
    // Обработка ошибок загрузки изображений
    initImageErrorHandling();
});

// Анимации загрузки
function initHeaderAnimations() {
    const header = document.querySelector('.c-layout-header');
    const brandContainer = document.querySelector('.navbar-brand-container');
    const navbarNav = document.querySelector('.navbar-nav');
    const headerActions = document.querySelector('.header-actions');
    
    if (header) {
        header.classList.add('loaded');
    }
    
    if (brandContainer) {
        brandContainer.classList.add('fade-in');
    }
    
    if (navbarNav) {
        navbarNav.classList.add('fade-in');
    }
    
    if (headerActions) {
        headerActions.classList.add('fade-in');
    }
}

// Эффекты скролла
function initScrollEffects() {
    const header = document.querySelector('.c-layout-header');
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (header) {
            if (scrollTop > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
        
        lastScrollTop = scrollTop;
    });
}

// Hover эффекты
function initHoverEffects() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.classList.add('hovered');
        });
        
        link.addEventListener('mouseleave', function() {
            this.classList.remove('hovered');
        });
    });
}

// Dropdown эффекты
function initDropdownEffects() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const dropdownMenu = this.nextElementSibling;
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Закрываем все другие dropdown
            dropdownToggles.forEach(otherToggle => {
                if (otherToggle !== this) {
                    otherToggle.setAttribute('aria-expanded', 'false');
                    const otherMenu = otherToggle.nextElementSibling;
                    if (otherMenu) {
                        otherMenu.classList.remove('show');
                    }
                }
            });
            
            // Переключаем текущий dropdown
            if (isExpanded) {
                this.setAttribute('aria-expanded', 'false');
                dropdownMenu.classList.remove('show');
            } else {
                this.setAttribute('aria-expanded', 'true');
                dropdownMenu.classList.add('show');
            }
        });
    });
    
    // Закрытие dropdown при клике вне его
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            dropdownToggles.forEach(toggle => {
                toggle.setAttribute('aria-expanded', 'false');
                const dropdownMenu = toggle.nextElementSibling;
                if (dropdownMenu) {
                    dropdownMenu.classList.remove('show');
                }
            });
        }
    });
}

// Языковые флаги
function initLanguageFlags() {
    const flags = document.querySelectorAll('.flag');
    
    flags.forEach(flag => {
        flag.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Убираем активный класс у всех флагов
            flags.forEach(f => f.classList.remove('clicked'));
            
            // Добавляем активный класс к текущему флагу
            this.classList.add('clicked');
            
            // Анимация клика
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            
            // Здесь можно добавить логику смены языка
            console.log('Язык изменен на:', this.alt);
        });
    });
}

// Кнопка для слабовидящих
function initAccessibilityButton() {
    const accessibilityBtn = document.querySelector('.accessibility-btn');
    
    if (accessibilityBtn) {
        accessibilityBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Анимация клика
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 150);
            
            // Здесь можно добавить логику для слабовидящих
            console.log('Активирована версия для слабовидящих');
            
            // Пример: увеличение размера шрифта
            const currentFontSize = parseInt(getComputedStyle(document.body).fontSize);
            const newFontSize = currentFontSize + 2;
            document.body.style.fontSize = newFontSize + 'px';
        });
    }
}

// Мобильное меню
function initMobileMenu() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            this.classList.toggle('active');
            
            // Анимация иконки
            const icon = this.querySelector('.navbar-toggler-icon');
            if (icon) {
                icon.style.transform = this.classList.contains('active') ? 'rotate(90deg)' : 'rotate(0deg)';
            }
        });
        
        // Закрытие мобильного меню при клике на ссылку
        const mobileLinks = navbarCollapse.querySelectorAll('.nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    navbarCollapse.classList.remove('show');
                    navbarToggler.classList.remove('active');
                    const icon = navbarToggler.querySelector('.navbar-toggler-icon');
                    if (icon) {
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            });
        });
    }
}

// Обработка ошибок загрузки изображений
function initImageErrorHandling() {
    const images = document.querySelectorAll('img');
    
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.style.display = 'none';
            
            // Показываем fallback для логотипа
            if (this.classList.contains('logo')) {
                const fallback = this.parentElement.querySelector('.logo-fallback');
                if (fallback) {
                    fallback.style.display = 'block';
                }
            }
        });
    });
}

// Дополнительные утилиты
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Оптимизированная обработка скролла
const optimizedScrollHandler = debounce(function() {
    const header = document.querySelector('.c-layout-header');
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (header) {
        if (scrollTop > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
}, 10);

window.addEventListener('scroll', optimizedScrollHandler);

// Обработка изменения размера окна
window.addEventListener('resize', debounce(function() {
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');
    
    if (window.innerWidth > 992) {
        if (navbarCollapse) {
            navbarCollapse.classList.remove('show');
        }
        if (navbarToggler) {
            navbarToggler.classList.remove('active');
            const icon = navbarToggler.querySelector('.navbar-toggler-icon');
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
            }
        }
    }
}, 250));

// Экспорт функций для возможного использования в других скриптах
window.HeaderUtils = {
    initHeaderAnimations,
    initScrollEffects,
    initHoverEffects,
    initDropdownEffects,
    initLanguageFlags,
    initAccessibilityButton,
    initMobileMenu,
    initImageErrorHandling
}; 