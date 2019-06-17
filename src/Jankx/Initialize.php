<?php
namespace Jankx;

use Jankx\SiteLayouts\Layout as SiteLayout;
use Jank\UI\Framework as UIFramework;

class Initialize
{
    public static function init()
    {
        self::themeSupports();

        if (class_exists(SiteLayout::class)) {
            $GLOBALS['site_layout'] = SiteLayout::instance();
        }

        if (\Jankx::isRequest('frontend')) {
            $GLOBALS['ui_framework'] = UIFramework::instance();
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
