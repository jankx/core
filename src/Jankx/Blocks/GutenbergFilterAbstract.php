<?php

namespace Jankx\Blocks;

abstract class GutenbergFilterAbstract implements GutenbergFilterInterface
{
    protected $filterTag;

    public function getFilterTag()
    {
        return $this->filterTag;
    }
}
