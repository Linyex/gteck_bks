<?php
// Проверяем авторизацию
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit();
}

// Получаем данные пользователя
$userFio = $_SESSION['user_fio'] ?? 'Администратор';
$userAccessLevel = $_SESSION['user_access_level'] ?? 0;
?>

<header class="admin-header">
    <div class="header-container">
        <div class="header-left">
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-logo">
                <span class="logo-text">NoContrGtec</span>
                <span class="logo-subtitle">Admin Panel</span>
            </div>
        </div>
        
        <div class="header-center">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Поиск..." class="header-search">
            </div>
        </div>
        
        <div class="header-right">
            <!-- Статус безопасности -->
            <div class="header-item security-status">
                <button class="header-btn security-btn" onclick="toggleSecurityStatus()">
                    <i class="fas fa-shield-alt"></i>
                    <span class="security-badge success">Безопасно</span>
                </button>
                <div class="dropdown-menu security-menu">
                    <div class="dropdown-header">
                        <h4>Статус безопасности</h4>
                        <a href="/admin/monitoring">Подробнее</a>
                    </div>
                    <div class="security-stats">
                        <div class="security-stat">
                            <i class="fas fa-shield-alt text-success"></i>
                            <span>Система защищена</span>
                        </div>
                        <div class="security-stat">
                            <i class="fas fa-eye text-info"></i>
                            <span>0 активных угроз</span>
                        </div>
                        <div class="security-stat">
                            <i class="fas fa-ban text-warning"></i>
                            <span>127 заблокированных IP</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Уведомления -->
            <div class="header-item notifications">
                <button class="header-btn" onclick="toggleNotifications()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="dropdown-menu notifications-menu">
                    <div class="dropdown-header">
                        <h4>Уведомления</h4>
                        <a href="/admin/notifications">Все уведомления</a>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item unread">
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <div class="notification-content">
                                <p>Обнаружена подозрительная активность</p>
                                <span class="notification-time">5 минут назад</span>
                            </div>
                        </div>
                        <div class="notification-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <div class="notification-content">
                                <p>Резервное копирование завершено</p>
                                <span class="notification-time">1 час назад</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Профиль пользователя -->
            <div class="header-item user-menu">
                <button class="header-btn user-btn" onclick="toggleUserMenu()">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="user-name"><?= htmlspecialchars($userFio) ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu user-dropdown">
                    <div class="dropdown-header">
                        <div class="user-info">
                            <h4><?= htmlspecialchars($userFio) ?></h4>
                            <p>Уровень доступа: <?= $userAccessLevel ?></p>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="/admin/settings" class="dropdown-item">
                        <i class="fas fa-cog"></i> Настройки
                    </a>
                    <a href="/admin/profile" class="dropdown-item">
                        <i class="fas fa-user-edit"></i> Профиль
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/admin/logout" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt"></i> Выход
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleNotifications() {
    const menu = document.querySelector('.notifications-menu');
    menu.classList.toggle('active');
    // Закрываем другие меню
    document.querySelector('.user-dropdown').classList.remove('active');
}       

function toggleUserMenu() {
    const menu = document.querySelector('.user-dropdown');
    menu.classList.toggle('active');
    // Закрываем другие меню
    document.querySelector('.notifications-menu').classList.remove('active');
}

// Закрытие меню при клике вне
document.addEventListener('click', function(e) {
    if (!e.target.closest('.header-item')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('active');
        });
    }
});
</script> 