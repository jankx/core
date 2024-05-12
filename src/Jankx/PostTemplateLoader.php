<?php

namespace Jankx;

use Jankx\TemplateAndLayout;
use Jankx\PostLayout\Constracts\PostLayout;
use Jankx\PostLayout\PostLayoutManager;
use Jankx\PostLayout\Layout\ListLayout;

class PostTemplateLoader
{
    public function load()
    {
        add_action('jankx_template_page_archive_content', array($this, 'render'));
        add_action('jankx_template_before_post_content', array($this, 'renderPostMetas'));
    }

    public function render($page = 'home')
    {
        $layoutManager = PostLayoutManager::getInstance(
            TemplateAndLayout::getTemplateEngine()->getId()
        );
        $layoutStyle   = apply_filters(
            "jankx_post_layout_page_{$page}_style",
            ListLayout::LAYOUT_NAME
        );

        // Create post layout style instance
        $postLayoutInstance     = $layoutManager->createLayout($layoutStyle, $GLOBALS['wp_query']);

        // $postLayoutInstance->setOptions(array(
        //     'columns' => 4,
        // ));

        // Render posts
        if (is_a($postLayoutInstance, PostLayout::class)) {
            echo $postLayoutInstance->render();
        }
    }

    public function renderPostMetas()
    {
        jankx_template('common/post-metas');
    }
}
