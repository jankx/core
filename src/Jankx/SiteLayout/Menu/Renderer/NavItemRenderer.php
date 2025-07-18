<?php

namespace Jankx\SiteLayout\Menu\Renderer;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\SiteLayout\Admin\Menu\JankxItems;
use Jankx\Adapter\Options\Helper;

class NavItemRenderer
{
    protected $hook_walker_nav_menu_start_el_is_called;
    protected $logo_is_added;

    public function resetWalkerSupportHookStartEl($sorted_menu_items)
    {
        $this->hook_walker_nav_menu_start_el_is_called = false;

        foreach ($sorted_menu_items as $index => $item) {
            $metas = get_post_custom($item->ID);

            if (isset($metas['_jankx_menu_item_width'])) {
                $item->classes = array_merge(
                    $item->classes,
                    array_map(function ($width) {
                        return 'jkxw-' . $width;
                    }, $metas['_jankx_menu_item_width'])
                );
            }
            if (isset($metas['_jankx_menu_item_position'])) {
                $item->classes = array_merge(
                    $item->classes,
                    array_map(function ($position) {
                        return 'jkxp-' . $position;
                    }, $metas['_jankx_menu_item_position'])
                );
            }
            $sorted_menu_items[$index] = $item;
        }

        // Does not do anythin and return $sorted_menu_items
        return $sorted_menu_items;
    }

    public function getJankxLogo($item, $depth = 0, $args = array())
    {
        // Create a flag logo is added
        $this->logo_is_added = true;


        return jankx_component('logo', array(
            'text' => $item->post_title,
        ));
    }

    public function getJankxSearchForm($item, $depth, $args)
    {
        return get_search_form(array(
            'echo' => false,
        ));
    }

    public function getJankxHotline($item, $depth, $args)
    {
        $hotline = $item->post_title;
        if (Helper::getOption('contact_hotline')) {
            $hotline = Helper::getOption('contact_hotline');
        }
        return apply_filters('jankx/contact/hotline', sprintf('<a href="tel://%s">%s</a>', preg_replace('/[^\d]/', '', $hotline), $hotline), $item->ID);
    }

    protected function getContent($item_output, $item, $depth, $args)
    {
        $method = sprintf("get%s", preg_replace_callback(array('/^([a-z])/', '/[-_]([a-z])/'), function ($matches) {
            if (isset($matches[1])) {
                return strtoupper($matches[1]);
            }
        }, $item->type));
        $callable = apply_filters('jankx_site_layout_nav_item_callback', array($this, $method), $item, $depth, $args);

        if (!is_callable($callable)) {
            return $item_output;
        }

        return call_user_func($callable, $item, $depth, $args);
    }

    public function renderMenuItem($item_output, $item, $depth, $args)
    {
        // Create the flag to
        if (!$this->hook_walker_nav_menu_start_el_is_called) {
            $this->hook_walker_nav_menu_start_el_is_called = true;
        }
        $jankxItems = JankxItems::get_nav_items();

        if (isset($jankxItems[$item->type])) {
            $pre = apply_filters("jankx_site_layout_nav_item_{$item->type}", null, $item_output, $item, $depth, $args);
            if ($pre) {
                return $pre;
            }
            $content = $this->getContent($item_output, $item, $depth, $args);
            if ($content) {
                $item_output = $content;
            }
        }

        return $item_output;
    }

    public function renderMenuItemSubtitle($title, $item, $args, $depth)
    {
        $subtitle = get_post_meta($item->ID, '_jankx_menu_item_subtitle', true);
        $subtitle_position = get_post_meta($item->ID, '_jankx_menu_item_subtitle_position', true);
        if (!$subtitle) {
            return $title;
        }
        if (!$subtitle_position) {
            $subtitle_position = 'bottom';
        }

        if ($subtitle_position === 'top') {
            return sprintf(
                '<span class="jankx-subtitle position-%s menu-item-subtitle">%s</span>%s',
                $subtitle_position,
                $subtitle,
                $title
            );
        }
        return sprintf(
            '%s<span class="jankx-subtitle position-%s menu-item-subtitle">%s</span>',
            $title,
            $subtitle_position,
            $subtitle
        );
    }

    public function checkLogoIsAdded()
    {
        if (apply_filters('jankx_site_layout_disable_auto_add_logo_menu_item', null)) {
            return true;
        }
        return (bool)$this->logo_is_added;
    }

    public function customDisplayMenuItem($items, $args)
    {
        // Auto add logo menu item when primary menu doesn't has logo
        if ($this->checkLogoIsAdded() || $args->theme_location !== 'primary') {
            return $items;
        }

        $item = new \StdClass();
        $item->post_title = get_bloginfo('name');

        return sprintf(
            '<li id="menu-item-customm-jankx-logo" class="menu-item menu-item-type-jankx-logo menu-item-logo">%s</li>%s',
            $this->getJankxLogo($item),
            $items
        );
    }
}
