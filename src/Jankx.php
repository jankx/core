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
 * @license  MIT (https:///opensource.org/licenses/MIT)
 * @link     https://github.com/jankx/core
 */

use Jankx\Template\Template;

/**
 * This class is middle-class interaction between developer and other classes
 */
class Jankx
{
    const FRAMEWORK_NAME = 'Jankx Framework';

    protected static $instance;
    protected $defaultTemplateDir;

    public static function __callStatic($name, $args)
    {
        $instance = self::instance();
        if (isset($instance->$name) && is_callable($instance->$name)) {
            return call_user_func_array($instance->$name, $args);
        } else {
            throw new \Exception(
                sprintf('Call to undefined method %s::%s()', __CLASS__, $name)
            );
        }
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->defaultTemplateDir = sprintf(
            '%s/template/default',
            realpath(dirname(JANKX_FRAMEWORK_FILE_LOADER) . '/..')
        );
    }

    private static function isRequest($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'cron':
                return defined('DOING_CRON');
            case 'frontend':
                return ( ! is_admin() || defined('DOING_AJAX') ) && ! defined('DOING_CRON');
        }
    }

    public function init()
    {
        do_action('jankx_init');
        /**
         * Load Jankx templates
         */
        $templateLoader = Template::getInstance(
            $this->defaultTemplateDir,
            'templates',
            apply_filters_ref_array(
                'jankx_theme_template_engine',
                [
                    'wordpress',
                    &$this
                ]
            ),
        );
        $template = new Template();
        $template->load($this->defaultTemplateDir);

        $this->templateLoader = function () use ($templateLoader) {
            return $templateLoader;
        };

        define('JANKX_FRAMEWORK_LOADED', true);
        $GLOBALS['jankx_template'] = $template;

        do_action('jankx_loaded');
    }
}
