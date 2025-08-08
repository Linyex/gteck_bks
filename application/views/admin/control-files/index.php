<?php
// Устанавливаем текущую страницу для активного состояния навигации
$currentPage = 'control-files';
?>

<!-- Control Files Dashboard Content -->
<div class="control-files-dashboard">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-file-pdf-o"></i>
            <h1>Управление файлами контрольных работ</h1>
        </div>
        <div class="page-subtitle">
            Загрузка, управление и организация файлов для студентов
        </div>
        <div class="page-actions">
            <a href="/admin/control-files/upload" class="btn btn-primary">
                <i class="fas fa-upload"></i>
                Загрузить файл
            </a>
        </div>
    </div>

    <!-- Статистика -->
    <div class="metrics-grid">
        <div class="metric-card files">
            <div class="metric-icon">
                <i class="fas fa-file-text"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?php echo $stats['total_files']; ?></div>
                <div class="metric-label">Всего файлов</div>
            </div>
        </div>
        
        <div class="metric-card size">
            <div class="metric-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number">
                    <?php 
                        $total_mb = round($stats['total_size'] / (1024 * 1024), 1);
                        echo $total_mb > 0 ? $total_mb . ' MB' : '0 MB';
                    ?>
                </div>
                <div class="metric-label">Общий размер</div>
            </div>
        </div>
        
        <div class="metric-card groups">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-number"><?php echo $stats['groups_count']; ?></div>
                <div class="metric-label">Активных групп</div>
            </div>
        </div>
    </div>

    <!-- Фильтры и поиск -->
    <div class="admin-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-filter"></i>
                Фильтры и поиск
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="filters-form">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="search">Поиск по названию</label>
                        <input type="text" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Введите название файла...">
                    </div>
                    
                    <div class="filter-group">
                        <label for="group">Фильтр по группе</label>
                        <select id="group" name="group">
                            <option value="">Все группы</option>
                            <?php foreach ($groups as $group): ?>
                                <option value="<?php echo htmlspecialchars($group['group_name']); ?>"
                                        <?php echo $filter_group === $group['group_name'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($group['group_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="filters-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Применить фильтры
                    </button>
                    <a href="/admin/control-files" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Сбросить
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Список файлов -->
    <div class="admin-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-list"></i>
                Список файлов
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($files)): ?>
                <div class="files-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Название файла</th>
                                <th>Группа</th>
                                <th>Размер</th>
                                <th>Дата загрузки</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file): ?>
                                <tr>
                                    <td>
                                        <div class="file-info">
                                            <i class="fas fa-file-pdf-o"></i>
                                            <span><?php echo htmlspecialchars($file['filename']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($file['group_names'] ?? 'Без группы'); ?></td>
                                    <td><?php echo $formatFileSize($file['filesize'] ?? 0); ?></td>
                                    <td><?php echo date('d.m.Y H:i', strtotime($file['upload_date'])); ?></td>
                                    <td>
                                        <div class="file-actions">
                                            <a href="/admin/control-files/download/<?php echo $file['id']; ?>" 
                                               class="btn btn-sm btn-primary" title="Скачать">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="/admin/control-files/edit/<?php echo $file['id']; ?>" 
                                               class="btn btn-sm btn-secondary" title="Редактировать">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteFile(<?php echo $file['id']; ?>)" 
                                                    class="btn btn-sm btn-danger" title="Удалить">
                                                <i class="fas fa-trash"></i>
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
                    <i class="fas fa-file-pdf-o"></i>
                    <h3>Файлы не найдены</h3>
                    <p>Загрузите первый файл контрольной работы</p>
                    <a href="/admin/control-files/upload" class="btn btn-primary">
                        <i class="fas fa-upload"></i>
                        Загрузить файл
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function deleteFile(fileId) {
    if (confirm('Вы уверены, что хотите удалить этот файл?')) {
        fetch(`/admin/control-files/delete/${fileId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Ошибка при удалении файла');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при удалении файла');
        });
    }
}

function formatFileSizeJS(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script> 