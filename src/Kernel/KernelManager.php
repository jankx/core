<?php

namespace Jankx\Kernel;

use Illuminate\Container\Container;
use Jankx\Kernel\Interfaces\KernelInterface;

/**
 * Kernel Manager
 *
 * Manages the registration, initialization, and booting of kernels based on context.
 *
 * @package Jankx\Kernel
 */
class KernelManager
{
    protected $container;
    protected $booted = false;
    protected $currentKernel;
    protected $kernels = [];
    protected $bootedKernels = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->bootstrapSystem();
    }

    protected function bootstrapSystem()
    {
        // Khởi tạo hệ thống bằng CoreBootstrapper trước khi làm bất cứ điều gì khác
        $bootstrapper = new \Jankx\Bootstrap\CoreBootstrapper($this->container);
        $bootstrapper->bootstrap();
    }

    public function boot()
    {
        if ($this->booted) {
            return;
        }

        $this->booted = true;
        $this->determineContextAndBootKernel();
    }

    protected function determineContextAndBootKernel()
    {
        // Ưu tiên CLI context và không load các kernel khác nếu đang ở CLI
        if (defined('WP_CLI') && WP_CLI) {
            $this->currentKernel = $this->container->make(CLIKernel::class);
            $this->currentKernel->boot();
            return; // Dừng lại, không kiểm tra các context khác
        }

        if (wp_doing_cron()) {
            $this->currentKernel = $this->container->make(CronKernel::class);
        } elseif (defined('REST_REQUEST') && REST_REQUEST) {
            $this->currentKernel = $this->container->make(APIKernel::class);
        } elseif (is_admin()) {
            $this->currentKernel = $this->container->make(AdminKernel::class);
        } else {
            $this->currentKernel = $this->container->make(FrontendKernel::class);
        }

        if ($this->currentKernel) {
            $this->currentKernel->boot();
        }
    }

    public function getCurrentKernel()
    {
        return $this->currentKernel;
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

    public function getKernel(string $type)
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

    public function bootKernel(string $type): void
    {
        $kernel = $this->getKernel($type);

        if ($kernel && !$kernel->isBooted()) {
            $kernel->boot();
        }
    }

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
                $this->bootKernel('admin');
                break;

            case 'api':
                $this->bootKernel('api');
                break;

            case 'cli':
                $this->bootKernel('cli');
                break;

            case 'cron':
                $this->bootKernel('cron');
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

        // Check if it's a REST API request
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return 'api';
        }

        return 'frontend';
    }

    public function getAllKernels(): array
    {
        return $this->kernels;
    }

    public function getBootedKernels(): array
    {
        return $this->bootedKernels;
    }

    public function hasKernel(string $type): bool
    {
        return isset($this->kernels[$type]);
    }

    public function isKernelBooted(string $type): bool
    {
        $kernel = $this->getKernel($type);
        return $kernel ? $kernel->isBooted() : false;
    }

    public function removeKernel(string $type): void
    {
        unset($this->kernels[$type]);
        unset($this->bootedKernels[$type]);
    }

    public function getKernelInfo(string $type): array
    {
        $kernel = $this->getKernel($type);

        if (!$kernel) {
            return [];
        }

        return [
            'type' => $type,
            'booted' => $kernel->isBooted(),
            // Các thông tin khác nếu cần
        ];
    }

    public function getAllKernelInfo(): array
    {
        $info = [];

        foreach (array_keys($this->kernels) as $type) {
            $info[$type] = $this->getKernelInfo($type);
        }

        return $info;
    }
}
