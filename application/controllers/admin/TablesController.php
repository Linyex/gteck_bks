<?php

require_once __DIR__ . '/BaseAdminController.php';
require_once ENGINE_DIR . 'main/db.php';
require_once APPLICATION_DIR . 'models/TableModel.php';

class TablesController extends BaseAdminController {
    public function index() {
        $this->requireAccessLevel(1);
        $model = new TableModel();
        $tables = $model->listAll();
        echo $this->render('admin/tables/index', [
            'title' => 'Таблицы (Excel-подобные)',
            'currentPage' => 'tables',
            'tables' => $tables
        ]);
    }

    public function templateAdmission() {
        $this->requireAccessLevel(1);
        echo $this->render('admin/tables/template_admission', [
            'title' => 'Шаблон таблицы приёма',
            'currentPage' => 'tables'
        ]);
    }

    public function createAdmissionTemplate() {
        $this->requireAccessLevel(1);
        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');
        $form = trim($_POST['form'] ?? 'дневная');
        $basis = trim($_POST['basis'] ?? 'общего базового образования (9 классов)');
        $specialty = trim($_POST['specialty'] ?? '');
        $plan = (int)($_POST['plan'] ?? 0);
        $slug = trim($_POST['slug'] ?? '');
        $title = trim($_POST['title'] ?? 'Приём: ' . $specialty);

        if ($slug === '') { $slug = 'admission-' . date('Ymd-His'); }

        // Описание как шапка
        $description = "Дата: {$date}\nВремя: {$time}\n\nПрием: на платной основе.\nФорма получения образования: {$form}\nПрием осуществляется на основе: {$basis}\n\nСпециальность: {$specialty}";

        // Колонки: Специальность | План приёма (всего) | 0..3,0 | 3,1 | ... | 10,0 | Подано заявлений, всего
        $columns = [];
        $columns[] = ['title' => 'Специальность'];
        $columns[] = ['title' => 'План приёма (всего)'];
        $labels = [];
        // от 0..3,0 как первая колонка диапазона
        $labels[] = '0..3,0';
        // 3,1 до 10,0 с шагом 0,1
        for ($i = 31; $i <= 100; $i++) {
            $int = intdiv($i, 10);
            $dec = $i % 10;
            $labels[] = $int . ',' . $dec;
        }
        foreach ($labels as $lab) { $columns[] = ['title' => $lab]; }
        $columns[] = ['title' => 'Подано заявлений, всего'];

        // Строки
        $colCount = count($columns);
        $zeroRow = array_fill(0, $colCount, 0);

        $row1 = $zeroRow; // Подано заявлений от абитуриентов
        $row1[0] = $specialty;
        $row1[1] = $plan;

        $row2 = $zeroRow; // имеющие льготы
        $row2[0] = 'имеющие льготы';

        $row3 = $zeroRow; // на испытание вне конкурса
        $row3[0] = 'вне конкурса';

        $rows = [ $row1, $row2, $row3 ];

        $model = new TableModel();
        $newId = $model->create($title, $slug, $description, $columns, $rows, true, $_SESSION['user_id'] ?? null);
        return $this->json(['success' => $newId > 0, 'id' => $newId, 'edit_url' => '/admin/tables/edit?id=' . $newId, 'public_url' => '/tables/' . urlencode($slug)]);
    }

    public function edit() {
        $this->requireAccessLevel(1);
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $model = new TableModel();
        $item = $id ? $model->getById($id) : null;
        echo $this->render('admin/tables/edit', [
            'title' => $id ? 'Редактирование таблицы' : 'Создание таблицы',
            'currentPage' => 'tables',
            'item' => $item
        ]);
    }

