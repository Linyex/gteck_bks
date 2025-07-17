<div class="dashboard-container">
    <!-- Статистика -->
    <div class="stats-grid">
        <div class="stat-card success">
            <div class="stat-number" data-count="<?= $totalUsers ?>">0</div>
            <div class="stat-label">
                <i class="fas fa-users"></i> ПОЛЬЗОВАТЕЛИ
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-number" data-count="<?= $totalNews ?>">0</div>
            <div class="stat-label">
                <i class="fas fa-newspaper"></i> НОВОСТИ
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-number" data-count="<?= $totalFiles ?>">0</div>
            <div class="stat-label">
                <i class="fas fa-file"></i> ФАЙЛЫ
            </div>
        </div>
        
        <div class="stat-card danger">
            <div class="stat-number" data-count="<?= $totalPhotos ?>">0</div>
            <div class="stat-label">
                <i class="fas fa-images"></i> ФОТОГРАФИИ
            </div>
        </div>
    </div>
    
    <!-- Последние действия -->
    <div class="cyber-card">
        <h3 class="cyber-title" data-text="ПОСЛЕДНИЕ ДЕЙСТВИЯ">
            <i class="fas fa-history"></i> ПОСЛЕДНИЕ ДЕЙСТВИЯ
        </h3>
        
        <div class="activity-list">
            <?php if (!empty($recentActivity)): ?>
                <?php foreach ($recentActivity as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-<?= $activity['icon'] ?>"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text"><?= htmlspecialchars($activity['description']) ?></div>
                            <div class="activity-time"><?= $activity['time'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-activity">
                    <i class="fas fa-info-circle"></i>
                    <span>Нет последних действий</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Системная информация -->
    <div class="cyber-card">
        <h3 class="cyber-title" data-text="СИСТЕМНАЯ ИНФОРМАЦИЯ">
            <i class="fas fa-server"></i> СИСТЕМНАЯ ИНФОРМАЦИЯ
        </h3>
        
        <div class="system-info">
            <div class="info-row">
                <span class="info-label">Версия PHP:</span>
                <span class="info-value"><?= PHP_VERSION ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Сервер:</span>
                <span class="info-value"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Неизвестно' ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">База данных:</span>
                <span class="info-value">MySQL</span>
            </div>
            <div class="info-row">
                <span class="info-label">Время сервера:</span>
                <span class="info-value"><?= date('Y-m-d H:i:s') ?></span>
            </div>
        </div>
    </div>
    
    <!-- Быстрые действия -->
    <div class="cyber-card">
        <h3 class="cyber-title" data-text="БЫСТРЫЕ ДЕЙСТВИЯ">
            <i class="fas fa-bolt"></i> БЫСТРЫЕ ДЕЙСТВИЯ
        </h3>
        
        <div class="quick-actions">
            <a href="/admin/users/create" class="cyber-btn">
                <i class="fas fa-user-plus"></i> ДОБАВИТЬ ПОЛЬЗОВАТЕЛЯ
            </a>
            <a href="/admin/news/create" class="cyber-btn">
                <i class="fas fa-plus"></i> СОЗДАТЬ НОВОСТЬ
            </a>
            <a href="/admin/files/upload" class="cyber-btn">
                <i class="fas fa-upload"></i> ЗАГРУЗИТЬ ФАЙЛ
            </a>
            <a href="/admin/photos/create" class="cyber-btn">
                <i class="fas fa-camera"></i> ДОБАВИТЬ ФОТО
            </a>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        animation: fadeInUp 1s ease-out;
    }
    
    .activity-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid rgba(255, 215, 0, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .activity-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
        transition: left 0.5s;
    }
    
    .activity-item:hover::before {
        left: 100%;
    }
    
    .activity-item:hover {
        background: rgba(255, 215, 0, 0.1);
        transform: translateX(10px);
    }
    
    .activity-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(45deg, var(--primary-yellow), var(--primary-blue));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: var(--cyber-black);
        font-size: 1.2rem;
        font-weight: 900;
        box-shadow: var(--glow-yellow);
        animation: iconPulse 2s ease-in-out infinite;
    }
    
    @keyframes iconPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-text {
        color: var(--text-yellow);
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .activity-time {
        color: var(--text-blue);
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    .no-activity {
        text-align: center;
        padding: 40px;
        color: var(--text-blue);
        font-style: italic;
    }
    
    .no-activity i {
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }
    
    .system-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: rgba(255, 215, 0, 0.1);
        border-radius: 10px;
        border: 1px solid rgba(255, 215, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    .info-row:hover {
        background: rgba(255, 215, 0, 0.2);
        transform: scale(1.02);
        box-shadow: var(--glow-yellow);
    }
    
    .info-label {
        color: var(--text-blue);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .info-value {
        color: var(--text-yellow);
        font-family: 'Orbitron', monospace;
        font-weight: 700;
        text-shadow: var(--glow-yellow);
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .quick-actions .cyber-btn {
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    /* Анимация счетчиков */
    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .stat-number {
        animation: countUp 1s ease-out both;
    }
    
    /* Глитч-эффект для статистики */
    .stat-card:hover .stat-number {
        animation: glitchNumber 0.3s ease-in-out;
    }
    
    @keyframes glitchNumber {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-2px); }
        75% { transform: translateX(2px); }
    }
    
    /* Адаптивность */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .system-info {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .activity-item {
            flex-direction: column;
            text-align: center;
        }
        
        .activity-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }
    }
</style>

<script>
    // Анимация счетчиков
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count') || 0);
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current);
            }, 16);
        });
    }
    
    // Запуск анимации при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(animateCounters, 500);
        
        // Случайные глитчи для элементов
        setInterval(() => {
            const elements = document.querySelectorAll('.activity-item, .info-row');
            const randomElement = elements[Math.floor(Math.random() * elements.length)];
            
            if (randomElement) {
                randomElement.style.transform = 'translateX(' + (Math.random() - 0.5) * 10 + 'px)';
                setTimeout(() => {
                    randomElement.style.transform = '';
                }, 200);
            }
        }, 5000);
        
        // Эффект свечения для иконок активности
        document.querySelectorAll('.activity-icon').forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                this.style.boxShadow = 'var(--glow-combined)';
                this.style.transform = 'scale(1.2)';
            });
            
            icon.addEventListener('mouseleave', function() {
                this.style.boxShadow = 'var(--glow-yellow)';
                this.style.transform = 'scale(1)';
            });
        });
    });
    
    // Анимация появления элементов
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.cyber-card, .stat-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease-out';
        observer.observe(card);
    });
</script> 