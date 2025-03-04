<div <?php echo jankx_generate_html_attributes([
    'class' => apply_filters('jankx/thumbnail/classes', ['post-thumbnail'], $post, $data_index),
]); ?>>
    <?php do_action('jankx_post_layout_before_loop_post_thumbnail', $post, $data_index); ?>
    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
        <?php if (empty($content)): ?>
        <?php jankx_the_post_thumbnail($thumbnail_size); ?>
        <?php else: ?>
            <?php echo $content; ?>
        <?php endif; ?>
    </a>
    <?php do_action('jankx_post_layout_after_loop_post_thumbnail', $post, $data_index); ?>
</div>
