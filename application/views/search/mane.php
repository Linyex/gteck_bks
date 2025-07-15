<?php echo $header; ?>

<div class="c-layout-page" style="color: #464646;">
    <div class="c-content-box c-size-md">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Поиск по сайту</h1>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Поиск</h3>
                        </div>
                        <div class="panel-body">
                            <form method="GET" action="/search">
                                <div class="form-group">
                                    <label for="query">Введите поисковый запрос:</label>
                                    <input type="text" class="form-control" id="query" name="query" 
                                           value="<?php echo htmlspecialchars($query ?? ''); ?>" 
                                           placeholder="Введите текст для поиска...">
                                </div>
                                <button type="submit" class="btn btn-primary">Найти</button>
                            </form>
                        </div>
                    </div>
                    
                    <?php if (!empty($query)): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Результаты поиска для "<?php echo htmlspecialchars($query); ?>"</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (!empty($results)): ?>
                                    <div class="search-results">
                                        <?php foreach ($results as $result): ?>
                                            <div class="search-item" style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                                                <h4><a href="<?php echo htmlspecialchars($result['url']); ?>"><?php echo htmlspecialchars($result['title']); ?></a></h4>
                                                <p class="text-muted"><?php echo htmlspecialchars($result['url']); ?></p>
                                                <p><?php echo htmlspecialchars($result['snippet']); ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <p>По вашему запросу ничего не найдено. Попробуйте изменить поисковый запрос.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>
