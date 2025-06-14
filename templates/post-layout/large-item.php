<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<div <?php post_class(array('loop-item', 'post-large-image')); ?>>
    <?php do_action('jankx_post_layout_before_loop_item', $post, $data_index); ?>

    <?php jankx_template('post-layout/thumbnail', [
        'post' => $post,
        'data_index' => $data_index,
        'thumbnail_size' => 'medium_large'
    ]); ?>

    <div class="post-infos">
        <?php if ($show_title) : ?>
        <<?php echo $post_title_tag; ?> class="post-title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
        </<?php echo $post_title_tag; ?>>
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
