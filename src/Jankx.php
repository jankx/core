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

    public function __call($method, $args)
    {
    }

    public static function __callStatic($method, $args)
    {
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
    }

    public function setup()
    {
        $this->theme = new \Jankx\Theme();
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

    public static function isApiRequest()
    {
        if (empty($_SERVER['REQUEST_URI'])) {
            return false;
        }
        $rest_prefix         = trailingslashit(rest_get_url_prefix());
        $is_rest_api_request = ( false !== strpos($_SERVER['REQUEST_URI'], $rest_prefix) );
        return apply_filters('woocommerce_is_rest_api_request', $is_rest_api_request);
    }
}
