<div class="admin-header">
    <div class="admin-header-content">
        <div class="admin-title-section">
            <h1 class="admin-title">📤 Загрузить файл контрольной работы</h1>
            <p class="admin-subtitle">Добавление нового файла с привязкой к группам</p>
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
                    <i class="fa fa-upload"></i>
                    Параметры файла
                </div>
            </div>

            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="admin-form">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="file" class="form-label required">
                                <i class="fa fa-file"></i>
                                Выберите файл
                            </label>
                            <div class="file-upload-area" id="fileUploadArea">
                                <input type="file" 
                                       id="file" 
                                       name="file" 
                                       class="file-input" 
                                       accept=".pdf,.doc,.docx,.txt,.zip,.rar"
                                       required>
                                <div class="upload-content">
                                    <div class="upload-icon">📁</div>
                                    <div class="upload-text">
                                        <strong>Нажмите для выбора файла</strong> или перетащите сюда
                                    </div>
                                    <div class="upload-hint">
                                        Поддерживаемые форматы: PDF, DOC, DOCX, TXT, ZIP, RAR
                                    </div>
                                </div>
                                <div class="file-preview" id="filePreview" style="display: none;">
                                    <div class="preview-icon">📄</div>
                                    <div class="preview-details">
                                        <div class="preview-name"></div>
                                        <div class="preview-size"></div>
                                    </div>
                                    <button type="button" class="preview-remove" onclick="removeFile()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="filename" class="form-label">
                                <i class="fa fa-tag"></i>
                                Название файла
                            </label>
                            <input type="text" 
                                   id="filename" 
                                   name="filename" 
                                   class="form-control" 
                                   placeholder="Автоматически из имени файла">
                            <div class="form-help">
                                Оставьте пустым для использования оригинального названия
                            </div>
                        </div>

                        <div class="form-group">
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
                                               class="checkbox-input">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkbox-text"><?php echo htmlspecialchars($group['group_name']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="form-help">
                                Выберите группы, которые будут иметь доступ к файлу
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
                                      placeholder="Краткое описание файла (предмет, тема, семестр)"></textarea>
                            <div class="form-help">
                                Опциональное описание для удобства поиска
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-upload"></i>
                            Загрузить файл
                        </button>
                        <a href="/admin/control-files" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i>
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="admin-card info-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa fa-info-circle"></i>
                    Рекомендации
                </div>
            </div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon">📁</div>
                        <div class="info-content">
                            <h4>Форматы файлов</h4>
                            <p>Поддерживаются: PDF, DOC, DOCX, TXT, ZIP, RAR. Максимальный размер: 50 MB.</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">👥</div>
                        <div class="info-content">
                            <h4>Привязка к группам</h4>
                            <p>Файл будет доступен только студентам выбранных групп после ввода пароля.</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📝</div>
                        <div class="info-content">
                            <h4>Именование</h4>
                            <p>Используйте понятные названия: "Математика_Контрольная_1_семестр".</p>
                        </div>
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

.form-label.required::after {
    content: ' *';
    color: #EF4444;
}

/* File Upload Area */
.file-upload-area {
    position: relative;
    border: 2px dashed #D1D5DB;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
}

.file-upload-area.dragover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.1);
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-content {
    pointer-events: none;
}

.upload-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.upload-text {
    margin-bottom: 10px;
    color: #374151;
}

.upload-hint {
    font-size: 0.9rem;
    color: #6B7280;
}

.file-preview {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 8px;
    margin-top: 15px;
}

.preview-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.preview-details {
    flex: 1;
}

.preview-name {
    font-weight: 600;
    color: #374151;
    margin-bottom: 5px;
}

.preview-size {
    font-size: 0.9rem;
    color: #6B7280;
}

.preview-remove {
    background: #EF4444;
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-remove:hover {
    background: #DC2626;
}

/* Groups Selection */
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
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-item {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.info-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
    width: 40px;
    text-align: center;
}

.info-content h4 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
}

.info-content p {
    margin: 0;
    color: #6B7280;
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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const uploadArea = document.getElementById('fileUploadArea');
    const uploadContent = uploadArea.querySelector('.upload-content');
    const filePreview = document.getElementById('filePreview');
    const filenameInput = document.getElementById('filename');
    
    // Drag & Drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });
    
    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });
    
    function handleFileSelect(file) {
        // Проверяем размер файла (50MB)
        if (file.size > 50 * 1024 * 1024) {
            alert('Файл слишком большой. Максимальный размер: 50 MB');
            return;
        }
        
        // Проверяем тип файла
        const allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
        const fileExt = file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExt)) {
            alert('Недопустимый тип файла. Разрешены: ' + allowedTypes.join(', '));
            return;
        }
        
        // Показываем превью
        uploadContent.style.display = 'none';
        filePreview.style.display = 'flex';
        
        filePreview.querySelector('.preview-name').textContent = file.name;
        filePreview.querySelector('.preview-size').textContent = formatFileSize(file.size);
        
        // Автозаполнение названия
        if (!filenameInput.value) {
            const nameWithoutExt = file.name.substring(0, file.name.lastIndexOf('.'));
            filenameInput.value = nameWithoutExt;
        }
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Валидация формы
    const form = document.querySelector('.admin-form');
    form.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        const selectedGroups = document.querySelectorAll('input[name="group_names[]"]:checked');
        
        if (!file) {
            e.preventDefault();
            alert('Выберите файл для загрузки');
            return;
        }
        
        if (selectedGroups.length === 0) {
            e.preventDefault();
            alert('Выберите хотя бы одну группу');
            return;
        }
    });
});

function removeFile() {
    const fileInput = document.getElementById('file');
    const uploadContent = document.querySelector('.upload-content');
    const filePreview = document.getElementById('filePreview');
    const filenameInput = document.getElementById('filename');
    
    fileInput.value = '';
    uploadContent.style.display = 'block';
    filePreview.style.display = 'none';
    filenameInput.value = '';
}
</script> 