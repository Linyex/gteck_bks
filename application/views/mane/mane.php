<div class="container-fluid">
  <!-- Hero Section with Slider -->
  <section class="hero-section" data-aos="fade-up">
    <div class="row">
      <div class="col-lg-8">
        <div class="hero-slider">
          <div class="slider-item active">
            <div class="slider-div-image">
              <div class="animated-background">
                <div class="floating-elements">
                  <div class="element">🎓</div>
                  <div class="element">📚</div>
                  <div class="element">⚡</div>
                  <div class="element">💡</div>
                </div>
                <div class="gradient-overlay"></div>
                <div class="content-overlay">
                  <h2>Приемная кампания 2025</h2>
                  <p>Добро пожаловать в Гомельский торгово-экономический колледж Белкоопсоюза</p>
                </div>
              </div>
            </div>
          </div>
          <div class="slider-item">
            <div class="slider-div-image">
              <div class="animated-background">
                <div class="floating-elements">
                  <div class="element">🏛️</div>
                  <div class="element">📖</div>
                  <div class="element">🔬</div>
                  <div class="element">🎯</div>
                </div>
                <div class="gradient-overlay"></div>
                <div class="content-overlay">
                  <h2>Учреждение образования "Гомельский торгово-экономический колледж" Белкоопсоюза</h2>
                </div>
              </div>
            </div>
          </div>
          <div class="slider-item">
            <div class="slider-div-image">
              <div class="animated-background">
                <div class="floating-elements">
                  <div class="element">⚖️</div>
                  <div class="element">🛒</div>
                  <div class="element">📊</div>
                  <div class="element">💻</div>
                </div>
                <div class="gradient-overlay"></div>
                <div class="content-overlay">
                  <h2>Обучение по специальностям:</h2>
                  <ul>
                    <li>Правоведение</li>
                    <li>Торговая деятельность</li>
                    <li>Бух. учет, анализ и контроль</li>
                    <li>Планово-экономическая и аналитическая деятельность</li>
                    <li>Разработка и сопровождение программного обеспечения информационных систем</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="hero-sidebar">
          <!-- Search -->
          <div class="search-widget" data-aos="fade-left">
            <h4><i class="fa fa-search"></i> Поиск по сайту</h4>
            <form id="searchForm" method="POST">
              <div class="search-input">
                <input type="text" name="Search_text" id="Search_text" placeholder="Поиск по сайту..">
                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
              </div>
            </form>
            <div class="search-results" id="searchResults"></div>
          </div>
          <!-- Quick Info -->
          <div class="quick-info" data-aos="fade-left" data-aos-delay="100">
            <h4><i class="fa fa-info-circle"></i> Дополнительная информация</h4>
            <?php if (!empty($lastzamena['zamena_file'])): ?>
              <div class="info-item">
                <a href="/<?php echo ($lastzamena['zamena_file']) ?>" target="_blank" class="info-link">
                  <i class="fa fa-calendar"></i>
                  <span>Изменения в расписании<br><?php echo $lastzamena['zamena_text'] ?></span>
                </a>
              </div>
            <?php else: ?>
              <div class="info-item">
                <span class="info-text"><i class="fa fa-check"></i> Изменений в расписании нет</span>
              </div>
            <?php endif; ?>
            
            <div class="info-item">
              <a href="https://docs.google.com/spreadsheets/d/1YGUg5U5KBQWBqi88gi8SCAEQTnf_CtDZs_U1usbUj7o/edit?usp=sharing" target="_blank" class="info-link">
                <i class="fa fa-chart-line"></i>
                <span>Ход приёма документов</span>
              </a>
            </div>
            
            <div class="info-item">
              <a href="/assets/files/№ 88 от 27.03.2025 Об утверждении Порядка приема на 2025 год.pdf" target="_blank" class="info-link">
                <i class="fa fa-file-pdf-o"></i>
                <span>Порядок приёма абитуриентов 2025 год</span>
              </a>
            </div>
          </div>

          <!-- Contacts -->
          <div class="contacts-widget" data-aos="fade-left" data-aos-delay="200">
            <h4><i class="fa fa-phone"></i> Контакты</h4>
            <div class="contact-info">
              <p><i class="fa fa-map-marker"></i> 246017, г. Гомель, ул. Привокзальная, 4</p>
              <p><i class="fa fa-envelope"></i> gtec@mail.gomel.by</p>
              <p><i class="fa fa-phone"></i> 8(0232) 33-70-02</p>
              <p><i class="fa fa-phone"></i> Приемная комиссия: +375 232 20-22-14</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- News Section -->
  <section class="news-section" data-aos="fade-up">
    <div class="row">
      <div class="col-12">
        <div class="section-header">
          <h2 class="section-title">Новости</h2>
          <?php if($total_news > $limit): ?>
            <a href="/news" class="btn-view-all">
              <i class="fa fa-newspaper-o"></i>
              Все новости (<?php echo $total_news ?>)
            </a>
          <?php endif; ?>
        </div>
        <hr class="section-divider">
      </div>
    </div>
    
    <?php if(empty($news)): ?>
      <div class="row">
        <div class="col-12">
          <div class="no-news">
            <i class="fa fa-newspaper-o"></i>
            <p>На данный момент нет новостей</p>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="news-grid">
        <?php foreach($news as $item): ?>
        <div class="news-card" data-aos="fade-up">
          <div class="news-image">
            <a href="/<?php echo $item['news_image'] ?>" data-fancybox="gallery">
              <img src="/<?php echo $item['news_image'] ?>" alt="<?php echo $item['news_title'] ?>">
              <div class="news-overlay">
                <i class="fa fa-search"></i>
              </div>
            </a>
          </div>
          <div class="news-content">
            <div class="news-meta">
              <span class="news-date">
                <i class="fa fa-calendar"></i>
                <?php echo date("d.m.Y", strtotime($item['news_date_add'])) ?>
              </span>
              <span class="news-category">
                <a href="/news/category/mane/<?php echo $item['category_name'] ?>">
                  <?php echo $item['category_text'] ?>
                </a>
              </span>
            </div>
            <h3 class="news-title">
              <a href="/news/view/mane/<?php echo $item['news_id'] ?>">
                <?php echo $item['news_title'] ?>
              </a>
            </h3>
            <div class="news-excerpt">
              <?php 
                $string = mb_substr($item['news_text'], 0, 250);
                $string = rtrim($string, "!,.-");
                echo htmlspecialchars_decode($string) . " …"; 
              ?>
            </div>
            <div class="news-footer">
              <a href="/news/view/mane/<?php echo $item['news_id'] ?>" class="read-more">
                Подробнее <i class="fa fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      
      <?php if(isset($pagination)): ?>
      <div class="pagination-wrapper" data-aos="fade-up">
        <?php echo $pagination ?> 
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </section>
</div>

