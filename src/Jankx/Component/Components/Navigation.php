<?php
namespace Jankx\Component\Components;

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

        return $this->renderViaEngine(
            $templates,
            array(
                'args' => apply_filters(
                    "jankx_component_navigation_{$this->props['theme_location']}_args",
                    $this->props
                ),
            ),
            false
        );
    }
}
