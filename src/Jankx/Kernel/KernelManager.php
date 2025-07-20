<?php

namespace Jankx\Kernel;

use Jankx\Container\Container;
use Jankx\Kernel\Interfaces\KernelInterface;

/**
 * Kernel Manager
 *
 * Manages all kernels in the Jankx framework
 *
 * @package Jankx\Kernel
 */
class KernelManager
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $kernels = [];

    /**
     * @var array
     */
    protected $bootedKernels = [];

    /**
     * Constructor
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?: Jankx::getInstance();
    }

    /**
     * Register kernel
     */
    public function registerKernel(string $type, string $kernelClass): void
    {
        if (!class_exists($kernelClass)) {
            throw new \InvalidArgumentException("Kernel class {$kernelClass} does not exist");
        }

        if (!is_subclass_of($kernelClass, KernelInterface::class)) {
            throw new \InvalidArgumentException("Kernel class {$kernelClass} must implement KernelInterface");
        }

        $this->kernels[$type] = $kernelClass;
    }

    /**
     * Get kernel
     */
    public function getKernel(string $type): ?KernelInterface
    {
        if (!isset($this->kernels[$type])) {
            return null;
        }

        $kernelClass = $this->kernels[$type];

        if (!isset($this->bootedKernels[$type])) {
            $this->bootedKernels[$type] = new $kernelClass($this->container);
        }

        return $this->bootedKernels[$type];
    }

    /**
     * Boot kernel
     */
    public function bootKernel(string $type): void
    {
        $kernel = $this->getKernel($type);

        if ($kernel && !$kernel->isBooted()) {
            $kernel->boot();
        }
    }

    /**
     * Boot all kernels
     */
    public function bootAllKernels(): void
    {
        foreach (array_keys($this->kernels) as $type) {
            $this->bootKernel($type);
        }
    }

    /**
     * Boot kernels by context
     */
    public function bootKernelsByContext(): void
    {
        $context = $this->getCurrentContext();

        switch ($context) {
            case 'frontend':
                $this->bootKernel('frontend');
                break;

            case 'admin':
                $this->bootKernel('dashboard');
                break;

            case 'cron':
                $this->bootKernel('cron');
                break;

            case 'cli':
                $this->bootKernel('cli');
                break;

            default:
                $this->bootKernel('frontend');
                break;
        }
    }

    /**
     * Get current context
     */
    protected function getCurrentContext(): string
    {
        if (defined('WP_CLI') && WP_CLI) {
            return 'cli';
        }

        if (wp_doing_cron()) {
            return 'cron';
        }

        if (is_admin()) {
            return 'admin';
        }

        return 'frontend';
    }

    /**
     * Get all kernels
     */
    public function getAllKernels(): array
    {
        return $this->kernels;
    }

    /**
     * Get booted kernels
     */
    public function getBootedKernels(): array
    {
        return $this->bootedKernels;
    }

    /**
     * Check if kernel is registered
     */
    public function hasKernel(string $type): bool
    {
        return isset($this->kernels[$type]);
    }

    /**
     * Check if kernel is booted
     */
    public function isKernelBooted(string $type): bool
    {
        $kernel = $this->getKernel($type);
        return $kernel ? $kernel->isBooted() : false;
    }

    /**
     * Remove kernel
     */
    public function removeKernel(string $type): void
    {
        unset($this->kernels[$type]);
        unset($this->bootedKernels[$type]);
    }

    /**
     * Get kernel info
     */
    public function getKernelInfo(string $type): array
    {
        $kernel = $this->getKernel($type);

        if (!$kernel) {
            return [];
        }

        return [
            'type' => $kernel->getType(),
            'booted' => $kernel->isBooted(),
            'services' => count($kernel->getServices()),
            'hooks' => count($kernel->getHooks()),
            'filters' => count($kernel->getFilters()),
            'bootstrappers' => count($kernel->getBootstrappers()),
        ];
    }

    /**
     * Get all kernel info
     */
    public function getAllKernelInfo(): array
    {
        $info = [];

        foreach (array_keys($this->kernels) as $type) {
            $info[$type] = $this->getKernelInfo($type);
        }

        return $info;
    }
}