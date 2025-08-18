(function(){
	async function refreshStats(){
		try{
			const r = await fetch('/admin/security/api/stats?days=30', {credentials:'same-origin'});
			if(!r.ok) return;
			const data = await r.json();
			// Можно обновить DOM, если есть id-элементы для метрик
		}catch(_){ }
	}
	window.addEventListener('load', refreshStats);
})();


