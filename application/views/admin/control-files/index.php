<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">📁 Управление файлами контрольных работ</h1>
            <p class="admin-subtitle">Загрузка и управление файлами для студентов</p>
        </div>
        <div class="admin-actions">
            <a href="/admin/control-files/upload" class="btn btn-primary">
                <i class="fa fa-upload"></i>
                Загрузить файл
            </a>
        </div>
    </div>
</div>

<div class="admin-content">
    <div class="admin-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa fa-list"></i>
                Загруженные файлы
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
                                                <i class="fa fa-file-pdf-o"></i>
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
                                            <a href="/admin/control-files/edit/<?php echo $file['id']; ?>" 
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
                    <div class="empty-icon">📁</div>
                    <h3>Файлы не найдены</h3>
                    <p>Загрузите первый файл контрольной работы</p>
                    <a href="/admin/control-files/upload" class="btn btn-primary">
                        <i class="fa fa-upload"></i>
                        Загрузить файл
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.file-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-icon {
    color: #EF4444;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.file-details {
    flex: 1;
}

.file-name {
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
}

.file-path {
    font-size: 0.8rem;
    color: #6B7280;
    font-family: monospace;
}

.groups-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.group-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.no-groups {
    color: #9CA3AF;
    font-style: italic;
    font-size: 0.9rem;
}

.description-cell {
    max-width: 200px;
    color: #6B7280;
    line-height: 1.4;
    font-size: 0.9rem;
}

.file-size {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.date-cell {
    text-align: center;
}

.date {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.time {
    color: #9CA3AF;
    font-size: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #374151;
    margin-bottom: 10px;
    font-size: 1.5rem;
}

.empty-state p {
    color: #6B7280;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .admin-table {
        font-size: 0.9rem;
    }
    
    .file-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .groups-list {
        flex-direction: column;
        gap: 4px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Кнопки удаления
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const filename = this.dataset.filename;
            
            if (confirm(`Вы уверены, что хотите удалить файл "${filename}"?\n\nЭто действие нельзя отменить.`)) {
                fetch('/admin/control-files/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('tr').remove();
                        showNotification('success', data.message);
                        
                        // Проверяем, остались ли файлы
                        const tbody = document.querySelector('.admin-table tbody');
                        if (tbody && tbody.children.length === 0) {
                            location.reload(); // Перезагружаем для показа empty state
                        }
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    showNotification('error', 'Ошибка при удалении файла');
                });
            }
        });
    });
});

function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 8px;
        color: white;
        z-index: 1000;
        background: ${type === 'success' ? 'linear-gradient(135deg, #10B981 0%, #34D399 100%)' : 'linear-gradient(135deg, #EF4444 0%, #F87171 100%)'};
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 500;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script> 