<?php
namespace Jankx;

/**
 * Jankx\GlobalVariables class
 */
class GlobalVariables
{
    protected static $vars;


    public static function set($var, $value)
    {
        static::$vars[$var] = $value;

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
