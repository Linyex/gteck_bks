<?php echo $header ?>
<h2>Вести колледжа</h2>
<hr>
<div class="spiski">
    <ul style="padding: 0; line-height: 30px;">
        <?php foreach($filesa as $item): ?>
            <li><a href="/<?php echo $item['files_file'] ?>" download><?php echo $item['files_text'] ?></a></li>
        <?php endforeach; ?>
        <?php if($empty1 == (int)0): ?>
            <li style="text-align: center;">На данный момент нет вестей</li>
        <?php endif; ?>
    </ul>
</div>
<?php echo $footer ?>