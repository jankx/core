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
use Jankx\Integration\Integrator;
use Jankx\Option\Framework as OptionFramework;
use Jankx\UX\UserExperience;
use Jankx\PostLayout\PostLayoutManager;
use Jankx\Widget\WidgetManager;
use Jankx\Guarder;
use Jankx\Command\CLI;
use Jankx\Comments;
use Jankx\ConfigurationReader;
use Jankx\GlobalVariables;
use Jankx\Social\Sharing;
use Jankx\TemplateLoader;

/**
 * This class is middle-class interaction between developer and other classes
 */
class Jankx
{
    const FRAMEWORK_NAME    = 'Jankx Framework';
    const FRAMEWORK_VERSION = '1.0.0';

    protected static $instance;
    protected $defaultTemplateDir;

    public static $theme;

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
        static::$theme = wp_get_theme();
        define('JANKX_THEME_DEFAULT_ENGINE', $this->defaultTemplateDir);
        define('JANKX_CACHE_DIR', sprintf('%s/jankx/caches/', WP_CONTENT_DIR));
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
    private function includes()
    {
        $jankxVendor = realpath(dirname(JANKX_FRAMEWORK_FILE_LOADER) . '/..');
        $fileNames = array(
            'component/component.php',
            'core/functions.php',
            'template/functions.php',
        );
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
         * The config reader will read config file and set the value into GlobalVariables with prefix `config.*`
         * Example get the config value: \Jankx\GlobalVariables::get('config.theme.version')
         */
        $configReader = new ConfigurationReader(apply_filters(
            'jankx_theme_configuration_file',
            '.theme.yml'
        ));
        $configReader->read();

        /**
         * Load Jankx templates
         */
        Template::getLoader(
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

        $templateLoader = new TemplateLoader();
        $templateLoader->load();



        // Create Asset clousure for Jankx
        $assetManager = AssetManager::instance();
        $this->asset  = function () use ($assetManager) {
            return $assetManager;
        };

        // Create option framework clousure
        $optionFramework = OptionFramework::getInstance();
        $this->optionFramework = function () use ($optionFramework) {
            return $optionFramework;
        };

        $siteLayout = SiteLayout::getInstance();
        $this->siteLayout = function () use ($siteLayout) {
            return $siteLayout;
        };

        $userExperience = UserExperience::getInstance();
        $this->ux = function () use ($userExperience) {
            return $userExperience;
        };

        $widgets = WidgetManager::getInstance();
        $this->widgets = function () use ($widgets) {
            return $widgets;
        };

        $guarder = Guarder::getInstance();
        $guarder->watch();

        if (class_exists(WP_CLI::class)) {
            CLI::getInstance();
        }

        // Setup Jankx::device() method
        add_action('after_setup_theme', 'jankx_get_device_detector');

        add_action('after_setup_theme', array($this, 'setupOptionFramework'), 5);
        add_action('after_setup_theme', array($this, 'integrations'));
        add_action('after_setup_theme', array($this, 'improveUserExperience'));

        add_action('init', array($this, 'init'));

        // Init socials sharing
        if (apply_filters('jankx_socials_sharing_enable', GlobalVariables::get('config.socials.sharing', false))) {
            add_action('after_setup_theme', array(Sharing::class, 'get_instance'));
        }

        // Register widgets
        add_action('widgets_init', array(static::widgets(), 'registerWidgets'));
    }

    public function init()
    {
        // Run hook jankx init via components
        do_action('jankx_init_features');

        add_theme_support('html5');
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
            'unlink-homepage-logo' => true,
        ));

        // Load Jankx components
        Registry::registerComponents();

        // Setup post layout
        PostLayoutManager::getInstance();

        // Init the comments system
        Comments::init();
    }

    public function integrations()
    {
        $integrator = Integrator::getInstance();
        $integrator->integrate();
    }

    public function setupOptionFramework()
    {
        $optionFramework = static::optionFramework();

        /**
         * Default mode is `auto` and Jankx auto search Option framework to use
         *
         * Hook `jankx_option_framework_mode`: You set the option framework via this hook
         */
        $optionMode = apply_filters(
            'jankx_option_framework_mode',
            get_option('jankx_option_framework', 'auto')
        );
        $optionFramework->setMode($optionMode);
        $optionFramework->loadFramework();
    }

    // Improve UX
    public function improveUserExperience()
    {
        static::ux()->optimize();
    }
}
