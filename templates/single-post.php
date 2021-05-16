<article <?php post_class(); ?>>
    <?php if (apply_filters('jankx_template_page_single_post_header', true, $GLOBALS['post'])) : ?>
    <h1 class="jankx-header page-header"><?php the_title(); ?></h1>
    <?php endif; ?>

    <?php do_action('jankx_template_before_post_content'); ?>

    <div class="post-content">
        <?php the_content(); ?>
    </div>

    <?php do_action('jankx_template_after_post_content'); ?>
</article>
