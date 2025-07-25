<?php echo $header ?> 
<h2>Заочная форма получения образования</h2>
<hr>  
<div style="text-align: center;">
    <p><span class="c-font-red-1"><strong>Контингент учащихся заочной формы обучения на 01.09.2024 составляет 206 человек.</strong></span></p>
</div>

<div class="otdel-photo">
<div style="padding-top: 10px; padding-bottom: 20px; display: table; width: 100%;">
    <div class="photos">
        
        <div class="works">
            <div class="c-content-overlay">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                        <a href="/assets/media/otdel/6.jpg" data-fancybox="gallery">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/assets/media/otdel/6.jpg');"></div>
            </div>
        </div>
        <div class="works">
            <div class="c-content-overlay">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                        <a href="/assets/media/otdel/7.jpg" data-fancybox="gallery">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/assets/media/otdel/7.jpg');"></div>
            </div>
        </div>
        <div class="works">
            <div class="c-content-overlay">
                <div class="c-overlay-wrapper">
                    <div class="c-overlay-content">
                        <a href="/assets/media/otdel/4.jpg" data-fancybox="gallery">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="works-ico c-overlay-object img-responsive" style="background-image: url('/assets/media/otdel/4.jpg');"></div>
            </div>
        </div>
            
    </div>
</div>
</div>

<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
    <tbody class="style-table1">
        <tr style="text-align: center;">
            <td colspan="3">Учебно-воспитательный процесс организуют:</td>
        </tr>
        <tr style="text-align: center;">
            <td>ФИО Зав. Отделением</td>
            <td>Кабинет, телефон</td>
            <td>Группы</td>
        </tr>
        <tr style="text-align: center;">
            <td>Яроцкая Наталья Александровна
<br>Время работы:<br>
                ПН-ЧТ 8.00-17.00 ПТ 8.00-16.10<br>Обед 12.30-13.20
            </td>
            <td>302<br>8 (0232) 29-37-39</td>
            <td>
                <p>Э-101, Э-201, Э-301</p>
                <p>Б-201, Б-301</p>
                <p>Т-101, Т-111, Т-201, Т-211, Т-301</p>
                <p>Ю-101, Ю-201</p>
            </td>
        </tr>
    <!-- заоч резерв сюда -->
        </tr>
    </tbody>
</table>

<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
    <tbody class="style-table1">
        <tr style="text-align: center;">
            <td colspan="3">Методист</td>
        </tr>
        <tr style="text-align: center;">
            <td>ФИО Методиста</td>
            <td>Кабинет, телефон</td>
            <td>Группы</td>
        </tr>
        <tr style="text-align: center;">
            <td>Рычкова-Михальченко Валентина Константиновна
<br>Время работы:
                <br>
                ПН,ПТ 9.00-13.00 <br> ВТ,СР,ЧТ 13.00-17.00 
            </td>
            <td>110а<br>8 (0232) 20-22-14</td>
            <td>
                <p>Э-101, Э-201, Э-301</p>
                <p>Б-201, Б-301</p>
                <p>Т-101, Т-111, Т-201, Т-211, Т-301</p>
                <p>Ю-101, Ю-201</p>
            </td>
        </tr>
    <!-- заоч резерв сюда -->
        </tr>
    </tbody>
</table>

<h3>Работа отделения осуществляется по плану, утвержденному директором колледжа.</h3><br>
<div class="spiski">
    <ul style="line-height: 30px; padding-left: 10px;">
        <li><a href="/stud/kontrolnui" target="_blank"><b style="color: #1cc109; text-transform: uppercase; font-size: 13px;">Список контрольных работ</b></a></li>
        <li><a href="/stud/ymk" target="_blank"><b style="color: #1cc109; text-transform: uppercase; font-size: 13px;">Список УМК</b></a></li>
        <?php if($status1['statusb_code'] == (int)2): ?>
            <?php foreach($filesa as $item): ?>
                <?php if ($item['files_ekzamen'] == (int)2): ?>
                    <li><a href="/<?php echo $item['files_file'] ?>" target="_blank"><?php echo $item['files_text'] ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if($empty1 == (int)0): ?>
                <li style="text-align: center;">На данный момент нет экзаменов / контрольных работ</li>
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
        <li><a href="/assets/files/Положение об учреждении ССО.pdf" target="_blank"><b class="c-font-red-3">Положение</b> об учреждении среднего специального образования</a></li>
        <li><a href="/assets/files/Положение о порядке и условиях отчисления для перевода, перевода учащихся и восстановления лиц, для продолжения получения образования.pdf" target="_blank"><b class="c-font-red-3">Положение</b> о порядке перевода, восстановления и отчисления учащихся, получающих среднее специальное образование</a></li>
        <li><a href="/assets/files/Правила аттестации 2022.docx" target="_blank"><b class="c-font-red-3">Правила</b> проведения аттестации учащихся, курсантов при освоении содержания образовательных программ среднего специального образования</a></li><br>

    </ul>
</div><br>
<?php echo $footer ?>