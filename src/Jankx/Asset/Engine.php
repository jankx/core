<?php
namespace Jankx\Asset;

use Jankx\TemplateEngine\Engines\Plates;

class Engine extends Plates
{
    const ENGINE_NAME = 'jankx_asset';

    protected $extension = null;
}
