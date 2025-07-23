<?php
$header = $header ?? '';
$footer = $footer ?? '';
$error = $error ?? '';
$news = $news ?? [];
?>

<div class="news-form-container">
    <!-- Заголовок формы -->
    <div class="form-header">
        <div class="form-title-section">
            <h1 class="form-title">
                <i class="fas fa-edit"></i>
                Редактирование новости
            </h1>
            <p class="form-subtitle">Измените данные новости и сохраните изменения</p>
        </div>
        
        <div class="form-actions">
            <a href="/admin/news" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Назад к списку
            </a>
        </div>
    </div>

    <!-- Уведомления об ошибках -->
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Форма редактирования новости -->
    <div class="form-card">
        <form method="POST" action="/admin/news/update/<?= $news['news_id'] ?? '' ?>" enctype="multipart/form-data" class="news-form">
            <!-- Основная информация -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Основная информация
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading"></i>
                            Заголовок новости *
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-input" 
                               placeholder="Введите заголовок новости"
                               value="<?= htmlspecialchars($news['news_title'] ?? '') ?>"
                               required>
                        <div class="input-counter">
                            <span id="titleCounter"><?= strlen($news['news_title'] ?? '') ?></span> / 200 символов
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            <i class="fas fa-tag"></i>
                            Категория *
                        </label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">Выберите категорию</option>
                            <option value="1" <?= ($news['category_id'] ?? 1) == 1 ? 'selected' : '' ?>>Общие новости</option>
                            <option value="2" <?= ($news['category_id'] ?? 1) == 2 ? 'selected' : '' ?>>Академические</option>
                            <option value="3" <?= ($news['category_id'] ?? 1) == 3 ? 'selected' : '' ?>>События</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Содержание новости -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-file-text"></i>
                    Содержание новости
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="content" class="form-label">
                            <i class="fas fa-edit"></i>
                            Текст новости *
                        </label>
                        <textarea id="content" 
                                  name="content" 
                                  class="form-textarea" 
                                  placeholder="Введите текст новости..."
                                  rows="12"
                                  required><?= htmlspecialchars($news['news_text'] ?? '') ?></textarea>
                        <div class="input-counter">
                            <span id="contentCounter"><?= strlen($news['news_text'] ?? '') ?></span> / 5000 символов
                        </div>
                    </div>
                </div>
            </div>

            <!-- Медиа файлы -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-images"></i>
                    Медиа файлы
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="main_photo" class="form-label">
                            <i class="fas fa-camera"></i>
                            Главная фотография
                        </label>
                        <div class="file-upload-container">
                            <input type="file" 
                                   id="main_photo" 
                                   name="main_photo" 
                                   class="file-input" 
                                   accept="image/*">
                            <div class="file-upload-area" id="mainPhotoArea">
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Перетащите файл сюда или нажмите для выбора</p>
                                    <span class="file-info">Поддерживаемые форматы: JPG, PNG, GIF (до 5MB)</span>
                                </div>
                                <div class="file-preview" id="mainPhotoPreview" style="display: none;">
                                    <img id="mainPhotoImg" src="" alt="Предварительный просмотр">
                                    <button type="button" class="remove-file" onclick="removeFile('main_photo')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-images"></i>
                            Дополнительные фотографии
                        </label>
                        <div class="file-upload-container">
                            <input type="file" 
                                   id="additional_photos" 
                                   name="additional_photos[]" 
                                   class="file-input" 
                                   accept="image/*"
                                   multiple>
                            <div class="file-upload-area" id="additionalPhotosArea">
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Перетащите файлы сюда или нажмите для выбора</p>
                                    <span class="file-info">Можно выбрать несколько файлов</span>
                                </div>
                                <div class="file-preview-grid" id="additionalPhotosPreview" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Информация о новости -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info"></i>
                    Информация о новости
                </h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">ID новости:</span>
                        <span class="info-value"><?= $news['news_id'] ?? 'N/A' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Дата создания:</span>
                        <span class="info-value"><?= date('d.m.Y H:i', strtotime($news['news_date_add'] ?? 'now')) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Категория:</span>
                        <span class="info-value">
                            <?php
                            $categoryId = $news['category_id'] ?? 1;
                            $categories = [
                                1 => 'Общие новости',
                                2 => 'Академические',
                                3 => 'События'
                            ];
                            echo $categories[$categoryId] ?? 'Общие новости';
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Настройки публикации -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-cog"></i>
                    Настройки публикации
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-eye"></i>
                            Статус публикации
                        </label>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="status" value="published" checked>
                                <span class="radio-custom"></span>
                                <span class="radio-label">Опубликовать</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="status" value="draft">
                                <span class="radio-custom"></span>
                                <span class="radio-label">Сохранить как черновик</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="form-actions-bottom">
                <button type="submit" class="btn btn-primary btn-submit">
                    <i class="fas fa-save"></i>
                    Сохранить изменения
                </button>
                <button type="button" class="btn btn-secondary" onclick="saveDraft()">
                    <i class="fas fa-file"></i>
                    Сохранить черновик
                </button>
                <a href="/admin/news" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    Отмена
                </a>
                <button type="button" class="btn btn-danger" onclick="deleteNews(<?= $news['news_id'] ?? '' ?>)">
                    <i class="fas fa-trash"></i>
                    Удалить новость
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.news-form-container {
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-title-section {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.form-title {
    color: #fff;
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.form-title i {
    color: #00d4ff;
    font-size: 32px;
}

.form-subtitle {
    color: #b8c5d6;
    margin: 0;
    font-size: 16px;
}

.form-actions {
    display: flex;
    gap: 15px;
}

.form-card {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.news-form {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.form-section {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 30px;
}

.form-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.section-title {
    color: #fff;
    margin: 0 0 20px 0;
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title i {
    color: #00d4ff;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-label i {
    color: #00d4ff;
}

.form-input,
.form-select,
.form-textarea {
    padding: 12px 16px;
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    font-size: 16px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #00d4ff;
    box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.input-counter {
    color: #6c757d;
    font-size: 12px;
    text-align: right;
    margin-top: 5px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    background: rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #b8c5d6;
    font-weight: 500;
    font-size: 14px;
}

.info-value {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}

.file-upload-container {
    position: relative;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    z-index: 2;
}

.file-upload-area {
    border: 2px dashed rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.02);
    position: relative;
    min-height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.file-upload-area:hover {
    border-color: #00d4ff;
    background: rgba(0, 212, 255, 0.05);
}

.upload-placeholder {
    color: #b8c5d6;
}

.upload-placeholder i {
    font-size: 48px;
    color: #6c757d;
    margin-bottom: 15px;
    display: block;
}

.upload-placeholder p {
    margin: 0 0 10px 0;
    font-size: 16px;
    font-weight: 500;
}

.file-info {
    font-size: 12px;
    color: #6c757d;
}

.file-preview {
    position: relative;
    width: 100%;
    height: 100%;
}

.file-preview img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 8px;
    object-fit: cover;
}

.remove-file {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.3s ease;
}

.remove-file:hover {
    background: #c82333;
    transform: scale(1.1);
}

.file-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.radio-item {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    padding: 10px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.radio-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

.radio-item input[type="radio"] {
    display: none;
}

.radio-custom {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    position: relative;
    transition: all 0.3s ease;
}

.radio-item input[type="radio"]:checked + .radio-custom {
    border-color: #00d4ff;
    background: #00d4ff;
}

.radio-item input[type="radio"]:checked + .radio-custom::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: #fff;
    border-radius: 50%;
}

.radio-label {
    color: #fff;
    font-weight: 500;
}

.form-actions-bottom {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary {
    background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 212, 255, 0.4);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.btn-outline {
    background: transparent;
    color: #6c757d;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

.alert-danger {
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    color: #fff;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

@media (max-width: 768px) {
    .form-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .form-actions-bottom {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Счетчики символов
    const titleInput = document.getElementById('title');
    const contentTextarea = document.getElementById('content');
    const titleCounter = document.getElementById('titleCounter');
    const contentCounter = document.getElementById('contentCounter');
    
    titleInput.addEventListener('input', function() {
        const length = this.value.length;
        titleCounter.textContent = length;
        
        if (length > 180) {
            titleCounter.style.color = '#ff6b6b';
        } else if (length > 150) {
            titleCounter.style.color = '#ffa726';
        } else {
            titleCounter.style.color = '#6c757d';
        }
    });
    
    contentTextarea.addEventListener('input', function() {
        const length = this.value.length;
        contentCounter.textContent = length;
        
        if (length > 4500) {
            contentCounter.style.color = '#ff6b6b';
        } else if (length > 4000) {
            contentCounter.style.color = '#ffa726';
        } else {
            contentCounter.style.color = '#6c757d';
        }
    });
    
    // Загрузка файлов
    setupFileUpload('main_photo', 'mainPhotoArea', 'mainPhotoPreview', 'mainPhotoImg');
    setupFileUpload('additional_photos', 'additionalPhotosArea', 'additionalPhotosPreview');
});

function setupFileUpload(inputId, areaId, previewId, imgId = null) {
    const input = document.getElementById(inputId);
    const area = document.getElementById(areaId);
    const preview = document.getElementById(previewId);
    
    input.addEventListener('change', function(e) {
        const files = e.target.files;
        
        if (files.length > 0) {
            if (inputId === 'main_photo') {
                // Одна фотография
                const file = files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById(imgId).src = e.target.result;
                    preview.style.display = 'block';
                    area.querySelector('.upload-placeholder').style.display = 'none';
                };
                
                reader.readAsDataURL(file);
            } else {
                // Несколько фотографий
                preview.style.display = 'block';
                area.querySelector('.upload-placeholder').style.display = 'none';
                preview.innerHTML = '';
                
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'preview-item';
                        imgContainer.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}">
                            <button type="button" class="remove-file" onclick="removePreviewItem(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        preview.appendChild(imgContainer);
                    };
                    
                    reader.readAsDataURL(file);
                });
            }
        }
    });
    
    // Drag and drop
    area.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#00d4ff';
        this.style.background = 'rgba(0, 212, 255, 0.1)';
    });
    
    area.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
        this.style.background = 'rgba(255, 255, 255, 0.02)';
    });
    
    area.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
        this.style.background = 'rgba(255, 255, 255, 0.02)';
        
        const files = e.dataTransfer.files;
        input.files = files;
        input.dispatchEvent(new Event('change'));
    });
}

function removeFile(inputId) {
    const input = document.getElementById(inputId);
    const area = document.getElementById(inputId.replace('_', '') + 'Area');
    const preview = document.getElementById(inputId.replace('_', '') + 'Preview');
    
    input.value = '';
    preview.style.display = 'none';
    area.querySelector('.upload-placeholder').style.display = 'block';
}

function removePreviewItem(button) {
    button.parentElement.remove();
}

function saveDraft() {
    const form = document.querySelector('.news-form');
    const statusInput = form.querySelector('input[name="status"][value="draft"]');
    statusInput.checked = true;
    form.submit();
}

function deleteNews(newsId) {
    if (confirm('Вы уверены, что хотите удалить эту новость? Это действие нельзя отменить.')) {
        window.location.href = `/admin/news/delete/${newsId}`;
    }
}
</script>
