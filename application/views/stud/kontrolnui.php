
<div class="c-layout-page" style="color: #464646;">
    <div class="c-content-box c-size-md">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Контрольные работы</h1>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Домашние контрольные работы</h3>
                        </div>
                        <div class="panel-body">
                            <?php if (empty($files)): ?>
                                <p class="text-muted">Контрольные работы не найдены.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Группа</th>
                                                <th>Название файла</th>
                                                <th>Дата загрузки</th>
                                                <th>Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($files as $file): ?>
                                                <tr>
                                                    <td>
                                                        <span class="label label-primary">
                                                            <?php echo htmlspecialchars($file['groupname'] ?? 'Не указана'); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo htmlspecialchars($file['path']); ?>" 
                                                           target="_blank" 
                                                           class="btn btn-link">
                                                            <i class="fa fa-download"></i>
                                                            <?php echo htmlspecialchars($file['filename']); ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?php echo date('d.m.Y H:i', strtotime($file['upload_date'])); ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo htmlspecialchars($file['path']); ?>" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-success">
                                                            <i class="fa fa-download"></i> Скачать
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

