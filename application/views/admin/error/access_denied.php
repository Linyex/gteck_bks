<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Доступ запрещен' ?></title>
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 2rem;
        }
        
        .error-icon {
            font-size: 8rem;
            color: #ff6b35;
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }
        
        .error-title {
            font-size: 3rem;
            color: #ff6b35;
            margin-bottom: 1rem;
            text-shadow: 0 0 20px rgba(255, 107, 53, 0.5);
        }
        
        .error-message {
            font-size: 1.2rem;
            color: #e0e0e0;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border: none;
            color: #000;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(255, 107, 53, 0.3);
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(255, 107, 53, 0.5);
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #ff4757, #ff3742);
            border: none;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(255, 71, 87, 0.3);
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(255, 71, 87, 0.5);
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title"><?= isset($title) ? $title : 'Доступ запрещен' ?></h1>
        <p class="error-message">
            <?= isset($message) ? $message : 'У вас недостаточно прав для выполнения этого действия. Обратитесь к администратору для получения необходимых разрешений.' ?>
        </p>
        <div class="error-actions">
            <a href="/admin/dashboard" class="btn-back">Вернуться на главную</a>
            <a href="/admin/logout" class="btn-logout">Выйти из системы</a>
        </div>
    </div>
</body>
</html> 