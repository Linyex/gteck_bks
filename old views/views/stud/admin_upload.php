<?php echo $header/* @var $groups array */ /* @var $files array */ /* @var $success_message string */ /* @var $error_message string */ ?>

<div class="container">
    <h1>Административная панель - Загрузка контрольных работ</h1>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><b> _SESSION['error_message']:</b> <?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
    <?php endif; ?>

<?php
// ВРЕМЕННО: вывод последних 20 строк error_log для диагностики
if (file_exists(__DIR__ . '/../../../error_log')) {
    $log = file(__DIR__ . '/../../../error_log');
    $last = array_slice($log, -20);
    echo '<div class="alert alert-warning" style="white-space:pre-wrap; max-height:300px; overflow:auto; font-size:12px;">';
    echo '<b>Последние строки error_log (для диагностики):</b><br>';
    foreach ($last as $line) {
        echo htmlspecialchars($line) . "<br>";
    }
    echo '</div>';
}
?>

    <form action="/stud/admin_upload/upload" method="post" enctype="multipart/form-data" class="upload-section">
        <div class="form-group">
            <label for="group_id">Группа:</label>
            <select name="group_id" id="group_id" required>
                <?php foreach ($groups as $group): ?>
                    <option value="<?php echo $group['id_group']; ?>"><?php echo htmlspecialchars($group['groupname']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="file">Файл:</label>
            <input type="file" name="file" id="file" required>
        </div>
        <button type="submit" class="btn">Загрузить</button>
    </form>

    <h2>Загруженные файлы</h2>
    <table class="files-table">
        <thead>
            <tr>
                <th>Группа</th>
                <th>Файл</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo htmlspecialchars($file['groupname']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></td>
                    <td>
                        <form action="/stud/admin_upload/delete" method="post" style="display:inline;">
                            <input type="hidden" name="file_id" value="<?php echo $file['id']; ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить файл?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f4;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 30px;
    }
    .upload-section {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }
    select, input[type="file"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }
    .btn {
        background: #007bff;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }
    .btn:hover {
        background: #0056b3;
    }
    .btn-danger {
        background: #dc3545;
    }
    .btn-danger:hover {
        background: #c82333;
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
    .files-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .files-table th, .files-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    .files-table th {
        background: #f8f9fa;
    }
</style> 
<?php echo $footer ?> 