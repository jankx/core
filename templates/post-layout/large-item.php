<div <?php post_class(array('loop-item', 'post-large-image')); ?>>
    <?php do_action('jankx_post_layout_before_loop_item', $post, $data_index); ?>

    <div class="post-thumbnail">
        <?php do_action('jankx_post_layout_before_loop_post_thumbnail', $post, $data_index); ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php jankx_the_post_thumbnail('medium_large'); ?>
        </a>
        <?php do_action('jankx_post_layout_after_loop_post_thumbnail', $post, $data_index); ?>
    </div>

    <div class="post-infos">
        <?php if ($show_title) : ?>
        <<?php $this->e($post_title_tag); ?> class="post-title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </<?php $this->e($post_title_tag); ?>>
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

        <div class="post-exceprt"><?php the_excerpt(); ?></div>
    </div>

    <?php do_action('jankx_post_layout_after_loop_item', $post, $data_index); ?>
</div>
