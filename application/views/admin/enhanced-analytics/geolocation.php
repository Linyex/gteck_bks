
<div class="geolocation-container">
    <!-- Заголовок -->
    <div class="geolocation-header">
        <div class="header-content">
            <h1><i class="fas fa-globe"></i> Геолокация активности</h1>
            <div class="header-actions">
                <button class="btn btn-blue" onclick="refreshGeolocationData()">
                    <i class="fas fa-sync-alt"></i> Обновить
                </button>
                <button class="btn btn-green" onclick="exportGeolocationData()">
                    <i class="fas fa-download"></i> Экспорт
                </button>
            </div>
        </div>
        
        <!-- Навигация -->
        <nav class="geolocation-nav">
            <a href="/admin/enhanced-analytics" class="nav-item">
                <i class="fas fa-tachometer-alt"></i> Обзор
            </a>
            <a href="/admin/enhanced-analytics/geolocation" class="nav-item active">
                <i class="fas fa-map-marker-alt"></i> Геолокация
            </a>
            <a href="/admin/enhanced-analytics/behavior" class="nav-item">
                <i class="fas fa-user-clock"></i> Поведение
            </a>
            <a href="/admin/enhanced-analytics/ml-anomalies" class="nav-item">
                <i class="fas fa-brain"></i> ML Аномалии
            </a>
            <a href="/admin/enhanced-analytics/notifications" class="nav-item">
                <i class="fas fa-bell"></i> Уведомления
            </a>
            <a href="/admin/enhanced-analytics/reports" class="nav-item">
                <i class="fas fa-file-alt"></i> Отчеты
            </a>
        </nav>
    </div>

    <!-- Основные метрики -->
    <div class="geolocation-metrics">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-globe"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= count($geolocationStats['countries'] ?? []) ?></div>
                <div class="metric-label">Стран</div>
                <div class="metric-details">
                    <span class="active"><?= count($geolocationStats['active_countries'] ?? []) ?> активных</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= count($geolocationStats['cities'] ?? []) ?></div>
                <div class="metric-label">Городов</div>
                <div class="metric-details">
                    <span class="top"><?= $geolocationStats['top_city'] ?? 'Нет данных' ?></span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= count($geolocationStats['suspicious_countries'] ?? []) ?></div>
                <div class="metric-label">Подозрительных стран</div>
                <div class="metric-details">
                    <span class="warning">Требуют внимания</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= count($geolocationStats['blocked_countries'] ?? []) ?></div>
                <div class="metric-label">Заблокированных стран</div>
                <div class="metric-details">
                    <span class="danger">Заблокировано</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Интерактивная карта -->
    <div class="map-section">
        <div class="section-header">
            <h3><i class="fas fa-map"></i> Интерактивная карта активности</h3>
            <div class="map-controls">
                <select id="mapPeriod" class="form-select">
                    <option value="24">Последние 24 часа</option>
                    <option value="7" selected>Последние 7 дней</option>
                    <option value="30">Последние 30 дней</option>
                </select>
                <button class="btn btn-sm" onclick="refreshMap()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="map-container">
            <div id="geolocationMap" style="height: 500px; width: 100%;"></div>
        </div>
    </div>

    <!-- Статистика по странам -->
    <div class="countries-section">
        <div class="section-header">
            <h3><i class="fas fa-flag"></i> Статистика по странам</h3>
        </div>
        <div class="countries-grid">
            <?php if (!empty($countryStats)): ?>
                <?php foreach (array_slice($countryStats, 0, 10) as $country): ?>
                    <div class="country-card">
                        <div class="country-flag">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div class="country-info">
                            <div class="country-name"><?= htmlspecialchars($country['country']) ?></div>
                            <div class="country-stats">
                                <span class="users"><?= $country['count'] ?> пользователей</span>
                                <span class="cities"><?= $country['cities_count'] ?? 0 ?> городов</span>
                            </div>
                        </div>
                        <div class="country-risk <?= $country['risk_level'] ?? 'low' ?>">
                            <?= ucfirst($country['risk_level'] ?? 'low') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    <p>Нет данных о странах</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Подозрительные локации -->
    <div class="suspicious-section">
        <div class="section-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Подозрительные локации</h3>
        </div>
        <div class="suspicious-list">
            <?php if (!empty($suspiciousLocations)): ?>
                <?php foreach ($suspiciousLocations as $location): ?>
                    <div class="suspicious-item">
                        <div class="location-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="location-info">
                            <div class="location-name">
                                <?= htmlspecialchars($location['city'] ?? 'Неизвестный город') ?>, 
                                <?= htmlspecialchars($location['country'] ?? 'Неизвестная страна') ?>
                            </div>
                            <div class="location-details">
                                <span class="ip">IP: <?= htmlspecialchars($location['ip_address']) ?></span>
                                <span class="activity"><?= $location['activity_count'] ?> действий</span>
                            </div>
                        </div>
                        <div class="location-risk <?= $location['risk_level'] ?? 'medium' ?>">
                            <?= ucfirst($location['risk_level'] ?? 'medium') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-shield-check"></i>
                    <p>Нет подозрительных локаций</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css">

