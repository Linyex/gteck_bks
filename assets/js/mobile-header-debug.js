// ===== ОТЛАДОЧНАЯ ВЕРСИЯ МОБИЛЬНОГО МЕНЮ =====

console.log('🔍 Загрузка мобильного меню...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM загружен, инициализируем мобильное меню...');
    
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileNavClose = document.getElementById('mobile-nav-close');
    const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
    const mobileDropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
    
    console.log('🔍 Поиск элементов:');
    console.log('- mobileMenuToggle:', mobileMenuToggle);
    console.log('- mobileNav:', mobileNav);
    console.log('- mobileNavClose:', mobileNavClose);
    console.log('- mobileNavOverlay:', mobileNavOverlay);
    console.log('- mobileDropdownToggles:', mobileDropdownToggles.length);
    
    if (!mobileMenuToggle) {
        console.error('❌ Не найден элемент mobile-menu-toggle!');
        return;
    }
    
    if (!mobileNav) {
        console.error('❌ Не найден элемент mobile-nav!');
        return;
    }
    
    // Открытие мобильного меню
    function openMobileMenu() {
        console.log('🚀 Открываем мобильное меню...');
        mobileNav.classList.add('active');
        mobileMenuToggle.classList.add('active');
        mobileNavOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        console.log('✅ Мобильное меню открыто');
    }
    
    // Закрытие мобильного меню
    function closeMobileMenu() {
        console.log('🚪 Закрываем мобильное меню...');
        mobileNav.classList.remove('active');
        mobileMenuToggle.classList.remove('active');
        mobileNavOverlay.classList.remove('active');
        document.body.style.overflow = '';
        console.log('✅ Мобильное меню закрыто');
    }
    
    // Обработчики событий
    console.log('🔗 Добавляем обработчики событий...');
    
    mobileMenuToggle.addEventListener('click', function(e) {
        console.log('🖱️ Клик по гамбургер-кнопке!');
        e.preventDefault();
        openMobileMenu();
    });
    
    if (mobileNavClose) {
        mobileNavClose.addEventListener('click', function(e) {
            console.log('🖱️ Клик по кнопке закрытия!');
            e.preventDefault();
            closeMobileMenu();
        });
    }
    
    if (mobileNavOverlay) {
        mobileNavOverlay.addEventListener('click', function(e) {
            console.log('🖱️ Клик по оверлею!');
            e.preventDefault();
            closeMobileMenu();
        });
    }
    
    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
            console.log('⌨️ Нажата клавиша Escape!');
            closeMobileMenu();
        }
    });
    
    // Обработка dropdown в мобильном меню
    mobileDropdownToggles.forEach((toggle, index) => {
        console.log(`🔗 Добавляем обработчик для dropdown ${index + 1}`);
        toggle.addEventListener('click', function(e) {
            console.log(`🖱️ Клик по dropdown toggle ${index + 1}!`);
            e.preventDefault();
            
            const dropdown = this.nextElementSibling;
            const isActive = this.classList.contains('active');
            
            console.log('- dropdown элемент:', dropdown);
            console.log('- текущее состояние:', isActive ? 'активен' : 'неактивен');
            
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
            
            console.log('- новое состояние:', this.classList.contains('active') ? 'активен' : 'неактивен');
        });
    });
    
    // Закрытие меню при клике на ссылку
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link:not(.mobile-dropdown-toggle)');
    console.log(`🔗 Добавляем обработчики для ${mobileNavLinks.length} ссылок`);
    
    mobileNavLinks.forEach((link, index) => {
        link.addEventListener('click', function() {
            console.log(`🖱️ Клик по ссылке ${index + 1}: ${this.textContent}`);
            if (!this.classList.contains('mobile-dropdown-toggle')) {
                closeMobileMenu();
            }
        });
    });
    
    // Адаптация при изменении размера окна
    window.addEventListener('resize', function() {
        console.log('📱 Изменение размера окна:', window.innerWidth, 'px');
        if (window.innerWidth > 1024) {
            closeMobileMenu();
        }
    });
    
    console.log('✅ Мобильное меню инициализировано успешно!');
    
    // Тестовая функция для проверки
    window.testMobileMenu = function() {
        console.log('🧪 Тестируем мобильное меню...');
        console.log('- mobileNav.classList:', mobileNav.classList.toString());
        console.log('- mobileMenuToggle.classList:', mobileMenuToggle.classList.toString());
        console.log('- mobileNav.style.display:', mobileNav.style.display);
        console.log('- mobileNav.style.transform:', mobileNav.style.transform);
    };
}); 