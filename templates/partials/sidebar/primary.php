<?php
if (!isset($sidebar_name)) {
    $sidebar_name = 'primary';
}
?>
<aside id="jankx-<?php echo $sidebar_name; ?>-sidebar" class="sidebar <?php echo $sidebar_name; ?>">
    <?php
    if (is_active_sidebar($sidebar_name)) {
        dynamic_sidebar($sidebar_name);
    } elseif (current_user_can('edit_theme_options')) {
        printf(
            __('Please add the widgets to this sidebar at <a href="%s">Widget Dashboard</a>. Only you see this message because you are the moderator.', 'jankx'),
            admin_url('widgets.php')
        );
    }
    ?>
</aside>
