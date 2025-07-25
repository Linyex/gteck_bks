// Анимации для дашборда
document.addEventListener('DOMContentLoaded', function() {
    
    // Инициализация AOS (Animate On Scroll)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }
    
    // Анимация счетчиков с задержкой
    const counters = document.querySelectorAll('.stat-number[data-count]');
    
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-count'));
                animateCounter(counter, target);
                counterObserver.unobserve(counter);
            }
        });
    }, observerOptions);
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
    
    function animateCounter(element, target) {
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        const originalText = element.textContent;
        
        // Проверяем есть ли специальные символы в оригинальном тексте
        const hasSpecialFormat = originalText.includes('K') || originalText.includes('+') || originalText.includes('%');
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
                // Возвращаем оригинальный формат если есть спецсимволы
                if (hasSpecialFormat) {
                    element.textContent = originalText;
                } else {
                    element.textContent = Math.floor(current);
                }
            } else {
                if (hasSpecialFormat) {
                    // Для спецформатов показываем прогресс но сохраняем формат
                    const progress = current / target;
                    if (originalText.includes('K+')) {
                        element.textContent = Math.floor((target / 1000) * progress) + 'K+';
                    } else {
                        element.textContent = Math.floor(current) + originalText.slice(-1);
                    }
                } else {
                    element.textContent = Math.floor(current);
                }
            }
        }, 16);
    }
    
    // Анимация прогресс-баров
    const progressBars = document.querySelectorAll('.progress-bar');
    
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const width = bar.style.width;
                bar.style.width = '0%';
                
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
                
                progressObserver.unobserve(bar);
            }
        });
    }, observerOptions);
    
    progressBars.forEach(bar => {
        progressObserver.observe(bar);
    });
    
    // Эффект свечения для статусных индикаторов
    const statusDots = document.querySelectorAll('.status-dot');
    
    statusDots.forEach(dot => {
        if (dot.classList.contains('online') || dot.classList.contains('ok')) {
            setInterval(() => {
                dot.style.boxShadow = '0 0 10px rgba(0, 255, 0, 0.5)';
                setTimeout(() => {
                    dot.style.boxShadow = 'none';
                }, 500);
            }, 2000);
        }
    });
    
    // Анимация иконок в карточках статистики
    const statIcons = document.querySelectorAll('.stat-icon i');
    
    statIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2) rotate(5deg)';
            this.style.color = '#ff00ff';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.color = '';
        });
    });
    
    // Эффект параллакса для карточек
    const statCards = document.querySelectorAll('.stat-card');
    
    statCards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    // Анимация активности
    const activityItems = document.querySelectorAll('.activity-item');
    
    activityItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, 1000 + (index * 200));
    });
    
    // Эффект печатания для заголовков
    const titles = document.querySelectorAll('.cyber-title');
    
    titles.forEach(title => {
        const text = title.textContent;
        title.textContent = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < text.length) {
                title.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        };
        
        setTimeout(typeWriter, 1500);
    });
    
    // Анимация кнопок быстрых действий
    const actionButtons = document.querySelectorAll('.action-btn');
    
    actionButtons.forEach((button, index) => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.05)';
            this.style.boxShadow = '0 10px 25px rgba(0, 255, 255, 0.4)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
        
        // Добавляем эффект пульсации при клике
        button.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Случайные глитч-эффекты
    setInterval(() => {
        const cards = document.querySelectorAll('.stat-card, .cyber-card');
        const randomCard = cards[Math.floor(Math.random() * cards.length)];
        
        if (randomCard) {
            randomCard.style.filter = 'hue-rotate(90deg)';
            setTimeout(() => {
                randomCard.style.filter = '';
            }, 100);
        }
    }, 8000);
    
    // Анимация загрузки данных
    const memoryValues = document.querySelectorAll('.memory-value, .php-value');
    
    memoryValues.forEach(value => {
        const originalText = value.textContent;
        value.textContent = 'Загрузка...';
        
        setTimeout(() => {
            value.textContent = originalText;
            value.style.color = '#00ffff';
            setTimeout(() => {
                value.style.color = '';
            }, 1000);
        }, 2000);
    });
    
    // Эффект неонового свечения для активных элементов
    const activeElements = document.querySelectorAll('.stat-card:hover, .activity-item:hover');
    
    document.addEventListener('mouseover', function(e) {
        if (e.target.closest('.stat-card') || e.target.closest('.activity-item')) {
            e.target.closest('.stat-card, .activity-item').style.boxShadow = '0 0 20px rgba(0, 255, 255, 0.3)';
        }
    });
    
    document.addEventListener('mouseout', function(e) {
        if (e.target.closest('.stat-card') || e.target.closest('.activity-item')) {
            e.target.closest('.stat-card, .activity-item').style.boxShadow = '';
        }
    });
    
    // Анимация состояния сайта
    const statusCard = document.querySelector('.status-card');
    if (statusCard) {
        const statusIcon = statusCard.querySelector('.stat-icon i');
        const statusNumber = statusCard.querySelector('.stat-number');
        
        if (statusNumber.textContent === 'ОК') {
            setInterval(() => {
                statusIcon.style.color = '#00ff00';
                setTimeout(() => {
                    statusIcon.style.color = '#00ffff';
                }, 500);
            }, 3000);
        } else {
            statusIcon.style.animation = 'shake 0.5s ease-in-out infinite';
        }
    }
    
    // Эффект матрицы для фона
    const dashboardContainer = document.querySelector('.dashboard-container');
    if (dashboardContainer) {
        const matrixOverlay = document.createElement('div');
        matrixOverlay.className = 'matrix-overlay';
        matrixOverlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.1;
            z-index: -1;
            background: linear-gradient(90deg, transparent 50%, rgba(0, 255, 0, 0.1) 50%);
            background-size: 4px 4px;
            animation: matrix 20s linear infinite;
        `;
        
        dashboardContainer.style.position = 'relative';
        dashboardContainer.appendChild(matrixOverlay);
        
        const matrixKeyframes = `
            @keyframes matrix {
                0% { transform: translateY(-100%); }
                100% { transform: translateY(100%); }
            }
        `;
        
        const style = document.createElement('style');
        style.textContent = matrixKeyframes;
        document.head.appendChild(style);
    }
}); 