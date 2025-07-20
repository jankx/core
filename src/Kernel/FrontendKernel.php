<?php

namespace Jankx\Kernel;

use Jankx\Jankx;
use Jankx\Kernel\Bootstrappers\AssetBootstrapper;
use Jankx\Kernel\Bootstrappers\ThemeBootstrapper;
use Jankx\Kernel\Bootstrappers\WooCommerceBootstrapper;
use Jankx\UX\UserExperience;
//use Jankx\Social\Sharing;
//use Jankx\IconFonts;
use Jankx\CSS\GlobalVariables as GlobalCSSVariables;
use Jankx\Comments;

//use Jankx\Widget\WidgetManager;

/**
 * Frontend Kernel
 *
 * Handles frontend-specific features and optimizations
 *
 * @package Jankx\Kernel
 */
class FrontendKernel extends AbstractKernel
{
    protected $serviceProviders = [
        \Jankx\Providers\FrontendServiceProvider::class,
        \Jankx\Providers\GutenbergServiceProvider::class,
        \Jankx\Providers\PlatesTemplateServiceProvider::class,
        \Jankx\Providers\PostLayoutServiceProvider::class,
    ];

    /**
     * Get kernel type
     */
    protected function getKernelType(): string
    {
        return 'frontend';
    }

    /**
     * Register bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        // Theme bootstrapper (highest priority)
        $this->addBootstrapper(ThemeBootstrapper::class);

        // Asset bootstrapper
        $this->addBootstrapper(AssetBootstrapper::class);

        // WooCommerce bootstrapper
        $this->addBootstrapper(WooCommerceBootstrapper::class);

        // Allow child themes to add custom bootstrappers
        $customBootstrappers = apply_filters('jankx/frontend/bootstrappers', []);
        foreach ($customBootstrappers as $bootstrapper) {
            $this->addBootstrapper($bootstrapper);
        }
    }

    /**
     * Register services
     */
    protected function registerServices(): void
    {
        // User experience
        $this->addService(UserExperience::class);

        // Icon fonts - Bỏ qua vì class không tồn tại
        // $this->addService(IconFonts::class);

        // Global CSS variables
        $this->addService(GlobalCSSVariables::class);

        // Comments system
        $this->addService(Comments::class);

        // Widget manager - Bỏ qua vì class không tồn tại
        // $this->addService(WidgetManager::class);

        // Social sharing (if enabled) - Bỏ qua vì class không tồn tại
        //if (apply_filters('jankx_socials_sharing_enable', true)) {
        //    $this->addService(Sharing::class);
        //}
    }

    /**
     * Register hooks
     */
    protected function registerHooks(): void
    {
        // Chỉ đăng ký hooks nếu đang ở context frontend
        if ($this->isFrontendContext()) {
            // Enqueue scripts
            $this->addHook('wp_enqueue_scripts', [$this, 'enqueueScripts'], 20);

            // Body classes
            $this->addHook('body_class', [$this, 'addBodyClasses']);

            // Excerpt length
            $this->addHook('excerpt_length', [$this, 'excerptLength']);

            // Customizer
            $this->addHook('customize_register', [$this, 'customizeRegister']);

            // Widgets
            $this->addHook('widgets_init', [$this, 'registerWidgets']);

            // Comments
            $this->addHook('wp', [$this, 'initComments']);

            // CSS Variables
            $this->addHook('wp', [$this, 'initCSSVariables']);

            // Icon fonts
            $this->addHook('wp_enqueue_scripts', [$this, 'registerIconFonts']);

            // Social sharing
            if (apply_filters('jankx_socials_sharing_enable', true)) {
                $this->addHook('after_setup_theme', [$this, 'initSocialSharing']);
            }
        }
    }

    /**
     * Register filters
     */
    protected function registerFilters(): void
    {
        // Chỉ đăng ký filters nếu đang ở context frontend
        if ($this->isFrontendContext()) {
            // Performance optimizations
            $this->addFilter('wp_resource_hints', [$this, 'resourceHints'], 10, 2);
            $this->addFilter('wp_head', [$this, 'removeUnnecessaryTags']);

            // SEO optimizations
            $this->addFilter('wp_title', [$this, 'optimizeTitle']);

            // Content optimizations
            $this->addFilter('the_content', [$this, 'optimizeContent']);
            $this->addFilter('excerpt_more', [$this, 'excerptMore']);
        }
    }

