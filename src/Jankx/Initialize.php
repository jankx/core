<?php
namespace Jankx;

class Initialize
{
    public static function init()
    {
        self::themeSupports();
    }

    /**
     * Setup Jankx theme supports features.
     */
    public static function themeSupports()
    {
        add_theme_support('post-thumbnails');
    }
}
