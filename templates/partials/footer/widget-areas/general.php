<div class="jankx-footer-widget widget-area-<?php echo $index; ?>">
    <?php
    if (is_active_sidebar("footer_{$index}")) {
        dynamic_sidebar("footer_{$index}");
    } elseif (current_user_can('edit_theme_options')) {
        printf(
            __('Please add the widgets to this sidebar at <a href="%s">Widget Dashboard</a>. Only you see this message because you are the moderator.', 'jankx'),
            admin_url('widgets.php')
        );
    }
    ?>
</div>