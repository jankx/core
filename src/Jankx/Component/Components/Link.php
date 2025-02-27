<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class Link extends Component
{
    const COMPONENT_NAME = 'link';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function parseProps($props)
    {
        $this->props = wp_parse_args($props, array(
            'text' => '',
            'url' => '',
            'target' => '_self',
        ));
    }

    public function render()
    {
        // Check the link text or url must have a value
        if (empty($this->props['text']) && empty($this->props['url'])) {
            return;
        }
        return $this->renderViaEngine('link', $this->props, 'link', false);
    }
}
