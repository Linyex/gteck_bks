<?php echo $header; ?>

<style>
    .groups-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .groups-header {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
    }
    
    .groups-header h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
        color: #34495e;
    }
    
    .groups-header p {
        font-size: 1.1em;
        color: #7f8c8d;
    }
    
    .stats-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }
    
    .stat-card h3 {
        margin: 0 0 10px 0;
        font-size: 1.2em;
    }
    
    .stat-number {
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 0.9em;
        opacity: 0.9;
    }
    
    .groups-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
    }
    
    .section-header h2 {
        color: #2c3e50;
        margin: 0;
    }
    
    .add-group-btn {
        background: #27ae60;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.3s ease;
    }
    
    .add-group-btn:hover {
        background: #229954;
    }
    
    .groups-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .group-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        border-left: 4px solid #3498db;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .group-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .group-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .group-name {
        font-size: 1.2em;
        font-weight: bold;
        color: #2c3e50;
    }
    
    .group-actions {
        display: flex;
        gap: 10px;
    }
    
    .view-files-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: background 0.3s ease;
    }
    
    .view-files-btn:hover {
        background: #2980b9;
    }
    
    .delete-group-btn {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: background 0.3s ease;
    }
    
    .delete-group-btn:hover {
        background: #c0392b;
    }
    
    .group-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 15px;
    }
    
    .group-stat {
        text-align: center;
        padding: 8px;
        background: white;
        border-radius: 4px;
        font-size: 0.9em;
    }
    
    .group-stat-number {
        font-weight: bold;
        color: #3498db;
    }
    
    .group-stat-label {
        color: #7f8c8d;
        font-size: 0.8em;
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        position: relative;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .modal-title {
        font-size: 1.5em;
        color: #2c3e50;
        margin: 0;
    }
    
    .close-btn {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
        color: #7f8c8d;
    }
    
    .close-btn:hover {
        color: #2c3e50;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #3498db;
    }
    
    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.3s ease;
    }
    
    .btn-primary {
        background: #3498db;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2980b9;
    }
    
    .btn-secondary {
        background: #95a5a6;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #7f8c8d;
    }
    
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        display: none;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .files-modal {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .files-list {
        display: grid;
        gap: 10px;
    }
    
    .file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #3498db;
    }
    
    .file-name {
        font-weight: 500;
        color: #2c3e50;
    }
    
    .file-actions {
        display: flex;
        gap: 5px;
    }
    
    .file-btn {
        padding: 4px 8px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 11px;
        color: white;
    }
    
    .download-file-btn {
        background: #27ae60;
    }
    
    .delete-file-btn {
        background: #e74c3c;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .groups-grid {
            grid-template-columns: 1fr;
        }
        
        .group-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="groups-container">
    <div class="groups-header">
        <h1>Управление группами</h1>
        <p>Просмотр статистики и управление группами студентов</p>
    </div>
    
    <!-- Уведомления -->
    <div id="alert" class="alert"></div>
    
    <!-- Статистика -->
    <div class="stats-section">
        <h2>Общая статистика</h2>
        <div class="stats-grid" id="statsGrid">
            <div class="stat-card">
                <div class="stat-number" id="totalGroups">0</div>
                <div class="stat-label">Групп</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalFiles">0</div>
                <div class="stat-label">Файлов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalPdf">0</div>
                <div class="stat-label">PDF файлов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalDoc">0</div>
                <div class="stat-label">Word файлов</div>
            </div>
        </div>
    </div>
    
    <!-- Группы -->
    <div class="groups-section">
        <div class="section-header">
            <h2>Группы студентов</h2>
            <button class="add-group-btn" onclick="showAddGroupModal()">
                Добавить группу
            </button>
        </div>
        
        <div class="groups-grid" id="groupsGrid">
            <div class="loading">
                <div class="spinner"></div>
                <p>Загрузка групп...</p>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно добавления группы -->
<div id="addGroupModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Добавить новую группу</h3>
            <button class="close-btn" onclick="closeModal('addGroupModal')">&times;</button>
        </div>
        
        <form id="addGroupForm">
            <div class="form-group">
                <label for="groupName">Название группы:</label>
                <input type="text" id="groupName" name="group_name" 
                       placeholder="Например: T401" required>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addGroupModal')">
                    Отмена
                </button>
                <button type="submit" class="btn btn-primary">
                    Добавить группу
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно просмотра файлов -->
<div id="filesModal" class="modal">
    <div class="modal-content files-modal">
        <div class="modal-header">
            <h3 class="modal-title" id="filesModalTitle">Файлы группы</h3>
            <button class="close-btn" onclick="closeModal('filesModal')">&times;</button>
        </div>
        
        <div id="filesModalContent">
            <div class="loading">
                <div class="spinner"></div>
                <p>Загрузка файлов...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Глобальные переменные
