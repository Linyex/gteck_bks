<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - CyberAdmin</title>

    <!-- Основные стили -->
    <link rel="stylesheet" href="/assets/css/admin-cyberpunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Дополнительные стили -->
    <?php $navPage = 'security'; if (!empty($additional_css)) foreach ($additional_css as $css): ?>
        <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>

    <style>
        .sessions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .sessions-table th,
        .sessions-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 255, 255, 0.2);
        }

        .sessions-table th {
            background: rgba(0, 255, 255, 0.1);
            color: #00ffff;
            font-weight: 600;
        }

        .sessions-table tr:hover {
            background: rgba(0, 255, 255, 0.05);
        }

        .session-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .session-status.active { 
            background: rgba(0, 255, 0, 0.2); 
            color: #00ff00; 
        }

        .session-status.inactive { 
            background: rgba(255, 0, 0, 0.2); 
            color: #ff0000; 
        }

        .session-actions {
            display: flex;
            gap: 8px;
        }

        .btn-terminate {
            background: rgba(255, 0, 0, 0.2);
            color: #ff0000;
            border: 1px solid rgba(255, 0, 0, 0.3);
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.8em;
            transition: all 0.3s ease;
        }

        .btn-terminate:hover {
            background: rgba(255, 0, 0, 0.3);
        }

        .session-info {
            font-size: 0.9em;
            color: #b0b0b0;
        }

        .session-duration {
            color: #00ffff;
            font-weight: 600;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(0, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            color: #00ffff;
            font-weight: bold;
        }

        .stat-label {
            color: #b0b0b0;
            margin-top: 8px;
        }

        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-group label {
            color: #b0b0b0;
            font-size: 0.9em;
        }

        .filter-group select,
        .filter-group input {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0, 255, 255, 0.3);
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #00ffff;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-users"></i> Управление сессиями</h1>
            <p>Мониторинг и управление активными сессиями пользователей</p>
        </div>

        <!-- Статистика -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number"><?= count($sessions) ?></div>
                <div class="stat-label">Активных сессий</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_unique(array_column($sessions, 'user_id'))) ?></div>
                <div class="stat-label">Уникальных пользователей</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_filter($sessions, function($s) { return strpos($s['ip_address'], '127.0.0.1') !== false || strpos($s['ip_address'], '::1') !== false; })) ?></div>
                <div class="stat-label">Локальных подключений</div>
            </div>
        </div>

        <!-- Фильтры -->
        <div class="filters">
            <div class="filter-group">
                <label>Пользователь:</label>
                <select id="userFilter">
                    <option value="">Все пользователи</option>
                    <?php foreach (array_unique(array_column($sessions, 'user_fio')) as $user): ?>
                        <?php if ($user): ?>
                            <option value="<?= htmlspecialchars($user) ?>"><?= htmlspecialchars($user) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>IP адрес:</label>
                <select id="ipFilter">
                    <option value="">Все IP</option>
                    <?php foreach (array_unique(array_column($sessions, 'ip_address')) as $ip): ?>
                        <?php if ($ip): ?>
                            <option value="<?= htmlspecialchars($ip) ?>"><?= htmlspecialchars($ip) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <button class="btn btn-primary" onclick="refreshSessions()">
                    <i class="fas fa-sync-alt"></i> Обновить
                </button>
            </div>
        </div>

        <!-- Таблица сессий -->
        <div class="admin-content">
            <?php if (empty($sessions)): ?>
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h3>Нет активных сессий</h3>
                    <p>В данный момент нет активных сессий пользователей</p>
                </div>
            <?php else: ?>
                <table class="sessions-table">
                    <thead>
                        <tr>
                            <th>Пользователь</th>
                            <th>IP адрес</th>
                            <th>Последняя активность</th>
                            <th>Длительность</th>
                            <th>User Agent</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $session): ?>
                            <?php
                            $lastActivity = new DateTime($session['last_activity']);
                            $created = new DateTime($session['created_at']);
                            $duration = $lastActivity->diff($created);
                            $durationText = '';
                            if ($duration->days > 0) {
                                $durationText = $duration->days . 'д ' . $duration->h . 'ч';
                            } elseif ($duration->h > 0) {
                                $durationText = $duration->h . 'ч ' . $duration->i . 'м';
                            } else {
                                $durationText = $duration->i . 'м';
                            }
                            
                            $isLocal = strpos($session['ip_address'], '127.0.0.1') !== false || 
                                      strpos($session['ip_address'], '::1') !== false ||
                                      strpos($session['ip_address'], 'localhost') !== false;
                            ?>
                            <tr data-user="<?= htmlspecialchars($session['user_fio'] ?? '') ?>" 
                                data-ip="<?= htmlspecialchars($session['ip_address'] ?? '') ?>">
                                <td>
                                    <div class="session-info">
                                        <strong><?= htmlspecialchars($session['user_fio'] ?? 'Неизвестно') ?></strong>
                                        <?php if ($session['user_login']): ?>
                                            <br><small><?= htmlspecialchars($session['user_login']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="<?= $isLocal ? 'session-duration' : '' ?>">
                                        <?= htmlspecialchars($session['ip_address'] ?? 'Неизвестно') ?>
                                    </span>
                                </td>
                                <td><?= $lastActivity->format('d.m.Y H:i:s') ?></td>
                                <td class="session-duration"><?= $durationText ?></td>
                                <td>
                                    <div class="session-info">
                                        <?= htmlspecialchars(substr($session['user_agent'] ?? '', 0, 50)) ?>
                                        <?php if (strlen($session['user_agent'] ?? '') > 50): ?>
                                            <span title="<?= htmlspecialchars($session['user_agent']) ?>">...</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="session-status active">Активна</span>
                                </td>
                                <td>
                                    <div class="session-actions">
                                        <form method="POST" action="/admin/security/terminate-session" style="display: inline;">
                                            <input type="hidden" name="session_id" value="<?= htmlspecialchars($session['session_token']) ?>">
                                            <button type="submit" class="btn-terminate" 
                                                    onclick="return confirm('Завершить эту сессию?')">
                                                <i class="fas fa-times"></i> Завершить
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Дополнительные скрипты -->
    <?php if (!empty($additional_js)) foreach ($additional_js as $js): ?>
        <script src="<?= $js ?>"></script>
    <?php endforeach; ?>

    <script>
        // Фильтрация сессий
        document.getElementById('userFilter').addEventListener('change', filterSessions);
        document.getElementById('ipFilter').addEventListener('change', filterSessions);

        function filterSessions() {
            const userFilter = document.getElementById('userFilter').value;
            const ipFilter = document.getElementById('ipFilter').value;
            const rows = document.querySelectorAll('.sessions-table tbody tr');

            rows.forEach(row => {
                const user = row.getAttribute('data-user');
                const ip = row.getAttribute('data-ip');
                
                const userMatch = !userFilter || user.includes(userFilter);
                const ipMatch = !ipFilter || ip.includes(ipFilter);
                
                row.style.display = userMatch && ipMatch ? '' : 'none';
            });
        }

        function refreshSessions() {
            location.reload();
        }

        // Автообновление каждые 30 секунд
        setInterval(refreshSessions, 30000);
    </script>
</body>
</html> 