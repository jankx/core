<?php
namespace Jankx;

class GlobalVariables
{
    protected static $vars;

    public static function set($var, $value)
    {
        static::$vars[$var] = apply_filters(
            'jankx_global_set_variable',
            $value,
            $var
        );

        return true;
    }

    public static function get($var, $defaultValue = null)
    {
        if (isset(static::$vars[$var])) {
            return static::$vars[$var];
        }
        return $defaultValue;
    }
}
