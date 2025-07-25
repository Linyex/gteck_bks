<!-- Hero Section для поиска -->
<section class="search-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-content text-center" data-aos="fade-up">
                    <h1 class="hero-title"><span class="hero-icon">🔍</span>Поиск по сайту</h1>
                    <p class="hero-subtitle">Найдите нужную информацию быстро и удобно</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Доступность</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100+</span>
                            <span class="stat-label">Страниц</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">∞</span>
                            <span class="stat-label">Возможностей</span>
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
        <!-- Поиск -->
        <div class="content-section" data-aos="fade-up">
            <h2 class="section-title">🔎 Поиск информации</h2>
            <div class="search-section">
                <form id="searchForm" method="POST" class="modern-search-form">
                    <div class="search-input-group">
                        <input type="text" class="search-input" name="Search_text" id="Search_text" placeholder="Введите запрос для поиска...">
                        <button type="submit" class="search-button">
                            <i class="fa fa-search"></i>
                            <span>Найти</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Результаты поиска -->
        <div class="content-section" data-aos="fade-up" data-aos-delay="200">
            <div id="showtitle"></div>
            <div id="showres" class="search-results"></div>
        </div>
    </div>
</div>

<style>
/* Современные стили для поиска */
.search-section {
    max-width: 600px;
    margin: 0 auto;
}

.modern-search-form {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.search-input-group {
    display: flex;
    gap: 15px;
    align-items: center;
}

.search-input {
    flex: 1;
    padding: 12px 20px;
    border: none;
    outline: none;
    font-size: 16px;
}
#but {
    padding: 12px 20px;
    background: #8B5CF6;
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}
#but:hover {
    background: #7C3AED;
}
.news-about {
    margin-top: 10px;
}
.news-about a {
    color: #8B5CF6;
    text-decoration: none;
    font-weight: bold;
}
.news-about a:hover {
    text-decoration: underline;
}
</style>

<script>
    var mytext = decodeURIComponent(location.search.substr(1)).split('&');
    
    mytext.splice(0, 1);
    var result = mytext[0];
    if (result != undefined) {
        var searchpole = document.getElementById("Search_text");
        searchpole.value = result;
    }
    
    $('#searchForm').ajaxForm({
        url: '/search/mane/ajax',
        dataType: 'json',
        success: function(data) {
            switch (data.status) {
                case 'error':
                    $("#showres").html("");
                    $("#showtitle").html("<div style='display: table; width: 100%; padding: 10px; font-size: 18px;'>" + data.error + "</div>");
                    break;
                case 'success':
                    $("#showres").html("");
                    var total = data.success[0]['total'];
                    $("#showtitle").html("<div style='display: table; width: 100%; padding: 10px; font-size: 18px;'>" + "По запросу '" + data.success[0]['stroka'] + "', было найдено " + data.success[0]['total'] + " похожих результатов:" + "</div>");
                    for (var i = 0; i <= total; i++) {
                        $("#showres").append("<div id='po"+ (i+1) + "' style='display: table; width: 100%; padding: 10px;'>"+ (i+1) + ". "+ data.success[i]['fullstr'] + "<div class='news-about'><a href='/"+ data.success[i]['silks'] + "' target='_blank'>Подробнее</a></div>" + "</div>");
                    }
                    break;
            }
        },
        beforeSubmit: function(arr, $form, options) {
            $('button[type=submit]').prop('disabled', true);
        }
    });
</script>