    public function saveMeta() {
        $this->requireAccessLevel(1);
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? 'Без названия');
        $slug = trim($_POST['slug'] ?? 'table-' . time());
        $description = trim($_POST['description'] ?? '');
        $isPublic = isset($_POST['is_public']);
        $model = new TableModel();
        if ($id) {
            $ok = $model->updateMeta($id, $title, $slug, $description, $isPublic, $_SESSION['user_id'] ?? null);
            return $this->json(['success' => (bool)$ok, 'id' => $id]);
        } else {
            $newId = $model->create($title, $slug, $description, [], [], $isPublic, $_SESSION['user_id'] ?? null);
            return $this->json(['success' => $newId > 0, 'id' => $newId]);
        }
    }

    public function saveData() {
        $this->requireAccessLevel(1);
        $id = (int)($_POST['id'] ?? 0);
        $columns = json_decode($_POST['columns'] ?? '[]', true) ?: [];
        $rows = json_decode($_POST['rows'] ?? '[]', true) ?: [];
        $model = new TableModel();
        $merges = json_decode($_POST['merges'] ?? '[]', true) ?: [];
        $ok = $model->saveData($id, $columns, $rows, $_SESSION['user_id'] ?? null, $merges);
        return $this->json(['success' => (bool)$ok]);
    }

    public function delete() {
        $this->requireAccessLevel(1);
        $id = (int)($_POST['id'] ?? 0);
        $ok = (new TableModel())->delete($id);
        return $this->json(['success' => (bool)$ok]);
    }

    // ===== Импорт/экспорт =====
    public function exportCsv() {
        $this->requireAccessLevel(1);
        $id = (int)($_GET['id'] ?? 0);
        $item = (new TableModel())->getById($id);
        if (!$item) { http_response_code(404); exit('Not found'); }
        $filename = ($item['slug'] ?? ('table-'.$id)).'.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $out = fopen('php://output', 'w');
        // Заголовки колонок
        $headers = array_map(fn($c)=>$c['title'] ?? '', $item['columns_json'] ?? []);
        if (!empty($headers)) fputcsv($out, $headers, ';');
        // Строки
        foreach (($item['rows_json'] ?? []) as $row) {
            $line = [];
            for ($i=0;$i<count($headers);$i++) { $line[] = $row[$i] ?? ''; }
            fputcsv($out, $line, ';');
        }
        fclose($out); exit;
    }

    public function exportXls() {
        $this->requireAccessLevel(1);
        $id = (int)($_GET['id'] ?? 0);
        $item = (new TableModel())->getById($id);
        if (!$item) { http_response_code(404); exit('Not found'); }
        $filename = ($item['slug'] ?? ('table-'.$id)).'.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        echo "<html><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><body><table border=1>";
        echo '<thead><tr>';
        foreach (($item['columns_json'] ?? []) as $c) { echo '<th>'.htmlspecialchars($c['title'] ?? '').'</th>'; }
        echo '</tr></thead><tbody>';
        $cols = count($item['columns_json'] ?? []);
        foreach (($item['rows_json'] ?? []) as $row) {
            echo '<tr>';
            for ($i=0;$i<$cols;$i++) { echo '<td>'.htmlspecialchars((string)($row[$i] ?? '')).'</td>'; }
            echo '</tr>';
        }
        echo '</tbody></table></body></html>';
        exit;
    }

    public function exportPdf() {
        $this->requireAccessLevel(1);
        $id = (int)($_GET['id'] ?? 0);
        $item = (new TableModel())->getById($id);
        if (!$item) { http_response_code(404); exit('Not found'); }
        require_once APPLICATION_DIR . 'lib/fpdf.php';
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        $pdf->MultiCell(0,8, iconv('UTF-8','CP1251//IGNORE', $item['title'] ?? 'Таблица'));
        if (!empty($item['description'])) {
            $pdf->SetFont('Arial','',9);
            $pdf->MultiCell(0,6, iconv('UTF-8','CP1251//IGNORE', $item['description']));
        }
        $pdf->Ln(2);
        $cols = count($item['columns_json'] ?? []);
        if ($cols === 0) { $pdf->Output('I', ($item['slug'] ?? 'table').'.pdf'); exit; }
        $cellW = max(20, min(40, (290 - 10) / $cols));
        $pdf->SetFont('Arial','B',7);
        foreach (($item['columns_json'] ?? []) as $c) {
            $pdf->Cell($cellW, 7, iconv('UTF-8','CP1251//IGNORE', mb_substr($c['title'] ?? '',0,20)), 1, 0, 'C');
        }
        $pdf->Ln();
        $pdf->SetFont('Arial','',7);
        foreach (($item['rows_json'] ?? []) as $row) {
            for ($i=0;$i<$cols;$i++) {
                $pdf->Cell($cellW, 6, iconv('UTF-8','CP1251//IGNORE', (string)($row[$i] ?? '')), 1, 0, 'C');
            }
            $pdf->Ln();
        }
        $pdf->Output('I', ($item['slug'] ?? 'table').'.pdf');
        exit;
    }

    public function importCsv() {
        $this->requireAccessLevel(1);
        $id = (int)($_POST['id'] ?? 0);
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            return $this->json(['success' => false, 'message' => 'Файл не загружен']);
        }
        $hasHeader = isset($_POST['has_header']);
        $tmp = $_FILES['file']['tmp_name'];
        $fh = fopen($tmp, 'r');
        if (!$fh) return $this->json(['success' => false, 'message' => 'Не удалось открыть файл']);
        $rows = [];
        while (($data = fgetcsv($fh, 0, ';')) !== false) {
            if (count($data) === 1) { // попробуем запятая
                $data = str_getcsv($data[0], ',');
            }
            $rows[] = $data;
        }
        fclose($fh);
        if (empty($rows)) return $this->json(['success' => false, 'message' => 'Пустой CSV']);
        $columns = [];
        if ($hasHeader) {
            $headers = array_shift($rows);
            foreach ($headers as $h) { $columns[] = ['title' => trim((string)$h)]; }
        } else {
            $max = max(array_map('count', $rows));
            for ($i=0;$i<$max;$i++) { $columns[] = ['title' => 'Колонка '.($i+1)]; }
        }
        // Нормализуем строки под количество колонок
        $colCount = count($columns);
        $normRows = [];
        foreach ($rows as $r) {
            $line = array_pad($r, $colCount, '');
            $normRows[] = $line;
        }
        $ok = (new TableModel())->saveData($id, $columns, $normRows, $_SESSION['user_id'] ?? null);
        return $this->json(['success' => (bool)$ok]);
    }

    public function importXlsxCreate() {
        $this->requireAccessLevel(1);
        // Глушим вывод ошибок в HTML для корректного JSON-ответа
        if (function_exists('ini_set')) { @ini_set('display_errors', '0'); }
        try {
            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                return $this->json(['success' => false, 'message' => 'Файл не загружен']);
            }
            $tmp = $_FILES['file']['tmp_name'];
            // 1) Пытаемся через PhpSpreadsheet
            $usedPhpSpreadsheet = false;
            $rows = [];
            $mergesList = [];
            if (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
                require_once __DIR__ . '/../../../vendor/autoload.php';
                if (class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory')) {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmp);
                    $sheet = $spreadsheet->getActiveSheet();
                    // Строим полную матрицу по ширине листа и разворачиваем объединённые ячейки
                    $highestRow = (int) $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    $maxColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                    $rows2 = [];
                    for ($r = 1; $r <= $highestRow; $r++) {
                        $line = [];
                        for ($c = 1; $c <= $maxColIndex; $c++) {
                            $cell = $sheet->getCellByColumnAndRow($c, $r);
                            $val = $cell ? $cell->getCalculatedValue() : '';
                            $line[] = is_null($val) ? '' : (string)$val;
                        }
                        $rows2[] = $line;
                    }
                    // Разворачиваем объединённые диапазоны, копируя значение верх-левого в пустые ячейки диапазона
                    $merges = $sheet->getMergeCells();
                    foreach ($merges as $range) {
                        $parts = explode(':', strtoupper($range));
                        if (count($parts) === 2) {
                            $start = $parts[0]; $end = $parts[1];
                            $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(preg_replace('/\d+/', '', $start));
                            $startRow = (int)preg_replace('/\D+/', '', $start);
                            $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(preg_replace('/\d+/', '', $end));
                            $endRow = (int)preg_replace('/\D+/', '', $end);
                            // сохраняем merge в 0-базированном виде для нашего движка
                            $mergesList[] = [
                                'r1' => $startRow - 1,
                                'c1' => $startCol - 1,
                                'r2' => $endRow - 1,
                                'c2' => $endCol - 1,
                            ];
                            $value = $rows2[$startRow-1][$startCol-1] ?? '';
                            for ($rr = $startRow; $rr <= $endRow; $rr++) {
                                for ($cc = $startCol; $cc <= $endCol; $cc++) {
                                    if (($rows2[$rr-1][$cc-1] ?? '') === '') {
                                        $rows2[$rr-1][$cc-1] = (string)$value;
                                    }
                                }
                            }
                        }
                    }
                    $rows = $rows2; // индексы 0..N
                    $usedPhpSpreadsheet = true;
                }
            }
            // 2) Фолбэк на простой парсер, если PhpSpreadsheet недоступен
            if (!$usedPhpSpreadsheet) {
                if (!class_exists('ZipArchive')) {
                    return $this->json(['success' => false, 'message' => 'Нужен PhpSpreadsheet (composer) или ZipArchive для fallback.']);
                }
                require_once APPLICATION_DIR . 'lib/SimpleXLSX.php';
                $xlsx = SimpleXLSX::parse($tmp);
                if (!$xlsx) return $this->json(['success' => false, 'message' => 'Не удалось прочитать XLSX']);
                $rows = $xlsx->rows(0);
                $mergesList = [];
            }
            if (empty($rows)) return $this->json(['success' => false, 'message' => 'Пустой XLSX']);
            // Сохраняем макет как в Excel: нормализуем все строки до максимального числа колонок
            $maxCols = 0;
            foreach ($rows as $r) { if (is_array($r)) { $maxCols = max($maxCols, count($r)); } }
            if ($maxCols === 0) return $this->json(['success' => false, 'message' => 'В файле нет колонок']);
            $columns = [];
            for ($i = 0; $i < $maxCols; $i++) { $columns[] = ['title' => 'Колонка '.($i+1)]; }
            $norm = [];
            foreach ($rows as $r) { $norm[] = array_pad(array_map(fn($v)=> (string)$v, (array)$r), $maxCols, ''); }
            $title = trim($_POST['title'] ?? 'Импорт из Excel');
            $slug = trim($_POST['slug'] ?? 'excel-'.date('Ymd-His'));
            $model = new TableModel();
            $newId = $model->create($title, $slug, '', $columns, $norm, true, $_SESSION['user_id'] ?? null, $mergesList);
            return $this->json(['success' => $newId>0, 'id' => $newId, 'edit_url' => '/admin/tables/edit?id='.$newId, 'public_url' => '/tables/'.$slug]);
        } catch (Throwable $e) {
            error_log('importXlsxCreate error: '.$e->getMessage());
            return $this->json(['success' => false, 'message' => 'Ошибка импорта XLSX: '.$e->getMessage()]);
        }
    }

    public function importJsonCreate() {
        $this->requireAccessLevel(1);
        try {
            $title = trim($_POST['title'] ?? 'Импорт из Excel');
            $slug = trim($_POST['slug'] ?? 'excel-'.date('Ymd-His'));
            $columns = json_decode($_POST['columns'] ?? '[]', true) ?: [];
            $rows = json_decode($_POST['rows'] ?? '[]', true) ?: [];
            if (empty($columns)) return $this->json(['success' => false, 'message' => 'Пустые колонки']);
            // Нормализация
            $columns = array_map(function($c){
                if (is_array($c) && isset($c['title'])) return ['title' => (string)$c['title']];
                return ['title' => (string)$c];
            }, $columns);
            $colCount = count($columns);
            $norm = [];
            foreach ($rows as $r) { $norm[] = array_pad(array_map('strval', (array)$r), $colCount, ''); }
            $model = new TableModel();
            $newId = $model->create($title, $slug, '', $columns, $norm, true, $_SESSION['user_id'] ?? null, []);
            return $this->json(['success' => $newId>0, 'id' => $newId, 'edit_url' => '/admin/tables/edit?id='.$newId, 'public_url' => '/tables/'.$slug]);
        } catch (Throwable $e) {
            error_log('importJsonCreate error: '.$e->getMessage());
            return $this->json(['success' => false, 'message' => 'Ошибка импорта: '.$e->getMessage()]);
        }
    }

    private function json($data) {
        // Убираем любой предыдущий вывод (в т.ч. предупреждения), чтобы не ломать JSON
        if (function_exists('ob_get_level')) { while (@ob_get_level() > 0) { @ob_end_clean(); } }
        if (function_exists('ini_set')) { @ini_set('display_errors', '0'); }
        if (function_exists('http_response_code')) { @http_response_code(200); }
        if (function_exists('header_remove')) { @header_remove('X-Powered-By'); }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}