    /**
     * Kiểm tra xem có phải context frontend không
     */
    protected function isFrontendContext(): bool
    {
        return !is_admin() && !defined('WP_CLI') && !defined('REST_REQUEST') && !wp_doing_cron();
    }

    /**
     * Enqueue scripts
     */
    public function enqueueScripts(): void
    {
        // Theme styles
        wp_enqueue_style(
            'jankx-style',
            get_stylesheet_uri(),
            [],
            Jankx::FRAMEWORK_VERSION
        );

        // Google Fonts
        wp_enqueue_style(
            'jankx-google-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
            [],
            null
        );

        // Theme JavaScript
        wp_enqueue_script(
            'jankx-scripts',
            jankx_core_asset_url('js/app.js'),
            ['jquery'],
            Jankx::FRAMEWORK_VERSION,
            true
        );

        // Localize script
        wp_localize_script('jankx-scripts', 'jankx_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('jankx_nonce'),
        ]);
    }

    /**
     * Add body classes
     */
    public function addBodyClasses(array $classes): array
    {
        $classes[] = 'jankx-theme';
        $classes[] = 'kernel-frontend';

        if (is_single()) {
            $classes[] = 'single-post';
        }

        if (is_page()) {
            $classes[] = 'single-page';
        }

        return $classes;
    }

    /**
     * Custom excerpt length
     */
    public function excerptLength(int $length): int
    {
        return apply_filters('jankx_excerpt_length', 20);
    }

    /**
     * Customize register
     */
    public function customizeRegister($wp_customize): void
    {
        // Hero Section
        $wp_customize->add_section('jankx_hero', [
            'title' => __('Hero Section', 'jankx'),
            'priority' => 30,
        ]);

        // Hero Title
        $wp_customize->add_setting('hero_title', [
            'default' => 'Welcome to Jankx',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('hero_title', [
            'label' => __('Hero Title', 'jankx'),
            'section' => 'jankx_hero',
            'type' => 'text',
        ]);

        // Newsletter Section
        $wp_customize->add_section('jankx_newsletter', [
            'title' => __('Newsletter Section', 'jankx'),
            'priority' => 35,
        ]);

        // Newsletter Title
        $wp_customize->add_control('newsletter_title', [
            'label' => __('Newsletter Title', 'jankx'),
            'section' => 'jankx_newsletter',
            'type' => 'text',
        ]);
    }

    /**
     * Register widgets
     */
    public function registerWidgets(): void
    {
        // Bỏ qua vì class không tồn tại
        // $widgetManager = $this->container->make(WidgetManager::class);
        // $widgetManager->registerWidgets();
    }

    /**
     * Init comments
     */
    public function initComments(): void
    {
        $comments = $this->container->make(Comments::class);
        $comments->init($this->container);
    }

    /**
     * Init CSS variables
     */
    public function initCSSVariables(): void
    {
        $cssVariables = $this->container->make(GlobalCSSVariables::class);
        $cssVariables->init();
    }

    /**
     * Icon fonts
     */
    public function registerIconFonts(): void
    {
        // Bỏ qua vì class không tồn tại
        // $iconFonts = $this->container->make(IconFonts::class);
        // $iconFonts->register();
    }

    /**
     * Init social sharing
     */
    public function initSocialSharing(): void
    {
        // Bỏ qua vì class không tồn tại
        // $sharing = $this->container->make(Sharing::class);
        // $sharing->init();
    }

    /**
     * Resource hints
     */
    public function resourceHints(array $hints, string $relation_type): array
    {
        if ('dns-prefetch' === $relation_type) {
            $hints[] = '//fonts.googleapis.com';
            $hints[] = '//cdnjs.cloudflare.com';
        }

        return $hints;
    }

    /**
     * Remove unnecessary tags
     */
    public function removeUnnecessaryTags(): void
    {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }

    /**
     * Optimize title
     */
    public function optimizeTitle(string $title): string
    {
        if (is_front_page()) {
            return get_bloginfo('name') . ' - ' . get_bloginfo('description');
        }

        return $title;
    }

    /**
     * Optimize content
     */
    public function optimizeContent(string $content): string
    {
        // Remove empty paragraphs
        $content = preg_replace('/<p[^>]*><\/p>/', '', $content);

        // Optimize images
        $content = preg_replace('/<img([^>]*)>/', '<img$1 loading="lazy">', $content);

        return $content;
    }

    /**
     * Excerpt more
     */
    public function excerptMore(string $more): string
    {
        return '...';
    }
}
