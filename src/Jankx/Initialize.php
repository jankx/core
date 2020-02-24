<?php
namespace Jankx;

use Jankx\Asset\Manager as AssetManager;
use Jankx\PostLayout\Manager as PostLayoutManager;
use Jankx\Register;
use Jankx\SiteLayout\Layout as SiteLayout;

class Initialize
{
    public static function init()
    {
        /**
         * Adding WordPress native theme supports
         */
        self::themeSupports();

        /**
         * Setup Jankx features
         */
        if (class_exists(SiteLayout::class)) {
            $GLOBALS['site_layout'] = SiteLayout::instance();
        }

        if (class_exists(AssetManager::class)) {
            $GLOBALS['asset_manager'] = AssetManager::instance();
        }

        if (class_exists(PostLayoutManager::class)) {
            $GLOBALS['post_layout'] = PostLayoutManager::instance();
        }

        /**
         * Register WordPress native features
         */
        add_action('init', array(Register::class, 'menus'));
        add_action('widgets_init', array(Register::class, 'sidebars'));
    }

    /**
     * Setup Jankx theme supports features.
     */
    public static function themeSupports()
    {
        add_theme_support('post-thumbnails');
    }
}
