<?php

namespace Jankx\Component\Contracts;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface ComponentPlatform
{
    public function getPlatform();
}
