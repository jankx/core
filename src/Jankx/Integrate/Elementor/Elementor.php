<?php
namespace Jankx\Integrate\Elementor;

use ReflectionClass;
use Jankx\Integrate\Constract;

class Elementor extends Constract
{
    public function integrate()
    {
        add_action('elementor/controls/controls_registered', array($this, 'registerJankxControls'));
        add_action('elementor/widgets/widgets_registered', array($this, 'registerJankxWidgets'));

        if (apply_filters('jankx_ecommerce_elementor_active_woocommerce_tab', true)) {
            add_action('elementor/elements/categories_registered', array($this, 'customWidgetCategories'));
        }
        if (apply_filters('jankx_plugin_elementor_silent_mode', false)) {
            add_filter('elementor/editor/localize_settings', array( $this, 'removeElementPromtionWidgets'));
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

    public function customWidgetCategories($elementManager)
    {
        $reflectElementManager = new ReflectionClass($elementManager);
        $widgetCategoryRefProp = $reflectElementManager->getProperty('categories');
        $widgetCategoryRefProp->setAccessible(true);

        $widgetCategory = $widgetCategoryRefProp->getValue($elementManager);

        do_action(
            'jankx_integrate_elementor_custom_widget_category',
            $widgetCategoryRefProp,
            $widgetCategory,
            $elementManager
        );
    }
}
