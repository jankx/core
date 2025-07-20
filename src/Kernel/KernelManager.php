<?php

namespace Jankx\Kernel;

use Illuminate\Container\Container;
use Jankx\Kernel\FrontendKernel;
use Jankx\Kernel\DashboardKernel;
use Jankx\Kernel\CronKernel;
use Jankx\Kernel\CLIKernel;
use Jankx\Kernel\APIKernel;
use Jankx\Kernel\NotFoundKernel;
use Jankx\Kernel\AjaxKernel;

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
    }

    public function boot()
    {
        if ($this->booted) {
            error_log('Kernel already booted. Skipping boot process.');
            return;
        }

        $this->booted = true;
        $this->determineContextAndBootKernel();
    }

    protected function determineContextAndBootKernel()
    {
        if (defined('WP_CLI') && WP_CLI) {
            $this->currentKernel = $this->container->make(CLIKernel::class);
            error_log('CLI context detected. Booting CLIKernel.');
        } elseif (defined('DOING_CRON') && DOING_CRON) {
            $this->currentKernel = $this->container->make(CronKernel::class);
            error_log('Cron context detected. Booting CronKernel.');
        } elseif (defined('REST_REQUEST') && REST_REQUEST || isset($_GET['rest_route'])) {
            $this->currentKernel = $this->container->make(APIKernel::class);
            error_log('API context detected. Booting APIKernel.');
        } elseif (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
            $this->currentKernel = $this->container->make(AjaxKernel::class);
            error_log('AJAX context detected. Booting AjaxKernel.');
        } elseif (is_admin()) {
            $this->currentKernel = $this->container->make(DashboardKernel::class);
            error_log('Admin context detected. Booting DashboardKernel.');
        } elseif (is_404()) {
            $this->currentKernel = $this->container->make(NotFoundKernel::class);
            error_log('404 context detected. Booting NotFoundKernel.');
        } else {
            $this->currentKernel = $this->container->make(FrontendKernel::class);
            error_log('Frontend context detected. Booting FrontendKernel.');
        }

        if ($this->currentKernel) {
            $this->currentKernel->boot();
        }
    }

    public function getCurrentKernel()
    {
        return $this->currentKernel;
    }

    public function registerKernel(string $type, string $kernelClass): void
    {
        if (!class_exists($kernelClass)) {
            throw new \InvalidArgumentException("Kernel class {$kernelClass} does not exist");
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

    public function bootKernelsByContext(): void
    {
        $context = $this->getCurrentContext();
        error_log("Current context: {$context}");

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

            case '404':
                $this->bootKernel('404');
                break;

            case 'ajax':
                $this->bootKernel('api');
                break;

            default:
                error_log("Unknown context: {$context}, defaulting to frontend");
                $this->bootKernel('frontend');
                break;
        }
        error_log("Booted kernel for context: {$context}");
    }

    protected function getCurrentContext(): string
    {
        if (defined('WP_CLI') && WP_CLI) {
            return 'cli';
        }

        if (defined('DOING_CRON') && DOING_CRON) {
            return 'cron';
        }

        if (defined('REST_REQUEST') && REST_REQUEST || isset($_GET['rest_route'])) {
            return 'api';
        }

        if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
            return 'ajax';
        }

        if (is_admin()) {
            return 'admin';
        }

        if (is_404()) {
            return '404';
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