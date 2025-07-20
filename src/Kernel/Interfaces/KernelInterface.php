<?php

namespace Jankx\Kernel\Interfaces;

use Illuminate\Container\Container;

/**
 * Kernel Interface
 *
 * @package Jankx\Kernel\Interfaces
 */
interface KernelInterface
{
    /**
     * Boot kernel
     */
    public function boot(): void;

    /**
     * Check if kernel is booted
     */
    public function isBooted(): bool;

    /**
     * Get kernel type
     */
    public function getType(): string;

    /**
     * Get container
     */
    public function getContainer(): Container;

    /**
     * Get services
     */
    public function getServices(): array;

    /**
     * Get hooks
     */
    public function getHooks(): array;

    /**
     * Get filters
     */
    public function getFilters(): array;

    /**
     * Get bootstrappers
     */
    public function getBootstrappers(): array;

    /**
     * Add bootstrapper
     */
    public function addBootstrapper(string $bootstrapper): void;

    /**
     * Remove bootstrapper
     */
    public function removeBootstrapper(string $bootstrapper): void;

    /**
     * Check if bootstrapper exists
     */
    public function hasBootstrapper(string $bootstrapper): bool;
}
