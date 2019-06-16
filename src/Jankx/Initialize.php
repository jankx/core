<?php
namespace Jankx;

use Jankx\SiteLayouts\Layout as SiteLayout;

class Initialize
{
    public static function init()
    {
        self::themeSupports();

        if (class_exists(SiteLayout::class)) {
            $GLOBALS['site_layout'] = SiteLayout::instance();
        }
    }

    /**
     * Setup Jankx theme supports features.
     */
    public static function themeSupports()
    {
        add_theme_support('post-thumbnails');
    }
}
