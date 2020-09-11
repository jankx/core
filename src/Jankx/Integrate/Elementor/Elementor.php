<?php
namespace Jankx\Integrate\Elementor;

use Jankx\Integrate\Constract;

class Elementor extends Constract
{
    public function integrate()
    {
        add_action('elementor/controls/controls_registered', array($this, 'registerCustomControlsf'));
        add_action('elementor/widgets/widgets_registered', array($this, 'registerCustomControls'));

        if (apply_filters('jankx_plugin_elementor_silent_mode', false)) {
            add_filter('elementor/editor/localize_settings', array( $this, 'removeElementPromtionWidgets' ));
        }
    }

    public function registerJankxControls($controlsManager)
    {
    }

    public function registerJankxWidgets($widgetsManager)
    {
    }

    public function removeElementPromtionWidgets($config)
    {
        // Remove Elementor promotion widgets to look good
        if (isset($config['promotionWidgets'])) {
            unset($config['promotionWidgets']);
        }

        return $config;
    }
}
