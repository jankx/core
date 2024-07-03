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

use Illuminate\Container\Container;
use Jankx\Asset\AssetManager;
use Jankx\Comments;
use Jankx\Component\Registry;
use Jankx\Configs\ThemeConfigurations;
use Jankx\Guarder;
use Jankx\ScriptLoader;
use Jankx\Option\Framework as OptionFramework;
use Jankx\PostLayout\PostLayoutManager;
use Jankx\SiteLayout\SiteLayout;
use Jankx\Social\Sharing;
use Jankx\Template\Template;
use Jankx\TemplateEngine\Engines\Plates;
use Jankx\TemplateAndLayout;
use Jankx\PostTemplateLoader;
use Jankx\UX\UserExperience;
use Jankx\Widget\WidgetManager;
use Jankx\IconFonts;
use Jankx\Admin\Admin;
use Jankx\Command\CommandManager;
use Jankx\CSS\GlobalVariables as GlobalCSSVariables;
use Jankx\GlobalConfigs;
use Jankx\Interfaces\Filter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * This class is middle-class interaction between developer and other classes
 */
class Jankx extends Container
{
    const FRAMEWORK_NAME    = 'Jankx Framework';
    const FRAMEWORK_VERSION = '1.0.0.0';
    const ENGINE_ID         = 'jankx';

    protected static $instance;

    protected $templateName;
    protected $templateStylesheet;
    protected $theme;
    protected $admin;
    protected $filters = [];

    protected $asset;
    protected $optionFramework;
    protected $siteLayout;
    protected $ux;
    protected $widgets;
    protected $socialConnects;

    protected $textDomain;

    protected static $device;

    public static function __callStatic($name, $args)
    {
        $instance = self::getInstance();
        if (isset($instance->$name) && is_callable($instance->$name)) {
            return call_user_func_array($instance->$name, $args);
        } else {
            throw new \Exception(
                sprintf('Call to undefined method %s::%s()', __CLASS__, $name)
            );
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->singleton(Container::class, self::class);
        }
        return self::$instance;
    }

    private function __construct()
    {
        define('JANKX_CACHE_DIR', sprintf('%s/jankx/caches/', WP_CONTENT_DIR));
        define('JANKX_CACHE_DIR_URL', content_url('jankx/caches'));
    }


    protected function parseThemeJson()
    {
        $templateJson   = join(DIRECTORY_SEPARATOR, [get_template_directory(), "theme.json"]);
        $themeJson      = join(DIRECTORY_SEPARATOR, [get_stylesheet_directory(), "theme.json"]);
        $templateConfig = file_exists($templateJson) ? json_decode(file_get_contents($templateJson), true) : [];

        // Create template name from parent theme or template name
        $templateConfig['template_name'] = array_get($templateConfig, 'name', self::FRAMEWORK_NAME);

        // var_dump($templateConfig);die;
        if (is_child_theme() && file_exists($themeJson)) {
            $templateConfig = array_merge_recursive($templateConfig, json_decode(file_get_contents($themeJson), true));
        }

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $configs = $serializer->denormalize($templateConfig, ThemeConfigurations::class, 'json');
        $this->instance(ThemeConfigurations::class, $configs);

        GlobalConfigs::parseFromThemeJson($configs);
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
        $this->loadExtraFeatures();

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
            'core/functions.php'
        );
        foreach ($fileNames as $fileName) {
            $file = sprintf('%s/%s', $jankxVendor, $fileName);
            if (file_exists($file)) {
                require_once $file;
            }
        }