let currentGroups = [];
let currentStats = [];

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadGroups();
    setupAddGroupForm();
});

// Загрузка статистики
function loadStats() {
    fetch('/control/api/getStats')
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            currentStats = data.stats;
            renderStats(data.stats);
        } else {
            showAlert('Ошибка загрузки статистики', 'error');
        }
    })
    .catch(error => {
        showAlert('Ошибка загрузки статистики', 'error');
        console.error('Error:', error);
    });
}

// Отображение статистики
function renderStats(stats) {
    let totalGroups = stats.length;
    let totalFiles = 0;
    let totalPdf = 0;
    let totalDoc = 0;
    
    stats.forEach(stat => {
        totalFiles += parseInt(stat.file_count || 0);
        totalPdf += parseInt(stat.pdf_count || 0);
        totalDoc += parseInt(stat.doc_count || 0);
    });
    
    document.getElementById('totalGroups').textContent = totalGroups;
    document.getElementById('totalFiles').textContent = totalFiles;
    document.getElementById('totalPdf').textContent = totalPdf;
    document.getElementById('totalDoc').textContent = totalDoc;
}

// Загрузка групп
function loadGroups() {
    const groupsGrid = document.getElementById('groupsGrid');
    
    groupsGrid.innerHTML = `
        <div class="loading">
            <div class="spinner"></div>
            <p>Загрузка групп...</p>
        </div>
    `;
    
    fetch('/control/api/getGroups')
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            currentGroups = data.groups;
            renderGroups(data.groups);
        } else {
            showAlert('Ошибка загрузки групп', 'error');
        }
    })
    .catch(error => {
        showAlert('Ошибка загрузки групп', 'error');
        console.error('Error:', error);
    });
}

