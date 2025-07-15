<?php echo $header; ?>

<div class="c-layout-page" style="color: #464646;">
    <div class="c-content-box c-size-md">
        <div class="container">
            <div class="row">
    <div class="col-md-12">
                    <?php if (!empty($news)): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title"><?php echo htmlspecialchars($news['title']); ?></h1>
                                <p class="text-muted"><?php echo date('d.m.Y H:i', strtotime($news['date'])); ?></p>
                        </div>
                            <div class="panel-body">
                                <div class="news-content">
                                    <?php echo nl2br(htmlspecialchars($news['content'])); ?>
                    </div>
                                <div class="news-navigation" style="margin-top: 30px;">
                                    <a href="/news" class="btn btn-primary">← Назад к списку новостей</a>
            </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <h4>Новость не найдена</h4>
                            <p>Запрашиваемая новость не существует или была удалена.</p>
                            <a href="/news" class="btn btn-primary">Перейти к списку новостей</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>
