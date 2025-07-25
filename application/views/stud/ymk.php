
<style>
    .panel1,.panel2,.panel3,.panelt101,.panelt111,.panele101,.panely101{
        display: none;
    }
    .panel{
        display: none;
    }
    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 9999;
        display: none;
    }
    .popup {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .inpass {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 10px;
        width: 200px;
    }
    .btnpass {
        padding: 10px 20px;
        margin: 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background: #007bff;
        color: white;
    }
    .btnpass:hover {
        background: #0056b3;
    }
</style>

<form onsubmit="return PassCheck(document)" method="post" id="pop">
<div id="overlay">
    <div class="popup">
        <a>Введите пароль своей группы для получения доступа к УМК<br></a>
        <input type="text" class="inpass" name="inpass" id="pass">
        <button type="submit" class='btnpass'>Отправить</button>

    </div>
</div>
</form>

<!-- Hero Section для УМК -->
<section class="ymk-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">📚</span>Учебно-методические комплексы</h1>
                    <p class="hero-subtitle">Электронные учебно-методические комплексы по всем специальностям и курсам</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">100+</span>
                            <span class="stat-label">УМК</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">4</span>
                            <span class="stat-label">Курса</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">12</span>
                            <span class="stat-label">Групп</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Основной контент -->
<div class="c-layout-page">
    <div class="container">
        <!-- Информационная панель -->
        <div class="info-panel" data-aos="fade-up">
            <div class="info-icon">🔐</div>
            <div class="info-content">
                <h3>Доступ к УМК</h3>
                <p>Для получения доступа к учебно-методическим комплексам вашей группы введите пароль группы</p>
                <button class="access-btn" onclick="showPasswordPrompt()">
                    <i class="fa fa-key"></i>
                    Получить доступ
                </button>
            </div>
        </div>

        <!-- Курсы и группы -->
        <div class="courses-section" data-aos="fade-up" data-aos-delay="200">
            <h2 class="section-title">📖 Курсы и специальности</h2>
            
            <!-- 1-й курс -->
            <div class="course-accordion">
                <div class="course-header" onclick="toggleCourse('course1')">
                    <div class="course-icon">1️⃣</div>
                    <div class="course-info">
                        <h3>1-й курс</h3>
                        <p>Базовые дисциплины и введение в специальность</p>
                    </div>
                    <div class="course-arrow">
                        <i class="fa fa-chevron-down"></i>
                    </div>
                </div>
                <div class="course-content" id="course1">
                    
                    <!-- Группа Т-111 -->
                    <div class="group-card">
                        <div class="group-header" onclick="toggleGroup('group-t111')">
                            <div class="group-badge">Т-111</div>
                            <div class="group-title">Торговая деятельность (на основе ПТО)</div>
                            <div class="group-arrow">
                                <i class="fa fa-chevron-right"></i>
                            </div>
                        </div>
                        <div class="group-materials" id="group-t111">
                            <div class="materials-grid">
                                <a href="/assets/files/ymk/ЭУМК _Основы предпринимательства Т-101, 111, 201, 211, Э-101,201.doc" class="material-item">
                                    <div class="material-icon">📄</div>
                                    <div class="material-info">
                                        <h4>Основы предпринимательства</h4>
                                        <span class="material-type">DOC</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Охрана труда Т-101,111, Э-101.docx" class="material-item">
                                    <div class="material-icon">🛡️</div>
                                    <div class="material-info">
                                        <h4>Охрана труда</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Экспертиза товаров Т 111, 211, 201, 301.docx" class="material-item">
                                    <div class="material-icon">🔍</div>
                                    <div class="material-info">
                                        <h4>Экспертиза товаров</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Информ технол на базе ПТО Т 111, 211 .doc" class="material-item">
                                    <div class="material-icon">💻</div>
                                    <div class="material-info">
                                        <h4>Деловые коммуникации</h4>
                                        <span class="material-type">DOC</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК ЭКОНОМИКА Т-111 проф-тех образование.docx" class="material-item">
                                    <div class="material-icon">💰</div>
                                    <div class="material-info">
                                        <h4>Экономика организации</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/УМК ДАНИЛЕВИЧ ДК Т101,Т111,Э201.docx" class="material-item">
                                    <div class="material-icon">🖥️</div>
                                    <div class="material-info">
                                        <h4>Информационные технологии</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК Основы менеджмента Т-111.docx" class="material-item">
                                    <div class="material-icon">👔</div>
                                    <div class="material-info">
                                        <h4>Основы менеджмента</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК Э-201, Э301, Т-111, Т-201 т211 Ценообразование.docx" class="material-item">
                                    <div class="material-icon">💵</div>
                                    <div class="material-info">
                                        <h4>Ценообразование</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК т-111.docx" class="material-item">
                                    <div class="material-icon">🌍</div>
                                    <div class="material-info">
                                        <h4>Иностранный язык</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/оп ЭУМКт101 т111 э101.docx" class="material-item">
                                    <div class="material-icon">⚖️</div>
                                    <div class="material-info">
                                        <h4>Основы права</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Коммерческая деятельность Т-111, 211, 201, 301.doc" class="material-item">
                                    <div class="material-icon">🏪</div>
                                    <div class="material-info">
                                        <h4>Коммерческая деятельность</h4>
                                        <span class="material-type">DOC</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Основы маркетинга Т-111, 201, 211, Э-101, 201, Б-301.docx" class="material-item">
                                    <div class="material-icon">📈</div>
                                    <div class="material-info">
                                        <h4>Основы маркетинга</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_ОТТ (на основе ПТО) Т 111, Т 211.docx" class="material-item">
                                    <div class="material-icon">🏷️</div>
                                    <div class="material-info">
                                        <h4>ОТТ (на основе ПТО)</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Торговое оборудование Т 111, Т 101.docx" class="material-item">
                                    <div class="material-icon">⚙️</div>
                                    <div class="material-info">
                                        <h4>Торговое оборудование</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf" class="material-item">
                                    <div class="material-icon">🇧🇾</div>
                                    <div class="material-info">
                                        <h4>Белорусский язык профлексика</h4>
                                        <span class="material-type">PDF</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК История беларусской государственности 1 курс.docx" class="material-item">
                                    <div class="material-icon">🏛️</div>
                                    <div class="material-info">
                                        <h4>История беларусской государственности</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/эумк тавароведение Т-111, Т-211.docx" class="material-item">
                                    <div class="material-icon">📦</div>
                                    <div class="material-info">
                                        <h4>Товароведение</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Группа Т-101 -->
                    <div class="group-card">
                        <div class="group-header" onclick="toggleGroup('group-t101')">
                            <div class="group-badge">Т-101</div>
                            <div class="group-title">Торговая деятельность (на основе ОСО)</div>
                            <div class="group-arrow">
                                <i class="fa fa-chevron-right"></i>
                            </div>
                        </div>
                        <div class="group-materials" id="group-t101">
                            <div class="materials-grid">
                                <a href="/assets/files/ymk/ЭУМК _Основы предпринимательства Т-101, 111, 201, 211, Э-101,201.doc" class="material-item">
                                    <div class="material-icon">📄</div>
                                    <div class="material-info">
                                        <h4>Основы предпринимательства</h4>
                                        <span class="material-type">DOC</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Охрана труда Т-101,111, Э-101.docx" class="material-item">
                                    <div class="material-icon">🛡️</div>
                                    <div class="material-info">
                                        <h4>Охрана труда</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК ЭКОНОМИКА Т-101 .docx" class="material-item">
                                    <div class="material-icon">💰</div>
                                    <div class="material-info">
                                        <h4>Экономика организации</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Товароведение продовольственных товаров т101,201,301.docx" class="material-item">
                                    <div class="material-icon">🍎</div>
                                    <div class="material-info">
                                        <h4>Товароведение продовольственных</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/эумк тавароведение Т-101, Т-201.docx" class="material-item">
                                    <div class="material-icon">📦</div>
                                    <div class="material-info">
                                        <h4>Товароведение</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК_Информационные технологии Т 101, 201.docx" class="material-item">
                                    <div class="material-icon">💻</div>
                                    <div class="material-info">
                                        <h4>Информационные технологии</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                                <a href="/assets/files/ymk/ЭУМК Основы менеджмента Т-101.docx" class="material-item">
                                    <div class="material-icon">👔</div>
                                    <div class="material-info">
                                        <h4>Основы менеджмента</h4>
                                        <span class="material-type">DOCX</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Modal -->
<div id="passwordModal" class="password-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>🔐 Доступ к УМК</h3>
            <button class="close-btn" onclick="hidePasswordPrompt()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Введите пароль вашей группы для получения доступа к учебно-методическим комплексам</p>
            <form onsubmit="return checkPassword(event)">
                <input type="password" id="groupPassword" class="password-input" placeholder="Пароль группы" required>
                <button type="submit" class="submit-btn">
                    <i class="fa fa-unlock"></i>
                    Получить доступ
                </button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
        var delay_popup = 0;
        setTimeout("document.getElementById('overlay').style.display='block'", delay_popup);

function PassCheck(form) {
    var password = form.inpass.value;
    
    // Проверяем пароли для разных групп
    if(password == "111") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panelt111").style.display = "inline";
        return false;
    }
    else if(password == "101") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panelt101").style.display = "inline";
        return false;
    }
    else if(password == "э101") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panele101").style.display = "inline";
        return false;
    }
    else if(password == "ю101") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panely101").style.display = "inline";
        return false;
    }
    else if(password == "211") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panelt211").style.display = "inline";
        return false;
    }
    else if(password == "201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panelt201").style.display = "inline";
        return false;
    }
    else if(password == "э201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panele201").style.display = "inline";
        return false;
    }
    else if(password == "ю201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panely201").style.display = "inline";
        return false;
    }
    else if(password == "301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panelt301").style.display = "inline";
        return false;
    }
    else if(password == "э301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panele301").style.display = "inline";
        return false;
    }
    else if(password == "б301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panelb301").style.display = "inline";
        return false;
    }
    else {
        alert("Неверный пароль!");
        return false;
    }
}
</script>

