<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">📚 Загрузить файл УМК</h1>
            <p class="admin-subtitle">Добавление нового учебно-методического комплекса</p>
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
                <i class="fa fa-upload"></i>
                Загрузка УМК
            </div>
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="filename">Название файла *</label>
                        <input type="text" name="filename" id="filename" class="form-control" 
                               placeholder="Введите название файла" required>
                        <small class="form-text text-muted">Будет использовано как отображаемое имя файла</small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="file">Файл *</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                        <small class="form-text text-muted">
                            Разрешены: PDF, DOC, DOCX, TXT, ZIP, RAR, PPT, PPTX
                        </small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea name="description" id="description" class="form-control" rows="3" 
                              placeholder="Краткое описание содержимого файла"></textarea>
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
                                                   value="<?php echo htmlspecialchars($group['group_name']); ?>">
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
                                Нет доступных групп. Сначала создайте группы в разделе 
                                <a href="/admin/group-passwords">Управление паролями групп</a>.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload"></i>
                        Загрузить УМК
                    </button>
                    <a href="/admin/umk-files" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Автоматическое заполнение названия файла
    $('#file').change(function() {
        const file = this.files[0];
        if (file && !$('#filename').val()) {
            const name = file.name.replace(/\.[^/.]+$/, ""); // Убираем расширение
            $('#filename').val(name);
        }
    });
    
    // Валидация формы
    $('.upload-form').submit(function(e) {
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