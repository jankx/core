<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class Image extends Component
{
    const COMPONENT_NAME = 'image';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }
}
