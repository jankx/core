<?php

namespace Jankx\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface BlockInterface
{
    public function getType();

    public function register();

    public function setBlockBaseDirectory($directory);

    public function getBlockBaseDirectory();

    public function getJsonFile();

    public function getBlockJson(): array;

    public function isServerSideRender(): bool;

    public function render($data, $content);

    public function isEnabled(): bool;

    public function get_responsive_setting($key, $defaultValue);
}
