<?php echo $header ?>
<h2>Редактирование страницы - "<?php echo $pageinfo['name'] ?>"</h2>
<hr>
<div style="text-align: center;">
    <p><span class="c-font-red-1"><strong>Внимание!</strong></span></p>
    <p>Редактируйте только текст страницы.<br> Также не стирайте Html и Php код, чтобы не нарушить работоспособность страницы.</p>
</div>
<div class="floats clearfix">
    <div id="dle-content">
        <form id="editPageForm" method="POST">
            <div class="profile-content info">
                <div class="profile-f">
                    <code>
                        <textarea name="edito" id="edito" rows="18" style="width: 100%; font-size: 14px; line-height: 20px; resize: vertical;"><?php echo $pageinfo['code'] ?></textarea></code>
                </div>
            </div>
            <div class="save-all">
                <button id="but1" class="btn-success my-btn" style="width: 100%; border: 0;" type="submit">Сохранить изменения</button>
            </div>
        </form>
    </div>
</div>
<script>
    let hidebut = document.getElementById('but1');
    $('#editPageForm').ajaxForm({
        url: '/admin/pages/okno/edit/ajax/<?php echo $pageinfo['name'] ?>',
        dataType: 'text',
        success: function(data) {
            console.log(data);
            data = $.parseJSON(data);
            switch (data.status) {
                case 'error':
                    hidebut.setAttribute('style', 'display: block;');
                    $('button[type=submit]').prop('disabled', false);
                    $("#otvet").html("<div class='answer answer-danger'>" + data.error + "</div>");
                    break;
                case 'success':
                    $("#otvet").html("<div class='answer answer-success'>" + data.success + "</div>");
                    setTimeout("redirect('/admin/pages/okno/edit/mane/<?php echo $pageinfo['name'] ?>')", 1500);
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