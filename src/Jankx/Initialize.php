<?php
namespace Jankx;

use Jankx\SiteLayouts\Layout as SiteLayout;
use Jankx\UI\Framework as UIFramework;
use Jankx\Register;

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

        /**
         * Register WordPress native features
         */
        add_action('init', array(Register::class, 'menus'));
        add_action('init', array(Register::class, 'sidebars'));
    }

    /**
     * Setup Jankx theme supports features.
     */
    public static function themeSupports()
    {
        add_theme_support('post-thumbnails');
    }
}
