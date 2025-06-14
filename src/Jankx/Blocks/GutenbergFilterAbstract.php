<?php

namespace Jankx\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

abstract class GutenbergFilterAbstract implements GutenbergFilterInterface
{
    protected $filterTag;

    public function getFilterTag()
    {
        return $this->filterTag;
    }
}
