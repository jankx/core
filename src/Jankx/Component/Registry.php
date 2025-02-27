<?php
namespace Jankx\Component;

use Jankx;
use Jankx\Component\Components\Row;
use Jankx\Component\Components\Column;
use Jankx\Component\Components\Header;
use Jankx\Component\Components\Footer;
use Jankx\Component\Components\HTML;
use Jankx\Component\Components\Template;
use Jankx\Component\Components\Logo;
use Jankx\Component\Components\Modal;
use Jankx\Component\Components\SearchForm;
use Jankx\Component\Components\Link;
use Jankx\Component\Components\Dropdown;
use Jankx\Component\Components\DataList;
use Jankx\Component\Components\BreakingNews;
use Jankx\Component\Components\Navigation;

use Jankx\Component\Constracts\ComponentViaActionHook;
use Jankx\Component\Constracts\ComponentPlatform;
use Jankx\Component\Components\MobileHeader;

class Registry
{
    protected static $components = array();
    protected static $hookComponents = array();

    public static function register($name, $componentClass)
    {
        if (isset(static::$components[$name])) {
            return;
        }
        if (!class_exists($componentClass)) {
            return;
        }
        // Register the component to global list
        static::$components[$name] = $componentClass;
    }

    public static function registerComponents()
    {
        static::$components = apply_filters(
            'jankx_components',
            array(
                Row::COMPONENT_NAME          => Row::class,
                Column::COMPONENT_NAME       => Column::class,
                Header::COMPONENT_NAME       => Header::class,
                Footer::COMPONENT_NAME       => Footer::class,
                HTML::COMPONENT_NAME         => HTML::class,
                Template::COMPONENT_NAME     => Template::class,
                Logo::COMPONENT_NAME         => Logo::class,
                Modal::COMPONENT_NAME        => Modal::class,
                SearchForm::COMPONENT_NAME   => SearchForm::class,
                Link::COMPONENT_NAME         => Link::class,
                Dropdown::COMPONENT_NAME     => Dropdown::class,
                DataList::COMPONENT_NAME     => DataList::class,
                'list'                       => DataList::class,
                BreakingNews::COMPONENT_NAME => BreakingNews::class,
                Navigation::COMPONENT_NAME   => Navigation::class,
            )
        );

        add_action('template_redirect', array(__CLASS__, 'loadComponentViaHooks'), 30);
    }

    public static function getComponents()
    {
        return static::$components;
    }

    public static function loadComponentViaHooks()
    {
        $components = array();
        if (apply_filters('jankx/template/header/mobile/enabled', true)) {
            $components[MobileHeader::COMPONENT_NAME] = MobileHeader::class;
        }
        $components = apply_filters('jankx/load/via_hook/components', $components);
        $platform = 'desktop';
        if (Jankx::device()->isMobile()) {
            $platform = 'mobile';
        } elseif (Jankx::device()->isTablet()) {
            $platform = 'tablet';
        }

        foreach ($components as $component_cls) {
            if (!is_a($component_cls, ComponentViaActionHook::class, true)) {
                continue;
            }
            $component = new $component_cls();
            $component->parseProps(apply_filters(
                "jankx/load/via_hook/component/{$component->getName()}/props",
                array()
            ));

            if (!is_a($component, ComponentPlatform::class) || in_array($platform, (array) $component->getPlatform())) {
                add_action(
                    $component->getActionHook(),
                    array($component, 'render'),
                    $component->getPriority()
                );

                static::$hookComponents[$component->getActionHook()][$component->getName()] = $component;
            }
        }
    }

    public static function getComponentViaHook($hookName, $componentName)
    {
        if (!isset(static::$hookComponents[$hookName], static::$hookComponents[$hookName][$componentName])) {
            return null;
        }
        return static::$hookComponents[$hookName][$componentName];
    }
}
