<?php

namespace Jankx\Extra\PageSpeed;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Interfaces\GooglePagespeedModuleInterface;

abstract class BaseModule implements GooglePagespeedModuleInterface
{
    public function startHook()
    {
        return 'jankx/template/page/render/start';
    }

    public function endHook()
    {
        return 'jankx/template/page/render/end';
    }
}
