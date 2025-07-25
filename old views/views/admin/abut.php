<?php echo $header ?>
<?php if (!empty($error)): ?>
    <?php echo $error ?>
<?php endif; ?>
<div>
    <h2>Списки зачисленных</h2>
    <hr>
    <div class="spiski">
        <h2>На основе общего базового образования (9 классов)</h2>
        <h3>Дневная форма получения образования</h3>

        <table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; word-break: break-word; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
            <tbody style="cursor: pointer;" class="style-table">
                <?php foreach($sabut as $item): ?>
                <?php if ($item['sabut_status'] == (int)1): ?>
                <tr>
                    <td><?php echo $item['sabut_text'] ?></td>
                    <td class="hidden-xs" style="width: 120px; text-align: center;"><?php echo date("d.m", strtotime($item['sabut_date_add'])) ?>.<?php echo date("Y", strtotime($item['sabut_date_add'])) ?></td>
                    <td style="width: 110px;">
                        <form action="/admin/abut/delete/<?php echo $item['sabut_id'] ?>">
                            <button class="btn-danger my-btn" style="font-size: 14px; width: 100%; border: 0; padding: 5px;">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php if($empty1 == (int)0): ?>
                <tr>
                    <td style="text-align: center;">На данный момент нет списков</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>На основе общего среднего образования (11 классов)</h2>
        <h3>Дневная форма получения образования</h3>
        <table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; word-break: break-word; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
            <tbody style="cursor: pointer;" class="style-table">
                <?php foreach($sabut as $item): ?>
                <?php if ($item['sabut_status'] == (int)2): ?>
                <tr>
                    <td><?php echo $item['sabut_text'] ?></td>
                    <td class="hidden-xs" style="width: 120px; text-align: center;"><?php echo date("d.m", strtotime($item['sabut_date_add'])) ?>.<?php echo date("Y", strtotime($item['sabut_date_add'])) ?></td>
                    <td style="width: 110px;">
                        <form action="/admin/abut/delete/<?php echo $item['sabut_id'] ?>">
                            <button class="btn-danger my-btn" style="font-size: 14px; width: 100%; border: 0; padding: 5px;">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php if($empty2 == (int)0): ?>
                <tr>
                    <td style="text-align: center;">На данный момент нет списков</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>Заочная форма получения образования</h3>
        <table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; word-break: break-word; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
            <tbody style="cursor: pointer;" class="style-table">
                <?php foreach($sabut as $item): ?>
                <?php if ($item['sabut_status'] == (int)3): ?>
                <tr>
                    <td><?php echo $item['sabut_text'] ?></td>
                    <td class="hidden-xs" style="width: 120px; text-align: center;"><?php echo date("d.m", strtotime($item['sabut_date_add'])) ?>.<?php echo date("Y", strtotime($item['sabut_date_add'])) ?></td>
                    <td style="width: 110px;">
                        <form action="/admin/abut/delete/<?php echo $item['sabut_id'] ?>">
                            <button class="btn-danger my-btn" style="font-size: 14px; width: 100%; border: 0; padding: 5px;">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php if($empty3 == (int)0): ?>
                <tr>
                    <td style="text-align: center;">На данный момент нет списков</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>На основе профессионально-технического образования (ПТО)</h2>

        <h3>Заочная форма получения образования</h3>
        <table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; word-break: break-word; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
            <tbody style="cursor: pointer;" class="style-table">
                <?php foreach($sabut as $item): ?>
                <?php if ($item['sabut_status'] == (int)4): ?>
                <tr>
                    <td><?php echo $item['sabut_text'] ?></td>
                    <td class="hidden-xs" style="width: 120px; text-align: center;"><?php echo date("d.m", strtotime($item['sabut_date_add'])) ?>.<?php echo date("Y", strtotime($item['sabut_date_add'])) ?></td>
                    <td style="width: 110px;">
                        <form action="/admin/abut/delete/<?php echo $item['sabut_id'] ?>">
                            <button class="btn-danger my-btn" style="font-size: 14px; width: 100%; border: 0; padding: 5px;">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php if($empty4 == (int)0): ?>
                <tr>
                    <td style="text-align: center;">На данный момент нет списков</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <hr>
    <h2>Загрузка новых списков</h2>
    <form id="uploadForm" method="POST">
        <div class="upload-izm" style="margin-bottom: 10px; width: 100%;">
            <label class="switch" style="float: left;">
                <input type="checkbox" id="uploadsp1" name="uploadsp1" onchange="togglepos1()">
                <span class="slider-sw"></span>
            </label>
            <label for="uploadsp1" style="display: block; float: left; margin-left: 15px; margin-bottom: 15px; font-weight: 100; font-size: 16px; cursor: pointer; user-select: none;">На основе общего базового образования (9 классов) - Дневная</label>
            <div class="profile-pass">
                <div class="form-group">
                    <input name="uplfile1" type="file" id="uplfile1" style="word-break: break-word; width: 100%; font-size: 16px; text-align-last: center;" accept="application/pdf, application/msword, .docx, .xls, .xlsx" onchange="ValidateSingleInput(this);" disabled>
                </div>
            </div>
        </div>
        <div class="upload-izm" style="margin-bottom: 10px; width: 100%;">
            <label class="switch" style="float: left;">
                <input type="checkbox" id="uploadsp2" name="uploadsp2" onchange="togglepos2()">
                <span class="slider-sw"></span>
            </label>
            <label for="uploadsp2" style="display: block; float: left; margin-left: 15px; margin-bottom: 15px; font-weight: 100; font-size: 16px; cursor: pointer; user-select: none;">На основе общего среднего образования (11 классов) - Дневная</label>
            <div class="profile-pass">
                <div class="form-group">
                    <input name="uplfile2" type="file" id="uplfile2" style="word-break: break-word; width: 100%; font-size: 16px; text-align-last: center;" accept="application/pdf, application/msword, .docx, .xls, .xlsx" onchange="ValidateSingleInput(this);" disabled>
                </div>
            </div>
        </div>
        <div class="upload-izm" style="margin-bottom: 10px; width: 100%;">
            <label class="switch" style="float: left;">
                <input type="checkbox" id="uploadsp3" name="uploadsp3" onchange="togglepos3()">
                <span class="slider-sw"></span>
            </label>
            <label for="uploadsp3" style="display: block; float: left; margin-left: 15px; margin-bottom: 15px; font-weight: 100; font-size: 16px; cursor: pointer; user-select: none;">На основе общего среднего образования (11 классов) - Заочная</label>
            <div class="profile-pass">
                <div class="form-group">
                    <input name="uplfile3" type="file" id="uplfile3" style="word-break: break-word; width: 100%; font-size: 16px; text-align-last: center;" accept="application/pdf, application/msword, .docx, .xls, .xlsx" onchange="ValidateSingleInput(this);" disabled>
                </div>
            </div>
        </div>
        <div class="upload-izm" style="margin-bottom: 10px; width: 100%;">
            <label class="switch" style="float: left;">
                <input type="checkbox" id="uploadsp4" name="uploadsp4" onchange="togglepos4()">
                <span class="slider-sw"></span>
            </label>
            <label for="uploadsp4" style="display: block; float: left; margin-left: 15px; margin-bottom: 15px; font-weight: 100; font-size: 16px; cursor: pointer; user-select: none;">На основе профессионально-технического образования (ПТО) - Заочная</label>
            <div class="profile-pass" style="width: 100%;">
                <div class="form-group">
                    <input name="uplfile4" type="file" id="uplfile4" style="word-break: break-word; width: 100%; font-size: 16px; text-align-last: center;" accept="application/pdf, application/msword, .docx, .xls, .xlsx" onchange="ValidateSingleInput(this);" disabled>
                </div>
            </div>
        </div>
        <div class="save-all" id="but">
            <button>Сохранить изменения</button>
        </div>
    </form>
</div>
<script>
    function togglepos1() {
        var status = $('#uploadsp1').is(':checked');
        if (status) {
            $('#uplfile1').prop('disabled', false);
        } else {
            $('#uplfile1').prop('disabled', true);
        }
    }
    function togglepos2() {
        var status = $('#uploadsp2').is(':checked');
        if (status) {
            $('#uplfile2').prop('disabled', false);
        } else {
            $('#uplfile2').prop('disabled', true);
        }
    }
    function togglepos3() {
        var status = $('#uploadsp3').is(':checked');
        if (status) {
            $('#uplfile3').prop('disabled', false);
        } else {
            $('#uplfile3').prop('disabled', true);
        }
    }
    function togglepos4() {
        var status = $('#uploadsp4').is(':checked');
        if (status) {
            $('#uplfile4').prop('disabled', false);
        } else {
            $('#uplfile4').prop('disabled', true);
        }
    }
    let hidebut = document.getElementById('but');
    $('#uploadForm').ajaxForm({
        url: '/admin/abut/ajax',
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
