<?php

namespace Jankx\Blocks;

use Jankx\Jankx;

/**
 * Block Manager
 *
 * Manages the initialization and rendering of Gutenberg Blocks in Jankx framework.
 *
 * @package Jankx\Blocks
 */
class BlockManager
{
    /**
     * Instance of DynamicBlockRegistry
     *
     * @var DynamicBlockRegistry
     */
    protected $dynamicRegistry;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dynamicRegistry = new DynamicBlockRegistry();
    }

    /**
     * Initialize block system
     */
    public function init(): void
    {
        // Register services for dynamic blocks
        $this->dynamicRegistry->registerServices();

        // Hook into WordPress to initialize deferred services before rendering
        add_action('render_block', [$this, 'beforeRenderBlock'], 10, 3);
    }

    /**
     * Called before a block is rendered
     *
     * @param string $block_content The block content about to be rendered
     * @param array $block The block data being rendered
     * @param WP_Block $block_instance The block instance
     * @return string
     */
    public function beforeRenderBlock($block_content, $block, $block_instance): string
    {
        // Initialize deferred services before rendering any block
        $this->dynamicRegistry->initializeDeferredServices();

        return $block_content;
    }

    /**
     * Get the dynamic block registry
     *
     * @return DynamicBlockRegistry
     */
    public function getDynamicRegistry(): DynamicBlockRegistry
    {
        return $this->dynamicRegistry;
    }

    /**
     * Register the block manager in the container
     */
    public static function register(): void
    {
        $container = \Jankx\Jankx::getInstance();
        $container->singleton(BlockManager::class, function () {
            return new BlockManager();
        });

        // Initialize the block system
        $blockManager = $container->make(BlockManager::class);
        $blockManager->init();
    }
}