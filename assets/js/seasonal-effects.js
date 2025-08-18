(function(){
	const html = document.documentElement;
	const body = document.body;
	const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (prefersReduced) return;

	// Создаём контейнер с canvas поверх страницы
	let host = document.getElementById('season-effects');
	if (!host) {
		host = document.createElement('div');
		host.id = 'season-effects';
		const cv = document.createElement('canvas');
		host.appendChild(cv);
		// Вставляем сразу после header, чтобы фоновая анимация была за контентом
		document.body.prepend(host);
	}

	const canvas = host.querySelector('canvas');
	const ctx = canvas.getContext('2d');
	let width, height, dpr;

	function resize(){
		dpr = window.devicePixelRatio || 1;
		width = window.innerWidth;
		height = window.innerHeight;
		canvas.width = Math.floor(width * dpr);
		canvas.height = Math.floor(height * dpr);
		canvas.style.width = width + 'px';
		canvas.style.height = height + 'px';
		ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
	}
	window.addEventListener('resize', resize);
	resize();

	// Определяем сезон
	let season = 'spring';
	const cl = body.className || '';
	if (cl.indexOf('season-winter')>=0) season = 'winter';
	else if (cl.indexOf('season-summer')>=0) season = 'summer';
	else if (cl.indexOf('season-autumn')>=0) season = 'autumn';

	// Частицы по сезонам
	let particles = [];
    // Лето: используем падающие зелёные листья
	const maxCount = 180;
	let windT = Math.random()*1000;
	function wind(){
		// легкие порывы ветра
		windT += 0.002;
		return Math.sin(windT) * 0.6; // -0.6 .. 0.6
	}

	function rand(min, max){ return Math.random()*(max-min)+min; }
	function pick(arr){ return arr[Math.floor(Math.random()*arr.length)]; }

	function makeParticle(){
		if (season === 'winter') {
			// Снежинки: три слоя глубины + часть — «настоящие» снежинки
			const layer = Math.random()<0.55 ? 1 : (Math.random()<0.6 ? 2 : 3); // 1 ближний, 3 дальний
			const baseVy = layer===1? rand(1.3,1.9) : layer===2? rand(1.0,1.4) : rand(0.6,1.0);
			const baseR  = layer===1? rand(2.2,4.2) : layer===2? rand(1.6,3.0) : rand(1.0,2.2);
			const type = Math.random()<0.35 ? 'flake' : 'snow';
			return {x: rand(0,width), y: rand(-height,0), r: baseR, vy: baseVy, vx: rand(-0.2,0.2), rot: rand(0,Math.PI), vr: rand(-0.015,0.015), ph: rand(0,Math.PI*2), vph: rand(0.01,0.03), layer, type, t: 'snow'};
		}
		if (season === 'autumn') {
			// Лист осени (треугольник/ромб)
			const colors = ['#d35400','#e67e22','#c0392b','#a84300'];
			return {x: rand(0,width), y: rand(-height,0), r: rand(6,12), vy: rand(1.0,1.8), vx: rand(-0.6,0.6), rot: rand(0,Math.PI), vr: rand(-0.02,0.02), color: pick(colors), t:'leaf'};
		}
		if (season === 'spring') {
			// Лепесток сакуры/красные листья (овалы)
			const colors = ['#ff4d4d','#ff6b6b','#ff8b8b','#ff9999'];
			return {x: rand(0,width), y: rand(-height,0), r: rand(3,7), vy: rand(0.8,1.5), vx: rand(-0.4,0.4), rot: rand(0,Math.PI), vr: rand(-0.03,0.03), color: pick(colors), t:'petal'};
		}
		// summer
		return {x: rand(0,width), y: rand(0,height), r: rand(50,120), a: rand(0,Math.PI*2), va: rand(0.0015,0.003), t:'ray'};
	}

	function drawParticle(p){
		if (p.t === 'snow') {
			ctx.save();
			ctx.translate(p.x, p.y);
			ctx.rotate(p.rot||0);
			// Светло-голубое свечение, чтобы было видно на белом фоне
			ctx.shadowColor = 'rgba(90,140,200,0.55)';
			ctx.shadowBlur = p.layer===1 ? 10 : (p.layer===2 ? 7 : 4);
			if (p.type === 'flake') {
				// Простая снежинка из 6 лучей
				ctx.strokeStyle = 'rgba(255,255,255,0.95)';
				ctx.lineWidth = 1;
				for (let i=0;i<6;i++){
					ctx.rotate(Math.PI/3);
					ctx.beginPath();
					ctx.moveTo(0,0);
					ctx.lineTo(0, -p.r*2.2);
					ctx.stroke();
				}
			} else {
				// Двойной круг: мягкий голубой ободок + белая сердцевина
				ctx.beginPath();
				ctx.fillStyle = 'rgba(160,200,240,0.35)';
				ctx.arc(0, 0, p.r*1.6, 0, Math.PI*2);
				ctx.fill();
				ctx.beginPath();
				ctx.fillStyle = 'rgba(255,255,255,0.95)';
				ctx.arc(0, 0, p.r, 0, Math.PI*2);
				ctx.fill();
			}
			ctx.restore();
			return;
		}
		if (p.t === 'leaf') {
			ctx.save();
			ctx.translate(p.x, p.y);
			ctx.rotate(p.rot);
			ctx.fillStyle = p.color;
			ctx.beginPath();
			ctx.moveTo(-p.r, 0);
			ctx.lineTo(0, -p.r*0.6);
			ctx.lineTo(p.r, 0);
			ctx.lineTo(0, p.r*0.6);
			ctx.closePath();
			ctx.fill();
			ctx.restore();
			return;
		}
		if (p.t === 'petal') {
			ctx.save();
			ctx.translate(p.x, p.y);
			ctx.rotate(p.rot);
			ctx.fillStyle = p.color;
			ctx.beginPath();
			ctx.ellipse(0, 0, p.r, p.r*0.6, 0, 0, Math.PI*2);
			ctx.fill();
			ctx.restore();
			return;
		}
		// summer rays (soft glows)
		const grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
		grad.addColorStop(0, 'rgba(255, 223, 128, 0.20)');
		grad.addColorStop(1, 'rgba(255, 223, 128, 0)');
		ctx.fillStyle = grad;
		ctx.beginPath();
		ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
		ctx.fill();
	}

    // --------- Лето: кинематографичные лучи и bokeh ----------
    function initSummer(){ /* не требуется отдельная инициализация */ }

    function drawLeaf(x,y,scale,rot,color){
        ctx.save();
        ctx.translate(x,y);
        ctx.rotate(rot);
        ctx.scale(scale, scale);
        const grad = ctx.createLinearGradient(0,-20,0,20);
        grad.addColorStop(0, color);
        grad.addColorStop(1, 'rgba(0,0,0,0.05)');
        ctx.fillStyle = grad;
        ctx.beginPath();
        // Стилизация листа (каплевидная форма с лодочкой)
        ctx.moveTo(0,-22);
        ctx.bezierCurveTo(16,-18, 18,4, 0,22);
        ctx.bezierCurveTo(-18,4, -16,-18, 0,-22);
        ctx.fill();
        // Жилка
        ctx.strokeStyle = 'rgba(0,0,0,0.10)';
        ctx.lineWidth = 1;
        ctx.beginPath(); ctx.moveTo(0,-18); ctx.lineTo(0,16); ctx.stroke();
        ctx.restore();
    }

    // Летние падающие листья: лёгкие, мелкие, не мешают контенту
    let summerLeaves = null;
    function ensureSummerLeaves(){
        if (summerLeaves) return;
        summerLeaves = [];
        const count = Math.max(30, Math.floor(width/50)); // много мелких
        for (let i=0;i<count;i++){
            summerLeaves.push({
                x: Math.random()*width,
                y: Math.random()*height - height,
                vx: (Math.random()-0.5)*0.25,
                vy: Math.random()*0.8 + 0.35,
                rot: Math.random()*Math.PI*2,
                vr: (Math.random()*0.02 - 0.01),
                s: Math.random()*0.35 + 0.25, // масштаб мелкий
                col: Math.random()<0.5? '#27ae60' : '#58d68d',
                swayP: Math.random()*Math.PI*2,
                swayS: Math.random()*0.02 + 0.008
            });
        }
    }

    function drawSummer(){
        ensureSummerLeaves();
        ctx.save();
        ctx.globalCompositeOperation = 'multiply';
        summerLeaves.forEach(l => {
            l.swayP += l.swayS;
            l.x += l.vx + Math.sin(l.swayP)*0.2;
            l.y += l.vy;
            l.rot += l.vr;
            if (l.y - 20 > height) { // перезапуск сверху
                l.y = -10; l.x = Math.random()*width;
            }
            drawLeaf(l.x, l.y, l.s, l.rot, l.col);
        });
        ctx.restore();
    }

	function step(){
		ctx.clearRect(0,0,width,height);
		if (season === 'summer') {
			// Кинематографичное лето
			drawSummer();
		} else if (season === 'winter') {
			const w = wind();
			particles.forEach(p => {
				p.y += p.vy;
				p.ph += p.vph;
				const sway = Math.sin(p.ph) * (p.layer===1? 0.9 : p.layer===2? 0.7 : 0.5);
				p.x += p.vx + w * (p.layer===1?1: p.layer===2?0.7:0.4) + sway;
				if (p.vr) p.rot += p.vr;
				if (p.y - 20 > height || p.x < -20 || p.x > width+20) {
					Object.assign(p, makeParticle()); p.y = -10; // перезапуск сверху
				}
			});
		} else {
			particles.forEach(p => {
				p.y += p.vy; p.x += p.vx; if (p.vr) p.rot += p.vr;
				if (p.y - 20 > height || p.x < -20 || p.x > width+20) {
					Object.assign(p, makeParticle()); p.y = -10; // перезапуск сверху
				}
			});
		}
		particles.forEach(drawParticle);
		requestAnimationFrame(step);
	}

	// Инициализация
	const initial = season === 'summer' ? 24 : maxCount;
	for (let i=0;i<initial;i++) particles.push(makeParticle());
	step();
})();


