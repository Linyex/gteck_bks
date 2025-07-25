<?php echo $header ?>
<?php if (!empty($error)): ?>
    <?php echo $error ?>
<?php endif; ?>
<div class="col-md-12" style="padding: 0px; display: inline-block; width: 100%;">
    <h2>Список новостей</h2>
    <hr>
    <div class="info-text">
        <div class="floats clearfix">
            <div id="dle-content">
                <div style="margin-bottom: 15px; width: 100%; display: table;">
                    <a class="btn-success my-btn" style="padding: 5px; width: 100%; display: table; text-align: center;" href="/admin/news/create">Добавить новую новость</a>
                </div>
                <table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border: 0; word-break: break-word; height: auto;" class="tablica-spec table table-striped table-advance table-hover">
                    <thead class="bg-primary">
                        <tr style="font-weight: 400;">
                            <td style="width: 40px;">ID</td>
                            <td>Заголовок</td>
                            <td style="width: 150px;" class="hidden-xs">Дата добавления</td>
                            <td style="width: 100px;">Действие</td>
                        </tr>
                    </thead>
                    <tbody style="cursor: pointer;" class="style-table">
                        <?php foreach($news as $item): ?>
                        <tr onClick="redirect('/admin/news/edit/mane/<?php echo $item['news_id'] ?>')">
                            <td style="min-width: 75px;"><?php echo $item['news_id'] ?></td>
                            <td><?php echo $item['news_title'] ?></td>
                            <td class="hidden-xs"><?php echo date("d.m", strtotime($item['news_date_add'])) ?>.<?php echo date("Y", strtotime($item['news_date_add'])) ?></td>
                            <td>
                                <form action="/admin/news/delete/<?php echo $item['news_id'] ?>">
                                    <button class="btn-danger my-btn" style="font-size: 14px; width: 100%; border: 0; padding: 5px;">Удалить</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($news)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">На данный момент нет новостей</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div style="padding: 0px; display: table; width: 100%; text-align: center;">
    <?php echo $pagination ?>
</div>
<?php echo $footer ?>
