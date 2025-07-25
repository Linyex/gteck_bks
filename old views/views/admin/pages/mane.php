<?php echo $header ?>
<h2>Редактирование страниц</h2>
<hr>
<p>Выберите раздел, для редактирования статических страниц страниц</p>
<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
    <tbody class="style-table">
        <tr style="text-align: center;">
            <td>Наименование раздела</td>
            <td>Количество страниц</td>
        </tr>
        <tr>
            <td><div class="news-about"><a href="/admin/pages/abut" style="float: none;">Абитуриенту</a></div></td>
            <td style="text-align: center;"><?php echo $count1; ?></td>
        </tr>
        <tr>
            <td><div class="news-about"><a href="/admin/pages/stud" style="float: none;">Учащемуся</a></div></td>
            <td style="text-align: center;"><?php echo $count2; ?></td>
        </tr>
        <tr>
            <td><div class="news-about"><a href="/admin/pages/prepod" style="float: none;">Преподавателю</a></div></td>
            <td style="text-align: center;"><?php echo $count3; ?></td>
        </tr>
        <tr>
            <td><div class="news-about"><a href="/admin/pages/kol" style="float: none;">О колледже</a></div></td>
            <td style="text-align: center;"><?php echo $count4; ?></td>
        </tr>
        <tr>
            <td><div class="news-about"><a href="/admin/pages/okno" style="float: none;">Одно окно</a></div></td>
            <td style="text-align: center;"><?php echo $count5; ?></td>
        </tr>
        <tr >
            <td><div class="news-about"><a href="/admin/pages/dopage" style="float: none;">Дополнительные страницы</a></div></td>
            <td style="text-align: center;"><?php echo $count6; ?></td>
        </tr>
    </tbody>
</table>

<?php echo $footer ?>
