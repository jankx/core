<?php

namespace Jankx\Component\Constracts;

interface ComponentViaActionHook extends Component
{
    public function getActionHook();

    public function getPriority();
}
