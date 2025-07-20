<?php

namespace Jankx\Kernel\Bootstrappers;

use Jankx\Asset\AssetManager;
use Jankx\Asset\Bucket;

/**
 * Asset Bootstrapper
 *
 * Bootstrap asset management system
 *
 * @package Jankx\Kernel\Bootstrappers
 */
class AssetBootstrapper extends AbstractBootstrapper
{
    /**
     * @var int
     */
    protected $priority = 5;

    /**
     * @var array
     */
    protected $dependencies = [
        'Jankx\Asset\AssetManager',
        'Jankx\Asset\Bucket'
    ];

    /**
     * Bootstrap the application
     */
    public function bootstrap($container): void
    {
        // Initialize asset manager
        $assetManager = AssetManager::instance();
        $container->instance(AssetManager::class, $assetManager);

        // Initialize bucket
        $bucket = Bucket::instance();
        $container->instance(Bucket::class, $bucket);

        // Register asset hooks
        add_action('wp_enqueue_scripts', [$this, 'registerAssets'], 20);
        add_action('admin_enqueue_scripts', [$this, 'registerAdminAssets'], 20);

        do_action('jankx/bootstrapper/asset/loaded', $assetManager);
    }

    /**
     * Check conditions
     */
    protected function checkConditions(): bool
    {
        return wp_is_request('frontend') || is_admin();
    }

    /**
     * Register assets
     */
    public function registerAssets(): void
    {
        // Bỏ qua vì phương thức không tồn tại
        // $assetManager = $this->container->make(AssetManager::class);
        // $assetManager->registerAssets();
    }

    /**
     * Register admin assets
     */
    public function registerAdminAssets(): void
    {
        // Bỏ qua vì phương thức không tồn tại
        // $assetManager = $this->container->make(AssetManager::class);
        // $assetManager->registerAdminAssets();
    }

    /**
     * Get description
     */
    public function getDescription(): string
    {
        return 'Bootstrap asset management system';
    }
}
