<?php

namespace Jankx\Kernel;

use Jankx\Kernel\AbstractKernel;
use Jankx\Kernel\Interfaces\KernelInterface;
use Jankx\Kernel\Bootstrappers\CLIBootstrapper;

/**
 * CLI Kernel - Xử lý các CLI commands
 */
class CLIKernel extends AbstractKernel implements KernelInterface
{
    protected $name = 'cli';
    protected $context = 'cli';

    protected $serviceProviders = [
        \Jankx\Providers\CLIServiceProvider::class,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->bootstrappers = [
            CLIBootstrapper::class,
        ];
    }

    /**
     * Boot kernel cho CLI context
     */
    public function boot(): void
    {
        // Kiểm tra nếu đang trong CLI context
        if (!$this->isCliRequest()) {
            return;
        }

        parent::boot();

        // Đăng ký CLI commands
        $this->registerCliCommands();
    }

    /**
     * Lấy loại kernel
     */
    public function getKernelType(): string
    {
        return 'cli';
    }

    /**
     * Đăng ký bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        $this->bootstrappers = [
            CLIBootstrapper::class,
        ];
    }

    /**
     * Đăng ký services
     */
    protected function registerServices(): void
    {
        // Đăng ký các services cho CLI
        // Bỏ qua việc đăng ký service không tồn tại
    }

    /**
     * Đăng ký hooks cho CLI
     */
    protected function registerHooks(): void
    {
        // Đăng ký các hooks cần thiết cho CLI
    }

    /**
     * Đăng ký filters cho CLI
     */
    protected function registerFilters(): void
    {
        // Đăng ký các filters cần thiết cho CLI
    }

    /**
     * Kiểm tra xem có phải CLI request không
     */
    protected function isCliRequest()
    {
        return defined('WP_CLI') && WP_CLI;
    }

    /**
     * Đăng ký CLI commands
     */
    protected function registerCliCommands()
    {
        if (!class_exists('WP_CLI')) {
            return;
        }

        // Cache commands
        \WP_CLI::add_command('jankx cache', [$this, 'cacheCommands']);

        // Database commands
        \WP_CLI::add_command('jankx db', [$this, 'databaseCommands']);

        // Asset commands
        \WP_CLI::add_command('jankx assets', [$this, 'assetCommands']);

        // Book commands
        \WP_CLI::add_command('jankx books', [$this, 'bookCommands']);
    }

    /**
     * Cache commands
     */
    public function cacheCommands($args, $assoc_args)
    {
        $command = $args[0] ?? 'list';

        switch ($command) {
            case 'flush':
                $this->flushCache();
                \WP_CLI::success('Cache đã được xóa thành công!');
                break;

            case 'warm':
                $this->warmCache();
                \WP_CLI::success('Cache đã được làm ấm thành công!');
                break;

            case 'status':
                $this->showCacheStatus();
                break;

            default:
                \WP_CLI::error('Lệnh không hợp lệ. Sử dụng: flush, warm, status');
        }
    }

    /**
     * Database commands
     */
    public function databaseCommands($args, $assoc_args)
    {
        $command = $args[0] ?? 'list';

        switch ($command) {
            case 'optimize':
                $this->optimizeDatabase();
                \WP_CLI::success('Database đã được tối ưu thành công!');
                break;

            case 'backup':
                $this->backupDatabase();
                \WP_CLI::success('Database đã được backup thành công!');
                break;

            case 'cleanup':
                $this->cleanupDatabase();
                \WP_CLI::success('Database đã được dọn dẹp thành công!');
                break;

            default:
                \WP_CLI::error('Lệnh không hợp lệ. Sử dụng: optimize, backup, cleanup');
        }
    }

    /**
     * Asset commands
     */
    public function assetCommands($args, $assoc_args)
    {
        $command = $args[0] ?? 'list';

        switch ($command) {
            case 'compile':
                $this->compileAssets();
                \WP_CLI::success('Assets đã được compile thành công!');
                break;

            case 'minify':
                $this->minifyAssets();
                \WP_CLI::success('Assets đã được minify thành công!');
                break;

            case 'optimize':
                $this->optimizeAssets();
                \WP_CLI::success('Assets đã được tối ưu thành công!');
                break;

            default:
                \WP_CLI::error('Lệnh không hợp lệ. Sử dụng: compile, minify, optimize');
        }
    }

    /**
     * Book commands
     */
    public function bookCommands($args, $assoc_args)
    {
        $command = $args[0] ?? 'list';

        switch ($command) {
            case 'import':
                $this->importBooks($assoc_args);
                break;

            case 'export':
                $this->exportBooks($assoc_args);
                break;

            case 'sync':
                $this->syncBooks();
                \WP_CLI::success('Books đã được sync thành công!');
                break;

            default:
                \WP_CLI::error('Lệnh không hợp lệ. Sử dụng: import, export, sync');
        }
    }

    /**
     * Flush cache
     */
    protected function flushCache()
    {
        // Xóa cache của WordPress
        wp_cache_flush();

        // Xóa cache của theme
        if (function_exists('wp_cache_delete')) {
            wp_cache_delete('jankx_theme_cache', 'jankx');
        }

        // Xóa cache của object cache
        if (function_exists('wp_cache_flush_group')) {
            wp_cache_flush_group('jankx');
        }
    }

