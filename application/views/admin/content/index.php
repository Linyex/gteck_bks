<?php
$page_title = 'Управление контентом сайта';
?>
<div class="admin-page">
  <div class="admin-header">
    <h1 class="admin-title"><i class="fas fa-edit"></i> Управление контентом сайта</h1>
    <p class="admin-subtitle">Живой конструктор: выберите страницу, кликните по элементу в предпросмотре и измените текст</p>
  </div>

  <div class="constructor-layout">
    <section class="left-panel">
      <style>
        .content-toolbar{display:flex;gap:8px;align-items:center;margin:0 0 14px}
        .content-toolbar .btn{display:inline-flex;align-items:center;gap:8px}
        .tpl-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.35);backdrop-filter:saturate(120%) blur(2px);opacity:0;pointer-events:none;transition:opacity .2s ease}
        .tpl-drawer{position:fixed;top:0;right:-620px;width:600px;max-width:92vw;height:100vh;background:#fff;box-shadow:-12px 0 24px rgba(0,0,0,.12);z-index:1000;padding:16px 18px 22px;overflow:auto;transition:right .25s ease}
        .tpl-drawer.open{right:0}
        .tpl-backdrop.open{opacity:1;pointer-events:auto}
        .tpl-drawer .drawer-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
        .tpl-drawer .drawer-title{font-size:16px;font-weight:600}
        .tpl-drawer .btn-link{background:none;border:none;color:#666;cursor:pointer}
        .file-search{display:flex;gap:8px;margin:8px 0}
        .file-search input{flex:1}
        .hint{color:#888;margin-left:12px}
        .status-ok{color:#138b2e}
        .status-err{color:#b00020}
      </style>
      <div class="content-toolbar">
        <button class="btn btn-secondary" id="open-templates"><i class="fas fa-layer-group"></i> Шаблоны</button>
        <a class="btn" id="open-in-new" target="_blank"><i class="fas fa-external-link-alt"></i> Открыть страницу в новой вкладке</a>
      </div>
      <div class="block preview-panel" data-panel="preview">
        <label>Страница (URL/путь)</label>
        <div class="url-row">
          <input type="text" id="page-url" class="form-control" placeholder="/stud или /news" value="/" />
          <button id="btn-load" class="btn btn-secondary"><i class="fas fa-eye"></i> Открыть</button>
        </div>
        <small>Та же доменная зона, страница откроется справа во фрейме</small>
      </div>

      <div class="tpl-backdrop" id="tpl-backdrop"></div>
      <aside class="tpl-drawer" id="tpl-drawer" aria-hidden="true">
        <div class="drawer-head">
          <div class="drawer-title"><i class="fas fa-layer-group"></i> Шаблоны сайта</div>
          <button class="btn-link" id="close-templates" aria-label="Закрыть"><i class="fas fa-times"></i></button>
        </div>
        <div class="file-search">
          <input type="text" id="file-filter" class="form-control" placeholder="Фильтр по имени..." />
          <button id="refresh-files" class="btn btn-secondary"><i class="fas fa-sync"></i></button>
        </div>
        <div class="url-row">
          <select id="tpl-file" class="form-control" style="flex:1;height:240px" size="12"></select>
          <button id="btn-open-file" class="btn"><i class="fas fa-file-code"></i> Открыть</button>
        </div>
        <textarea id="tpl-editor" class="form-control" rows="14" style="display:none;margin-top:10px"></textarea>
        <div class="form-inline mt-1" id="tpl-actions" style="display:none">
          <button id="btn-save-file" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить</button>
          <span id="tpl-status" class="hint" aria-live="polite"></span>
        </div>
      </aside>

      <div class="block">
        <label>Выбранный элемент</label>
        <input type="text" id="selected-selector" class="form-control" placeholder="CSS селектор" readonly />
        <div class="hint">Кликните по элементу в предпросмотре, чтобы выбрать. Наведите курсор — элемент подсветится.</div>
      </div>

      <div class="block">
        <label>Содержимое</label>
        <textarea id="editor-content" class="form-control" rows="8" placeholder="Введите новый текст"></textarea>
        <div class="form-inline mt-1">
          <label class="checkbox"><input type="checkbox" id="editor-is-html" /> HTML</label>
          <button id="btn-apply" class="btn btn-outline-info"><i class="fas fa-play"></i> Применить в предпросмотре</button>
        </div>
      </div>

      <div class="block">
        <button id="btn-save" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить правку</button>
        <div id="save-status" class="mt-2"></div>
      </div>

      <div class="block">
        <h3>Последние правки</h3>
        <div class="overrides-list">
          <?php foreach ($overrides as $ov): ?>
            <div class="override-item" data-id="<?php echo (int)$ov['id']; ?>">
              <div class="override-meta">
                <code><?php echo htmlspecialchars($ov['path']); ?></code>
                <span class="selector"><?php echo htmlspecialchars($ov['css_selector']); ?></span>
                <span class="date"><?php echo htmlspecialchars($ov['updated_at']); ?></span>
              </div>
              <pre class="override-content"><?php echo htmlspecialchars(mb_strimwidth($ov['content'], 0, 260, '…')); ?></pre>
              <div class="override-actions">
                <button class="btn btn-sm btn-danger js-delete" data-id="<?php echo (int)$ov['id']; ?>"><i class="fas fa-trash"></i> Удалить</button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="right-panel">
      <div class="preview-toolbar">
        <span>Предпросмотр</span>
        <div class="tools">
          <button id="btn-toggle-pick" class="btn btn-sm btn-success"><i class="fas fa-mouse-pointer"></i> Выбор</button>
          <button id="btn-reload" class="btn btn-sm"><i class="fas fa-sync"></i> Обновить</button>
        </div>
      </div>
      <iframe id="preview" title="Предпросмотр" sandbox="allow-same-origin allow-scripts allow-forms" referrerpolicy="no-referrer"></iframe>
    </section>
  </div>
</div>

<style>
.constructor-layout { display:grid; grid-template-columns: 420px 1fr; gap:16px; align-items: stretch; }
@media (max-width: 1200px){ .constructor-layout { grid-template-columns: 1fr; } }
.left-panel { display:flex; flex-direction:column; gap:14px; }
.right-panel { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 12px; overflow:hidden; display:flex; flex-direction:column; }
.block { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 12px; padding: 12px; }
.url-row { display:flex; gap:8px; }
.hint { font-size: 12px; opacity: .8; margin-top: 6px; }
.overrides-list { max-height: 340px; overflow:auto; }
.override-item { background: rgba(0,0,0,0.2); border-radius: 8px; padding: 10px; margin-bottom: 8px; }
.override-meta { display:flex; gap:10px; align-items:center; font-size: 12px; opacity:.9 }
.override-meta code { background: rgba(0,0,0,0.35); padding: 2px 6px; border-radius: 6px; }
.override-meta .selector { color:#93c5fd }
.override-content { white-space: pre-wrap; margin:6px 0 0 }
.form-inline { display:flex; gap:12px; align-items:center; }
.checkbox { display:flex; align-items:center; gap:8px; }
.preview-toolbar { display:flex; justify-content:space-between; align-items:center; padding:8px 10px; border-bottom: 1px solid rgba(255,255,255,0.1); }
#preview { width: 100%; height: calc(100vh - 260px); border:0; background:#fff; }
.picker-overlay { position:absolute; pointer-events:none; border:2px dashed #10b981; background: rgba(16,185,129,0.08); z-index: 999999; }
</style>

<script>
// Элементы UI
const $url = document.getElementById('page-url');
const $btnLoad = document.getElementById('btn-load');
const $iframe = document.getElementById('preview');
const $selector = document.getElementById('selected-selector');
const $content = document.getElementById('editor-content');
const $isHtml = document.getElementById('editor-is-html');
const $apply = document.getElementById('btn-apply');
const $save = document.getElementById('btn-save');
const $status = document.getElementById('save-status');
const $reload = document.getElementById('btn-reload');
const $togglePick = document.getElementById('btn-toggle-pick');
// Toolbar & drawer
const $openInNew = document.getElementById('open-in-new');
const $drawer = document.getElementById('tpl-drawer');
const $backdrop = document.getElementById('tpl-backdrop');
const $btnOpenDrawer = document.getElementById('open-templates');
const $btnCloseDrawer = document.getElementById('close-templates');
const $fileFilter = document.getElementById('file-filter');
const $refreshFiles = document.getElementById('refresh-files');
  // Прямое редактирование файлов
const $tplSelect = document.getElementById('tpl-file');
const $tplOpen = document.getElementById('btn-open-file');
const $tplEditor = document.getElementById('tpl-editor');
const $tplSave = document.getElementById('btn-save-file');
const $tplStatus = document.getElementById('tpl-status');
const $tplActions = document.getElementById('tpl-actions');

let picking = true;
let selectedEl = null;
let overlay = null;

function loadPreview() {
  const path = ($url.value || '/').trim();
  $iframe.src = path;
}

$btnLoad.addEventListener('click', loadPreview);
$reload.addEventListener('click', () => $iframe.contentWindow.location.reload());
$togglePick.addEventListener('click', () => {
  picking = !picking;
  $togglePick.classList.toggle('btn-success', picking);
  $togglePick.classList.toggle('btn-outline-warning', !picking);
  $togglePick.innerHTML = picking ? '<i class="fas fa-mouse-pointer"></i> Выбор' : '<i class="fas fa-ban"></i> Выбор выкл.';
});

function computeSelector(el) {
  if (!el || !el.tagName) return '';
  if (el.id) return `#${CSS.escape(el.id)}`;
  let path = [];
  let node = el;
  while (node && node.nodeType === 1 && path.length < 5 && node !== node.ownerDocument.documentElement) {
    let sel = node.tagName.toLowerCase();
    if (node.classList.length) sel += '.' + Array.from(node.classList).slice(0,3).map(c=>CSS.escape(c)).join('.');
    const parent = node.parentElement;
    if (parent) {
      const siblings = Array.from(parent.children).filter(n => n.tagName === node.tagName);
      if (siblings.length > 1) sel += `:nth-of-type(${siblings.indexOf(node)+1})`;
    }
    path.unshift(sel);
    node = parent;
  }
  return path.join(' > ');
}

function installPicker(doc) {
  overlay = doc.createElement('div');
  overlay.className = 'picker-overlay';
  doc.body.appendChild(overlay);

  const moveOverlay = (el) => {
    if (!el || !el.getBoundingClientRect) return;
    const r = el.getBoundingClientRect();
    overlay.style.left = r.left + doc.defaultView.scrollX + 'px';
    overlay.style.top = r.top + doc.defaultView.scrollY + 'px';
    overlay.style.width = r.width + 'px';
    overlay.style.height = r.height + 'px';
    overlay.style.display = 'block';
  };

  doc.addEventListener('mouseover', (e) => {
    if (!picking) return;
    moveOverlay(e.target);
  }, true);

  doc.addEventListener('click', (e) => {
    if (!picking) return;
    e.preventDefault(); e.stopPropagation();
    selectedEl = e.target;
    $selector.value = computeSelector(selectedEl);
    $content.value = selectedEl.textContent.trim();
    moveOverlay(selectedEl);
  }, true);
}

$iframe.addEventListener('load', () => {
  try { installPicker($iframe.contentDocument); } catch (e) { console.error(e); }
});

// Применить изменения в предпросмотре
$apply.addEventListener('click', () => {
  if (!selectedEl) { $status.textContent = 'Сначала выберите элемент в предпросмотре'; return; }
  if ($isHtml.checked) selectedEl.innerHTML = $content.value; else selectedEl.textContent = $content.value;
});

// Сохранить правку в БД
$save.addEventListener('click', async () => {
  const path = new URL($iframe.contentWindow.location.href, window.location.origin).pathname;
  const selector = $selector.value.trim();
  const content = $content.value;
  if (!selector) { $status.textContent = 'Не выбран элемент (CSS селектор пуст)'; return; }
  const fd = new FormData();
  fd.append('path', path);
  fd.append('selector', selector);
  fd.append('content', content);
  if ($isHtml.checked) fd.append('is_html', '1');
  const res = await fetch('/admin/content/save', { method: 'POST', body: fd });
  const json = await res.json();
  $status.textContent = json.success ? 'Сохранено' : (json.message || 'Ошибка сохранения');
  if (json.success) setTimeout(()=> location.reload(), 700);
});

// Удаление правки
document.querySelectorAll('.js-delete').forEach(btn => btn.addEventListener('click', async () => {
  const id = btn.dataset.id;
  if (!confirm('Удалить правку #' + id + '?')) return;
  const fd = new FormData(); fd.append('id', id);
  const res = await fetch('/admin/content/delete', { method: 'POST', body: fd });
  const json = await res.json();
  if (json.success) location.reload();
}));

// Авто-загрузка главной
loadPreview();
$openInNew.href = location.origin + ($url.value || '/');
$url.addEventListener('input', () => { $openInNew.href = location.origin + ($url.value || '/'); });

// ===== загрузка списка файлов =====
async function loadFiles() {
  try {
    const res = await fetch('/admin/content/list-files');
    const json = await res.json();
    if (!json.success) return;
    $tplSelect.dataset.all = JSON.stringify(json.files);
    fillList(json.files);
  } catch(_) {}
}
loadFiles();

function fillList(list){
  $tplSelect.innerHTML = '';
  list.forEach(f => {
    const opt = document.createElement('option');
    opt.value = f; opt.textContent = f;
    $tplSelect.appendChild(opt);
  });
}

$fileFilter.addEventListener('input', () => {
  const all = JSON.parse($tplSelect.dataset.all || '[]');
  const q = ($fileFilter.value || '').toLowerCase().trim();
  if (!q) return fillList(all);
  fillList(all.filter(f => f.toLowerCase().includes(q)));
});

$refreshFiles.addEventListener('click', loadFiles);

$tplOpen.addEventListener('click', async () => {
  const f = $tplSelect.value;
  if (!f) return;
  const res = await fetch('/admin/content/open?file=' + encodeURIComponent(f));
  const json = await res.json();
  if (json.success) {
    $tplEditor.style.display = '';
    $tplActions.style.display = 'flex';
    $tplEditor.value = json.content;
    $tplStatus.textContent = '';
    openDrawer();
  }
});

$tplSave.addEventListener('click', async () => {
  const fd = new FormData();
  fd.append('file', $tplSelect.value);
  fd.append('content', $tplEditor.value);
  const res = await fetch('/admin/content/save-file', { method: 'POST', body: fd });
  const json = await res.json();
  $tplStatus.textContent = json.message || (json.success ? 'Сохранено' : 'Ошибка');
  $tplStatus.className = 'hint ' + (json.success ? 'status-ok' : 'status-err');
});

function openDrawer(){
  $drawer.classList.add('open');
  $backdrop.classList.add('open');
}
function closeDrawer(){
  $drawer.classList.remove('open');
  $backdrop.classList.remove('open');
}
$btnOpenDrawer.addEventListener('click', openDrawer);
$btnCloseDrawer.addEventListener('click', closeDrawer);
$backdrop.addEventListener('click', closeDrawer);
</script>