// Отображение групп
function renderGroups(groups) {
    const groupsGrid = document.getElementById('groupsGrid');
    
    if (!groups || groups.length === 0) {
        groupsGrid.innerHTML = `
            <div class="empty-state">
                <i>👥</i>
                <h3>Группы не найдены</h3>
                <p>Добавьте первую группу студентов</p>
            </div>
        `;
        return;
    }
    
    const groupsHtml = groups.map(group => {
        const stat = currentStats.find(s => s.groupname === group.groupname);
        const fileCount = stat ? parseInt(stat.file_count || 0) : 0;
        const pdfCount = stat ? parseInt(stat.pdf_count || 0) : 0;
        const docCount = stat ? parseInt(stat.doc_count || 0) : 0;
        
        return `
            <div class="group-card" data-group-id="${group.id_group}">
                <div class="group-header">
                    <div class="group-name">${escapeHtml(group.groupname)}</div>
                    <div class="group-actions">
                        <button class="view-files-btn" onclick="viewGroupFiles('${group.groupname}')">
                            Файлы
                        </button>
                        <button class="delete-group-btn" onclick="deleteGroup(${group.id_group})">
                            Удалить
                        </button>
                    </div>
                </div>
                
                <div class="group-stats">
                    <div class="group-stat">
                        <div class="group-stat-number">${fileCount}</div>
                        <div class="group-stat-label">Всего файлов</div>
                    </div>
                    <div class="group-stat">
                        <div class="group-stat-number">${pdfCount}</div>
                        <div class="group-stat-label">PDF</div>
                    </div>
                    <div class="group-stat">
                        <div class="group-stat-number">${docCount}</div>
                        <div class="group-stat-label">Word</div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    groupsGrid.innerHTML = groupsHtml;
}

// Настройка формы добавления группы
function setupAddGroupForm() {
    const form = document.getElementById('addGroupForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const groupName = document.getElementById('groupName').value.trim();
        
        if (!groupName) {
            showAlert('Пожалуйста, введите название группы', 'error');
            return;
        }
        
        formData.append('group_name', groupName);
        
        fetch('/control/api/addGroup', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showAlert(data.message, 'success');
                closeModal('addGroupModal');
                form.reset();
                loadGroups();
                loadStats();
            } else {
                showAlert(data.error, 'error');
            }
        })
        .catch(error => {
            showAlert('Ошибка добавления группы', 'error');
            console.error('Error:', error);
        });
    });
}

// Удаление группы
function deleteGroup(groupId) {
    if (!confirm('Вы уверены, что хотите удалить эту группу? Все файлы группы также будут удалены.')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('group_id', groupId);
    
    fetch('/control/api/deleteGroup', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showAlert(data.message, 'success');
            loadGroups();
            loadStats();
        } else {
            showAlert(data.error, 'error');
        }
    })
    .catch(error => {
        showAlert('Ошибка удаления группы', 'error');
        console.error('Error:', error);
    });
}

// Просмотр файлов группы
function viewGroupFiles(groupName) {
    const modal = document.getElementById('filesModal');
    const modalTitle = document.getElementById('filesModalTitle');
    const modalContent = document.getElementById('filesModalContent');
    
    modalTitle.textContent = `Файлы группы ${groupName}`;
    modalContent.innerHTML = `
        <div class="loading">
            <div class="spinner"></div>
            <p>Загрузка файлов...</p>
        </div>
    `;
    
    modal.style.display = 'block';
    
    const formData = new FormData();
    formData.append('group_name', groupName);
    
    fetch('/control/api/getFilesByGroup', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            renderGroupFiles(data.files, modalContent);
        } else {
            modalContent.innerHTML = `
                <div class="alert alert-error">
                    ${data.error}
                </div>
            `;
        }
    })
    .catch(error => {
        modalContent.innerHTML = `
            <div class="alert alert-error">
                Ошибка загрузки файлов
            </div>
        `;
        console.error('Error:', error);
    });
}

// Отображение файлов группы
function renderGroupFiles(files, container) {
    if (!files || files.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i>📁</i>
                <h3>Файлы не найдены</h3>
                <p>В этой группе пока нет загруженных файлов</p>
            </div>
        `;
        return;
    }
    
    const filesHtml = files.map(file => {
        const fileExtension = getFileExtension(file.filename);
        const fileIcon = getFileIcon(fileExtension);
        
        return `
            <div class="file-item">
                <div class="file-name">
                    ${fileIcon} ${escapeHtml(file.filename)}
                </div>
                <div class="file-actions">
                    <a href="/control/download?file_id=${file.id}" 
                       class="file-btn download-file-btn" target="_blank">
                        Скачать
                    </a>
                    <button class="file-btn delete-file-btn" onclick="deleteFile(${file.id})">
                        Удалить
                    </button>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = `
        <div class="files-list">
            ${filesHtml}
        </div>
    `;
}

// Удаление файла из модального окна
function deleteFile(fileId) {
    if (!confirm('Вы уверены, что хотите удалить этот файл?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('file_id', fileId);
    
    fetch('/control/api/delete', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showAlert(data.message, 'success');
            // Обновляем статистику и группы
            loadGroups();
            loadStats();
            // Закрываем модальное окно
            closeModal('filesModal');
        } else {
            showAlert(data.error, 'error');
        }
    })
    .catch(error => {
        showAlert('Ошибка удаления файла', 'error');
        console.error('Error:', error);
    });
}

// Показать модальное окно добавления группы
function showAddGroupModal() {
    document.getElementById('addGroupModal').style.display = 'block';
    document.getElementById('groupName').focus();
}

// Закрыть модальное окно
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Вспомогательные функции
function getFileExtension(filename) {
    return filename.split('.').pop().toLowerCase();
}

function getFileIcon(extension) {
    switch (extension) {
        case 'pdf': return '📄';
        case 'doc': return '📝';
        case 'docx': return '📝';
        default: return '📁';
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showAlert(message, type) {
    const alert = document.getElementById('alert');
    alert.textContent = message;
    alert.className = `alert alert-${type}`;
    alert.style.display = 'block';
    
    setTimeout(() => {
        alert.style.display = 'none';
    }, 5000);
}

// Закрытие модальных окон при клике вне их
window.onclick = function(event) {
    const addGroupModal = document.getElementById('addGroupModal');
    const filesModal = document.getElementById('filesModal');
    
    if (event.target === addGroupModal) {
        closeModal('addGroupModal');
    }
    
    if (event.target === filesModal) {
        closeModal('filesModal');
    }
}
</script>

<?php echo $footer; ?> 