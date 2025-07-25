<?php /* @var $debug_info array */ ?>
<div class="container">
    <h1>Отладка загрузки файлов</h1>

    <h2>Настройки PHP:</h2>
    <table class="debug-table">
        <tr><td>upload_max_filesize:</td><td><?php echo $debug_info['php_settings']['upload_max_filesize']; ?></td></tr>
        <tr><td>post_max_size:</td><td><?php echo $debug_info['php_settings']['post_max_size']; ?></td></tr>
        <tr><td>max_execution_time:</td><td><?php echo $debug_info['php_settings']['max_execution_time']; ?></td></tr>
        <tr><td>memory_limit:</td><td><?php echo $debug_info['php_settings']['memory_limit']; ?></td></tr>
    </table>

    <h2>Папка загрузки:</h2>
    <table class="debug-table">
        <tr><td>Путь:</td><td><?php echo htmlspecialchars($debug_info['upload_dir']['path']); ?></td></tr>
        <tr><td>Существует:</td><td><?php echo $debug_info['upload_dir']['exists'] ? 'ДА' : 'НЕТ'; ?></td></tr>
        <tr><td>Доступна для записи:</td><td><?php echo $debug_info['upload_dir']['writable'] ? 'ДА' : 'НЕТ'; ?></td></tr>
        <tr><td>Права:</td><td><?php echo $debug_info['upload_dir']['permissions']; ?></td></tr>
    </table>

    <h2>Информация о сервере:</h2>
    <table class="debug-table">
        <tr><td>DOCUMENT_ROOT:</td><td><?php echo htmlspecialchars($debug_info['server']['document_root']); ?></td></tr>
        <tr><td>SCRIPT_NAME:</td><td><?php echo htmlspecialchars($debug_info['server']['script_name']); ?></td></tr>
    </table>

    <?php if (isset($debug_info['upload_test'])): ?>
        <h2>Тест загрузки:</h2>
        <table class="debug-table">
            <tr><td>Имя файла:</td><td><?php echo htmlspecialchars($debug_info['upload_test']['file_info']['name']); ?></td></tr>
            <tr><td>Размер:</td><td><?php echo $debug_info['upload_test']['file_info']['size']; ?> байт</td></tr>
            <tr><td>Тип:</td><td><?php echo htmlspecialchars($debug_info['upload_test']['file_info']['type']); ?></td></tr>
            <tr><td>Код ошибки:</td><td><?php echo $debug_info['upload_test']['error_code']; ?></td></tr>
            <?php if (isset($debug_info['upload_test']['target_path'])): ?>
                <tr><td>Путь сохранения:</td><td><b><?php echo htmlspecialchars($debug_info['upload_test']['target_path']); ?></b></td></tr>
            <?php endif; ?>
        </table>

        <?php if (isset($debug_info['upload_test']['error_message'])): ?>
            <div class="alert alert-danger">
                <strong>Ошибка загрузки:</strong> <?php echo htmlspecialchars($debug_info['upload_test']['error_message']); ?>
            </div>
        <?php elseif (isset($debug_info['upload_test']['status']) && $debug_info['upload_test']['status'] === 'success'): ?>
            <div class="alert alert-success">
                <strong>Файл загружен во временную папку успешно!</strong>
            </div>
            
            <h3>Перемещение файла:</h3>
            <p>Целевой путь: <?php echo htmlspecialchars($debug_info['upload_test']['target_path']); ?></p>
            
            <?php if ($debug_info['upload_test']['move_success']): ?>
                <div class="alert alert-success">
                    <strong>Файл успешно перемещен в целевую папку!</strong>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <strong>Ошибка при перемещении файла!</strong><br>
                    PHP ошибка: <?php echo htmlspecialchars($debug_info['upload_test']['php_error']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    <h2>Тестовая форма загрузки:</h2>
    <form method="post" enctype="multipart/form-data" class="upload-form">
        <div class="form-group">
            <label for="file">Выберите файл:</label>
            <input type="file" name="file" id="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Загрузить файл</button>
    </form>
</div>

<style>
    .debug-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .debug-table td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .debug-table td:first-child {
        font-weight: bold;
        background-color: #f8f9fa;
        width: 200px;
    }
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .upload-form {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 6px;
        margin-top: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-group input[type="file"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }
    .btn-primary {
        background: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background: #0056b3;
    }
</style> 