<style>
/* YMK Page Styles */
.c-layout-page {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 24px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    margin: 24px;
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.c-layout-page::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.08) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.08) 0%, transparent 50%);
    pointer-events: none;
    z-index: 1;
}

/* Info Panel */
.info-panel {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 40px;
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    z-index: 2;
}

.info-icon {
    font-size: 3rem;
    flex-shrink: 0;
}

.info-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 10px;
}

.info-content p {
    color: #6B7280;
    margin-bottom: 20px;
    line-height: 1.6;
}

.access-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.access-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
}

/* Section Title */
.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1F2937;
    margin-bottom: 30px;
    position: relative;
    z-index: 2;
}

/* Course Accordion */
.course-accordion {
    background: rgba(255,255,255,0.9);
    border-radius: 20px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    position: relative;
    z-index: 2;
}

.course-header {
    display: flex;
    align-items: center;
    padding: 25px 30px;
    cursor: pointer;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transition: all 0.3s ease;
}

.course-header:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
}

.course-icon {
    font-size: 2rem;
    margin-right: 20px;
    flex-shrink: 0;
}

.course-info {
    flex: 1;
}

.course-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: white;
}

.course-info p {
    color: rgba(255,255,255,0.9);
    margin: 0;
    font-size: 0.9rem;
}

