
<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/engine/main/db.php';

// Получаем все группы
try {
    $stmt = $pdo->query("SELECT DISTINCT groupname FROM groups ORDER BY groupname");
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $groups = [];
}

// Получаем файлы по каждой группе
$group_files = [];
foreach ($groups as $group) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM dkrfiles WHERE groupname = ? ORDER BY upload_date DESC");
        $stmt->execute([$group['groupname']]);
        $group_files[$group['groupname']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $group_files[$group['groupname']] = [];
    }
}
?>

<!-- Hero Section для контрольных работ -->
<section class="kontrol-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title">📝 Контрольные работы</h1>
                    <p class="hero-subtitle">Скачайте домашние контрольные работы по всем предметам и специальностям</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($groups); ?></span>
                            <span class="stat-label">Групп</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo array_sum(array_map('count', $group_files)); ?></span>
                            <span class="stat-label">Файлов</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Доступность</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Основной контент -->
<div class="c-layout-page">
    <div class="container">
        <!-- Навигация по группам -->
        <div class="content-section" data-aos="fade-up">
            <h2 class="section-title">🎯 Выберите специальность</h2>
            <div class="specialty-tabs">
                <button class="tab-btn active" onclick="showTab('all')">
                    <i class="fa fa-graduation-cap"></i>
                    Все специальности
                </button>
                <button class="tab-btn" onclick="showTab('bux')">
                    <i class="fa fa-calculator"></i>
                    Бухгалтеры
                </button>
                <button class="tab-btn" onclick="showTab('econ')">
                    <i class="fa fa-chart-line"></i>
                    Экономисты
                </button>
                <button class="tab-btn" onclick="showTab('torg')">
                    <i class="fa fa-shopping-cart"></i>
                    Товароведы
                </button>
                <button class="tab-btn" onclick="showTab('prog')">
                    <i class="fa fa-code"></i>
                    Программисты
                </button>
            </div>
        </div>

        <!-- Файлы по группам -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="200">
            <h2 class="section-title">📁 Файлы контрольных работ</h2>

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
    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 9999;
        display: none;
    }
    .popup {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .inpass {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 10px;
        width: 200px;
    }
    .btnpass, .btnkyrc {
        padding: 10px 20px;
        margin: 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .btnpass {
        background: #007bff;
        color: white;
    }
    .btnkyrc {
        background: #28a745;
        color: white;
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
            <!-- Современный вывод файлов по группам -->
            <div class="files-grid">
                <?php foreach ($groups as $group): ?>
                    <?php 
                        $groupName = $group['groupname'];
                        $files = isset($group_files[$groupName]) ? $group_files[$groupName] : [];
                        
                        // Определяем иконку специальности
                        $specialty_icon = '🎓';
                        if (strpos($groupName, 'Б') !== false || strpos($groupName, 'Э') !== false) {
                            $specialty_icon = '📊';
                        } elseif (strpos($groupName, 'Т') !== false) {
                            $specialty_icon = '🛒';
                        } elseif (strpos($groupName, 'П') !== false) {
                            $specialty_icon = '💻';
                        }
                    ?>
                    <div class="group-card" data-aos="fade-up" data-aos-delay="<?php echo array_search($group, $groups) * 100; ?>">
                        <div class="group-header">
                            <div class="group-icon"><?php echo $specialty_icon; ?></div>
                            <div>
                                <h3 class="group-title">Группа <?php echo htmlspecialchars($groupName); ?></h3>
                                <p class="group-subtitle"><?php echo count($files); ?> файлов</p>
                            </div>
                        </div>
                        
                        <?php if (!empty($files)): ?>
                            <ul class="files-list">
                                <?php foreach ($files as $file): ?>
                                    <li class="file-item">
                                        <i class="fa fa-file-pdf file-icon"></i>
                                        <a href="<?php echo htmlspecialchars($file['path']); ?>" 
                                           target="_blank" 
                                           class="file-link">
                                            <?php echo htmlspecialchars($file['filename']); ?>
                                        </a>
                                        <span class="file-size">
                                            <?php 
                                                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $file['path'])) {
                                                    $size = filesize($_SERVER['DOCUMENT_ROOT'] . $file['path']);
                                                    echo $size > 1024 * 1024 ? round($size / (1024 * 1024), 1) . ' MB' : round($size / 1024, 1) . ' KB';
                                                }
                                            ?>
                                        </span>
                                        <button onclick="window.open('<?php echo htmlspecialchars($file['path']); ?>', '_blank')" 
                                                class="download-btn-small">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="no-files">
                                <i class="fa fa-folder-open" style="font-size: 2rem; color: #D1D5DB; margin-bottom: 10px;"></i>
                                <p style="color: #6B7280; font-style: italic;">Файлы для этой группы пока не загружены</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// Современная функция для работы с вкладками
function showTab(specialty) {
    const buttons = document.querySelectorAll('.tab-btn');
    const cards = document.querySelectorAll('.group-card');
    
    // Убираем активное состояние со всех кнопок
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Добавляем активное состояние нажатой кнопке
    event.target.classList.add('active');
    
    // Показываем/скрываем карточки в зависимости от специальности
    cards.forEach(card => {
        const groupTitle = card.querySelector('.group-title').textContent;
        let show = specialty === 'all';
        
        if (!show) {
            switch(specialty) {
                case 'bux':
                    show = groupTitle.includes('Б') || groupTitle.includes('Э');
                    break;
                case 'econ':
                    show = groupTitle.includes('Э');
                    break;
                case 'torg':
                    show = groupTitle.includes('Т');
                    break;
                case 'prog':
                    show = groupTitle.includes('П');
                    break;
            }
        }
        
        card.style.display = show ? 'block' : 'none';
    });
}

// Удаляем старые функции
/*
        var delay_popup = 0;
        setTimeout("document.getElementById('overlay').style.display='block'", delay_popup);
        
function bubs(){
    var pop = document.getElementById("pop");
        pop.style.display = "none"; 
    var panel2 = document.getElementById("panel2");
        panel2.style.display = "inline";
    var panel3 = document.getElementById("panel3");
        panel3.style.display = "inline";
*/

// Удаляем старый код - все файлы теперь доступны без паролей
/*
function bubs(){
    // Старая функция - удалена
}

function PassCheck(form) {
    // Старая функция проверки паролей - удалена
    // Теперь все файлы доступны без паролей
}
*/
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panele101").style.display = "inline";
        return false;
    }
    else if(password == "ю101") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panely101").style.display = "inline";
        return false;
    }
    else if(password == "211") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panelt211").style.display = "inline";
        return false;
    }
    else if(password == "201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panelt201").style.display = "inline";
        return false;
    }
    else if(password == "э201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panele201").style.display = "inline";
        return false;
    }
    else if(password == "ю201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panely201").style.display = "inline";
        return false;
    }
    else if(password == "301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panelt301").style.display = "inline";
        return false;
    }
    else if(password == "э301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panele301").style.display = "inline";
        return false;
    }
    else if(password == "б301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panelb301").style.display = "inline";
        return false;
    }
    else {
        alert("Неверный пароль!");
        return false;
    }
}
</script>

