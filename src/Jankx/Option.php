<?php

namespace Jankx;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Adapter\Options\Helper;

class Option
{
    public static function get($optionName, $defaultValue = null)
    {
        return Helper::getOption($optionName, $defaultValue);
    }
}
