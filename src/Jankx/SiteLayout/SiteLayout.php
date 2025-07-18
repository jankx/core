<?php

namespace Jankx\SiteLayout;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\GlobalConfigs;
use Jankx\SiteLayout\Admin\Metabox\PostLayout;
use Jankx\SiteLayout\Constracts\MobileMenuLayout;
use Jankx\SiteLayout\Admin\Menu\JankxItems;
use Jankx\SiteLayout\Customizer\Header as HeaderCustomizer;
use Jankx\SiteLayout\Menu\Mobile\SecondaryMenuOffcanvas;
use Jankx\SiteLayout\Menu\Mobile\Slideout;
use Jankx\SiteLayout\Menu\Mobile\NavbarCollapse;
use Jankx\SiteLayout\Menu\SecondaryNavigation;
use Jankx\Template\Page;

use function get_current_screen;

class SiteLayout
{
    const LAYOUT_FULL_WIDTH               = 'jankx-fw';
    const LAYOUT_CONTENT_SIDEBAR          = 'jankx-cs';
    const LAYOUT_SIDEBAR_CONTENT          = 'jankx-sc';
    const LAYOUT_CONTENT_SIDEBAR_SIDEBAR  = 'jankx-css';
    const LAYOUT_SIDEBAR_CONTENT_SIDEBAR  = 'jankx-scs';
    const LAYOUT_SIDEBAR_SIDEBAR_CONTENT  = 'jankx-ssc';

    protected static $instance;
    protected static $sidebarName;
    protected static $mobileMenus;

    protected $currentLayout;
    protected $menu;

    public $layoutLoader;

    protected $templateEngine;

    protected $pageTemplates;

    protected $defaultLayout;

    /**
     * @var \Jankx\Template\Page
     */
    protected $currentPage;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        if (!defined('JANKX_SITE_LAYOUT_LOAD_FILE')) {
            define('JANKX_SITE_LAYOUT_LOAD_FILE', __FILE__);
        }

        if (!defined('JANKX_SITE_LAYOUT_DIR')) {
            define('JANKX_SITE_LAYOUT_DIR', realpath(dirname(JANKX_SITE_LAYOUT_LOAD_FILE) . '/..'));
        }

