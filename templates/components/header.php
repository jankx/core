<header <?php echo $attributes; ?>>
    <?php do_action('jankx_component_before_header_content'); ?>
    <?php jankx_open_container('c-header-container', 'jankx_component_header_container'); ?>

        <?php do_action('jankx_component_before_header'); ?>
        <?php echo $content; ?>
        <?php do_action('jankx_component_after_header'); ?>

    <?php jankx_close_container('jankx_component_header_container'); ?>
    <?php do_action('jankx_component_after_header_content'); ?>
</header>
