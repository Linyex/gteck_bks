// Background Animation Controller - Enhanced Version
class BackgroundAnimations {
    constructor() {
        this.currentBackground = 'matrix';
        this.backgrounds = {
            matrix: this.createMatrixRain.bind(this),
            grid: this.createCyberGrid.bind(this),
            holographic: this.createHolographic.bind(this),
            neural: this.createNeuralNetwork.bind(this),
            datastream: this.createDataStream.bind(this),
            circuit: this.createCircuitBoard.bind(this),
            energy: this.createEnergyField.bind(this)
        };
        this.init();
    }

    init() {
        this.createBackgroundContainer();
        this.createBackgroundSelector();
        this.setupEventListeners();
        this.loadSavedBackground();
        this.createParticles();
        this.optimizePerformance();
    }

    createBackgroundContainer() {
        // Удаляем существующий контейнер если есть
        const existing = document.querySelector('.animated-background');
        if (existing) {
            existing.remove();
        }
        
        const container = document.createElement('div');
        container.className = 'animated-background';
        document.body.appendChild(container);
        this.container = container;
    }

    createBackgroundSelector() {
        // Удаляем существующий селектор если есть
        const existing = document.querySelector('.background-selector');
        if (existing) {
            existing.remove();
        }
        
        const selector = document.createElement('div');
        selector.className = 'background-selector';
        selector.innerHTML = `
            <div class="bg-selector-toggle">
                <i class="fas fa-palette"></i>
            </div>
            <div class="bg-selector-content">
                <h4><i class="fas fa-palette"></i> Анимированный фон</h4>
                <div class="bg-option active" data-bg="matrix">
                    <input type="radio" name="background" id="bg-matrix" value="matrix" checked>
                    <label for="bg-matrix">Matrix Rain</label>
                </div>
                <div class="bg-option" data-bg="grid">
                    <input type="radio" name="background" id="bg-grid" value="grid">
                    <label for="bg-grid">Cyber Grid</label>
                </div>
                <div class="bg-option" data-bg="holographic">
                    <input type="radio" name="background" id="bg-holographic" value="holographic">
                    <label for="bg-holographic">Holographic</label>
                </div>
                <div class="bg-option" data-bg="neural">
                    <input type="radio" name="background" id="bg-neural" value="neural">
                    <label for="bg-neural">Neural Network</label>
                </div>
                <div class="bg-option" data-bg="datastream">
                    <input type="radio" name="background" id="bg-datastream" value="datastream">
                    <label for="bg-datastream">Data Stream</label>
                </div>
                <div class="bg-option" data-bg="circuit">
                    <input type="radio" name="background" id="bg-circuit" value="circuit">
                    <label for="bg-circuit">Circuit Board</label>
                </div>
                <div class="bg-option" data-bg="energy">
                    <input type="radio" name="background" id="bg-energy" value="energy">
                    <label for="bg-energy">Energy Field</label>
                </div>
            </div>
        `;
        document.body.appendChild(selector);
        this.selector = selector;
        
        // Добавляем обработчик для переключения
        const toggle = selector.querySelector('.bg-selector-toggle');
        toggle.addEventListener('click', () => {
            selector.classList.toggle('expanded');
        });
        
        // Закрываем селектор при клике вне его
        document.addEventListener('click', (e) => {
            if (!selector.contains(e.target)) {
                selector.classList.remove('expanded');
            }
        });
    }

    setupEventListeners() {
        // Background selection
        this.selector.addEventListener('change', (e) => {
            if (e.target.type === 'radio') {
                this.switchBackground(e.target.value);
            }
        });

        // Option clicks
        this.selector.addEventListener('click', (e) => {
            const option = e.target.closest('.bg-option');
            if (option) {
                const bgType = option.dataset.bg;
                this.switchBackground(bgType);
                this.updateSelector(bgType);
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key >= '1' && e.key <= '7') {
                const backgrounds = Object.keys(this.backgrounds);
                const index = parseInt(e.key) - 1;
                if (backgrounds[index]) {
                    this.switchBackground(backgrounds[index]);
                }
            }
        });
    }

