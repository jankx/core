<?php

namespace Jankx\Widget\Widgets;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Jankx;
use WP_Widget;
use Jankx\Widget\Renderers\PostsRenderer;
use Jankx\PostLayout\PostLayoutManager;

class Posts extends WP_Widget
{
    public function __construct()
    {
        $options = array(
            'classname' => 'jankx-posts',
            'description' => __('Show posts on your site with many filters and features.', 'jankx')
        );
        parent::__construct(
            'jankx_posts',
            sprintf(
                '&lt;%s&gt; %s',
                Jankx::templateName(),
                __('Posts')
            ),
            $options
        );
    }

    public function widget($args, $instance)
    {
        $postsRenderer = PostsRenderer::prepare(array(
            'posts_per_page' => array_get($instance, 'posts_per_page', 5),
            'thumbnail_position' => array_get($instance, 'thumbnail_position', 5),
            'thumbnail_size' => array_get($instance, 'thumbnail_size', 'medium_large'),
            'show_postdate' => array_get($instance, 'show_post_date', 'no') === 'yes',
            'columns' => array_get($instance, 'columns', 4),
            'rows' => array_get($instance, 'rows', 1),
            'show_dot' => array_get($instance, 'show_carousel_pagination', 'no') === 'yes',
            'post_type' => array_get($instance, 'post_type'),
            'data_preset' => array_get($instance, 'data_preset'),
        ));
        if (array_get($instance, 'post_layout')) {
            $postsRenderer->setLayout(array_get($instance, 'post_layout'));
        }
        $content = $postsRenderer->render();
        // The posts do not have content
        if (!$content) {
            return;
        }

        echo array_get($args, 'before_widget');
        if (!empty($instance['title'])) {
            echo array_get($args, 'before_title');
            echo $instance['title'];
            echo array_get($args, 'after_title');
        }
        echo $content;
        echo array_get($args, 'after_widget');
    }

    public function getPostTypes()
    {
        $postTypes = array();

        $postTypeObjects = get_post_types(array(
            'public' => true,
        ), 'objects');
        foreach ($postTypeObjects as $postType => $object) {
            $postTypes[$postType] = $object->label;
        }
        return $postTypes;
    }

    protected function getImageSizeName($sizeName)
    {
        switch ($sizeName) {
            case 'thumbnail':
                return __('Thumbnail');
            case 'medium':
                return __('Medium');
            case 'large':
                return __('Large');
            default:
                return preg_replace_callback(array(
                    '/^(\w)/',
                    '/(\w)([\-|_]{1,})/'
                ), function ($matches) {
                    if (isset($matches[2])) {
                        return sprintf('%s ', $matches[1]);
                    } elseif (isset($matches[1])) {
                        return strtoupper($matches[1]);
                    }
                }, $sizeName);
        }
    }

    protected function getImageSizes()
    {
        $ret = array();
        foreach (get_intermediate_image_sizes() as $imageSize) {
            if (apply_filters('jankx_image_size_ignore_medium_large_size', true)) {
                if ($imageSize === 'medium_large') {
                    continue;
                }
            }
            $ret[$imageSize] = $this->getImageSizeName($imageSize);
        }
        $ret['full'] = __('Full size', 'jankx');

        return $ret;
    }

