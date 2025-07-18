<?php

namespace Jankx\SiteLayout\Menu\Mobile;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\SiteLayout\Constracts\MobileMenuLayout;

class Slideout implements MobileMenuLayout
{
    const NAME = 'slideout';

    public function load()
    {
        add_action('jankx/template/header/before', array($this, 'openSlideoutMenu'), 15);
        add_action('jankx/template/header/after', array($this, 'closeSlideoutMenu'), 5);

        add_action('jankx/template/header/after', array($this, 'openMainPanel'), 9);
        add_action('wp_footer', array($this, 'closeMainPanel'), 1);
        add_action('body_class', array($this, 'appendSlideoutClassToBody'));

        add_filter('jankx_asset_js_dependences', function ($deps) {
            $deps[] = 'slideout';
            return $deps;
        });

        $slideDirection = apply_filters('slideout_menu_direction', 'left');
        $enableTouch    = apply_filters('slideout_menu_touch', false) ? 'true' : 'false';

        execute_script("<script>window.slideout = new Slideout({
            'panel': document.getElementById('main-panel'),
            'menu': document.getElementById('mobile-menu'),
            'padding': 256,
            'tolerance': 70,
            'touch': {$enableTouch},
            'side': '{$slideDirection}'
          });

          // Toggle button
          var toogleButton = document.querySelector('.toggle-sp-menu-button');
          if (toogleButton) {
            toogleButton.addEventListener('click', function() {
              slideout.toggle();
            });
          }
          </script>");
    }

    public function appendSlideoutClassToBody($classes)
    {
        $classes[] = 'menu-style-slideout';
        return $classes;
    }

    public function openMainPanel()
    {
        ?>
        <div id="main-panel" class="slideout-panel">
        <?php
    }

    public function closeMainPanel()
    {
        ?>
        </div> <!-- end #main-panel block -->
        <?php
    }

    public function openSlideoutMenu()
    {
        ?>
        <nav id="mobile-menu" class="slideout-menu">
        <?php
    }

    public function closeSlideoutMenu()
    {
        ?>
        </nav>
        <?php
    }
}
