<?php
/**
 * Страница отчетов мониторинга
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Отчеты мониторинга</h1>
                <div class="page-actions">
                    <button class="btn btn-primary" onclick="generateReport()">
                        <i class="fas fa-file-alt"></i> Создать отчет
                    </button>
                    <button class="btn btn-secondary" onclick="exportReport()">
                        <i class="fas fa-download"></i> Экспорт
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Отчеты безопасности</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Тип отчета</th>
                                    <th>Период</th>
                                    <th>Статус</th>
                                    <th>Создан</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody id="reportsTable">
                                <tr>
                                    <td colspan="6" class="text-center">Отчеты не найдены</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Быстрые отчеты</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action" onclick="generateQuickReport('daily')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Ежедневный отчет</h6>
                                <small>За последние 24 часа</small>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="generateQuickReport('weekly')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Еженедельный отчет</h6>
                                <small>За последние 7 дней</small>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="generateQuickReport('monthly')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Ежемесячный отчет</h6>
                                <small>За последние 30 дней</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Настройки отчетов</h5>
                </div>
                <div class="card-body">
                    <form id="reportSettingsForm">
                        <div class="form-group">
                            <label for="reportType">Тип отчета</label>
                            <select class="form-control" id="reportType">
                                <option value="security">Безопасность</option>
                                <option value="activity">Активность</option>
                                <option value="performance">Производительность</option>
                                <option value="errors">Ошибки</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reportPeriod">Период</label>
                            <select class="form-control" id="reportPeriod">
                                <option value="1">1 день</option>
                                <option value="7">7 дней</option>
                                <option value="30">30 дней</option>
                                <option value="90">90 дней</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reportFormat">Формат</label>
                            <select class="form-control" id="reportFormat">
                                <option value="html">HTML</option>
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                                <option value="json">JSON</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Создать отчет</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateReport() {
    // Логика создания отчета
    console.log('Создание отчета...');
}

function exportReport() {
    // Логика экспорта отчета
    console.log('Экспорт отчета...');
}

function generateQuickReport(period) {
    // Логика создания быстрого отчета
    console.log('Создание быстрого отчета:', period);
}

document.getElementById('reportSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const type = document.getElementById('reportType').value;
    const period = document.getElementById('reportPeriod').value;
    const format = document.getElementById('reportFormat').value;
    
    console.log('Создание отчета:', { type, period, format });
});
</script> 