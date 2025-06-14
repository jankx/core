<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}
 ?>
<article <?php post_class(); ?>>
    <?php if (apply_filters('jankx_template_page_single_post_header', true, $GLOBALS['post'])) : ?>
    <h1 class="jankx-header page-header"><?php the_title(); ?></h1>
    <?php endif; ?>

    <?php do_action('jankx/post/content/before'); ?>

    <div class="post-content entry-content">
        <?php the_content(); ?>
    </div>

    <?php do_action('jankx/post/content/after'); ?>
</article>
