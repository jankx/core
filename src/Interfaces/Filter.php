<?php

namespace Jankx\Interfaces;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface Filter
{
    public function getHooks();

    public function getPriority();

    public function getArgsCounter();

    public function getExecutor();
}
