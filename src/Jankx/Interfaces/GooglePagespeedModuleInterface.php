<?php

namespace Jankx\Interfaces;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

interface GooglePagespeedModuleInterface
{
    public function startHook();

    public function endHook();

    public function init();

    public function execute();
}
