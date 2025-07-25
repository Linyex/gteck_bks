<?php echo $header ?> 
<h2>Отделение дополнительного образования и повышения квалификации</h2>
<hr>  
<div style="text-align: center;">
    <p><span class="c-font-red-1"><strong>Дополнительное образование взрослых</strong></span></p>
</div><br>
<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
    <tbody class="style-table1">
        <tr style="text-align: center;">
            <td colspan="2">Учебный процесс организует заведующий отделением</td>
        </tr>
        <tr style="text-align: center;">
            <td>ФИО Зав. Отделением</td>
            <td>Кабинет, телефон</td>
        </tr>
        <tr style="text-align: center;">
            <td>Яроцкая Наталья Александровна</td>
            <td>302<br>8 (0232) 29-37-39</td>
        </tr>
        <tr style="text-align: center;">
            <td colspan="2">Время работы</td>
        </tr>
        <tr style="text-align: center;">
            <td colspan="2">ПН-ЧТ 8.00-17.00 ПТ 8.00-16.10 ~ Обед 12.30-13.20</td>
        </tr>
    </tbody>
</table><br>
<span>
    <p>Дополнительное образование взрослых – вид дополнительного образования, направленный на профессиональное развитие слушателя и удовлетворение его познавательных потребностей. <br>Дополнительное образование взрослых в колледже осуществляется по следующим образовательным программам:</p>
    <ul>
        <li>повышение квалификации рабочих (служащих);</li>
        <li>профессиональная подготовка рабочих (служащих);</li>
        <li>обучающие курсы.</li>
    </ul>
    <p>Образовательные программы реализуются в очной форме получения образования.<br>Занятия организуются в течение учебного года по мере комплектования групп.<br>По окончании обучения слушателю выдается документ государственного образца согласно Кодексу Республики Беларусь</p>
</span><br>
<div class="spiski">
    <ul style="line-height: 30px; padding-left: 10px;">
        <?php foreach($filesa as $item): ?>
            <?php if ($item['files_ekzamen'] == (int)1): ?>
                <li><a href="/<?php echo $item['files_file'] ?>" target="_blank"><?php echo $item['files_text'] ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if($empty1 == (int)0): ?>
            <li style="text-align: center;">На данный момент нет дополнительной информации</li>
        <?php endif; ?><br>
        <li><h3>Обучающие курсы</h3></li>
        <li><a href="/assets/dopfiles/Курсы английского.doc" target="_blank" download>Курсы английского языка</a></li>
        <li><a href="/assets/dopfiles/Бизнес-курсы.doc" target="_blank" download>Бизнес - курсы</a></li>
        <li><a href="/assets/dopfiles/Выездные семинары.doc" target="_blank" download>Выездные семинары для организаций всех форм собственности (в том числе для организаций системы потребительской кооперации)</a></li>
        <br>
        <li><a href="/assets/dopfiles/Регламент обучения.doc" target="_blank" download>Регламент проведения обучения</a></li>
        <li><a href="/assets/dopfiles/Договор об услугах.doc" target="_blank" download>Договор о платных услугах в сфере образования</a></li>
        <li><a href="/assets/dopfiles/Карточка слушателя.doc" target="_blank" download>Учетная карточка слушателя</a></li>
    </ul>
</div><br>
<?php echo $footer ?>  