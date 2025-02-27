<?php

namespace Jankx\Blocks;

interface GutenbergFilterInterface
{
    public function getFilterTag();

    public function apply($blockContent, $parsedBlock, $wpBlock);
}
