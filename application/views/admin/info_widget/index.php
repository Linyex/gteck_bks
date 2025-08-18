<?php
$page_title = 'Инфо-виджет: управление';
?>
<div class="admin-page">
	<div class="admin-header">
		<h1 class="admin-title"><i class="fas fa-info-circle"></i> Инфо-виджет: управление</h1>
		<p class="admin-subtitle">Добавляйте ссылки/документы, скрывайте пункты приёмной комиссии, меняйте порядок</p>
	</div>

	<div class="block">
		<form id="createForm" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-3">
					<label>Заголовок</label>
					<input type="text" name="title" class="form-control" required>
				</div>
				<div class="col-md-3">
					<label>URL</label>
					<input type="text" name="url" class="form-control" placeholder="https://... или /path">
				</div>
				<div class="col-md-2">
					<label>Иконка (FA)</label>
					<input type="text" name="icon" class="form-control" placeholder="fa fa-calendar" id="createIconInput">
					<div class="icon-helper">
						<button type="button" class="btn btn-sm" id="toggleCreateIcons">Иконки</button>
						<div class="icon-picker" id="createIconPicker" style="display:none"></div>
					</div>
				</div>
				<div class="col-md-2">
					<label>Группа</label>
					<select name="group" class="form-control">
						<option value="general">Общее</option>
						<option value="admission">Приёмная комиссия</option>
					</select>
				</div>
				<div class="col-md-2">
					<label>Файл (PDF/DOC)</label>
					<input type="file" name="file" class="form-control">
				</div>
			</div>
			<div class="row" style="margin-top:8px">
				<div class="col-md-8">
					<label>Описание</label>
					<input type="text" name="description" class="form-control" placeholder="Короткое описание">
				</div>
				<div class="col-md-2">
					<label>Позиция</label>
					<input type="number" name="position" class="form-control" value="0">
				</div>
				<div class="col-md-2" style="display:flex;align-items:flex-end">
					<label style="display:flex;gap:6px;align-items:center"><input type="checkbox" name="is_active" checked> Активно</label>
				</div>
			</div>
			<div style="margin-top:10px">
				<button class="btn btn-primary" type="submit">Добавить</button>
			</div>
		</form>
	</div>

	<div class="block">
		<h3>Список элементов</h3>
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Группа</th>
					<th>Заголовок</th>
					<th>Иконка</th>
					<th>URL/Файл</th>
					<th>Активно</th>
					<th>Позиция</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="itemsBody">
				<?php foreach (($items ?? []) as $it): ?>
				<tr data-id="<?php echo (int)$it['id']; ?>">
					<td><?php echo (int)$it['id']; ?></td>
					<td><?php echo htmlspecialchars($it['group']); ?></td>
					<td><input class="form-control i-title" value="<?php echo htmlspecialchars($it['title']); ?>"></td>
					<td>
						<div style="display:flex;gap:6px;align-items:center">
							<input class="form-control i-icon" value="<?php echo htmlspecialchars($it['icon'] ?? ''); ?>" placeholder="fa fa-link" style="max-width:180px">
							<button class="btn btn-sm btn-light ico-toggle" type="button">⋯</button>
						</div>
						<div class="icon-picker" style="display:none"></div>
					</td>
					<td>
						<input class="form-control i-url" placeholder="URL" value="<?php echo htmlspecialchars($it['url'] ?? ''); ?>" style="margin-bottom:4px">
						<input class="form-control i-file" type="file">
						<?php if (!empty($it['file_path'])): ?>
							<div><a href="<?php echo htmlspecialchars($it['file_path']); ?>" target="_blank">Файл</a></div>
						<?php endif; ?>
					</td>
					<td><input type="checkbox" class="i-active" <?php echo !empty($it['is_active']) ? 'checked' : ''; ?>></td>
					<td><input class="form-control i-pos" type="number" value="<?php echo (int)$it['position']; ?>" style="width:90px"></td>
					<td>
						<button class="btn btn-sm btn-success act-save">Сохранить</button>
						<button class="btn btn-sm btn-danger act-del">Удалить</button>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<script>
	(function(){
		// Небольшой набор часто используемых FA-иконок
		const FA_ICONS = [
			'fa fa-info-circle','fa fa-calendar','fa fa-chart-line','fa fa-file-pdf-o','fa fa-file-word-o','fa fa-file-excel-o','fa fa-file-o','fa fa-book','fa fa-bullhorn','fa fa-envelope','fa fa-phone','fa fa-map-marker','fa fa-external-link','fa fa-link','fa fa-download','fa fa-upload','fa fa-graduation-cap','fa fa-users','fa fa-university','fa fa-check','fa fa-times','fa fa-star','fa fa-newspaper-o'
		];

		function renderIconPicker(container, targetInput){
			container.innerHTML = '';
			const wrap = document.createElement('div');
			wrap.style.display = 'grid';
			wrap.style.gridTemplateColumns = 'repeat(4, minmax(0,1fr))';
			wrap.style.gap = '6px';
			FA_ICONS.forEach(cls => {
				const btn = document.createElement('button');
				btn.type = 'button';
				btn.className = 'btn btn-light';
				btn.style.display = 'flex';
				btn.style.alignItems = 'center';
				btn.style.gap = '8px';
				btn.innerHTML = '<i class="'+cls+'"></i><span style="font-size:11px">'+cls+'</span>';
				btn.addEventListener('click', ()=>{ targetInput.value = cls; container.style.display='none'; });
				wrap.appendChild(btn);
			});
			container.appendChild(wrap);
		}

		const createForm = document.getElementById('createForm');
		createForm.addEventListener('submit', async (e)=>{
			e.preventDefault();
			const fd = new FormData(createForm);
			const res = await fetch('/admin/info-widget/store', {method:'POST', body: fd});
			try{const j = await res.json(); if(j.success){ location.reload(); } else { alert('Ошибка: '+(j.message||'')); }}catch(_){ alert('Ошибка сети'); }
		});

		// Пикер иконок для формы создания
		(function(){
			const btn = document.getElementById('toggleCreateIcons');
			const picker = document.getElementById('createIconPicker');
			const input = document.getElementById('createIconInput');
			renderIconPicker(picker, input);
			btn.addEventListener('click', ()=>{
				picker.style.display = (picker.style.display==='none' || !picker.style.display) ? 'block' : 'none';
			});
		})();

		document.querySelectorAll('#itemsBody tr').forEach((row)=>{
			// Пикер иконок в строках списка
			const icoInput = row.querySelector('.i-icon');
			const icoPicker = row.querySelector('.icon-picker');
			const icoBtn = row.querySelector('.ico-toggle');
			if (icoInput && icoPicker && icoBtn) {
				renderIconPicker(icoPicker, icoInput);
				icoBtn.addEventListener('click', ()=>{
					icoPicker.style.display = (icoPicker.style.display==='none' || !icoPicker.style.display) ? 'block' : 'none';
				});
			}

			row.querySelector('.act-save').addEventListener('click', async ()=>{
				const id = row.getAttribute('data-id');
				const fd = new FormData();
				fd.append('id', id);
				fd.append('title', row.querySelector('.i-title').value.trim());
				fd.append('icon', (row.querySelector('.i-icon')?.value||'').trim());
				fd.append('url', row.querySelector('.i-url').value.trim());
				fd.append('position', row.querySelector('.i-pos').value);
				fd.append('is_active', row.querySelector('.i-active').checked ? '1' : '');
				const f = row.querySelector('.i-file');
				if (f && f.files && f.files[0]) { fd.append('file', f.files[0]); }
				const res = await fetch('/admin/info-widget/update', {method:'POST', body: fd});
				try{const j = await res.json(); if(!j.success) alert('Не сохранено'); }catch(_){ alert('Ошибка сети'); }
			});
			row.querySelector('.act-del').addEventListener('click', async ()=>{
				if(!confirm('Удалить элемент?')) return;
				const fd = new FormData();
				fd.append('id', row.getAttribute('data-id'));
				const res = await fetch('/admin/info-widget/delete', {method:'POST', body: fd});
				try{const j = await res.json(); if(j.success){ row.remove(); } else { alert('Не удалено'); }}catch(_){ alert('Ошибка сети'); }
			});
		});
	})();
	</script>
</div>


