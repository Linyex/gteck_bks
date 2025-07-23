<?php require_once 'application/views/admin/common/header.php'; ?>

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
                <div class="metric-number"><?= $geolocationStats['countries_count'] ?? 0 ?></div>
                <div class="metric-label">Стран</div>
                <div class="metric-details">
                    <span class="active"><?= $geolocationStats['active_countries'] ?? 0 ?> активных</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?= $geolocationStats['cities_count'] ?? 0 ?></div>
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
                <div class="metric-number"><?= $geolocationStats['suspicious_countries'] ?? 0 ?></div>
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
                <div class="metric-number"><?= $geolocationStats['blocked_countries'] ?? 0 ?></div>
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
                <select id="mapFilter" onchange="updateMapFilter()">
                    <option value="all">Все активности</option>
                    <option value="suspicious">Подозрительные</option>
                    <option value="blocked">Заблокированные</option>
                    <option value="recent">За последние 24 часа</option>
                </select>
                <button class="btn btn-sm btn-blue" onclick="refreshMap()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        
        <div class="map-container">
            <div id="worldMap" style="height: 500px; width: 100%;"></div>
        </div>
    </div>

    <!-- Статистика по странам -->
    <div class="countries-section">
        <div class="section-header">
            <h3><i class="fas fa-flag"></i> Статистика по странам</h3>
            <div class="table-controls">
                <input type="text" id="countrySearch" placeholder="Поиск страны..." onkeyup="filterCountries()">
                <select id="countrySort" onchange="sortCountries()">
                    <option value="activity">По активности</option>
                    <option value="suspicious">По подозрительности</option>
                    <option value="alphabet">По алфавиту</option>
                </select>
            </div>
        </div>
        
        <div class="table-container">
            <table class="admin-table" id="countriesTable">
                <thead>
                    <tr>
                        <th>Страна</th>
                        <th>Активность</th>
                        <th>Уникальных IP</th>
                        <th>Подозрительных действий</th>
                        <th>Последняя активность</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($countryStats as $country): ?>
                    <tr class="country-row" data-country="<?= htmlspecialchars($country['country']) ?>">
                        <td>
                            <div class="country-info">
                                <span class="country-name"><?= htmlspecialchars($country['country']) ?></span>
                                <?php if ($country['is_suspicious']): ?>
                                <span class="badge badge-warning">Подозрительно</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="activity-bar">
                                <div class="bar-fill" style="width: <?= min(100, ($country['activity_count'] / max(array_column($countryStats, 'activity_count'))) * 100) ?>%"></div>
                                <span class="activity-count"><?= $country['activity_count'] ?></span>
                            </div>
                        </td>
                        <td><?= $country['unique_ips'] ?></td>
                        <td>
                            <?php if ($country['suspicious_count'] > 0): ?>
                            <span class="badge badge-danger"><?= $country['suspicious_count'] ?></span>
                            <?php else: ?>
                            <span class="badge badge-success">0</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $country['last_activity'] ? date('d.m.Y H:i', strtotime($country['last_activity'])) : 'Нет данных' ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-info" onclick="viewCountryDetails('<?= htmlspecialchars($country['country']) ?>')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if ($country['suspicious_count'] > 0): ?>
                                <button class="btn btn-sm btn-warning" onclick="blockCountry('<?= htmlspecialchars($country['country']) ?>')">
                                    <i class="fas fa-ban"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Подозрительные локации -->
    <?php if (!empty($suspiciousLocations)): ?>
    <div class="suspicious-locations-section">
        <div class="section-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Подозрительные локации</h3>
            <button class="btn btn-red" onclick="blockAllSuspiciousLocations()">
                <i class="fas fa-ban"></i> Заблокировать все
            </button>
        </div>
        
        <div class="suspicious-grid">
            <?php foreach ($suspiciousLocations as $location): ?>
            <div class="suspicious-card">
                <div class="location-header">
                    <div class="location-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="location-info">
                        <div class="location-name"><?= htmlspecialchars($location['city']) ?>, <?= htmlspecialchars($location['country']) ?></div>
                        <div class="location-coords"><?= $location['latitude'] ?>, <?= $location['longitude'] ?></div>
                    </div>
                    <div class="risk-level risk-<?= $location['risk_level'] ?>">
                        <?= strtoupper($location['risk_level']) ?>
                    </div>
                </div>
                
                <div class="location-stats">
                    <div class="stat-item">
                        <span class="stat-label">Активность:</span>
                        <span class="stat-value"><?= $location['activity_count'] ?> действий</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Подозрительных:</span>
                        <span class="stat-value"><?= $location['suspicious_count'] ?> действий</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Последняя активность:</span>
                        <span class="stat-value"><?= date('d.m.Y H:i', strtotime($location['last_activity'])) ?></span>
                    </div>
                </div>
                
                <div class="location-actions">
                    <button class="btn btn-sm btn-info" onclick="viewLocationDetails(<?= $location['id'] ?>)">
                        <i class="fas fa-eye"></i> Детали
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="blockLocation(<?= $location['id'] ?>)">
                        <i class="fas fa-ban"></i> Заблокировать
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Топ городов -->
    <div class="top-cities-section">
        <div class="section-header">
            <h3><i class="fas fa-city"></i> Топ городов по активности</h3>
        </div>
        
        <div class="cities-grid">
            <?php foreach (array_slice($countryStats, 0, 10) as $index => $city): ?>
            <div class="city-card">
                <div class="city-rank">#<?= $index + 1 ?></div>
                <div class="city-info">
                    <div class="city-name"><?= htmlspecialchars($city['city'] ?? $city['country']) ?></div>
                    <div class="city-country"><?= htmlspecialchars($city['country']) ?></div>
                </div>
                <div class="city-stats">
                    <div class="stat-item">
                        <span class="stat-label">Активность:</span>
                        <span class="stat-value"><?= $city['activity_count'] ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Уникальных IP:</span>
                        <span class="stat-value"><?= $city['unique_ips'] ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Подключение библиотек -->
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css">