    loadSavedBackground() {
        const saved = localStorage.getItem('cyberpunk-background');
        if (saved && this.backgrounds[saved]) {
            this.switchBackground(saved);
        } else {
            this.switchBackground('matrix');
        }
    }

    switchBackground(type) {
        if (this.currentBackground === type) return;

        // Clear current background
        this.container.innerHTML = '';
        
        // Create new background
        if (this.backgrounds[type]) {
            this.backgrounds[type]();
            this.currentBackground = type;
            localStorage.setItem('cyberpunk-background', type);
            this.updateSelector(type);
        }
    }

    updateSelector(type) {
        // Update radio buttons
        const radio = this.selector.querySelector(`input[value="${type}"]`);
        if (radio) {
            radio.checked = true;
        }

        // Update active class
        this.selector.querySelectorAll('.bg-option').forEach(option => {
            option.classList.remove('active');
        });
        const activeOption = this.selector.querySelector(`[data-bg="${type}"]`);
        if (activeOption) {
            activeOption.classList.add('active');
        }
    }

    createMatrixRain() {
        const matrix = document.createElement('div');
        matrix.className = 'matrix-rain';
        this.container.appendChild(matrix);

        const characters = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
        const columns = Math.floor(window.innerWidth / 15); // Увеличиваем количество колонок

        for (let i = 0; i < columns; i++) {
            const column = document.createElement('div');
            column.className = 'matrix-column';
            column.style.left = (i * 15) + 'px';
            column.style.animationDuration = (Math.random() * 2 + 1.5) + 's'; // Ускоряем анимацию
            column.style.animationDelay = Math.random() * 1 + 's';
            column.style.opacity = '0.9'; // Увеличиваем яркость
            
            let text = '';
            for (let j = 0; j < 25; j++) { // Увеличиваем количество символов
                text += characters[Math.floor(Math.random() * characters.length)] + '<br>';
            }
            column.innerHTML = text;
            
            matrix.appendChild(column);
        }
    }

