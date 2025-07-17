<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Вход в панель управления') ?> - NoContrGtec</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Cyberpunk Admin Styles -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    
    <style>
        /* Login-specific styles */
        .login-container {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(0, 0, 0, 0.9);
            border: 3px solid var(--primary-yellow);
            border-radius: 20px;
            padding: 50px;
            width: 100%;
            max-width: 500px;
            position: relative;
            backdrop-filter: blur(20px);
            box-shadow: var(--glow-yellow);
            animation: cardGlow 4s ease-in-out infinite alternate;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
            animation: scanLine 3s linear infinite;
        }
        
        @keyframes scanLine {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        @keyframes cardGlow {
            0% { 
                box-shadow: var(--glow-yellow);
                border-color: var(--primary-yellow);
            }
            100% { 
                box-shadow: var(--glow-combined);
                border-color: var(--primary-blue);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }
        
        .login-header h1 {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            color: var(--text-yellow);
            text-shadow: var(--glow-yellow);
            margin-bottom: 15px;
            animation: textGlow 2s ease-in-out infinite alternate;
            position: relative;
        }
        
        .login-header h1::before,
        .login-header h1::after {
            content: 'SYSTEM LOGIN';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .login-header h1::before {
            color: var(--primary-blue);
            animation: glitch1 2s infinite;
            z-index: -1;
        }
        
        .login-header h1::after {
            color: var(--accent-yellow);
            animation: glitch2 2s infinite;
            z-index: -2;
        }
        
        @keyframes glitch1 {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
        }
        
        @keyframes glitch2 {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(2px, -2px); }
            40% { transform: translate(2px, 2px); }
            60% { transform: translate(-2px, -2px); }
            80% { transform: translate(-2px, 2px); }
        }
        
        @keyframes textGlow {
            0% { 
                text-shadow: var(--glow-yellow);
                color: var(--text-yellow);
            }
            100% { 
                text-shadow: var(--glow-combined);
                color: var(--accent-yellow);
            }
        }
        
        .login-header p {
            font-size: 1.2rem;
            color: var(--text-blue);
            font-weight: 400;
            animation: fadeInUp 1s ease-out 0.5s both;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-group {
            margin-bottom: 30px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-blue);
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: var(--glow-blue);
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .form-control {
            width: 100%;
            padding: 20px 25px;
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid var(--primary-yellow);
            border-radius: 10px;
            color: var(--text-blue);
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            outline: none;
            position: relative;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: var(--glow-blue);
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.02);
        }
        
        .form-control::placeholder {
            color: rgba(0, 212, 255, 0.5);
        }
        
        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-yellow);
            font-size: 1.3rem;
            animation: iconPulse 2s ease-in-out infinite;
        }
        
        @keyframes iconPulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }
        
        .btn-login {
            width: 100%;
            padding: 20px;
            background: linear-gradient(45deg, var(--primary-yellow), var(--primary-blue), var(--accent-yellow));
            background-size: 200% 200%;
            border: none;
            border-radius: 10px;
            color: var(--cyber-black);
            font-family: 'Orbitron', monospace;
            font-size: 1.2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: gradientShift 3s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: var(--glow-combined);
        }
        
        .alert {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid;
            font-weight: 600;
            animation: shake 0.6s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        
        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 0, 0, 0.2), transparent);
            animation: errorScan 2s ease-in-out infinite;
        }
        
        @keyframes errorScan {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }
        
        .alert-danger {
            background: rgba(255, 0, 0, 0.1);
            border-color: #ff0000;
            color: #ff0000;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.3);
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .back-link {
            text-align: center;
            margin-top: 40px;
        }
        
        .back-link a {
            color: var(--text-blue);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .back-link a::before {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--text-blue), var(--text-yellow));
            transition: width 0.3s ease;
        }
        
        .back-link a:hover::before {
            width: 100%;
        }
        
        .back-link a:hover {
            text-shadow: var(--glow-blue);
            color: var(--accent-blue);
        }
        
        .form-group {
            animation: fadeInUp 1s ease-out both;
        }
        
        .form-group:nth-child(1) { animation-delay: 0.8s; }
        .form-group:nth-child(2) { animation-delay: 1s; }
        .btn-login { animation: fadeInUp 1s ease-out 1.2s both; }
        .back-link { animation: fadeInUp 1s ease-out 1.4s both; }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 40px 30px;
            }
            
            .login-header h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Cyberpunk Background -->
    <div class="cyber-background"></div>
    <div class="grid-overlay"></div>
    
    <!-- Glitch Effects -->
    <div class="glitch-overlay">
        <div class="glitch-line"></div>
        <div class="glitch-line"></div>
        <div class="glitch-line"></div>
        <div class="glitch-line"></div>
    </div>
    
    <!-- Floating Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>SYSTEM LOGIN</h1>
                <p>Доступ к панели управления</p>
            </div>
            
            <?php if (isset($error) && $error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/admin/login">
                <div class="form-group">
                    <label for="username">ИДЕНТИФИКАТОР</label>
                    <div class="input-wrapper">
                        <input type="text" 
                               class="form-control" 
                               id="username"
                               name="username" 
                               placeholder="Введите логин" 
                               required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">ПАРОЛЬ ДОСТУПА</label>
                    <div class="input-wrapper">
                        <input type="password" 
                               class="form-control" 
                               id="password"
                               name="password" 
                               placeholder="Введите пароль" 
                               required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> ВОЙТИ В СИСТЕМУ
                </button>
            </form>
            
            <div class="back-link">
                <a href="/"><i class="fas fa-arrow-left"></i> ВЕРНУТЬСЯ НА САЙТ</a>
            </div>
        </div>
    </div>
</body>
</html> 