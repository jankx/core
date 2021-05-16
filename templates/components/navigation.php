<?php
if (has_nav_menu($args['theme_location']) || $args['theme_location'] === 'primary') :
    ?>
    <nav class="jankx-ui navigation navigation-<?php echo $args['theme_location']; ?>">
    <?php
    if ($args['open_container']) {
        jankx_open_container();
    }

        wp_nav_menu($args);

    if ($args['open_container']) {
        jankx_close_container();
    }
    ?>
    </nav>
<?php endif; ?>
