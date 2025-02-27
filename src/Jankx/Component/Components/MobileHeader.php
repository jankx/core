<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;
use Jankx\Component\Constracts\ComponentViaActionHook;
use Jankx\Component\Constracts\ComponentPlatform;

class MobileHeader extends Component implements ComponentViaActionHook, ComponentPlatform
{
    const COMPONENT_NAME = 'mobile_header';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function getActionHook()
    {
        return apply_filters(
            'jankx/component/mobile_header/render_hook',
            'jankx/template/header/after'
        );
    }

    public function getPriority()
    {
        return apply_filters(
            'jankx/component/mobile_header/render_hook/priority',
            10
        );
    }

    public function getPlatform()
    {
        return apply_filters(
            'jankx/component/mobile_header/platforms',
            static::PLATFORM
        );
    }

    protected function getMobileHeaderElements()
    {
        $elements = array(
            'logo' => array(
                'callback' => 'jankx_get_logo_image',
            ),
            'hamburger' => array(
                'text' => __('Menu', 'jankx'),
                'icon' => '',
                'image' => '',
                'callback' => 'jankx_get_toggle_hamburger_menu',
            )
        );

        $elements = apply_filters('jankx/component/mobile_header/elements', $elements);

        return array_map(function ($props) {
            return wp_parse_args($props, array(
                'text' => '',
                'icon' => '',
                'image' => '',
                'callback' => '',
            ));
        }, $elements);
    }

    public function render()
    {
        $attributes = array(
            'id' => 'jankx-mobile-header',
            'class' => 'mobile-header',
        );
        echo sprintf('<div %s>', jankx_generate_html_attributes($attributes));
        do_action('jankx/component/mobile_header/content/before');

        $this->renderViaEngine('mobile/header', array(
            'elements' => $this->getMobileHeaderElements(),
        ));

        do_action('jankx/component/mobile_header/content/after');
        echo '</div>';
    }
}
