<?php $page_title = isset($item['id']) ? 'Редактирование таблицы' : 'Создание таблицы'; ?>
<div class="admin-page">
  <div class="admin-header">
    <h1 class="admin-title"><i class="fas fa-table"></i> <?php echo $page_title; ?></h1>
  </div>

  <div class="block">
    <form id="meta-form" onsubmit="return false;">
      <input type="hidden" name="id" value="<?php echo isset($item['id']) ? (int)$item['id'] : 0; ?>" />
      <div class="form-row">
        <label>Заголовок</label>
        <input class="form-control" name="title" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" required />
      </div>
      <div class="form-row">
        <label>Слаг (для URL)</label>
        <input class="form-control" name="slug" value="<?php echo htmlspecialchars($item['slug'] ?? ''); ?>" placeholder="naprimer-priem-9-klassov" />
      </div>
      <div class="form-row">
        <label>Описание</label>
        <textarea class="form-control" name="description" rows="2"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
      </div>
      <label class="checkbox"><input type="checkbox" name="is_public" <?php echo (!isset($item['is_public']) || (int)$item['is_public']===1)?'checked':''; ?>/> Публичная</label>
      <div class="mt-1">
        <button class="btn btn-primary" id="save-meta"><i class="fas fa-save"></i> Сохранить мета</button>
        <a class="btn" href="/admin/tables">Назад</a>
        <span id="status-meta" class="hint"></span>
      </div>
    </form>
  </div>

  <div class="block">
    <h3>Данные таблицы</h3>
    <div class="form-inline">
      <button class="btn" id="add-col"><i class="fas fa-columns"></i> Добавить колонку</button>
      <button class="btn" id="add-row"><i class="fas fa-plus"></i> Добавить строку</button>
      <button class="btn btn-secondary" id="save-data"><i class="fas fa-save"></i> Сохранить данные</button>
      <span id="status-data" class="hint"></span>
      <div style="flex:1"></div>
      <?php if (!empty($item['id'])): ?>
      <a class="btn" href="/admin/tables/export-csv?id=<?php echo (int)$item['id']; ?>" target="_blank"><i class="fas fa-file-csv"></i> Экспорт CSV</a>
      <a class="btn" href="/admin/tables/export-xls?id=<?php echo (int)$item['id']; ?>" target="_blank"><i class="fas fa-file-excel"></i> Экспорт XLS</a>
      <a class="btn" href="/admin/tables/export-pdf?id=<?php echo (int)$item['id']; ?>" target="_blank"><i class="fas fa-file-pdf"></i> Экспорт PDF</a>
      <?php endif; ?>
    </div>
    <div id="grid" class="cms-grid" style="overflow:auto; max-width: 100%;">
      <table class="admin-table" id="grid-table">
        <thead><tr id="grid-head"></tr></thead>
        <tbody id="grid-body"></tbody>
      </table>
    </div>
    <div class="form-inline mt-1">
      <?php if (!empty($item['id'])): ?>
      <form id="import-form" enctype="multipart/form-data" onsubmit="return false;">
        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>" />
        <label class="checkbox"><input type="checkbox" name="has_header" checked /> Первая строка — заголовки</label>
        <input type="file" name="file" accept=".csv,text/csv" />
        <button class="btn" id="btn-import"><i class="fas fa-file-import"></i> Импорт CSV</button>
      </form>
      <?php endif; ?>
    </div>
    <div class="form-inline mt-1">
      <button class="btn" id="add-merge"><i class="fas fa-object-group"></i> Объединить ячейки</button>
      <button class="btn btn-secondary" id="clear-merges"><i class="fas fa-ban"></i> Очистить объединения</button>
    </div>
  </div>
</div>

<style>
.form-row { display:flex; flex-direction:column; gap:6px; margin-bottom:10px }
.cms-grid td, .cms-grid th { min-width: 160px; }
.cms-grid input { width: 100%; box-sizing: border-box; padding: 6px 8px; }
/* Горизонтальный скролл при большом количестве колонок */
#grid { overflow-x: auto; overflow-y: hidden; }
#grid-table { width: max-content; table-layout: auto; }
/* Выделение диапазона */
#grid-body td.sel { outline: 2px solid #6c63ff; }
</style>

<script>
const state = {
  id: <?php echo isset($item['id']) ? (int)$item['id'] : 0; ?>,
  columns: <?php echo json_encode($item['columns_json'] ?? [], JSON_UNESCAPED_UNICODE); ?>,
  rows: <?php echo json_encode($item['rows_json'] ?? [], JSON_UNESCAPED_UNICODE); ?>,
  merges: <?php echo json_encode($item['merges_json'] ?? [], JSON_UNESCAPED_UNICODE); ?>
};
const $head = document.getElementById('grid-head');
const $body = document.getElementById('grid-body');
const $statusMeta = document.getElementById('status-meta');
const $statusData = document.getElementById('status-data');

function render() {
  // head
  $head.innerHTML = '';
  state.columns.forEach((c, idx) => {
    const th = document.createElement('th');
    th.innerHTML = `<input value="${(c && c.title) ? c.title : ('Колонка '+(idx+1))}" data-idx="${idx}" class="js-col-title"/>`;
    $head.appendChild(th);
  });
  // body
  $body.innerHTML = '';
  state.rows.forEach((r, rIdx) => {
    const tr = document.createElement('tr');
    state.columns.forEach((c, cIdx) => {
      const td = document.createElement('td');
      const val = (r && r[cIdx] != null) ? r[cIdx] : '';
      td.innerHTML = `<input value="${String(val).replaceAll('"','&quot;')}" data-r="${rIdx}" data-c="${cIdx}" class="js-cell"/>`;
      // помечаем координату для последующего применения merges
      td.dataset.r = rIdx; td.dataset.c = cIdx;
      tr.appendChild(td);
    });
    $body.appendChild(tr);
  });
  applyMerges();
}