.course-arrow {
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.course-arrow.active {
    transform: rotate(180deg);
}

/* Course Content */
.course-content {
    display: none;
    padding: 30px;
}

.course-content.active {
    display: block;
}

/* Group Cards */
.group-card {
    background: rgba(255,255,255,0.8);
    border-radius: 16px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    margin-bottom: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.group-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.group-header {
    display: flex;
    align-items: center;
    padding: 20px 25px;
    cursor: pointer;
    background: rgba(102, 126, 234, 0.05);
    transition: all 0.3s ease;
}

.group-header:hover {
    background: rgba(102, 126, 234, 0.1);
}

.group-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.9rem;
    margin-right: 15px;
    flex-shrink: 0;
}

.group-title {
    flex: 1;
    font-size: 1.2rem;
    font-weight: 600;
    color: #1F2937;
}

.group-arrow {
    font-size: 1rem;
    color: #6B7280;
    transition: transform 0.3s ease;
}

.group-arrow.active {
    transform: rotate(90deg);
}

/* Group Materials */
.group-materials {
    display: none;
    padding: 25px;
    background: rgba(255,255,255,0.5);
}

.group-materials.active {
    display: block;
}

.materials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
}

.material-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 16px 20px;
    background: white;
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.material-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.05), transparent);
    transition: left 0.6s ease;
}

.material-item:hover::before {
    left: 100%;
}

.material-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
    text-decoration: none;
    color: inherit;
}

.material-icon {
    font-size: 1.8rem;
    flex-shrink: 0;
}

.material-info {
    flex: 1;
}

