// Admin Panel Common JavaScript
class AdminPanel {
    constructor() {
        this.sidebarOpen = true;
        this.notificationsOpen = false;
        this.userMenuOpen = false;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupResponsive();
        this.initializeTooltips();
    }

    setupEventListeners() {
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.header-item')) {
                this.closeAllDropdowns();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                this.toggleSidebar();
            }
        });
    }

    setupResponsive() {
        // Handle mobile sidebar
        if (window.innerWidth <= 1024) {
            this.sidebarOpen = false;
            this.updateSidebarState();
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024 && !this.sidebarOpen) {
                this.sidebarOpen = true;
                this.updateSidebarState();
            } else if (window.innerWidth <= 1024 && this.sidebarOpen) {
                this.sidebarOpen = false;
                this.updateSidebarState();
            }
        });
    }

    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        this.updateSidebarState();
    }

    updateSidebarState() {
        const sidebar = document.getElementById('admin-sidebar');
        const main = document.querySelector('.admin-main');
        
        if (this.sidebarOpen) {
            sidebar.classList.remove('closed');
            main.style.marginLeft = '280px';
        } else {
            sidebar.classList.add('closed');
            main.style.marginLeft = '0';
        }
    }

    toggleNotifications() {
        this.notificationsOpen = !this.notificationsOpen;
        this.closeUserMenu();
        
        if (this.notificationsOpen) {
            this.showNotificationsDropdown();
        } else {
            this.hideNotificationsDropdown();
        }
    }

    toggleUserMenu() {
        this.userMenuOpen = !this.userMenuOpen;
        this.closeNotifications();
        
        if (this.userMenuOpen) {
            this.showUserDropdown();
        } else {
            this.hideUserDropdown();
        }
    }

    closeAllDropdowns() {
        this.notificationsOpen = false;
        this.userMenuOpen = false;
        this.hideNotificationsDropdown();
        this.hideUserDropdown();
    }

    closeNotifications() {
        this.notificationsOpen = false;
        this.hideNotificationsDropdown();
    }

    closeUserMenu() {
        this.userMenuOpen = false;
        this.hideUserDropdown();
    }

    showNotificationsDropdown() {
        // Remove existing dropdown
        const existing = document.querySelector('.notifications-dropdown');
        if (existing) existing.remove();

        const dropdown = document.createElement('div');
        dropdown.className = 'dropdown-menu notifications-dropdown active';
        dropdown.innerHTML = `
            <div class="dropdown-header">
                <h4>Уведомления</h4>
                <a href="/admin/notifications">Все</a>
            </div>
            <div class="notification-list">
                <div class="notification-item unread">
                    <div class="notification-content">
                        <p>Новый пользователь зарегистрирован</p>
                        <div class="notification-time">2 минуты назад</div>
                    </div>
                </div>
                <div class="notification-item unread">
                    <div class="notification-content">
                        <p>Обнаружена подозрительная активность</p>
                        <div class="notification-time">5 минут назад</div>
                    </div>
                </div>
                <div class="notification-item">
                    <div class="notification-content">
                        <p>Система обновлена успешно</p>
                        <div class="notification-time">1 час назад</div>
                    </div>
                </div>
            </div>
            <div class="dropdown-divider"></div>
            <a href="/admin/notifications" class="dropdown-item">
                <i class="fas fa-bell"></i>
                Настройки уведомлений
            </a>
        `;

        const button = document.querySelector('.action-btn[onclick="toggleNotifications()"]') || document.querySelector('.header-btn[onclick="toggleNotifications()"]');
        if (button && button.parentNode) {
            button.parentNode.appendChild(dropdown);
        }
    }

    hideNotificationsDropdown() {
        const dropdown = document.querySelector('.notifications-dropdown');
        if (dropdown) {
            dropdown.remove();
        }
    }

    showUserDropdown() {
        // Remove existing dropdown
        const existing = document.querySelector('.user-dropdown');
        if (existing) existing.remove();

        const dropdown = document.createElement('div');
        dropdown.className = 'dropdown-menu user-dropdown active';
        dropdown.innerHTML = `
            <div class="dropdown-header">
                <h4>Администратор</h4>
                <span>admin@example.com</span>
            </div>
            <a href="/admin/profile" class="dropdown-item">
                <i class="fas fa-user"></i>
                Профиль
            </a>
            <a href="/admin/settings" class="dropdown-item">
                <i class="fas fa-cog"></i>
                Настройки
            </a>
            <div class="dropdown-divider"></div>
            <a href="/admin/logout" class="dropdown-item text-danger">
                <i class="fas fa-sign-out-alt"></i>
                Выход
            </a>
        `;

        const button = document.querySelector('.action-btn[onclick="toggleUserMenu()"]') || document.querySelector('.header-btn[onclick="toggleUserMenu()"]');
        if (button && button.parentNode) {
            button.parentNode.appendChild(dropdown);
        }
    }

    hideUserDropdown() {
        const dropdown = document.querySelector('.user-dropdown');
        if (dropdown) {
            dropdown.remove();
        }
    }

    openSettings() {
        this.showModal('Настройки', `
            <div class="settings-form">
                <div class="form-group">
                    <label for="theme-select">Тема оформления</label>
                    <select id="theme-select" class="form-control">
                        <option value="cyberpunk">Киберпанк</option>
                        <option value="dark">Темная</option>
                        <option value="light">Светлая</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="language-select">Язык интерфейса</label>
                    <select id="language-select" class="form-control">
                        <option value="ru">Русский</option>
                        <option value="en">English</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="notifications-enabled" checked>
                        Включить уведомления
                    </label>
                </div>
            </div>
        `);
    }

    showModal(title, content) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal">
                <div class="modal-header">
                    <h3>${title}</h3>
                    <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">&times;</button>
                </div>
                <div class="modal-content">
                    ${content}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-blue" onclick="adminPanel.saveSettings()">
                        <i class="fas fa-save"></i> Сохранить
                    </button>
                    <button class="btn" onclick="this.closest('.modal-overlay').remove()">Отмена</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    saveSettings() {
        // Simulate saving settings
        this.showNotification('Настройки сохранены', 'success');
        document.querySelector('.modal-overlay').remove();
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const icon = type === 'success' ? 'check-circle' : 
                   type === 'error' ? 'exclamation-triangle' : 'info-circle';
        
        notification.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
        `;

        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => notification.classList.add('show'), 10);
        
        // Auto remove
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    initializeTooltips() {
        // Add tooltips to buttons
        const buttons = document.querySelectorAll('[title]');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', (e) => {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = e.target.title;
                document.body.appendChild(tooltip);
                
                const rect = e.target.getBoundingClientRect();
                tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
            });
            
            button.addEventListener('mouseleave', () => {
                const tooltip = document.querySelector('.tooltip');
                if (tooltip) tooltip.remove();
            });
        });
    }

    // Search functionality
    handleSearch(query) {
        if (query.length < 2) return;

        // Filter content based on search
        const searchableElements = document.querySelectorAll('[data-searchable]');
        searchableElements.forEach(element => {
            const text = element.textContent.toLowerCase();
            const matches = text.includes(query.toLowerCase());
            
            if (matches) {
                element.style.opacity = '1';
                element.style.transform = 'scale(1)';
            } else {
                element.style.opacity = '0.3';
                element.style.transform = 'scale(0.95)';
            }
        });
    }
}

// Global functions for onclick handlers
function toggleSidebar() {
    if (window.adminPanel) {
        window.adminPanel.toggleSidebar();
    }
}

function toggleNotifications() {
    if (window.adminPanel) {
        window.adminPanel.toggleNotifications();
    }
}

function toggleUserMenu() {
    if (window.adminPanel) {
        window.adminPanel.toggleUserMenu();
    }
}

function openSettings() {
    if (window.adminPanel) {
        window.adminPanel.openSettings();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.adminPanel = new AdminPanel();
    
    // Setup search
    const searchInput = document.querySelector('.header-search');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            window.adminPanel.handleSearch(e.target.value);
        });
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.adminPanel) {
        window.adminPanel.closeAllDropdowns();
    }
});

// Улучшенная логика для dropdown меню
document.addEventListener('DOMContentLoaded', function() {
    // Функция для позиционирования dropdown
    function positionDropdown(dropdownItem) {
        const dropdownMenu = dropdownItem.querySelector('.dropdown-menu');
        const rect = dropdownItem.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        
        // Проверяем, помещается ли dropdown снизу
        if (rect.bottom + dropdownMenu.offsetHeight > viewportHeight) {
            // Если не помещается, показываем сверху
            dropdownMenu.style.top = 'auto';
            dropdownMenu.style.bottom = '0';
        } else {
            // Показываем снизу
            dropdownMenu.style.top = '0';
            dropdownMenu.style.bottom = 'auto';
        }
    }
    
    // Обработчики для dropdown
    document.querySelectorAll('.nav-item.dropdown').forEach(dropdownItem => {
        const dropdownMenu = dropdownItem.querySelector('.dropdown-menu');
        let hideTimeout;
        
        // Показать dropdown при наведении
        dropdownItem.addEventListener('mouseenter', function() {
            clearTimeout(hideTimeout);
            positionDropdown(dropdownItem);
            dropdownMenu.classList.add('show');
        });
        
        // Скрыть dropdown при уходе курсора
        dropdownItem.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(() => {
                dropdownMenu.classList.remove('show');
            }, 300);
        });
        
        // Не скрывать при наведении на dropdown
        dropdownMenu.addEventListener('mouseenter', function() {
            clearTimeout(hideTimeout);
        });
        
        // Скрыть при уходе с dropdown
        dropdownMenu.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(() => {
                dropdownMenu.classList.remove('show');
            }, 100);
        });
    });
    
    // Закрытие всех dropdown при клике вне их
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.nav-item.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
});