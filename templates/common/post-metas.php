<ul class="post-metas">
    <li class="post-date">
        <?php do_action('jankx_post_layout_meta_before_post_date'); ?>
        <?php the_date(); ?>
    </li>
    <li class="post-author">
        <?php do_action('jankx_post_layout_meta_before_post_author'); ?>
        <?php the_author_posts_link(); ?>
    </li>
    <li class="comment-counts">
        <?php do_action('jankx_post_layout_meta_before_post_comments'); ?>
        <?php
            $count = get_comments_number();
            printf(_n('%s comment', '%s comments', $count, 'jankx'), number_format_i18n($count));
        ?>
    </li>
</ul>
