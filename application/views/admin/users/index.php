<div class="admin-main">
    <div class="main-header">
        <h1>Управление пользователями</h1>
        <p>Просмотр и редактирование пользователей системы</p>
    </div>

    <div class="users-container">
        <?php if ($this->isAdmin()): ?>
            <div class="action-bar">
                <a href="/admin/users/create" class="btn btn-blue">
                    <i class="fas fa-plus"></i> Добавить пользователя
                </a>
            </div>
        <?php endif; ?>

        <div class="cyber-card">
            <div class="card-header">
                <h3>Список пользователей (<?= $totalUsers ?>)</h3>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Поиск пользователей..." class="form-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Логин</th>
                            <th>ФИО</th>
                            <th>Статус</th>
                            <th>Уровень доступа</th>
                            <th>Дата регистрации</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['user_id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($user['user_login']) ?></strong>
                                        <?php if ($user['user_id'] == $_SESSION['admin_user_id']): ?>
                                            <span class="badge badge-current">Вы</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($user['user_fio']) ?></td>
                                    <td>
                                        <?php if ($user['user_status']): ?>
                                            <span class="badge badge-success">Активен</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Неактивен</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $levelText = '';
                                        $levelClass = '';
                                        switch ($user['user_access_level']) {
                                            case 10:
                                                $levelText = 'Администратор';
                                                $levelClass = 'badge-danger';
                                                break;
                                            case 5:
                                                $levelText = 'Модератор';
                                                $levelClass = 'badge-warning';
                                                break;
                                            default:
                                                $levelText = 'Пользователь';
                                                $levelClass = 'badge-info';
                                        }
                                        ?>
                                        <span class="badge <?= $levelClass ?>"><?= $levelText ?></span>
                                    </td>
                                    <td><?= date('d.m.Y H:i', strtotime($user['user_date_reg'])) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/admin/users/edit/<?= $user['user_id'] ?>" class="btn btn-sm btn-blue" title="Редактировать">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($user['user_id'] != $_SESSION['admin_user_id'] && $this->isAdmin()): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deleteUser(<?= $user['user_id'] ?>, '<?= htmlspecialchars($user['user_fio']) ?>')" title="Удалить">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <p>Пользователей пока нет</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pagination-container">
                    <nav class="pagination-nav">
                        <?php if ($currentPage > 1): ?>
                            <a href="/admin/users?page=<?= $currentPage - 1 ?>" class="page-link">
                                <i class="fas fa-chevron-left"></i> Назад
                            </a>
                        <?php endif; ?>
                        
                        <div class="page-numbers">
                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <a href="/admin/users?page=<?= $i ?>" class="page-link <?= $i == $currentPage ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="/admin/users?page=<?= $currentPage + 1 ?>" class="page-link">
                                Вперед <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Модальное окно для подтверждения удаления -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Подтверждение удаления</h3>
            <button type="button" class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Вы действительно хотите удалить пользователя <strong id="userName"></strong>?</p>
            <p class="warning-text">Это действие нельзя отменить!</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Отмена</button>
            <form id="deleteForm" method="POST">
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        </div>
    </div>
</div>

<style>
.users-container {
    padding: 20px;
}

.action-bar {
    margin-bottom: 20px;
    display: flex;
    justify-content: flex-end;
}

.search-box {
    position: relative;
    max-width: 300px;
}

.search-box .form-input {
    padding-right: 40px;
}

.search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-blue);
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: #000;
}

.badge-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: #fff;
}

.badge-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: #fff;
}

.badge-warning {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    color: #000;
}

.badge-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: #fff;
}

.badge-current {
    background: linear-gradient(135deg, var(--primary-yellow), var(--accent-yellow));
    color: #000;
    font-size: 10px;
    margin-left: 5px;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-sm {
    padding: 6px 10px;
    font-size: 12px;
}

.empty-state {
    padding: 40px;
    text-align: center;
    color: var(--text-blue);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 16px;
    opacity: 0.8;
}

.pagination-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

.pagination-nav {
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-numbers {
    display: flex;
    gap: 5px;
}

.page-link {
    padding: 8px 12px;
    border: 1px solid var(--primary-blue);
    background: transparent;
    color: var(--text-white);
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: var(--primary-blue);
    color: #000;
    box-shadow: var(--glow-blue);
}

.page-link.active {
    background: var(--primary-blue);
    color: #000;
    box-shadow: var(--glow-blue);
}

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    backdrop-filter: blur(5px);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(135deg, var(--dark-gray) 0%, var(--medium-gray) 100%);
    border: 1px solid var(--primary-blue);
    border-radius: 12px;
    padding: 0;
    min-width: 400px;
    box-shadow: var(--glow-blue);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid var(--primary-blue);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    color: var(--text-yellow);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-white);
    font-size: 18px;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: var(--primary-blue);
    color: #000;
}

.modal-body {
    padding: 20px;
}

.modal-body p {
    margin: 0 0 10px 0;
    color: var(--text-white);
}

.warning-text {
    color: var(--primary-yellow) !important;
    font-size: 14px;
    font-style: italic;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid var(--primary-blue);
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    .users-container {
        padding: 10px;
    }
    
    .action-bar {
        justify-content: center;
    }
    
    .search-box {
        max-width: 100%;
        margin-top: 15px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modal-content {
        min-width: 90%;
        margin: 20px;
    }
}
</style>

<script>
function deleteUser(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = '/admin/users/delete/' + userId;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Закрытие модального окна при клике вне его
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Поиск пользователей
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script> 