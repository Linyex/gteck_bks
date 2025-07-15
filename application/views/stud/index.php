<h1><?php echo $title; ?></h1>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Доступные разделы</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="well">
                                <h4><i class="fa fa-file-text"></i> Контрольные работы</h4>
                                <p>Просмотр и скачивание домашних контрольных работ</p>
                                <a href="/stud/kontrolnui" class="btn btn-primary">Перейти</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well">
                                <h4><i class="fa fa-upload"></i> Добавить работу</h4>
                                <p>Загрузка новой контрольной работы</p>
                                <a href="/stud/add_kontrolnui" class="btn btn-success">Добавить</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="well">
                                <h4><i class="fa fa-cog"></i> Администрирование</h4>
                                <p>Управление контрольными работами</p>
                                <a href="/stud/admin_upload" class="btn btn-info">Управление</a>
                            </div>
                        </div>
                    </div>
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

.well {
    min-height: 140px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
}

.well h4 {
    margin-top: 0;
    color: #333;
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

.btn-success {
    background-color: #5cb85c;
    border-color: #4cae4c;
    color: white;
}

.btn-success:hover {
    background-color: #449d44;
    border-color: #398439;
}

.btn-info {
    background-color: #5bc0de;
    border-color: #46b8da;
    color: white;
}

.btn-info:hover {
    background-color: #31b0d5;
    border-color: #269abc;
}

.row {
    margin-left: -15px;
    margin-right: -15px;
}

.col-md-4, .col-md-12 {
    position: relative;
    min-height: 1px;
    padding-left: 15px;
    padding-right: 15px;
}

@media (min-width: 768px) {
    .col-md-4 {
        float: left;
        width: 33.33333333%;
    }
    .col-md-12 {
        float: left;
        width: 100%;
    }
}
</style> 