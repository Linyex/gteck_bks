<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Минимальный тест CSS</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Cyberpunk Admin Styles -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    
    <style>
        /* Fallback стили для старых браузеров */
        body {
            background: #000 !important;
            color: #ffd700 !important;
            font-family: Arial, sans-serif;
        }
        
        .test-box {
            border: 2px solid #ffd700;
            background: #001a33;
            color: #00d4ff;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }
        
        .test-title {
            color: #ffd700 !important;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.8);
        }
        
        .test-text {
            color: #00d4ff !important;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="test-box">
        <h1 class="test-title">ТЕСТ CSS ЗАГРУЗКИ</h1>
        <p class="test-text">Если вы видите желтый заголовок и синий текст на черном фоне, CSS загружается!</p>
        
        <h2>Проверка переменных:</h2>
        <p style="color: var(--text-yellow);">Желтый текст (CSS переменная)</p>
        <p style="color: var(--text-blue);">Синий текст (CSS переменная)</p>
        
        <h2>Проверка шрифтов:</h2>
        <p style="font-family: 'Orbitron', monospace; color: #ffd700;">Orbitron шрифт</p>
        <p style="font-family: 'Rajdhani', sans-serif; color: #00d4ff;">Rajdhani шрифт</p>
        
        <h2>Проверка кнопки:</h2>
        <button style="
            background: linear-gradient(45deg, #ffd700, #00d4ff);
            border: 2px solid #ffd700;
            border-radius: 10px;
            color: #000;
            padding: 15px 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        ">КИБЕРПАНК КНОПКА</button>
    </div>
    
    <div class="test-box">
        <h2>Проверка админских классов:</h2>
        <div class="admin-container">
            <div class="admin-sidebar">
                <div class="sidebar-header">
                    <h1>ADMIN PANEL</h1>
                    <p>Тестовая панель</p>
                </div>
            </div>
            <div class="admin-main">
                <div class="main-header">
                    <h1>ТЕСТОВАЯ СТРАНИЦА</h1>
                    <p>Проверка стилей</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Проверяем загрузку CSS
        document.addEventListener('DOMContentLoaded', function() {
            const styles = getComputedStyle(document.body);
            const bgColor = styles.backgroundColor;
            console.log('Background color:', bgColor);
            
            // Проверяем CSS переменные
            const yellowColor = getComputedStyle(document.documentElement).getPropertyValue('--text-yellow');
            console.log('Yellow color variable:', yellowColor);
        });
    </script>
</body>
</html> 