<?php
// Проверяем авторизацию
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /admin/auth/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Угрозы безопасности - Мониторинг</title>
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .threats-container {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            color: #fff;
            font-family: 'Courier New', monospace;
            padding: 20px;
        }
        
        .threats-header {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid #ff0000;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }
        
        .threats-title {
            font-size: 2rem;
            color: #ff0000;
            margin: 0 0 10px 0;
            text-shadow: 0 0 10px #ff0000;
        }
        
        .threats-subtitle {
            color: #888;
            margin: 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid #333;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 0, 0, 0.3);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #888;
            font-size: 0.9rem;
        }
        
        .stat-critical { color: #ff0000; }
        .stat-high { color: #ff8000; }
        .stat-medium { color: #ffff00; }
        .stat-low { color: #00ff00; }
        
        .threats-filters {
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid #333;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }
        
        .filters-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-label {
            color: #ff0000;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .filter-input {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #ff0000;
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
        }
        
        .filter-input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }
        
        .filter-select {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #ff0000;
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
        }
        
        .filter-btn {
            background: linear-gradient(45deg, #ff0000, #ff8000);
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 0, 0, 0.4);
        }
        
        .threats-list {
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid #333;
            border-radius: 10px;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .threat-item {
            border-bottom: 1px solid #222;
            padding: 20px;
            transition: background 0.3s ease;
        }
        
        .threat-item:hover {
            background: rgba(255, 0, 0, 0.05);
        }
        
        .threat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .threat-type {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .threat-icon {
            font-size: 1.5rem;
        }
        
        .threat-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
        }
        
        .threat-severity {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .severity-critical { background: #ff0000; color: #fff; }
        .severity-high { background: #ff8000; color: #000; }
        .severity-medium { background: #ffff00; color: #000; }
        .severity-low { background: #00ff00; color: #000; }
        
        .threat-details {
            margin-bottom: 15px;
        }
        
        .threat-description {
            color: #ccc;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .threat-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            font-size: 0.9rem;
            color: #888;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .threat-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-block {
            background: linear-gradient(45deg, #ff0000, #800000);
            color: #fff;
        }
        
        .btn-block:hover {
            box-shadow: 0 5px 15px rgba(255, 0, 0, 0.4);
        }
        
        .btn-ignore {
            background: linear-gradient(45deg, #888, #444);
            color: #fff;
        }
        
        .btn-ignore:hover {
            box-shadow: 0 5px 15px rgba(128, 128, 128, 0.4);
        }
        
        .btn-details {
            background: linear-gradient(45deg, #0080ff, #004080);
            color: #fff;
        }
        
        .btn-details:hover {
            box-shadow: 0 5px 15px rgba(0, 128, 255, 0.4);
        }
        
        .back-btn {
            background: linear-gradient(45deg, #ff8000, #ff0000);
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 128, 0, 0.4);
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #888;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 3px solid #ff0000;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .no-threats {
            text-align: center;
            padding: 40px;
            color: #888;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body class="threats-container">
    <!-- Кнопка назад -->
    <a href="/admin/monitoring" class="back-btn">
        <i class="fas fa-arrow-left"></i> Назад к мониторингу
    </a>

    <!-- Заголовок -->
    <div class="threats-header">
        <h1 class="threats-title">
            <i class="fas fa-exclamation-triangle"></i> Угрозы безопасности
        </h1>
        <p class="threats-subtitle">
            Мониторинг и управление угрозами безопасности в реальном времени
        </p>
    </div>

    <!-- Статистика -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-critical">
                <i class="fas fa-radiation"></i>
            </div>
            <div class="stat-value stat-critical" id="criticalCount">0</div>
            <div class="stat-label">Критические угрозы</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-high">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value stat-high" id="highCount">0</div>
            <div class="stat-label">Высокие угрозы</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-medium">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-value stat-medium" id="mediumCount">0</div>
            <div class="stat-label">Средние угрозы</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-low">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-value stat-low" id="blockedCount">0</div>
            <div class="stat-label">Заблокировано IP</div>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="threats-filters">
        <form id="threatsFilterForm">
            <div class="filters-row">
                <div class="filter-group">
                    <label class="filter-label">Тип угрозы</label>
                    <select name="type" class="filter-select">
                        <option value="">Все типы</option>
                        <option value="sql_injection">SQL инъекция</option>
                        <option value="xss_attack">XSS атака</option>
                        <option value="brute_force">Брутфорс</option>
                        <option value="suspicious_activity">Подозрительная активность</option>
                        <option value="failed_login">Неудачный вход</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Уровень угрозы</label>
                    <select name="severity" class="filter-select">
                        <option value="">Все уровни</option>
                        <option value="critical">Критический</option>
                        <option value="high">Высокий</option>
                        <option value="medium">Средний</option>
                        <option value="low">Низкий</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Статус</label>
                    <select name="status" class="filter-select">
                        <option value="">Все статусы</option>
                        <option value="active">Активные</option>
                        <option value="blocked">Заблокированные</option>
                        <option value="ignored">Игнорированные</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Поиск</label>
                    <input type="text" name="search" placeholder="Поиск угроз..." class="filter-input">
                </div>
                
                <div class="filter-group">
                    <button type="submit" class="filter-btn">
                        <i class="fas fa-search"></i> Применить фильтры
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Список угроз -->
    <div class="threats-list" id="threatsContainer">
        <div class="loading">
            <div class="loading-spinner"></div>
            <p>Загрузка угроз...</p>
        </div>
    </div>

    <!-- Скрипты -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Загрузка угроз
            function loadThreats() {
                const formData = new FormData($('#threatsFilterForm')[0]);
                
                $('#threatsContainer').html('<div class="loading"><div class="loading-spinner"></div><p>Загрузка угроз...</p></div>');
                
                $.ajax({
                    url: '/admin/api/monitoring/threats',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            displayThreats(response.data.threats);
                            updateStats(response.data.stats);
                        } else {
                            $('#threatsContainer').html('<div class="no-threats">Ошибка загрузки угроз</div>');
                        }
                    },
                    error: function() {
                        $('#threatsContainer').html('<div class="no-threats">Ошибка подключения к серверу</div>');
                    }
                });
            }
            
            // Отображение угроз
            function displayThreats(threats) {
                if (threats.length === 0) {
                    $('#threatsContainer').html('<div class="no-threats">Угроз не обнаружено</div>');
                    return;
                }
                
                let html = '';
                threats.forEach(function(threat) {
                    const severityClass = 'severity-' + threat.severity;
                    const time = new Date(threat.created_at).toLocaleString('ru-RU');
                    
                    html += '<div class="threat-item">' +
                        '<div class="threat-header">' +
                        '<div class="threat-type">' +
                        '<i class="fas fa-exclamation-triangle threat-icon"></i>' +
                        '<span class="threat-title">' + threat.description + '</span>' +
                        '</div>' +
                        '<span class="threat-severity ' + severityClass + '">' + 
                        threat.severity.toUpperCase() + '</span>' +
                        '</div>' +
                        '<div class="threat-details">' +
                        '<div class="threat-description">' + threat.details + '</div>' +
                        '<div class="threat-meta">' +
                        '<div class="meta-item"><i class="fas fa-clock"></i> ' + time + '</div>' +
                        '<div class="meta-item"><i class="fas fa-map-marker-alt"></i> ' + (threat.ip_address || 'Неизвестно') + '</div>' +
                        '<div class="meta-item"><i class="fas fa-user"></i> ' + (threat.user_id || 'Неизвестно') + '</div>' +
                        '<div class="meta-item"><i class="fas fa-tag"></i> ' + threat.type + '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="threat-actions">' +
                        '<button class="action-btn btn-block" onclick="blockIP(\'' + threat.ip_address + '\')">' +
                        '<i class="fas fa-ban"></i> Заблокировать IP</button>' +
                        '<button class="action-btn btn-ignore" onclick="ignoreThreat(' + threat.id + ')">' +
                        '<i class="fas fa-eye-slash"></i> Игнорировать</button>' +
                        '<button class="action-btn btn-details" onclick="viewDetails(' + threat.id + ')">' +
                        '<i class="fas fa-info-circle"></i> Детали</button>' +
                        '</div>' +
                        '</div>';
                });
                
                $('#threatsContainer').html(html);
            }
            
            // Обновление статистики
            function updateStats(stats) {
                $('#criticalCount').text(stats.critical || 0);
                $('#highCount').text(stats.high || 0);
                $('#mediumCount').text(stats.medium || 0);
                $('#blockedCount').text(stats.blocked || 0);
            }
            
            // Блокировка IP
            window.blockIP = function(ip) {
                if (!ip) {
                    alert('IP адрес не указан');
                    return;
                }
                
                if (confirm('Заблокировать IP адрес ' + ip + '?')) {
                    $.ajax({
                        url: '/admin/api/monitoring/block-ip',
                        method: 'POST',
                        data: { ip: ip },
                        success: function(response) {
                            if (response.success) {
                                alert('IP адрес заблокирован');
                                loadThreats();
                            } else {
                                alert('Ошибка блокировки: ' + response.error);
                            }
                        },
                        error: function() {
                            alert('Ошибка подключения к серверу');
                        }
                    });
                }
            };
            
            // Игнорирование угрозы
            window.ignoreThreat = function(threatId) {
                if (confirm('Игнорировать эту угрозу?')) {
                    $.ajax({
                        url: '/admin/api/monitoring/ignore-threat',
                        method: 'POST',
                        data: { threat_id: threatId },
                        success: function(response) {
                            if (response.success) {
                                alert('Угроза игнорирована');
                                loadThreats();
                            } else {
                                alert('Ошибка: ' + response.error);
                            }
                        },
                        error: function() {
                            alert('Ошибка подключения к серверу');
                        }
                    });
                }
            };
            
            // Просмотр деталей
            window.viewDetails = function(threatId) {
                // Здесь можно открыть модальное окно с деталями
                alert('Детали угрозы ID: ' + threatId);
            };
            
            // Обработка формы фильтров
            $('#threatsFilterForm').on('submit', function(e) {
                e.preventDefault();
                loadThreats();
            });
            
            // Автообновление каждые 10 секунд
            setInterval(function() {
                loadThreats();
            }, 10000);
            
            // Загрузка угроз при загрузке страницы
            loadThreats();
        });
    </script>
</body>
</html> 