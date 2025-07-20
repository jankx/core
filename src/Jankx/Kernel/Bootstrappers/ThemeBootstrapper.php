<?php

namespace Jankx\Kernel\Bootstrappers;

/**
 * Theme Bootstrapper
 *
 * Bootstrap theme features and support
 *
 * @package Jankx\Kernel\Bootstrappers
 */
class ThemeBootstrapper extends AbstractBootstrapper
{
    /**
     * @var int
     */
    protected $priority = 1;

    /**
     * Bootstrap the application
     */
    public function bootstrap($container): void
    {
        // Add theme support
        add_action('after_setup_theme', [$this, 'setupThemeSupport']);

        // Register nav menus
        add_action('after_setup_theme', [$this, 'registerNavMenus']);

        // Add image sizes
        add_action('after_setup_theme', [$this, 'addImageSizes']);

        // Register widgets
        add_action('widgets_init', [$this, 'registerWidgets']);

        do_action('jankx/bootstrapper/theme/loaded');
    }

    /**
     * Setup theme support
     */
    public function setupThemeSupport(): void
    {
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('custom-logo');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('responsive-embeds');
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('automatic-feed-links');
    }

    /**
     * Register nav menus
     */
    public function registerNavMenus(): void
    {
        register_nav_menus([
            'primary' => __('Primary Menu', 'jankx'),
            'footer' => __('Footer Menu', 'jankx'),
            'mobile' => __('Mobile Menu', 'jankx'),
        ]);
    }

    /**
     * Add image sizes
     */
    public function addImageSizes(): void
    {
        add_image_size('jankx-featured', 800, 600, true);
        add_image_size('jankx-thumbnail', 300, 200, true);
        add_image_size('jankx-hero', 1200, 600, true);
        add_image_size('jankx-square', 400, 400, true);
    }

    /**
     * Register widgets
     */
    public function registerWidgets(): void
    {
        register_sidebar([
            'name' => __('Primary Sidebar', 'jankx'),
            'id' => 'sidebar-1',
            'description' => __('Add widgets here to appear in your sidebar.', 'jankx'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ]);

        register_sidebar([
            'name' => __('Footer Widget Area', 'jankx'),
            'id' => 'footer-1',
            'description' => __('Add widgets here to appear in your footer.', 'jankx'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
    }

    /**
     * Get description
     */
    public function getDescription(): string
    {
        return 'Bootstrap theme features and support';
    }
}