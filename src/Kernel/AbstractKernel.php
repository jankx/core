<?php

namespace Jankx\Kernel;

use Jankx\Jankx;
use Jankx\Kernel\Interfaces\KernelInterface;
use Jankx\Kernel\Interfaces\BootstrapperInterface;
use Illuminate\Container\Container;

/**
 * Abstract Kernel Class
 *
 * Base class for all kernel types in Jankx framework
 *
 * @package Jankx\Kernel
 */
abstract class AbstractKernel implements KernelInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var array
     */
    protected $hooks = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $bootstrappers = [];

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @var string
     */
    protected $kernelType;

    protected $serviceProviders = [];

    /**
     * Constructor
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?: Jankx::getInstance();
        $this->kernelType = $this->getKernelType();

        $this->registerBootstrappers();
        $this->registerServices();
        $this->registerHooks();
        $this->registerFilters();
    }

    /**
     * Get kernel type
     */
    abstract protected function getKernelType(): string;

    /**
     * Register bootstrappers
     */
    abstract protected function registerBootstrappers(): void;

    /**
     * Register services
     */
    abstract protected function registerServices(): void;

    /**
     * Register hooks
     */
    abstract protected function registerHooks(): void;

    /**
     * Register filters
     */
    abstract protected function registerFilters(): void;

    /**
     * Boot kernel
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        // Run bootstrappers
        $this->runBootstrappers();

        // Load components
        $this->loadServices();
        $this->loadHooks();
        $this->loadFilters();

        $this->booted = true;

        do_action("jankx/kernel/{$this->kernelType}/booted", $this);
    }

    /**
     * Check if kernel is booted
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Get kernel type
     */
    public function getType(): string
    {
        return $this->kernelType;
    }

    /**
     * Get container
     */
    public function getContainer(): \Illuminate\Container\Container
    {
        return $this->container;
    }

    /**
     * Get services
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * Get hooks
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }

    /**
     * Get filters
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Get bootstrappers
     */
    public function getBootstrappers(): array
    {
        return $this->bootstrappers;
    }

    /**
     * Add bootstrapper
     */
    public function addBootstrapper(string $bootstrapper): void
    {
        if (!in_array($bootstrapper, $this->bootstrappers)) {
            $this->bootstrappers[] = $bootstrapper;
        }
    }

    /**
     * Remove bootstrapper
     */
    public function removeBootstrapper(string $bootstrapper): void
    {
        $key = array_search($bootstrapper, $this->bootstrappers);
        if ($key !== false) {
            unset($this->bootstrappers[$key]);
        }
    }

    /**
     * Check if bootstrapper exists
     */
    public function hasBootstrapper(string $bootstrapper): bool
    {
        return in_array($bootstrapper, $this->bootstrappers);
    }

    /**
     * Run bootstrappers
     */
    protected function runBootstrappers(): void
    {
        // Sort bootstrappers by priority
        $sortedBootstrappers = $this->sortBootstrappersByPriority();

        foreach ($sortedBootstrappers as $bootstrapperClass) {
            if (!class_exists($bootstrapperClass)) {
                continue;
            }

            $bootstrapper = $this->container->make($bootstrapperClass);

            if (!$bootstrapper instanceof BootstrapperInterface) {
                continue;
            }

            // Check dependencies
            if (!$this->checkBootstrapperDependencies($bootstrapper)) {
                continue;
            }

            // Check if should run
            if (!$bootstrapper->shouldRun()) {
                continue;
            }

            $bootstrapper->bootstrap($this->container);
        }
    }

    /**
     * Sort bootstrappers by priority
     */
    protected function sortBootstrappersByPriority(): array
    {
        $bootstrappersWithPriority = [];

        foreach ($this->bootstrappers as $bootstrapperClass) {
            if (!class_exists($bootstrapperClass)) {
                continue;
            }

            $bootstrapper = $this->container->make($bootstrapperClass);

            if (!$bootstrapper instanceof BootstrapperInterface) {
                continue;
            }

            $bootstrappersWithPriority[] = [
                'class' => $bootstrapperClass,
                'priority' => $bootstrapper->getPriority()
            ];
        }

        // Sort by priority (lower number = higher priority)
        usort($bootstrappersWithPriority, function ($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });

        return array_column($bootstrappersWithPriority, 'class');
    }

    /**
     * Check bootstrapper dependencies
     */
    protected function checkBootstrapperDependencies(BootstrapperInterface $bootstrapper): bool
    {
        $dependencies = $bootstrapper->getDependencies();

        foreach ($dependencies as $dependency) {
            if (!class_exists($dependency) && !function_exists($dependency)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Load services
     */
    protected function loadServices()
    {
        foreach ($this->getServiceProviders() as $providerClass) {
            if (class_exists($providerClass)) {
                try {
                    $provider = new $providerClass($this->container);
                    $provider->register();
                    $provider->boot();
                } catch (\Exception $e) {
                    error_log(sprintf("%s: Không thể khởi tạo Service Provider {$providerClass}: " . $e->getMessage(), get_class($this)));
                }
            } else {
                error_log(sprintf("%s: Service Provider {$providerClass} không tồn tại", get_class($this)));
            }
        }
    }

    /**
     * Load hooks
     */
    protected function loadHooks(): void
    {
        foreach ($this->hooks as $hook) {
            if (isset($hook['hook'], $hook['callback'], $hook['priority'])) {
                add_action($hook['hook'], $hook['callback'], $hook['priority'], $hook['args'] ?? 1);
            }
        }
    }

    /**
     * Load filters
     */
    protected function loadFilters(): void
    {
        foreach ($this->filters as $filter) {
            if (isset($filter['filter'], $filter['callback'], $filter['priority'])) {
                add_filter($filter['filter'], $filter['callback'], $filter['priority'], $filter['args'] ?? 1);
            }
        }
    }

    /**
     * Add service
     */
    protected function addService($service, array $params = []): void
    {
        if (is_string($service)) {
            $this->services[] = ['class' => $service, 'params' => $params];
        } else {
            $this->services[] = $service;
        }
    }

    /**
     * Add hook
     */
    protected function addHook(string $hook, $callback, int $priority = 10, int $args = 1): void
    {
        $this->hooks[] = [
            'hook' => $hook,
            'callback' => $callback,
            'priority' => $priority,
            'args' => $args
        ];
    }

    /**
     * Add filter
     */
    protected function addFilter(string $filter, $callback, int $priority = 10, int $args = 1): void
    {
        $this->filters[] = [
            'filter' => $filter,
            'callback' => $callback,
            'priority' => $priority,
            'args' => $args
        ];
    }

    protected function getServiceProviders(): array
    {
        return $this->serviceProviders;
    }
}
