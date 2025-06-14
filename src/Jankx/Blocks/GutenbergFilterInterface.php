<?php

namespace Jankx\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

interface GutenbergFilterInterface
{
    public function getFilterTag();

    public function apply($blockContent, $parsedBlock, $wpBlock);
}
