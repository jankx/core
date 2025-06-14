<?php

namespace Jankx\Component\Components;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Component\Abstracts\Component;

class Navigation extends Component
{
    const COMPONENT_NAME = 'nav';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function parseProps($props)
    {
        $this->props = wp_parse_args($props, array(
            'theme_location' => '',
            'open_container' => false,
            'sticky' => false,
        ));
    }

    public function render()
    {
        if (empty($this->props['theme_location'])) {
            return;
        }

        $templates = array(
            "navigation/{$this->props['theme_location']}",
            'navigation'
        );

        $args = apply_filters(
            "jankx_component_navigation_{$this->props['theme_location']}_args",
            $this->props
        );

        $menu_classes = [sprintf('navigation-%s', $this->props['theme_location'])];
        if (array_get($this->props, 'sticky') === true) {
            $menu_classes[] = 'sticky-menu';
        }

        return $this->renderViaEngine(
            $templates,
            array(
                'args' => $args,
                'menu_classes' => $menu_classes,
            ),
            false
        );
    }
}
