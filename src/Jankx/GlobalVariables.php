<?php
namespace Jankx;

/**
 * Jankx\GlobalVariables class
 */
class GlobalVariables
{
    protected static $vars = array();
    protected static $locked_vars = array();

    public static function set($name, $value, $lock_it = false)
    {
        if (!isset(static::$vars[$name]) || empty(static::$locked_vars[$name])) {
            $keys = array_reverse(explode('.', $name));
            foreach ($keys as $index => $key) {
                if ($index === count($keys) - 1) {
                    static::$vars[$key] = $value;
                    break;
                }
                $value = array(
                    $key => $value,
                );
            }

            if ($lock_it) {
                static::$locked_vars[$name] = true;
            }
        }
        return true;
    }

    public static function get($var, $defaultValue = null)
    {
        $keys = array_filter(explode('.', $var));
        $current_value = static::$vars;
        foreach ($keys as $key) {
            if (!isset($current_value[$key])) {
                return $defaultValue;
            }
            $current_value = $current_value[$key];
        }
        return $current_value;
    }

    public static function getAll()
    {
        return static::$vars;
    }
}
