<?php echo $header ?>
<div class="col-md-12" style="padding: 0px; display: inline-block;">
   <h2>Настройки пользователя</h2>
   <hr>
   <div class="profile-content">
       <form id="editPanForm" method="POST">
           <div class="changepas">
               <div class="profile-pass">
                   <div class="profile-pass">
                       <div for="login" class="fieldLabel">Логин:</div>
                       <div class="fieldContent">
                           <input type="text" id="login" name="login" value="<?php echo $user_login  ?>" class="input">
                       </div>
                  </div>
               </div>
               <label class="switch" style="float: left;">
                   <input type="checkbox" id="editpassword" name="editpassword" onchange="togglePassword()">
                   <span class="slider-sw"></span>
               </label>
               <label for="editpassword" style="display: block; float: left; margin-left: 15px; margin-bottom: 15px; font-weight: 100; font-size: 16px; cursor: pointer; user-select: none;">Сменить пароль</label>
               <div class="profile-pass">
                   <div class="profile-pass">
                       <div for="password" class="fieldLabel">Текущий пароль:</div>
                       <div class="fieldContent">
                           <input type="text" id="password" name="password" class="input" disabled>
                       </div>
                       <div for="password1" class="fieldLabel">Пароль:</div>
                       <div class="fieldContent">
                           <input type="password" id="password1" name="password1" class="input" disabled>
                       </div>
                       <div for="password2" class="fieldLabel">Подтвердите пароль:</div>
                       <div class="fieldContent">
                           <input type="password" id="password2" name="password2" class="input" disabled>
                       </div>
                       <div class="save-all">
                           <button type="submit">Сохранить изменения</button>
                       </div>
                   </div>
               </div>
               <p style="font-size: 15px;" align="center">Рекомендуется сделать надежный пароль для защиты аккаунта</p>
           </div>
       </form>
   </div>
</div>
<script>
   function togglePassword() {
       var status = $('#editpassword').is(':checked');
       if (status) {
           $('#password').prop('disabled', false);
           $('#password1').prop('disabled', false);
           $('#password2').prop('disabled', false);
       } else {
           $('#password').prop('disabled', true);
           $('#password1').prop('disabled', true);
           $('#password2').prop('disabled', true);
       }
   }
    $('#editPanForm').ajaxForm({
        url: '/admin/settings/ajax',
        dataType: 'json',
        success: function(data) {
            switch (data.status) {
                case 'error':
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
            $('button[type=submit]').prop('disabled', true);
        }
    });
</script>
<?php echo $footer ?>