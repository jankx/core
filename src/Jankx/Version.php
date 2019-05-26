<?php

namespace Jankx;

class Version
{
    const JANKX_CORE_VERSION = '0.1.1';

    public static function wp()
    {
        return $GLOBALS['wp_version'];
    }

    public static function core()
    {
        return self::JANKX_CORE_VERSION;
    }

    /**
     * This method will be return template version of current theme.
     *
     * @return string current template version
     */
    public static function template()
    {
        return jankx()->theme->template()->version();
    }

    public static function theme()
    {
        return jankx()->theme->version();
    }
}
