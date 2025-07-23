
<h1><?php echo $title; ?></h1>

<div class="container">
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Загрузить новую контрольную работу</h3>
                </div>
                <div class="panel-body">
                    <form action="/stud/admin_upload" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload">
                        
                        <div class="form-group">
                            <label for="group_id">Группа:</label>
                            <select name="group_id" id="group_id" class="form-control" required>
                                <option value="">Выберите группу</option>
                                <?php foreach ($groups as $group): ?>
                                    <option value="<?php echo $group['id_group']; ?>">
                                        <?php echo htmlspecialchars($group['groupname']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="file">Файл:</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Загрузить</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Статистика</h3>
                </div>
                <div class="panel-body">
                    <p><strong>Всего файлов:</strong> <?php echo count($files); ?></p>
                    <p><strong>Всего групп:</strong> <?php echo count($groups); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Загруженные файлы</h3>
                </div>
                <div class="panel-body">
                    <?php if (empty($files)): ?>
                        <p class="text-muted">Файлы не найдены.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Группа</th>
                                        <th>Файл</th>
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
                                                   target="_blank">
                                                    <?php echo htmlspecialchars($file['filename']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo date('d.m.Y H:i', strtotime($file['upload_date'])); ?>
                                            </td>
                                            <td>
                                                <form action="/stud/admin_upload" method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="file_id" value="<?php echo $file['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Удалить файл?')">
                                                        Удалить
                                                    </button>
                                                </form>
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

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.panel {
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.panel-heading {
    background-color: #f5f5f5;
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

.panel-title {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

.panel-body {
    padding: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
}

.form-control:focus {
    border-color: #66afe9;
    outline: 0;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
}

.btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    border-radius: 4px;
    text-decoration: none;
}

.btn-primary {
    background-color: #337ab7;
    border-color: #2e6da4;
    color: white;
}

.btn-primary:hover {
    background-color: #286090;
    border-color: #204d74;
}

.btn-danger {
    background-color: #d9534f;
    border-color: #d43f3a;
    color: white;
}

.btn-danger:hover {
    background-color: #c9302c;
    border-color: #ac2925;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f9f9f9;
    font-weight: bold;
}

.label {
    display: inline-block;
    padding: 3px 7px;
    font-size: 12px;
    font-weight: bold;
    border-radius: 3px;
}

.label-primary {
    background-color: #337ab7;
    color: white;
}

.text-muted {
    color: #777;
}

.row {
    margin-left: -15px;
    margin-right: -15px;
}

.col-md-6, .col-md-12 {
    position: relative;
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
}

@media (min-width: 768px) {
    .col-md-6 {
        float: left;
        width: 50%;
    }
    .col-md-12 {
        float: left;
        width: 100%;
    }
}
</style> 