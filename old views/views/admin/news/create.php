<?php echo $header ?>
<?php if (!empty($error)): ?>
    <?php echo $error ?>
<?php endif; ?>
<div class="col-md-12" style="padding: 0; display: inline-block;">
    <h1>Создание новости</h1>
    <div class="col-md-12">
        <form id="createForm" method="POST" enctype="multipart/form-data">
            <article class="news pp3" style="margin-bottom: 0; border-bottom: 0;">
                <div class="col-md-4" style="padding: 0;">
                    <!-- Область предпросмотра -->
                    <h2 style="font-size: 18px;">Главная фотография: <?php echo $error ?></h2>
                    <div class="c-content-overlay" id="block-view" style="display: none">
                        <a id="bloa" style="display: none;"></a>
                        <img class="c-overlay-object img-responsive" id="blah" src="#" style="pointer-events: none;">
                    </div>
                    <div class="upload-izm" style="width: 100%;" id="prosto">
                        <div class="profile-pass" style="margin-bottom: 0;">
                            <div class="form-group">
                                <input name="filephoto" type="file" id="filephoto" style="width: 100%; font-size: 15px; text-align-last: center;" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 news-info">
                    <div class="profile-content info">
                        <div class="profile-f">
                            <h2>Заголовок:</h2>
                            <input type="text" id="title" name="title" class="input" placeholder="Введите заголовок" value="">
                            <h2>Выберите категорию:</h2>
                            <select class="form-control" id="category" name="category">
                                <option value="0" selected="selected">Общие новости</option>
                                <option value="1">Спортивная жизнь</option>
                                <option value="2">Педагог-психолог</option>
                                <option value="3">Социальный педагог</option>
                                <option value="4">Абитуриенту</option>
                                <option value="5">Дневное отделение</option>
                                <option value="6">Заочное отделение</option>
                                <option value="7">Отделение проф.подготовки</option>
                                <option value="8">Общежитие</option>
                                <option value="9">Сотрудничество</option>
                                <option value="10">Информационно-воспитательная работа</option>
                            </select>
                            <h2>Описание:</h2>
                            <textarea name="text" id="text" cols="45" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </article>
            <label class="switch" style="float: left; margin-top: 5px;">
                <input type="checkbox" id="uploadizm" name="uploadizm" onchange="toggleIzm()">
                <span class="slider-sw"></span>
            </label>
            <label for="uploadizm" style="display: block; float: left; margin-left: 15px; margin-bottom: 10px; font-weight: 100; font-size: 20px; cursor: pointer; user-select: none;">Загрузить дополнительные фотографии</label>

            <div style="border-top: solid rgba(128, 128, 128, 0.48) 1px; padding-top: 20px; padding-bottom: 20px; display: table; width: 100%;">
                <div class="photos">
                    <div class="upload-izm" style="width: 100%;">
                        <div class="profile-pass" style="margin-bottom: 0;">
                            <div class="form-group">
                                <input name="uploadbtn[]" type="file" multiple id="uploadbtn" style="font-size: 15px; width: 100%; text-align-last: center;" accept="image/*" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="save-all" id="buts">
                        <button type="submit" name="send">Сохранить изменения</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function toggleIzm() {
        var status = $('#uploadizm').is(':checked');
        if (status) {
            $('#uploadbtn').prop('disabled', false);
        } else {
            $('#uploadbtn').prop('disabled', true);
        }
    }
    let hidebut = document.getElementById('buts');
    $('#createForm').ajaxForm({
        url: '/admin/news/create/ajax',
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
                    setTimeout("redirect('/admin/news')", 1500);
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