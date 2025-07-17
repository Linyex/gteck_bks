<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Внутренняя ошибка сервера</title>
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
        
        .error-code {
            font-size: 12rem;
            color: #ff4757;
            margin-bottom: 1rem;
            text-shadow: 0 0 30px rgba(255, 71, 87, 0.5);
            font-weight: bold;
            animation: errorGlow 2s ease-in-out infinite alternate;
        }
        
        .error-title {
            font-size: 2.5rem;
            color: #e0e0e0;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.2rem;
            color: #b0b0b0;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn-primary {
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
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(255, 71, 87, 0.5);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(108, 117, 125, 0.3);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(108, 117, 125, 0.5);
        }
        
        @keyframes errorGlow {
            from { text-shadow: 0 0 20px rgba(255, 71, 87, 0.5); }
            to { text-shadow: 0 0 30px rgba(255, 71, 87, 0.8), 0 0 40px rgba(255, 71, 87, 0.3); }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <h1 class="error-title">Внутренняя ошибка сервера</h1>
        <p class="error-message">
            Произошла внутренняя ошибка сервера. Наша команда уже работает над её устранением. 
            Пожалуйста, попробуйте обновить страницу или вернуться позже.
        </p>
        <div class="error-actions">
            <a href="/admin/dashboard" class="btn-primary">Главная панель</a>
            <a href="javascript:location.reload()" class="btn-secondary">Обновить страницу</a>
        </div>
    </div>
</body>
</html> 