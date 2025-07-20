<?php

namespace Jankx\Kernel\Bootstrappers;

use Jankx\Jankx;
use Jankx\Kernel\Interfaces\BootstrapperInterface;
use Illuminate\Container\Container;

/**
 * Abstract Bootstrapper
 *
 * Base class for all bootstrappers in Jankx framework
 *
 * @package Jankx\Kernel\Bootstrappers
 */
abstract class AbstractBootstrapper implements BootstrapperInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var int
     */
    protected $priority = 10;

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * Constructor
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?: Jankx::getInstance();
    }

    /**
     * Bootstrap the application
     */
    abstract public function bootstrap(Container $container): void;

    /**
     * Get bootstrapper priority
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Set bootstrapper priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Check if bootstrapper should run
     */
    public function shouldRun(): bool
    {
        return $this->enabled && $this->checkConditions();
    }

    /**
     * Check bootstrapper conditions
     */
    protected function checkConditions(): bool
    {
        return true;
    }

    /**
     * Get bootstrapper dependencies
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * Set bootstrapper dependencies
     */
    public function setDependencies(array $dependencies): void
    {
        $this->dependencies = $dependencies;
    }

    /**
     * Enable bootstrapper
     */
    public function enable(): void
    {
        $this->enabled = true;
    }

    /**
     * Disable bootstrapper
     */
    public function disable(): void
    {
        $this->enabled = false;
    }

    /**
     * Check if bootstrapper is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Get bootstrapper name
     */
    public function getName(): string
    {
        return static::class;
    }

    /**
     * Get bootstrapper description
     */
    public function getDescription(): string
    {
        return 'Bootstrapper for ' . $this->getName();
    }
}
