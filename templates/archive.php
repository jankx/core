<?php
/**
 * Content page index
 */
use Jankx\PostLayout\PostLayoutManager;
use Jankx\Widget\Renderers\PostsRenderer;

/**
 * The index page content will be render via action hook
 *
 * Hooked:
 *  - N/A
 */
$template_hook = 'jankx_template_archive_content';
if (is_post_type_archive()) {
    $template_hook = 'jankx_template_page_post_type_archive_content';
} elseif (is_tax()) {
    $template_hook = 'jankx_template_page_taxonomy_content';
}

if (has_action($template_hook)) {
    do_action($template_hook, get_queried_object());
} else {
    jankx_template(
        'common/archive-page-header',
        array('queried_object' => get_queried_object())
    );

    $postRenderer = PostsRenderer::prepare(
        array(
            'query' => $GLOBALS['wp_query'],
            'columns' => 4,
            'show_excerpt' => true,
            'show_paginate' => true,
            'excerpt_length' => 15,
            'show_postdate' => true,
            'thumbnail_size' => 'medium'
        )
    );
    $postRenderer->setLayout(PostLayoutManager::CARD);

    echo $postRenderer->render();
}
