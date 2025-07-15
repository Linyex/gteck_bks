<?php echo $header ?>
<?php if (!empty($error)): ?>
    <?php echo $error ?>
<?php elseif (!empty($success)): ?>
    <?php echo $success ?>
<?php elseif (!empty($warning)): ?>
    <?php echo $warning ?>
<?php endif; ?>
<div class="col-md-12" style="padding: 0px; display: inline-block;">
    <h2>Добро пожаловать в панель управления сайтом</h2>
    <hr>
    <p>Вы вошли в систему <?php if ($user_access_level >= 3): ?>как Администратор<?php else: ?>Пользователь<?php endif; ?></p>
    <form action="/admin/mane/delete">
        <button class="btn-primary my-btn" style="font-size: 14px; border: 0; padding: 5px 20px; ">Удалить изменения в расписании</button>
    </form>
    <hr>
    <div>
        <p href="admin/test.php" class="test-p">Тестовое поле</p> <br>
        <p href="admin/bugs.php" class="test-p">Не баг а фича</p>
    </div>
    <form id="createForm" method="POST">
        <?php if ($user_access_level >= 3): ?>
        <h2>Приемная комиссия</h2>
        <p>Включите, когда идет ход приемной комиссии - появляется кнопка в боковом меню "Сроки приема документов"</p>
        <label class="switch">
            <?php if($status1['statusb_code'] == (int)2): ?>
                <input id="getpr" name="getpr" type="checkbox" checked>
            <?php else: ?>
                <input id="getpr" name="getpr" type="checkbox">
            <?php endif; ?>
            <span class="slider-sw"></span>
        </label>
        <?php endif; ?>
        <h2>Новый год</h2>
        <p>Включите в период нового года - появляется гирлянда возле шапки.</p>
        <label class="switch">
            <?php if($status2['statusb_code'] == (int)2): ?>
                <input id="getp" name="getp" type="checkbox" checked>
            <?php else: ?>
                <input id="getp" name="getp" type="checkbox">
            <?php endif; ?>
            <span class="slider-sw"></span>
        </label>
        <?php if ($user_access_level >= 3): ?>
        <h2>Изменения в расписании</h2>
        <div class="upload-izm">
            <label class="switch" style="float: left;">
                <input type="checkbox" id="uploadizm" name="uploadizm" onchange="toggleIzm()">
                <span class="slider-sw"></span>
            </label>
            <label for="uploadizm" style="display: block; float: left; margin-left: 15px; margin-bottom: 15px; font-weight: 100; font-size: 16px; cursor: pointer; user-select: none;">Загрузить изменения в расписании</label>
            <div class="profile-pass">
                <div class="form-group">
                    <input name="fileizm" style="width: 100%; font-size: 15px; text-align-last: center;" type="file" id="fileizm" accept="application/pdf,	application/msword, .docx, .xls, .xlsx" onchange="ValidateSingleInput(this);" disabled>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="save-all" id="buts">
            <button>Сохранить изменения</button>
        </div>
    </form>
</div>
<script>
    function toggleIzm() {
        var status = $('#uploadizm').is(':checked');
        if (status) {
            $('#fileizm').prop('disabled', false);
        } else {
            $('#fileizm').prop('disabled', true);
        }
    }
    let hidebut = document.getElementById('buts');
    $('#createForm').ajaxForm({
        url: '/admin/mane/ajax',
        dataType: 'json',
        success: function(data) {
            switch (data.status) {
                case 'error':
                    hidebut.setAttribute('style', 'display: block;');
                    $('button[type=submit]').prop('disabled', false);
                    $("#otvet").html("<div class='answer answer-danger'>" + data.error + "</div>");
                    break;
                case 'success':
                    $("#otvet").html("<div class='answer answer-success'>" + data.success + "</div>");
                    setTimeout("reload()", 1500);
                    break;
            }
        },
        beforeSubmit: function(arr, $form, options) {
            hidebut.setAttribute('style', 'display: none;');
            $('button[type=submit]').prop('disabled', true);
        }
    });
</script>

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
