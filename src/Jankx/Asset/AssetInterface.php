<?php

namespace Jankx\Asset;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface AssetInterface
{
    public function call();

    public function register();
}
