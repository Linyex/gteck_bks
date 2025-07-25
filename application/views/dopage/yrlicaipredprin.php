<!-- Hero Section для электронных обращений -->
<section class="stud-hero appeals-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">📧</span>Электронные обращения</h1>
                    <p class="hero-subtitle">Для юридических лиц и индивидуальных предпринимателей</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Доступность</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">5</span>
                            <span class="stat-label">Обязательных полей</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">⚡</span>
                            <span class="stat-label">Быстро</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Основной контент -->
<div class="c-layout-page">
    <div class="container">
        
        <!-- Требования к обращению -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="200">
            <h2 class="section-title">📋 Требования к электронному обращению</h2>
            <div class="requirements-section">
                <div class="requirements-intro" data-aos="slide-up" data-aos-delay="300">
                    <p class="intro-text">Электронное обращение в обязательном порядке должно содержать следующую информацию:</p>
                </div>
                
                <div class="requirements-list" data-aos="zoom-in" data-aos-delay="400">
                    <div class="requirement-item" data-aos="slide-right" data-aos-delay="500">
                        <div class="req-number">1</div>
                        <div class="req-content">
                            <h4>Адресат</h4>
                            <p>Наименование и (или) адрес организации либо должность лица, которым направляется обращение</p>
                        </div>
                    </div>
                    
                    <div class="requirement-item" data-aos="slide-left" data-aos-delay="600">
                        <div class="req-number">2</div>
                        <div class="req-content">
                            <h4>Полное наименование</h4>
                            <p>Полное наименование юридического лица и его место нахождения</p>
                        </div>
                    </div>
                    
                    <div class="requirement-item" data-aos="slide-right" data-aos-delay="700">
                        <div class="req-number">3</div>
                        <div class="req-content">
                            <h4>Суть обращения</h4>
                            <p>Изложение сути обращения</p>
                        </div>
                    </div>
                    
                    <div class="requirement-item" data-aos="slide-left" data-aos-delay="800">
                        <div class="req-number">4</div>
                        <div class="req-content">
                            <h4>Ответственное лицо</h4>
                            <p>ФИО либо инициалы руководителя или лица, уполномоченного подписывать обращения</p>
                        </div>
                    </div>
                    
                    <div class="requirement-item" data-aos="slide-right" data-aos-delay="900">
                        <div class="req-number">5</div>
                        <div class="req-content">
                            <h4>Контактная информация</h4>
                            <p>Адрес электронной почты заявителя</p>
                        </div>
                    </div>
                </div>
                
                <div class="additional-info" data-aos="slide-up" data-aos-delay="1000">
                    <div class="info-note">
                        <h4>❗ Важно:</h4>
                        <p>К электронным обращениям, подаваемым представителями заявителей, должны прилагаться электронные копии документов, подтверждающих их полномочия.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Форма обращения -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="700">
            <h2 class="section-title">📝 Форма электронного обращения</h2>
            <div class="appeal-form-container">
                <form id="sendmessageForm" method="POST" enctype="multipart/form-data" class="modern-appeal-form" data-aos="zoom-in" data-aos-delay="800">
                    
                    <div class="form-section">
                        <label for="fio" class="form-label required">
                            <i class="fas fa-user"></i>
                            Ф.И.О руководителя или лица, уполномоченного подписывать обращения
                        </label>
                        <input type="text" 
                               id="fio" 
                               name="fio" 
                               class="modern-input" 
                               placeholder="Введите полное ФИО" 
                               required>
                    </div>

                    <div class="form-section">
                        <label for="company" class="form-label required">
                            <i class="fas fa-building"></i>
                            Полное наименование юридического лица или ИП
                        </label>
                        <input type="text" 
                               id="company" 
                               name="company" 
                               class="modern-input" 
                               placeholder="Введите полное наименование организации" 
                               required>
                    </div>

                    <div class="form-section">
                        <label for="mesto" class="form-label required">
                            <i class="fas fa-map-marker-alt"></i>
                            Место нахождения (адрес)
                        </label>
                        <input type="text" 
                               id="mesto" 
                               name="mesto" 
                               class="modern-input" 
                               placeholder="Введите полный адрес" 
                               required>
                    </div>

                    <div class="form-section">
                        <label for="email" class="form-label required">
                            <i class="fas fa-envelope"></i>
                            Адрес электронной почты
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="modern-input" 
                               placeholder="example@company.com" 
                               required>
                    </div>

                    <div class="form-section">
                        <label for="subject" class="form-label required">
                            <i class="fas fa-tag"></i>
                            Тема обращения
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               class="modern-input" 
                               placeholder="Краткая тема обращения" 
                               required>
                    </div>

                    <div class="form-section">
                        <label for="message" class="form-label required">
                            <i class="fas fa-edit"></i>
                            Суть обращения
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  class="modern-textarea" 
                                  placeholder="Подробно изложите суть вашего обращения..." 
                                  rows="6" 
                                  required></textarea>
                    </div>

                    <div class="form-section">
                        <label for="files" class="form-label">
                            <i class="fas fa-paperclip"></i>
                            Прикрепить документы (по необходимости)
                        </label>
                        <div class="file-upload-section">
                            <input type="file" 
                                   id="files" 
                                   name="files[]" 
                                   class="file-input" 
                                   multiple 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="file-upload-display">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">
                                    <p>Нажмите или перетащите файлы</p>
                                    <span>Поддерживаются: PDF, DOC, DOCX, JPG, PNG</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            <span>Отправить обращение</span>
                        </button>
                        <button type="reset" class="reset-btn">
                            <i class="fas fa-undo"></i>
                            <span>Очистить форму</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Информация о рассмотрении -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="900">
            <h2 class="section-title">⏰ Информация о рассмотрении</h2>
            <div class="processing-info">
                <div class="info-grid">
                    <div class="info-card" data-aos="flip-left" data-aos-delay="1000">
                        <div class="info-icon">📅</div>
                        <h4>Срок рассмотрения</h4>
                        <p>Обращения рассматриваются в установленные законодательством сроки</p>
                    </div>
                    <div class="info-card" data-aos="flip-left" data-aos-delay="1100">
                        <div class="info-icon">📧</div>
                        <h4>Уведомление</h4>
                        <p>Ответ будет направлен на указанный адрес электронной почты</p>
                    </div>
                    <div class="info-card" data-aos="flip-left" data-aos-delay="1200">
                        <div class="info-icon">🔒</div>
                        <h4>Конфиденциальность</h4>
                        <p>Все обращения обрабатываются конфиденциально</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Улучшения для формы
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sendmessageForm');
    const fileInput = document.getElementById('files');
    const uploadDisplay = document.querySelector('.file-upload-display');
    
    // Drag & drop для файлов
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadDisplay.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadDisplay.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadDisplay.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        uploadDisplay.classList.add('drag-over');
    }
    
    function unhighlight(e) {
        uploadDisplay.classList.remove('drag-over');
    }
    
    uploadDisplay.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileDisplay();
    }
    
    fileInput.addEventListener('change', updateFileDisplay);
    
    function updateFileDisplay() {
        const files = fileInput.files;
        const uploadText = uploadDisplay.querySelector('.upload-text p');
        
        if (files.length > 0) {
            uploadText.textContent = `Выбрано файлов: ${files.length}`;
            uploadDisplay.classList.add('has-files');
        } else {
            uploadText.textContent = 'Нажмите или перетащите файлы';
            uploadDisplay.classList.remove('has-files');
        }
    }
});
</script>
<?php echo $footer ?>