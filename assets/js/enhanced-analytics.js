// Modern Enhanced Analytics JavaScript
class EnhancedAnalytics {
    constructor() {
        this.currentView = 'overview';
        this.charts = {};
        this.isLoading = false;
        this.updateInterval = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeCharts();
        this.startRealTimeUpdates();
        this.createParticles();
        this.setupAnimations();
    }

    setupEventListeners() {
        // Navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const view = item.getAttribute('data-view');
                this.switchView(view);
            });
        });

        // Settings button
        const settingsBtn = document.querySelector('.btn-settings');
        if (settingsBtn) {
            settingsBtn.addEventListener('click', () => this.openSettings());
        }

        // Modal close
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay') || 
                e.target.classList.contains('modal-close')) {
                this.closeModal();
            }
        });

        // Form submissions
        const settingsForm = document.getElementById('settings-form');
        if (settingsForm) {
            settingsForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveSettings();
            });
        }

        // Real-time toggle
        const realtimeToggle = document.getElementById('realtime-toggle');
        if (realtimeToggle) {
            realtimeToggle.addEventListener('change', () => {
                this.toggleRealTime();
            });
        }

        // Search functionality
        const searchInput = document.querySelector('.header-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.handleSearch(e.target.value);
            });
        }

        // Notification system
        this.setupNotifications();
    }

    setupAnimations() {
        // Smooth scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all metric cards and chart sections
        document.querySelectorAll('.metric-card, .chart-section').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // Header animations
        const header = document.querySelector('.admin-header');
        if (header) {
            let lastScroll = 0;
            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;
                if (currentScroll > lastScroll && currentScroll > 100) {
                    header.style.transform = 'translateY(-100%)';
                } else {
                    header.style.transform = 'translateY(0)';
                }
                lastScroll = currentScroll;
            });
        }
    }

    createParticles() {
        const particlesContainer = document.createElement('div');
        particlesContainer.className = 'particles';
        document.body.appendChild(particlesContainer);

        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
            particlesContainer.appendChild(particle);
        }
    }

    switchView(view) {
        if (this.currentView === view) return;

        // Update navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector(`[data-view="${view}"]`).classList.add('active');

        // Animate transition
        const container = document.querySelector('.enhanced-analytics-container');
        container.style.opacity = '0';
        container.style.transform = 'translateY(10px)';

        setTimeout(() => {
            this.currentView = view;
            this.loadViewData(view);
            
            container.style.opacity = '1';
            container.style.transform = 'translateY(0)';
        }, 300);
    }

    async loadViewData(view) {
        this.showLoading();
        
        try {
            const response = await fetch(`/admin/enhanced-analytics/data?view=${view}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            this.updateViewContent(view, data);
        } catch (error) {
            console.error('Error loading view data:', error);
            this.showNotification('Error loading data', 'error');
        } finally {
            this.hideLoading();
        }
    }

    updateViewContent(view, data) {
        const container = document.querySelector('.analytics-content');
        if (!container) return;

        // Update metrics
        if (data.metrics) {
            this.updateMetrics(data.metrics);
        }

        // Update charts
        if (data.charts) {
            this.updateCharts(data.charts);
        }

        // Update tables
        if (data.tables) {
            this.updateTables(data.tables);
        }

        // Animate new content
        container.style.opacity = '0';
        setTimeout(() => {
            container.style.opacity = '1';
        }, 100);
    }

    updateMetrics(metrics) {
        Object.keys(metrics).forEach(key => {
            const element = document.querySelector(`[data-metric="${key}"]`);
            if (element) {
                const numberElement = element.querySelector('.metric-number');
                if (numberElement) {
                    this.animateNumber(numberElement, metrics[key].value);
                }

                const trendElement = element.querySelector('.metric-trend');
                if (trendElement && metrics[key].trend) {
                    trendElement.textContent = metrics[key].trend;
                    trendElement.className = `metric-trend ${metrics[key].trend > 0 ? 'up' : 'down'}`;
                }
            }
        });
    }

    animateNumber(element, targetValue) {
        const currentValue = parseInt(element.textContent.replace(/,/g, '')) || 0;
        const increment = (targetValue - currentValue) / 30;
        let current = currentValue;

        const animate = () => {
            current += increment;
            if ((increment > 0 && current >= targetValue) || 
                (increment < 0 && current <= targetValue)) {
                element.textContent = targetValue.toLocaleString();
                return;
            }
            element.textContent = Math.floor(current).toLocaleString();
            requestAnimationFrame(animate);
        };

        animate();
    }

    updateCharts(chartData) {
        Object.keys(chartData).forEach(chartId => {
            const chart = this.charts[chartId];
            if (chart) {
                chart.data = chartData[chartId];
                chart.update('none');
            }
        });
    }

    updateTables(tableData) {
        Object.keys(tableData).forEach(tableId => {
            const table = document.querySelector(`[data-table="${tableId}"]`);
            if (table) {
                this.updateTableContent(table, tableData[tableId]);
            }
        });
    }

    updateTableContent(table, data) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        // Animate out existing rows
        const existingRows = tbody.querySelectorAll('tr');
        existingRows.forEach((row, index) => {
            setTimeout(() => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
            }, index * 50);
        });

        // Add new rows after animation
        setTimeout(() => {
            tbody.innerHTML = '';
            data.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.style.opacity = '0';
                tr.style.transform = 'translateX(20px)';
                
                Object.values(row).forEach(value => {
                    const td = document.createElement('td');
                    td.textContent = value;
                    tr.appendChild(td);
                });
                
                tbody.appendChild(tr);
                
                // Animate in new row
                setTimeout(() => {
                    tr.style.opacity = '1';
                    tr.style.transform = 'translateX(0)';
                }, index * 100);
            });
        }, existingRows.length * 50 + 200);
    }

    initializeCharts() {
        // User Activity Chart
        const userChartCtx = document.getElementById('user-activity-chart');
        if (userChartCtx) {
            this.charts.userActivity = new Chart(userChartCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Active Users',
                        data: [],
                        borderColor: '#00ffff',
                        backgroundColor: 'rgba(0, 255, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#b0b0b0' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        },
                        y: {
                            ticks: { color: '#b0b0b0' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        }
                    }
                }
            });
        }

        // Security Threats Chart
        const securityChartCtx = document.getElementById('security-threats-chart');
        if (securityChartCtx) {
            this.charts.securityThreats = new Chart(securityChartCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Low', 'Medium', 'High', 'Critical'],
                    datasets: [{
                        data: [0, 0, 0, 0],
                        backgroundColor: [
                            '#00ff88',
                            '#00ffff',
                            '#ff6644',
                            '#ff0044'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#ffffff',
                                padding: 20
                            }
                        }
                    }
                }
            });
        }

        // Behavior Analysis Chart
        const behaviorChartCtx = document.getElementById('behavior-analysis-chart');
        if (behaviorChartCtx) {
            this.charts.behaviorAnalysis = new Chart(behaviorChartCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Sessions',
                        data: [],
                        backgroundColor: '#8a2be2',
                        borderColor: '#8a2be2',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#b0b0b0' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        },
                        y: {
                            ticks: { color: '#b0b0b0' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        }
                    }
                }
            });
        }
    }

    startRealTimeUpdates() {
        this.updateInterval = setInterval(() => {
            this.updateRealTimeData();
        }, 30000); // Update every 30 seconds
    }

    async updateRealTimeData() {
        try {
            const response = await fetch('/admin/enhanced-analytics/realtime', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            this.updateMetrics(data.metrics);
            
            // Update real-time indicators
            this.updateRealTimeIndicators(data);
        } catch (error) {
            console.error('Error updating real-time data:', error);
        }
    }

    updateRealTimeIndicators(data) {
        // Update online users count
        const onlineUsers = document.querySelector('.online-users');
        if (onlineUsers && data.onlineUsers) {
            onlineUsers.textContent = data.onlineUsers;
        }

        // Update last activity
        const lastActivity = document.querySelector('.last-activity');
        if (lastActivity && data.lastActivity) {
            lastActivity.textContent = this.formatTime(data.lastActivity);
        }

        // Update system status
        if (data.systemStatus) {
            this.updateSystemStatus(data.systemStatus);
        }
    }

    updateSystemStatus(status) {
        const statusElement = document.querySelector('.system-status');
        if (!statusElement) return;

        statusElement.className = `system-status ${status.toLowerCase()}`;
        statusElement.textContent = status;

        // Add pulse animation for critical status
        if (status === 'CRITICAL') {
            statusElement.style.animation = 'pulse 1s ease-in-out infinite';
        } else {
            statusElement.style.animation = '';
        }
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;

        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return `${Math.floor(diff / 60000)}m ago`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)}h ago`;
        return date.toLocaleDateString();
    }

    toggleRealTime() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
            this.showNotification('Real-time updates disabled', 'info');
        } else {
            this.startRealTimeUpdates();
            this.showNotification('Real-time updates enabled', 'success');
        }
    }

    openSettings() {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal">
                <div class="modal-header">
                    <h3><i class="fas fa-cog"></i> Analytics Settings</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-content">
                    <form id="settings-form" class="settings-form">
                        <div class="form-group">
                            <label for="update-interval">Update Interval (seconds)</label>
                            <input type="number" id="update-interval" name="update_interval" value="30" min="10" max="300">
                        </div>
                        <div class="form-group">
                            <label for="data-retention">Data Retention (days)</label>
                            <input type="number" id="data-retention" name="data_retention" value="90" min="7" max="365">
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="realtime-toggle" name="realtime_enabled" checked>
                                Enable Real-time Updates
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="encryption-enabled" name="encryption_enabled" checked>
                                Enable Data Encryption
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="anomaly-detection" name="anomaly_detection" checked>
                                Enable Anomaly Detection
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-blue" onclick="analytics.saveSettings()">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                    <button class="btn" onclick="analytics.closeModal()">Cancel</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        setTimeout(() => modal.classList.add('show'), 10);
    }

    async saveSettings() {
        const form = document.getElementById('settings-form');
        const formData = new FormData(form);
        const settings = Object.fromEntries(formData.entries());

        this.showLoading();

        try {
            const response = await fetch('/admin/enhanced-analytics/settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(settings)
            });

            if (!response.ok) throw new Error('Failed to save settings');
            
            this.showNotification('Settings saved successfully', 'success');
            this.closeModal();
        } catch (error) {
            console.error('Error saving settings:', error);
            this.showNotification('Error saving settings', 'error');
        } finally {
            this.hideLoading();
        }
    }

    closeModal() {
        const modal = document.querySelector('.modal-overlay');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 300);
        }
    }

    setupNotifications() {
        // Create notification container
        if (!document.querySelector('.notification-container')) {
            const container = document.createElement('div');
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
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

    showLoading() {
        this.isLoading = true;
        document.body.classList.add('loading');
        
        // Add loading overlay
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Loading...</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }

    hideLoading() {
        this.isLoading = false;
        document.body.classList.remove('loading');
        
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }

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

    // Export functionality
    exportData(format = 'csv') {
        this.showLoading();
        
        setTimeout(() => {
            const link = document.createElement('a');
            link.href = `/admin/enhanced-analytics/export?format=${format}`;
            link.download = `analytics-${new Date().toISOString().split('T')[0]}.${format}`;
            link.click();
            
            this.hideLoading();
            this.showNotification(`Data exported as ${format.toUpperCase()}`, 'success');
        }, 1000);
    }

    // Cleanup on page unload
    destroy() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.analytics = new EnhancedAnalytics();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.analytics) {
        window.analytics.destroy();
    }
});

// Add CSS for loading overlay
const loadingStyles = `
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.loading-spinner {
    text-align: center;
    color: #00ffff;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(0, 255, 255, 0.3);
    border-top-color: #00ffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.loading-spinner p {
    font-family: 'Orbitron', monospace;
    font-size: 14px;
    letter-spacing: 1px;
    margin: 0;
}
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = loadingStyles;
document.head.appendChild(styleSheet); 