    protected function get_post_layout_options($current)
    {
        $layouts = PostLayoutManager::getLayouts(array(
            'field' => 'names'
        ));
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('post_layout'); ?>"><?php _e('Post Layouts', 'jankx'); ?></label>
            <select name="<?php echo $this->get_field_name('post_layout'); ?>"
                id="<?php echo $this->get_field_id('post_layout'); ?>" class="widefat">
                <option value=""><?php _e('Default'); ?></option>
                <?php foreach ($layouts as $layout => $name) : ?>
                    <option value="<?php echo $layout; ?>" <?php echo selected($layout, $current); ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    protected function getDataPresets($instance)
    {
        $presets = apply_filters('jankx/widget/post/data/preset', array(
            'recents' => __('Recents'),
            'related' => __('Related', 'jankx'),
        ));
        if (array_get($instance, 'post_type')) {
            return apply_filters(
                sprintf('jankx/widget/%s/data/preset', array_get($instance, 'post_type')),
                $presets,
                $instance
            );
        }
        return $presets;
    }

    public function form($instance)
    {
        $thumbnail_positions = array(
            'top' => __('Top'),
            'left' => __('Left'),
            'right' => __('Right'),
        );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo array_get($instance, 'title'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post Type', 'jankx'); ?></label>
            <select name="<?php echo $this->get_field_name('post_type'); ?>"
                id="<?php echo $this->get_field_id('post_type'); ?>" class="widefat">
                <option value=""><?php _e('Default'); ?></option>
                <?php foreach ($this->getPostTypes($instance) as $post_type => $post_type_label) : ?>
                    <option value="<?php echo $post_type; ?>" <?php echo selected($post_type, array_get($instance, 'post_type', '')); ?>><?php echo $post_type_label; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('data_preset'); ?>"><?php _e('Data Preset', 'jankx'); ?></label>
            <select name="<?php echo $this->get_field_name('data_preset'); ?>"
                id="<?php echo $this->get_field_id('data_preset'); ?>" class="widefat">
                <option value=""><?php _e('Default'); ?></option>
                <?php foreach ($this->getDataPresets($instance) as $data_preset => $position) : ?>
                    <option value="<?php echo $data_preset; ?>" <?php echo selected($data_preset, array_get($instance, 'data_preset', '')); ?>><?php echo $position; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php $this->get_post_layout_options(array_get($instance, 'post_layout')); ?>
        <p>
            <label
                for="<?php echo $this->get_field_id('thumbnail_position'); ?>"><?php _e('Thumbnail Position', 'jankx'); ?></label>
            <select name="<?php echo $this->get_field_name('thumbnail_position'); ?>"
                id="<?php echo $this->get_field_id('thumbnail_position'); ?>" class="widefat">
                <option value=""><?php _e('Default'); ?></option>
                <?php foreach ($thumbnail_positions as $thumbnail_position => $position) : ?>
                    <option value="<?php echo $thumbnail_position; ?>" <?php echo selected($thumbnail_position, array_get($instance, 'thumbnail_position', '')); ?>><?php echo $position; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('thumbnail_size'); ?>"><?php _e('Thumbnail Size', 'jankx'); ?></label>
            <select name="<?php echo $this->get_field_name('thumbnail_size'); ?>"
                id="<?php echo $this->get_field_id('thumbnail_size'); ?>" class="widefat">
                <option value=""><?php _e('Default'); ?></option>
                <?php foreach ($this->getImageSizes() as $thumbnail_size => $size) : ?>
                    <option value="<?php echo $thumbnail_size; ?>" <?php echo selected($thumbnail_size, array_get($instance, 'thumbnail_size', '')); ?>><?php echo $size; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_post_date') ?>">
                <input type="checkbox" id="<?php echo $this->get_field_id('show_post_date') ?>"
                    name="<?php echo $this->get_field_name('show_post_date'); ?>" <?php checked('yes', array_get($instance, 'show_post_date', 'no')); ?> value="yes" />
                <?php _e('Show post date', 'jankx'); ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page') ?>"><?php _e('Number of items', 'jankx'); ?></label>
            <input type="number" id="<?php echo $this->get_field_id('posts_per_page') ?>"
                name="<?php echo $this->get_field_name('posts_per_page'); ?>"
                value="<?php echo array_get($instance, 'posts_per_page', 5) ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('columns') ?>"><?php _e('Columns', 'jankx'); ?></label>
            <input type="number" class="widefat" id="<?php echo $this->get_field_id('columns') ?>"
                name="<?php echo $this->get_field_name('columns'); ?>"
                value="<?php echo array_get($instance, 'columns', 5) ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('rows') ?>"><?php _e('Rows', 'jankx'); ?></label>
            <input type="number" class="widefat" id="<?php echo $this->get_field_id('rows') ?>"
                name="<?php echo $this->get_field_name('rows'); ?>" value="<?php echo array_get($instance, 'rows', 5) ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_carousel_pagination') ?>">
                <input type="checkbox" id="<?php echo $this->get_field_id('show_carousel_pagination') ?>"
                    name="<?php echo $this->get_field_name('show_carousel_pagination'); ?>" <?php checked('yes', array_get($instance, 'show_carousel_pagination', 'no')); ?> value="yes" />
                <?php _e('Show slide pagination', 'jankx'); ?>
            </label>
        </p>
        <?php
    }
}
