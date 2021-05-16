<aside id="jankx-secondary-sidebar" class="sidebar secondary">
    <?php
    if (is_active_sidebar('secondary')) {
        dynamic_sidebar('secondary');
    } elseif (current_user_can('edit_theme_options')) {
        printf(
            __('Please add the widgets to this sidebar at <a href="%s">Widget Dashboard</a>. Only you see this message because you are the moderator.', 'jankx'),
            admin_url('widgets.php')
        );
    }
    ?>
</aside>