<?php
namespace Jankx;

use Jankx\SiteLayouts\Layout as SiteLayout;
use Jankx\UI\Framework as UIFramework;
use Jankx\Asset\Manager as AssetManager;
use Jankx\PostLayouts\Manager as PostLayoutManager;
use Jankx\Register;

class Initialize
{
    public static function init()
    {
        self::themeSupports();

        if (class_exists(SiteLayout::class)) {
            $GLOBALS['site_layout'] = SiteLayout::instance();
        }

        if (class_exists(AssetManager::class)) {
            $GLOBALS['asset_manager'] = AssetManager::instance();
        }

        if (\Jankx::isRequest('frontend')) {
            $GLOBALS['ui_framework'] = UIFramework::instance();
        }

        if (class_exists(PostLayoutManager::class)) {
            $GLOBALS['post_layout'] = PostLayoutManager::instance();
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