<script>
$(document).ready(function() {
  // Search functionality
  $('#searchForm').on('submit', function(e) {
    e.preventDefault();
    var searchText = $('#Search_text').val();
    if (searchText.trim() !== '') {
      window.location.href = '/search/?&' + encodeURIComponent(searchText);
    }
  });

  // Hero slider functionality
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slider-item');
  const totalSlides = slides.length;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove('active');
      if (i === index) {
        slide.classList.add('active');
      }
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
  }

  // Auto-advance slides
  if (totalSlides > 1) {
    setInterval(nextSlide, 5000);
  }

  // Initialize first slide
  showSlide(0);

  // AJAX search functionality
  $('#Search_text').on('input', function() {
    var searchText = $(this).val();
    if (searchText.length >= 3) {
      $.ajax({
        url: '/main/ajax',
        method: 'POST',
        data: {
          Search_text2: searchText
        },
        success: function(response) {
          var data = JSON.parse(response);
          var resultsHtml = '';
          
          if (data.status === 'success' && data.success.length > 0) {
            data.success.forEach(function(result) {
              resultsHtml += '<div class="search-result-item">';
              resultsHtml += '<h4>' + result.title + '</h4>';
              resultsHtml += '<p>' + result.fullstr + '</p>';
              resultsHtml += '<small>Найдено: ' + result.total + ' результатов</small>';
              resultsHtml += '</div>';
            });
          } else {
            resultsHtml = '<div class="no-results">По вашему запросу ничего не найдено</div>';
          }
          
          $('#searchResults').html(resultsHtml);
        },
        error: function() {
          $('#searchResults').html('<div class="error">Ошибка поиска</div>');
        }
      });
    } else {
      $('#searchResults').empty();
    }
  });
});
</script>

<style>
.search-result-item {
  background: rgba(30, 41, 59, 0.8);
  border-radius: 8px;
  padding: 10px;
  margin: 5px 0;
  border: 1px solid rgba(59, 130, 246, 0.2);
}

.search-result-item h4 {
  color: #f1f5f9;
  font-size: 0.9rem;
  margin-bottom: 5px;
}

.search-result-item p {
  color: #cbd5e1;
  font-size: 0.8rem;
  margin-bottom: 5px;
}

.search-result-item small {
  color: #94a3b8;
  font-size: 0.7rem;
}

.no-results, .error {
  color: #94a3b8;
  text-align: center;
  padding: 10px;
  font-style: italic;
}

.error {
  color: #ef4444;
}
</style>
