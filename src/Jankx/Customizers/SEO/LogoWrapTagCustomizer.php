<?php

namespace Jankx\Customizers\SEO;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Customizers\BaseCustomizer;
use Jankx\GlobalConfigs;

class LogoWrapTagCustomizer extends BaseCustomizer
{
    protected $isFilterHook = false;

    public function getExecuteHook(): ?string
    {
        return 'jankx/html/logo/tag';
    }

    public function getMethod()
    {
        return [$this, 'changeLogoTag'];
    }

    public function changeLogoTag($tag)
    {
        if (!GlobalConfigs::get('customs.seo.logo_tag.h1', true)) {
            return $tag;
        }
        if (is_home() || is_front_page()) {
            return 'h1';
        }
        return $tag;
    }
}
