<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}
 ?>
<div class="description">
    <?php
    esc_html_e(
        sprintf(
            __(
                'Jankx post layout is used to manage layout for %s.
                It useful to customize content and sidebars by selecting below layouts in this box.',
                'jankx'
            ),
            'post'
        )
    ); ?>
</div>
<select name="<?php echo esc_attr($metaKey); ?>" id="post-layouts" class="widefat">
    <option value=""><?php esc_html_e('Default Layout', 'jankx'); ?></option>

    <?php foreach ($layouts as $layout => $name) : ?>
        <option
        <?php
        if ($currentLayout === $layout) {
            echo ' selected';
        }
        ?>
                 value="<?php echo $layout; ?>"><?php echo $name; ?></option>
    <?php endforeach; ?>
</select>
