<?php echo $header ?>
<?php if (!empty($error)): ?>
    <?php echo $error ?>
<?php endif; ?>
<div>
    <h2>Редактирование фотографий лучших спортсменов</h2>
    <hr>
    <div class="spiski">
        <h2>Список фотографий</h2>
        <table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; word-break: break-word; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
            <tbody style="cursor: pointer;" class="style-table">
                <?php foreach($images as $item): ?>
                <tr>
                    <td><?php echo $item['images_text'] ?></td>
                    <td class="hidden-xs" style="width: 120px; text-align: center;"><?php echo date("d.m", strtotime($item['images_date_add'])) ?>.<?php echo date("Y", strtotime($item['images_date_add'])) ?></td>
                    <td style="width: 110px;">
                        <form action="/admin/bestsport/delete/<?php echo $item['images_id'] ?>">
                            <button class="btn-danger my-btn" style="font-size: 14px; width: 100%; border: 0; padding: 5px;">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if($empty1 == (int)0): ?>
                <tr>
                    <td style="text-align: center;">На данный момент нет фотографий</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <hr>
    <form id="uploadForm" method="POST">
    <!-- Область предпросмотра -->
        <div style="display: table: width: 100%;">
            <div class="c-content-overlay" id="block-view" style="display: none">
                <a id="bloa" style="display: none;"></a>
                <img width="300" class="c-overlay-object img-responsive" id="blah" src="#" style="pointer-events: none;">
            </div>
            <h2>Загрузка новых фотографий</h2>
            <div class="upload-izm" style="width: 100%;" id="prosto">
                <label class="switch" style="float: left;">
                    <input type="checkbox" id="uploadsp1" name="uploadsp1" onchange="togglepos1()">
                    <span class="slider-sw"></span>
                </label>
                <label for="uploadsp1" style="display: block; float: left; margin-left: 15px; margin-bottom: 15px; font-weight: 100; font-size: 16px; cursor: pointer; user-select: none;">Загрузить фотографию</label>
                <div class="profile-pass" style="margin-bottom: 0;">
                    <div class="form-group">
                        <input name="filephoto" type="file" id="filephoto" style="width: 100%; font-size: 15px; text-align-last: center;" accept="image/*" disabled>
                    </div>
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
            $('#filephoto').prop('disabled', false);
        } else {
            $('#filephoto').prop('disabled', true);
        }
    }
    let hidebut = document.getElementById('but');
    $('#uploadForm').ajaxForm({
        url: '/admin/bestsport/ajax',
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