        $this->loadFeatures();
        $this->initHooks();
    }

    public static function getSidebarName($name = null)
    {
        if (is_null($name)) {
            return static::$sidebarName;
        }
        static::$sidebarName = $name;
    }

    protected function loadFeatures()
    {
        $footerBuilder = new FooterBuilder();
        $footerBuilder->build();

        $this->menu = new JankxItems();

        $headerCustomizer = new HeaderCustomizer();
        $headerCustomizer->customize();

        if (is_admin()) {
            new Admin();
        }
    }

    /**
     * @return \Jankx\Template\Page
     */
    protected function getCurrentPage()
    {
        if (is_null($this->currentPage)) {
            $this->currentPage = Page::getInstance();
        }
        return $this->currentPage;
    }

    protected function initHooks()
    {
        add_action('init', array($this, 'registerMenus'));
        add_action('widgets_init', array($this, 'registerSidebars'), 5);
        add_action('jankx/template/renderer/pre', array($this, 'buildLayout'));

        add_action('get_sidebar', array(SiteLayout::class, 'getSidebarName'));
        add_action('init', array($this->menu, 'register'));

        add_action('wp_head', array($this, 'metaViewport'), 5);
        add_filter('body_class', array($this, 'bodyClasses'));
        add_action('template_redirect', array($this, 'createMobileMenu'), 5);

        add_filter(
            'jankx/template/tag/html/classes',
            array($this, 'appendMobileLayoutToHtmlClass')
        );

        if (GlobalConfigs::get('customs.layout.menu.secondary.enable', false)) {
            $secondaryNavigation = new SecondaryNavigation();
            add_action('wp', [$secondaryNavigation, 'init']);
        }
    }

    public function registerMenus()
    {
        $menus = array(
            'primary' => __('Primary Menu', 'jankx'),
        );

        if (GlobalConfigs::get('customs.layout.menu.secondary.enable', false)) {
            $menus['secondary'] = __('Second Menu', 'jankx');
        }

        register_nav_menus(
            apply_filters(
                'jankx_site_layout_register_menus',
                $menus
            )
        );
    }

    public function setPageTemplates($templates)
    {
        $this->pageTemplates = $templates;
    }

    public function getPageTemplates()
    {
        return empty($this->pageTemplates) ? [] : $this->pageTemplates;
    }


    public function setDefaultLayout($layout)
    {
        $this->defaultLayout = $layout;
    }


    public function setTemplateEngine($templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    public function getTemplateEngine()
    {
        return $this->templateEngine;
    }

    public function buildLayout()
    {
        /**
         * Load template for site layout
         */
        $this->layoutLoader = $this->getCurrentPage()->isGutenbergSupport()
            ? new GutenbergLayoutLoader(
                $this->getLayout(),
                $this->getTemplateEngine()
            )
            : new LayoutLoader(
                $this->getLayout(),
                $this->getTemplateEngine()
            );

            $this->layoutLoader->load();
    }

    public function bodyClasses($classes)
    {
        if (apply_filters('jankx/layout/based/common-css', true)) {
            $classes[] = 'jankx-base';
        }

        $classes[] = apply_filters('jankx/layout/site/menu/styles', 'default-navigation');
        $classes[] = $this->getLayout();

        return $classes;
    }

    public function getSupportLayouts()
    {
        $layouts = apply_filters('jankx/site/layouts', array(
            static::LAYOUT_FULL_WIDTH               => __('Full Width', 'jankx'),
            static::LAYOUT_CONTENT_SIDEBAR          => __('Content Sidebar', 'jankx'),
            static::LAYOUT_SIDEBAR_CONTENT          => __('Sidebar Content', 'jankx'),
            static::LAYOUT_CONTENT_SIDEBAR_SIDEBAR  => __('Content Sidebar Sidebar', 'jankx'),
            static::LAYOUT_SIDEBAR_CONTENT_SIDEBAR  => __('Sidebar Content Sidebar', 'jankx'),
            static::LAYOUT_SIDEBAR_SIDEBAR_CONTENT  => __('Sidebar Sidebar Content', 'jankx'),
        ));

        return $layouts;
    }

    public function getLayout($skipDefault = false)
    {
        if (is_null($this->currentLayout)) {
            $this->currentLayout = $this->getCurrentLayout();

            if (empty($this->currentLayout)) {
                if ($skipDefault) {
                    return $this->currentLayout;
                }
                $this->currentLayout = $this->getDefaultLayout();
            }
        }

        $this->getCurrentPage()
            ->setLoadedLayout($this->currentLayout);

        return apply_filters('jankx/layout/site/currentLayout', $this->currentLayout);
    }

    public function getCurrentLayout()
    {
        $pre = apply_filters('jankx/layout/site/pre', null);
        if (!empty($pre)) {
            return $pre;
        }

        if (is_admin()) {
            $currentScreen = get_current_screen();
            if ($currentScreen->base === 'post') {
                $post_id = isset($_GET['post']) ? (int)$_GET['post'] : 0;
                return get_post_meta($post_id, PostLayout::POST_LAYOUT_META_KEY, true);
            }
        }

        if (is_singular()) {
            return get_post_meta(get_the_ID(), PostLayout::POST_LAYOUT_META_KEY, true);
        }
    }

    public function getDefaultLayout()
    {
        $defaultLayout = $this->defaultLayout;
        if (is_null($defaultLayout)) {
            $defaultLayout = (is_singular('post') ? static::LAYOUT_CONTENT_SIDEBAR : static::LAYOUT_FULL_WIDTH);
        }

        return apply_filters(
            'jankx/site/layout/default',
            $defaultLayout
        );
    }

    public function registerSidebars()
    {
        $primaryArgs = apply_filters('jankx/layout/sidebars/register/args', array(
            'id' => 'primary',
            'name' => __('Primary Sidebar', 'jankx'),
            'before_widget' => '<section id="%1$s" class="widget jankx-widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="jankx-title widget-title"><span>',
            'after_title' => '</span></h3>'
        ));
        register_sidebar($primaryArgs);

        if (apply_filters('jankx/layout/sidebars/alternative/enabled', true)) {
            $secondaryArgs = apply_filters('jankx/layout/sidebars/secondary/register/args', array(
                'id' => 'secondary',
                'name' => __('Secondary Sidebar', 'jankx'),
                'before_widget' => '<section id="%1$s" class="widget jankx-widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="jankx-title widget-title"><span>',
                'after_title' => '</span></h3>'
            ));
            register_sidebar($secondaryArgs);
        }
    }

    public function metaViewport()
    {
        if (!apply_filters('jankx/layout/responsive/enabled', true)) {
            return;
        }
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?php
    }

    public static function getMobileMenus()
    {
        if (is_null(static::$mobileMenus)) {
            static::$mobileMenus = apply_filters(
                'jankx/layout/site/mobile/menus',
                array(
                    Slideout::NAME => Slideout::class,
                    NavbarCollapse::NAME => NavbarCollapse::class,
                    SecondaryMenuOffcanvas::NAME => SecondaryMenuOffcanvas::class,
                )
            );
        }
        return static::$mobileMenus;
    }

    public function createMobileMenu()
    {
        // Check theme enable mobile menu
        if (!apply_filters('jankx/layout/mobile/menu/enabled', true)) {
            return;
        }
        $menus = static::getMobileMenus();
        $useMenu = apply_filters(
            'jankx/layout/site/mobile/menu/apply',
            !GlobalConfigs::get('customs.layout.menu.secondary.enable', false)  ? Slideout::NAME : SecondaryMenuOffcanvas::NAME
        );

        if (isset($menus[$useMenu])) {
            $mobileMenu = new $menus[$useMenu]();
            if (is_a($mobileMenu, MobileMenuLayout::class)) {
                $mobileMenu->load();
            }
        }
    }

    public function appendMobileLayoutToHtmlClass($classes)
    {
        if (jankx_is_mobile()) {
            $classes[] = 'jankx-mobile';
        }

        return $classes;
    }
}
