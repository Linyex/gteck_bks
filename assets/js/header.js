// ===== JAVASCRIPT ДЛЯ HEADER =====

document.addEventListener('DOMContentLoaded', function() {
    
    // Инициализация анимаций загрузки
    initHeaderAnimations();
    
    // Обработка скролла для изменения стиля шапки
    initScrollEffects();
    
    // Обработка hover эффектов
    initHoverEffects();
    
    // Обработка dropdown меню (новое)
    initDropdownMenus();
    
    // Анимация переливающихся цветов в dropdown меню (с небольшой задержкой)
    setTimeout(() => {
        initColorAnimation();
    }, 100);
    
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

// Функция для dropdown меню
function initDropdownMenus() {
    const dropdowns = document.querySelectorAll('.nav-item.dropdown');
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (!toggle || !menu) return;
        
        let hideTimeout;
        
        // Функция для позиционирования dropdown меню
        function positionDropdown() {
            const rect = toggle.getBoundingClientRect();
            const menuHeight = menu.offsetHeight || 300; // Примерная высота
            const viewportHeight = window.innerHeight;
            
            // Позиционируем dropdown под элементом
            menu.style.left = (rect.left + rect.width / 2) + 'px';
            
            // Проверяем, поместится ли меню снизу
            if (rect.bottom + menuHeight > viewportHeight) {
                // Показываем сверху
                menu.style.top = (rect.top - menuHeight - 10) + 'px';
            } else {
                // Показываем снизу
                menu.style.top = (rect.bottom + 5) + 'px';
            }
        }
        
        // Показать меню при наведении
        dropdown.addEventListener('mouseenter', function() {
            clearTimeout(hideTimeout);
            
            // Позиционируем меню перед показом
            positionDropdown();
            
            menu.style.opacity = '1';
            menu.style.visibility = 'visible';
            menu.style.transform = 'translateX(-50%) translateY(0)';
            menu.style.pointerEvents = 'auto';
        });
        
        // Скрыть меню при уходе курсора
        dropdown.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(() => {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.transform = 'translateX(-50%) translateY(10px)';
                menu.style.pointerEvents = 'none';
            }, 150);
        });
        
        // Обработка submenu
        const submenus = dropdown.querySelectorAll('.dropdown-submenu');
        submenus.forEach(submenu => {
            const submenuList = submenu.querySelector('.dropdown-submenu-list');
            
            if (!submenuList) return;
            
            let submenuTimeout;
            
            // Функция для позиционирования submenu
            function positionSubmenu() {
                const rect = submenu.getBoundingClientRect();
                const submenuWidth = submenuList.offsetWidth || 220;
                const viewportWidth = window.innerWidth;
                
                // Позиционируем submenu справа от элемента
                submenuList.style.top = rect.top + 'px';
                
                // Проверяем, поместится ли меню справа
                if (rect.right + submenuWidth > viewportWidth) {
                    // Показываем слева
                    submenuList.style.left = (rect.left - submenuWidth - 8) + 'px';
                } else {
                    // Показываем справа
                    submenuList.style.left = (rect.right + 8) + 'px';
                }
            }
            
            submenu.addEventListener('mouseenter', function() {
                clearTimeout(submenuTimeout);
                
                // Позиционируем submenu перед показом
                positionSubmenu();
                
                submenuList.style.opacity = '1';
                submenuList.style.visibility = 'visible';
                submenuList.style.transform = 'translateX(0)';
                submenuList.style.pointerEvents = 'auto';
            });
            
            submenu.addEventListener('mouseleave', function() {
                submenuTimeout = setTimeout(() => {
                    submenuList.style.opacity = '0';
                    submenuList.style.visibility = 'hidden';
                    submenuList.style.transform = 'translateX(-10px)';
                    submenuList.style.pointerEvents = 'none';
                }, 100);
            });
        });
        
        // Закрыть меню при клике на ссылку
        const links = menu.querySelectorAll('a[href]');
        links.forEach(link => {
            link.addEventListener('click', function() {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.transform = 'translateX(-50%) translateY(10px)';
                menu.style.pointerEvents = 'none';
            });
        });
        
        // Обновляем позицию при скролле/ресайзе
        window.addEventListener('scroll', positionDropdown);
        window.addEventListener('resize', positionDropdown);
    });
    
    // Закрыть все меню при клике вне их
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-item.dropdown')) {
            const menus = document.querySelectorAll('.dropdown-menu');
            menus.forEach(menu => {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.transform = 'translateX(-50%) translateY(10px)';
                menu.style.pointerEvents = 'none';
            });
            
            const submenus = document.querySelectorAll('.dropdown-submenu-list');
            submenus.forEach(submenu => {
                submenu.style.opacity = '0';
                submenu.style.visibility = 'hidden';
                submenu.style.transform = 'translateX(-10px)';
                submenu.style.pointerEvents = 'none';
            });
        }
    });
}

