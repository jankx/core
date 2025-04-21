<?php

namespace Jankx\Customizers;

use Jankx;
use Jankx\Extra\Features\AuthorBoxFeature;

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
    }
}
