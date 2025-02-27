<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\LayoutComponent;

class Column extends LayoutComponent
{
    const COMPONENT_NAME = 'breaking_news';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function render()
    {
    }
}
