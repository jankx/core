<?php

namespace Jankx\Component\Abstracts;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

abstract class MobileComponent extends Component
{
    /**
     * Summary of getPlatform
     *
     * @return array|string
     */
    public function getPlatform()
    {
        return apply_filters(
            "jankx/component/{$this->getName()}/platforms",
            static::PLATFORM
        );
    }
}
