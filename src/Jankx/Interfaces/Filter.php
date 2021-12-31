<?php
namespace Jankx\Interfaces;

interface Filter
{
    public function getHooks();

    public function getPriority();

    public function getArgsCounter();

    public function getExecutor();
}
