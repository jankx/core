<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class Dropdown extends Component
{
    const COMPONENT_NAME = 'dropdown';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function parseProps($props)
    {
        $this->props = wp_parse_args($props, array(
        ));
    }

    public function render()
    {
    }
}
