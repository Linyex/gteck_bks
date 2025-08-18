<div class="container">
  <h1 class="section-title">Публичные таблицы</h1>
  <div class="cards-grid">
    <?php foreach ($tables as $t): ?>
      <a class="card" href="/tables/<?php echo urlencode($t['slug']); ?>">
        <div class="card-body">
          <h3><?php echo htmlspecialchars($t['title']); ?></h3>
          <div class="hint">Обновлено: <?php echo htmlspecialchars($t['updated_at']); ?></div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<style>
.cards-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px; }
.card { display:block; border: 1px solid rgba(0,0,0,.1); border-radius: 10px; padding: 10px; background: #fff; color: inherit; text-decoration: none; }
.card h3 { margin: 0 0 6px; }
.hint { color: #666; font-size: 13px }
</style>


