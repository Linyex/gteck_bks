// ===== ФУНКЦИИ ДЛЯ СТРАНИЦЫ ГРАФИКА РАБОТЫ =====

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация анимаций
    initAnimations();
    
    // Инициализация интерактивных элементов
    initInteractiveElements();
    
    // Инициализация статистики
    initStatistics();
});

// Инициализация анимаций
function initAnimations() {
    // Анимация для статистических элементов
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.2}s`;
        item.classList.add('animate-fade-in');
    });
    
    // Анимация для карточек расписания
    const scheduleItems = document.querySelectorAll('.schedule-item');
    scheduleItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.3}s`;
        item.classList.add('animate-slide-up');
    });
    
    // Анимация для контактных карточек
    const contactCards = document.querySelectorAll('.contact-card');
    contactCards.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.2}s`;
        item.classList.add('animate-fade-in');
    });
    
    // Анимация для информационных карточек
    const infoCards = document.querySelectorAll('.info-card');
    infoCards.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.3}s`;
        item.classList.add('animate-slide-up');
    });
}

// Инициализация интерактивных элементов
function initInteractiveElements() {
    // Добавление hover эффектов для карточек
    const cards = document.querySelectorAll('.schedule-item, .contact-card, .info-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Добавление клик эффектов
    cards.forEach(card => {
        card.addEventListener('click', function() {
            // Добавляем эффект пульсации при клике
            this.style.animation = 'pulse 0.3s ease-in-out';
            setTimeout(() => {
                this.style.animation = '';
            }, 300);
        });
    });
}

// Инициализация статистики
function initStatistics() {
    // Анимация счетчиков статистики
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        animateCounter(stat, 0, target, 2000);
    });
}

// Функция анимации счетчика
function animateCounter(element, start, end, duration) {
    const startTime = performance.now();
    
    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = Math.floor(start + (end - start) * progress);
        element.textContent = current;
        
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }
    
    requestAnimationFrame(updateCounter);
}

// Функция для проверки текущего времени работы
function checkCurrentWorkStatus() {
    const now = new Date();
    const currentHour = now.getHours();
    const currentDay = now.getDay(); // 0 = воскресенье, 1 = понедельник, и т.д.
    
    const workDays = [1, 2, 3, 4, 5]; // Понедельник - Пятница
    const isWorkDay = workDays.includes(currentDay);
    
    let status = '';
    let statusClass = '';
    
    if (!isWorkDay) {
        status = 'Выходной день';
        statusClass = 'weekend';
    } else if (currentHour >= 8 && currentHour < 17) {
        if (currentHour === 12 || (currentHour === 13 && now.getMinutes() < 20)) {
            status = 'Обеденный перерыв';
            statusClass = 'break';
        } else {
            status = 'Рабочее время';
            statusClass = 'workday';
        }
    } else {
        status = 'Вне рабочего времени';
        statusClass = 'closed';
    }
    
    return { status, statusClass };
}

// Функция для отображения текущего статуса
function displayCurrentStatus() {
    const statusInfo = checkCurrentWorkStatus();
    
    // Создаем элемент для отображения статуса
    const statusElement = document.createElement('div');
    statusElement.className = `current-status ${statusInfo.statusClass}`;
    statusElement.innerHTML = `
        <div class="status-content">
            <i class="fa fa-clock-o"></i>
            <span>Текущий статус: ${statusInfo.status}</span>
        </div>
    `;
    
    // Добавляем элемент в начало страницы
    const heroSection = document.querySelector('.grafik-hero');
    if (heroSection) {
        heroSection.appendChild(statusElement);
    }
}

// Функция для добавления CSS анимаций
function addAnimations() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }
        
        .animate-slide-up {
            animation: slide-up 1s ease-out forwards;
        }
        
        .current-status {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 10px 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }
        
        .current-status.workday {
            border-left: 4px solid #10b981;
        }
        
        .current-status.break {
            border-left: 4px solid #f59e0b;
        }
        
        .current-status.weekend {
            border-left: 4px solid #8b5cf6;
        }
        
        .current-status.closed {
            border-left: 4px solid #ef4444;
        }
        
        .status-content {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #2d3748;
        }
        
        .status-content i {
            color: #8b5cf6;
        }
    `;
    document.head.appendChild(style);
}

// Инициализация всех функций при загрузке страницы
window.addEventListener('load', function() {
    addAnimations();
    displayCurrentStatus();
    
    // Обновление статуса каждую минуту
    setInterval(displayCurrentStatus, 60000);
});

// Функция для добавления интерактивности к иконкам
function addIconInteractivity() {
    const icons = document.querySelectorAll('.schedule-icon i, .contact-icon i, .info-icon i');
    
    icons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2) rotate(5deg)';
            this.style.transition = 'all 0.3s ease';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    });
}

// Вызов функции интерактивности иконок
document.addEventListener('DOMContentLoaded', addIconInteractivity); 