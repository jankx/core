<?php
namespace Jankx\Component\Constracts;

interface ComponentViaActionHook
{
    public function getActionHook();

    public function getPriority();
}
