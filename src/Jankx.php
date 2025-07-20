<?php

namespace Jankx;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

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
use Jankx\Comments;
use Jankx\Component\Registry;
use Jankx\Configs\ThemeConfigurations;
use Jankx\Guarder;
use Jankx\ScriptLoader;
use Jankx\Adapter\Options\Framework as OptionFramework;
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
use Jankx\Interfaces\GooglePagespeedModuleInterface;
use Jankx\Extra\PageSpeed\HTML5FixerModule;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class Jankx
 *
 * Lớp chính của framework Jankx, cung cấp các chức năng cốt lõi và quản lý các thành phần của framework.
 *
 * @package Jankx
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @version 1.0.0.48
 * @license MIT
 *
 *
 * @method static mixed __callStatic(string $name, array $args) Xử lý các phương thức static không tồn tại
 * @method mixed __get(string $name) Lấy giá trị của thuộc tính
 * @method static Jankx getInstance() Lấy instance duy nhất của class
 * @method void setup() Thiết lập framework
 * @method void init() Khởi tạo framework
 * @method void setupOptionFramework() Thiết lập option framework
 * @method void improveUserExperience() Cải thiện trải nghiệm người dùng
 * @method static object device() Lấy thông tin thiết bị
 * @method static string getActiveTemplateEngine() Lấy template engine đang hoạt động
 * @method static string getTextDomain() Lấy text domain
 * @method static string render(string|array $templates, array $data = [], bool $echo = true) Render template
 * @method void loadExtraFeatures() Load các tính năng bổ sung
 * @method void registerAdminScripts() Đăng ký scripts cho admin
 * @method static void addFilter(Filter $filterObject) Thêm filter
 * @method UserExperience ux() Lấy user experience manager
 * @method void optimizePageSpeed() Tối ưu tốc độ trang
 * @method static string templateName() Lấy tên template
 * @method static string themeName() Lấy tên theme
 *
 * @method void parseThemeJson() Phân tích file theme.json
 * @method void includes() Load các dependencies
 * @method void initCoreFramework() Khởi tạo core framework
 *
 * @method static mixed make(string $abstract, array $parameters = []) Tạo instance từ Container
 * @method static mixed bound(string $abstract) Kiểm tra binding từ Container
 * @method static void bind(string $abstract, \Closure|string|null $concrete = null, bool $shared = false) Đăng ký binding từ Container
 * @method static void singleton(string $abstract, \Closure|string|null $concrete = null) Đăng ký singleton từ Container
 * @method static mixed instance(string $abstract, mixed $instance) Đăng ký instance từ Container
 * @method static mixed resolve(string $abstract, array $parameters = []) Resolve từ Container
 * @method static void forgetInstance(string $abstract) Xóa instance từ Container
 * @method static void forgetInstances() Xóa tất cả instances từ Container
 * @method static void flush() Xóa tất cả bindings và instances từ Container
 * @method static array getBindings() Lấy tất cả bindings từ Container
 * @method static bool has(string $id) Kiểm tra có binding từ Container
 * @method static mixed get(string $id) Lấy binding từ Container
 * @method static bool hasMethodBinding(string $method) Kiểm tra có method binding từ Container
 * @method static void bindMethod(array|string $method, \Closure $callback) Đăng ký method binding từ Container
 * @method static mixed callMethodBinding(string $method, mixed $instance, array $parameters = []) Gọi method binding từ Container
 * @method static void addContextualBinding(string $concrete, string $abstract, \Closure|string $implementation) Thêm contextual binding từ Container
 * @method static void when(string $concrete) Thiết lập contextual binding từ Container
 * @method static \Closure factory(\Closure $closure) Tạo factory từ Container
 * @method static mixed wrap(\Closure $callback, array $parameters = []) Wrap closure từ Container
 * @method static mixed call(\Closure|callable $callback, array $parameters = [], string|null $defaultMethod = null) Gọi callback từ Container
 * @method static mixed resolveClass(\ReflectionParameter $parameter) Resolve class từ Container
 * @method static mixed resolveNonClass(\ReflectionParameter $parameter) Resolve non-class từ Container
 * @method static mixed resolvePrimitive(\ReflectionParameter $parameter) Resolve primitive từ Container
 * @method static mixed resolveOptionalClass(\ReflectionParameter $parameter) Resolve optional class từ Container
 * @method static mixed resolveClassForVariadic(\ReflectionParameter $parameter) Resolve class for variadic từ Container
 * @method static mixed resolveNonClassForVariadic(\ReflectionParameter $parameter) Resolve non-class for variadic từ Container
 * @method static mixed resolvePrimitiveForVariadic(\ReflectionParameter $parameter) Resolve primitive for variadic từ Container
 * @method static mixed resolveOptionalClassForVariadic(\ReflectionParameter $parameter) Resolve optional class for variadic từ Container
 * @method static mixed resolveDependenciesForClass(\ReflectionClass $reflector, array $primitives = []) Resolve dependencies for class từ Container
 * @method static mixed resolveDependenciesForCallable(\ReflectionFunctionAbstract $reflector, array $primitives = []) Resolve dependencies for callable từ Container
 * @method static mixed resolveDependenciesForMethod(\ReflectionMethod $reflector, array $primitives = []) Resolve dependencies for method từ Container
 * @method static mixed resolveDependenciesForFunction(\ReflectionFunction $reflector, array $primitives = []) Resolve dependencies for function từ Container
 * @method static mixed resolveDependenciesForClosure(\ReflectionFunction $reflector, array $primitives = []) Resolve dependencies for closure từ Container
 * @method static mixed resolveDependenciesForInvokable(\ReflectionMethod $reflector, array $primitives = []) Resolve dependencies for invokable từ Container
 * @method static mixed resolveDependenciesForConstructor(\ReflectionMethod $reflector, array $primitives = []) Resolve dependencies for constructor từ Container
 * @method static mixed resolveDependenciesForMethodCall(\ReflectionMethod $reflector, array $primitives = []) Resolve dependencies for method call từ Container
 * @method static mixed resolveDependenciesForStaticMethodCall(\ReflectionMethod $reflector, array $primitives = []) Resolve dependencies for static method call từ Container
 * @method static mixed resolveDependenciesForClosureCall(\ReflectionFunction $reflector, array $primitives = []) Resolve dependencies for closure call từ Container
 * @method static mixed resolveDependenciesForFunctionCall(\ReflectionFunction $reflector, array $primitives = []) Resolve dependencies for function call từ Container
 * @method static mixed resolveDependenciesForInvokableCall(\ReflectionMethod $reflector, array $primitives = []) Resolve dependencies for invokable call từ Container
 * @method static mixed resolveDependenciesForConstructorCall(\ReflectionMethod $reflector, array $primitives = []) Resolve dependencies for constructor call từ Container
 */
