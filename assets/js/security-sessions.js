(function(){
	function qs(id){ return document.getElementById(id); }

	function render(items){
		const tbody = document.querySelector('.sessions-table tbody');
		if (!tbody) return;
		tbody.innerHTML = (items||[]).map(s => {
			const last = new Date((s.last_activity||'').replace(' ', 'T'));
			const created = new Date((s.created_at||'').replace(' ', 'T'));
			const durMs = Math.max(0, last - created);
			const min = Math.floor(durMs/60000); const h = Math.floor(min/60); const d=min%60;
			const duration = h>0 ? `${h}ч ${d}м` : `${min}м`;
			const ua = (s.user_agent||'').slice(0,50) + ((s.user_agent||'').length>50?'...':'');
			return `<tr data-user="${escapeHtml(s.user_fio||'')}" data-ip="${escapeHtml(s.ip_address||'')}">
				<td><div class="session-info"><strong>${escapeHtml(s.user_fio||'Неизвестно')}</strong><br><small>${escapeHtml(s.user_login||'')}</small></div></td>
				<td>${escapeHtml(s.ip_address||'')}</td>
				<td>${isNaN(last)?'': last.toLocaleString('ru-RU')}</td>
				<td class="session-duration">${duration}</td>
				<td><div class="session-info">${escapeHtml(ua)}</div></td>
				<td><span class="session-status active">Активна</span></td>
				<td>
					<div class="session-actions">
						<form method="POST" action="/admin/security/terminate-session" style="display:inline;">
							<input type="hidden" name="session_id" value="${escapeHtml(s.session_token)}">
							<button type="submit" class="btn-terminate" onclick="return confirm('Завершить эту сессию?')"><i class="fas fa-times"></i> Завершить</button>
						</form>
					</div>
				</td>
			</tr>`;
		}).join('');
	}

	function escapeHtml(s){ return (''+s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[m])); }

	async function load(){
		const params = new URLSearchParams();
		const u = qs('userFilter')?.value || '';
		if (u) params.set('user', u);
		const ip = qs('ipFilter')?.value || '';
		if (ip) params.set('ip', ip);
		const res = await fetch('/admin/security/api/sessions?' + params.toString(), {credentials:'same-origin'});
		if (!res.ok) return;
		const data = await res.json();
		if (data.success) render(data.items);
	}

	window.refreshSessions = load;
	document.addEventListener('DOMContentLoaded', () => {
		const uf = qs('userFilter'); const ipf = qs('ipFilter');
		if (uf) uf.addEventListener('change', load);
		if (ipf) ipf.addEventListener('change', load);
		load();
	});
})();


