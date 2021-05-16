<div <?php post_class(array('loop-item', 'post-large-image')); ?>>
    <div class="post-thumbnail">
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php jankx_the_post_thumbnail('medium_large'); ?>
        </a>
    </div>
    <div class="post-infos">
        <h2 class="post-title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h2>

        <div class="description"><?php the_excerpt(); ?></div>
    </div>
</div>
