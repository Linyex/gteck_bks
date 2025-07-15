<?php echo $header ?>
<div class="col-md-12" style="padding: 0px; display: inline-block;">
    <h2>Редактирование раздела "Абитуриенту"</h2>
    <hr>
    <form id="createForm" method="POST">
        <h2>Списки зачисленных</h2>
        <p>Включите, чтобы показать дополнительную кнопку в боковом меню "Списки зачисленных абитуриентов"</p>
        <label class="switch">
            <?php if($status3['statusb_code'] == (int)2): ?>
                <input id="getpr" name="getpr" type="checkbox" checked>
            <?php else: ?>
                <input id="getpr" name="getpr" type="checkbox">
            <?php endif; ?>
            <span class="slider-sw"></span>
        </label>
       
        <div class="save-all" id="buts">
            <button>Сохранить изменения</button>
        </div>
    </form>
</div>
<script>
    let hidebut = document.getElementById('buts');
    $('#createForm').ajaxForm({
        url: '/admin/abutedit/ajax',
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