.material-info h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 4px;
    line-height: 1.3;
}

.material-type {
    font-size: 0.75rem;
    color: #6B7280;
    background: rgba(102, 126, 234, 0.1);
    padding: 2px 8px;
    border-radius: 6px;
    font-weight: 500;
}

/* Password Modal */
.password-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    backdrop-filter: blur(8px);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 20px;
    padding: 0;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    width: 90%;
    max-width: 400px;
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
}

.close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.close-btn:hover {
    background: rgba(255,255,255,0.2);
}

.modal-body {
    padding: 30px;
}

.modal-body p {
    margin-bottom: 20px;
    color: #6B7280;
    line-height: 1.6;
}

.password-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 12px;
    font-size: 1rem;
    margin-bottom: 20px;
    transition: border-color 0.3s ease;
}

.password-input:focus {
    outline: none;
    border-color: #667eea;
}

.submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .c-layout-page {
        margin: 12px;
        padding: 20px;
    }
    
    .course-header, .group-header {
        padding: 15px 20px;
    }
    
    .materials-grid {
        grid-template-columns: 1fr;
    }
    
    .info-panel {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .section-title {
        font-size: 2rem;
    }
}
</style>

<script>
// JavaScript для YMK страницы
function showPasswordPrompt() {
    document.getElementById('passwordModal').style.display = 'block';
    document.getElementById('groupPassword').focus();
}

function hidePasswordPrompt() {
    document.getElementById('passwordModal').style.display = 'none';
    document.getElementById('groupPassword').value = '';
}

function checkPassword(event) {
    event.preventDefault();
    const password = document.getElementById('groupPassword').value;
    
    // Список паролей групп (это должно быть на сервере в реальном приложении)
    const groupPasswords = {
        'T111': 't111',
        'T101': 't101',
        'E101': 'e101',
        'Y101': 'y101',
        'T211': 't211',
        'T201': 't201',
        'E201': 'e201',
        'Y201': 'y201',
        'T301': 't301',
        'E301': 'e301',
        'B301': 'b301'
    };
    
    let accessGranted = false;
    let groupName = '';
    
    for (const [group, pass] of Object.entries(groupPasswords)) {
        if (password.toLowerCase() === pass) {
            accessGranted = true;
            groupName = group;
            break;
        }
    }
    
    if (accessGranted) {
        hidePasswordPrompt();
        showSuccessMessage(groupName);
        // Здесь можно добавить логику для показа материалов конкретной группы
    } else {
        showErrorMessage();
    }
    
    return false;
}

function showSuccessMessage(group) {
    const message = document.createElement('div');
    message.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
            z-index: 10000;
            font-weight: 600;
        ">
            ✅ Доступ к УМК группы ${group} получен!
        </div>
    `;
    document.body.appendChild(message);
    
    setTimeout(() => {
        document.body.removeChild(message);
    }, 3000);
}

function showErrorMessage() {
    const message = document.createElement('div');
    message.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
            z-index: 10000;
            font-weight: 600;
        ">
            ❌ Неверный пароль группы
        </div>
    `;
    document.body.appendChild(message);
    
    setTimeout(() => {
        document.body.removeChild(message);
    }, 3000);
}

function toggleCourse(courseId) {
    const content = document.getElementById(courseId);
    const arrow = content.previousElementSibling.querySelector('.course-arrow');
    
    if (content.classList.contains('active')) {
        content.classList.remove('active');
        arrow.classList.remove('active');
    } else {
        // Закрыть все другие курсы
        document.querySelectorAll('.course-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.course-arrow').forEach(a => a.classList.remove('active'));
        
        // Открыть выбранный курс
        content.classList.add('active');
        arrow.classList.add('active');
    }
}

function toggleGroup(groupId) {
    const materials = document.getElementById(groupId);
    const arrow = materials.previousElementSibling.querySelector('.group-arrow');
    
    if (materials.classList.contains('active')) {
        materials.classList.remove('active');
        arrow.classList.remove('active');
    } else {
        materials.classList.add('active');
        arrow.classList.add('active');
    }
}

// Закрытие модального окна по клику на фон
document.addEventListener('click', function(event) {
    const modal = document.getElementById('passwordModal');
    if (event.target === modal) {
        hidePasswordPrompt();
    }
});

// Закрытие модального окна по Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hidePasswordPrompt();
    }
});
</script>



