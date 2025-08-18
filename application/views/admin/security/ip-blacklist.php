<?php $currentPage = 'security'; ?>

<div class="admin-container">
	<div class="admin-header">
		<h1><i class="fas fa-ban"></i> Блокировка IP адресов</h1>
		<p>Управление черным списком IP адресов</p>
	</div>

	<?php if (!empty($_SESSION['flash_message'])): ?>
		<div class="alert <?= htmlspecialchars($_SESSION['flash_message']['type']) ?>">
			<?= htmlspecialchars($_SESSION['flash_message']['text']) ?>
		</div>
		<?php unset($_SESSION['flash_message']); ?>
	<?php endif; ?>

	<div class="admin-content">
		<div class="cyberpunk-card">
			<div class="card-header">
				<h3 class="card-title">➕ Добавить IP</h3>
			</div>
			<div class="card-content">
				<form action="/admin/security/ip-blacklist/add" method="post" class="cyberpunk-form">
					<div class="form-row">
						<div class="form-group">
							<label for="ip_address">IP адрес</label>
							<input type="text" id="ip_address" name="ip_address" class="cyberpunk-input" placeholder="Например, 192.168.0.1" required>
						</div>
						<div class="form-group">
							<label for="blocked_until">Заблокирован до (необязательно)</label>
							<input type="datetime-local" id="blocked_until" name="blocked_until" class="cyberpunk-input">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group" style="flex:1;">
							<label for="reason">Причина</label>
							<input type="text" id="reason" name="reason" class="cyberpunk-input" placeholder="Причина блокировки">
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="cyberpunk-btn cyberpunk-btn-primary"><span class="btn-icon">💾</span> Добавить</button>
					</div>
				</form>
			</div>
		</div>

		<div class="cyberpunk-card" style="margin-top:20px;">
			<div class="card-header">
				<h3 class="card-title">📋 Черный список</h3>
			</div>
			<div class="card-content">
				<?php if (empty($blacklist)): ?>
					<div class="empty-state">
						<i class="fas fa-list"></i>
						<h3>Список пуст</h3>
						<p>Добавьте IP адрес для блокировки</p>
					</div>
				<?php else: ?>
					<table class="audit-table" style="width:100%;">
						<thead>
							<tr>
								<th>ID</th>
								<th>IP адрес</th>
								<th>Причина</th>
								<th>До</th>
								<th>Создано</th>
								<th>Кем</th>
								<th>Действия</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($blacklist as $row): ?>
							<tr>
								<td><?= (int)$row['id'] ?></td>
								<td><?= htmlspecialchars($row['ip_address']) ?></td>
								<td><?= htmlspecialchars($row['reason'] ?? '') ?></td>
								<td><?= !empty($row['blocked_until']) ? date('d.m.Y H:i', strtotime($row['blocked_until'])) : '—' ?></td>
								<td><?= date('d.m.Y H:i', strtotime($row['created_at'])) ?></td>
								<td><?= htmlspecialchars($row['created_by_name'] ?? '—') ?></td>
								<td>
									<form action="/admin/security/ip-blacklist/remove" method="post" onsubmit="return confirm('Удалить запись?')">
										<input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
										<button type="submit" class="cyberpunk-btn cyberpunk-btn-danger" style="padding:4px 8px;">Удалить</button>
									</form>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>


