<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">✏️ Редактировать файл</h1>
            <p class="admin-subtitle">Изменение параметров файла: <?php echo htmlspecialchars($file['filename']); ?></p>
        </div>
        <div class="admin-actions">
            <a href="/admin/control-files" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left"></i>
                Назад к списку
            </a>
        </div>
    </div>
</div>

<div class="admin-content">
    <div class="form-container">
        <div class="admin-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa fa-edit"></i>
                    Параметры файла
                </div>
            </div>

            <div class="card-body">
                <form method="POST" class="admin-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="filename" class="form-label required">
                                <i class="fa fa-tag"></i>
                                Название файла
                            </label>
                            <input type="text" 
                                   id="filename" 
                                   name="filename" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($file['filename']); ?>"
                                   required>
                            <div class="form-help">
                                Отображаемое название файла для студентов
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa fa-file"></i>
                                Текущий файл
                            </label>
                            <div class="current-file">
                                <div class="file-info">
                                    <div class="file-icon">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"><?php echo htmlspecialchars($file['filename']); ?></div>
                                        <div class="file-path"><?php echo htmlspecialchars($file['path']); ?></div>
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
                                    </div>
                                    <a href="<?php echo htmlspecialchars($file['path']); ?>" 
                                       target="_blank" 
                                       class="download-btn">
                                        <i class="fa fa-download"></i>
                                        Скачать
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="group_names" class="form-label required">
                                <i class="fa fa-users"></i>
                                Группы
                            </label>
                            <div class="groups-selection">
                                <?php foreach ($groups as $group): ?>
                                    <label class="group-checkbox">
                                        <input type="checkbox" 
                                               name="group_names[]" 
                                               value="<?php echo htmlspecialchars($group['group_name']); ?>"
                                               class="checkbox-input"
                                               <?php echo in_array($group['group_name'], $file_group_names) ? 'checked' : ''; ?>>
                                        <span class="checkbox-custom"></span>
                                        <span class="checkbox-text"><?php echo htmlspecialchars($group['group_name']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="form-help">
                                Группы, которые имеют доступ к файлу
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="description" class="form-label">
                                <i class="fa fa-info-circle"></i>
                                Описание
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Краткое описание файла"><?php echo htmlspecialchars($file['description']); ?></textarea>
                            <div class="form-help">
                                Описание для удобства поиска и идентификации
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>
                            Сохранить изменения
                        </button>
                        <a href="/admin/control-files" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i>
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Информация о файле -->
        <div class="admin-card info-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa fa-info-circle"></i>
                    Информация о файле
                </div>
            </div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-label">Загружен:</div>
                        <div class="info-value">
                            <?php echo date('d.m.Y в H:i', strtotime($file['upload_date'])); ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Путь к файлу:</div>
                        <div class="info-value file-path">
                            <?php echo htmlspecialchars($file['path']); ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Размер:</div>
                        <div class="info-value">
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
                    </div>
                    <div class="info-item">
                        <div class="info-label">Текущие группы:</div>
                        <div class="info-value">
                            <div class="current-groups">
                                <?php if (!empty($file_group_names)): ?>
                                    <?php foreach ($groups as $group): ?>
                                        <?php if (in_array($group['group_name'], $file_group_names)): ?>
                                            <span class="group-badge"><?php echo htmlspecialchars($group['group_name']); ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="no-groups">Не привязан к группам</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="warning-notice">
                    <div class="notice-icon">⚠️</div>
                    <div class="notice-content">
                        <h4>Внимание</h4>
                        <p>Изменение привязки к группам повлияет на доступность файла для студентов.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.current-file {
    background: rgba(102, 126, 234, 0.05);
    border: 1px solid rgba(102, 126, 234, 0.1);
    border-radius: 12px;
    padding: 20px;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.file-icon {
    color: #EF4444;
    font-size: 2rem;
    flex-shrink: 0;
}

.file-details {
    flex: 1;
}

.file-name {
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
    font-size: 1.1rem;
}

.file-path {
    font-size: 0.9rem;
    color: #6B7280;
    font-family: monospace;
    margin-bottom: 4px;
}

.file-size {
    font-size: 0.9rem;
    color: #9CA3AF;
}

.download-btn {
    background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
    color: white;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    color: white;
    text-decoration: none;
}

.groups-selection {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    padding: 20px;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.group-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    margin: 0;
    padding: 10px;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.group-checkbox:hover {
    background: rgba(102, 126, 234, 0.1);
}

.checkbox-input {
    display: none;
}

.checkbox-custom {
    width: 18px;
    height: 18px;
    border: 2px solid #D1D5DB;
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.checkbox-input:checked + .checkbox-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

.checkbox-input:checked + .checkbox-custom::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.checkbox-text {
    font-weight: 500;
    color: #374151;
}

.form-actions {
    display: flex;
    gap: 15px;
    padding-top: 20px;
    border-top: 1px solid #E5E7EB;
}

.info-card {
    height: fit-content;
}

.info-list {
    margin-bottom: 25px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    flex-shrink: 0;
    margin-right: 15px;
}

.info-value {
    color: #6B7280;
    font-size: 0.9rem;
    text-align: right;
}

.info-value.file-path {
    font-family: monospace;
    word-break: break-all;
}

.current-groups {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: flex-end;
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
}

.warning-notice {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: rgba(255, 193, 7, 0.1);
    border-radius: 12px;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.notice-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.notice-content h4 {
    margin: 0 0 8px 0;
    color: #92400E;
    font-size: 1rem;
    font-weight: 600;
}

.notice-content p {
    margin: 0;
    color: #A16207;
    font-size: 0.9rem;
    line-height: 1.5;
}

@media (max-width: 992px) {
    .form-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .groups-selection {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .file-info {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .current-groups {
        justify-content: flex-start;
    }
    
    .info-item {
        flex-direction: column;
        align-items: stretch;
    }
    
    .info-value {
        text-align: left;
        margin-top: 5px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.admin-form');
    
    form.addEventListener('submit', function(e) {
        const filename = document.getElementById('filename').value.trim();
        const selectedGroups = document.querySelectorAll('input[name="group_names[]"]:checked');
        
        if (!filename) {
            e.preventDefault();
            alert('Введите название файла');
            return;
        }
        
        if (selectedGroups.length === 0) {
            e.preventDefault();
            alert('Выберите хотя бы одну группу');
            return;
        }
    });
});
</script> 