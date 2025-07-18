<?php

namespace Jankx\SiteLayout\Customizer;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class Header
{
    public function customize()
    {
        add_action('customize_register', array($this, 'registerLogoHeightControl'));
    }

    public function registerLogoHeightControl($wp_customize)
    {
        // Register logo height setting
        $wp_customize->add_setting('logo_height', array(
            'default'   => '60',
            'transport' => 'refresh',
        ));

        $wp_customize->add_control('logo_height', array(
            'label'      => __('Logo Height', 'jankx'),
            'section'    => 'title_tagline',
            'type'   => 'range',
            'description' => __('Max logo height will display on header'),
            'input_attrs' => array(
                'min' => 0,
                'max' => 500,
                'step' => 5,
            ),
        ));
    }
}
