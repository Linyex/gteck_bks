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
<?php echo $footer ?>
