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

use Jankx\Asset\AssetManager;
use Jankx\Component\Registry;
use Jankx\SiteLayout\SiteLayout;
use Jankx\Template\Template;

/**
 * This class is middle-class interaction between developer and other classes
 */
class Jankx
{
    const FRAMEWORK_NAME    = 'Jankx Framework';
    const FRAMEWORK_VERSION = '1.0.0';

    protected static $instance;
    protected $defaultTemplateDir;

    public $siteLayout;
    public $assetManager;

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

    private function __construct()
    {
        $this->defaultTemplateDir = sprintf(
            '%s/template/default',
            realpath(dirname(JANKX_FRAMEWORK_FILE_LOADER) . '/..')
        );
        define('JANKX_THEME_DEFAULT_ENGINE', $this->defaultTemplateDir);
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

    public function setup()
    {
        if (did_action('init')) {
            if (!function_exists('jankx')) {
                function jankx()
                {
                    _e('The init hook is called. Please try implement the Jankx framework in another place.', 'jankx');
                }
            }
            return;
        }

        do_action('jankx_init');

        $this->includes();
        $this->initCoreFramework();

        do_action('jankx_loaded');

        define('JANKX_FRAMEWORK_LOADED', true);
    }

    /**
     * Load the Jankx dependences
     *
     * @return void
     */
    private function includes() {
        $jankxVendor = realpath(dirname(JANKX_FRAMEWORK_FILE_LOADER) . '/..');
        $fileNames = array('component/component.php');
        foreach ($fileNames as $fileName) {
            $file = sprintf('%s/%s', $jankxVendor, $fileName);
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }

    private function initCoreFramework()
    {
        /**
         * Load Jankx templates
         */
        $templateLoader = Template::getLoader(
            $this->defaultTemplateDir,
            apply_filters('jankx_theme_template_directory_name', 'templates'),
            apply_filters_ref_array(
                'jankx_theme_template_engine',
                [
                    'wordpress',
                    &$this
                ]
            )
        );
        $templateLoader->load();

        $this->templateLoader = function () use ($templateLoader) {
            return $templateLoader;
        };

        $this->siteLayout   = SiteLayout::getInstance();
        $this->assetManager = AssetManager::instance();

        add_action('init', array($this, 'init'));
        add_action('init', array(
            Registry::class,
            'registerComponents'
        ));
        add_action('widgets_init', array(
            $this->siteLayout,
            'registerSidebars'
        ));
    }

    public function init()
    {
        add_theme_support('post-thumbnails');
    }
}
