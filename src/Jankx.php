<?php
/**
 * This is the main class or the main gate to a developer
 * can use it to run all features of the framework.
 *
 * PHP version 5.4 or later
 *
 * @category Jankx
 * @package  Core
 * @author   Puleeno Nguyen <puleeno@gmail.com>
 * @license  MIT (https://opensource.org/licenses/MIT)
 * @link     https://github.com/jankx/core
 */

/**
 * This class is middle-class interaction between developer and other classes
 * phpcs:ignoreFile
 */
class Jankx
{
    protected static $instance;

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->includes();
        $this->initHooks();
    }

    public function __call($name, $args)
    {
        $callback = array($this, $name);
        if (isset($this->$name) && is_callable($callback)) {
            return call_user_func_array($callback, $args);
        } else {
            throw new \Exception(
                sprintf('Call to undefined method %s::%s()', __CLASS__, $name)
            );
        }
    }

    public static function __callStatic($name, $args)
    {
        $instance = self::instance();
        $callback = array($instance, $name);
        if (isset($instance->$name) && is_callable($callback)) {
            return call_user_func_array($callback, $args);
        } else {
            throw new \Exception(
                sprintf('Call to undefined method %s::%s()', __CLASS__, $name)
            );
        }
    }

    public function includes()
    {
        if (self::isRequest('admin') && class_exists('\Jankx\Admin\Admin')) {
            new \Jankx\Admin\Admin();
        }
    }

    public function initHooks()
    {
        add_action('after_setup_theme', array($this, 'setup'));
        add_action('init', array('\Jankx\Initialize', 'init'));

        /**
         * Setup template for frontend page
         */
        add_action('jankx_setup_environment', array('\Jankx\Template\Initialize', 'loadTemplateFunctions'));
    }

    public function setup()
    {
        $this->theme = \Jankx\Theme::getInstance();

        /**
         * Setup Jankx environment via action hooks
         */
        do_action('jankx_setup_environment', $this);
    }

    public static function isRequest($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'cron':
                return defined('DOING_CRON');
            case 'frontend':
                return ( ! is_admin() || defined('DOING_AJAX') ) && ! defined('DOING_CRON') && ! self::isApiRequest();
        }
    }

    /**
     * Check current request is API request
     *
     * @return boolean Is API request or not
     */
    public static function isApiRequest()
    {
        if (empty($_SERVER['REQUEST_URI'])) {
            return false;
        }
        $rest_prefix         = trailingslashit(rest_get_url_prefix());
        $is_rest_api_request = ( false !== strpos($_SERVER['REQUEST_URI'], $rest_prefix) );
        return apply_filters('woocommerce_is_rest_api_request', $is_rest_api_request);
    }

    /**
     * Get location use to search template for theme
     * When you want to custom or create a new template for your theme
     * you can use hook `jankx_template_directory` to change template directory
     * It will help you avoid modify Jankx templates and conflict when upgrate framework.
     *
     * @return string The template location
     */
    public static function templateDirectory()
    {
        return apply_filters('jankx_template_directory', 'templates');
    }
}
