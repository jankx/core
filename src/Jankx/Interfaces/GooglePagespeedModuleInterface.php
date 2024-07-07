<?php

namespace Jankx\Interfaces;

interface GooglePagespeedModuleInterface
{
    public function startHook();

    public function endHook();

    public function init();

    public function execute();
}