    createCyberGrid() {
        const grid = document.createElement('div');
        grid.className = 'cyber-grid';
        grid.style.opacity = '0.3'; // Увеличиваем видимость
        this.container.appendChild(grid);
        
        // Добавляем дополнительные эффекты
        const gridOverlay = document.createElement('div');
        gridOverlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(90deg, transparent 98%, rgba(0, 255, 255, 0.2) 100%),
                linear-gradient(0deg, transparent 98%, rgba(255, 0, 255, 0.2) 100%);
            background-size: 30px 30px;
            opacity: 0.4;
            animation: gridMove 15s linear infinite;
        `;
        this.container.appendChild(gridOverlay);
    }

    createHolographic() {
        const holographic = document.createElement('div');
        holographic.className = 'holographic';
        holographic.style.opacity = '0.8'; // Увеличиваем яркость
        this.container.appendChild(holographic);
        
        // Добавляем дополнительные голографические элементы
        for (let i = 0; i < 3; i++) {
            const holoElement = document.createElement('div');
            holoElement.style.cssText = `
                position: absolute;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                width: 200px;
                height: 200px;
                background: radial-gradient(circle, rgba(0, 255, 255, 0.2) 0%, transparent 70%);
                border-radius: 50%;
                animation: holographicShift ${8 + Math.random() * 4}s ease-in-out infinite;
                animation-delay: ${Math.random() * 2}s;
            `;
            this.container.appendChild(holoElement);
        }
    }

    createNeuralNetwork() {
        const neural = document.createElement('div');
        neural.className = 'neural-network';
        this.container.appendChild(neural);

        // Создаем больше узлов
        const nodeCount = 25; // Увеличиваем количество узлов
        for (let i = 0; i < nodeCount; i++) {
            const node = document.createElement('div');
            node.className = 'neural-node';
            node.style.left = Math.random() * 100 + '%';
            node.style.top = Math.random() * 100 + '%';
            node.style.animationDelay = Math.random() * 3 + 's';
            node.style.opacity = '0.8'; // Увеличиваем яркость
            neural.appendChild(node);
        }

        // Создаем больше соединений
        const connectionCount = 15; // Увеличиваем количество соединений
        for (let i = 0; i < connectionCount; i++) {
            const connection = document.createElement('div');
            connection.className = 'neural-connection';
            connection.style.left = Math.random() * 100 + '%';
            connection.style.top = Math.random() * 100 + '%';
            connection.style.width = (Math.random() * 300 + 150) + 'px'; // Увеличиваем длину
            connection.style.transform = `rotate(${Math.random() * 360}deg)`;
            connection.style.animationDelay = Math.random() * 4 + 's';
            connection.style.opacity = '0.6'; // Увеличиваем яркость
            neural.appendChild(connection);
        }
    }

    createDataStream() {
        const stream = document.createElement('div');
        stream.className = 'data-stream';
        this.container.appendChild(stream);

        const columnCount = Math.floor(window.innerWidth / 25); // Увеличиваем количество колонок
        for (let i = 0; i < columnCount; i++) {
            const column = document.createElement('div');
            column.className = 'data-column';
            column.style.left = (i * 25) + 'px';
            column.style.animationDuration = (Math.random() * 3 + 2) + 's'; // Ускоряем анимацию
            column.style.animationDelay = Math.random() * 2 + 's';
            column.style.opacity = '0.8'; // Увеличиваем яркость
            stream.appendChild(column);
        }
    }

    createCircuitBoard() {
        const circuit = document.createElement('div');
        circuit.className = 'circuit-board';
        circuit.style.opacity = '0.2'; // Увеличиваем видимость
        this.container.appendChild(circuit);
        
        // Добавляем дополнительные элементы схемы
        for (let i = 0; i < 5; i++) {
            const circuitElement = document.createElement('div');
            circuitElement.style.cssText = `
                position: absolute;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                width: ${Math.random() * 100 + 50}px;
                height: 2px;
                background: linear-gradient(90deg, transparent, #00ffff, transparent);
                animation: circuitPulse ${6 + Math.random() * 4}s ease-in-out infinite;
                animation-delay: ${Math.random() * 2}s;
            `;
            this.container.appendChild(circuitElement);
        }
    }

    createEnergyField() {
        const energy = document.createElement('div');
        energy.className = 'energy-field';
        energy.style.opacity = '0.7'; // Увеличиваем яркость
        this.container.appendChild(energy);
        
        // Добавляем дополнительные энергетические поля
        for (let i = 0; i < 4; i++) {
            const energyField = document.createElement('div');
            energyField.style.cssText = `
                position: absolute;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(0, 255, 255, 0.15) 0%, transparent 70%);
                border-radius: 50%;
                animation: energyWave ${10 + Math.random() * 5}s ease-in-out infinite;
                animation-delay: ${Math.random() * 3}s;
            `;
            this.container.appendChild(energyField);
        }
    }

    createParticles() {
        // Удаляем существующие частицы
        const existing = document.querySelector('.particles');
        if (existing) {
            existing.remove();
        }
        
        const particlesContainer = document.createElement('div');
        particlesContainer.className = 'particles';
        document.body.appendChild(particlesContainer);

        // Создаем больше частиц
        for (let i = 0; i < 30; i++) { // Увеличиваем количество частиц
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
            particle.style.opacity = '0.6'; // Увеличиваем яркость
            particlesContainer.appendChild(particle);
        }
    }

    optimizePerformance() {
        // Оптимизация производительности
        let ticking = false;
        
        const updateBackground = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    // Обновляем только при необходимости
                    ticking = false;
                });
                ticking = true;
            }
        };

        window.addEventListener('resize', updateBackground);
    }

    destroy() {
        // Очистка при уничтожении
        if (this.container) {
            this.container.remove();
        }
        if (this.selector) {
            this.selector.remove();
        }
        const particles = document.querySelector('.particles');
        if (particles) {
            particles.remove();
        }
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    window.backgroundAnimations = new BackgroundAnimations();
});

// Инициализация если DOM уже загружен
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.backgroundAnimations = new BackgroundAnimations();
    });
} else {
    window.backgroundAnimations = new BackgroundAnimations();
} 