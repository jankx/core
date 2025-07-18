<?php

namespace Jankx\SiteLayout\Menu\Mobile;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\SiteLayout\Constracts\MobileMenuLayout;

class NavbarCollapse implements MobileMenuLayout
{
    const NAME = 'offcanvas';

    public function load()
    {
        add_filter('body_class', array($this, 'appendCollapseStyleToBody'));
        add_filter('jankx/component/mobile_header/render_hook', array($this, 'changeHookRenderMobileHeader'));
        add_filter('nav_menu_css_class', array($this, 'createCarretIconShowSubMenu'), 10, 3);

        add_action('jankx/component/header/content/before', array($this, 'openToggleWrapTag'), 12);
        add_action('jankx/component/header/content/after', array($this, 'closeToggleWrapTag'), 8);

        execute_script("<script>(function(d) {
            var toggleButton = d.querySelector('.menu-style-collapse .toggle-sp-menu-button');
            toggleButton.addEventListener('click', function(e) {
                var site_header = e.target.findParent('.jankx-site-header');
                if (site_header) {
                    site_header.toggleClass('show');

                    if (site_header.hasClass('show')) {
                        document.querySelector('html').addClass('menu-collapsed');
                    } else {
                        document.querySelector('html').removeClass('menu-collapsed');
                    }
                }
            });
        })(document)</script>");
    }

    public function appendCollapseStyleToBody($classes)
    {
        $classes[] = 'menu-style-collapse';

        return $classes;
    }

    public function changeHookRenderMobileHeader()
    {
        return 'jankx/component/header/content/before';
    }

    public function openToggleWrapTag()
    {
        echo sprintf('<div %s>', jankx_generate_html_attributes(array(
            'class' => 'header-collapse',
        )));
    }

    public function closeToggleWrapTag()
    {
        echo '</div> <!-- /.header-collapse -->';
    }

    public function createCarretIconShowSubMenu($classes, $item, $args)
    {
        if (in_array('menu-item-has-children', $classes)) {
            $args->after = jankx_template('common/after-has-children-menu-item', array(), false);
        } else {
            $args->after = '';
        }
        return $classes;
    }
}
