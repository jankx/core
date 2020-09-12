<?php
namespace Jankx\Integrate\Elementor\Widgets;

use Elementor\Widget_Base;

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
    }

    protected function render()
    {
    }

    protected function _content_template()
    {
    }
}
