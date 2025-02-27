<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;
use Jankx\Option\Option;

class Header extends Component
{
    const COMPONENT_NAME = 'header';

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    protected function createChildCompontsFromPreset($presetName)
    {
        if ($presetName === 'default') {
            add_filter(
                'jankx_compont_header_preset_default_components',
                array(__CLASS__, 'createDefaultPreset')
            );
        }

        return apply_filters(
            "jankx_compont_header_preset_{$presetName}_components",
            array()
        );
    }

    public function parseProps($props)
    {
        $this->props = wp_parse_args($props, array(
            'preset' => 'default',
            'sticky' => false,
            'display' => 'flex',
        ));

        $this->props = apply_filters(
            'jankx_component_header_props',
            $this->props
        );

        if ($this->props['preset'] !== 'none') {
            $this->addChildren(static::createChildCompontsFromPreset($this->props['preset']));
        }
    }

    public static function createDefaultPreset($components)
    {
        array_push($components, new Template(array(
            'template_file' => array(
                'partials/header/before'
            ),
        )));

        array_push($components, new Navigation(array(
            'theme_location' => apply_filters('jankx/component/header/preset/default/menu_location', 'primary'),
            'show_home'      => true,
        )));

        array_push($components, new Template(array(
            'template_file' => array(
                'partials/header/after'
            ),
        )));

        return $components;
    }

    public function buildComponentData()
    {
        $header_id = 'jankx-site-header';
        $header_classes = array('jankx-site-header');
        if ($this->props['sticky']) {
            $header_classes[] = 'sticky-header';
        }

        return array(
            'content' => $this->renderChildren(),
            'header_id' => $header_id,
            'header_class' => $header_classes,
        );
    }

    public function render()
    {
        $data = $this->buildComponentData();

        return $this->renderViaEngine(
            'header',
            array(
                'content' => $data['content'],
                'attributes' => jankx_generate_html_attributes(array(
                    'class' => $data['header_class'],
                    'id' => $data['header_id'],
                ))
            ),
            false
        );
    }
}