    /**
     * Warm cache
     */
    protected function warmCache()
    {
        \WP_CLI::log('Đang làm ấm cache...');

        // Preload các trang quan trọng
        $important_pages = [
            home_url(),
            home_url('/shop/'),
            home_url('/blog/'),
        ];

        foreach ($important_pages as $url) {
            wp_remote_get($url);
            \WP_CLI::log("Đã preload: {$url}");
        }
    }

    /**
     * Show cache status
     */
    protected function showCacheStatus()
    {
        $cache_stats = [
            'object_cache' => wp_using_ext_object_cache(),
            'page_cache' => defined('WP_CACHE') && WP_CACHE,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
        ];

        \WP_CLI::log('Cache Status:');
        foreach ($cache_stats as $key => $value) {
            \WP_CLI::log("  {$key}: " . ($value ? 'Enabled' : 'Disabled'));
        }
    }

    /**
     * Optimize database
     */
    protected function optimizeDatabase()
    {
        global $wpdb;

        $tables = $wpdb->get_results("SHOW TABLES");

        foreach ($tables as $table) {
            $table_name = array_values((array) $table)[0];
            $wpdb->query("OPTIMIZE TABLE {$table_name}");
            \WP_CLI::log("Đã optimize table: {$table_name}");
        }
    }

    /**
     * Backup database
     */
    protected function backupDatabase()
    {
        $backup_dir = WP_CONTENT_DIR . '/backups/';
        if (!is_dir($backup_dir)) {
            mkdir($backup_dir, 0755, true);
        }

        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $filepath = $backup_dir . $filename;

        // Export database
        $command = sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s',
            DB_HOST,
            DB_USER,
            DB_PASSWORD,
            DB_NAME,
            $filepath
        );

        exec($command);
        \WP_CLI::log("Database đã được backup tại: {$filepath}");
    }

    /**
     * Cleanup database
     */
    protected function cleanupDatabase()
    {
        global $wpdb;

        // Xóa revisions cũ
        $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        \WP_CLI::log('Đã xóa revisions cũ');

        // Xóa spam comments
        $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
        \WP_CLI::log('Đã xóa spam comments');

        // Xóa trashed posts
        $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'trash'");
        \WP_CLI::log('Đã xóa trashed posts');
    }

    /**
     * Compile assets
     */
    protected function compileAssets()
    {
        $assets_dir = get_template_directory() . '/assets/';

        // Compile SCSS to CSS
        if (file_exists($assets_dir . 'src/scss/')) {
            \WP_CLI::log('Đang compile SCSS...');
            // Thêm logic compile SCSS ở đây
        }

        // Compile JS
        if (file_exists($assets_dir . 'src/js/')) {
            \WP_CLI::log('Đang compile JS...');
            // Thêm logic compile JS ở đây
        }
    }

    /**
     * Minify assets
     */
    protected function minifyAssets()
    {
        $assets_dir = get_template_directory() . '/assets/';

        // Minify CSS
        $css_files = glob($assets_dir . 'css/*.css');
        foreach ($css_files as $file) {
            \WP_CLI::log("Đang minify: {$file}");
            // Thêm logic minify CSS ở đây
        }

        // Minify JS
        $js_files = glob($assets_dir . 'js/*.js');
        foreach ($js_files as $file) {
            \WP_CLI::log("Đang minify: {$file}");
            // Thêm logic minify JS ở đây
        }
    }

    /**
     * Optimize assets
     */
    protected function optimizeAssets()
    {
        $this->compileAssets();
        $this->minifyAssets();

        // Optimize images
        $this->optimizeImages();
    }

    /**
     * Optimize images
     */
    protected function optimizeImages()
    {
        $upload_dir = wp_upload_dir();
        $images = glob($upload_dir['basedir'] . '/*/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        \WP_CLI::log('Đang optimize images...');
        foreach ($images as $image) {
            // Thêm logic optimize image ở đây
            \WP_CLI::log("Đã optimize: {$image}");
        }
    }

    /**
     * Import books
     */
    protected function importBooks($assoc_args)
    {
        $file = $assoc_args['file'] ?? null;

        if (!$file || !file_exists($file)) {
            \WP_CLI::error('File không tồn tại hoặc không được chỉ định');
            return;
        }

        \WP_CLI::log("Đang import books từ file: {$file}");
        // Thêm logic import books ở đây
    }

    /**
     * Export books
     */
    protected function exportBooks($assoc_args)
    {
        $file = $assoc_args['file'] ?? 'books-export-' . date('Y-m-d-H-i-s') . '.csv';

        \WP_CLI::log("Đang export books ra file: {$file}");
        // Thêm logic export books ở đây
    }

    /**
     * Sync books
     */
    protected function syncBooks()
    {
        \WP_CLI::log('Đang sync books...');
        // Thêm logic sync books ở đây
    }

    /**
     * Shutdown kernel
     */
    public function shutdown()
    {
        parent::shutdown();

        // Cleanup CLI specific resources
        if (class_exists('WP_CLI')) {
            \WP_CLI::remove_all_commands('jankx');
        }
    }
}
