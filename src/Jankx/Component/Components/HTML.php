<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class HTML extends Component
{
    const COMPONENT_NAME = 'html';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function parseProps($props)
    {
        $this->props = wp_parse_args($props, array(
            'content' => '',
        ));
    }

    public function render()
    {
        /**
         * Sanitizes content for allowed HTML tags for post content.
         * @link https://developer.wordpress.org/reference/functions/wp_kses_post/
         */
        return wp_kses_post(
            $this->props['content']
        );
    }
}