class Jankx extends Container
{
    /**
     * Tên của framework
     */
    const FRAMEWORK_NAME    = 'Jankx Framework';

    /**
     * Phiên bản hiện tại của framework
     */
    const FRAMEWORK_VERSION = '1.0.0.48';

    /**
     * ID của template engine
     */
    const ENGINE_ID         = 'jankx';

    /**
     * ID chính của framework
     */
    const PRIMARY_ID = 'primary';

    /**
     * Instance duy nhất của class
     *
     * @var Jankx
     */
    protected static $instance;

    /**
     * Dữ liệu template
     *
     * @var array
     */
    protected $templateData;

    /**
     * Stylesheet của template
     *
     * @var string
     */
    protected $templateStylesheet;

    /**
     * Theme hiện tại
     *
     * @var WP_Theme
     */
    protected $theme;

    /**
     * Admin instance
     *
     * @var Admin
     */
    protected $admin;

    /**
     * Danh sách các filters
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Option framework
     *
     * @var OptionFramework
     */
    protected $optionFramework;

    /**
     * Site layout
     *
     * @var SiteLayout
     */
    protected $siteLayout;

    /**
     * User experience manager
     *
     * @var UserExperience
     */
    protected $userExperience;

    /**
     * Widget manager
     *
     * @var WidgetManager
     */
    protected $widgets;

