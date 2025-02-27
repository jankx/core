<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class BreakingNews extends Component
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
