<?php

namespace Jankx\Widget\Renderers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use WP_Query;
use Jankx\PostLayout\PostLayoutManager;
use Jankx\TemplateAndLayout;
use Jankx\PostLayout\Layout\Card;

class PostsTabsRenderer extends Base
{
    protected $options = array(
        'post_type' => 'post',
        'layout' => 'card',
    );
    protected $layoutOptions = array(
        'columns' => 4,
    );

    protected function generateWordPressQuery()
    {
        $args = array(
            'post_type' => array_get($this->options, 'post_type', 'post'),
            'posts_per_page' => array_get($this->options, 'posts_per_page', 10),
        );
        return new WP_Query($args);
    }


    public function render()
    {
        $postLayoutManager = PostLayoutManager::getInstance(TemplateAndLayout::getTemplateEngine());

        $layout = $postLayoutManager->createLayout(
            'tabs',
            $this->generateWordPressQuery()
        );

        $layout->addTabs(array_get($this->options, 'tabs', []));
        $layout->addChildLayout(array_get($this->options, 'layout', Card::LAYOUT_NAME));
        $layout->setOptions($this->layoutOptions);

        return $layout->render(false);
    }
}
