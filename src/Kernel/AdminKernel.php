<?php

namespace Jankx\Kernel;

use Jankx\Kernel\Interfaces\KernelInterface;
use Jankx\Kernel\Bootstrappers\AdminBootstrapper;
use Jankx\Kernel\Bootstrappers\ThemeBootstrapper;

/**
 * Admin Kernel
 *
 * Handles admin-specific features
 *
 * @package Jankx\Kernel
 */
class AdminKernel extends AbstractKernel implements KernelInterface
{
    /**
     * Get kernel type
     */
    public function getKernelType(): string
    {
        return 'admin';
    }

    /**
     * Register bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        // Theme bootstrapper (highest priority)
        $this->addBootstrapper(ThemeBootstrapper::class);

        // Admin bootstrapper
        $this->addBootstrapper(AdminBootstrapper::class);

        // Allow child themes to add custom bootstrappers
        $customBootstrappers = apply_filters('jankx/admin/bootstrappers', []);
        foreach ($customBootstrappers as $bootstrapper) {
            $this->addBootstrapper($bootstrapper);
        }
    }

    /**
     * Register services
     */
    protected function registerServices(): void
    {
        // Admin-specific services will be registered here
    }

    /**
     * Register hooks
     */
    protected function registerHooks(): void
    {
        // Admin-specific hooks will be registered here
    }

    /**
     * Register filters
     */
    protected function registerFilters(): void
    {
        // Admin-specific filters will be registered here
    }

    /**
     * Boot the kernel
     */
    public function boot(): void
    {
        parent::boot();
        // Additional boot logic for admin if needed
    }

    /**
     * Check if kernel is booted
     */
    public function isBooted(): bool
    {
        return parent::isBooted();
    }
}
