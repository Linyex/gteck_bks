<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Test</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Cyberpunk Admin Styles -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    
    <style>
        .test-container {
            padding: 50px;
            text-align: center;
        }
        
        .test-title {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            color: var(--text-yellow);
            text-shadow: var(--glow-yellow);
            margin-bottom: 30px;
        }
        
        .test-text {
            color: var(--text-blue);
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        
        .test-btn {
            background: linear-gradient(45deg, var(--primary-yellow), var(--primary-blue));
            border: 2px solid var(--primary-yellow);
            border-radius: 10px;
            color: var(--cyber-black);
            padding: 15px 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .test-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--glow-combined);
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1 class="test-title">CSS TEST</h1>
        <p class="test-text">Если вы видите желтый текст с синим свечением, CSS работает правильно!</p>
        <a href="/admin/login" class="test-btn">ПЕРЕЙТИ К ЛОГИНУ</a>
    </div>
</body>
</html> 