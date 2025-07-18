<?php

namespace Jankx\Widget\Renderers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class SocialSharingRenderer extends Base
{
    public function render()
    {
        $pre = apply_filters('jankx/socials/sharing/pre', null, $this);
        if (!is_null($pre)) {
            return $pre;
        }

        $buttons = apply_filters('jankx/socials/sharing/buttons', array(
            'fbButton' => 'Facebook',
            'tw' => 'Twitter',
            'pinterest' => 'Pinterest',
            'linkedin' => 'Linkedin',
            'whatsapp' => 'WhatsApp',
            'viber' => 'Viber',
            'email' => 'Email',
            'telegram' => 'Telegram',
            'line' => 'Line'
        ));

        jankx_social_share_buttons($buttons);
    }
}
