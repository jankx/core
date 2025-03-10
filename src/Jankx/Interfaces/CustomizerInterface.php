<?php

namespace Jankx\Interfaces;

interface CustomizerInterface
{
    public function isEnabled(): bool;

    public function getExecuteHook(): ?string;

    public function getMethod();

    public function getPriority(): int;

    public function unload();

    public function isFilterHook(): bool;
}
