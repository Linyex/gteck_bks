<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Панель управления') ?> - NoContrGtec</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Cyberpunk Admin Styles -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    
    <style>
        /* Layout-specific styles */
        .admin-container {
            min-height: 100vh;
            display: flex;
        }
        
        .admin-sidebar {
            width: 300px;
            background: rgba(0, 0, 0, 0.95);
            border-right: 3px solid var(--primary-yellow);
            padding: 30px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            backdrop-filter: blur(20px);
            box-shadow: var(--glow-yellow);
            z-index: 1000;
        }
        
        .admin-sidebar .nav-link {
            color: var(--text-white);
            padding: 20px 30px;
            border: 1px solid transparent;
            margin: 5px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
        }
        
        .admin-sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .admin-sidebar .nav-link:hover::before,
        .admin-sidebar .nav-link.active::before {
            left: 100%;
        }
        
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: rgba(255, 215, 0, 0.1);
            border-color: var(--primary-yellow);
            box-shadow: var(--glow-yellow);
            transform: translateX(10px);
            color: var(--text-yellow);
        }
        
        .admin-sidebar .nav-link i {
            width: 25px;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        .admin-main {
            flex: 1;
            margin-left: 300px;
            background: transparent;
            min-height: 100vh;
        }
        
        .main-header {
            background: linear-gradient(135deg, var(--cyber-black) 0%, var(--dark-blue) 100%);
            border-bottom: 2px solid var(--primary-yellow);
            padding: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .main-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-yellow), var(--primary-blue), var(--primary-yellow));
            animation: scanline 3s linear infinite;
        }
        
        .main-header h1 {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--text-yellow);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
            text-shadow: var(--glow-yellow);
        }
        
        .main-header p {
            color: var(--text-blue);
            font-size: 1.1rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .main-content {
            padding: 30px;
        }
        
        .cyber-card {
            background: linear-gradient(135deg, var(--cyber-black) 0%, var(--dark-blue) 100%);
            border: 2px solid var(--primary-yellow);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--glow-yellow);
        }
        
        .cyber-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-yellow), var(--primary-blue), var(--primary-yellow));
            animation: scanline 4s linear infinite;
        }
        
        .cyber-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-yellow);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-shadow: var(--glow-yellow);
            position: relative;
        }
        
        .cyber-title i {
            margin-right: 15px;
            color: var(--text-blue);
        }
        
        .cyber-btn {
            display: inline-flex;
            align-items: center;
            padding: 15px 25px;
            background: linear-gradient(45deg, var(--primary-yellow), var(--primary-blue));
            border: 2px solid var(--primary-yellow);
            border-radius: 10px;
            color: var(--cyber-black);
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin: 10px;
        }
        
        .cyber-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .cyber-btn:hover::before {
            left: 100%;
        }
        
        .cyber-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--glow-combined);
            border-color: var(--primary-blue);
        }
        
        .cyber-btn i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--cyber-black) 0%, var(--dark-blue) 100%);
            border: 2px solid var(--primary-yellow);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: var(--glow-yellow);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--glow-combined);
        }
        
        .stat-card.success { border-color: var(--primary-yellow); }
        .stat-card.info { border-color: var(--primary-blue); }
        .stat-card.warning { border-color: var(--accent-yellow); }
        .stat-card.danger { border-color: #ff4444; }
        
        .stat-number {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            color: var(--text-yellow);
            text-shadow: var(--glow-yellow);
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: var(--text-blue);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stat-label i {
            margin-right: 10px;
            color: var(--text-yellow);
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
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
    </div>
    
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h1>ADMIN PANEL</h1>
                <p>Система управления</p>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="/admin/dashboard" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i> ДАШБОРД
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/admin/users" class="nav-link <?= $currentPage === 'users' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> ПОЛЬЗОВАТЕЛИ
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/admin/news" class="nav-link <?= $currentPage === 'news' ? 'active' : '' ?>">
                        <i class="fas fa-newspaper"></i> НОВОСТИ
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/admin/files" class="nav-link <?= $currentPage === 'files' ? 'active' : '' ?>">
                        <i class="fas fa-file"></i> ФАЙЛЫ
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/admin/photos" class="nav-link <?= $currentPage === 'photos' ? 'active' : '' ?>">
                        <i class="fas fa-images"></i> ФОТОГРАФИИ
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/admin/settings" class="nav-link <?= $currentPage === 'settings' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i> НАСТРОЙКИ
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/admin/profile" class="nav-link <?= $currentPage === 'profile' ? 'active' : '' ?>">
                        <i class="fas fa-user"></i> ПРОФИЛЬ
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/admin/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> ВЫХОД
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="admin-main">
            <div class="main-header">
                <h1><?= htmlspecialchars($title ?? 'Панель управления') ?></h1>
                <p>Добро пожаловать, <?= htmlspecialchars($adminUser['user_fio'] ?? 'Администратор') ?></p>
            </div>
            
            <div class="main-content">
                <?= $content ?>
            </div>
        </div>
    </div>
    
    <script>
        // Mobile menu toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            sidebar.classList.toggle('open');
        }
        
        // Add mobile menu button if needed
        if (window.innerWidth <= 768) {
            const header = document.querySelector('.main-header');
            const menuBtn = document.createElement('button');
            menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            menuBtn.className = 'mobile-menu-btn';
            menuBtn.onclick = toggleSidebar;
            header.appendChild(menuBtn);
        }
    </script>
</body>
</html> 