<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детальная аналитика активности</title>
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="/assets/css/enhanced-analytics.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .logs-controls {
            background: rgba(22, 33, 62, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .controls-grid {
            display: grid;
            grid-template-columns: 1fr auto auto auto;
            gap: 1rem;
            align-items: end;
        }

        .search-group {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .filter-select {
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .logs-table-container {
            background: rgba(22, 33, 62, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .logs-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .logs-table th {
            background: rgba(102, 126, 234, 0.1);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            position: relative;
            cursor: pointer;
            transition: var(--transition);
        }

        .logs-table th:hover {
            background: rgba(102, 126, 234, 0.2);
        }

        .logs-table th.sortable::after {
            content: '↕';
            position: absolute;
            right: 1rem;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .logs-table th.sort-asc::after {
            content: '↑';
            color: var(--success-color);
        }

        .logs-table th.sort-desc::after {
            content: '↓';
            color: var(--success-color);
        }

        .logs-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(15, 52, 96, 0.3);
            transition: var(--transition);
        }

        .logs-table tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .logs-table tr:hover td {
            background: rgba(102, 126, 234, 0.05);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .user-login {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .action-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-login { background: rgba(40, 167, 69, 0.2); color: #28a745; }
        .action-logout { background: rgba(220, 53, 69, 0.2); color: #dc3545; }
        .action-page_view { background: rgba(102, 126, 234, 0.2); color: #667eea; }
        .action-file_download { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
        .action-form_submit { background: rgba(23, 162, 184, 0.2); color: #17a2b8; }

        .ip-address {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.05);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .time-stamp {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
            font-style: italic;
        }

        .results-info {
            padding: 1rem;
            background: rgba(102, 126, 234, 0.1);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .controls-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .logs-table {
                font-size: 0.8rem;
            }
            
            .logs-table th,
            .logs-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="animated-background"></div>
    <div class="background-overlay"></div>

    <div class="admin-container">
        <?php include 'application/views/admin/common/sidebar.php'; ?>

        <main class="admin-main">
            <div class="main-container">
                <div class="page-header">
                    <a href="/admin/enhanced-analytics" class="btn btn-sm">
                        <i class="fas fa-arrow-left"></i> Назад к аналитике
                    </a>
                    <h1>Детальная аналитика активности</h1>
                    <p>Подробный анализ действий пользователей</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['total_activities'] ?? 0 ?></div>
                            <div class="stat-label">Всего действий</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['unique_users'] ?? 0 ?></div>
                            <div class="stat-label">Уникальных пользователей</div>
                        </div>
                    </div>
                </div>

                <div class="charts-section">
                    <div class="chart-row">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Активность по часам</h3>
                            </div>
                            <div class="chart-container">
                                <canvas id="hourlyChart"></canvas>
                            </div>
                        </div>
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Типы активности</h3>
                            </div>
                            <div class="chart-container">
                                <canvas id="activityTypesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="logs-section">
                    <div class="section-header">
                        <h3>Детальные логи активности</h3>
                    </div>
                    
                    <div class="logs-controls">
                        <form method="GET" class="controls-grid">
                            <input type="hidden" name="period" value="<?= htmlspecialchars($_GET['period'] ?? '7') ?>">
                            <input type="hidden" name="activity_type" value="<?= htmlspecialchars($_GET['activity_type'] ?? '') ?>">
                            
                            <div class="search-group">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" 
                                       name="search" 
                                       class="search-input" 
                                       placeholder="Поиск по пользователю, действию или IP..."
                                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                            
                            <select name="sort_by" class="filter-select">
                                <option value="activity_time" <?= ($_GET['sort_by'] ?? 'activity_time') === 'activity_time' ? 'selected' : '' ?>>По времени</option>
                                <option value="user_fio" <?= ($_GET['sort_by'] ?? '') === 'user_fio' ? 'selected' : '' ?>>По пользователю</option>
                                <option value="action_type" <?= ($_GET['sort_by'] ?? '') === 'action_type' ? 'selected' : '' ?>>По типу действия</option>
                                <option value="ip_address" <?= ($_GET['sort_by'] ?? '') === 'ip_address' ? 'selected' : '' ?>>По IP адресу</option>
                            </select>
                            
                            <select name="sort_order" class="filter-select">
                                <option value="DESC" <?= ($_GET['sort_order'] ?? 'DESC') === 'DESC' ? 'selected' : '' ?>>По убыванию</option>
                                <option value="ASC" <?= ($_GET['sort_order'] ?? '') === 'ASC' ? 'selected' : '' ?>>По возрастанию</option>
                            </select>
                            
                            <button type="submit" class="btn">
                                <i class="fas fa-filter"></i> Применить
                            </button>
                        </form>
                    </div>

                    <div class="logs-table-container">
                        <div class="results-info">
                            Найдено записей: <?= count($activityLogs) ?>
                            <?php if (!empty($_GET['search'])): ?>
                                по запросу "<?= htmlspecialchars($_GET['search']) ?>"
                            <?php endif; ?>
                        </div>
                        
                        <table class="logs-table">
                            <thead>
                                <tr>
                                    <th class="sortable <?= ($_GET['sort_by'] ?? 'activity_time') === 'activity_time' ? ($_GET['sort_order'] ?? 'DESC') === 'DESC' ? 'sort-desc' : 'sort-asc' : '' ?>">
                                        Время
                                    </th>
                                    <th class="sortable <?= ($_GET['sort_by'] ?? '') === 'user_fio' ? ($_GET['sort_order'] ?? '') === 'DESC' ? 'sort-desc' : 'sort-asc' : '' ?>">
                                        Пользователь
                                    </th>
                                    <th class="sortable <?= ($_GET['sort_by'] ?? '') === 'action_type' ? ($_GET['sort_order'] ?? '') === 'DESC' ? 'sort-desc' : 'sort-asc' : '' ?>">
                                        Тип действия
                                    </th>
                                    <th class="sortable <?= ($_GET['sort_by'] ?? '') === 'ip_address' ? ($_GET['sort_order'] ?? '') === 'DESC' ? 'sort-desc' : 'sort-asc' : '' ?>">
                                        IP адрес
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($activityLogs)): ?>
                                    <?php foreach ($activityLogs as $log): ?>
                                        <tr>
                                            <td>
                                                <div class="time-stamp">
                                                    <?= date('d.m.Y H:i:s', strtotime($log['activity_time'])) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="user-info">
                                                    <div class="user-avatar">
                                                        <?= strtoupper(substr($log['user_fio'] ?? $log['user_login'] ?? 'U', 0, 1)) ?>
                                                    </div>
                                                    <div class="user-details">
                                                        <div class="user-name">
                                                            <?= htmlspecialchars($log['user_fio'] ?? 'Неизвестный') ?>
                                                        </div>
                                                        <div class="user-login">
                                                            <?= htmlspecialchars($log['user_login'] ?? '') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="action-badge action-<?= strtolower(str_replace('_', '', $log['action_type'])) ?>">
                                                    <?= htmlspecialchars($log['action_type']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="ip-address">
                                                    <?= htmlspecialchars($log['ip_address'] ?? 'N/A') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="no-data">
                                            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block; color: var(--text-secondary);"></i>
                                            Нет данных для отображения
                                            <?php if (!empty($_GET['search'])): ?>
                                                <br><small>Попробуйте изменить параметры поиска</small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const activityData = <?= json_encode($chartData ?? []) ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            initHourlyChart();
            initActivityTypesChart();
            initTableSorting();
        });

        function initHourlyChart() {
            const ctx = document.getElementById('hourlyChart').getContext('2d');
            const hourlyData = activityData.hourly_activity || [];
            
            const labels = hourlyData.map(item => item.hour + ':00');
            const data = hourlyData.map(item => item.count);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Активность',
                        data: data,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: '#fff' }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            ticks: { color: '#fff' }
                        },
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            ticks: { color: '#fff' }
                        }
                    }
                }
            });
        }

        function initActivityTypesChart() {
            const ctx = document.getElementById('activityTypesChart').getContext('2d');
            const typesData = activityData.activity_types || [];
            
            const labels = typesData.map(item => item.type);
            const data = typesData.map(item => item.count);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#667eea', '#28a745', '#ffc107', '#dc3545', '#17a2b8']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#fff' }
                        }
                    }
                }
            });
        }

        function initTableSorting() {
            const sortableHeaders = document.querySelectorAll('.logs-table th.sortable');
            
            sortableHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const currentSortBy = this.textContent.trim().toLowerCase();
                    const currentSortOrder = '<?= $_GET['sort_order'] ?? 'DESC' ?>';
                    const newSortOrder = currentSortOrder === 'DESC' ? 'ASC' : 'DESC';
                    
                    // Определяем параметр сортировки
                    let sortBy = 'activity_time';
                    if (currentSortBy.includes('пользователь')) sortBy = 'user_fio';
                    else if (currentSortBy.includes('тип действия')) sortBy = 'action_type';
                    else if (currentSortBy.includes('ip адрес')) sortBy = 'ip_address';
                    
                    // Создаем форму для отправки параметров
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = window.location.pathname;
                    
                    // Добавляем все текущие параметры
                    const currentParams = new URLSearchParams(window.location.search);
                    currentParams.set('sort_by', sortBy);
                    currentParams.set('sort_order', newSortOrder);
                    
                    // Создаем скрытые поля для всех параметров
                    for (const [key, value] of currentParams.entries()) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        form.appendChild(input);
                    }
                    
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        }
    </script>
</body>
</html> 