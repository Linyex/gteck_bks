<style>
.audit-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.audit-table th,
.audit-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid rgba(0, 255, 255, 0.2);
}

.audit-table th {
    background: rgba(0, 255, 255, 0.1);
    color: #00ffff;
    font-weight: 600;
}

.audit-table tr:hover {
    background: rgba(0, 255, 255, 0.05);
}

.action-type {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8em;
    font-weight: 600;
}

.action-type.login_success { background: rgba(0, 255, 0, 0.2); color: #00ff00; }
.action-type.login_failed { background: rgba(255, 0, 0, 0.2); color: #ff0000; }
.action-type.admin_action { background: rgba(255, 165, 0, 0.2); color: #ffa500; }
.action-type.data_change { background: rgba(0, 0, 255, 0.2); color: #0000ff; }
.action-type.file_access { background: rgba(128, 0, 128, 0.2); color: #800080; }

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 30px;
    gap: 10px;
}

.pagination a,
.pagination span {
    padding: 8px 12px;
    border: 1px solid rgba(0, 255, 255, 0.3);
    border-radius: 4px;
    text-decoration: none;
    color: #00ffff;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background: rgba(0, 255, 255, 0.1);
}

.pagination .current {
    background: rgba(0, 255, 255, 0.2);
    border-color: #00ffff;
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
    padding: 6px 10px;
    border: 1px solid rgba(0, 255, 255, 0.3);
    border-radius: 4px;
    background: rgba(0, 0, 0, 0.3);
    color: #ffffff;
}

.filter-group select:focus,
.filter-group input:focus {
    outline: none;
    border-color: #00ffff;
}

.export-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.btn-export {
    padding: 8px 16px;
    border: 1px solid rgba(0, 255, 255, 0.3);
    border-radius: 4px;
    background: rgba(0, 0, 0, 0.3);
    color: #00ffff;
    text-decoration: none;
    font-size: 0.9em;
    transition: all 0.3s ease;
}

.btn-export:hover {
    background: rgba(0, 255, 255, 0.1);
}
</style>

<?php
// Устанавливаем текущую страницу для активного состояния навигации
$currentPage = 'security';
?>

<!-- Security Audit Content -->
<div class="security-audit-dashboard">

        <!-- Main Content -->
        <main class="admin-main">
            <div class="content-header">
                <h1><i class="fas fa-clipboard-list"></i> Аудит безопасности</h1>
                <p>Просмотр и анализ логов безопасности системы</p>
            </div>

            <!-- Filters -->
            <div class="filters">
                <div class="filter-group">
                    <label>Тип действия:</label>
                    <select id="actionTypeFilter">
                        <option value="">Все типы</option>
                        <option value="login_success">Успешный вход</option>
                        <option value="login_failed">Неудачный вход</option>
                        <option value="admin_action">Админ действие</option>
                        <option value="data_change">Изменение данных</option>
                        <option value="file_access">Доступ к файлам</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Пользователь:</label>
                    <input type="text" id="userFilter" placeholder="ID пользователя">
                </div>

                <div class="filter-group">
                    <label>IP адрес:</label>
                    <input type="text" id="ipFilter" placeholder="IP адрес">
                </div>

                <div class="filter-group">
                    <label>Дата:</label>
                    <input type="date" id="dateFilter">
                </div>

                <button class="btn btn-primary" onclick="applyFilters()">
                    <i class="fas fa-filter"></i> Применить
                </button>
            </div>

            <!-- Export Actions -->
            <div class="export-actions">
                <a href="/admin/security/export-logs?format=json" class="btn-export">
                    <i class="fas fa-download"></i> Экспорт JSON
                </a>
                <a href="/admin/security/export-logs?format=csv" class="btn-export">
                    <i class="fas fa-download"></i> Экспорт CSV
                </a>
                <button class="btn-export" onclick="cleanupLogs()">
                    <i class="fas fa-trash"></i> Очистить старые логи
                </button>
            </div>

            <!-- Audit Table -->
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-table"></i> Логи безопасности</h2>
                    <div class="section-actions">
                        <button class="btn btn-primary" onclick="refreshAudit()">
                            <i class="fas fa-sync-alt"></i> Обновить
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="audit-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Тип действия</th>
                                <th>IP адрес</th>
                                <th>Детали</th>
                                <th>Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?= $log['id'] ?></td>
                                        <td>
                                            <?php if ($log['user_fio']): ?>
                                                <?= htmlspecialchars($log['user_fio']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Гость</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="action-type <?= $log['action_type'] ?>">
                                                <?= htmlspecialchars($log['action_type']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($log['ip_address'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php if ($log['action_details']): ?>
                                                <button class="btn btn-sm" onclick="showDetails(<?= $log['id'] ?>)">
                                                    <i class="fas fa-eye"></i> Просмотр
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">Нет деталей</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d.m.Y H:i:s', strtotime($log['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p>Логи не найдены</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>">
                                <i class="fas fa-chevron-left"></i> Назад
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $currentPage): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>">
                                Вперед <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<!-- Modal for Details -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Детали действия</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <pre id="detailsContent"></pre>
        </div>
    </div>
</div>

<script>
// Функции для работы с аудитом
function applyFilters() {
    const actionType = document.getElementById('actionTypeFilter').value;
    const userFilter = document.getElementById('userFilter').value;
    const ipFilter = document.getElementById('ipFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;

    let url = '/admin/security/audit?';
    if (actionType) url += `action_type=${actionType}&`;
    if (userFilter) url += `user_id=${userFilter}&`;
    if (ipFilter) url += `ip_address=${ipFilter}&`;
    if (dateFilter) url += `date=${dateFilter}&`;

    window.location.href = url;
}

function refreshAudit() {
    location.reload();
}

function cleanupLogs() {
    if (confirm('Вы уверены, что хотите удалить старые логи? Это действие нельзя отменить.')) {
        fetch('/admin/security/cleanup-logs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                days: 90
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Удалено ${data.data.deleted_count} записей`);
                location.reload();
            } else {
                alert('Ошибка при очистке логов');
            }
        });
    }
}

function showDetails(logId) {
    // Здесь можно добавить AJAX запрос для получения деталей
    document.getElementById('detailsModal').style.display = 'block';
    document.getElementById('detailsContent').textContent = 'Детали действия будут загружены...';
}

function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Закрытие модального окна при клике вне его
window.onclick = function(event) {
    const modal = document.getElementById('detailsModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script> 