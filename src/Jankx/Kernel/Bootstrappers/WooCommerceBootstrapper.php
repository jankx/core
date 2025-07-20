<?php

namespace Jankx\Kernel\Bootstrappers;

/**
 * WooCommerce Bootstrapper
 *
 * Bootstrap WooCommerce support
 *
 * @package Jankx\Kernel\Bootstrappers
 */
class WooCommerceBootstrapper extends AbstractBootstrapper
{
    /**
     * @var int
     */
    protected $priority = 15;

    /**
     * @var array
     */
    protected $dependencies = [
        'WooCommerce'
    ];

    /**
     * Bootstrap the application
     */
    public function bootstrap($container): void
    {
        // Add WooCommerce support
        add_action('after_setup_theme', [$this, 'addWooCommerceSupport']);

        // Remove WooCommerce default styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');

        // Customize WooCommerce
        add_action('after_setup_theme', [$this, 'customizeWooCommerce']);

        // Add WooCommerce hooks
        add_action('woocommerce_before_main_content', [$this, 'woocommerceWrapperStart']);
        add_action('woocommerce_after_main_content', [$this, 'woocommerceWrapperEnd']);

        do_action('jankx/bootstrapper/woocommerce/loaded');
    }

    /**
     * Check conditions
     */
    protected function checkConditions(): bool
    {
        return class_exists('WooCommerce');
    }

    /**
     * Add WooCommerce support
     */
    public function addWooCommerceSupport(): void
    {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Customize WooCommerce
     */
    public function customizeWooCommerce(): void
    {
        // Remove WooCommerce breadcrumbs
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

        // Remove WooCommerce sidebar
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

        // Customize WooCommerce hooks
        add_filter('woocommerce_show_page_title', '__return_false');
        add_filter('woocommerce_product_description_heading', '__return_null');
        add_filter('woocommerce_product_additional_information_heading', '__return_null');
    }

    /**
     * WooCommerce wrapper start
     */
    public function woocommerceWrapperStart(): void
    {
        echo '<div class="woocommerce-wrapper">';
    }

    /**
     * WooCommerce wrapper end
     */
    public function woocommerceWrapperEnd(): void
    {
        echo '</div>';
    }

    /**
     * Get description
     */
    public function getDescription(): string
    {
        return 'Bootstrap WooCommerce support';
    }
}