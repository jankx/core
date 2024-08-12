<?php

namespace Jankx\Interfaces;

interface CustomizerInterface
{
    public function isEnabled(): bool;

    public function getExecuteHook(): ?string;

    public function custom();

    public function unload();
}
