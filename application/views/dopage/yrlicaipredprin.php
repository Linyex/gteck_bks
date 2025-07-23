
<div class="col-md-12" style="margin-bottom: 20px; padding: 0;">
    <h2>Электронные обращения юридических лиц и ИП</h2>
    <hr>
    <div class="message-text">
        <h3>Электронное обращение в обязательном порядке должно содержать:</h3>
        <ul>
            <li>наименование и (или) адрес организации либо должность лица, которым направляется обращение;</li>
            <li>полное наименование юридического лица и его место нахождения;</li>
            <li>изложение сути обращения;</li>
            <li>фамилию, собственное имя, отчество (если таковое имеется) либо инициалы руководителя или лица, уполномоченного в установленном порядке подписывать обращения;</li>
            <li>адрес электронной почты заявителя.</li>
        </ul>
        <p>К электронным обращениям, подаваемым представителями заявителей, должны прилагаться электронные копии документов, подтверждающих их полномочия.</p>
    </div><br>
    <form id="sendmessageForm" method="POST" enctype="multipart/form-data">
        <div class="profile-f">
            <h2>Ф.И.О руководителя или лица, уполномоченного подписывать обращения: <b style="color: #c61c1c">*</b></h2>
            <input type="text" id="fio" name="fio" class="input" placeholder="Введите Ф.И.О" value="">
            <h2>Полное наименование юридического лица или ИП: <b style="color: #c61c1c">*</b></h2>
            <input type="text" id="company" name="company" class="input" placeholder="Введите наименование" value="">
            <h2>Место нахождения (адрес): <b style="color: #c61c1c">*</b></h2>
            <input type="text" id="mesto" name="mesto" class="input" placeholder="Введите место нахождения" value="">
            <h2>Email: <b style="color: #c61c1c">*</b></h2>
            <input type="text" id="email" name="email" class="input" placeholder="Введите адрес электронной почты" value="">
            <h2>Изложение сути обращения: <b style="color: #c61c1c">*</b></h2>
            <textarea name="text" id="text" cols="45" rows="5"></textarea>
            <h2>Прикрепить файлы (не более 2 Мб):</h2>
            <div class="upload-izm" style="width: 100%;" id="prosto">
                <div class="profile-pass" style="margin-bottom: 0;">
                    <div class="form-group">
                        <input name="myfile[]" type="file" multiple id="myfile" style="font-size: 15px; width: 100%; text-align-last: center;" accept=".doc,.docx,.pdf,.rtf,.txt,.odt,.zip,.rar,.png,.tiff,.jpg,.jpeg">
                    </div>
                </div>
            </div>
            <h2>Введите проверочный код: <b style="color: #c61c1c">*</b></h2>
            <div class="save-all2">
                <input type="text" id="kod" name="kod" class="input" placeholder="Введите код" style="float: left; width: 100%; margin-right: 10px; height: 100%;">
                <img src="/assets/captcha/captcha.php" data-src="/assets/captcha/captcha.php" width="172" height="42" alt="Капча" style="border: solid 1px #c3c3c3; pointer-events: none;">
            </div><br>
            <div style="display: table; width: 100%;">
                <h2 style="float: left; max-width: 70%;">Я даю согласие на обработку отправляемых мною персональных данных: <b style="color: #c61c1c">*</b></h2>
                <input type="checkbox" id="sogl" name="sogl" style="float: left; width: auto; margin: 10px; width: 20px; height: 20px">
            </div>
            <div style="display: table; width: 100%;" class="fast-menu">
                <h2 style="float: left; max-width: 70%;">Я ознакомился с <a href="/message"> правилами о порядке подачи и рассмотрения электронных обращений, требований предьявляемых к электронному обращению</a>: <b style="color: #c61c1c">*</b></h2>
                <input type="checkbox" id="znakom" name="znakom" style="float: left; width: auto; margin: 10px; width: 20px; height: 20px">
            </div>
        </div>
        <div class="save-all" id="buts">
            <button type="submit" name="send">Отправить обращение</button>
        </div>
    </form>
</div>
<script>
    $('#sendmessageForm').ajaxForm({
        url: '/message/yrlicaipredprin/ajax',
        dataType: 'json',
        success: function(data) {
            switch (data.status) {
                case 'error':
                    $('button[type=submit]').prop('disabled', false);
                    $("#otvet").html("<div class='answer answer-danger'>" + data.error + "</div>");
                    break;
                case 'success':
                    $("#otvet").html("<div class='answer answer-success'>" + data.success + "</div>");
                    setTimeout("redirect('/message/grazhdan')", 1500);
                    break;
            }
        },
        beforeSubmit: function(arr, $form, options) {
            $('button[type=submit]').prop('disabled', true);
        }
    });
</script>
<?php echo $footer ?>