        $this->parseThemeJson();
    }

    private function initCoreFramework()
    {

        $theme       = wp_get_theme();
        $themeParent = $theme->parent();
        if (!$themeParent) {
            $themeParent = $theme;
        }

        $this->theme = function () use ($theme) {
            return $theme;
        };
        $this->templateName = function () use ($themeParent) {
            return GlobalConfigs::get(
                'theme.short_name',
                $themeParent->get('Name')
            );
        };
        $this->templateStylesheet = function () use ($themeParent) {
            return $themeParent->stylesheet;
        };

        $this->textDomain = $themeParent->get('TextDomain');

        // Create Jankx admin instance;
        $admin = is_admin() ? new Admin() : null;
        $this->admin = function () use ($admin) {
            return $admin;
        };

        /**
         * Load Jankx templates
         */
        $templateLoader = TemplateAndLayout::get_instance();
        add_action('after_setup_theme', array($templateLoader, 'createTemplateEngine'), 15);
        if (wp_is_request('frontend')) {
            $templateLoader->load();
        }

        // Load post layout templates
        $templateLoader = new PostTemplateLoader();
        add_action('template_redirect', array($templateLoader, 'load'));

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

        // Call theme assets
        $scriptLoader = new ScriptLoader();
        $scriptLoader->load();

        if (class_exists('WP_CLI')) {
            CommandManager::getInstance();
        }

        // Setup Jankx::device() method
        add_action('after_setup_theme', 'jankx_get_device_detector', 5);

        add_action('after_setup_theme', array($this, 'setupOptionFramework'), 5);
        add_action('after_setup_theme', array($this, 'improveUserExperience'));

        add_action('init', array($this, 'init'));

        // Init socials sharing
        if (apply_filters('jankx_socials_sharing_enable', GlobalConfigs::get('socials.sharing', true))) {
            add_action('after_setup_theme', array(Sharing::class, 'get_instance'));
        }

        // Register widgets
        add_action('widgets_init', array(static::widgets(), 'registerWidgets'));
    }

    public function init()
    {
        // Run hook jankx init via components
        do_action('jankx/init');

        add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
            'unlink-homepage-logo' => true,
        ));
        add_theme_support('automatic-feed-links');

        // Load Jankx components
        Registry::registerComponents();

        // Setup post layout
        PostLayoutManager::createInstance(
            TemplateAndLayout::getTemplateEngine()
        );

        // Init the comments system
        add_action('wp', array(Comments::class, 'init'));

        // Init Global CSS Variables
        add_action('wp', array(GlobalCSSVariables::class, 'init'));

        if (wp_is_request('frontend')) {
            // Load icon fonts
            $iconFonts = IconFonts::getInstance();
            add_action('wp_enqueue_scripts', array($iconFonts, 'register_scripts'));
        } elseif (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'registerAdminScripts'));
        }

        // Setup template engine for Site layout
        add_action('jankx/template/renderer/pre', function () {
            $siteLayout = SiteLayout::getInstance();
            $templateAndLayout = TemplateAndLayout::get_instance();
            $siteLayout->setTemplateEngine($templateAndLayout);
        }, 5);
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
        try {
            $optionFramework->loadFramework();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    // Improve UX
    public function improveUserExperience()
    {
        static::ux()->optimize();
    }


    /**!
     * Static methods
     */

    public static function device()
    {
        if (is_null(static::$device) && function_exists('jankx_get_device_detector')) {
            static::$device = jankx_get_device_detector();
        }
        return self::$device;
    }

    public static function getActiveTemplateEngine()
    {
        return apply_filters(
            'jankx/template/engine/apply',
            Plates::ENGINE_NAME
        );
    }

    public static function getTextDomain()
    {
        $instance = static::getInstance();
        return $instance->textDomain;
    }

    public static function render($templates, $data = array(), $echo = true)
    {
        $engine = Template::getEngine(static::ENGINE_ID);
        if (empty($engine)) {
            throw new \Exception('The Jankx template engine is not initialized');
        }

        return $engine->render($templates, $data, $echo);
    }

    public function loadExtraFeatures()
    {
        do_action('jankx/load/extra');

        // Support Mega Menu
        if (class_exists('\Jankx\Megu\Megu')) {
            call_user_func(['\Jankx\Megu\Megu', 'getInstance']);
        }
    }

    public function registerAdminScripts()
    {
        add_editor_style(jankx_core_asset_url('css/editor.css'));
    }

    /**
     * @param \Jankx\Filter $filterObject
     */
    public static function addFilter($filterObject)
    {
        if (!is_a($filterObject, Filter::class, true)) {
            return;
        }
        if (is_string($filterObject)) {
            $filterObject = new $filterObject();
        }

        foreach ($filterObject->getHooks() as $hook) {
            add_filter(
                $hook,
                [$filterObject, $filterObject->getExecutor()],
                $filterObject->getPriority(),
                $filterObject->getArgsCounter()
            );
        }
    }
}
