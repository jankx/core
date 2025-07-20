<?php

namespace Jankx\Kernel;

use Jankx\Jankx;
use Jankx\Kernel\Bootstrappers\AdminBootstrapper;
use Jankx\Kernel\Bootstrappers\ThemeBootstrapper;
use Jankx\Admin\Admin;
use Jankx\Command\CommandManager;
use Jankx\CSS\GlobalVariables as GlobalCSSVariables;

/**
 * Admin Kernel
 *
 * Handles admin-specific features and optimizations
 *
 * @package Jankx\Kernel
 */
class AdminKernel extends AbstractKernel
{
    /**
     * Get kernel type
     */
    protected function getKernelType(): string
    {
        return 'admin';
    }

    /**
     * Register bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        // Theme bootstrapper (highest priority)
        $this->addBootstrapper(ThemeBootstrapper::class);

        // Admin bootstrapper
        $this->addBootstrapper(AdminBootstrapper::class);

        // Allow child themes to add custom bootstrappers
        $customBootstrappers = apply_filters('jankx/admin/bootstrappers', []);
        foreach ($customBootstrappers as $bootstrapper) {
            $this->addBootstrapper($bootstrapper);
        }
    }

    /**
     * Register services
     */
    protected function registerServices(): void
    {
        // Admin interface
        $this->addService(Admin::class);

        // Command manager
        $this->addService(CommandManager::class);

        // Global CSS variables
        $this->addService(GlobalCSSVariables::class);
    }

    /**
     * Register hooks
     */
    protected function registerHooks(): void
    {
        // Admin scripts
        $this->addHook('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);

        // Admin menu
        $this->addHook('admin_menu', [$this, 'registerAdminMenu']);

        // Admin notices
        $this->addHook('admin_notices', [$this, 'showAdminNotices']);

        // Customizer
        $this->addHook('customize_register', [$this, 'customizeRegister']);

        // Meta boxes
        $this->addHook('add_meta_boxes', [$this, 'registerMetaBoxes']);

        // Save post
        $this->addHook('save_post', [$this, 'savePostMeta']);

        // Admin footer
        $this->addHook('admin_footer', [$this, 'adminFooter']);

        // CSS Variables
        $this->addHook('admin_init', [$this, 'initCSSVariables']);
    }

    /**
     * Register filters
     */
    protected function registerFilters(): void
    {
        // Admin body classes
        $this->addFilter('admin_body_class', [$this, 'addAdminBodyClasses']);

        // Admin title
        $this->addFilter('admin_title', [$this, 'optimizeAdminTitle']);

        // Admin footer text
        $this->addFilter('admin_footer_text', [$this, 'customFooterText']);
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueueAdminScripts(): void
    {
        $screen = get_current_screen();

        // Admin styles
        wp_enqueue_style(
            'jankx-admin',
            jankx_core_asset_url('css/admin.css'),
            [],
            Jankx::FRAMEWORK_VERSION
        );

        // Admin JavaScript
        wp_enqueue_script(
            'jankx-admin',
            jankx_core_asset_url('js/admin.js'),
            ['jquery'],
            Jankx::FRAMEWORK_VERSION,
            true
        );

        // Localize script
        wp_localize_script('jankx-admin', 'jankx_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('jankx_admin_nonce'),
            'screen' => $screen ? $screen->id : '',
        ]);
    }

    /**
     * Register admin menu
     */
    public function registerAdminMenu(): void
    {
        add_menu_page(
            __('Jankx Settings', 'jankx'),
            __('Jankx', 'jankx'),
            'manage_options',
            'jankx-settings',
            [$this, 'renderSettingsPage'],
            'dashicons-admin-generic',
            30
        );

        add_submenu_page(
            'jankx-settings',
            __('General', 'jankx'),
            __('General', 'jankx'),
            'manage_options',
            'jankx-settings',
            [$this, 'renderSettingsPage']
        );

        add_submenu_page(
            'jankx-settings',
            __('Performance', 'jankx'),
            __('Performance', 'jankx'),
            'manage_options',
            'jankx-performance',
            [$this, 'renderPerformancePage']
        );
    }

    /**
     * Show admin notices
     */
    public function showAdminNotices(): void
    {
        // Check for required plugins
        $missing_plugins = $this->checkRequiredPlugins();
        if (!empty($missing_plugins)) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>' . sprintf(
                __('Jankx Framework requires the following plugins: %s', 'jankx'),
                implode(', ', $missing_plugins)
            ) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Customize register
     */
    public function customizeRegister($wp_customize): void
    {
        // Add customizer sections and controls
        $this->addCustomizerSections($wp_customize);
    }

    /**
     * Register meta boxes
     */
    public function registerMetaBoxes(): void
    {
        add_meta_box(
            'jankx-post-options',
            __('Jankx Options', 'jankx'),
            [$this, 'renderPostMetaBox'],
            'post',
            'side',
            'default'
        );
    }

    /**
     * Save post meta
     */
    public function savePostMeta(int $post_id): void
    {
        if (!wp_verify_nonce($_POST['jankx_nonce'] ?? '', 'jankx_save_post')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save custom fields
        $this->saveCustomFields($post_id);
    }

    /**
     * Admin footer
     */
    public function adminFooter(): void
    {
        echo '<div id="jankx-admin-footer">';
        echo '<p>' . sprintf(
            __('Powered by %s', 'jankx'),
            '<a href="https://github.com/jankx/core" target="_blank">Jankx Framework</a>'
        ) . '</p>';
        echo '</div>';
    }

    /**
     * Init CSS variables
     */
    public function initCSSVariables(): void
    {
        $css_variables = $this->container->make(GlobalCSSVariables::class);
        $css_variables->init();
    }

    /**
     * Add admin body classes
     */
    public function addAdminBodyClasses(string $classes): string
    {
        $classes .= ' jankx-admin kernel-admin';
        return $classes;
    }

    /**
     * Optimize admin title
     */
    public function optimizeAdminTitle(string $title): string
    {
        return $title . ' - ' . __('Jankx Framework', 'jankx');
    }

    /**
     * Custom footer text
     */
    public function customFooterText(string $text): string
    {
        return sprintf(
            __('Thank you for using %s', 'jankx'),
            '<a href="https://github.com/jankx/core" target="_blank">Jankx Framework</a>'
        );
    }

    /**
     * Render settings page
     */
    public function renderSettingsPage(): void
    {
        include jankx_core_template_path('admin/settings.php');
    }

    /**
     * Render performance page
     */
    public function renderPerformancePage(): void
    {
        include jankx_core_template_path('admin/performance.php');
    }

    /**
     * Render post meta box
     */
    public function renderPostMetaBox($post): void
    {
        wp_nonce_field('jankx_save_post', 'jankx_nonce');
        include jankx_core_template_path('admin/meta-boxes/post-options.php');
    }

    /**
     * Add customizer sections
     */
    protected function addCustomizerSections($wp_customize): void
    {
        // Add customizer sections here
    }

    /**
     * Check required plugins
     */
    protected function checkRequiredPlugins(): array
    {
        $required_plugins = apply_filters('jankx_required_plugins', []);
        $missing_plugins = [];

        foreach ($required_plugins as $plugin) {
            if (!is_plugin_active($plugin)) {
                $missing_plugins[] = $plugin;
            }
        }

        return $missing_plugins;
    }

    /**
     * Save custom fields
     */
    protected function saveCustomFields(int $post_id): void
    {
        // Save custom fields logic here
    }
}