<?php

namespace Jankx\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface GutenbergFilterInterface
{
    public function getFilterTag();

    public function apply($blockContent, $parsedBlock, $wpBlock);
}
