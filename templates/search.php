<?php
use Jankx\Widget\Renderers\PostsRenderer;
use Jankx\PostLayout\PostLayoutManager;
?>
<div class="seach-results-main">
    <?php jankx_open_container(); ?>
    <h1 class="page-header">
        <?php echo sprintf(__('Search results for "%s"', 'jankx'), get_search_query()); ?>
    </h1>
    <?php
    global $wp_query;
    if ($wp_query->have_posts()) :
        ?>
        <div class="search-resuls">
        <?php
            $layoutStyle   = apply_filters(
                "jankx_search_results_layout_style",
                PostLayoutManager::CARD
            );
            $postRenderer = PostsRenderer::prepare(
                array(
                    'query' => $GLOBALS['wp_query'],
                    'columns' => 4,
                    'show_excerpt' => true,
                    'excerpt_length' => 15,
                    'show_postdate' => true,
                )
            );
            $postRenderer->setLayout($layoutStyle);

            echo $postRenderer->render();

            // Create pagination
            echo jankx_paginate();
        ?>
        </div>
    <?php else : ?>
    <div class="no-results"><?php _e('Not found', 'jankx'); ?></div>
    <?php endif; ?>

    <?php jankx_close_container(); ?>
</div>
