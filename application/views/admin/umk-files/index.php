<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">📚 Управление файлами УМК</h1>
            <p class="admin-subtitle">Загрузка и управление учебно-методическими комплексами</p>
        </div>
        <div class="admin-actions">
            <a href="/admin/umk-files/upload" class="btn btn-primary">
                <i class="fa fa-upload"></i>
                Загрузить УМК
            </a>
        </div>
    </div>
</div>

<div class="admin-content">
    <div class="admin-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa fa-list"></i>
                Загруженные УМК
            </div>
            <div class="card-stats">
                <span class="stat-badge">
                    <i class="fa fa-file"></i>
                    <?php echo count($files); ?> файлов
                </span>
            </div>
        </div>

        <div class="card-body">
            <?php if (!empty($files)): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Файл</th>
                                <th>Группы</th>
                                <th>Описание</th>
                                <th>Размер</th>
                                <th>Загружен</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file): ?>
                                <tr>
                                    <td>
                                        <div class="file-info">
                                            <div class="file-icon">
                                                <i class="fa fa-book"></i>
                                            </div>
                                            <div class="file-details">
                                                <div class="file-name"><?php echo htmlspecialchars($file['filename']); ?></div>
                                                <div class="file-path"><?php echo htmlspecialchars($file['path']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="groups-list">
                                            <?php if (!empty($file['group_names'])): ?>
                                                <?php foreach (explode(',', $file['group_names']) as $group): ?>
                                                    <span class="group-badge"><?php echo htmlspecialchars(trim($group)); ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="no-groups">Не привязан к группам</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="description-cell">
                                            <?php echo htmlspecialchars(isset($file['description']) ? $file['description'] : 'Нет описания'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="file-size">
                                            <?php 
                                                $file_path = $_SERVER['DOCUMENT_ROOT'] . $file['path'];
                                                if (file_exists($file_path)) {
                                                    $size = filesize($file_path);
                                                    echo $size > 1024 * 1024 ? round($size / (1024 * 1024), 1) . ' MB' : round($size / 1024, 1) . ' KB';
                                                } else {
                                                    echo 'Файл не найден';
                                                }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <div class="date"><?php echo date('d.m.Y', strtotime($file['upload_date'])); ?></div>
                                            <div class="time"><?php echo date('H:i', strtotime($file['upload_date'])); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo htmlspecialchars($file['path']); ?>" 
                                               target="_blank"
                                               class="btn btn-sm btn-outline-info" 
                                               title="Скачать">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <a href="/admin/umk-files/edit/<?php echo $file['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Редактировать">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                    data-id="<?php echo $file['id']; ?>"
                                                    data-filename="<?php echo htmlspecialchars($file['filename']); ?>"
                                                    title="Удалить">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📚</div>
                    <h3>УМК файлы не найдены</h3>
                    <p>Загрузите первый файл УМК для начала работы</p>
                    <a href="/admin/umk-files/upload" class="btn btn-primary">
                        <i class="fa fa-upload"></i>
                        Загрузить УМК
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Обработка удаления файлов
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        const filename = $(this).data('filename');
        
        if (confirm('Вы уверены, что хотите удалить файл "' + filename + '"?\n\nЭто действие нельзя отменить.')) {
            $.post('/admin/umk-files/delete', {
                id: id
            })
            .done(function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Ошибка: ' + response.message);
                }
            })
            .fail(function() {
                alert('Произошла ошибка при удалении файла');
            });
        }
    });
});
</script> 