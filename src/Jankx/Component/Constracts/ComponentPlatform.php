<?php

namespace Jankx\Component\Constracts;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface ComponentPlatform
{
    public function getPlatform();
}
