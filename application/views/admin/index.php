<?php echo $header; ?>

<div class="c-layout-page" style="color: #464646;">
    <div class="c-content-box c-size-md">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Панель администратора</h1>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Новости</h3>
                                </div>
                                <div class="panel-body">
                                    <p>Управление новостями</p>
                                    <a href="/admin/news" class="btn btn-primary">Управление новостями</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Файлы</h3>
                                </div>
                                <div class="panel-body">
                                    <p>Управление файлами</p>
                                    <a href="/admin/files" class="btn btn-success">Управление файлами</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Пользователи</h3>
                                </div>
                                <div class="panel-body">
                                    <p>Управление пользователями</p>
                                    <a href="/admin/users" class="btn btn-info">Управление пользователями</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Настройки</h3>
                                </div>
                                <div class="panel-body">
                                    <p>Настройки сайта</p>
                                    <a href="/admin/settings" class="btn btn-warning">Настройки</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Статистика</h3>
                                </div>
                                <div class="panel-body">
                                    <p>Статистика сайта</p>
                                    <a href="/admin/stats" class="btn btn-default">Статистика</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Выход</h3>
                                </div>
                                <div class="panel-body">
                                    <p>Выйти из системы</p>
                                    <a href="/admin/logout" class="btn btn-danger">Выйти</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?> 