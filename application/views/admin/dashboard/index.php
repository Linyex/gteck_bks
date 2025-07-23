<div class="dashboard-container">
    <!-- Главная статистика с анимациями -->
    <div class="stats-grid">
        <div class="stat-card users-card" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-icon">
                <i class="fas fa-users pulse"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number" data-count="<?= $totalUsers ?>">0</div>
                <div class="stat-label">ПОЛЬЗОВАТЕЛИ</div>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: <?= min(($totalUsers / 100) * 100, 100) ?>%"></div>
                </div>
            </div>
        </div>

        <div class="stat-card news-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-icon">
                <i class="fas fa-newspaper bounce"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number" data-count="<?= $totalNews ?>">0</div>
                <div class="stat-label">НОВОСТИ</div>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: <?= min(($totalNews / 1000) * 100, 100) ?>%"></div>
                </div>
            </div>
        </div>
        
        <div class="stat-card server-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-icon">
                <i class="fas fa-hdd rotate"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $serverInfo['disk']['percent'] ?>%</div>
                <div class="stat-label">ЗАНЯТО МЕСТА</div>
                <div class="stat-details">
                    <span><?= $serverInfo['disk']['used'] ?> / <?= $serverInfo['disk']['total'] ?></span>
                </div>
                <div class="stat-progress">
                    <div class="progress-bar <?= $serverInfo['disk']['percent'] > 80 ? 'danger' : ($serverInfo['disk']['percent'] > 60 ? 'warning' : 'success') ?>" 
                         style="width: <?= $serverInfo['disk']['percent'] ?>%"></div>
                </div>
            </div>
        </div>
        
        <div class="stat-card status-card" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-icon">
                <i class="fas fa-<?= $siteStatus['overall'] === 'online' ? 'check-circle' : 'exclamation-triangle' ?> <?= $siteStatus['overall'] === 'online' ? 'pulse' : 'shake' ?>"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?= $siteStatus['overall'] === 'online' ? 'ОК' : 'ОШИБКА' ?></div>
                <div class="stat-label">СОСТОЯНИЕ САЙТА</div>
                <div class="status-indicators">
                    <span class="status-dot <?= $siteStatus['database'] ?>">БД</span>
                    <span class="status-dot <?= $siteStatus['files'] ?>">Файлы</span>
                    <span class="status-dot <?= $siteStatus['permissions'] ?>">Права</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Дополнительная информация о сервере -->
    <div class="server-info-grid">
        <div class="cyber-card memory-card" data-aos="fade-left" data-aos-delay="500">
            <h3 class="cyber-title">
                <i class="fas fa-memory"></i> ПАМЯТЬ
            </h3>
            <div class="memory-stats">
                <div class="memory-item">
                    <span class="memory-label">Использовано:</span>
                    <span class="memory-value"><?= $serverInfo['memory']['usage'] ?></span>
                </div>
                <div class="memory-item">
                    <span class="memory-label">Пик:</span>
                    <span class="memory-value"><?= $serverInfo['memory']['peak'] ?></span>
                </div>
                <div class="memory-item">
                    <span class="memory-label">Лимит:</span>
                    <span class="memory-value"><?= $serverInfo['memory']['limit'] ?></span>
                </div>
            </div>
        </div>
        
        <div class="cyber-card php-card" data-aos="fade-right" data-aos-delay="600">
            <h3 class="cyber-title">
                <i class="fab fa-php"></i> PHP ИНФОРМАЦИЯ
            </h3>
            <div class="php-stats">
                <div class="php-item">
                    <span class="php-label">Версия:</span>
                    <span class="php-value"><?= $serverInfo['php']['version'] ?></span>
                </div>
                <div class="php-item">
                    <span class="php-label">Время выполнения:</span>
                    <span class="php-value"><?= $serverInfo['php']['max_execution_time'] ?> сек</span>
                </div>
                <div class="php-item">
                    <span class="php-label">Макс. файл:</span>
                    <span class="php-value"><?= $serverInfo['php']['upload_max_filesize'] ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Последние действия -->
    <div class="cyber-card activity-card" data-aos="fade-up" data-aos-delay="700">
        <h3 class="cyber-title" data-text="ПОСЛЕДНИЕ ДЕЙСТВИЯ">
            <i class="fas fa-history"></i> ПОСЛЕДНИЕ ДЕЙСТВИЯ
        </h3>
        
        <div class="activity-list">
            <?php if (!empty($recentActivity)): ?>
                <?php foreach ($recentActivity as $index => $activity): ?>
                    <div class="activity-item" data-aos="fade-left" data-aos-delay="<?= 800 + ($index * 100) ?>">
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
                <div class="no-activity" data-aos="fade-up" data-aos-delay="800">
                    <i class="fas fa-info-circle"></i>
                    <span>Нет последних действий</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Быстрые действия -->
    <div class="cyber-card quick-actions-card" data-aos="fade-up" data-aos-delay="900">
        <h3 class="cyber-title" data-text="БЫСТРЫЕ ДЕЙСТВИЯ">
            <i class="fas fa-bolt"></i> БЫСТРЫЕ ДЕЙСТВИЯ
        </h3>
        
        <div class="quick-actions">
            <a href="/admin/users/create" class="cyber-btn action-btn" data-aos="zoom-in" data-aos-delay="1000">
                <i class="fas fa-user-plus"></i> ДОБАВИТЬ ПОЛЬЗОВАТЕЛЯ
            </a>
            <a href="/admin/news/create" class="cyber-btn action-btn" data-aos="zoom-in" data-aos-delay="1100">
                <i class="fas fa-plus"></i> СОЗДАТЬ НОВОСТЬ
            </a>
            <a href="/admin/files" class="cyber-btn action-btn" data-aos="zoom-in" data-aos-delay="1200">
                <i class="fas fa-upload"></i> ЗАГРУЗИТЬ ФАЙЛ
            </a>
            <a href="/admin/photos" class="cyber-btn action-btn" data-aos="zoom-in" data-aos-delay="1300">
                <i class="fas fa-camera"></i> ДОБАВИТЬ ФОТО
            </a>
        </div>
    </div>
