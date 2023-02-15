<?php if ($total_items > 0): ?>
<ul class="slider-indicators">
    <?php for($i=0; $i<$total_items; $i++): ?>
        <?php if ($i === 0): ?>
        <li class="active"></li>
        <?php else: ?>
        <li></li>
        <?php endif; ?>
    <?php endfor; ?>
</ul>
<?php endif; ?>
