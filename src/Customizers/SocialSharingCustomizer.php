<?php

namespace Jankx\Customizers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Adapter\Options\Helper;
use Jankx\Widget\Renderers\SocialSharingRenderer;

class SocialSharingCustomizer extends BaseCustomizer
{
    protected $positions = [
        'after_post_meta' => 'jankx/post/metas/after'
    ];
    public function getExecuteHook(): ?string
    {
        $position = Helper::getOption('social_sharing_position', 'after_post_meta');

        $renderHook = isset($this->options[$position]) ? $this->positions[$position] : 'jankx/post/metas/after';

        return apply_filters('jankx/socials/sharing/hook', $renderHook);
    }


    public function custom()
    {
        $renderer = new SocialSharingRenderer();
        echo $renderer->render();
    }
}
