<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">📚 Редактировать файл УМК</h1>
            <p class="admin-subtitle">Изменение информации о файле УМК</p>
        </div>
        <div class="admin-actions">
            <a href="/admin/umk-files" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Назад к списку
            </a>
        </div>
    </div>
</div>

<div class="admin-content">
    <div class="admin-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa fa-edit"></i>
                Редактирование УМК
            </div>
        </div>

        <div class="card-body">
            <form method="POST" class="edit-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="filename">Название файла *</label>
                        <input type="text" name="filename" id="filename" class="form-control" 
                               value="<?php echo htmlspecialchars($file['filename']); ?>" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Текущий файл</label>
                        <div class="current-file">
                            <i class="fa fa-book"></i>
                            <a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank">
                                <?php echo htmlspecialchars($file['filename']); ?>
                            </a>
                            <small class="text-muted d-block">
                                Файл нельзя изменить. Для замены создайте новый файл.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea name="description" id="description" class="form-control" rows="3" 
                              placeholder="Краткое описание содержимого файла"><?php echo htmlspecialchars($file['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Группы *</label>
                    <div class="groups-selection">
                        <?php if (!empty($groups)): ?>
                            <div class="row">
                                <?php foreach ($groups as $group): ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" 
                                                   id="group_<?php echo htmlspecialchars($group['group_name']); ?>" 
                                                   name="group_names[]" 
                                                   value="<?php echo htmlspecialchars($group['group_name']); ?>"
                                                   <?php echo in_array($group['group_name'], $file_group_names) ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" 
                                                   for="group_<?php echo htmlspecialchars($group['group_name']); ?>">
                                                <?php echo htmlspecialchars($group['group_name']); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i>
                                Нет доступных групп.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i>
                        Сохранить изменения
                    </button>
                    <a href="/admin/umk-files" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Валидация формы
    $('.edit-form').submit(function(e) {
        const selectedGroups = $('input[name="group_names[]"]:checked').length;
        if (selectedGroups === 0) {
            e.preventDefault();
            alert('Выберите хотя бы одну группу для привязки файла');
            return false;
        }
    });
});
</script>

<style>
.current-file {
    padding: 10px;
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.current-file a {
    color: #007bff;
    text-decoration: none;
}

.current-file a:hover {
    text-decoration: underline;
}

.groups-selection {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
    background: #f8f9fa;
}

.custom-control {
    margin-bottom: 10px;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
</style> 