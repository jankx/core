<?php

namespace Jankx\Component\Constracts;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface Component
{
    public function getName();

    public static function getTemplateEngine();

    public function parseProps($props);

    public function buildComponentData();

    public function renderViaEngine();

    public function render();

    public function __toString();

    public function echo();
}
