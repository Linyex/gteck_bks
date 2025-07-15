<?php echo $header ?> 
<h2>Дневное отделение</h2>
<hr>

<div style="text-align: center;">
    <p><span class="c-font-red-1"><strong>Контингент учащихся дневной формы обучения на 01.09.2024 - 737 человек.</strong></span></p>
</div>
<div class="otdel-photo">
<div style="padding-top: 10px; padding-bottom: 20px; display: table; width: 100%;">
    <div class="photos">
        
        <div class="works">
            <div class="c-content-overlay">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                        <a href="/assets/media/otdel/3.jpg" data-fancybox="gallery">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/assets/media/otdel/3.jpg');"></div>
            </div>
        </div>
        <div class="works">
            <div class="c-content-overlay">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                        <a href="/assets/media/otdel/2.jpg" data-fancybox="gallery">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/assets/media/otdel/2.jpg');"></div>
            </div>
        </div>
        <div class="works">
            <div class="c-content-overlay">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                        <a href="/assets/media/otdel/1.jpg" data-fancybox="gallery">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/assets/media/otdel/1.jpg');"></div>
            </div>
        </div>
            
    </div>
</div>
</div>

<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
    <tbody class="style-table1">
        <tr style="text-align: center;">
            <td colspan="3">Учебно-воспитательный процесс организуют заведующие отделениями</td>
        </tr>
        <tr style="text-align: center;">
            <td>ФИО зав. отделением</td>
            <td>Наименования отделения</td>
            <td>Кабинет, телефон</td>
            <td>Группы</td>
        </tr>
        <tr style="text-align: center;">
            <td>Дегтярева<br>
            Ольга Федоровна</td>
            <td>Дневное отделение информационных <br>
            технологий</td>
            <td>311<br> 8 (0232) 33-70-05</td>
            <td>
                <p>Г-41c, П-11, П-12, П-21</p>
                <p>П-22, П-23c, П-31, П-32, П-33c</p>
                <p>П-41, П-42, П-43c</p>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td>Кравцова<br>
            Елена Анатольевна</td>
            <td>Дневное отделение экономики<br>
            и коммерции</td>
            <td>304<br> 8 (0232) 33-70-12</td>
            <td>
                <p>Б-11, Б-21, Б-31,</p>
                <p>Э-11, Э-21, Э-31,</p>
                <p>Т-11, Т-21, Т-31, Т-32с</p>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td>Яроцкая<br>
            Наталья Александровна</td>
            <td>Юридическое отделение</td>
            <td>302<br> 8 (0232) 29-37-39</td>
            <td>
                <p>Ю-11, Ю-12, Ю-21</p>
                <p>Ю-22, Ю-31, Ю-32</p>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td colspan="3">Время работы</td>
        </tr>
        <tr style="text-align: center;">
            <td colspan="3">ПН-ЧТ 8.00-17.00 ПТ 8.00-16.10 ~ Обед 12.30-13.20</td>
        </tr>
    </tbody>
</table>
<span>
    <h3 class="c-font-yellow-2">Заведующие отделением:</h3>
    <ul>
        <li>организуют и осуществляют непосредственное руководство учебной и воспитательной - работой на отделениях;</li>
        <li>обеспечивают выполнение учебных планов</li>
        <li>готовят материалы по составлению расписания учебных занятий и контролируют его выполнение;</li>
        <li>организуют учет успеваемости учащихся;</li>
        <li>осуществляют контроль за посещаемостью занятий и дисциплиной учащихся за выполнением учащимся курсовых и дипломных работ (проектов);</li>
        <li>готовят материалы для назначения стипендии, для проведения государственных квалификационных экзаменов.</li>
    </ul>
    <p>Работа отделений осуществляется по плану, утвержденному директором колледжа.</p>
</span><br>
<div class="spiski">
    <ul style="line-height: 30px; padding-left: 10px;">
        <?php if($status1['statusb_code'] == (int)2): ?>
            <?php foreach($filesa as $item): ?>
                <?php if ($item['files_ekzamen'] == (int)2): ?>
                    <li><a href="/<?php echo $item['files_file'] ?>" target="_blank"><?php echo $item['files_text'] ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if($empty1 == (int)0): ?>
                <li style="text-align: center;">На данный момент нет экзаменов</li>
            <?php endif; ?>
            <br>
        <?php endif; ?>
        <?php foreach($filesa as $item): ?>
            <?php if ($item['files_ekzamen'] == (int)1): ?>
                <li><a href="/<?php echo $item['files_file'] ?>" target="_blank"><?php echo $item['files_text'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if($empty2 == (int)0): ?>
            <li style="text-align: center;">На данный момент нет дополнительной информации</li>
        <?php endif; ?>
        <br>
        <?php if (!empty($lastzamena['zamena_file'])): ?>
            <li><a href="/<?php echo ($lastzamena['zamena_file']) ?>">Изменения в расписании <?php echo $lastzamena['zamena_text'] ?></a></li>
        <?php else: ?>
            <p style="text-align: center;">На данный момент нет изменений в расписании</p>
        <?php endif; ?>
        <br>
        <li><a href="/assets/files/положения ССО -2022.docx" target="_blank"><b class="c-font-red-3">Положение</b> об учреждении среднего специального образования</a></li>
        <li><a href="/assets/files/Положение Об организации выписки документов об образовании.doc" target="_blank"><b class="c-font-red-3">Положение</b> об организации выписки документов об образовании</a></li>
        <li><a href="/assets/files/Правила аттестации 2022.docx" target="_blank"><b class="c-font-red-3">Правила</b> проведения аттестации учащихся, курсантов при освоении содержания образовательных программ среднего специального образования</a></li><br>
    </ul>
</div><br>
<?php echo $footer ?>