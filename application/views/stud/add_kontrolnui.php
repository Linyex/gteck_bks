<h1><?php echo $title; ?></h1>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Добавить новую контрольную работу</h3>
                </div>
                <div class="panel-body">
                    <form action="/stud/add_kontrolnui" method="post" enctype="multipart/form-data">
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
                            <label for="file">Файл контрольной работы:</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                            <small class="text-muted">Поддерживаемые форматы: PDF, DOC, DOCX, TXT</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Добавить контрольную работу</button>
                            <a href="/stud/kontrolnui" class="btn btn-default">Назад к списку</a>
                        </div>
                    </form>
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
    margin-right: 10px;
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

.btn-default {
    background-color: #fff;
    border-color: #ccc;
    color: #333;
}

.btn-default:hover {
    background-color: #e6e6e6;
    border-color: #adadad;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
}

.text-muted {
    color: #777;
    font-size: 12px;
}

.row {
    margin-left: -15px;
    margin-right: -15px;
}

.col-md-8, .col-md-offset-2 {
    position: relative;
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
}

@media (min-width: 768px) {
    .col-md-8 {
        float: left;
        width: 66.66666667%;
    }
    .col-md-offset-2 {
        margin-left: 16.66666667%;
    }
}
</style> 