<?php
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
if (has_nav_menu($args['theme_location'])) :
    ?>
    <nav class="jankx-ui navigation <?php echo implode(' ', $menu_classes); ?>">
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
