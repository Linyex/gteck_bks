
<div class="c-layout-page" style="color: #464646;">
    <div class="c-content-box c-size-md">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Новости - <?php echo htmlspecialchars($category['name']); ?></h1>
                    
                    <?php if (!empty($news)): ?>
                        <div class="row">
                            <?php foreach ($news as $item): ?>
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-muted"><?php echo date('d.m.Y', strtotime($item['date'])); ?></p>
                                            <p><?php echo htmlspecialchars(substr($item['content'], 0, 200)) . '...'; ?></p>
                                            <a href="/news/view/<?php echo $item['id']; ?>" class="btn btn-primary">Читать далее</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <p>В данной категории новости не найдены.</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="category-navigation" style="margin-top: 30px;">
                        <a href="/news" class="btn btn-primary">← Назад к списку новостей</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
