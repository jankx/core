<?php

namespace Jankx;

use Jankx\Configs\ThemeConfigurations;

/**
 * Jankx\GlobalConfig class
 */
class GlobalConfigs
{
    protected static $vars = array();
    protected static $locked_vars = array();

    public static function parseFromThemeJson(ThemeConfigurations $themeConfigurations)
    {
        self::set('layouts', $themeConfigurations->getLayouts());
        self::set('site', $themeConfigurations->getSite());
        self::set('post_types', array_get($themeConfigurations->getCustoms(), 'post_types', []));
    }

    public static function set($name, $value, $lock_it = false)
    {
        if (empty(static::$locked_vars[$name]) || !isset(static::$vars[$name])) {
            static::$vars[$name] = $value;
            if (is_array($value) && count($value) > 0 && gettype(array_keys($value)[0]) !== 'integer') {
                foreach ($value as $key => $subValue) {
                    static::set(sprintf('%s.%s', $name, $key), $subValue);
                }
            }
        }
        return true;
    }

    public static function get($name, $defaultValue = null)
    {
        if (isset(static::$vars[$name])) {
            return static::$vars[$name];
        }
        return $defaultValue;
    }

    public static function getAll()
    {
        return static::$vars;
    }
}
