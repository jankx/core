<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class Template extends Component
{
    const COMPONENT_NAME = 'template_file';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function parseProps($props)
    {
        $this->props = wp_parse_args($props, array(
            'template' => null,
            'data' => array(),
        ));
    }

    public function render()
    {
        if (empty($this->props['template'])) {
            return;
        }
        return $this->renderViaEngine(
            $this->props['template'],
            $this->props['data'],
            null, // Context
            false // Do not echo template
        );
    }
}
