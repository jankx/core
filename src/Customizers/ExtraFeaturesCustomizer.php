<?php

namespace Jankx\Customizers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx;
use Jankx\Adapter\Options\Helper;
use Jankx\Extra\Features\AuthorBoxFeature;
use Jankx\Extra\Features\FloatingButtonFeature;

class ExtraFeaturesCustomizer extends BaseCustomizer
{
    public function getExecuteHook(): ?string
    {
        return 'wp';
    }


    public function custom()
    {
        if (is_singular(apply_filters('jankx/extra/authorbox/post_types', ['post']))) {
            Jankx::getInstance()->instance(AuthorBoxFeature::class, new AuthorBoxFeature());
        }


        if (Helper::getOption('floating_button_enabled', false)) {
            Jankx::getInstance()->instance(FloatingButtonFeature::class, new FloatingButtonFeature());
        }
    }
}
