<?php

namespace Jankx\Component\Constracts;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

interface ComponentViaActionHook extends Component
{
    public function getActionHook();

    public function getPriority();
}
