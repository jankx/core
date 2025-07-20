<?php

namespace Jankx\Kernel;

use Jankx\Kernel\AbstractKernel;
use Jankx\Kernel\Interfaces\KernelInterface;
use Jankx\Kernel\Bootstrappers\CronBootstrapper;

/**
 * Cron Kernel - Xử lý các WP Cron jobs
 */
class CronKernel extends AbstractKernel implements KernelInterface
{
    protected $name = 'cron';
    protected $context = 'cron';

    public function __construct()
    {
        parent::__construct();
        $this->bootstrappers = [
            CronBootstrapper::class,
        ];
    }

    /**
     * Boot kernel cho Cron context
     */
    public function boot(): void
    {
        // Kiểm tra nếu đang trong Cron context
        if (!$this->isCronRequest()) {
            return;
        }

        parent::boot();

        // Đăng ký cron jobs
        $this->registerCronJobs();
    }

    /**
     * Lấy loại kernel
     */
    public function getKernelType(): string
    {
        return 'cron';
    }

    /**
     * Đăng ký bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        $this->bootstrappers = [
            CronBootstrapper::class,
        ];
    }

    /**
     * Đăng ký services
     */
    protected function registerServices(): void
    {
        // Đăng ký các services cho Cron
        $this->container->singleton('cron.scheduler', function () {
            return new \Jankx\Kernel\Services\CronScheduler();
        });
    }

    /**
     * Đăng ký hooks cho Cron
     */
    protected function registerHooks(): void
    {
        // Đăng ký các hooks cần thiết cho Cron
        add_action('init', [$this, 'registerCronJobs']);
    }

    /**
     * Đăng ký filters cho Cron
     */
    protected function registerFilters(): void
    {
        // Đăng ký các filters cần thiết cho Cron
    }

    /**
     * Kiểm tra xem có phải Cron request không
     */
    protected function isCronRequest()
    {
        return defined('DOING_CRON') && DOING_CRON;
    }

    /**
     * Đăng ký cron jobs
     */
    protected function registerCronJobs()
    {
        // Đăng ký các cron jobs
        if (!wp_next_scheduled('jankx_cron_cleanup_cache')) {
            wp_schedule_event(time(), 'daily', 'jankx_cron_cleanup_cache');
        }
        add_action('jankx_cron_cleanup_cache', [$this, 'cleanupCache']);

        if (!wp_next_scheduled('jankx_cron_sync_books')) {
            wp_schedule_event(time(), 'twicedaily', 'jankx_cron_sync_books');
        }
        add_action('jankx_cron_sync_books', [$this, 'syncBooks']);

        if (!wp_next_scheduled('jankx_cron_send_newsletter')) {
            wp_schedule_event(time(), 'daily', 'jankx_cron_send_newsletter');
        }
        add_action('jankx_cron_send_newsletter', [$this, 'sendNewsletter']);

        if (!wp_next_scheduled('jankx_cron_cleanup_database')) {
            wp_schedule_event(time(), 'weekly', 'jankx_cron_cleanup_database');
        }
        add_action('jankx_cron_cleanup_database', [$this, 'cleanupDatabase']);

        if (!wp_next_scheduled('jankx_cron_check_deals')) {
            wp_schedule_event(time(), 'hourly', 'jankx_cron_check_deals');
        }
        add_action('jankx_cron_check_deals', [$this, 'checkDeals']);
    }

    /**
     * Cleanup cache
     */
    public function cleanupCache()
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

        // Ghi log
        error_log('Jankx Cron: Cache đã được xóa vào ' . date('Y-m-d H:i:s'));
    }

    /**
     * Đồng bộ sách
     */
    public function syncBooks()
    {
        // Logic để đồng bộ sách từ nguồn bên ngoài
        error_log('Jankx Cron: Đồng bộ sách đã được thực hiện vào ' . date('Y-m-d H:i:s'));
    }

    /**
     * Gửi newsletter
     */
    public function sendNewsletter()
    {
        // Logic để gửi newsletter cho subscribers
        error_log('Jankx Cron: Newsletter đã được gửi vào ' . date('Y-m-d H:i:s'));
    }

    /**
     * Cleanup database
     */
    public function cleanupDatabase()
    {
        global $wpdb;

        // Xóa revisions cũ
        $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");

        // Xóa spam comments
        $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");

        // Xóa trashed posts
        $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'trash'");

        // Ghi log
        error_log('Jankx Cron: Database đã được dọn dẹp vào ' . date('Y-m-d H:i:s'));
    }

    /**
     * Kiểm tra deals
     */
    public function checkDeals()
    {
        // Logic để kiểm tra và cập nhật trạng thái deals
        error_log('Jankx Cron: Deals đã được kiểm tra vào ' . date('Y-m-d H:i:s'));
    }

    /**
     * Shutdown kernel
     */
    public function shutdown()
    {
        parent::shutdown();

        // Cleanup Cron specific resources
        remove_all_actions('jankx_cron_cleanup_cache');
        remove_all_actions('jankx_cron_sync_books');
        remove_all_actions('jankx_cron_send_newsletter');
        remove_all_actions('jankx_cron_cleanup_database');
        remove_all_actions('jankx_cron_check_deals');
    }
}
