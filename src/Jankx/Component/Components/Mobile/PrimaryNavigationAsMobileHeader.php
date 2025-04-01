<?php

namespace Jankx\Component\Components\Mobile;

use Jankx;
use Jankx\Component\Abstracts\MobileComponent;
use Jankx\Component\Constracts\ComponentPlatform;
use Jankx\Component\Constracts\ComponentViaActionHook;

class PrimaryNavigationAsMobileHeader extends MobileComponent implements ComponentViaActionHook, ComponentPlatform
{
    public function getActionHook()
    {
        return 'template_include';
    }

    public function getPriority()
    {
        return 20;
    }

    public function getName()
    {
        return 'primary-menu-as-mobile-header';
    }
    public function render($default = null)
    {
        // CUSTOM MENU ITEMS

        add_filter('wp_nav_menu_items', [$this, 'registerMenuItemForPrimaryMenu'], 10, 2);


        // return default values
        return $default;
    }

    public function registerMenuItemForPrimaryMenu($items, $args)
    {
        if ($args->theme_location !== Jankx::PRIMARY_ID) {
            return $items;
        }


        $li_atts          = array();
        $li_atts['id']    = 'toogle-header-menu';
        $li_atts['class'] = 'menu-item jankx-show-mobile-menu';
        $li_atts       = apply_filters('nav_menu_item_attributes', $li_atts, $args, 1);
        $li_attributes = jankx_generate_html_attributes($li_atts);

        $toggleButton = '<li ' . $li_attributes . '>' . jankx_get_toggle_hamburger_menu([], false) . '</li>';

        return $toggleButton . $items;
    }
}
