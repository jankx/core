<?php

namespace Jankx\Customizers;

use Jankx\Interfaces\CustomizerInterface;

abstract class BaseCustomizer implements CustomizerInterface
{
    protected static $instance;

    protected $isFilterHook = false;

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

    public function getMethod()
    {
        return [$this, 'custom'];
    }

    // This is placeholder method to override
    public function unload()
    {
    }

    public function isFilterHook(): bool
    {
        return $this->isFilterHook;
    }

    public function getPriority(): int
    {
        return 10;
    }

    public function isEnabled(): bool
    {
        return apply_filters(
            static::class . '_enabled',
            true
        );
    }
}
