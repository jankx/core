<?php

namespace Jankx\Widget\Renderers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use WP_Query;
use Jankx\PostLayout\PostLayoutManager;
use Jankx\TemplateAndLayout;
use Jankx\PostLayout\Layout\Card;

class PostTypePostsRenderer extends Base
{
    protected $featuredMetaKey;
    protected $featuredMetaValue;

    protected $options = array(
        'layout' => 'card',
        'post_type' => 'post',
        'posts_per_page' => 10,
        'data_type' => 'recents',
    );
    protected $layoutOptions = array(
        'columns' => 4,
    );

    public function setFeaturedMetaKey($key)
    {
        if ($key) {
            $this->featuredMetaKey = $key;
        }
    }

    public function setFeaturedMetaValue($value)
    {
        if ($value) {
            $this->featuredMetaValue = $value;
        }
    }

    protected function createDataTypeArgs($args)
    {
        switch (array_get($this->options, 'data_type')) {
            case 'featured':
                if ($this->featuredMetaKey) {
                    $featuredMetaQuery = array(
                        'key' => $this->featuredMetaKey,
                    );
                    if (empty($this->featuredMetaValue)) {
                        $featuredMetaQuery['compare'] = 'EXISTS';
                    } else {
                        $featuredMetaQuery['value'] = $this->featuredMetaValue;
                        $featuredMetaQuery['compare'] = '=';
                    }
                    $args['meta_query'][] = $featuredMetaQuery;
                }
                break;
            case 'recents':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
        }
        return $args;
    }

    protected function generateWordPressQuery()
    {
        $postType = array_get($this->options, 'post_type', 'post');
        $preQuery = apply_filters("jankx/{$postType}/pre/query", null, $this);
        if (is_a($preQuery, WP_Query::class)) {
            return $preQuery;
        }

        $args = array(
            'post_type' => $postType,
            'posts_per_page' => array_get($this->options, 'posts_per_page', 10),
        );

        if (array_get($this->options, 'show_paginate')) {
            $args['paged'] = get_query_var('paged', 1);
        }

        $args  = $this->createDataTypeArgs($args);

        return new WP_Query(apply_filters("jankx/{$postType}/query/args", $args, $this));
    }

    public function render()
    {
        $postLayoutManager = PostLayoutManager::getInstance(TemplateAndLayout::getTemplateEngine());
        if (!$postLayoutManager) {
            return;
        }

        $wp_query = $this->generateWordPressQuery();

        $layout = $postLayoutManager->createLayout(
            array_get($this->options, 'layout', Card::LAYOUT_NAME),
            $wp_query
        );

        $layout->setOptions($this->layoutOptions);

        return $layout->render(false);
    }
}
