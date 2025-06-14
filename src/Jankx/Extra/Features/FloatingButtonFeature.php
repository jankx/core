<?php

namespace Jankx\Extra\Features;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

class FloatingButtonFeature
{
    public function __construct()
    {
        add_action('jankx/template/footer/after', [$this, 'renderFloatingButtons'], 999);
    }


    public function renderFloatingButtons()
    {
        $buttons = [];
        $buttons = apply_filters('jankx/buttons/floating', $buttons);
        if (empty($buttons)) {
            return;
        }

        return jankx_template('buttons/floating', apply_filters('jankx/buttons/floating/data', [
            'buttons' => $buttons,
            'effect' => 'wiggle',
            'grouped' => false,
            'style' => 'default',
            'target' => '_blank'
        ]));
    }
}
