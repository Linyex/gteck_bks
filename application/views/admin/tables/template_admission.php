<?php $page_title = 'Шаблон таблицы приёма'; ?>
<div class="admin-page">
  <div class="admin-header">
    <h1 class="admin-title"><i class="fas fa-table"></i> Шаблон таблицы приёма</h1>
    <p class="admin-subtitle">Заполните поля — будет создана таблица с нужной шапкой и шкалой баллов.</p>
  </div>

  <div class="block">
    <form id="tpl-form" onsubmit="return false;">
      <div class="form-grid" style="display:grid;grid-template-columns:repeat(2, minmax(220px, 1fr));gap:12px;">
        <div>
          <label>Дата</label>
          <input class="form-control" name="date" value="<?php echo date('d.m.Y'); ?>"/>
        </div>
        <div>
          <label>Время</label>
          <input class="form-control" name="time" value="<?php echo date('H:i:s'); ?>"/>
        </div>
        <div>
          <label>Форма получения образования</label>
          <input class="form-control" name="form" value="дневная"/>
        </div>
        <div>
          <label>Основание (на основе скольких классов)</label>
          <input class="form-control" name="basis" value="общего базового образования (9 классов)"/>
        </div>
        <div>
          <label>Специальность</label>
          <input class="form-control" name="specialty" placeholder="Бухгалтерский учёт, анализ и контроль"/>
        </div>
        <div>
          <label>План приёма (число)</label>
          <input class="form-control" name="plan" type="number" min="0" value="0"/>
        </div>
        <div>
          <label>Заголовок таблицы (опционально)</label>
          <input class="form-control" name="title" placeholder="Например: Приём 9 классов — специальность..."/>
        </div>
        <div>
          <label>Слаг для URL (опционально)</label>
          <input class="form-control" name="slug" placeholder="naprimer-priem-9-klassov-buxgalteriya"/>
        </div>
      </div>
      <div class="mt-1">
        <button class="btn btn-primary" id="btn-create"><i class="fas fa-magic"></i> Создать таблицу</button>
        <span id="status" class="hint"></span>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('btn-create').addEventListener('click', async () => {
  const fd = new FormData(document.getElementById('tpl-form'));
  const res = await fetch('/admin/tables/create-admission-template', { method: 'POST', body: fd });
  const json = await res.json();
  const $st = document.getElementById('status');
  if (!json.success) { $st.textContent = json.message || 'Ошибка'; $st.style.color = '#e03131'; return; }
  $st.innerHTML = 'Готово. <a href="'+json.edit_url+'">Редактировать</a> · <a href="'+json.public_url+'" target="_blank">Открыть</a>';
  $st.style.color = '#14a44d';
});
</script>


