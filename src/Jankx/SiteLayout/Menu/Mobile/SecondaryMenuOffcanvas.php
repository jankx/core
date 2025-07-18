<?php

namespace Jankx\SiteLayout\Menu\Mobile;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx;
use Jankx\SiteLayout\Constracts\MobileMenuLayout;

class SecondaryMenuOffcanvas implements MobileMenuLayout
{
    const NAME = 'secondary-nav-slideout';

    public function load()
    {
        add_filter('jankx_asset_js_dependences', function ($deps) {

            $deps[] = 'mmenu-light';
            return $deps;
        });

        add_filter('jankx_asset_css_dependences', function ($deps) {

            $deps[] = 'mmenu-light';
            return $deps;
        });

        add_filter('jankx_component_navigation_secondary_args', function ($args) {

            // $args['container_class'] = 'mm-menu mm-horizontal mm-offcanvas';
            $args['container_id'] = 'offcanvas-menu';
            return $args;
        });

        add_action('body_class', array($this, 'addMmenuToBodyClasses'));
        add_action('jankx/template/header/before', [$this, 'openMmenuPageSection'], 5);
        add_action('jankx/template/footer/after', [$this, 'closeMmenuPageSection'], 25);

        $navigationOptions = apply_filters('jankx/mmenu/navigation/options', [
            'theme' => "dark",
            'slidingSubmenus' => true,
            'title' => Jankx::themeName()
        ]);

        $offcanvasOptions = apply_filters('jankx/mmenu/canvas/options', []);

        execute_script('<script>
                function toggleClassByFlag(cls, tag, flag) {
                    const el = document.querySelector(tag);
                    if (!el) {
                        return;
                    }
                    if (!flag) {
                        el.classList.remove(cls);
                    } else if (!el.classList.contains(cls)) {
                        el.classList.add(cls);
                    }
                }

                function checkScreenForMmenu() {
                    const panelWidth = (window.innerWidth * 80 / 100) + 35;
                    toggleClassByFlag("mm-wide-panel", "body", panelWidth > 440);
                }


                checkScreenForMmenu();
                const mmenu = new MmenuLight(
                    document.querySelector( "#offcanvas-menu" ),
                    "(max-width: 767px)"
                );

                const navigator = mmenu.navigation(' . json_encode($navigationOptions) . ');
                const drawer = mmenu.offcanvas(' . json_encode($offcanvasOptions) . ');
                document.querySelector( "button.toggle-sp-menu-button" )
                    .addEventListener( "click", ( event ) => {
                        event.preventDefault();
                        drawer.open();
                    });
                window.addEventListener("resize", checkScreenForMmenu);
                document.addEventListener("DOMContentLoaded", checkScreenForMmenu);
            </script>');
    }


    public function addMmenuToBodyClasses($classes)
    {
        $classes[] = 'mmenu-offcanvas';
        $classes[] = 'mm-wrapper';

        return $classes;
    }


    public function openMmenuPageSection() {
        ?>
        <!-- Open #page -->
         <div id="page">
        <?php
    }

    public function closeMmenuPageSection() {
        ?>
        </div>
        <!-- close #page -->
         <?php
    }
}
