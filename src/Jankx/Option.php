<?php
namespace Jankx;

use Jankx\Option\Helper;

class Option
{
    public static function get($optionName, $defaultValue = null)
    {
        return Helper::getOption($optionName, $defaultValue);
    }
}
