<?php echo $header ?>
<h2>Редактирование страниц "О колледже"</h2>
<hr>
<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
    <tbody style="cursor: pointer;" class="style-table">
        <tr style="font-weight: 400; text-align: center;">
            <td>Наименование страницы</td>
            <td>Размер страницы</td>
            <td>Дата последнего редактирования</td>
        </tr>
        <?php foreach($pages as $item): ?>	
        <tr style="cursor: pointer" onClick="redirect('/admin/pages/kol/edit/mane/<?php echo $item['filename'] ?>')">
            <td><?php echo $item['filename'] ?></td>						  
            <td><?php echo $item['size'] ?> КБ</td>						
            <td style="text-align: center;"><?php echo $item['rewrite'] ?></td>			
        </tr>
        <?php endforeach; ?>  
        <?php if(!$pages): ?>
            <td colspan="3" style="text-align: center;">Статических страниц не существует</td>
        <?php endif; ?> 
    </tbody>
</table>

<?php echo $footer ?>
