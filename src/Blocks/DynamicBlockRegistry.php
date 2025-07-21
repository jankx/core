<?php

namespace Jankx\Blocks;

use Jankx\Kernel\Interfaces\ServiceRegistryInterface;

/**
 * Dynamic Block Registry
 *
 * Manages the registration and initialization of dynamic Gutenberg Blocks for server-side rendering.
 *
 * @package Jankx\Blocks
 */
class DynamicBlockRegistry implements ServiceRegistryInterface
{
    /**
     * Array of registered dynamic blocks
     *
     * @var array
     */
    protected $blocks = [];

    /**
     * Array of deferred services for blocks
     *
     * @var array
     */
    protected $deferredServices = [];

    /**
     * Register a dynamic block
     *
     * @param string $blockName The name of the block
     * @param string $serviceClass The service class responsible for rendering the block
     * @param bool $deferred Whether the service should be initialized deferred (lazy-loaded)
     */
    public function registerBlock(string $blockName, string $serviceClass, bool $deferred = false): void
    {
        if (!class_exists($serviceClass)) {
            throw new \InvalidArgumentException("Service class {$serviceClass} for block {$blockName} does not exist");
        }

        $this->blocks[$blockName] = [
            'serviceClass' => $serviceClass,
            'deferred' => $deferred,
        ];

        if ($deferred) {
            $this->deferredServices[$blockName] = $serviceClass;
        } else {
            // Initialize non-deferred services immediately
            $this->initializeService($blockName, $serviceClass);
        }
    }

    /**
     * Initialize a service for a block
     *
     * @param string $blockName The name of the block
     * @param string $serviceClass The service class to initialize
     */
    protected function initializeService(string $blockName, string $serviceClass): void
    {
        // This method would typically be called with a container to resolve the service
        // For now, we'll just simulate initialization
        $this->blocks[$blockName]['initialized'] = true;
    }

    /**
     * Scan and register dynamic blocks from a directory
     *
     * @param string $directory The directory to scan for block files
     */
    public function scanAndRegisterBlocks(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = glob($directory . '/*.php');
        foreach ($files as $file) {
            $blockName = basename($file, '.php');
            $className = $this->guessClassName($blockName);

            if (class_exists($className)) {
                $this->registerBlock($blockName, $className, true); // Deferred by default
            }
        }
    }

    /**
     * Guess the class name for a block based on its file name
     *
     * @param string $blockName The name of the block
     * @return string The guessed class name
     */
    protected function guessClassName(string $blockName): string
    {
        // Convert kebab-case or snake_case to CamelCase
        $parts = explode('-', str_replace('_', '-', $blockName));
        $camelCase = array_map('ucfirst', $parts);
        $className = implode('', $camelCase) . 'Block';

        return "Jankx\\Blocks\\Dynamic\\{$className}";
    }

    /**
     * Initialize deferred services before rendering
     */
    public function initializeDeferredServices(): void
    {
        foreach ($this->deferredServices as $blockName => $serviceClass) {
            if (!isset($this->blocks[$blockName]['initialized']) || !$this->blocks[$blockName]['initialized']) {
                $this->initializeService($blockName, $serviceClass);
            }
        }
        // Clear deferred services after initialization
        $this->deferredServices = [];
    }

    /**
     * Get all registered blocks
     *
     * @return array
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * Register services (part of ServiceRegistryInterface)
     */
    public function registerServices(): void
    {
        // Scan and register blocks from the dynamic blocks directory
        $blocksDir = dirname(__DIR__) . '/Blocks/Dynamic';
        $this->scanAndRegisterBlocks($blocksDir);

        // Register sample block
        $this->registerBlock('sample-block', \Jankx\Blocks\Dynamic\SampleBlock::class, true);

        // Allow child themes to register custom blocks
        do_action('jankx_blocks_register_dynamic_blocks', $this);
    }
}
