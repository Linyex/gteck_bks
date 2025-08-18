<div class="container">
  <h1 class="section-title"><?php echo htmlspecialchars($item['title']); ?></h1>
  <?php if (!empty($item['description'])): ?>
    <p class="lead"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
  <?php endif; ?>
  <div class="table-responsive">
    <table class="table table-stack">
      <tbody>
        <?php
          $rows = $item['rows_json'] ?? [];
          $merges = $item['merges_json'] ?? [];
          $hidden = [];
          foreach ($rows as $rIdx => $row) {
            echo '<tr>';
            foreach ($row as $cIdx => $cell) {
              $key = $rIdx.'_'.$cIdx;
              if (!empty($hidden[$key])) continue;
              $rowspan = 1; $colspan = 1;
              foreach ($merges as $m) {
                $r1 = (int)$m['r1']; $c1 = (int)$m['c1'];
                $r2 = (int)$m['r2']; $c2 = (int)$m['c2'];
                if ($rIdx === $r1 && $cIdx === $c1) {
                  $rowspan = max(1, $r2 - $r1 + 1);
                  $colspan = max(1, $c2 - $c1 + 1);
                  for ($rr=$r1; $rr<=$r2; $rr++) {
                    for ($cc=$c1; $cc<=$c2; $cc++) {
                      if ($rr === $r1 && $cc === $c1) continue;
                      $hidden[$rr.'_'.$cc] = true;
                    }
                  }
                  break;
                }
              }
              echo '<td'.($rowspan>1?' rowspan="'.$rowspan.'"':'').($colspan>1?' colspan="'.$colspan.'"':'').'>';
              echo htmlspecialchars((string)$cell);
              echo '</td>';
            }
            echo '</tr>';
          }
        ?>
      </tbody>
    </table>
  </div>
</div>


