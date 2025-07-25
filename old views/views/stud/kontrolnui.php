<?php echo $header;
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/main/db_init.php';

// Получаем все группы
$groups = getAllGroups($db);

// Получаем файлы по каждой группе
$group_files = [];
foreach ($groups as $group) {
    $group_files[$group['groupname']] = getFilesByGroup($db, $group['groupname']);
}
?>

<style>
    .panel1,.panel2,.panel3,.t101,.t111,.t201,.t211,.t301,.e101,.e201,.e301,.y101,.y201,.y301,.panelt101,.panelt111,.panele101,.panely101{
        display: none;
    }
    .t111{
        display: none;
    }
    .t111{
        display: none;
    }
    .e101{
        display: none;
    }
    .y101{
        display: none;
    }
    .admin-link {
        display: inline-block;
        background: #007bff;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-bottom: 20px;
        font-weight: bold;
    }
    .admin-link:hover {
        background: #0056b3;
        color: white;
        text-decoration: none;
    }
</style>

<form onsubmit="return PassCheck(document)" method="post" id="pop">
<div id="overlay">
    <div class="popup">
        <a>Введите пароль своей группы для получения доступа к дкр<br></a>
        <input type="text" class="inpass" name="inpass" id="pass">
        <button type="submit" class='btnpass'>Отправить</button>
        <button type="button" onclick="bubs()" class="btnkyrc" id ="kyrc23">Если вы 2-3 курс</button>

    </div>
</div>
</form>

<h2>Контрольные работы</h2>
<hr>

<!-- Ссылка на административную панель -->
<div style="text-align: center; margin-bottom: 20px;">
    <a href="/stud/admin_upload" class="admin-link">Административная панель - Загрузка файлов</a>
</div>

<div style="display: table; width: 100%; word-break: break-word;">
    <!-- BEGIN: 1-I KYRS -->
    <div class="panel1" style="margin-bottom: 10px;" id="panel1">
        <a class="collapsed" data-toggle="collapse" href="#cl" aria-expanded="false" style="color: #fff;">
            <div class="panel-heading" name="first-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">1-й курс</div>
        </a>
        <div id="cl" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">

                <div class="panel" style="margin-bottom: 0px;" id = "panelt111" class ="t111">
                    <a class="collapsed" data-toggle="collapse" href="#cdl" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading"  style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-111</div>
                    </a>

                    <div id="cdl"  class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-111'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['T111']) && !empty($group_files['T111'])): ?>
                                        <?php foreach ($group_files['T111'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel,t101" style="margin-bottom: 0px;" id ="panelt101">
                    <a class="collapsed" data-toggle="collapse" href="#cd2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-101</div>
                    </a>
        
                    <div id="cd2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;" >
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['T101']) && !empty($group_files['T101'])): ?>
                                        <?php foreach ($group_files['T101'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                                    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id ="panele101" class="e101">
                    <a class="collapsed" data-toggle="collapse" href="#cd3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-101</div>
                    </a>

                    <div id="cd3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;" >
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['Э101']) && !empty($group_files['Э101'])): ?>
                                        <?php foreach ($group_files['Э101'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panely101" class ="y101">
                    <a class="collapsed" data-toggle="collapse" href="#cd4" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Ю-101</div>
                    </a>

                    <div id="cd4" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-y-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['Ю101']) && !empty($group_files['Ю101'])): ?>
                                        <?php foreach ($group_files['Ю101'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: 1-I KYRS -->
    <!-- BEGIN: 2-I KYRS -->
    <div class="panel2" style="margin-bottom: 10px;" id = "panel2">
        <a class="collapsed" data-toggle="collapse" href="#c2" style="color: #fff;">
            <div class="panel-heading" name="second-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">2-й курс</div>
        </a>

        <div id="c2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                <div class="panel" style="margin-bottom: 0px;" id ="panelt211" class="t211">
                    <a class="collapsed" data-toggle="collapse" href="#cel" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-211</div>
                    </a>

                    <div id="cel" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-211'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['T211']) && !empty($group_files['T211'])): ?>
                                        <?php foreach ($group_files['T211'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panelt201" class ="t201">
                    <a class="collapsed" data-toggle="collapse" href="#ce2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-201</div>
                    </a>

                    <div id="ce2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['T201']) && !empty($group_files['T201'])): ?>
                                        <?php foreach ($group_files['T201'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panele201" class ="e201">
                    <a class="collapsed" data-toggle="collapse" href="#ce3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-201</div>
                    </a>

                    <div id="ce3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['Э201']) && !empty($group_files['Э201'])): ?>
                                        <?php foreach ($group_files['Э201'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;"id = "panely201" class ="y201">
                    <a class="collapsed" data-toggle="collapse" href="#ce4" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Ю-201</div>
                    </a>

                    <div id="ce4" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-y-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['Ю201']) && !empty($group_files['Ю201'])): ?>
                                        <?php foreach ($group_files['Ю201'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: 2-I KYRS -->
    <!-- BEGIN: 3-I KYRS -->
    <div class="panel3" style="margin-bottom: 10px;" id = "panel3">
        <a class="accordion-toggle collapsed" data-toggle="collapse" href="#c3" style="font-size: 18px; color: #fff;">
            <div class="panel-heading" name="third-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">3-й курс</div>
        </a>

        <div id="c3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                <div class="panel" style="margin-bottom: 0px;" id = "panelt301" class ="t301">
                    <a class="collapsed" data-toggle="collapse" href="#crl" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-301, </div>
                    </a>

                    <div id="crl" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['T301']) && !empty($group_files['T301'])): ?>
                                        <?php foreach ($group_files['T301'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panele301" class ="e301">
                    <a class="collapsed" data-toggle="collapse" href="#cr2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-301</div>
                    </a>

                    <div id="cr2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['Э301']) && !empty($group_files['Э301'])): ?>
                                        <?php foreach ($group_files['Э301'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panelb301">
                    <a class="collapsed" data-toggle="collapse" href="#cr3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Б-301</div>
                    </a>

                    <div id="cr3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-b-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <?php if (isset($group_files['Б301']) && !empty($group_files['Б301'])): ?>
                                        <?php foreach ($group_files['Б301'] as $file): ?>
                                            <li><a href="<?php echo htmlspecialchars($file['path']); ?>" target="_blank"><?php echo htmlspecialchars($file['filename']); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li style="color: #999; font-style: italic;">Файлы для этой группы пока не загружены</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: 3-I KYRS -->
    <!-- BEGIN: KYRS LEKCII -->
    <div class="panel" style="margin-bottom: 10px;" id = "lekcii">
        <a class="accordion-toggle collapsed" data-toggle="collapse" href="#c4" style="font-size: 18px; color: #ffffff;">
            <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">Текст лекций</div>
        </a>

        <div id="c4" class="panel-collapse collapse" name="lectures" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                <div class="spiski" name = 'spiski'>
                    <ul style="padding: 0; line-height: 30px;">
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END: KYRS LEKCII -->
</div>

<script type="text/javascript">
        var delay_popup = 0;
        setTimeout("document.getElementById('overlay').style.display='block'", delay_popup);
        
function bubs(){
    var pop = document.getElementById("pop");
        pop.style.display = "none"; 
    var panel2 = document.getElementById("panel2");
        panel2.style.display = "inline";
    var panel3 = document.getElementById("panel3");
        panel3.style.display = "inline";
}

</script>

<?php echo $footer ?>
