<div class="jankx-logo image <?php echo $class; ?>">
    <?php printf('<%s class="logo-text-wrap">', $wrap_tag); ?>
        <?php if ($url) : ?>
            <a href="<?php echo $url; ?>" style="background-image: url(<?php echo $image_url; ?>)<?php echo $logo_size_styles; ?>">
                <?php echo $text; ?>
            </a>
        <?php else : ?>
            <span><?php echo $text; ?></span>
            <img src="<?php echo $image_url; ?>" alt="<?php echo $text; ?>">
        <?php endif; ?>
    <?php printf('</%s>', $wrap_tag); ?>
</div>
