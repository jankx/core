<div class="jankx-logo text <?php echo $class; ?>">
    <?php printf('<%s class="logo-text-wrap">', $wrap_tag); ?>
        <?php if ($url) : ?>
            <a href="<?php echo $url; ?>"><?php echo $text; ?></a>
        <?php else : ?>
            <?php echo $text; ?>
        <?php endif; ?>
    <?php printf('</%s>', $wrap_tag); ?>
</div>