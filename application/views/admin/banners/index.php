<?php
$page_title = 'Баннеры футера';
?>
<div class="admin-page">
	<div class="admin-header">
		<h1 class="admin-title"><i class="fa fa-image"></i> Баннеры футера</h1>
		<p class="admin-subtitle">Добавляйте/меняйте баннеры, ссылки, порядок и активность</p>
	</div>

	<div class="block">
		<form id="createBanner" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-3">
					<label>Заголовок (необязательно)</label>
					<input type="text" name="title" class="form-control" placeholder="Подпись баннера">
				</div>
				<div class="col-md-4">
					<label>Ссылка</label>
					<input type="text" name="link_url" class="form-control" placeholder="https://... или /path">
				</div>
				<div class="col-md-2">
					<label>Позиция</label>
					<input type="number" name="position" class="form-control" value="0">
				</div>
				<div class="col-md-3">
					<label>Изображение (jpg/png/webp)</label>
					<input type="file" name="image" class="form-control" accept="image/*" required>
				</div>
			</div>
			<div style="margin-top:10px; display:flex; gap:12px; align-items:center">
				<label style="display:flex;gap:6px;align-items:center"><input type="checkbox" name="is_active" checked> Активно</label>
				<button class="btn btn-primary" type="submit">Добавить баннер</button>
			</div>
		</form>
	</div>

	<div class="block">
		<h3>Список баннеров</h3>
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Превью</th>
					<th>Заголовок</th>
					<th>Ссылка</th>
					<th>Активно</th>
					<th>Позиция</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="bannersBody">
				<?php foreach (($items ?? []) as $b): ?>
				<tr data-id="<?php echo (int)$b['id']; ?>">
					<td><?php echo (int)$b['id']; ?></td>
					<td>
						<?php if (!empty($b['image_path'])): ?>
							<img src="<?php echo htmlspecialchars($b['image_path']); ?>" alt="" style="max-width:180px; max-height:70px; object-fit:cover">
						<?php endif; ?>
						<div style="margin-top:6px"><input type="file" class="form-control i-image" accept="image/*"></div>
					</td>
					<td><input class="form-control i-title" value="<?php echo htmlspecialchars($b['title'] ?? ''); ?>" placeholder="Подпись"></td>
					<td><input class="form-control i-link" value="<?php echo htmlspecialchars($b['link_url'] ?? ''); ?>" placeholder="URL"></td>
					<td><input type="checkbox" class="i-active" <?php echo !empty($b['is_active']) ? 'checked' : ''; ?>></td>
					<td><input type="number" class="form-control i-pos" value="<?php echo (int)$b['position']; ?>" style="width:90px"></td>
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
		const createForm = document.getElementById('createBanner');
		createForm.addEventListener('submit', async (e)=>{
			e.preventDefault();
			const fd = new FormData(createForm);
			const res = await fetch('/admin/banners/store', {method:'POST', body: fd});
			try{const j = await res.json(); if(j.success){ location.reload(); } else { alert('Ошибка: '+(j.message||'')); }}catch(_){ alert('Ошибка сети'); }
		});

		document.querySelectorAll('#bannersBody tr').forEach((row)=>{
			row.querySelector('.act-save').addEventListener('click', async ()=>{
				const fd = new FormData();
				fd.append('id', row.getAttribute('data-id'));
				fd.append('title', row.querySelector('.i-title').value.trim());
				fd.append('link_url', row.querySelector('.i-link').value.trim());
				fd.append('position', row.querySelector('.i-pos').value);
				fd.append('is_active', row.querySelector('.i-active').checked ? '1' : '');
				const f = row.querySelector('.i-image'); if (f && f.files && f.files[0]) fd.append('image', f.files[0]);
				const res = await fetch('/admin/banners/update', {method:'POST', body: fd});
				try{const j = await res.json(); if(!j.success) alert('Не сохранено'); }catch(_){ alert('Ошибка сети'); }
			});
			row.querySelector('.act-del').addEventListener('click', async ()=>{
				if(!confirm('Удалить баннер?')) return;
				const fd = new FormData(); fd.append('id', row.getAttribute('data-id'));
				const res = await fetch('/admin/banners/delete', {method:'POST', body: fd});
				try{const j = await res.json(); if(j.success){ row.remove(); } else { alert('Не удалено'); }}catch(_){ alert('Ошибка сети'); }
			});
		});
	})();
	</script>
</div>


