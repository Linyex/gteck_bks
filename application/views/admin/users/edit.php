<?php
/** @var array $user */
/** @var array $errors */
?>

<div class="cyberpunk-container">
	<div class="cyberpunk-header">
		<h1 class="cyberpunk-title">✏️ Редактирование пользователя</h1>
		<div class="cyberpunk-actions">
			<a href="/admin/users" class="cyberpunk-btn cyberpunk-btn-secondary">
				<span class="btn-icon">←</span> Назад к списку
			</a>
			<a href="/admin/users/view/<?= (int)$user['user_id'] ?>" class="cyberpunk-btn cyberpunk-btn-info">
				<span class="btn-icon">👤</span> Карточка
			</a>
		</div>
	</div>

	<?php if (!empty($errors)): ?>
		<div class="cyberpunk-alert cyberpunk-alert-danger" role="alert">
			<strong>Ошибки при сохранении:</strong>
			<ul>
				<?php foreach ($errors as $err): ?>
					<li><?= htmlspecialchars($err) ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if (!empty($_SESSION['password_reset_result']) && (int)$_SESSION['password_reset_result']['user_id'] === (int)$user['user_id']): ?>
		<div class="cyberpunk-alert cyberpunk-alert-success" role="alert">
			<strong>Пароль сброшен.</strong>
			<div>Логин: <code><?= htmlspecialchars($_SESSION['password_reset_result']['user_login']) ?></code></div>
			<div>Новый пароль: <code><?= htmlspecialchars($_SESSION['password_reset_result']['new_password']) ?></code></div>
		</div>
		<?php unset($_SESSION['password_reset_result']); ?>
	<?php endif; ?>

	<form action="/admin/users/edit/<?= (int)$user['user_id'] ?>" method="post" class="cyberpunk-form" autocomplete="off">
		<div class="cyberpunk-grid">
			<div class="cyberpunk-card">
				<div class="card-header">
					<h3 class="card-title">📋 Основные данные</h3>
				</div>
				<div class="card-content">
					<div class="form-row">
						<div class="form-group">
							<label>Логин</label>
							<input type="text" class="cyberpunk-input" value="<?= htmlspecialchars($user['user_login']) ?>" disabled>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="cyberpunk-input" value="<?= htmlspecialchars($user['user_email']) ?>" disabled>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group">
							<label for="fio">ФИО</label>
							<input type="text" id="fio" name="fio" class="cyberpunk-input" required value="<?= htmlspecialchars($user['user_fio']) ?>">
						</div>
						<div class="form-group">
							<label for="access_level">Уровень доступа</label>
							<select id="access_level" name="access_level" class="cyberpunk-select">
								<?php $levels = [1=>'Преподаватель',2=>'Методист',3=>'Зав. отделением',4=>'Зам. директора по воспитательной работе',5=>'Зам. директора по учебной работе',6=>'Директор',7=>'Социальный педагог',8=>'Психолог',10=>'Администратор']; foreach ($levels as $lvl=>$name): ?>
									<option value="<?= (int)$lvl ?>" <?= ((int)$user['user_access_level'] === (int)$lvl) ? 'selected' : '' ?>><?= htmlspecialchars($name) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="status">Статус</label>
							<select id="status" name="status" class="cyberpunk-select">
								<option value="1" <?= ((int)$user['user_status'] === 1) ? 'selected' : '' ?>>Активен</option>
								<option value="0" <?= ((int)$user['user_status'] === 0) ? 'selected' : '' ?>>Заблокирован</option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group">
							<label for="password">Новый пароль</label>
							<input type="password" id="password" name="password" class="cyberpunk-input" placeholder="Оставьте пустым, чтобы не менять">
							<small class="form-help">Если указать, будет установлен новый пароль.</small>
						</div>
					</div>
				</div>
			</div>

			<div class="cyberpunk-card">
				<div class="card-header">
					<h3 class="card-title">🚫 Блокировка</h3>
				</div>
				<div class="card-content">
					<div class="form-row">
						<div class="form-group">
							<label for="block_reason">Причина блокировки</label>
							<textarea id="block_reason" name="block_reason" class="cyberpunk-input" rows="3" placeholder="Укажите причину (видно только администраторам)"><?= htmlspecialchars($user['user_block_reason_dec'] ?? '') ?></textarea>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group">
							<label for="block_until">Дата разблокировки (необязательно)</label>
							<input type="datetime-local" id="block_until" name="block_until" class="cyberpunk-input" value="<?= !empty($user['user_block_until']) ? date('Y-m-d\TH:i', strtotime($user['user_block_until'])) : '' ?>">
							<small class="form-help">Оставьте пустым для постоянной блокировки.</small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="cyberpunk-btn cyberpunk-btn-primary">
				<span class="btn-icon">💾</span> Сохранить изменения
			</button>
			<a href="/admin/users" class="cyberpunk-btn cyberpunk-btn-secondary">Отмена</a>
		</div>
	</form>
</div>

<script>
(function(){
	function toggleBlockSection(){
		var statusEl = document.getElementById('status');
		var blocked = statusEl && statusEl.value === '0';
		var reason = document.getElementById('block_reason');
		var until = document.getElementById('block_until');
		if (reason) reason.closest('.cyberpunk-card').style.opacity = blocked ? 1 : 0.5;
		if (reason) reason.disabled = !blocked;
		if (until) until.disabled = !blocked;
	}
	var statusEl = document.getElementById('status');
	if (statusEl) {
		statusEl.addEventListener('change', toggleBlockSection);
		toggleBlockSection();
	}
})();
</script>


