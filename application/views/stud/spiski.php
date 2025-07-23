<h2>Списки групп</h2>
<hr>
<span>
    <h3 class="c-font-blue">Дневное отделение:</h3>
    <div class="spiski">
        <p>На основе общего базового образования: </p>
        <ul style="line-height: 30px;">
           <?php foreach($listgr as $item): ?>
                <?php if ($item['listgr_status'] == (int)1): ?>
                    <li><a href="/<?php echo $item['listgr_file'] ?>" target="_blank" download><?php echo $item['listgr_text'] ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if($empty1 == (int)0): ?>
                <li style="text-align: center;">На данный момент нет списков</li>
            <?php endif; ?>
        </ul>
        <p>На основе общего среднего образования: </p>
        <ul style="line-height: 30px;">
           <?php foreach($listgr as $item): ?>
                <?php if ($item['listgr_status'] == (int)2): ?>
                    <li><a href="/<?php echo $item['listgr_file'] ?>" target="_blank" download><?php echo $item['listgr_text'] ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if($empty2 == (int)0): ?>
                <li style="text-align: center;">На данный момент нет списков</li>
            <?php endif; ?>
        </ul>
        <p class="c-font-blue"><b>Заочное отделение:</b></p>
        <p>На основе общего среднего образования: </p>
        <ul style="line-height: 30px;">
           <?php foreach($listgr as $item): ?>
                <?php if ($item['listgr_status'] == (int)3): ?>
                    <li><a href="/<?php echo $item['listgr_file'] ?>" target="_blank" download><?php echo $item['listgr_text'] ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if($empty3 == (int)0): ?>
                <li style="text-align: center;">На данный момент нет списков</li>
            <?php endif; ?>
        </ul>
        <p>На основе профессионально-технического образования (ПТО):</p>
        <ul style="line-height: 30px;">
           <?php foreach($listgr as $item): ?>
                <?php if ($item['listgr_status'] == (int)4): ?>
                    <li><a href="/<?php echo $item['listgr_file'] ?>" target="_blank" download><?php echo $item['listgr_text'] ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if($empty4 == (int)0): ?>
                <li style="text-align: center;">На данный момент нет списков</li>
            <?php endif; ?>
        </ul>
    </div>
</span>  