function ensureAtLeastOne() {
  if (!Array.isArray(state.columns) || state.columns.length === 0) state.columns = [{ title: 'Колонка 1' }];
  if (!Array.isArray(state.rows) || state.rows.length === 0) state.rows = [[ '' ]];
}

ensureAtLeastOne();
render();

document.getElementById('add-col').addEventListener('click', () => {
  state.columns.push({ title: 'Новая колонка' });
  state.rows.forEach(r => r.push(''));
  render();
});
document.getElementById('add-row').addEventListener('click', () => {
  const cols = state.columns.length;
  state.rows.push(Array.from({length: cols}, () => ''));
  render();
});

document.addEventListener('input', (e) => {
  if (e.target.classList.contains('js-col-title')) {
    const idx = +e.target.dataset.idx;
    state.columns[idx] = { title: e.target.value };
  }
  if (e.target.classList.contains('js-cell')) {
    const r = +e.target.dataset.r; const c = +e.target.dataset.c;
    state.rows[r][c] = e.target.value;
  }
});

document.getElementById('save-meta').addEventListener('click', async () => {
  const fd = new FormData(document.getElementById('meta-form'));
  const res = await fetch('/admin/tables/save-meta', { method:'POST', body:fd });
  const json = await res.json();
  if (json.success && !state.id) window.location.href = '/admin/tables/edit?id=' + json.id;
  document.getElementById('status-meta').textContent = json.success ? 'Сохранено' : 'Ошибка';
});

document.getElementById('save-data').addEventListener('click', async () => {
  if (!state.id) { alert('Сначала сохраните метаданные'); return; }
  const fd = new FormData();
  fd.append('id', state.id);
  fd.append('columns', JSON.stringify(state.columns));
  fd.append('rows', JSON.stringify(state.rows));
  fd.append('merges', JSON.stringify(state.merges || []));
  const res = await fetch('/admin/tables/save-data', { method:'POST', body: fd });
  const json = await res.json();
  $statusData.textContent = json.success ? 'Данные сохранены' : 'Ошибка';
});

const importBtn = document.getElementById('btn-import');
if (importBtn) {
  importBtn.addEventListener('click', async () => {
    if (!state.id) { alert('Сначала сохраните метаданные'); return; }
    const form = document.getElementById('import-form');
    const fd = new FormData(form);
    const res = await fetch('/admin/tables/import-csv', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) { location.reload(); } else { alert(json.message || 'Ошибка импорта'); }
  });
}

// --- Выделение и объединение ячеек ---
let selStart = null; // {r,c}
let selEnd = null;   // {r,c}

function clearSelection() {
  document.querySelectorAll('#grid-body td').forEach(td => td.classList.remove('sel'));
}

function markSelection() {
  if (!selStart || !selEnd) return;
  const r1 = Math.min(selStart.r, selEnd.r), r2 = Math.max(selStart.r, selEnd.r);
  const c1 = Math.min(selStart.c, selEnd.c), c2 = Math.max(selStart.c, selEnd.c);
  clearSelection();
  for (let r=r1; r<=r2; r++) {
    for (let c=c1; c<=c2; c++) {
      const td = document.querySelector(`#grid-body td[data-r="${r}"][data-c="${c}"]`);
      if (td) td.classList.add('sel');
    }
  }
}

document.getElementById('grid').addEventListener('mousedown', (e) => {
  const td = e.target.closest('td');
  if (!td) return;
  selStart = { r: +td.dataset.r, c: +td.dataset.c };
  selEnd = { ...selStart };
  markSelection();
});
document.getElementById('grid').addEventListener('mousemove', (e) => {
  if (!selStart) return;
  const td = e.target.closest('td');
  if (!td) return;
  selEnd = { r: +td.dataset.r, c: +td.dataset.c };
  markSelection();
});
document.addEventListener('mouseup', () => { /* финализируем выделение */ });

document.getElementById('add-merge').addEventListener('click', () => {
  if (!selStart || !selEnd) { alert('Выделите диапазон ячеек мышью'); return; }
  const r1 = Math.min(selStart.r, selEnd.r), r2 = Math.max(selStart.r, selEnd.r);
  const c1 = Math.min(selStart.c, selEnd.c), c2 = Math.max(selStart.c, selEnd.c);
  if (!state.merges) state.merges = [];
  state.merges.push({ r1, c1, r2, c2 });
  applyMerges();
});

document.getElementById('clear-merges').addEventListener('click', () => {
  state.merges = [];
  applyMerges();
});

function applyMerges() {
  // Сбрасываем все colspan/rowspan
  document.querySelectorAll('#grid-body tr').forEach(tr => {
    tr.querySelectorAll('td').forEach(td => { td.style.display = ''; td.removeAttribute('rowspan'); td.removeAttribute('colspan'); });
  });
  const merges = state.merges || [];
  merges.forEach(m => {
    const { r1, c1, r2, c2 } = m;
    const topLeft = document.querySelector(`#grid-body td[data-r="${r1}"][data-c="${c1}"]`);
    if (!topLeft) return;
    const rowspan = (r2 - r1 + 1);
    const colspan = (c2 - c1 + 1);
    topLeft.setAttribute('rowspan', String(rowspan));
    topLeft.setAttribute('colspan', String(colspan));
    for (let r=r1; r<=r2; r++) {
      for (let c=c1; c<=c2; c++) {
        if (r===r1 && c===c1) continue;
        const td = document.querySelector(`#grid-body td[data-r="${r}"][data-c="${c}"]`);
        if (td) td.style.display = 'none';
      }
    }
  });
}
</script>


