<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\LayoutComponent;

class Row extends LayoutComponent
{
    const COMPONENT_NAME = 'row';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function parseProps($props)
    {
        $this->props = wp_parse_args($props, array(
            'items' => 4,
            'extra_items' => 0,
            'tablet_items' => 0,
            'mobile_items' => 0,
        ));
    }

    public function render()
    {
    }
}
