<?php

namespace Jankx\Asset;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\TemplateEngine\Engines\Plates;

class Engine extends Plates
{
    const ENGINE_NAME = 'jankx_asset';

    protected $extension = null;
}