<style>
/* Стили для геолокации */
.geolocation-container {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

.geolocation-header {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header-content h1 {
    margin: 0;
    color: #2c3e50;
    font-size: 28px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.geolocation-nav {
    display: flex;
    gap: 5px;
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.nav-item {
    padding: 10px 15px;
    text-decoration: none;
    color: #666;
    border-radius: 5px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.nav-item:hover {
    background: #f8f9fa;
    color: #2c3e50;
}

.nav-item.active {
    background: #3498db;
    color: white;
}

.geolocation-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.metric-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.metric-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.metric-content {
    flex: 1;
}

.metric-number {
    font-size: 28px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.metric-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
}

.metric-details span {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.metric-details .active { background: #e8f5e8; color: #27ae60; }
.metric-details .top { background: #e3f2fd; color: #2196f3; }
.metric-details .warning { background: #fff3e0; color: #f57c00; }
.metric-details .danger { background: #ffebee; color: #d32f2f; }

.map-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h3 {
    margin: 0;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
}

.map-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.map-controls select {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.map-container {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.countries-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.table-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.table-controls input {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 200px;
}

.table-controls select {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.country-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.country-name {
    font-weight: 500;
}

.activity-bar {
    position: relative;
    background: #f0f0f0;
    height: 20px;
    border-radius: 10px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #3498db, #2980b9);
    transition: width 0.3s ease;
}

.activity-count {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 12px;
    font-weight: 500;
    color: #333;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.badge-success { background: #e8f5e8; color: #27ae60; }
.badge-danger { background: #ffebee; color: #d32f2f; }
.badge-warning { background: #fff3e0; color: #f57c00; }

.action-buttons {
    display: flex;
    gap: 5px;
}

.suspicious-locations-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.suspicious-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.suspicious-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #f39c12;
}

.location-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.location-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f39c12;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.location-info {
    flex: 1;
}

.location-name {
    font-weight: 500;
    color: #2c3e50;
}

.location-coords {
    font-size: 12px;
    color: #666;
}

.risk-level {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.risk-low { background: #e8f5e8; color: #27ae60; }
.risk-medium { background: #fff3e0; color: #f57c00; }
.risk-high { background: #ffebee; color: #d32f2f; }
.risk-critical { background: #d32f2f; color: white; }

.location-stats {
    margin-bottom: 15px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 13px;
}

.stat-label {
    color: #666;
}

.stat-value {
    font-weight: 500;
    color: #2c3e50;
}

.location-actions {
    display: flex;
    gap: 10px;
}

.top-cities-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.cities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.city-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    position: relative;
}

.city-rank {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #3498db;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.city-info {
    margin-bottom: 10px;
}

.city-name {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 2px;
}

.city-country {
    font-size: 12px;
    color: #666;
}

.city-stats {
    font-size: 12px;
}

.city-stats .stat-item {
    margin-bottom: 3px;
}

/* Адаптивность */
@media (max-width: 768px) {
    .geolocation-metrics {
        grid-template-columns: 1fr;
    }
    
    .suspicious-grid {
        grid-template-columns: 1fr;
    }
    
    .cities-grid {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        gap: 15px;
    }
    
    .geolocation-nav {
        flex-wrap: wrap;
    }
}
</style>

<script>
let map;
let markers = [];

// Инициализация карты
function initMap() {
    map = L.map('worldMap').setView([20, 0], 2);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    loadMapData();
}

// Загрузка данных для карты
function loadMapData() {
    fetch('/admin/enhanced-analytics/api?type=geolocation')
        .then(response => response.json())
        .then(data => {
            clearMarkers();
            addMarkers(data.map_data);
        })
        .catch(error => console.error('Ошибка загрузки данных карты:', error));
}

// Очистка маркеров
function clearMarkers() {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
}

// Добавление маркеров
function addMarkers(mapData) {
    mapData.forEach(location => {
        const marker = L.marker([location.latitude, location.longitude])
            .bindPopup(`
                <div class="marker-popup">
                    <h4>${location.city}, ${location.country}</h4>
                    <p>Активность: ${location.activity_count}</p>
                    <p>Подозрительных: ${location.suspicious_count}</p>
                    <p>Последняя активность: ${location.last_activity}</p>
                </div>
            `)
            .addTo(map);
        
        markers.push(marker);
    });
}

// Обновление фильтра карты
function updateMapFilter() {
    const filter = document.getElementById('mapFilter').value;
    loadMapData(filter);
}

// Обновление карты
function refreshMap() {
    loadMapData();
}

// Обновление данных геолокации
function refreshGeolocationData() {
    location.reload();
}

// Экспорт данных геолокации
function exportGeolocationData() {
    const format = prompt('Выберите формат экспорта (csv, json, excel):', 'json');
    if (format) {
        window.open(`/admin/enhanced-analytics/export-data?format=${format}&type=geolocation`);
    }
}

// Фильтрация стран
function filterCountries() {
    const searchTerm = document.getElementById('countrySearch').value.toLowerCase();
    const rows = document.querySelectorAll('.country-row');
    
    rows.forEach(row => {
        const countryName = row.querySelector('.country-name').textContent.toLowerCase();
        if (countryName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Сортировка стран
function sortCountries() {
    const sortBy = document.getElementById('countrySort').value;
    const tbody = document.querySelector('#countriesTable tbody');
    const rows = Array.from(tbody.querySelectorAll('.country-row'));
    
    rows.sort((a, b) => {
        let aValue, bValue;
        
        switch (sortBy) {
            case 'activity':
                aValue = parseInt(a.querySelector('.activity-count').textContent);
                bValue = parseInt(b.querySelector('.activity-count').textContent);
                return bValue - aValue;
            case 'suspicious':
                aValue = parseInt(a.querySelector('.badge-danger')?.textContent || '0');
                bValue = parseInt(b.querySelector('.badge-danger')?.textContent || '0');
                return bValue - aValue;
            case 'alphabet':
                aValue = a.querySelector('.country-name').textContent;
                bValue = b.querySelector('.country-name').textContent;
                return aValue.localeCompare(bValue);
        }
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

// Просмотр деталей страны
function viewCountryDetails(country) {
    window.open(`/admin/enhanced-analytics/geolocation?country=${encodeURIComponent(country)}`, '_blank');
}

// Блокировка страны
function blockCountry(country) {
    if (confirm(`Заблокировать все IP адреса из страны "${country}"?`)) {
        fetch('/admin/enhanced-analytics/block-country', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ country: country })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Страна заблокирована!');
                location.reload();
            } else {
                alert('Ошибка блокировки: ' + data.error);
            }
        })
        .catch(error => {
            alert('Ошибка блокировки: ' + error);
        });
    }
}

// Просмотр деталей локации
function viewLocationDetails(locationId) {
    window.open(`/admin/enhanced-analytics/geolocation?location=${locationId}`, '_blank');
}

// Блокировка локации
function blockLocation(locationId) {
    if (confirm('Заблокировать эту локацию?')) {
        fetch('/admin/enhanced-analytics/block-location', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ location_id: locationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Локация заблокирована!');
                location.reload();
            } else {
                alert('Ошибка блокировки: ' + data.error);
            }
        })
        .catch(error => {
            alert('Ошибка блокировки: ' + error);
        });
    }
}

// Блокировка всех подозрительных локаций
function blockAllSuspiciousLocations() {
    if (confirm('Заблокировать все подозрительные локации?')) {
        fetch('/admin/enhanced-analytics/block-all-suspicious-locations', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Все подозрительные локации заблокированы!');
                location.reload();
            } else {
                alert('Ошибка блокировки: ' + data.error);
            }
        })
        .catch(error => {
            alert('Ошибка блокировки: ' + error);
        });
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>

<?php require_once 'application/views/admin/common/footer.php'; ?> 