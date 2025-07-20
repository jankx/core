<?php

namespace Jankx\Providers;

use Illuminate\Container\Container;

abstract class ServiceProvider
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    abstract public function register();

    public function boot()
    {
        // Phương thức boot có thể được ghi đè nếu cần
    }

    protected function bind($abstract, $concrete = null, $shared = false)
    {
        $this->container->bind($abstract, $concrete, $shared);
    }

    protected function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }
}