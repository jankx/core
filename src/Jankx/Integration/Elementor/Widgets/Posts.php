<?php
namespace Jankx\Integration\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Jankx\PostLayout\PostLayoutManager;
use Jankx\Widget\Renderers\PostsRenderer;

class Posts extends Widget_Base
{
    public function get_name()
    {
        return 'jankx_posts';
    }

    public function get_title()
    {
        return __('Jankx Posts', 'jankx');
    }

    public function get_icon()
    {
        return 'eicon-post-list';
    }

    public function get_categories()
    {
        return array('theme-elements', 'jankx');
    }

    protected function _register_controls()
    {
        $postLayout = PostLayoutManager::getInstance();

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'jankx'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'widget_title',
            [
                'label' => __('Title', 'jankx'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Recent Posts', 'jankx'),
                'placeholder' => __('Input widget title', 'jankx'),
            ]
        );

        $taxQuery = array('taxonomy' => 'category', 'fields' => 'id=>name', 'hide_empty' => false);
        $postCats = version_compare($GLOBALS['wp_version'], '4.5.0') >= 0
            ? get_terms($taxQuery)
            : get_terms($taxQuery['taxonomy'], $taxQuery);

        $this->add_control(
            'post_categories',
            [
                'label' => __('Post Categories', 'jankx'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $postCats,
                'default' => '',
            ]
        );

        $taxQuery = array('taxonomy' => 'post_tag', 'fields' => 'id=>name', 'hide_empty' => false);
        $postTags = version_compare($GLOBALS['wp_version'], '4.5.0') >= 0
            ? get_terms($taxQuery)
            : get_terms($taxQuery['taxonomy'], $taxQuery);

        $this->add_control(
            'post_tags',
            [
                'label' => __('Post Tags', 'jankx'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $postTags,
                'default' => 'none',
            ]
        );

        $this->add_control(
            'post_layout',
            [
                'label' => __('Layout', 'jankx'),
                'type' => Controls_Manager::SELECT,
                'default' => PostLayoutManager::LIST_LAYOUT,
                'options' => $postLayout->getLayouts(array(
                    'type' => 'names'
                )),
            ]
        );

        $this->add_control(
            'show_view_all_link',
            [
                'label' => __('View All URL', 'jankx'),
                'type' => Controls_Manager::URL,
                'default' => array(),
            ]
        );

        $this->add_control(
            'show_post_thumbnail',
            [
                'label' => __('Show Thumbnail', 'jankx'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'jankx'),
                'label_off' => __('Hide', 'jankx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_post_excerpt',
            [
                'label' => __('Show Excerpt', 'jankx'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'jankx'),
                'label_off' => __('Hide', 'jankx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => __('Number of Posts', 'jankx'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 5,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $postsRenderer = PostsRenderer::prepare(array(
            'show_thumbnail' => $settings['show_post_thumbnail'],
            'show_expert' => $settings['show_post_excerpt'],
            'categories' => $settings['post_categories'],
            'tags' => $settings['post_tags'],
            'header_text' => $settings['widget_title'],
            'view_all_url' => $settings['show_view_all_link'],
            'layout' => array_get($settings, 'post_layout', PostLayoutManager::LIST_LAYOUT),
            'limit' => $settings['limit'],
        ));

        echo $postsRenderer->render();
    }

    protected function _content_template()
    {
    }
}
