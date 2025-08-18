(function(){
	const state = { page: 1, limit: 50 };

	function qs(id){ return document.getElementById(id); }

	function buildQuery(){
		const params = new URLSearchParams();
		params.set('page', state.page);
		params.set('limit', state.limit);
		const at = qs('actionTypeFilter')?.value || '';
		if (at) params.set('action_type', at);
		const uid = qs('userFilter')?.value || '';
		if (uid) params.set('user_id', uid);
		const ip = qs('ipFilter')?.value || '';
		if (ip) params.set('ip_address', ip);
		const date = qs('dateFilter')?.value || '';
		if (date) params.set('date', date);
		return params.toString();
	}

	function renderStats(stats){
		const box = document.getElementById('auditStats');
		if (!box) return;
		if (!Array.isArray(stats) || stats.length === 0) { box.innerHTML = '<span class="text-muted">Нет данных</span>'; return; }
		box.innerHTML = stats.map(s => {
			const t = s.action_type;
			const c = s.cnt;
			return `<span class="action-type ${t}" style="margin-right:8px; display:inline-block;">${t}: ${c}</span>`;
		}).join('');
	}

	function renderRows(items){
		const tbody = document.querySelector('.audit-table tbody') || document.getElementById('auditBody');
		if (!tbody) return;
		tbody.innerHTML = (items || []).map(log => {
			const user = log.user_fio ? escapeHtml(log.user_fio) : '<span class="text-muted">Гость</span>';
			const detailsBtn = log.action_details ? `<button class="btn btn-sm" data-id="${log.id}" data-details='${escapeHtml(log.action_details)}'>
				<i class="fas fa-eye"></i> Просмотр
			</button>` : '<span class="text-muted">Нет деталей</span>';
			return `<tr>
				<td>${log.id}</td>
				<td>${user}</td>
				<td><span class="action-type ${log.action_type}">${escapeHtml(log.action_type)}</span></td>
				<td>${escapeHtml(log.ip_address || 'N/A')}</td>
				<td>${detailsBtn}</td>
				<td>${formatDateTime(log.created_at)}</td>
			</tr>`;
		}).join('');
		// bind detail buttons
		tbody.querySelectorAll('button[data-details]').forEach(btn => {
			btn.addEventListener('click', () => {
				document.getElementById('detailsModal').style.display = 'block';
				document.getElementById('detailsContent').textContent = tryPrettyJson(btn.getAttribute('data-details'));
			});
		});
	}

	function renderPagination(totalPages){
		const wrap = document.querySelector('.pagination') || document.getElementById('auditPagination');
		if (!wrap) return;
		if (!totalPages || totalPages <= 1) { wrap.innerHTML = ''; return; }
		const cur = state.page;
		let html = '';
		if (cur > 1) html += `<a href="#" data-page="${cur-1}"><i class="fas fa-chevron-left"></i> Назад</a>`;
		for (let i = Math.max(1, cur-2); i <= Math.min(totalPages, cur+2); i++) {
			html += i === cur ? `<span class="current">${i}</span>` : `<a href="#" data-page="${i}">${i}</a>`;
		}
		if (cur < totalPages) html += `<a href="#" data-page="${cur+1}">Вперед <i class="fas fa-chevron-right"></i></a>`;
		wrap.innerHTML = html;
		wrap.querySelectorAll('a[data-page]').forEach(a => {
			a.addEventListener('click', (e) => { e.preventDefault(); state.page = parseInt(a.getAttribute('data-page'),10); load(); });
		});
	}

	function escapeHtml(s){ return (s||'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[m])); }
	function formatDateTime(dt){ try{ const d = new Date(dt.replace(' ', 'T')); return d.toLocaleString('ru-RU'); } catch(_){ return dt; } }
	function tryPrettyJson(s){ try{ const j = JSON.parse(s); return JSON.stringify(j, null, 2); } catch(_){ return s; } }

	async function load(){
		const query = buildQuery();
		const res = await fetch('/admin/security/api/audit?' + query, {credentials:'same-origin'});
		if (!res.ok) return;
		const data = await res.json();
		if (!data.success) return;
		renderRows(data.items);
		renderPagination(data.total_pages);
		renderStats(data.stats);
	}

	window.SecurityAudit = {
		applyFilters: function(){ state.page = 1; load(); },
		refresh: function(){ load(); }
	};

	document.addEventListener('DOMContentLoaded', () => {
		const btn = document.querySelector('.filters .btn.btn-primary');
		if (btn) btn.addEventListener('click', (e)=>{ e.preventDefault(); window.SecurityAudit.applyFilters(); });
		load();
	});
})();


