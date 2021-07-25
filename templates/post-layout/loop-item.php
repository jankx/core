<div <?php post_class('loop-item'); ?>>
    <?php do_action('jankx_post_layout_before_loop_item', $post); ?>

    <?php if ($show_thumbnail) : ?>
    <div class="post-thumbnail">
        <?php do_action('jankx_post_layout_before_loop_post_thumbnail', $post); ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php jankx_the_post_thumbnail($thumbnail_size); ?>
        </a>
        <?php do_action('jankx_post_layout_after_loop_post_thumbnail', $post); ?>
    </div>
    <?php endif; ?>

    <div class="post-infos">
        <?php if ($show_title) : ?>
        <h2 class="post-title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </h2>
        <?php endif; ?>

        <?php if ($show_excerpt) : ?>
        <div class="post-exceprt"><?php the_excerpt(); ?></div>
        <?php endif; ?>
        <?php if (!empty($post_meta_features)) : ?>
            <ul class="post-metas">
            <?php foreach ($post_meta_features as $feature => $value) : ?>
                <li class=<?php echo $feature; ?>>
                <?php
                    do_action("jankx_post_layout_meta_before_{$feature}");

                    echo $this->e($this->get_meta_value($value, $feature));

                    do_action("jankx_post_layout_meta_after_{$feature}");
                ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php do_action('jankx_post_layout_after_loop_item', $post); ?>
</div>
