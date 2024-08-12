<?php

namespace Jankx\Customizers;

use Jankx\Interfaces\CustomizerInterface;

abstract class BaseCustomizer implements CustomizerInterface
{
    protected static $instance;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }


    // This is placeholder method to override
    public function unload()
    {
    }
}
