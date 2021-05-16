<article <?php post_class(); ?>>
    <?php if (apply_filters('jankx_template_page_single_header', true, $GLOBALS['post'])) : ?>
    <h1 class="jankx-header page-header"><?php the_title(); ?></h1>
    <?php endif; ?>

    <?php the_content(); ?>
</article>
