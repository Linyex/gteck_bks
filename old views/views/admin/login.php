<!DOCTYPE html>
<html lang="ru" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title>Панель управления | Гомельский торгово-экономический колледж</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="gtec-bks.by" name="url" />
    <link rel="stylesheet" href="/assets/css/timesnewroman/timesnewromanpsmt.ttf" type="text/css">
    <link href="/assets/css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/gtec.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/components-rtl.css" id="style_components" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="/favicon.png" />
    <style>
        .content {
            margin-top: 50px;
        }

    </style>
</head>
<div id="otvet" class="otv"></div>
<body style="background: #f4f4f4;">

    <!-- BEGIN: LoGIN -->
    <div class="center clearfix login" >
        <div class="" style="background: #fff; padding: 20px; display: block; text-align: center;">
           
            <div class="logo-st" style="margin-bottom: 10px; text-align: center; display: block;">
                <a href="#" class="logotype" title="На главную">
                    <img src="/assets/media/img/logos/logo2.png" alt="" width="80px;">
                </a>
                <div class="c-brand log" align="left">
                    <h4 class="c-font-15  c-desktop-logo-inverse c-desktop-logo"></h4>
                </div>
            </div>
            <h4>Учреждение образования<br class="">
                "Гомельский торгово-экономеский колледж"<br>Белкоопсоюза</h4>
            <hr>
            <div class="login-profile">
                <div class="profile-content" style="text-align: left;">
                    <form id="loginForm" method="POST">
                        <div class="profile-content info">
                            <div class="profile-f">
                                <h2>Логин пользователя</h2>
                                <input type="text" id="log" name="log" class="input" placeholder="Введите логин" value="">
                                <h2>Пароль</h2>
                                <input type="password" id="password" name="password" class="input" placeholder="Введите пароль" value="">
                            </div>
                        </div>

                        <div class="save-all">
                            <button id="login" type="submit">Войти</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr><h4>Вход в панель управления</h4>
        </div>
    </div>
    <!-- END: LOGIN -->

    <!-- END: FOOTER -->
    <a href="javascript:void(0);" class="js-back-to-top back-to-top">Наверх</a>
    <!-- BEGIN: JS SCRIPTS -->
    <script src="/assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/bootstrap.js" type="text/javascript"></script>
    <script src="/assets/js/jquery.back-to-top.js" type="text/javascript"></script>
    <!-- FORMS -->
    <script src="/assets/js/private/jquery.js"></script>
    <script src="/assets/js/private/jquery.form.js"></script>
    <script src="/assets/js/private/jquery.flot.js"></script>
    <script src="/assets/js/private/jquery.flot.time.js"></script>
    <script src="/assets/js/private/main.js"></script>
    <script src="/assets/js/gtec.js"></script>
    <script>
        $('#loginForm').ajaxForm({
            url: '/admin/login/ajax',
            dataType: 'text',
            success: function(data) {
                console.log(data);
                data = $.parseJSON(data);
                switch (data.status) {
                    case 'error':
                        $("#otvet").html("<div class='answer answer-danger'>" + data.error + "</div>");
                        break;
                    case 'success':
                        $("#otvet").html("<div class='answer answer-success'>" + data.success + "</div>");
                        setTimeout("redirect('/admin')", 1500);
                        break;
                }
            },
            beforeSubmit: function(arr, $form, options) {}
        });
    </script>
</body>

</html>