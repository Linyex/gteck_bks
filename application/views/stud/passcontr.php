<?php 
    $page_title = "Контроль доступа"; 
    include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/header.php'; 
?>

<section class="passcontr-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-icon">🔐</span>
            Контроль доступа
        </h1>
        <p class="hero-subtitle">Доступ к материалам для групп</p>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div id="pop" class="auth-card">
                    <h4 class="auth-title">Введите пароль группы</h4>
                    <p class="auth-description">Для доступа к материалам введите пароль вашей группы</p>
                    
                    <div class="auth-form">
                        <div class="form-group">
                            <label for="pass">Пароль группы:</label>
                            <input type="password" id="pass" class="form-control" placeholder="Введите пароль группы">
                        </div>
                        <button type="button" class="btn btn-primary btn-block" onclick="PassCheck()">
                            <i class="fas fa-key"></i> Войти
                        </button>
                    </div>
                </div>

                <div id="panel1" class="materials-panel" style="display: none;">
                    <h4 class="materials-title">Материалы группы</h4>
                    
                    <div id="panelt101" class="group-panel" style="display: none;">
                        <div class="group-badge">Группа Т-101</div>
                        <p>Доступные материалы для группы Т-101</p>
                    </div>

                    <div id="panelt111" class="group-panel" style="display: none;">
                        <div class="group-badge">Группа Т-111</div>
                        <p>Доступные материалы для группы Т-111</p>
                    </div>

                    <div id="panele101" class="group-panel" style="display: none;">
                        <div class="group-badge">Группа Е-101</div>
                        <p>Доступные материалы для группы Е-101</p>
                    </div>

                    <div id="panely101" class="group-panel" style="display: none;">
                        <div class="group-badge">Группа У-101</div>
                        <p>Доступные материалы для группы У-101</p>
                    </div>

                    <div id="lekcii" class="lectures-section" style="display: none;">
                        <h5>Лекции и материалы</h5>
                        <div class="lectures-grid">
                            <div class="lecture-item">
                                <i class="fas fa-file-pdf"></i>
                                <span>Лекция 1</span>
                            </div>
                            <div class="lecture-item">
                                <i class="fas fa-file-word"></i>
                                <span>Практическое задание</span>
                            </div>
                            <div class="lecture-item">
                                <i class="fas fa-file-powerpoint"></i>
                                <span>Презентация</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
var T101 = "mc9ob8ob";
var T111 = "gaikts0";
var E101 = "htpfqxk4";
var Y101 = "jaoqfkv7";

var passgroup = [T101, T111, E101, Y101];

function PassCheck() {
    var g = document.getElementById("pass");
    var lekc = document.getElementById("lekcii");
    var pop = document.getElementById("pop");
    var mpanel1 = document.getElementById("panel1");

    switch(g.value){ 
        case (passgroup[0]):
            mpanel1.style.display = "block";
            var panelt101 = document.getElementById("panelt101");
            panelt101.style.display = "block";
            lekc.style.display = "block";
            pop.style.display = "none";
            return false;
        break;
        
        case (passgroup[1]):
            mpanel1.style.display = "block";
            var panelt111 = document.getElementById("panelt111");
            panelt111.style.display = "block";
            lekc.style.display = "block";
            pop.style.display = "none";
            return false;
        break;

        case (passgroup[2]):
            mpanel1.style.display = "block";
            var panele101 = document.getElementById("panele101");
            panele101.style.display = "block";
            lekc.style.display = "block";
            pop.style.display = "none";
            return false;
        break;

        case (passgroup[3]):
            mpanel1.style.display = "block";
            var panely101 = document.getElementById("panely101");
            panely101.style.display = "block";
            lekc.style.display = "block";
            pop.style.display = "none";
            return false;
        break;

        default:
            alert("Неправильный пароль");
            return false;
    }
    return false;
}

// Обработка Enter в поле пароля
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('pass').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            PassCheck();
        }
    });
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/application/views/common/footer.php'; ?>