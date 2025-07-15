<?php echo $header ?>
<?php if (!empty($error)): ?>
    <?php echo $error ?>
<?php endif; ?>
<div class="col-md-12" style="padding: 0; display: inline-block;">
    <h2>Редактирование новости #<?php echo $news['news_id'] ?></h2>
    <hr>
    <div class="col-md-12">
        <form id="editForm" method="POST" enctype="multipart/form-data">
            <article class="news pp3" style="margin-bottom: 0; border-bottom: 0;">
                <div class="col-md-4" style="padding: 0;">
                    <!-- Область предпросмотра -->
                    <h2 style="font-size: 18px;">Главная фотография:</h2>
                    <div class="c-content-overlay" id="block-view">
                        <div class="c-overlay-wrapper">
                            <div class="c-overlay-content">
                                <a data-fancybox="gallery" id="bloa" href="/<?php echo $news['news_image'] ?>">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                        </div><img class="c-overlay-object img-responsive" id="blah" src="/<?php echo $news['news_image'] ?>">
                    </div>
                    <div class="upload-izm" style="margin-top: 30px; width: 100%;" id="prosto">
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
                            <input type="text" id="title" name="title" class="input" placeholder="Введите заголовок" value="<?php echo $news['news_title'] ?>">
                            <h2>Выберите категорию:</h2>
                            <select class="form-control" id="category" name="category">
                                <option value="0" <?php if($news['category_id'] == 1): ?> selected="selected"<?php endif; ?>>Общие новости</option>
                                <option value="1" <?php if($news['category_id'] == 2): ?> selected="selected"<?php endif; ?>>Спортивная жизнь</option>
                                <option value="2" <?php if($news['category_id'] == 3): ?> selected="selected"<?php endif; ?>>Педагог-психолог</option>
                                <option value="3" <?php if($news['category_id'] == 4): ?> selected="selected"<?php endif; ?>>Социальный педагог</option>
                                <option value="4" <?php if($news['category_id'] == 5): ?> selected="selected"<?php endif; ?>>Абитуриенту</option>
                                <option value="5" <?php if($news['category_id'] == 6): ?> selected="selected"<?php endif; ?>>Дневное отделение</option>
                                <option value="6" <?php if($news['category_id'] == 7): ?> selected="selected"<?php endif; ?>>Заочное отделение</option>
                                <option value="7" <?php if($news['category_id'] == 8): ?> selected="selected"<?php endif; ?>>Отделение проф.подготовки</option>
                                <option value="8" <?php if($news['category_id'] == 9): ?> selected="selected"<?php endif; ?>>Общежитие</option>
                                <option value="9" <?php if($news['category_id'] == 10): ?> selected="selected"<?php endif; ?>>Сотрудничество</option>
                                <option value="10" <?php if($news['category_id'] == 11): ?> selected="selected"<?php endif; ?>>Информационно-воспитательная работа</option>
                            </select>
                            <h2>Описание:</h2>
                            <textarea name="text" id="text" cols="45" rows="5"><?php echo $news['news_text'] ?></textarea>
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
                    <div id="outputMulti">
                    </div>
                    <div class="upload-izm" style="width: 100%;">
                        <div class="profile-pass" style="margin-bottom: 0;">
                            <div class="row">
                                <span id="outputMulti"></span>
                            </div>
                            <div class="form-group">
                                <input name="uploadbtn[]" type="file" multiple id="uploadbtn" style="font-size: 15px; width: 100%; text-align-last: center;" accept="image/*" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="save-all" id="but1">
                        <button type="submit" name="send">Сохранить изменения</button>
                    </div>
                </div>
            </div>
        </form>
        <?php if (!empty($newsphoto)): ?>
        <label style="display: block; float: left; margin-left: 15px; margin-bottom: 10px; font-weight: 100; font-size: 20px; cursor: pointer; user-select: none;">Редактирование фотографий</label>

        <div style="border-top: solid rgba(128, 128, 128, 0.48) 1px; padding-top: 20px; padding-bottom: 20px; display: table; width: 100%;">
            <div class="photos">

                <?php foreach($newsphoto as $item): ?>
                <div class="works">
                    <div class="c-content-overlay">
                        <div class="c-overlay-wrapper">
                            <div class="c-overlay-content">
                                <a href="/<?php echo $item['news_img'] ?>" data-fancybox="gallery">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                        </div>
                        <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/<?php echo $item['news_img'] ?>');"></div>
                    </div>
                    <div class="save-all" style="width: 100%;">
                        <form action="/admin/news/edit/delete/<?php echo $item['img_id'] ?>">
                            <button style="width: 100%; border: 0;" type="submit">Удалить фото</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
        <?php endif; ?>
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
    let hidebut = document.getElementById('but1');
    $('#editForm').ajaxForm({ 
        url: '/admin/news/edit/ajax/<?php echo $news['news_id'] ?>',
        dataType: 'text',
        success: function(data) {
            console.log(data);
            data = $.parseJSON(data);
            switch(data.status) {
                case 'error':
                    hidebut.setAttribute('style', 'display: block;');
                    $('button[type=submit]').prop('disabled', false);
                    $("#otvet").html("<div class='answer answer-danger'>" + data.error + "</div>");
                    break;
                case 'success':
                    $("#otvet").html("<div class='answer answer-success'>" + data.success + "</div>");
                    setTimeout("redirect('/admin/news/edit/mane/<?php echo $news['news_id'] ?>')", 1500);
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
