<?php

namespace Jankx\Kernel\Interfaces;

use Illuminate\Container\Container;

/**
 * Kernel Interface
 *
 * Defines the contract for kernel classes in the Jankx framework.
 *
 * @package Jankx\Kernel\Interfaces
 */
interface KernelInterface
{
    /**
     * Constructor
     *
     * @param Container $container The dependency injection container
     */
    public function __construct(Container $container);

    /**
     * Boot the kernel
     */
    public function boot(): void;

    /**
     * Check if the kernel is booted
     *
     * @return bool
     */
    public function isBooted(): bool;

    /**
     * Get the kernel type
     *
     * @return string
     */
    public function getKernelType(): string;

    /**
     * Get container
     *
     * @return \Illuminate\Container\Container
     */
    public function getContainer(): \Illuminate\Container\Container;
}