// Кнопка для слабовидящих
function initAccessibilityButton() {
    const accessibilityBtn = document.querySelector('.accessibility-btn');
    
    if (accessibilityBtn) {
        // Проверяем сохраненное состояние
        const isAccessibilityMode = localStorage.getItem('accessibility-mode') === 'true';
        if (isAccessibilityMode) {
            activateAccessibilityMode();
            accessibilityBtn.classList.add('active');
        }
        
        accessibilityBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Анимация клика
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 150);
            
            // Переключение режима
            const isActive = this.classList.contains('active');
            if (isActive) {
                deactivateAccessibilityMode();
                this.classList.remove('active');
                localStorage.setItem('accessibility-mode', 'false');
            } else {
                activateAccessibilityMode();
                this.classList.add('active');
                localStorage.setItem('accessibility-mode', 'true');
            }
        });
    }
}

// Активация режима для слабовидящих
function activateAccessibilityMode() {
    document.body.classList.add('accessibility-mode');
    
    // Применяем стили для слабовидящих
    if (!document.getElementById('accessibility-styles')) {
        const style = document.createElement('style');
        style.id = 'accessibility-styles';
        style.textContent = `
            .accessibility-mode {
                filter: contrast(1.2) brightness(1.1);
            }
            .accessibility-mode * {
                font-size: 1.1em !important;
                line-height: 1.6 !important;
            }
            .accessibility-mode .c-layout-header {
                background: rgba(255, 255, 255, 0.95) !important;
            }
            .accessibility-mode .nav-link {
                background: rgba(139, 92, 246, 0.1) !important;
                border: 2px solid rgba(139, 92, 246, 0.3) !important;
            }
            .accessibility-mode .accessibility-btn.active {
                background: linear-gradient(135deg, rgba(139, 92, 246, 0.9), rgba(16, 185, 129, 0.9)) !important;
                color: white !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    console.log('Режим для слабовидящих активирован');
}

// Деактивация режима для слабовидящих
function deactivateAccessibilityMode() {
    document.body.classList.remove('accessibility-mode');
    
    const style = document.getElementById('accessibility-styles');
    if (style) {
        style.remove();
    }
    
    console.log('Режим для слабовидящих деактивирован');
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

// Инициализация элегантной анимации в фиолетово-синей палитре
function initColorAnimation() {
    const dropdownLinks = document.querySelectorAll('.dropdown-menu a, .dropdown-submenu-list a');
    
    if (dropdownLinks.length === 0) return;
    
    // Функция для создания RGB цвета из HSL
    function hslToRgb(h, s, l) {
        h /= 360;
        s /= 100;
        l /= 100;
        
        const hue2rgb = (p, q, t) => {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1/6) return p + (q - p) * 6 * t;
            if (t < 1/2) return q;
            if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        };
        
        if (s === 0) {
            return [l, l, l]; // ахроматический
        } else {
            const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            const p = 2 * l - q;
            const r = hue2rgb(p, q, h + 1/3);
            const g = hue2rgb(p, q, h);
            const b = hue2rgb(p, q, h - 1/3);
            return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
        }
    }
    
    // Обработка каждой ссылки
    dropdownLinks.forEach((link, linkIndex) => {
        const originalText = link.textContent;
        const isSubmenu = link.closest('.dropdown-submenu-list');
        const baseSpeed = isSubmenu ? 0.5 : 0.8; // Скорость смещения цвета
        const hoverSpeed = isSubmenu ? 1.2 : 1.5; // Быстрее при hover
        
        let currentSpeed = baseSpeed;
        let colorOffset = linkIndex * 60; // Начальный сдвиг цвета для каждой ссылки
        let animationId;
        
        // Разбиваем текст на символы и оборачиваем в span
        link.innerHTML = '';
        const chars = originalText.split('').map((char, charIndex) => {
            const span = document.createElement('span');
            span.textContent = char;
            span.style.display = 'inline-block';
            span.style.transition = 'color 0.1s ease';
            
            // Специальная обработка пробелов
            if (char === ' ') {
                span.style.width = '0.3em'; // Фиксированная ширина пробела
                span.innerHTML = '&nbsp;'; // Неразрывный пробел
            }
            
            link.appendChild(span);
            return span;
        });
        
        // Функция анимации
        function animate() {
            colorOffset += currentSpeed;
            if (colorOffset >= 100) colorOffset = 0; // Циклируем от 0 до 100
            
            // Применяем цвета к каждому символу
            chars.forEach((charSpan, charIndex) => {
                // Создаем плавный переход через фиолетово-синюю палитру
                const step = 100 / Math.max(chars.length, 6); // Разделяем на шаги
                let position = (colorOffset + (charIndex * step)) % 100;
                
                // Определяем цвет на основе позиции в палитре
                let r, g, b;
                
                if (position < 25) {
                    // Переход от темно-фиолетового к фиолетовому
                    const t = position / 25;
                    r = Math.round(109 + (139 - 109) * t); // 109 -> 139
                    g = Math.round(40 + (92 - 40) * t);    // 40 -> 92
                    b = Math.round(217 + (246 - 217) * t); // 217 -> 246
                } else if (position < 50) {
                    // Переход от фиолетового к светло-фиолетовому
                    const t = (position - 25) / 25;
                    r = Math.round(139 + (168 - 139) * t); // 139 -> 168
                    g = Math.round(92 + (139 - 92) * t);   // 92 -> 139
                    b = Math.round(246 + (250 - 246) * t); // 246 -> 250
                } else if (position < 75) {
                    // Переход к синевато-фиолетовому
                    const t = (position - 50) / 25;
                    r = Math.round(168 + (124 - 168) * t); // 168 -> 124
                    g = Math.round(139 + (159 - 139) * t); // 139 -> 159
                    b = Math.round(250 + (252 - 250) * t); // 250 -> 252
                } else {
                    // Возврат к темно-фиолетовому
                    const t = (position - 75) / 25;
                    r = Math.round(124 + (109 - 124) * t); // 124 -> 109
                    g = Math.round(159 + (40 - 159) * t);  // 159 -> 40
                    b = Math.round(252 + (217 - 252) * t); // 252 -> 217
                }
                
                const color = `rgb(${r}, ${g}, ${b})`;
                charSpan.style.color = color;
            });
            
            // Продолжаем анимацию
            animationId = requestAnimationFrame(animate);
        }
        
        // Обработчики hover для ускорения анимации
        link.addEventListener('mouseenter', () => {
            currentSpeed = hoverSpeed;
            // Добавляем небольшое масштабирование символов при hover
            chars.forEach(charSpan => {
                charSpan.style.transform = 'scale(1.05)';
            });
        });
        
        link.addEventListener('mouseleave', () => {
            currentSpeed = baseSpeed;
            // Убираем масштабирование
            chars.forEach(charSpan => {
                charSpan.style.transform = 'scale(1)';
            });
        });
        
        // Запускаем анимацию
        animate();
        
        // Сохраняем данные для остановки анимации
        link.colorAnimationId = animationId;
        link.charSpans = chars;
    });
    
    console.log('💜 Элегантная фиолетовая анимация по буквам инициализирована для', dropdownLinks.length, 'ссылок');
}

// Остановка элегантной анимации
function stopColorAnimation() {
    const dropdownLinks = document.querySelectorAll('.dropdown-menu a, .dropdown-submenu-list a');
    
    dropdownLinks.forEach(link => {
        if (link.colorAnimationId) {
            cancelAnimationFrame(link.colorAnimationId);
            link.colorAnimationId = null;
        }
        
        // Восстанавливаем оригинальный текст если был разбит на spans
        if (link.charSpans) {
            const originalText = link.charSpans.map(span => span.textContent).join('');
            link.innerHTML = originalText;
            link.charSpans = null;
        }
    });
    
    console.log('🛑 Элегантная анимация остановлена');
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
    initDropdownMenus,
    initAccessibilityButton,
    activateAccessibilityMode,
    deactivateAccessibilityMode,
    initMobileMenu,
    initImageErrorHandling,
    initColorAnimation,
    stopColorAnimation
}; 