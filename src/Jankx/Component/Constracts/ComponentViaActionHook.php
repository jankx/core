<?php

namespace Jankx\Component\Contracts;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface ComponentViaActionHook extends Component
{
    public function getActionHook();

    public function getPriority();
}
