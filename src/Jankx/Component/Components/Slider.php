<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class Slider extends Component
{
    const COMPONENT_NAME = 'slider';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }
}
