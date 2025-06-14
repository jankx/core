<?php

namespace Jankx;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Interfaces\Filter as FilterInterface;

abstract class Filter implements FilterInterface
{
    protected $priority = 10;
    protected $hooks = [];
    protected $argsCounter = 1;
    protected $executor = "execute";

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            trigger_error("Property $name doesn't exists and cannot be get.", E_USER_ERROR);
            return null;
        }
        return $this->$name;
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            trigger_error("Property $name doesn't exists and cannot be set.", E_USER_ERROR);
            return false;
        }
        $this->$name = $value;
    }

    public function getHooks()
    {
        if (is_array($this->hooks)) {
            return $this->hooks;
        }
        return [$this->hooks];
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getArgsCounter()
    {
        return $this->argsCounter;
    }

    public function getExecutor()
    {
        return $this->executor;
    }
}
