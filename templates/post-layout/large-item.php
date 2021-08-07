<div <?php post_class(array('loop-item', 'post-large-image')); ?>>
    <?php do_action('jankx_post_layout_before_loop_item', $post); ?>

    <div class="post-thumbnail">
        <?php do_action('jankx_post_layout_before_loop_post_thumbnail', $post); ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php jankx_the_post_thumbnail('medium_large'); ?>
        </a>
        <?php do_action('jankx_post_layout_after_loop_post_thumbnail', $post); ?>
    </div>
    <div class="post-infos">
        <h2 class="post-title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h2>

        <div class="description"><?php the_excerpt(); ?></div>
    </div>

    <?php do_action('jankx_post_layout_after_loop_item', $post); ?>
</div>
