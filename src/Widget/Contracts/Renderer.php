<?php

namespace Jankx\Widget\Contracts;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface Renderer
{
    public function render();

    public function setTemplateEngine($templateEngine);
}
