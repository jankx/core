<h3 class="<?php echo $class_name; ?>">
    <?php if ($url) : ?>
        <a href="<?php echo $url; ?>"><?php echo $text; ?></a>
    <?php else : ?>
        <?php echo $text; ?>
    <?php endif; ?>
</h3>
