<?php
$currentPage = 'control-files';
?>

<!-- Control Files Upload Dashboard Content -->
<div class="control-files-upload-dashboard">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-upload"></i>
            <h1>Загрузить файл контрольной работы</h1>
        </div>
        <div class="page-subtitle">
            Добавление нового файла с привязкой к группам студентов
        </div>
        <div class="page-actions">
            <a href="/admin/control-files" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Назад к списку
            </a>
        </div>
    </div>

    <!-- Основная форма -->
    <div class="admin-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-upload"></i>
                Параметры файла
            </div>
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="file" class="form-label required">
                            <i class="fas fa-file"></i>
                            Выберите файл
                        </label>
                        <div class="file-upload-area" id="fileUploadArea">
                            <input type="file" 
                                   id="file" 
                                   name="file" 
                                   class="file-input" 
                                    accept=".pdf,.doc,.docx,.rtf,.txt,.zip,.rar"
                                   required>
                            <div class="upload-content">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload"></i>
                                </div>
                                <div class="upload-text">
                                    <strong>Нажмите для выбора файла</strong> или перетащите сюда
                                </div>
                                <div class="upload-hint">
                                    Поддерживаемые форматы: PDF, DOC, DOCX, RTF, TXT, ZIP, RAR
                                </div>
                            </div>
                            <div class="file-preview" id="filePreview" style="display: none;">
                                <div class="preview-icon">
                                    <i class="fas fa-file-text"></i>
                                </div>
                                <div class="preview-details">
                                    <div class="preview-name"></div>
                                    <div class="preview-size"></div>
                                </div>
                                <button type="button" class="preview-remove" onclick="removeFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="filename" class="form-label required">
                            <i class="fas fa-tag"></i>
                            Название файла
                        </label>
                        <input type="text" 
                               id="filename" 
                               name="filename" 
                               class="form-control" 
                               placeholder="Введите название файла для отображения" 
                               required>
                        <div class="form-help">
                            Это имя будет отображаться студентам в списке работ. По умолчанию подставляется из имени файла.
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
                                           value="<?= htmlspecialchars($group['group_name']) ?>"
                                           class="checkbox-input">
                                    <span class="checkbox-custom"></span>
                                    <span class="checkbox-text"><?= htmlspecialchars($group['group_name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-help">
                            Группы, которые имеют доступ к файлу
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i>
                            Описание
                        </label>
                        <textarea id="description" 
                                   name="description" 
                                   class="form-control" 
                                   rows="4"
                                   placeholder="Краткое описание файла и инструкции для студентов"></textarea>
                        <div class="form-help">Описание не обязательно</div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i>
                        Загрузить файл
                    </button>
                    <a href="/admin/control-files" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Информационная карточка -->
    <div class="info-card">
        <div class="info-header">
            <i class="fas fa-info-circle"></i>
            <h3>Информация о загрузке</h3>
        </div>
        <div class="info-content">
            <ul>
                <li><strong>Максимальный размер файла:</strong> 50 MB</li>
                <li><strong>Поддерживаемые форматы:</strong> PDF, DOC, DOCX, TXT, ZIP, RAR</li>
                <li><strong>Рекомендуемый формат:</strong> PDF для лучшей совместимости</li>
                <li><strong>Безопасность:</strong> Все файлы проверяются на вирусы</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Функции для работы с загрузкой файлов
function removeFile() {
    document.getElementById('file').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.querySelector('.upload-content').style.display = 'block';
}

// Обработка выбора файла
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const preview = document.getElementById('filePreview');
        const uploadContent = document.querySelector('.upload-content');
        
        // Показываем превью
        preview.style.display = 'flex';
        uploadContent.style.display = 'none';
        
        // Заполняем информацию о файле
        preview.querySelector('.preview-name').textContent = file.name;
        preview.querySelector('.preview-size').textContent = formatFileSize(file.size);
        
        // Автоматически заполняем название файла
        const filenameInput = document.getElementById('filename');
        if (!filenameInput.value) {
            filenameInput.value = file.name.replace(/\.[^/.]+$/, '');
        }
    }
});

// Drag and drop функциональность
const uploadArea = document.getElementById('fileUploadArea');

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadArea.classList.add('drag-over');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('file').files = files;
        document.getElementById('file').dispatchEvent(new Event('change'));
    }
});

// Форматирование размера файла
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Валидация формы
document.querySelector('form').addEventListener('submit', function(e) {
    const file = document.getElementById('file').files[0];
    const groups = document.querySelectorAll('input[name="group_names[]"]:checked');
    
    if (!file) {
        e.preventDefault();
        alert('Пожалуйста, выберите файл для загрузки');
        return;
    }
    
    if (groups.length === 0) {
        e.preventDefault();
        alert('Пожалуйста, выберите хотя бы одну группу');
        return;
    }
    
    // Проверка размера файла (50MB)
    if (file.size > 50 * 1024 * 1024) {
        e.preventDefault();
        alert('Размер файла не должен превышать 50 MB');
        return;
    }
});
</script> 