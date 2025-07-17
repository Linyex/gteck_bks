<?php $currentPage = 'users'; ?>
<?php include 'application/views/admin/layouts/main.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-users"></i> Управление пользователями</h2>
            <?php if ($this->isAdmin()): ?>
                <a href="/admin/users/create" class="btn btn-admin btn-primary">
                    <i class="fas fa-plus"></i> Добавить пользователя
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">Список пользователей (<?= $totalUsers ?>)</h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Поиск пользователей..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-admin mb-0">
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
                                        <span class="badge badge-admin bg-info">Вы</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($user['user_fio']) ?></td>
                                <td>
                                    <?php if ($user['user_status']): ?>
                                        <span class="badge badge-admin bg-success">Активен</span>
                                    <?php else: ?>
                                        <span class="badge badge-admin bg-secondary">Неактивен</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $levelText = '';
                                    $levelClass = '';
                                    switch ($user['user_access_level']) {
                                        case 10:
                                            $levelText = 'Администратор';
                                            $levelClass = 'bg-danger';
                                            break;
                                        case 5:
                                            $levelText = 'Модератор';
                                            $levelClass = 'bg-warning';
                                            break;
                                        default:
                                            $levelText = 'Пользователь';
                                            $levelClass = 'bg-info';
                                    }
                                    ?>
                                    <span class="badge badge-admin <?= $levelClass ?>"><?= $levelText ?></span>
                                </td>
                                <td><?= date('d.m.Y H:i', strtotime($user['user_date_reg'])) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin/users/edit/<?= $user['user_id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user['user_id'] != $_SESSION['admin_user_id'] && $this->isAdmin()): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteUser(<?= $user['user_id'] ?>, '<?= htmlspecialchars($user['user_fio']) ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Пользователей пока нет</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <nav aria-label="Навигация по страницам">
                <ul class="pagination justify-content-center mb-0">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/admin/users?page=<?= $currentPage - 1 ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="/admin/users?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/admin/users?page=<?= $currentPage + 1 ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно для подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Вы действительно хотите удалить пользователя <strong id="userName"></strong>?</p>
                <p class="text-danger"><small>Это действие нельзя отменить!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = '/admin/users/delete/' + userId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

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