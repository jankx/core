<?php

namespace Jankx\Kernel\Interfaces;

use Illuminate\Container\Container;

/**
 * Bootstrapper Interface
 *
 * @package Jankx\Kernel\Interfaces
 */
interface BootstrapperInterface
{
    /**
     * Bootstrap the application
     */
    public function bootstrap(Container $container): void;

    /**
     * Get bootstrapper priority
     */
    public function getPriority(): int;

    /**
     * Check if bootstrapper should run
     */
    public function shouldRun(): bool;

    /**
     * Get bootstrapper dependencies
     */
    public function getDependencies(): array;
}