</div>

<!-- CSS для анимаций -->
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 255, 0.1));
    border: 1px solid rgba(0, 255, 255, 0.3);
    border-radius: 15px;
    padding: 25px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.stat-card:hover::before {
    left: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 255, 255, 0.3);
}

.stat-icon {
    font-size: 2.5em;
    color: #00ffff;
    margin-bottom: 15px;
    text-align: center;
}

.stat-content {
    text-align: center;
}

.stat-number {
    font-size: 2.5em;
    font-weight: bold;
    color: #00ffff;
    margin-bottom: 5px;
    font-family: 'Courier New', monospace;
}

.stat-label {
    font-size: 0.9em;
    color: #ffffff;
    margin-bottom: 15px;
    font-weight: 600;
    letter-spacing: 1px;
}

.stat-progress {
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #00ffff, #ff00ff);
    border-radius: 3px;
    transition: width 1s ease;
}

.progress-bar.danger {
    background: linear-gradient(90deg, #ff0000, #ff6600);
}

.progress-bar.warning {
    background: linear-gradient(90deg, #ffff00, #ff6600);
}

.stat-details {
    font-size: 0.8em;
    color: #cccccc;
    margin-bottom: 10px;
}

.status-indicators {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
}

.status-dot {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.7em;
    font-weight: bold;
    text-transform: uppercase;
}

.status-dot.online {
    background: rgba(0, 255, 0, 0.2);
    color: #00ff00;
    border: 1px solid #00ff00;
}

.status-dot.offline {
    background: rgba(255, 0, 0, 0.2);
    color: #ff0000;
    border: 1px solid #ff0000;
}

.status-dot.ok {
    background: rgba(0, 255, 0, 0.2);
    color: #00ff00;
    border: 1px solid #00ff00;
}

.status-dot.error {
    background: rgba(255, 0, 0, 0.2);
    color: #ff0000;
    border: 1px solid #ff0000;
}

.server-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.memory-stats, .php-stats {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.memory-item, .php-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(0, 255, 255, 0.1);
}

.memory-label, .php-label {
    color: #cccccc;
    font-size: 0.9em;
}

.memory-value, .php-value {
    color: #00ffff;
    font-weight: bold;
    font-family: 'Courier New', monospace;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: rgba(0, 255, 255, 0.05);
    border-radius: 10px;
    border-left: 3px solid #00ffff;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: rgba(0, 255, 255, 0.1);
    transform: translateX(5px);
}

.activity-icon {
    font-size: 1.2em;
    color: #00ffff;
    width: 30px;
    text-align: center;
}

.activity-content {
    flex: 1;
}

.activity-text {
    color: #ffffff;
    font-size: 0.9em;
    margin-bottom: 5px;
}

.activity-time {
    color: #cccccc;
    font-size: 0.8em;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 20px;
    background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 255, 0.1));
    border: 1px solid rgba(0, 255, 255, 0.3);
    border-radius: 10px;
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.action-btn:hover {
    background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(255, 0, 255, 0.2));
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 255, 255, 0.3);
    color: #00ffff;
}

/* Анимации */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.pulse { animation: pulse 2s infinite; }
.bounce { animation: bounce 2s infinite; }
.rotate { animation: rotate 3s linear infinite; }
.shake { animation: shake 0.5s ease-in-out; }

/* Адаптивность */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .server-info-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- JavaScript для анимаций счетчиков -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Анимация счетчиков
    const counters = document.querySelectorAll('.stat-number[data-count]');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000; // 2 секунды
        const step = target / (duration / 16); // 60 FPS
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
    
    // Анимация прогресс-баров
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
    
    // Добавляем эффект при наведении на карточки
    const statCards = document.querySelectorAll('.stat-card');
    
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script> 