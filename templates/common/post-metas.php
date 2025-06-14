<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}
 ?>
<ul class="post-metas">
    <?php if (in_array('post_date', $enabled_post_metas)) : ?>
    <li class="post-date">
        <?php do_action('jankx_post_layout_meta_before_post_date'); ?>
        <span class="meta-label"><i class="<?php echo jankx_get_font_icon('calendar'); ?>"></i></span><?php the_date(); ?>
    </li>
    <?php endif; ?>

    <?php if (in_array('author', $enabled_post_metas)) : ?>
    <li class="post-author">
        <?php do_action('jankx_post_layout_meta_before_post_author'); ?>
        <span class="meta-label"><?php _e('Author'); ?>:</span><?php the_author_posts_link(); ?>
    </li>
    <?php endif; ?>

    <?php if (in_array('categories', $enabled_post_metas)) : ?>
    <li class="post-categories">
        <?php do_action('jankx_post_layout_meta_before_post_categories'); ?>
        <span class="meta-label"><?php _e('Categories'); ?>:</span>
        <?php the_category(); ?>
    </li>
    <?php endif; ?>

    <?php if (in_array('comments', $enabled_post_metas)) : ?>
    <li class="comment-counts">
        <?php do_action('jankx_post_layout_meta_before_post_comments'); ?>
        <span class="meta-label"><?php _e('Comments'); ?>:</span><?php
            $count = get_comments_number();
            printf('%s %s', number_format_i18n($count), strtolower(_n('Comment', 'Comments', $count)));
        ?>
    </li>
    <?php endif; ?>
</ul>
