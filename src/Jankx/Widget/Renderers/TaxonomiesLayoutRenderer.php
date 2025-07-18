<?php

namespace Jankx\Widget\Renderers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use WP_Term_Query;
use Jankx\PostLayout\PostLayoutManager;
use Jankx\TemplateAndLayout;

class TaxonomiesLayoutRenderer extends Base
{
    protected $options = array(
        'layout' => 'card',
        'taxonomies' => array()
    );

    public function buildWordPressQuery()
    {
        $args = array(
            'hide_empty' => false,
            'taxonomy' => (array)array_get($this->options, 'taxonomies', 'category'),
            'number' => array_get($this->options, 'limit'),
        );

        if (!empty($this->options['taxonomy_terms'])) {
            foreach ($this->options['taxonomy_terms'] as $taxonomy_term) {
                if (preg_match('/^(.+)_(\d{1,})$/', $taxonomy_term, $matches)) {
                    $args['include'][] = $matches[2];
                }
            }
            $args['orderby'] = 'include';
            $args['order'] = 'ASC';
            unset($args['number']);
        }
        $wp_term_query = new WP_Term_Query($args);

        return $wp_term_query;
    }

    public function render()
    {
        $postLayoutManager = PostLayoutManager::getInstance(TemplateAndLayout::getTemplateEngine());
        $layout = $postLayoutManager->createTermLayout(
            array_get($this->options, 'layout', 'card'),
            $this->buildWordPressQuery()
        );

        $layout->setOptions($this->options);

        return $layout->render(false);
    }
}
