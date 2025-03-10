<?php

namespace Jankx\Customizers;

use Jankx\Adapter\Options\Helper;

class PostThumbnailEffectCustomizer extends BaseCustomizer
{
    protected $isFilterHook = true;

    public function getExecuteHook(): ?string
    {
        return "jankx/thumbnail/classes";
    }

    public function applyHoverEffect($classes) {
        $classes[] = Helper::getOption(
            'jankx/thumbnail/effect',
            'shine-effect'
        );

        return $classes;
    }

    public function getMethod() {
        return [$this, 'applyHoverEffect'];
    }

    public function unload() {
        remove_filter(
            $this->getExecuteHook(),
            [$this, 'applyHoverEffect'],
            $this->getPriority()
        );
    }
}
