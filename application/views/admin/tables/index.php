<?php $page_title = 'Таблицы (Excel-подобные)'; ?>
<div class="admin-page">
  <div class="admin-header">
    <h1 class="admin-title"><i class="fas fa-table"></i> Таблицы</h1>
    <p class="admin-subtitle">Создавайте и редактируйте таблицы для публичного просмотра (без регистрации).</p>
  </div>

  <div class="block">
    <button class="btn btn-primary" id="btn-create"><i class="fas fa-plus"></i> Новая таблица</button>
    <a class="btn" href="/admin/tables/template-admission"><i class="fas fa-magic"></i> Шаблон приёма</a>
    <form id="xlsx-form" enctype="multipart/form-data" style="display:inline-flex; gap:8px; align-items:center" onsubmit="return false;">
      <input type="file" name="file" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
      <input type="text" name="title" class="form-control" placeholder="Заголовок" style="width:220px" />
      <input type="text" name="slug" class="form-control" placeholder="Слаг (опц.)" style="width:200px" />
      <button class="btn" id="btn-import-xlsx"><i class="fas fa-file-import"></i> Создать из Excel</button>
    </form>
  </div>

  <div class="block">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Заголовок</th>
          <th>Слаг</th>
          <th>Публичная</th>
          <th>Обновлена</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tables as $t): ?>
          <tr>
            <td><?php echo (int)$t['id']; ?></td>
            <td><?php echo htmlspecialchars($t['title']); ?></td>
            <td><code><?php echo htmlspecialchars($t['slug']); ?></code></td>
            <td><?php echo ((int)$t['is_public']===1?'да':'нет'); ?></td>
            <td><?php echo htmlspecialchars($t['updated_at']); ?></td>
            <td>
              <a class="btn btn-sm" href="/admin/tables/edit?id=<?php echo (int)$t['id']; ?>"><i class="fas fa-edit"></i> Редактировать</a>
              <button class="btn btn-sm btn-danger js-del" data-id="<?php echo (int)$t['id']; ?>"><i class="fas fa-trash"></i> Удалить</button>
              <a class="btn btn-sm" href="/tables/<?php echo urlencode($t['slug']); ?>" target="_blank"><i class="fas fa-external-link-alt"></i> Открыть</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.getElementById('btn-create').addEventListener('click', () => {
  window.location.href = '/admin/tables/edit';
});
document.getElementById('btn-import-xlsx').addEventListener('click', async () => {
  const form = document.getElementById('xlsx-form');
  const fd = new FormData(form);
  const res = await fetch('/admin/tables/import-xlsx-create', { method: 'POST', body: fd });
  const text = await res.text();
  let json;
  try { json = JSON.parse(text); }
  catch (e) {
    alert('Сервер вернул не-JSON ответ. Возможно, ошибка PHP.\n\nФрагмент ответа:\n' + text.slice(0, 800));
    return;
  }
  if (json.success) {
    window.location.href = json.edit_url;
  } else {
    alert(json.message || 'Ошибка импорта XLSX');
  }
});
document.querySelectorAll('.js-del').forEach(btn => btn.addEventListener('click', async () => {
  if (!confirm('Удалить таблицу #' + btn.dataset.id + '?')) return;
  const fd = new FormData(); fd.append('id', btn.dataset.id);
  const res = await fetch('/admin/tables/delete', { method:'POST', body:fd });
  const text = await res.text();
  let json; try { json = JSON.parse(text); } catch(e) { alert('Ошибка удаления: не-JSON ответ\n\n'+text.slice(0,800)); return; }
  if (json.success) location.reload(); else alert(json.message || 'Не удалось удалить');
}));
</script>