    /**
     * Social connects
     *
     * @var array
     */
    protected $socialConnects;

    /**
     * Text domain
     *
     * @var string
     */
    protected $textDomain;

    /**
     * Device detector
     *
     * @var object
     */
    protected static $device;

    /**
     * Xử lý các phương thức static không tồn tại
     *
     * @param string $name Tên phương thức
     * @param array $args Các tham số
     * @return mixed
     * @throws Exception
     */
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

    /**
     * Lấy giá trị của thuộc tính
     *
     * @param string $name Tên thuộc tính
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    /**
     * Lấy instance duy nhất của class
     *
     * @return Jankx
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->singleton(Container::class, self::class);
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        define('JANKX_CACHE_DIR', sprintf('%s/jankx/caches/', WP_CONTENT_DIR));
        define('JANKX_CACHE_DIR_URL', content_url('jankx/caches'));


        $this->parseThemeJson();
    }

    /**
     * Phân tích file theme.json
     */
    protected function parseThemeJson()
    {
        $templateJson   = join(DIRECTORY_SEPARATOR, [get_template_directory(), "theme.json"]);
        $themeJson      = join(DIRECTORY_SEPARATOR, [get_stylesheet_directory(), "theme.json"]);
        $templateConfig = file_exists($templateJson) ? json_decode(file_get_contents($templateJson), true) : [];

        // Create template name from parent theme or template name
        $templateConfig['template_name'] = array_get($templateConfig, 'name', self::FRAMEWORK_NAME);

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

    /**
     * Thiết lập framework
     */
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
     * Load các dependencies
     */
    private function includes()
    {
        if (defined('JANKX_CORE_DIRECTORY')) {
            $jankxVendor = realpath(JANKX_CORE_DIRECTORY);
            $fileNames = array(
                'functions.php'
            );

            foreach ($fileNames as $fileName) {
                $file = sprintf('%s/%s', $jankxVendor, $fileName);
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }
    }

    /**
     * Khởi tạo core framework
     */
    private function initCoreFramework()
    {
        $this->templateData = wp_get_theme();

        $this->theme = function () {
            return $this->templateData;
        };

        $this->templateStylesheet = function () {
            return $this->templateData->stylesheet;
        };

        $this->textDomain = $this->templateData->get('TextDomain');

        // Initialize kernel system
        $this->initKernelSystem();

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
        add_action('after_setup_theme', array($this, 'optimizePageSpeed'));

        if (wp_is_request('frontend')) {
            $templateLoader->load();
        }

        // Load post layout templates
        $templateLoader = new PostTemplateLoader();
        add_action('template_redirect', array($templateLoader, 'load'));

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
        $this->userExperience = &$userExperience;

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

    /**
     * Initialize kernel system
     */
    private function initKernelSystem()
    {
        // Create kernel manager
        $kernelManager = new \Jankx\Kernel\KernelManager($this);

        // Register kernels
        $kernelManager->registerKernel('frontend', \Jankx\Kernel\FrontendKernel::class);
        $kernelManager->registerKernel('admin', \Jankx\Kernel\AdminKernel::class);
        $kernelManager->registerKernel('api', \Jankx\Kernel\APIKernel::class);
        $kernelManager->registerKernel('cli', \Jankx\Kernel\CLIKernel::class);

        // Store kernel manager in container
        $this->singleton(\Jankx\Kernel\KernelManager::class, function () use ($kernelManager) {
            return $kernelManager;
        });

        // Boot kernels by context
        $kernelManager->bootKernelsByContext();
    }

    /**
     * Khởi tạo framework
     */
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


        do_action('jankx/initialized');
    }

    /**
     * Thiết lập option framework
     */
    public function setupOptionFramework()
    {
        $optionFramework = static::optionFramework();

        /**
         * Default mode is `auto` and Jankx auto search Option framework to use
         *
         * Hook `jankx_option_framework_mode`: You set the option framework via this hook
         */
        $actMode = get_option('jankx_option_framework', 'auto');
        $optionMode = apply_filters(
            'jankx_option_framework_mode',
            in_array($actMode, ['auto', 'wordpress']) ? 'auto' : $actMode
        );
        $optionFramework->setMode($optionMode);
        try {
            $optionFramework->loadFramework();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Cải thiện trải nghiệm người dùng
     */
    public function improveUserExperience()
    {
        $this->ux()->optimize();
    }


    /**!
     * Static methods
     */

    /**
     * Lấy thông tin thiết bị
     *
     * @return object
     */
    public static function device()
    {
        if (is_null(static::$device) && function_exists('jankx_get_device_detector')) {
            static::$device = jankx_get_device_detector();
        }
        return self::$device;
    }

    /**
     * Lấy template engine đang hoạt động
     *
     * @return string
     */
    public static function getActiveTemplateEngine()
    {
        return apply_filters(
            'jankx/template/engine/apply',
            Plates::ENGINE_NAME
        );
    }

    /**
     * Lấy text domain
     *
     * @return string
     */
    public static function getTextDomain()
    {
        $instance = static::getInstance();
        return $instance->textDomain;
    }

    /**
     * Render template
     *
     * @param string|array $templates Template cần render
     * @param array $data Dữ liệu truyền vào template
     * @param bool $echo Có echo kết quả hay không
     * @return string
     * @throws Exception
     */
    public static function render($templates, $data = array(), $echo = true)
    {
        $engine = Template::getEngine(static::ENGINE_ID);
        if (empty($engine)) {
            throw new \Exception('The Jankx template engine is not initialized');
        }

        return $engine->render($templates, $data, $echo);
    }

    /**
     * Load các tính năng bổ sung
     */
    public function loadExtraFeatures()
    {
        do_action('jankx/load/extra');

        // Support Mega Menu
        if (class_exists('\Jankx\Megu\Megu')) {
            call_user_func(['\Jankx\Megu\Megu', 'getInstance']);
        }

        // PageSpeed Insights Optimize
        $this->instance('pagespeed.modules', apply_filters('jankx/pagespeed/modules', [
            HTML5FixerModule::class
        ]));
    }

    /**
     * Đăng ký scripts cho admin
     */
    public function registerAdminScripts()
    {
        add_editor_style(jankx_core_asset_url('css/editor.css'));
    }

    /**
     * Thêm filter
     *
     * @param Filter $filterObject Đối tượng filter
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

    /**
     * Lấy user experience manager
     *
     * @return UserExperience
     */
    public function ux()
    {
        return $this->userExperience;
    }

    /**
     * Tối ưu tốc độ trang
     */
    public function optimizePageSpeed()
    {
        $activeModules = $this->get('pagespeed.modules');
        if (is_array($activeModules) && count($activeModules) > 0) {
            foreach ($activeModules as $moduleClass) {
                if (!is_a($moduleClass, GooglePagespeedModuleInterface::class, true)) {
                    continue;
                }
                $module = new $moduleClass();
                if ($module->startHook()) {
                    add_action($module->startHook(), [$module, 'init']);
                }
                if ($module->endHook()) {
                    add_action($module->endHook(), [$module, 'execute']);
                }
            }
        }
    }


    /**
     * Lấy tên template
     *
     * @return string
     */
    public static function templateName()
    {
        $template = Jankx::getInstance()->templateData->parent();
        if (empty($template)) {
            $template = Jankx::getInstance()->templateData;
        }

        return GlobalConfigs::get(
            'theme.short_name',
            $template->get('Name')
        );
    }

    /**
     * Lấy tên theme
     *
     * @return string
     */
    public static function themeName()
    {
        return GlobalConfigs::get(
            'theme.name',
            static::getInstance()->templateData->get('Name')
        );
    }
}