<script>
    // Данные для карты
    const geolocationData = <?= json_encode($geolocationData ?? []) ?>;
    const mapData = <?= json_encode($mapData ?? []) ?>;
    
    // Инициализация карты
    document.addEventListener('DOMContentLoaded', function() {
        initGeolocationMap();
    });
    
    function initGeolocationMap() {
        const mapContainer = document.getElementById('geolocationMap');
        if (!mapContainer) return;
        
        // Создаем карту с центром в Беларуси
        const map = L.map('geolocationMap').setView([53.9023, 27.5619], 6);
        
        // Добавляем тайлы OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Добавляем маркеры активности
        if (mapData && mapData.length > 0) {
            mapData.forEach(point => {
                if (point.latitude && point.longitude) {
                    const marker = L.marker([point.latitude, point.longitude])
                        .bindPopup(`
                            <div class="map-popup">
                                <h4>Активность</h4>
                                <p><strong>Пользователей:</strong> ${point.count || 0}</p>
                                <p><strong>Координаты:</strong> ${point.latitude}, ${point.longitude}</p>
                            </div>
                        `)
                        .addTo(map);
                }
            });
        }
        
        // Добавляем маркеры подозрительных локаций
        const suspiciousData = <?= json_encode($suspiciousLocations ?? []) ?>;
        if (suspiciousData && suspiciousData.length > 0) {
            suspiciousData.forEach(location => {
                if (location.latitude && location.longitude) {
                    const suspiciousIcon = L.divIcon({
                        className: 'suspicious-marker',
                        html: '<i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i>',
                        iconSize: [20, 20]
                    });
                    
                    L.marker([location.latitude, location.longitude], {icon: suspiciousIcon})
                        .bindPopup(`
                            <div class="map-popup">
                                <h4>Подозрительная активность</h4>
                                <p><strong>Город:</strong> ${location.city || 'Неизвестно'}</p>
                                <p><strong>Страна:</strong> ${location.country || 'Неизвестно'}</p>
                                <p><strong>IP:</strong> ${location.ip_address}</p>
                                <p><strong>Риск:</strong> ${location.risk_level}</p>
                            </div>
                        `)
                        .addTo(map);
                }
            });
        }
    }
    
    // Обновление карты
    function refreshMap() {
        const mapContainer = document.getElementById('geolocationMap');
        if (mapContainer) {
            mapContainer.innerHTML = '';
            initGeolocationMap();
        }
    }
    
    // Обновление данных геолокации
    function refreshGeolocationData() {
        // Здесь можно добавить AJAX запрос для обновления данных
        console.log('Обновление данных геолокации...');
        location.reload();
    }
    
    // Экспорт данных
    function exportGeolocationData() {
        // Здесь можно добавить экспорт в CSV/JSON
        console.log('Экспорт данных геолокации...');
        alert('Функция экспорта будет добавлена позже');
    }
    
    // Обновление периода карты
    document.getElementById('mapPeriod')?.addEventListener('change', function() {
        console.log('Период карты изменен:', this.value);
        // Здесь можно добавить AJAX запрос для обновления данных карты
    });
</script>

<style>
    .geolocation-container {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        color: white;
    }
    
    .geolocation-header {
        margin-bottom: 30px;
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .header-content h1 {
        margin: 0;
        font-size: 2.5em;
    }
    
    .geolocation-nav {
        display: flex;
        gap: 10px;
        background: rgba(255,255,255,0.1);
        padding: 10px;
        border-radius: 10px;
    }
    
    .nav-item {
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 5px;
        transition: background 0.3s;
    }
    
    .nav-item:hover, .nav-item.active {
        background: rgba(255,255,255,0.2);
    }
    
    .geolocation-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .metric-card {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .metric-icon {
        font-size: 2em;
        opacity: 0.8;
    }
    
    .metric-number {
        font-size: 2em;
        font-weight: bold;
    }
    
    .metric-label {
        font-size: 0.9em;
        opacity: 0.8;
    }
    
    .metric-details {
        font-size: 0.8em;
        margin-top: 5px;
    }
    
    .map-section {
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .map-controls {
        display: flex;
        gap: 10px;
    }
    
    .countries-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 15px;
    }
    
    .country-card {
        background: rgba(255,255,255,0.1);
        padding: 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .country-flag {
        font-size: 1.5em;
    }
    
    .country-name {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .country-stats {
        font-size: 0.8em;
        opacity: 0.8;
    }
    
    .country-risk {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: bold;
    }
    
    .country-risk.low { background: #28a745; }
    .country-risk.medium { background: #ffc107; color: #000; }
    .country-risk.high { background: #fd7e14; }
    .country-risk.critical { background: #dc3545; }
    
    .suspicious-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .suspicious-item {
        background: rgba(255,255,255,0.1);
        padding: 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .location-icon {
        font-size: 1.2em;
        color: #dc3545;
    }
    
    .location-name {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .location-details {
        font-size: 0.8em;
        opacity: 0.8;
    }
    
    .location-risk {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: bold;
    }
    
    .location-risk.low { background: #28a745; }
    .location-risk.medium { background: #ffc107; color: #000; }
    .location-risk.high { background: #fd7e14; }
    .location-risk.critical { background: #dc3545; }
    
    .no-data {
        text-align: center;
        padding: 40px;
        opacity: 0.7;
    }
    
    .no-data i {
        font-size: 3em;
        margin-bottom: 15px;
    }
    
    .btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .btn:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .btn-blue { background: #007bff; }
    .btn-green { background: #28a745; }
    
    .form-select {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 8px;
        border-radius: 5px;
    }
    
    .form-select option {
        background: #333;
        color: white;
    }
</style> 